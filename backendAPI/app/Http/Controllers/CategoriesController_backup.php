<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Crypt;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('numberlineshow', 10);

        $query = Category::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->paginate($perPage)->appends($request->query());

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Category::create($request->only(['name', 'description']));

        return redirect()->route('categories.index')
                         ->with('success', 'Categoria criada com sucesso!');
    }

    public function edit($encryptedId)
    {
        $id = decrypt($encryptedId);   // ← DESCRIPTOGRAFA
        $category = Category::findOrFail($id);

        return view('categories.form', compact('category'));
    }

    public function update(Request $request, $encryptedId)
    {
        $id = decrypt($encryptedId);   // ← DESCRIPTOGRAFA
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required',
        ]);

        $category->update($request->only(['name', 'description']));

        return redirect()->route('categories.index')
                         ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy($encryptedId)
    {
        $id = decrypt($encryptedId);   // ← DESCRIPTOGRAFA
        Category::destroy($id);

        return redirect()->route('categories.index')
                         ->with('success', 'Categoria removida com sucesso!');
    }
}
