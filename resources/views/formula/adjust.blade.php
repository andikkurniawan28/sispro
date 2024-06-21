@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'adjust_formula')) }}
@endsection

@section('formula-active', 'active')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('formula.index') }}">{{ ucwords(str_replace('_', ' ', 'formula')) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">{{ $product->name }} ({{ $product->product_status->name }})</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('formula.update', $product->id) }}" method="POST">
                            @csrf
                            @method('POST')

                            <div id="raw_materials_container">
                                <!-- Raw material rows will be appended here -->
                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="button" class="btn btn-success" id="add_raw_material">Add Raw Material</button>
                                </div>
                            </div>

                            <div class="row justify-content-end mt-3">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Send</button>
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
            $('.product_id').select2({
                theme: 'bootstrap',
                width: '100%',
            });

            // Raw materials data with unit symbols
            const rawMaterialsData = @json($raw_materials->mapWithKeys(function($item) {
                return [$item->id => $item->unit->symbol];
            }));

            // Counter for unique IDs
            let rawMaterialCounter = 0;

            // Function to add a new Select2 raw material dropdown with quantity input
            function addRawMaterialDropdown() {
                rawMaterialCounter++;
                const newDropdownId = 'raw_material_' + rawMaterialCounter;

                // Create new row with Select2 dropdown and quantity input
                const newRow = `
                    <div class="row mb-3" id="raw_material_row_${rawMaterialCounter}">
                        <div class="col-sm-4">
                            <label class="col-form-label" for="${newDropdownId}">
                                Raw Material
                            </label>
                            <select class="raw_material form-control" id="${newDropdownId}" name="raw_materials[]" required>
                                <option disabled selected>Select a raw material</option>
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
                            <label class="col-form-label" for="unit_symbol_${newDropdownId}">
                                Remove
                            </label>
                            <br>
                            <button type="button" class="btn btn-danger btn-sm btn-block remove-raw-material">Remove</button>
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
                    const selectedRawMaterialId = $(this).val();
                    const unitSymbol = rawMaterialsData[selectedRawMaterialId];
                    $('#unit_symbol_' + newDropdownId).text(unitSymbol);
                });

                // Add click event listener to the remove button
                $('.remove-raw-material').last().click(function() {
                    $(this).closest('.row').remove();
                });
            }

            // Add raw material button click event
            $('#add_raw_material').click(function() {
                addRawMaterialDropdown();
            });

            // Initial load: add existing raw materials if any
            @foreach($formula as $item)
                addRawMaterialDropdown();
                $('#raw_material_' + rawMaterialCounter).val('{{ $item->raw_material_id }}').trigger('change');
                $('#quantity_raw_material_' + rawMaterialCounter).val('{{ $item->qty }}'); // Set quantity value
            @endforeach
        });
    </script>
@endsection
