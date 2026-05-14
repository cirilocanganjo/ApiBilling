<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Produtos;
use App\Models\Categorias;
use App\Models\Marcas;
use App\Models\Unidades;

class ProdutosController extends Controller
{
    public function index(Request $request)
    {
        $query = Produtos::with(['categoria', 'marca', 'unidade']);
        if ($request->filled('q')) {
            $query->where('nome', 'like', "%{$request->q}%");
        }
        $produtos = $query->orderBy('id', 'asc')->paginate(10)->appends($request->query());
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        $empresas = Empresas::all();
        $categorias = Categorias::all();
        $marcas = Marcas::all();
        $unidades = Unidades::all();
        return view('produtos.form', compact('empresas', 'categorias', 'marcas', 'unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:150',
            'preco' => 'nullable|numeric',
            'quantidade' => 'nullable|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
            'unidade_id' => 'required|exists:unidades,id',
            'empresa_id' => 'required|exists:empresas,id',
        ]);

        Produtos::create($request->all());
        return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso!');
    }

    public function edit(Produtos $produto)
    {
        $empresas = Empresas::all();
        $categorias = Categorias::all();
        $marcas = Marcas::all();
        $unidades = Unidades::all();
        return view('produtos.form', compact('produto', 'empresas', 'categorias', 'marcas', 'unidades'));
    }

    public function update(Request $request, Produtos $produto)
    {
        $request->validate([
            'nome' => 'required|string|max:150',
            'preco' => 'nullable|numeric',
            'quantidade' => 'nullable|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
            'unidade_id' => 'required|exists:unidades,id',
            'empresa_id' => 'required|exists:empresas,id',
        ]);

        $produto->update($request->all());
        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produtos $produto)
    {
        $produto->delete();
        return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso!');
    }
}
