<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/10/16
 * Time: 10:37 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class MasterImport extends Model {

    protected $table = 'MASTER_import';
    protected $connection = 'mysql';
    public $timestamps = false;
    /**
     * primaryKey
     *
     * @var integer
     * @access protected
     */
    // protected $primaryKey = null;

//    public $AmountPaid;
//    public $InvoiceAmount;
//    public $ShipDate;
//    public $ShipmentNumber;
//    public $InvoiceNumber;
//    public $BillOfLading;
//    public $CarrierName;
//    public $CheckNumber;
//    public $CheckDate;
//    public $RunNumber;
//    public $ShipperCity;
//    public $ShipperState;
//    public $ShipperName;
//    public $ConsigneeCity;
//    public $ConsigneeState;
//    public $ConsigneeName;
//    public $BatchNumber;
//    public $ActualWeight;
//    public $Location;
//    public $Link;
//    public $Division;

    protected $fillable = [
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
    ];

}
