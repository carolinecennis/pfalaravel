<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/14/16
 * Time: 2:48 PM
 */

namespace app\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\DupTable;
use App\Models\CleanTable;


class MasterTable extends Model {

    public $master;

    public function __construct($attributes = array())  {
        parent::__construct($attributes); // Eloquent
        $this->division = $attributes;
        if(!empty($attributes)) {
            $this->table = $attributes['division'] . "_Master";
            $this->master = $this->table;
        }
    }

    public function dups($division)
    {
        $dupTable = new DupTable($this->division);
        return $this->hasOne($dupTable, 'id');
    }

    public function clean($division)
    {
        $cleanTable = new CleanTable($this->division);
        return $this->hasOne($cleanTable, 'id');
    }

    public $division;
    protected $table;
    protected $connection = 'mysql';
    public $timestamps = false;

    public $id;
    public $isDupRecord;
    public $isSummedCheck;
    public $isDupAmtPaid;
    public $isDupShipment;
    public $isDupBOL;
    public $isDupInvoice;
    public $dupCheck;
    public $AmountPaid;
    public $OriginalAmtPaid;
    public $InvoiceAmount;
    public $ShipmentNumber;
    public $InvoiceNumber;
    public $ShipDate;
    public $BillOfLading;
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
        'isDupRecord',
        'isSummedCheck',
        'isDupAmtPaid',
        'isDupShipment',
        'isDupBOL',
        'isDupInvoice',
        'dupCheck',
        'AmountPaid',
        'OriginalAmtPaid',
        'InvoiceAmount',
        'ShipmentNumber',
        'InvoiceNumber',
        'ShipDate',
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
        'importDate'
    ];

} 
