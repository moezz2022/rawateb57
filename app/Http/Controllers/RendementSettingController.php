<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RendementSetting;

class RendementSettingController extends Controller
{
    public function months(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $quarters = [
            1 => 'الثلاثي الأول',
            2 => 'الثلاثي الثاني',
            3 => 'الثلاثي الثالث',
            4 => 'الثلاثي الرابع',
        ];

        $monthSettings = RendementSetting::where('year', $year)->get();

        return view('prime_rendements.months', compact('year', 'quarters', 'monthSettings'));
    }


    public function rndmsettings(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $quarters = [
            1 => 'الثلاثي الأول',
            2 => 'الثلاثي الثاني',
            3 => 'الثلاثي الثالث',
            4 => 'الثلاثي الرابع',
        ];

        $monthSettings = RendementSetting::where('year', $year)->get();

        return view('prime_rendements.rndmsettings', compact('year', 'quarters', 'monthSettings'));
    }
    // إضافة ثلاثي جديد
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|numeric|min:2000|max:2100',
            'quarter' => 'required|in:1,2,3,4',
        ]);

        RendementSetting::firstOrCreate(
            [
                'year' => $request->year,
                'quarter' => $request->quarter,
            ],
            [
                'is_open' => false,
            ]
        );

        return redirect()->route('prime_rendements.settings.months', ['year' => $request->year])
            ->with('success', 'تمت إضافة الثلاثي بنجاح.');

    }

    // فتح/غلق ثلاثي
    public function toggle($id)
    {
        $setting = RendementSetting::findOrFail($id);

        $setting->is_open = !$setting->is_open;
        $setting->save();

        return redirect()->back()->with('success', 'تم تحديث حالة الثلاثي بنجاح.');
    }
}
