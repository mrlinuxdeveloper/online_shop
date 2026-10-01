<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('addresses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:100',
            ],

            'receiver_name' => [
                'required',
                'string',
                'max:255',
            ],

            'receiver_phone' => [
                'required',
                'string',
                'max:30',
            ],

            'province' => [
                'required',
                'string',
                'max:100',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],

            'postal_code' => [
                'required',
                'string',
                'max:20',
            ],

            'is_default' => [
                'nullable',
                'boolean',
            ],
        ]);

        if ($request->boolean('is_default')) {
            Address::where('user_id', auth()->id())
                ->update([
                    'is_default' => false,
                ]);
        }

        if (
            !Address::where('user_id', auth()->id())->exists()
        ) {
            $validated['is_default'] = true;
        }

        $validated['user_id'] = auth()->id();

        Address::create($validated);

        return redirect()
            ->route('addresses.index')
            ->with('success', 'Address added successfully.');
    }

    public function edit(Address $address)
    {
        $this->authorizeAddress($address);

        return view('addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $this->authorizeAddress($address);

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:100',
            ],

            'receiver_name' => [
                'required',
                'string',
                'max:255',
            ],

            'receiver_phone' => [
                'required',
                'string',
                'max:30',
            ],

            'province' => [
                'required',
                'string',
                'max:100',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],

            'postal_code' => [
                'required',
                'string',
                'max:20',
            ],

            'is_default' => [
                'nullable',
                'boolean',
            ],
        ]);

        if ($request->boolean('is_default')) {
            Address::where('user_id', auth()->id())
                ->where('id', '!=', $address->id)
                ->update([
                    'is_default' => false,
                ]);
        }

        $address->update($validated);

        return redirect()
            ->route('addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address)
    {
        $this->authorizeAddress($address);

        $wasDefault = $address->is_default;

        $address->delete();

        if ($wasDefault) {
            $newDefault = Address::where(
                'user_id',
                auth()->id()
            )
                ->latest()
                ->first();

            if ($newDefault) {
                $newDefault->update([
                    'is_default' => true,
                ]);
            }
        }

        return redirect()
            ->route('addresses.index')
            ->with('success', 'Address deleted successfully.');
    }

    private function authorizeAddress(Address $address)
    {
        abort_unless(
            $address->user_id === auth()->id(),
            403
        );
    }
}
