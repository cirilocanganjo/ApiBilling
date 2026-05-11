<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;

class SuppliersController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::with(['city', 'province', 'country']);

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

        $suppliers = $query->orderBy($sort, $direction)->paginate(10);

        return view('suppliers.index', compact('suppliers', 'sort', 'direction'));
    }

    public function create()
    {
        $countries = Country::all();
        $provinces = Province::all();
        $cities = City::all();

        return view('suppliers.form', compact('countries', 'provinces', 'cities'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['natural_person'] = $request->has('natural_person') ? 1 : 0;
    
        Supplier::create($data);
    
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully!');
    }
      

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $countries = Country::all();
        $provinces = Province::all();
        $cities = City::all();

        return view('suppliers.form', compact('supplier', 'countries', 'provinces', 'cities'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $data = $request->all();
        $data['natural_person'] = $request->has('natural_person') ? 1 : 0;
    
        $supplier->update($data);
    
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
    }
    
    public function destroy($id)
    {
        Supplier::destroy($id);

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier deleted successfully!');
    }
}
