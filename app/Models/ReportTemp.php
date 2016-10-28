<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/20/16
 * Time: 7:35 PM
 */

namespace app\Models;

use Illuminate\Database\Eloquent\Model as Model;

class ReportTemp extends Model
{
    protected $table = 'ReportTemp';
    protected $connection = 'mysql';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    public $id;
    public $dupCheck;
    public $isDupRecord;
    public $isSummedCheck;
    public $isDupAmtPaid;
    public $isDupShipment;
    public $isDupBOL;
    public $isDupInvoice;
    public $AmountPaid;
    public $OriginalAmtPaid;
    public $InvoiceAmount;
    public $ShipDate;
    public $ShipmentNumber;
    public $cleanShipment;
    public $InvoiceNumber;
    public $cleanInvoice;
    public $BillOfLading;
    public $cleanBOL;
    public $CarrierName;
    public $CheckNumber;
    public $CheckDate;
    public $RunNumber;
    public $ShipperCity;
    public $ShipperState;
    public $ShipperName;
    public $ConsigneeCity;
    public $ConsigneeState;
    public $ConsigneeName;
    public $BatchNumber;
    public $ActualWeight;
    public $Location;
    public $Link;
    public $Division;
    public $importDate;

    protected $fillable = [
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
        'importDate'
    ];
}