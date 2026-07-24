<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'restaurant_name' => Setting::get('restaurant_name', 'مطعمنا'),
            'restaurant_tagline' => Setting::get('restaurant_tagline', 'أفضل المأكولات والمشروبات'),
            'restaurant_logo' => Setting::get('restaurant_logo', null),
            'primary_color' => Setting::get('primary_color', '#2563eb'),
            'primary_dark_color' => Setting::get('primary_dark_color', '#3b82f6'),
            'bg_sidebar_light' => Setting::get('bg_sidebar_light', '#ffffff'),
            'bg_sidebar_dark' => Setting::get('bg_sidebar_dark', '#141b2d'),
            'font_family' => Setting::get('font_family', 'Cairo'),
        ];

        return response()->json([
            'success' => true,
            'data' => $settings,
            'message' => 'Operation successful'
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'restaurant_name' => 'required|string|max:100',
            'restaurant_tagline' => 'nullable|string|max:200',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'primary_color' => 'required|string|max:20',
            'primary_dark_color' => 'required|string|max:20',
            'bg_sidebar_light' => 'nullable|string|max:20',
            'bg_sidebar_dark' => 'nullable|string|max:20',
            'font_family' => 'nullable|string|max:50',
        ]);

        Setting::set('restaurant_name', $request->input('restaurant_name'));
        Setting::set('restaurant_tagline', $request->input('restaurant_tagline'));
        Setting::set('primary_color', $request->input('primary_color'));
        Setting::set('primary_dark_color', $request->input('primary_dark_color'));
        
        if ($request->has('bg_sidebar_light')) {
            Setting::set('bg_sidebar_light', $request->input('bg_sidebar_light'));
        }
        if ($request->has('bg_sidebar_dark')) {
            Setting::set('bg_sidebar_dark', $request->input('bg_sidebar_dark'));
        }
        if ($request->has('font_family')) {
            Setting::set('font_family', $request->input('font_family'));
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            Setting::set('restaurant_logo', '/storage/' . $path);
        }

        $settings = [
            'restaurant_name' => Setting::get('restaurant_name'),
            'restaurant_tagline' => Setting::get('restaurant_tagline'),
            'restaurant_logo' => Setting::get('restaurant_logo'),
            'primary_color' => Setting::get('primary_color'),
            'primary_dark_color' => Setting::get('primary_dark_color'),
            'bg_sidebar_light' => Setting::get('bg_sidebar_light'),
            'bg_sidebar_dark' => Setting::get('bg_sidebar_dark'),
            'font_family' => Setting::get('font_family'),
        ];

        return response()->json([
            'success' => true,
            'data' => $settings,
            'message' => 'تم حفظ الإعدادات بنجاح'
        ]);
    }
}
