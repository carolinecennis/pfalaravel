@extends('layouts.main')

@section('content')
<table class="table table-bordered" id="report-table">
    <thead>
    <tr>
        <th>Id</th>
        <th>AmountPaid</th>
        <th>OriginalAmtPaid</th>
    </tr>
    </thead>
</table>
@stop

@push('scripts')
<script>
    $(function() {
        $('#report-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('export') !!}',
            columns: [
            { data: 'id', name: 'id' },
            { data: 'AmountPaid', name: 'AmountPaid' },
            { data: 'OriginalAmtPaid', name: 'OriginalAmtPaid' }
        ]
    });
    });
</script>
@endpush