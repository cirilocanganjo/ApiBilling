<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidades;
use App\Models\Empresas;

class UnidadesController extends Controller
{
    public function index()
    {
        $unidades = Unidades::with('empresa')->paginate(10);
        return view('unidades.index', compact('unidades'));
    }

    public function create()
    {
        $empresas = Empresas::all();
        return view('unidades.form', compact('empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:50',
            'sigla' => 'required|string|max:10',
            'empresa_id' => 'required|exists:empresas,id',
        ]);

        Unidades::create($request->all());
        return redirect()->route('unidades.index')->with('success', 'Unidade criada com sucesso!');
    }

    public function edit(Unidades $unidade)
    {
        $empresas = Empresas::all();
        return view('unidades.form', compact('unidade', 'empresas'));
    }

    public function update(Request $request, Unidades $unidade)
    {
        $request->validate([
            'nome' => 'required|string|max:50',
            'sigla' => 'required|string|max:10',
            'empresa_id' => 'required|exists:empresas,id',
        ]);

        $unidade->update($request->all());
        return redirect()->route('unidades.index')->with('success', 'Unidade atualizada com sucesso!');
    }

    public function destroy(Unidades $unidade)
    {
        $unidade->delete();
        return redirect()->route('unidades.index')->with('success', 'Unidade excluída com sucesso!');
    }
}
