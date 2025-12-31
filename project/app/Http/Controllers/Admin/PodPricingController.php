<?php

namespace App\Http\Controllers\Admin;

use App\Models\PodPricingOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PodPricingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display all pricing options
     */
    public function index()
    {
        $options = PodPricingOption::orderBy('category')->orderBy('sort_order')->get()->groupBy('category');
        $categories = PodPricingOption::$categories;
        return view('admin.pod-pricing.index', compact('options', 'categories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = PodPricingOption::$categories;
        return view('admin.pod-pricing.create', compact('categories'));
    }

    /**
     * Store new pricing option
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        PodPricingOption::create([
            'category' => $request->category,
            'name' => $request->name,
            'value' => $request->value,
            'price' => $request->price,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->is_active ? 1 : 0
        ]);

        return redirect()->route('admin-pod-pricing-index')
            ->with('success', 'Pricing option added successfully');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $option = PodPricingOption::findOrFail($id);
        $categories = PodPricingOption::$categories;
        return view('admin.pod-pricing.edit', compact('option', 'categories'));
    }

    /**
     * Update pricing option
     */
    public function update(Request $request, $id)
    {
        $option = PodPricingOption::findOrFail($id);
        
        $request->validate([
            'category' => 'required|string',
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        $option->update([
            'category' => $request->category,
            'name' => $request->name,
            'value' => $request->value,
            'price' => $request->price,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->is_active ? 1 : 0
        ]);

        return redirect()->route('admin-pod-pricing-index')
            ->with('success', 'Pricing option updated successfully');
    }

    /**
     * Delete pricing option
     */
    public function destroy($id)
    {
        $option = PodPricingOption::findOrFail($id);
        $option->delete();

        return redirect()->route('admin-pod-pricing-index')
            ->with('success', 'Pricing option deleted successfully');
    }

    /**
     * Toggle status
     */
    public function status($id)
    {
        $option = PodPricingOption::findOrFail($id);
        $option->is_active = !$option->is_active;
        $option->save();

        return redirect()->back()->with('success', 'Status updated');
    }

    /**
     * Get pricing options as JSON for frontend
     */
    public function getOptionsJson()
    {
        $options = PodPricingOption::active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return response()->json($options);
    }
}
