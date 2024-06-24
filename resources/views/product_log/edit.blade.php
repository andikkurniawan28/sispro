@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_product_log')) }}
@endsection

@section('product_log-active', 'active')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('product_log.index') }}">{{ ucwords(str_replace('_', ' ', 'product_log')) }}</a></li>
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
                        <form action="{{ route('product_log.update', $product_log->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="code">
                                    {{ ucwords(str_replace('_', ' ', 'code')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="code" name="code" value="{{ $product_log->code }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="product_warehouse_id">
                                    {{ ucwords(str_replace('_', ' ', 'warehouse')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="product_warehouse_id" id="product_warehouse_id" name="product_warehouse_id" required autofocus>
                                        @foreach ($product_warehouses as $product_warehouse)
                                            <option value="{{ $product_warehouse->id }}"
                                                {{ $product_warehouse->id == $product_log->product_warehouse_id ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $product_warehouse->name)) }}
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
                                        <option value="In" {{ $product_log->status == 'In' ? 'selected' : '' }}>In</option>
                                        <option value="Out" {{ $product_log->status == 'Out' ? 'selected' : '' }}>Out</option>
                                    </select>
                                </div>
                            </div>

                            <div id="products_container">
                                @foreach ($product_log->product_log_items as $index => $product_log_item)
                                    <div class="row mb-3" id="product_row_{{ $index }}">
                                        <div class="col-sm-4">
                                            <label class="col-form-label" for="product_{{ $index }}">
                                                Product
                                            </label>
                                            <select class="product form-control" id="product_{{ $index }}" name="products[]" required>
                                                <option disabled>Select a product</option>
                                                @foreach ($products as $productItem)
                                                    <option value="{{ $productItem->id }}" {{ $product_log_item->product_id == $productItem->id ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $productItem->code)) }} | {{ ucwords(str_replace('_', ' ', $productItem->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="quantity_{{ $index }}">
                                                Quantity
                                            </label>
                                            <input type="number" class="form-control quantity" id="quantity_{{ $index }}" name="quantities[]" placeholder="Quantity" value="{{ old('quantities')[$index] ?? $product_log_item->qty }}" required>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="col-form-label" for="unit_symbol_{{ $index }}">
                                                Unit Symbol
                                            </label>
                                            <span class="unit_symbol form-control" id="unit_symbol_{{ $index }}">
                                                {{ $products->find($product_log_item->product_id)->unit->symbol }}
                                            </span>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="col-form-label" for="remove_{{ $index }}">
                                                Remove
                                            </label>
                                            <br>
                                            <button type="button" class="btn btn-danger btn-sm btn-block remove-product">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="button" class="btn btn-success" id="add_product">Add Product</button>
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
                $('.product').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
                $('.product_warehouse_id').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
                $('.status').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
            }

            function initializeRemoveButton() {
                $('.remove-product').click(function() {
                    $(this).closest('.row').remove();
                });
            }

            // Initialize Select2 for existing rows
            initializeSelect2();

            // Initialize Remove button for existing rows
            initializeRemoveButton();

            // Products data with unit symbols
            const productsData = @json($products->mapWithKeys(function($item) {
                return [$item->id => $item->unit->symbol];
            }));

            // Counter for unique IDs
            let productCounter = {{ $product_log->product_log_items->count() }};

            // Function to add a new Select2 product dropdown with quantity input
            function addProductDropdown() {
                productCounter++;
                const newDropdownId = 'product_' + productCounter;

                // Create new row with Select2 dropdown and quantity input
                const newRow = `
                    <div class="row mb-3" id="product_row_${productCounter}">
                        <div class="col-sm-4">
                            <label class="col-form-label" for="${newDropdownId}">
                                Product
                            </label>
                            <select class="product form-control" id="${newDropdownId}" name="products[]" required>
                                <option disabled selected>Select a product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ ucwords(str_replace('_', ' ', $product->code)) }} |
                                        {{ ucwords(str_replace('_', ' ', $product->name)) }}
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
                            <button type="button" class="btn btn-danger btn-sm btn-block remove-product">Remove</button>
                        </div>
                    </div>
                `;

                // Append the new row to the container
                $('#products_container').append(newRow);

                // Initialize Select2 for the new dropdown
                $('#' + newDropdownId).select2({
                    theme: 'bootstrap',
                    width: '100%',
                });

                // Add change event listener to update unit symbol
                $('#' + newDropdownId).on('change', function() {
                    const selectedProductId = $(this).val();
                    const unitSymbol = productsData[selectedProductId];
                    $('#unit_symbol_' + newDropdownId).text(unitSymbol);
                });

                // Initialize Remove button for new row
                initializeRemoveButton();
            }

            // Add product button click event
            $('#add_product').click(function() {
                addProductDropdown();
            });
        });
    </script>
@endsection
