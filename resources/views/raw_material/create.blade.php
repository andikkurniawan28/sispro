@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_raw_material')) }}
@endsection

@section('raw_material-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("raw_material.index") }}">{{ ucwords(str_replace('_', ' ', 'raw_material')) }}</a></li>
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
                        <form action="{{ route("raw_material.store") }}" method="POST">
                            @csrf @method("POST")

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="code">
                                    {{ ucwords(str_replace('_', ' ', 'code')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="code" name="code" value="{{ old("code") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="raw_material_category_id">
                                    {{ ucwords(str_replace('_', ' ', 'raw_material_category')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="raw_material_category_id" id="raw_material_category_id" name="raw_material_category_id" required autofocus>
                                        <option disabled selected>Select a raw material category :</option>
                                        @foreach ($raw_material_categories as $raw_material_category)
                                            <option value="{{ $raw_material_category->id }}">@php echo ucwords(str_replace('_', ' ', $raw_material_category->name)); @endphp</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="unit_id">
                                    {{ ucwords(str_replace('_', ' ', 'unit')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="unit_id" id="unit_id" name="unit_id" required autofocus>
                                        <option disabled selected>Select a unit :</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }} (@php echo str_replace('_', ' ', $unit->symbol); @endphp)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row justify-content-end">
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
<script>
    $(document).ready(function() {
        $('.raw_material_category_id').select2({
            theme: 'bootstrap',
            width: '100%',
        });
        $('.unit_id').select2({
            theme: 'bootstrap',
            width: '100%',
        });
    });
</script>
@endsection
