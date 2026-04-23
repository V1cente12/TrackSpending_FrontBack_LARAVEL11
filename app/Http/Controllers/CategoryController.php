<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\CategoryUser;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function show()
    {
        $category = Category::all();
        return Inertia::render('SelectCategory', [
            'category' => $category
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id'
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->categories()->attach($validated['category_id']);

        return redirect()->route('dashboard');
    }
}