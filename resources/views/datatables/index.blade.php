@extends('layouts.main')

@section('content')
<table class="table table-bordered" id="report-table">
    <thead>
    <tr>
        <th>id</th>
        <th>dupCheck</th>
        <th>isDupRecord</th>
        <th>isSummedCheck</th>
        <th>isDupAmtPaid</th>
        <th>isDupShipment</th>
        <th>isDupBOL</th>
        <th>isDupInvoice</th>
        <th>AmountPaid</th>
        <th>OriginalAmtPaid</th>
        <th>InvoiceAmount</th>
        <th>ShipDate</th>
        <th>ShipmentNumber</th>
        <th>cleanShipment</th>
        <th>InvoiceNumber</th>
        <th>cleanInvoice</th>
        <th>BillOfLading</th>
        <th>cleanBOL</th>
        <th>CarrierName</th>
        <th>CheckNumber</th>
        <th>CheckDate</th>
        <th>RunNumber</th>
        <th>ShipperCity</th>
        <th>ShipperState</th>
        <th>ShipperName</th>
        <th>ConsigneeCity</th>
        <th>ConsigneeState</th>
        <th>ConsigneeName</th>
        <th>BatchNumber</th>
        <th>ActualWeight</th>
        <th>Location</th>
        <th>Link</th>
        <th>Division</th>
        <th>importDate</th>
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
            ajax: '{!! route('datatables.data') !!}',
            columns: [
            {data: 'id', name: 'id'},
            {data: 'dupCheck', name: 'dupCheck'},
            {data: 'isDupRecord', name: 'isDupRecord'},
            {data: 'isSummedCheck', name: 'isSummedCheck'},
            {data: 'isDupAmtPaid', name: 'isDupAmtPaid'},
            {data: 'isDupShipment', name: 'isDupShipment'},
            {data: 'isDupBOL', name: 'isDupBOL'},
            {data: 'isDupInvoice', name: 'isDupInvoice'},
            {data: 'AmountPaid', name: 'AmountPaid'},
            {data: 'OriginalAmtPaid', name: 'OriginalAmtPaid'},
            {data: 'InvoiceAmount', name: 'InvoiceAmount'},
            {data: 'ShipDate', name: 'ShipDate'},
            {data: 'ShipmentNumber', name: 'ShipmentNumber'},
            {data: 'cleanShipment', name: 'cleanShipment'},
            {data: 'InvoiceNumber', name: 'InvoiceNumber'},
            {data: 'cleanInvoice', name: 'cleanInvoice'},
            {data: 'BillOfLading', name: 'BillOfLading'},
            {data: 'cleanBOL', name: 'cleanBOL'},
            {data: 'CarrierName', name: 'CarrierName'},
            {data: 'CheckNumber', name: 'CheckNumber'},
            {data: 'CheckDate', name: 'CheckDate'},
            {data: 'RunNumber', name: 'RunNumber'},
            {data: 'ShipperCity', name: 'ShipperCity'},
            {data: 'ShipperState', name: 'ShipperState'},
            {data: 'ShipperName', name: 'ShipperName'},
            {data: 'ConsigneeCity', name: 'ConsigneeCity'},
            {data: 'ConsigneeState', name: 'ConsigneeState'},
            {data: 'ConsigneeName', name: 'ConsigneeName'},
            {data: 'BatchNumber', name: 'BatchNumber'},
            {data: 'ActualWeight', name: 'ActualWeight'},
            {data: 'Location', name: 'Location'},
            {data: 'Link', name: 'Link'},
            {data: 'Division', name: 'Division'},
            {data: 'importDate', name: 'importDate'}
            ]
        });
    });
</script>
@endpush