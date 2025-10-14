<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Imports\GroupsImport;
use Maatwebsite\Excel\Facades\Excel;
class GroupController extends Controller
{
    public function index(): View
    {
        $groups = Group::all();
        return view('groups.index', compact('groups'));
    }
    public function getSubGroups(Request $request)
    {
        $request->validate([
            'main_group_id' => 'required|exists:groups,id',
        ]);
        $subGroups = Group::where('parent_id', $request->main_group_id)->get();
        return response()->json($subGroups);
    }

    public function create()
    {
        $groups = Group::whereNull('parent_id')->get();
        return view('groups.add', compact('groups'));
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'AFFECT'=> ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer'],
        ]);

        $group = Group::create([
            'AFFECT'=>$request->AFFECT,
            'name' => $request->name,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('groups.index')->with('success', 'تم إضافة المؤسسة بنجاح!');
    }

    public function edit($id)
    {
        $group = Group::findOrFail($id);
        $groups = Group::whereNull('parent_id')->get();
        return view('groups.edit', compact('group', 'groups'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'AFFECT' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'parent_id' => 'nullable|string|max:255',
        ]);

        $parentId = null;

        if ($request->filled('parent_id')) {
            $parent = Group::where('id', $request->parent_id)
                ->orWhere('name', $request->parent_id)
                ->first();

            if (!$parent) {
                return back()->withErrors(['parent_id' => 'لم يتم العثور على المؤسسة التابعة.']);
            }

            $parentId = $parent->id;
        }

        $group = Group::findOrFail($id);
        $group->update([
            'AFFECT'=>$request->AFFECT,
            'name' => $request->name,
            'type' => $request->type,
            'parent_id' => $parentId,
        ]);

        return redirect()->route('groups.index')->with('success', 'تم تحديث معلومات المؤسسة بنجاح');
    }


    public function destroy($id)
    {

        $groups = Group::find($id);
        $groups->delete();
        return redirect()->route('groups.index')->with('success', 'تم حذف المؤسسة بنجاح!');
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('groups')->truncate();
        Excel::import(new GroupsImport, $file);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->back()->with('success', 'تم استيراد البيانات بنجاح');
    }



}
