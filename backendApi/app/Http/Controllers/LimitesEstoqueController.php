<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LimitesEstoque;

class LimitesEstoqueController extends Controller
{
    public function index(Request $request)
    {
        $query = LimitesEstoque::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('descricao', 'like', "%{$search}%");
        }

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        $query->orderBy($sort, $direction);

        $limites = $query->paginate(10);

        return view('limites_estoque.index', compact('limites'));
    }

    public function create()
    {
        return view('limites_estoque.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'descricao' => 'required|string|max:100',
            'quantidade_min' => 'nullable|integer',
            'quantidade_max' => 'nullable|integer',
        ]);

        LimitesEstoque::create($request->all());

        return redirect()->route('limites_estoque.index')
                         ->with('success', 'Limite de estoque criado com sucesso!');
    }

    public function edit($id)
    {
        $limite = LimitesEstoque::findOrFail($id);
        return view('limites_estoque.form', compact('limite'));
    }

    public function update(Request $request, $id)
    {
        $limite = LimitesEstoque::findOrFail($id);

        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'descricao' => 'required|string|max:100',
            'quantidade_min' => 'nullable|integer',
            'quantidade_max' => 'nullable|integer',
        ]);

        $limite->update($request->all());

        return redirect()->route('limites_estoque.index')
                         ->with('success', 'Limite de estoque atualizado com sucesso!');
    }

    public function destroy($id)
    {
        LimitesEstoque::destroy($id);

        return redirect()->route('limites_estoque.index')
                         ->with('success', 'Limite de estoque removido com sucesso!');
    }

    public function show($id)
    {
        return redirect()->route('limites_estoque.index');
    }
}
