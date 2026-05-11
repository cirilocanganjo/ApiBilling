<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with(['city', 'province', 'country']); // <-- carregar relacionamentos
    
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
    
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');
    
        $clients = $query->orderBy($sort, $direction)->paginate(10);
    
        return view('clients.index', compact('clients', 'sort', 'direction'));
    }
    

    public function create()
    {
        $countries = Country::all();      // só para o select
        $provinces = Province::all();     // só para o select
        $cities = City::all();

        return view('clients.form', compact('countries', 'provinces', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')
                         ->with('success', 'Client created successfully!');
    }

    public function edit($id)
    {
        $client = Client::with(['city', 'province', 'country'])->findOrFail($id);
        $countries = Country::all();
        $provinces = Province::all();
        $cities = City::all();

        return view('clients.form', compact('client', 'countries', 'provinces', 'cities'));
    }

/*     public function edit($id)
    {
        $client = Client::findOrFail($id);
        $countries = Country::all();
        $provinces = Province::all();
        $cities = City::all();

        return view('clients.form', compact('client', 'countries', 'provinces', 'cities'));
    } */

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')
                         ->with('success', 'Client updated successfully!');
    }

    public function destroy($id)
    {
        Client::destroy($id);

        return redirect()->route('clients.index')
                         ->with('success', 'Client deleted successfully!');
    }
}
