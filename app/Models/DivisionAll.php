<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/11/16
 * Time: 6:31 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;


class DivisionAll extends Model{

    protected $table = 'Division_All';
    protected $connection = 'mysql';
    public $timestamps = false;

    public $id;
    public $division;

    protected $fillable = [
        'division'
    ];

} 