@extends('layouts.app')
@section('title','Suppliers')
@section('content')

<!-- Pesquisa / Filtros -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card shadow-sm" style="background-color: #ffffff;">
            <div class="card-body">
            <form method="GET" action="{{ route('suppliers.index') }}">
                <div class="row g-3 align-items-center">

                    <!-- Pesquisa única -->
                    <div class="col-md-6 col-12 d-flex flex-wrap align-items-center mb-2 mb-md-0">
                        <label for="searchInput" class="col-form-label me-3 mb-2 mb-md-0 clsPaddingRigthLabelForInput">Search</label>
                        <div class="flex-grow-1">
                            <div class="input-group">
                                <input type="text" id="searchInput" name="q" class="form-control"
                                    placeholder="Name, Email or Phone" value="{{ request('q') }}">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="this.closest('.input-group').querySelector('input[name=q]').value=''">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Date range -->
                    <div class="col-md-4 col-12 d-flex flex-wrap align-items-center mb-2 mb-md-0">
                        <label for="reservation" class="col-form-label me-3 mb-2 mb-md-0 clsPaddingRigthLabelForInput">Date range</label>
                        <div class="flex-grow-1">
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                <input type="text" class="form-control" id="reservation" name="date_range" placeholder="Select range">
                            </div>
                        </div>
                    </div>

                    <!-- Botão -->
                    <div class="col-md-2 col-12 d-flex justify-content-md-start justify-content-end">
                        <button type="submit" class="btn btn-primary" style="min-width: 120px;">Search</button>
                    </div>

                </div>
            </form>

            </div>
        </div>
    </div>
</div>





<!-- Tabela -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm" style="background-color: #ffffff;">
            <div class="card-body">
                <div class="table-responsive">
                <div class="box-content">
                    <table id="suppliersTable" class="table table-striped table-hover datatable">
                        <thead>
                            <tr>
                                <!-- <th>ID</th> -->
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Pessoa Física</th>
                                <th class="no-sort">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($suppliers as $supplier)
                            <tr>
                                <!-- <td>{{ $supplier->id }}</td> -->
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td>
                                    <input type="checkbox" disabled {{ $supplier->natural_person ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <a href="{{ route('suppliers.edit',$supplier->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('suppliers.destroy',$supplier->id) }}" method="POST" class="d-inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                </div>

                <div class="p-3">
                    {{ $suppliers->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>

            </div>
        </div>
    </div>
</div>


@endsection
