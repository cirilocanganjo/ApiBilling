@extends('layouts.app')
@section('title','Clients')
@section('content')

<div class="mb-3">
    <a href="{{ route('clients.create') }}" class="btn btn-primary">New Client</a>
</div>

<!-- Search -->
<form method="GET" action="{{ route('clients.index') }}" class="mb-3">
    <div class="row">
        <div class="col-md-6">
            <input type="text" name="q" class="form-control" placeholder="Search by name, email, or phone" value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-block">Search</button>
        </div>
    </div>
</form>

@php
    $columns = [
        'id' => 'ID',
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'address' => 'Address'
    ];
    $sort = request('sort', 'id');
    $direction = request('direction', 'asc');
@endphp

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            @foreach($columns as $col => $label)
                @php
                    $dir = ($sort == $col && $direction == 'asc') ? 'desc' : 'asc';
                @endphp
                <th>
                    <a href="{{ route('clients.index', array_merge(request()->except('page'), ['sort' => $col, 'direction' => $dir])) }}">
                        {{ $label }}
                        @if($sort == $col)
                            @if($direction == 'asc')
                                <i class="fas fa-sort-up"></i>
                            @else
                                <i class="fas fa-sort-down"></i>
                            @endif
                        @else
                            <i class="fas fa-sort"></i>
                        @endif
                    </a>
                </th>
            @endforeach
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clients as $client)
        <tr>
            <td>{{ $client->id }}</td>
            <td>{{ $client->name }}</td>
            <td>{{ $client->email }}</td>
            <td>{{ $client->phone }}</td>
            <td>
                <a href="#" style="color: black; text-decoration: none;" data-toggle="modal" data-target="#addressModal{{ $client->id }}">
                    {{ $client->address }}<br>
                    <span style="color: red; font-size: 0.75rem;">+ detalhes do endereço</span>
                </a>

                <!-- Modal -->
                <div class="modal fade" id="addressModal{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel{{ $client->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content" style="background-color: #f5f7fa;"> <!-- fundo diferente -->
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="addressModalLabel{{ $client->id }}">Client Address Details</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <!-- Card: Address Info -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info shadow-sm h-100"> <!-- mesma altura -->
                                            <div class="card-header bg-info text-white">
                                                Address Info
                                            </div>
                                            <div class="card-body">
                                               <!--  <p><strong>Address:</strong> {{ $client->address }}</p> -->
                                                <p><strong>Complement:</strong> {{ $client->complement }}</p>
                                                <p><strong>Neighborhood:</strong> {{ $client->neighborhood }}</p>
                                                <p><strong>Postal Code:</strong> {{ $client->postal_code }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card: Location Info -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-success shadow-sm h-100"> <!-- mesma altura -->
                                            <div class="card-header bg-success text-white">
                                                Location Info
                                            </div>
                                            <div class="card-body">
                                                <p><strong>City:</strong> {{ optional($client->city)->name }}</p>
                                                <p><strong>Province:</strong> {{ optional($client->province)->name }}</p>
                                                <p><strong>Country:</strong> {{ optional($client->country)->name }}</p>
                                                <p><strong>Recipient:</strong> {{ $client->recipient }}</p>
                                                <p><strong>Notes:</strong> {{ $client->notes }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </td>



            <td>
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $clients->appends(request()->query())->links() }}

@endsection
