<?php

namespace App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Controller\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required','string','max:100','unique:service_categories,name'], 'description' => ['nullable','string','max:255']]);
        $slug = Str::slug($data['name']);
        if (ServiceCategory::where('slug', $slug)->exists()) {
            throw ValidationException::withMessages(['name' => 'A service category with a similar name already exists.']);
        }
        ServiceCategory::create(['name'=>$data['name'],'slug'=>$slug,'description'=>$data['description'] ?? null,'is_active'=>true]);
        return back()->with('status','Service category created.');
    }

    public function toggle(ServiceCategory $category)
    {
        $category->update(['is_active' => ! $category->is_active]);
        return back()->with('status', $category->name . ' is now ' . ($category->is_active ? 'active.' : 'hidden.'));
    }
}
