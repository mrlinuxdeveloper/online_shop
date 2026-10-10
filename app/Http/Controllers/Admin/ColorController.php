<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::latest()->paginate(15);

        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:colors,code',
            ],
            'hex' => [
                'required',
                'string',
                'max:7',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],
        ]);

        Color::create($validated);

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'رنگ با موفقیت ایجاد شد.');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:colors,code,' . $color->id,
            ],
            'hex' => [
                'required',
                'string',
                'max:7',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],
        ]);

        $color->update($validated);

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'رنگ با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Color $color)
    {
        if ($color->variants()->exists()) {
            return redirect()
                ->route('admin.colors.index')
                ->with('error', 'این رنگ در تنوع محصولات استفاده شده و قابل حذف نیست.');
        }

        $color->delete();

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'رنگ با موفقیت حذف شد.');
    }
}
