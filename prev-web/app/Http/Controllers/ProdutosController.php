<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produtos;
use App\Models\Empresas;
use App\Models\Categorias;
use App\Models\Marcas;
use App\Models\Unidades;
use App\Models\LimitesEstoque;
use Illuminate\Support\Facades\Storage;

class ProdutosController extends Controller
{
    // Listar produtos
    public function index(Request $request)
    {
        $query = Produtos::with(['empresa', 'categoria', 'subcategoria', 'marca', 'unidade', 'limiteEstoque']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('nome', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%")
                  ->orWhere('ean_gtin', 'like', "%{$search}%");
        }

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        $produtos = $query->orderBy($sort, $direction)->paginate(10)->appends($request->query());

        return view('produtos.index', compact('produtos'));
    }

    // Formulário de criação
    public function create()
    {
        $empresas = Empresas::all();
        $categorias = Categorias::all();
        $subcategorias = Categorias::all();
        $marcas = Marcas::all();
        $unidades = Unidades::all();
        $limites_estoque = LimitesEstoque::all();

        return view('produtos.form', compact('empresas','categorias','subcategorias','marcas','unidades','limites_estoque'));
    }

    // Salvar novo produto
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:150',
            'empresa_id' => 'required|exists:empresas,id',
            'categoria_id' => 'nullable|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:categorias,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'unidade_id' => 'nullable|exists:unidades,id',
            'limite_estoque_id' => 'nullable|exists:limites_estoque,id',
            'preco_venda' => 'nullable|numeric',
            'preco_custo' => 'nullable|numeric',
            'markup_percent' => 'nullable|numeric',
            'estoque_atual' => 'nullable|integer',
            'imagem' => 'nullable|image|max:2048' // validação do upload
        ]);

        $data = $request->only([
            'codigo','codigo_extra','ean_gtin','nome','empresa_id','categoria_id','subcategoria_id',
            'marca_id','localizacao','preco_venda','preco_custo','markup_percent','preco_alteravel',
            'seguir_markup','controlar_estoque','estoque_atual','limite_estoque_id',
            'unidade_id','permite_fracionamento','ativo','is_deleted'
        ]);

        // Upload de imagem
        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('produtos', 'public');
            $data['imagem'] = basename($path);
        }

        Produtos::create($data);

        return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso!');
    }

    // Formulário de edição
    public function edit(Produtos $produto)
    {
        $empresas = Empresas::all();
        $categorias = Categorias::all();
        $subcategorias = Categorias::all();
        $marcas = Marcas::all();
        $unidades = Unidades::all();
        $limites_estoque = LimitesEstoque::all();

        return view('produtos.form', compact('produto','empresas','categorias','subcategorias','marcas','unidades','limites_estoque'));
    }

    // Atualizar produto
    public function update(Request $request, Produtos $produto)
    {
        $request->validate([
            'nome' => 'required|string|max:150',
            'empresa_id' => 'required|exists:empresas,id',
            'categoria_id' => 'nullable|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:categorias,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'unidade_id' => 'nullable|exists:unidades,id',
            'limite_estoque_id' => 'nullable|exists:limites_estoque,id',
            'preco_venda' => 'nullable|numeric',
            'preco_custo' => 'nullable|numeric',
            'markup_percent' => 'nullable|numeric',
            'estoque_atual' => 'nullable|integer',
            'imagem' => 'nullable|image|max:2048'
        ]);

        $data = $request->only([
            'marca_id','localizacao','preco_venda','preco_custo','markup_percent',
            'preco_alteravel','seguir_markup','controlar_estoque','estoque_atual',
            'limite_estoque_id','unidade_id','permite_fracionamento','is_inativo','is_deleted'
        ]);
        
        // Converter checkboxes para boolean
        $data['preco_alteravel'] = $request->has('preco_alteravel') ? 1 : 0;
        $data['seguir_markup'] = $request->has('seguir_markup') ? 1 : 0;
        $data['controlar_estoque'] = $request->has('controlar_estoque') ? 1 : 0;
        $data['permite_fracionamento'] = $request->has('permite_fracionamento') ? 1 : 0;
        $data['is_inativo'] = $request->has('is_inativo') ? 1 : 0;
        $data['is_deleted'] = $request->has('is_deleted') ? 1 : 0;
        

        // Upload de imagem
        if ($request->hasFile('imagem')) {
            if ($produto->imagem && Storage::disk('public')->exists('produtos/'.$produto->imagem)) {
                Storage::disk('public')->delete('produtos/'.$produto->imagem);
            }
            $path = $request->file('imagem')->store('produtos', 'public');
            $data['imagem'] = basename($path);
        }

        $produto->update($data);

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }

    // Soft delete do produto
    public function destroy(Produtos $produto)
    {
        $produto->is_deleted = 1;
        $produto->deleted_at = now();
        $produto->save();

        return redirect()->route('produtos.index')->with('success', 'Produto removido com sucesso!');
    }
}
