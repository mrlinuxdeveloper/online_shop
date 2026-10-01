<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::latest()->paginate(15);

        return view('admin.sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('admin.sizes.create');
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
                'unique:sizes,code',
            ],
        ]);

        Size::create($validated);

        return redirect()
            ->route('admin.sizes.index')
            ->with('success', 'Size created successfully.');
    }

    public function edit(Size $size)
    {
        return view('admin.sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
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
                'unique:sizes,code,' . $size->id,
            ],
        ]);

        $size->update($validated);

        return redirect()
            ->route('admin.sizes.index')
            ->with('success', 'Size updated successfully.');
    }

    public function destroy(Size $size)
    {
        if ($size->variants()->exists()) {
            return redirect()
                ->route('admin.sizes.index')
                ->with('error', 'This sizes is used by product variants.');
        }

        $size->delete();

        return redirect()
            ->route('admin.sizes.index')
            ->with('success', 'Size deleted successfully.');
    }
}
