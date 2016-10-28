<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/20/16
 * Time: 12:18 PM
 */

namespace App\Services;

use DB;

class ExportService
{
    public $exportType;
    public $reportType;
    public $division;

    public function __construct($reportType, $exportType, $division)
    {
        $this->division = $division;
        $this->exportType = $exportType;
        $this->reportType = $reportType;
        $this->fileDate = (new \DateTime())->format('Y-m-d H.i.s');
    }

    public $sqlSelectReport = array(
        'id',
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
        'importDate');

    public function getExportFields()
    {

        $reportType = 'possibleDuplicates';
        $colNames = $this->sqlSelectReport;
        $fileDate = (new \DateTime())->format('Y-m-d H.i.s');
        $data = DB::table('ReportTemp')->get();

        // How many items to list per page
        $limit = 50;
        $total = count($data);

//        if($total == 0){
//            header("location:reports.php?status=NoRecords");
//
//        }

// How many pages will there be
        $pages = ceil($total / $limit);

// What page are we currently on?
        $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
            'options' => array(
                'default' => 1,
                'min_range' => 1,
            ),
        )));

        // Calculate the offset for the query
        $offset = ($page - 1) * $limit;

// Some information to display to the user
        $start = $offset + 1;
        $end = min(($offset + $limit), $total);

// The "back" link
        $prevlink = ($page > 1) ? '<a href="?page=1" title="First page" class="blue">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page" class="blue">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

// The "forward" link
        $nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page" class="blue">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page" class="blue">&raquo;</a>' : '<span class="disabled">&rsaquo;" </span> <span class="disabled">&raquo;</span>';

        $exportArray = (['reportType' => $reportType,
            'start' => $start,
            'end' => $end,
            'total' => $total,
            'nextlink' => $nextlink,
            'prevlink' => $prevlink,
            'page' => $page,
            'pages' => $pages,
            'colNames' => $colNames,
            'fileDate' => $fileDate
        ]);

        return $exportArray;

    }
}