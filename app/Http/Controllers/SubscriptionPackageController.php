<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPackage;

class SubscriptionPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptionPackages = SubscriptionPackage::all();
        return view('admin.paket-langganan.index', compact('subscriptionPackages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.paket-langganan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
        ]);

        SubscriptionPackage::create($validated);
        return redirect()->route('admin.paket-langganan.index')->with('success', 'Paket langganan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subscription = SubscriptionPackage::findOrFail($id);
        return view('admin.paket-langganan.edit', compact('subscription'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subscription = SubscriptionPackage::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
        ]);
        $subscription->update($validated);
        return redirect()->route('admin.paket-langganan.index')->with('success', 'Paket langganan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subscription = SubscriptionPackage::findOrFail($id);
        $subscription->delete();
        return redirect()->route('admin.paket-langganan.index')->with('success', 'Paket langganan berhasil dihapus');
    }
}
