<?php

return [

    'sourceDir' => '/Users/cennis/Documents/',
    'destinationDir' => '/Users/cennis/Documents/',
    'columnNames' => ['AMTPAID',
        'INVAMT',
        'SHIPDATE',
        'SHIPMENT',
        'INVOICE',
        'LADING',
        'VENDNAME',
        'CHCKNUM',
        'CHCKSENT',
        'RUN',
        'SCITY',
        'SSTATE',
        'SCOMP',
        'RCITY',
        'RSTATE',
        'RCOMP',
        'BATCH',
        'ACTWGT',
        'LOCATION',
        'IMAGEID',
        'DIVISION'],

    'reportHeaders' => ['id',
        'dupCheck',
        'isDupRecord',
        'isSummedCheck',
        'isDupAmtPaid',
        'isDupShipment',
        'isDupBOL',
        'isDupInvoice',
        'AmountPaid',
        'OriginalAmtPaid',
        'InvoiceAmount',
        'ShipDate',
        'ShipmentNumber',
        'cleanShipment',
        'InvoiceNumber',
        'cleanInvoice',
        'BillOfLading',
        'cleanBOL',
        'CarrierName',
        'CheckNumber',
        'CheckDate',
        'RunNumber',
        'ShipperCity',
        'ShipperState',
        'ShipperName',
        'ConsigneeCity',
        'ConsigneeState',
        'ConsigneeName',
        'BatchNumber',
        'ActualWeight',
        'Location',
        'Link',
        'Division',
        'importDate'],

    'zeroPaidHeaders' => ['id',
        'importAmtPaid',
        'AmountPaid',
        'OriginalAmtPaid',
        'importInvoiceAmt',
        'InvoiceAmount',
        'ShipDate',
        'ShipmentNumber',
        'InvoiceNumber',
        'BillOfLading',
        'CarrierName',
        'CheckNumber',
        'CheckDate',
        'RunNumber',
        'ShipperCity',
        'ShipperState',
        'ShipperName',
        'ConsigneeCity',
        'ConsigneeState',
        'ConsigneeName',
        'BatchNumber',
        'ActualWeight',
        'Location',
        'Link',
        'Division',
        'importDate'],

        'masterImportFillable' => [
        'AmountPaid',
        'InvoiceAmount',
        'ShipDate',
        'ShipmentNumber',
        'InvoiceNumber',
        'BillOfLading',
        'CarrierName',
        'CheckNumber',
        'CheckDate',
        'RunNumber',
        'ShipperCity',
        'ShipperState',
        'ShipperName',
        'ConsigneeCity',
        'ConsigneeState',
        'ConsigneeName',
        'BatchNumber',
        'ActualWeight',
        'Location',
        'Link',
        'Division'
        ]
];


?>
