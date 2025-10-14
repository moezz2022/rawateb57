<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\DeletedMessage;
use App\Models\SaveMessage;
use App\Models\PermanentlyDeletedMessage;
use App\Models\Group;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mainGroup = $user->mainGroup;
        $subGroups = $user->subGroup ? collect([$user->subGroup]) : collect();

        if (!$mainGroup) {
            return redirect()->route('dashboard')->with('error', 'لا توجد مجموعة مرتبطة بحسابك.');
        }

        $groupIds = $subGroups->pluck('id')->toArray();
        $groupIds[] = $mainGroup->id;

        $permanentlyDeletedMessageIds = PermanentlyDeletedMessage::where('user_id', $user->id)->pluck('message_id')->toArray();
        $deletedMessageIds = DeletedMessage::where('user_id', $user->id)->pluck('message_id')->toArray();
        $savedMessageIds = SaveMessage::where('user_id', $user->id)->pluck('message_id')->toArray();

        $messages = Message::whereHas('groups', function ($query) use ($groupIds) {
            $query->whereIn('group_id', $groupIds);
        })
            ->with([
                'groups' => function ($query) use ($groupIds) {
                    $query->whereIn('group_id', $groupIds)
                        ->withPivot('is_read');
                },
                'sender.subGroup',
                'sender.mainGroup',
                'attachments'
            ])
            ->whereNotIn('id', $deletedMessageIds)
            ->whereNotIn('id', $permanentlyDeletedMessageIds)
            ->whereNotIn('id', $savedMessageIds)
            ->orderByRaw("
            (SELECT MIN(gm.is_read)
             FROM message_group gm
             WHERE gm.message_id = messages.id
               AND gm.group_id IN (" . implode(',', array_map('intval', $groupIds)) . ")
            ) ASC,
            created_at DESC
        ")
            ->paginate(5);

        $messages->getCollection()->transform(function ($message) {
            $group = $message->groups->first();
            $message->is_read_status = $group ? $group->pivot->is_read : 0;
            return $message;
        });

        return view('dashboard', compact('messages'));
    }

    public function latest()
    {
        $user = auth()->user();
        $mainGroup = $user->mainGroup;
        $subGroups = $user->subGroup ? collect([$user->subGroup]) : collect();

        if (!$mainGroup) {
            return response()->json(['messages' => []]);
        }

        $groupIds = $subGroups->pluck('id')->toArray();
        $groupIds[] = $mainGroup->id;

        $permanentlyDeletedMessageIds = PermanentlyDeletedMessage::where('user_id', $user->id)->pluck('message_id')->toArray();
        $deletedMessageIds = DeletedMessage::where('user_id', $user->id)->pluck('message_id')->toArray();
        $savedMessageIds = SaveMessage::where('user_id', $user->id)->pluck('message_id')->toArray();

        $messages = Message::whereHas('groups', function ($query) use ($groupIds) {
            $query->whereIn('group_id', $groupIds);
        })
            ->with([
                'groups' => function ($query) use ($groupIds) {
                    $query->whereIn('group_id', $groupIds)
                        ->withPivot('is_read');
                },
                'sender.subGroup',
                'sender.mainGroup',
                'attachments'
            ])
            ->whereNotIn('id', $deletedMessageIds)
            ->whereNotIn('id', $permanentlyDeletedMessageIds)
            ->whereNotIn('id', $savedMessageIds)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($msg) {
                $group = $msg->groups->first();
                $msg->is_read_status = $group ? $group->pivot->is_read : 0;
                $msg->formatted_date = $msg->created_at->format('H:i Y-m-d');
                return $msg;
            });

        return response()->json(['messages' => $messages]);
    }



    public function inbox()
    {
        $user = auth()->user();
        $mainGroup = $user->mainGroup;
        $subGroups = $user->subGroup ? collect([$user->subGroup]) : collect();
        if (!$mainGroup) {
            return redirect()->route('home')->with('error', 'لا توجد مجموعة مرتبطة بحسابك.');
        }
        $groupIds = $subGroups ? $subGroups->pluck('id')->toArray() : [];
        $groupIds[] = $mainGroup->id;

        $permanentlyDeletedMessageIds = PermanentlyDeletedMessage::where('user_id', $user->id)->pluck('message_id')->toArray();
        $deletedMessageIds = DeletedMessage::where('user_id', $user->id)->pluck('message_id')->toArray();
        $savedMessageIds = SaveMessage::where('user_id', $user->id)->pluck('message_id')->toArray(); // جلب الرسائل المحفوظة
        $messages = Message::whereHas('groups', function ($query) use ($groupIds) {
            $query->whereIn('group_id', $groupIds);
        })
            ->whereNotIn('id', $deletedMessageIds)
            ->whereNotIn('id', $permanentlyDeletedMessageIds)
            ->whereNotIn('id', $savedMessageIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('messages.inbox', compact('messages'));
    }
    public function outbox()
    {
        $user = auth()->user();

        $messages = Message::with(['sender', 'groups'])
            ->whereNotIn('id', DeletedMessage::where('user_id', $user->id)->pluck('message_id'))
            ->whereNotIn('id', PermanentlyDeletedMessage::where('user_id', $user->id)->pluck('message_id'))
            ->whereNotIn('id', SaveMessage::where('user_id', $user->id)->pluck('message_id'))
            ->where(function ($query) use ($user) {
                if ($user->role === 'director') {
                    // المدير يشوف رسائله + رسائل المقتصد في نفس المؤسسة
                    $query->where('sender_id', $user->id)
                        ->orWhereHas('sender', function ($q) use ($user) {
                        $q->where('role', 'manager')
                            ->where('sub_group', $user->sub_group);
                    });
                } else {
                    // أي مستخدم آخر يشوف فقط رسائله
                    $query->where('sender_id', $user->id);
                }
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('messages.outbox', compact('messages'));
    }



    public function create()
    {
        $adminGroups = $this->getCachedGroups('admin');
        $educationGroups = $this->getCachedGroups('education');
        $inspectionGroups = $this->getCachedGroups('inspection');
        return view('messages.create', compact('adminGroups', 'educationGroups', 'inspectionGroups'));
    }
    private function getCachedGroups($type)
    {
        return Cache::remember("{$type}_groups", 60, function () use ($type) {
            $groups = Group::where('type', $type)
                ->whereNull('parent_id')
                ->get();
            foreach ($groups as $group) {
                $group->children = $group->children->filter(function ($child) use ($group) {
                    return $child->name !== $group->name;
                });
            }

            return $groups;
        });
    }


    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'receiver_group_id' => 'required|exists:groups,id',
            'attachments.*' => 'nullable|file|mimes:zip,rar,pdf,doc,docx,xlsx,xlsm,xls,csv,png,jpg,jpeg|max:30720',
        ], [
            'subject.required' => 'حقل الموضوع مطلوب.',
            'receiver_group_id.required' => 'يرجى اختيار المستلم.',
            'attachments.*.file' => 'يجب أن يكون المرفق ملف صالح.',
            'attachments.*.mimes' => 'نوع الملف غير مدعوم، يرجى رفع ملفات من نوع (zip,rar,pdf,doc,docx,xlsx,xlsm,xls,csv,png,jpg,jpeg).',
            'attachments.*.max' => 'حجم الملف المرفق لا يجب أن يتجاوز 30 ميغابايت.',
        ]);
        try {
            $receiverIds = array_map('trim', explode(',', $request->input('receiver_group_id')));
            $isMultiple = count($receiverIds) > 1 ? 1 : 0;
            $existingGroups = Group::whereIn('id', $receiverIds)->pluck('id')->toArray();
            $invalidGroups = array_diff($receiverIds, $existingGroups);
            if (!empty($invalidGroups)) {
                return redirect()->back()->with('error', 'بعض المجموعات غير صالحة أو لا وجود لها.');
            }
            $attachments = [];
            DB::transaction(function () use ($request, &$attachments, $receiverIds, $isMultiple) {
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $originalName = $file->getClientOriginalName();
                        $path = $file->storeAs('attachments', $originalName, 'public');
                        $attachments[] = $originalName;
                    }
                }
                $message = Message::create([
                    'sender_id' => auth()->user()->id,
                    'subject' => $request->input('subject'),
                    'body' => clean($request->input('body')),
                    'is_multiple' => $isMultiple,
                ]);
                $message->groups()->attach($receiverIds);
                foreach ($attachments as $attachment) {
                    $message->attachments()->create(['filename' => $attachment]);
                }
            });
            return redirect()->route('messages.success');
        } catch (\Exception $e) {
            Log::error('خطأ أثناء إرسال الرسالة: ' . $e->getMessage());
            return redirect()->back()->with('error', 'فشل في إرسال الرسالة: ' . $e->getMessage());
        }
    }
    public function show($slug)
    {
        $message = Message::where('slug', $slug)
            ->with(['sender.subGroup', 'sender.mainGroup', 'groups.parent', 'attachments'])
            ->firstOrFail();

        // تحديث حالة الاطلاع إذا كان المستخدم ضمن المجموعات
        $userGroupId = auth()->user()->groups->pluck('id')->first();
        if ($userGroupId && $message->groups->contains($userGroupId)) {
            $message->groups()->updateExistingPivot($userGroupId, ['is_read' => 1]);
        }

        $message->refresh();

        // المرسل
        $senderGroup = $message->sender
            ? (optional($message->sender->subGroup)->name
                ?? optional($message->sender->mainGroup)->name
                ?? 'المجموعة غير محددة')
            : 'المرسل غير موجود';

        // التحقق إذا فيه مجموعات فرعية جزئية
        $mainGroups = $message->groups->pluck('parent_id')->unique();
        $hasPartialSubGroups = $mainGroups->contains(function ($parentId) use ($message) {
            return $message->groups->where('parent_id', $parentId)->count() !==
                Group::where('parent_id', $parentId)->count();
        });

        // المستقبلين
        $receiverGroups = $hasPartialSubGroups
            ? $message->groups->pluck('name')->toArray()
            : $message->groups
                ->map(fn($g) => optional($g->parent)->name)
                ->filter()
                ->unique()
                ->values()
                ->toArray();

        // المرفقات
        $attachments = $message->attachments->pluck('filename')->toArray();

        // التاريخ بالعربي
        $formattedDate = $message->created_at->locale('ar')->translatedFormat('l d-m-Y h:i a');

        return view('messages.show', compact(
            'message',
            'senderGroup',
            'hasPartialSubGroups',
            'receiverGroups',
            'attachments',
            'formattedDate'
        ));
    }


    public function manage(Request $request)
    {
        if ($request->_method === 'DELETE') {
            if ($request->has('message_ids')) {
                $deletedCount = Message::withTrashed()->whereIn('id', $request->message_ids)->forceDelete();
                if ($deletedCount > 0) {
                    return redirect()->back()->with('success', 'تم حذف الرسائل بنجاح.');
                }
                return redirect()->back()->with('error', 'لم يتم العثور على أي رسائل للحذف.');
            }
            return redirect()->back()->with('error', 'لم يتم تحديد أي رسائل للحذف.');
        }
    }
    public function delete(Request $request)
    {
        $messageIds = $request->input('message_ids');
        if (empty($messageIds)) {
            return redirect()->back()->with('error', 'لم يتم تحديد أي رسائل للحذف.');
        }
        foreach ($messageIds as $messageId) {
            $message = Message::find($messageId);
            if ($message) {
                DeletedMessage::create([
                    'message_id' => $messageId,
                    'user_id' => auth()->id(),
                ]);
            }
        }
        return redirect()->back()->with('success', 'تم حذف الرسائل بنجاح.');
    }
    public function trash()
    {
        $user = auth()->user();
        $deletedMessages = DeletedMessage::where('user_id', $user->id)
            ->with('message.groups')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('messages.trash', ['messages' => $deletedMessages]);
    }
    public function restore(Request $request)
    {
        $messageIds = $request->input('message_ids');
        $userId = auth()->id();
        if (empty($messageIds)) {
            return redirect()->route('messages.trash')->with('error', 'لم يتم تحديد أي رسائل للاستعادة.');
        }
        DeletedMessage::whereIn('message_id', $messageIds)
            ->where('user_id', $userId)
            ->delete();
        return redirect()->route('messages.trash')->with('success', 'تمت استعادة الرسائل بنجاح.');
    }
    public function permanentlyDelete(Request $request)
    {
        $messageIds = $request->input('message_ids');
        if (empty($messageIds)) {
            return redirect()->back()->with('error', 'لم يتم تحديد أي رسائل للحذف النهائي.');
        }
        foreach ($messageIds as $messageId) {
            $deletedMessage = DeletedMessage::where('message_id', $messageId)
                ->where('user_id', auth()->id())
                ->first();
            if ($deletedMessage) {
                PermanentlyDeletedMessage::create([
                    'message_id' => $deletedMessage->message_id,
                    'user_id' => auth()->id(),
                ]);
                $deletedMessage->delete();
            }
        }
        return redirect()->back()->with('success', 'تم حذف الرسائل نهائيًا.');
    }
    public function forward($slug)
    {
        $messages = Message::where('slug', $slug)->firstOrFail();

        $adminGroups = Group::where('type', 'admin')->whereNull('parent_id')->get();
        $educationGroups = Group::where('type', 'education')->whereNull('parent_id')->get();
        $inspectionGroups = Group::where('type', 'inspection')->whereNull('parent_id')->get();

        return view('messages.forward', compact('messages', 'adminGroups', 'educationGroups', 'inspectionGroups'));
    }

    public function forwardStore(Request $request, $messageId)
    {
        $request->validate([
            'receiver_group_id' => 'required|string',
        ], [
            'receiver_group_id.required' => 'يرجى تحديد مجموعة المستلمين.',
        ]);
        try {
            $originalMessage = Message::findOrFail($messageId);
            $receiverIds = array_map('trim', explode(',', $request->input('receiver_group_id')));
            $isMultiple = count($receiverIds) > 1 ? 1 : 0;
            $existingGroups = Group::whereIn('id', $receiverIds)->pluck('id')->toArray();
            $invalidGroups = array_diff($receiverIds, $existingGroups);
            if (!empty($invalidGroups)) {
                return redirect()->back()->with('error', 'بعض المجموعات غير صالحة أو لا وجود لها.');
            }
            $newMessageData = [
                'sender_id' => auth()->user()->id,
                'subject' => $originalMessage->subject,
                'body' => $originalMessage->body,
                'is_multiple' => $isMultiple,
            ];

            DB::transaction(function () use ($newMessageData, $receiverIds, $originalMessage) {
                $newMessage = Message::create($newMessageData);
                $newMessage->groups()->attach($receiverIds);
                foreach ($originalMessage->attachments as $attachment) {
                    $newMessage->attachments()->create(['filename' => $attachment->filename]);
                }
            });
            return redirect()->back()->with('success', 'تم إعادة توجيه الرسالة بنجاح!');
        } catch (\Exception $e) {
            Log::error('خطأ أثناء إعادة توجيه الرسالة: ' . $e->getMessage());
            return redirect()->back()->with('error', 'فشل في إعادة توجيه الرسالة: ' . $e->getMessage());
        }
    }
    public function save(Request $request)
    {
        $messageId = $request->input('message_id');
        $message = Message::find($messageId);
        if ($message) {
            $exists = SaveMessage::where('message_id', $messageId)
                ->where('user_id', auth()->id())
                ->exists();
            if (!$exists) {
                SaveMessage::create([
                    'message_id' => $messageId,
                    'user_id' => auth()->id(),
                ]);
                return response()->json(['success' => true, 'message' => 'تم حفظ الرسالة بنجاح']);
            } else {
                return response()->json(['success' => false, 'message' => 'تم حفظ هذه الرسالة مسبقاً']);
            }
        }

        return response()->json(['success' => false, 'message' => 'لم يتم العثور على الرسالة']);
    }
    public function savedMessages()
    {
        $userId = auth()->id();
        $deletedMessageIds = DeletedMessage::where('user_id', $userId)->pluck('message_id')->toArray();
        $savedMessages = SaveMessage::with('message')
            ->where('user_id', $userId)
            ->whereNotIn('message_id', $deletedMessageIds)
            ->paginate(10);
        return view('messages.saved', ['messages' => $savedMessages]);
    }
    public function restoreSaved(Request $request)
    {
        $messageId = $request->input('message_id');
        $userId = auth()->id();
        $savedMessage = SaveMessage::where('message_id', $messageId)
            ->where('user_id', $userId)
            ->first();
        if ($savedMessage) {
            $savedMessage->delete();
            return response()->json(['success' => true, 'message' => 'تم استعادة الرسالة بنجاح']);
        }
        return response()->json(['success' => false, 'message' => 'لم يتم العثور على الرسالة']);
    }

    public function search(Request $request)
    {
        $queryText = $request->input('search');

        if (!$queryText || trim($queryText) === '') {
            return redirect()->back()->with('error', 'يرجى إدخال نص للبحث.');
        }

        $query = Message::query();

        $query->where(function ($q) use ($queryText) {
            $q->where('subject', 'LIKE', '%' . $queryText . '%')
                ->orWhere('body', 'LIKE', '%' . $queryText . '%')
                ->orWhereHas('groups', function ($groupQuery) use ($queryText) {
                    $groupQuery->where('name', 'LIKE', '%' . $queryText . '%');
                });
        });

        $query->where(function ($q) {
            $q->where('sender_id', auth()->id())
                ->orWhereHas('groups.users', function ($userQuery) {
                    $userQuery->where('users.id', auth()->id());
                });
        });

        $messages = $query->orderBy('created_at', 'desc')
            ->paginate(100);

        return view('messages.search', compact('messages'));
    }






}
