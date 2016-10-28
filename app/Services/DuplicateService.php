<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/17/16
 * Time: 7:22 PM
 */

namespace App\Services;

use App\Models\MasterTable;
use App\Models\DupTable;
use App\Models\CleanTable;
use DB;
use Log;

class DuplicateService
{

    public $masterTable;
    public $cleanTable;
    public $groupBy;
    public $date;
    public $varTypes = array('cleanShipment' => 'isDupShipment',
        'cleanInvoice' => 'isDupInvoice',
        'cleanBOL' => 'isDupBOL',
        'cleanAmtPaid' => 'isDupAmtPaid');

    public function __construct($division, $groupBy, $date)
    {
        $this->division = $division;
        $masterTable = new MasterTable(['division' => $division]);
        $cleanTable = new CleanTable(['division' => $division]);

        $this->masterTable = $masterTable;
        $this->cleanTable = $cleanTable;
        $this->groupBy = $groupBy;
        $this->date = $date;
    }

    public function getAnalyzeCount() {

        $masterTable = $this->masterTable['table'];

        $count = DB::table($masterTable)->count();

        return $count;
    }

    public function getDuplicates($beginrunnumber){
        $this->resetAll();
        $this->findDups($beginrunnumber);
    }

    public function resetAll()
    {

        DB::table($this->masterTable['table'])
            ->update(['isDupRecord' => 0,
                'isDupAmtPaid' => 0,
                'isDupShipment' => 0,
                'isDupBOL' => 0,
                'isDupInvoice' => 0,
            ]);
    }

    public function findDups($beginrunnumber)
    {
        foreach($this->varTypes as $key => $value) {

            $cleanTable = $this->cleanTable['table'];
            $masterTable = $this->masterTable['table'];
            $varType = $key;
            $i = 0;
            $x = "";

            $tempTable = $varType."_duplicates";
            $date = $this->date;
            $groupBy = $this->groupBy;
            $groupByCount = $this->groupBy;
            $groupByHaving = array();
            $groupByEquals = array();

            if ($groupBy == null) {
                $groupBy = array();
            } else {
                foreach ($groupByCount as &$item) {
                    $i == '0' ? $x = 'x' : $x = 'xx';
                    array_push($groupByHaving, $x.'>1');
                    array_push($groupByEquals, 'x.'.$item. '= a.'.$item);
                    $item = 'COUNT(a.' . $item.')'.$x;
                    $i++;
                }
                foreach ($groupBy as &$item) {
                    $item = 'a.'.$item;
                }
                array_unshift($groupBy,'a.cleanVar');
            }

            // array_push($groupBy, $cleanTable.'.'.$varType);
            $varType == 'cleanAmtPaid' ? $nullCheck = 0 : $nullCheck = "''";

            $groupBy = implode(',', $groupBy);
            $groupByCount = implode(',', $groupByCount);
            $groupByHaving = implode(' and ', $groupByHaving);
            $groupByEquals = implode(' and ', $groupByEquals);
            if($beginrunnumber == "") {
                $optionalGreaterThan = "";
            } else {
                $optionalGreaterThan = "and RunNumber >= $beginrunnumber";
            }

            Log::info('optionalGreaterThan= ' . $optionalGreaterThan);

            DB::statement("DROP TABLE if exists $tempTable");

            $tempQuery = "CREATE TABLE IF NOT EXISTS $tempTable 
              (INDEX `cleanVar`(`cleanVar`,`ShipperCity`,`RunNumber`)) 
              DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci
              AS SELECT x.*
              from   (
              select aa.id,
                 aa.$varType as cleanVar,
                 aa.dupCheck,
                 '$varType' as varType,
                  m.ShipperCity,
                  m.RunNumber,
                  aa.importDate
                 FROM  $cleanTable aa JOIN $masterTable m on m.id = aa.id 
                 JOIN (select a.$varType,
                              count(a.$varType) as count
                       from   $cleanTable a
                              JOIN (select $varType,
                                           dupCheck
                                    from   $cleanTable
                                    where  dupCheck = 0
                                           AND importDate = '$date'
                                           and $varType != $nullCheck) x
                                on x.$varType = a.$varType
                                   AND x.dupCheck = 0
                       group  by a.$varType) as xx
                   on xx.$varType = aa.$varType
          where  xx.count > 1
                 and aa.dupCheck = 0
                 $optionalGreaterThan 
                 order by aa.$varType desc)x";

        DB::statement($tempQuery);

        Log::info('tempquery= ' . $tempQuery);

            if ($groupBy == null) {

                $query = "UPDATE $masterTable a,
                 (SELECT xx.* from $tempTable xx JOIN
                 (SELECT * from $tempTable 
                 group by cleanVar, varType having count(cleanVar) > 1)
                 x on x.cleanVar = xx.cleanVar order by xx.cleanVar) z 
                 SET a.$value = 1 WHERE a.id = z.id";
                Log::info('updateQuery= ' . $query);
            } else {
                $query = "UPDATE $masterTable c, (
                SELECT a.* from $tempTable a JOIN(
                SELECT a.cleanVar, a.ShipperCity, a.RunNumber, $groupByCount  
                FROM $tempTable a
                JOIN (select cleanVar, ShipperCity, RunNumber 
                from $tempTable where importDate = '$date')x 
                on x.cleanVar = a.cleanVar
                AND $groupByEquals
                GROUP BY $groupBy  
                HAVING $groupByHaving
                ORDER BY NULL 
                )x on x.cleanVar = a.cleanVar 
                AND $groupByEquals)xx
                SET c.$value = 1 WHERE c.id = xx.id";
                Log::info('updateQuery= ' . $query);
            }

            try {
                $affected = DB::update($query);
                Log::info('query= ' . $query);
            } catch (\Exception $e) {
                Log::info("EXCEPTION" + $e->getMessage());
              //  $affected = DB::update($query);
            }

            Log::info($affected . ' affected for ' . $value);
        }
    }
}
