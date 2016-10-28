<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/15/16
 * Time: 2:57 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;


class CleanTable extends Model{

    public function __construct($attributes = array())  {
        parent::__construct($attributes); // Eloquent
        $this->division = $attributes;
        if(!empty($attributes)) {
            $this->table = $attributes['division'] . "_CleanData";
        }
    }

    protected $table;
    protected $connection = 'mysql';
    public $timestamps = false;

    public $id;
    public $cleanShipment;
    public $cleanInvoice;
    public $cleanBOL;
    public $cleanAmtPaid;
    public $division;
    public $importDate;
    public $dupCheck;

    protected $fillable = [
        'cleanShipment',
        'cleanInvoice',
        'cleanBOL',
        'cleanAmtPaid',
        'division',
        'importDate',
        'dupCheck'
    ];

} 