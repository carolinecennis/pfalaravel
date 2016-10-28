<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/18/16
 * Time: 8:59 AM
 */

namespace app\Models;

use Illuminate\Database\Eloquent\Model as Model;


class DivisionMetadata extends Model{

    protected $table = 'Division_Metadata';
    protected $connection = 'mysql';
    public $timestamps = false;

    public $id;
    public $division;
    public $importDate;
    public $countImported;
    public $fileHash;

    protected $fillable = [
        'division',
        'importDate',
        'countImported',
        'fileHash'
    ];

} 
