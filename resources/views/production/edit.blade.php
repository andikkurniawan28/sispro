@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_production')) }}
@endsection

@section('production-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route("production.index") }}">{{ ucwords(str_replace('_', ' ', 'production')) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield("title")</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@yield('title')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("production.update", $production->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="code">
                                    {{ ucwords(str_replace('_', ' ', 'code')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="code" name="code" value="{{ $production->code }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="demand_id">
                                    {{ ucwords(str_replace('_', ' ', 'demand')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="demand_id" id="demand_id" name="demand_id" required autofocus>
                                        <option disabled>Select a demand :</option>
                                        @foreach ($demands as $demand)
                                            <option value="{{ $demand->id }}" {{ $production->demand_id == $demand->id ? 'selected' : '' }}>{{ $demand->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="product_id">
                                    {{ ucwords(str_replace('_', ' ', 'product')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="product_id" id="product_id" name="product_id" required autofocus>
                                        <option disabled>Select a raw product :</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ $production->product_id == $product->id ? 'selected' : '' }}>{{ $product->code }} | @php echo str_replace('_', ' ', $product->name); @endphp</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="batch">
                                    {{ ucwords(str_replace('_', ' ', 'batch')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="batch" name="batch" value="{{ $production->batch }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="accepted">
                                    {{ ucwords(str_replace('_', ' ', 'accepted')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="accepted" name="accepted" value="{{ $production->accepted }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="rejected">
                                    {{ ucwords(str_replace('_', ' ', 'rejected')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="rejected" name="rejected" value="{{ $production->rejected }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="qty">
                                    {{ ucwords(str_replace('_', ' ', 'qty')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="qty" name="qty" value="{{ $production->qty }}" required>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
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
<script>
    $(document).ready(function() {
        $('.product_id').select2({
            theme: 'bootstrap',
            width: '100%',
        });
        $('.demand_id').select2({
            theme: 'bootstrap',
            width: '100%',
        });
    });
</script>
@endsection
