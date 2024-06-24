@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_raw_material_log')) }}
@endsection

@section('raw_material_log-active', 'active')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('raw_material_log.index') }}">{{ ucwords(str_replace('_', ' ', 'raw_material_log')) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@yield('title')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('raw_material_log.update', $raw_material_log->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="code">
                                    {{ ucwords(str_replace('_', ' ', 'code')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="code" name="code" value="{{ $raw_material_log->code }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="raw_material_warehouse_id">
                                    {{ ucwords(str_replace('_', ' ', 'warehouse')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="raw_material_warehouse_id" id="raw_material_warehouse_id" name="raw_material_warehouse_id" required autofocus>
                                        @foreach ($raw_material_warehouses as $raw_material_warehouse)
                                            <option value="{{ $raw_material_warehouse->id }}"
                                                {{ $raw_material_warehouse->id == $raw_material_log->raw_material_warehouse_id ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $raw_material_warehouse->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="status">
                                    {{ ucwords(str_replace('_', ' ', 'status')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="status" id="status" name="status" required>
                                        <option disabled selected>Select a status :</option>
                                        <option value="In" {{ $raw_material_log->status == 'In' ? 'selected' : '' }}>In</option>
                                        <option value="Out" {{ $raw_material_log->status == 'Out' ? 'selected' : '' }}>Out</option>
                                    </select>
                                </div>
                            </div>

                            <div id="raw_materials_container">
                                @foreach ($raw_material_log->raw_material_log_items as $index => $raw_material_log_item)
                                    <div class="row mb-3" id="raw_material_row_{{ $index }}">
                                        <div class="col-sm-4">
                                            <label class="col-form-label" for="raw_material_{{ $index }}">
                                                Product
                                            </label>
                                            <select class="raw_material form-control" id="raw_material_{{ $index }}" name="raw_materials[]" required>
                                                <option disabled>Select a raw_material</option>
                                                @foreach ($raw_materials as $raw_materialItem)
                                                    <option value="{{ $raw_materialItem->id }}" {{ $raw_material_log_item->raw_material_id == $raw_materialItem->id ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $raw_materialItem->code)) }} | {{ ucwords(str_replace('_', ' ', $raw_materialItem->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="quantity_{{ $index }}">
                                                Quantity
                                            </label>
                                            <input type="number" class="form-control quantity" id="quantity_{{ $index }}" name="quantities[]" placeholder="Quantity" value="{{ old('quantities')[$index] ?? $raw_material_log_item->qty }}" required>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="col-form-label" for="unit_symbol_{{ $index }}">
                                                Unit Symbol
                                            </label>
                                            <span class="unit_symbol form-control" id="unit_symbol_{{ $index }}">
                                                {{ $raw_materials->find($raw_material_log_item->raw_material_id)->unit->symbol }}
                                            </span>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="col-form-label" for="remove_{{ $index }}">
                                                Remove
                                            </label>
                                            <br>
                                            <button type="button" class="btn btn-danger btn-sm btn-block remove-raw_material">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="button" class="btn btn-success" id="add_raw_material">Add Product</button>
                                </div>
                            </div>

                            <div class="row justify-content-end mt-3">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            function initializeSelect2() {
                $('.raw_material').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
                $('.raw_material_warehouse_id').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
                $('.status').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
            }

            function initializeRemoveButton() {
                $('.remove-raw_material').click(function() {
                    $(this).closest('.row').remove();
                });
            }

            // Initialize Select2 for existing rows
            initializeSelect2();

            // Initialize Remove button for existing rows
            initializeRemoveButton();

            // Products data with unit symbols
            const raw_materialsData = @json($raw_materials->mapWithKeys(function($item) {
                return [$item->id => $item->unit->symbol];
            }));

            // Counter for unique IDs
            let raw_materialCounter = {{ $raw_material_log->raw_material_log_items->count() }};

            // Function to add a new Select2 raw_material dropdown with quantity input
            function addProductDropdown() {
                raw_materialCounter++;
                const newDropdownId = 'raw_material_' + raw_materialCounter;

                // Create new row with Select2 dropdown and quantity input
                const newRow = `
                    <div class="row mb-3" id="raw_material_row_${raw_materialCounter}">
                        <div class="col-sm-4">
                            <label class="col-form-label" for="${newDropdownId}">
                                Product
                            </label>
                            <select class="raw_material form-control" id="${newDropdownId}" name="raw_materials[]" required>
                                <option disabled selected>Select a raw_material</option>
                                @foreach ($raw_materials as $raw_material)
                                    <option value="{{ $raw_material->id }}">
                                        {{ ucwords(str_replace('_', ' ', $raw_material->code)) }} |
                                        {{ ucwords(str_replace('_', ' ', $raw_material->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label class="col-form-label" for="quantity_${newDropdownId}">
                                Quantity
                            </label>
                            <input type="number" class="form-control quantity" id="quantity_${newDropdownId}" name="quantities[]" placeholder="Quantity" required>
                        </div>
                        <div class="col-sm-2">
                            <label class="col-form-label" for="unit_symbol_${newDropdownId}">
                                Unit Symbol
                            </label>
                            <span class="unit_symbol form-control" id="unit_symbol_${newDropdownId}"></span>
                        </div>
                        <div class="col-sm-2">
                            <label class="col-form-label" for="remove_${newDropdownId}">
                                Remove
                            </label>
                            <br>
                            <button type="button" class="btn btn-danger btn-sm btn-block remove-raw_material">Remove</button>
                        </div>
                    </div>
                `;

                // Append the new row to the container
                $('#raw_materials_container').append(newRow);

                // Initialize Select2 for the new dropdown
                $('#' + newDropdownId).select2({
                    theme: 'bootstrap',
                    width: '100%',
                });

                // Add change event listener to update unit symbol
                $('#' + newDropdownId).on('change', function() {
                    const selectedProductId = $(this).val();
                    const unitSymbol = raw_materialsData[selectedProductId];
                    $('#unit_symbol_' + newDropdownId).text(unitSymbol);
                });

                // Initialize Remove button for new row
                initializeRemoveButton();
            }

            // Add raw_material button click event
            $('#add_raw_material').click(function() {
                addProductDropdown();
            });
        });
    </script>
@endsection
