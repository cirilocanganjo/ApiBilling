@extends('layouts.app')
@section('title', isset($supplier) ? 'Edit Supplier' : 'New Supplier')
@section('content')

<div class="container-fluid">
    <form action="{{ isset($supplier) ? route('suppliers.update', $supplier->id) : route('suppliers.store') }}" method="POST" novalidate>
        @csrf
        @if(isset($supplier)) @method('PUT') @endif

        <!-- Name -->
        <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ $supplier->name ?? '' }}" required>
            <div class="invalid-feedback">Name is required.</div>

            <!-- Checkbox Pessoa Física -->
            <div class="form-check mt-2">
                <input type="checkbox" name="natural_person" class="form-check-input" id="natural_person"
                    value="1" {{ isset($supplier) && $supplier->natural_person ? 'checked' : '' }}>
                <label class="form-check-label" for="natural_person">Pessoa Física</label>
            </div>
        </div>


        <!-- Email -->
        <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="{{ $supplier->email ?? '' }}" required>
            <div class="invalid-feedback">Valid email is required.</div>
        </div>

        <!-- Phone -->
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ $supplier->phone ?? '' }}">
        </div>

        <!-- Address textarea for visualization only -->
        <div class="form-group">
            <label>Address</label>
            <textarea name="address_view" class="form-control" rows="4" readonly data-toggle="modal" data-target="#addressModal" style="cursor: pointer;">{{ isset($supplier) ? implode("\n", array_filter([
                $supplier->address,
                $supplier->complement,
                $supplier->neighborhood,
                $supplier->postal_code,
                optional($supplier->city)->name,
                optional($supplier->province)->name,
                optional($supplier->country)->name
            ])) : '' }}</textarea>
        </div>

        <!-- Hidden fields for database -->
        <input type="hidden" name="address" value="{{ $supplier->address ?? '' }}">
        <input type="hidden" name="complement" value="{{ $supplier->complement ?? '' }}">
        <input type="hidden" name="neighborhood" value="{{ $supplier->neighborhood ?? '' }}">
        <input type="hidden" name="postal_code" value="{{ $supplier->postal_code ?? '' }}">
        <input type="hidden" name="country_id" value="{{ $supplier->country_id ?? '' }}">
        <input type="hidden" name="province_id" value="{{ $supplier->province_id ?? '' }}">
        <input type="hidden" name="city_id" value="{{ $supplier->city_id ?? '' }}">

        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>

<!-- Modal for Address -->
<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background-color: #f5f7fa;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addressModalLabel">Edit Supplier Address</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column: Inputs -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" id="modal_address" class="form-control" value="{{ $supplier->address ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>Complement</label>
                                    <input type="text" id="modal_complement" class="form-control" value="{{ $supplier->complement ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>Neighborhood</label>
                                    <input type="text" id="modal_neighborhood" class="form-control" value="{{ $supplier->neighborhood ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" id="modal_postal_code" class="form-control" value="{{ $supplier->postal_code ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Selects -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Country</label>
                                    <select id="modal_country" class="form-control">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ isset($supplier) && $supplier->country_id == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Province</label>
                                    <select id="modal_province" class="form-control">
                                        <option value="">Select Province</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ isset($supplier) && $supplier->province_id == $province->id ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <select id="modal_city" class="form-control">
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ isset($supplier) && $supplier->city_id == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveAddress()">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function saveAddress() {
    // Atualiza os campos reais do banco
    document.querySelector('input[name="address"]').value = document.getElementById('modal_address').value;
    document.querySelector('input[name="complement"]').value = document.getElementById('modal_complement').value;
    document.querySelector('input[name="neighborhood"]').value = document.getElementById('modal_neighborhood').value;
    document.querySelector('input[name="postal_code"]').value = document.getElementById('modal_postal_code').value;
    document.querySelector('input[name="country_id"]').value = document.getElementById('modal_country').value;
    document.querySelector('input[name="province_id"]').value = document.getElementById('modal_province').value;
    document.querySelector('input[name="city_id"]').value = document.getElementById('modal_city').value;

    // Atualiza a textarea de visualização
    const lines = [
        document.getElementById('modal_address').value,
        document.getElementById('modal_complement').value,
        document.getElementById('modal_neighborhood').value,
        document.getElementById('modal_postal_code').value,
        document.getElementById('modal_city').selectedOptions[0]?.text,
        document.getElementById('modal_province').selectedOptions[0]?.text,
        document.getElementById('modal_country').selectedOptions[0]?.text
    ].filter(Boolean);

    document.querySelector('textarea[name="address_view"]').value = lines.join("\n");

    $('#addressModal').modal('hide');
}

// Form validation
(function() {
  'use strict';
  var forms = document.querySelectorAll('form');
  Array.prototype.slice.call(forms).forEach(function(form) {
    form.addEventListener('submit', function(event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
@endpush

@endsection
