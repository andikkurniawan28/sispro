@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'formula')) }}
@endsection

@section('formula-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                {{-- <div class="btn-group" role="group" aria-label="manage">
                    <a href="{{ route('formula.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div> --}}
                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-bordered table-hovered" id="formula_table" width="100%">
                        <thead>
                            <tr>
                                <th>{{ ucwords(str_replace('_', ' ', 'product_code')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'product_name')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'formula_list')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'manage')) }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize DataTable only if it is not already initialized
            if (!$.fn.DataTable.isDataTable('#formula_table')) {
                var table = $('#formula_table').DataTable({
                    layout: {
                        bottomStart: {
                            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                        },
                    },
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('formula.index') }}",
                    order: [
                        [0, 'desc']
                    ],
                    columns: [
                        { data: 'code', name: 'code' },
                        { data: 'name', name: 'name' },
                        { data: 'formula_list', name: 'formula_list', orderable: false, searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    initComplete: function(settings, json) {
                        var api = this.api();
                        var headers = api.columns().header();
                        // Optional: Custom header processing if needed
                    }
                });
            }
        });
    </script>
@endsection
