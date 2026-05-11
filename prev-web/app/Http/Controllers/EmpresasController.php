<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresas;

class EmpresasController extends Controller
{
    public function index(Request $request)
    {
        $query = Empresas::query();

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                ->orWhere('nif', 'like', "%{$search}%")
                ->orWhere('telefone', 'like', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        $query->orderBy($sort, $direction);

        $empresas = $query->paginate(10);

        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresas.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email',
        ]);

        Empresas::create($request->all());

        return redirect()->route('empresas.index')
                         ->with('success', 'Empresa criado com sucesso!');
    }

    public function edit($id)
    {
        $empresa = Empresas::findOrFail($id);

        return view('empresas.form', compact('empresa'));
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresas::findOrFail($id);

        $request->validate([
            'nome' => 'required',
            'email' => 'required|email',
        ]);

        $empresa->update($request->all());

        return redirect()->route('empresas.index')
                         ->with('success', 'Empresa atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Empresas::destroy($id);

        return redirect()->route('empresas.index')
                         ->with('success', 'Empresa removido com sucesso!');
    }

    public function show($id)
    {
        return redirect()->route('empresas.index');
    }
}

