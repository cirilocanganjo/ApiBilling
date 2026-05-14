<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marcas;
use App\Models\Empresas;

class MarcasController extends Controller
{
    public function index()
    {
        $marcas = Marcas::with('empresa')->paginate(10);
        return view('marcas.index', compact('marcas'));
    }

    public function create()
    {
        $empresas = Empresas::all();
        return view('marcas.form', compact('empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100',
            'empresa_id' => 'required|exists:empresas,id',
        ]);

        Marcas::create($request->all());
        return redirect()->route('marcas.index')->with('success', 'Marca criada com sucesso!');
    }

    public function edit(Marcas $marca)
    {
        $empresas = Empresas::all();
        return view('marcas.form', compact('marca', 'empresas'));
    }

    public function update(Request $request, Marcas $marca)
    {
        $request->validate([
            'nome' => 'required|string|max:100',
            'empresa_id' => 'required|exists:empresas,id',
        ]);

        $marca->update($request->all());
        return redirect()->route('marcas.index')->with('success', 'Marca atualizada com sucesso!');
    }

    public function destroy(Marcas $marca)
    {
        $marca->delete();
        return redirect()->route('marcas.index')->with('success', 'Marca excluída com sucesso!');
    }
}
