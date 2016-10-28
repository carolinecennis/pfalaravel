<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/14/16
 * Time: 4:37 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class DupTable extends Model{

    public function __construct($attributes = array())  {
        parent::__construct($attributes); // Eloquent
        $this->division = $attributes;
        if(!empty($attributes)) {
            $this->table = $attributes['division'] . "_Dups";
        }
    }

    public $id;
    public $division;
    protected $table;
    protected $connection = 'mysql';
    public $timestamps = false;

    public $concatCkBatch;

    protected $fillable = [
        'concatCkBatch'
        ];

    public function getMatchedRows($query)
    {
        return $query->select('id')->havingRaw("COUNT(concatCkBatch) > 1")
            ->get();
    }

} 