<?php

namespace App\Http\Controllers\Admin;

use App\Models\MockupTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Image;

class MockupTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of mockup templates
     */
    public function index()
    {
        $templates = MockupTemplate::orderBy('product_type')->orderBy('name')->get();
        return view('admin.mockup.index', compact('templates'));
    }

    /**
     * Show the form for creating a new mockup template
     */
    public function create()
    {
        $productTypes = MockupTemplate::$productTypes;
        $styles = MockupTemplate::$styles;
        $colors = MockupTemplate::$colors;
        
        return view('admin.mockup.create', compact('productTypes', 'styles', 'colors'));
    }

    /**
     * Store a newly created mockup template
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'product_type' => 'required',
            'style' => 'required',
            'color' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:5120',
            'design_x' => 'required|integer|min:0',
            'design_y' => 'required|integer|min:0',
            'design_width' => 'required|integer|min:50',
            'design_height' => 'required|integer|min:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $template = new MockupTemplate();
        $template->name = $request->name;
        $template->product_type = $request->product_type;
        $template->style = $request->style;
        $template->color = $request->color;
        $template->design_x = $request->design_x;
        $template->design_y = $request->design_y;
        $template->design_width = $request->design_width;
        $template->design_height = $request->design_height;
        $template->status = $request->status ?? 1;

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            $file->move(public_path('assets/images/mockups'), $filename);
            $template->image = $filename;
        }

        $template->save();

        return redirect()->route('admin-mockup-index')->with('success', 'Mockup template created successfully!');
    }

    /**
     * Show the form for editing a mockup template
     */
    public function edit($id)
    {
        $template = MockupTemplate::findOrFail($id);
        $productTypes = MockupTemplate::$productTypes;
        $styles = MockupTemplate::$styles;
        $colors = MockupTemplate::$colors;
        
        return view('admin.mockup.edit', compact('template', 'productTypes', 'styles', 'colors'));
    }

    /**
     * Update the specified mockup template
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'product_type' => 'required',
            'style' => 'required',
            'color' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'design_x' => 'required|integer|min:0',
            'design_y' => 'required|integer|min:0',
            'design_width' => 'required|integer|min:50',
            'design_height' => 'required|integer|min:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $template = MockupTemplate::findOrFail($id);
        $template->name = $request->name;
        $template->product_type = $request->product_type;
        $template->style = $request->style;
        $template->color = $request->color;
        $template->design_x = $request->design_x;
        $template->design_y = $request->design_y;
        $template->design_width = $request->design_width;
        $template->design_height = $request->design_height;
        $template->status = $request->status ?? 1;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            $oldImage = public_path('assets/images/mockups/' . $template->image);
            if (file_exists($oldImage) && $template->image) {
                unlink($oldImage);
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            $file->move(public_path('assets/images/mockups'), $filename);
            $template->image = $filename;
        }

        $template->save();

        return redirect()->route('admin-mockup-index')->with('success', 'Mockup template updated successfully!');
    }

    /**
     * Remove the specified mockup template
     */
    public function destroy($id)
    {
        $template = MockupTemplate::findOrFail($id);
        
        // Delete image file
        $imagePath = public_path('assets/images/mockups/' . $template->image);
        if (file_exists($imagePath) && $template->image) {
            unlink($imagePath);
        }
        
        $template->delete();

        return redirect()->route('admin-mockup-index')->with('success', 'Mockup template deleted successfully!');
    }

    /**
     * Toggle template status
     */
    public function status($id)
    {
        $template = MockupTemplate::findOrFail($id);
        $template->status = $template->status == 1 ? 0 : 1;
        $template->save();

        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    /**
     * API: Get all active templates for designer mockup viewer
     */
    public function getTemplatesJson()
    {
        $templates = MockupTemplate::active()->orderBy('product_type')->get()->map(function ($t) {
            return [
                'id' => $t->id,
                'name' => $t->name,
                'product_type' => $t->product_type,
                'product_type_name' => $t->product_type_name,
                'style' => $t->style,
                'style_name' => $t->style_name,
                'color' => $t->color,
                'color_name' => $t->color_name,
                'image_url' => $t->image_url,
                'design_area' => $t->design_area
            ];
        });

        return response()->json($templates);
    }
}
