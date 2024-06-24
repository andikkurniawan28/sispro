@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_production_quality')) }}
@endsection

@section('production_quality-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('production_quality.index') }}">{{ ucwords(str_replace('_', ' ', 'production_quality')) }}</a></li>
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
                        <form action="{{ route('production_quality.update', $production_quality->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="production_id">
                                    {{ ucwords(str_replace('_', ' ', 'production')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="production_id" id="production_id" name="production_id" required autofocus>
                                        <option value="{{ $production_quality->production_id }}" selected>
                                            {{ $production_quality->production->code }} |
                                            {{ $production_quality->production->product->code }} -
                                            {{ $production_quality->production->batch }}
                                        </option>
                                        @foreach ($productions as $production)
                                            <option value="{{ $production->id }}" {{ $production->id == $production_quality->production_id ? 'selected' : '' }}>
                                                {{ $production->code }} |
                                                {{ $production->product->code }} -
                                                {{ $production->batch }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @foreach ($qualities as $quality)
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="{{ str_replace(' ', '_', $quality->name) }}">
                                        {{ ucwords(str_replace('_', ' ', $quality->name)) }}
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="@if($quality->type == 'Qualitative'){{ 'text' }}@else{{ 'number' }}@endif" class="form-control"
                                            id="{{ str_replace(' ', '_', $quality->name) }}"
                                            name="{{ str_replace(' ', '_', $quality->name) }}"
                                            value="{{ old(str_replace(' ', '_', $quality->name), $production_quality->{str_replace(' ', '_', $quality->name)}) }}"
                                            step="any"
                                        >
                                    </div>
                                </div>
                            @endforeach

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="status">
                                    {{ ucwords(str_replace('_', ' ', 'status')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="status" id="status" name="status" required>
                                        <option disabled selected>Select a status :</option>
                                        <option value="Processing" {{ $production_quality->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="Pass" {{ $production_quality->status == 'Pass' ? 'selected' : '' }}>Pass</option>
                                    </select>
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
        $('.production_id').select2({
            theme: 'bootstrap',
            width: '100%',
        });
        $('.status').select2({
            theme: 'bootstrap',
            width: '100%',
        });
    });
</script>
@endsection
