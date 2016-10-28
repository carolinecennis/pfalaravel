<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/15/16
 * Time: 3:38 PM
 */

namespace App\Services;

use App\Models\MasterTable;
use App\Models\DupTable;
use App\Models\CleanTable;
use DB;
use Log;
use App\Services\MultiCurlService;

class SumChecksService
{

    public $masterTable;
    public $dupTable;
    public $cleanTable;
    public $concatVariables;

    public function __construct($division)
    {
        $this->division = $division;
        $masterTable = new MasterTable(['division' => $division]);
        $dupTable = new DupTable(['division' => $division]);
        $cleanTable = new CleanTable(['division' => $division]);

        $this->masterTable = $masterTable;
        $this->dupTable = $dupTable;
        $this->cleanTable = $cleanTable;

        $this->concatVariables= array(
            'CheckNumber',
            'BatchNumber',
            'ShipmentNumber',
            'InvoiceNumber',
            'CarrierName',
            'BillOfLading',
            'Division'
        );
    }

    public function resetAll()
    {
        $masterTable = $this->masterTable['table'];
        $cleanTable = $this->cleanTable['table'];

        Log::info('resetting all isSummedCheck/dupCheck');

        $affected = DB::update("UPDATE $masterTable x 
          JOIN $cleanTable c on c.id = x.id
          SET x.dupCheck = 0,
          c.dupCheck = 0,
          x.AmountPaid = x.OriginalAmtPaid");

//        $affected = DB::table($masterTable)
//          ->join($cleanTable, $cleanTable . '.id', '=', $masterTable . '.id')
//            ->update([$masterTable.'.isSummedCheck' => 0,
//                $masterTable.'.dupCheck' => 0,
//                $cleanTable.'.dupCheck' => 0,
//                $masterTable.'.AmountPaid' => DB::raw($masterTable.'.OriginalAmtPaid')]);

        Log::info('reset count for sum '.$affected);

    }

    public function sumLikeChecks($groupBy, $beginrunnumber)
    {

        $masterTable = $this->masterTable['table'];
        $cleanTable = $this->cleanTable['table'];
        $optionalGreaterThan = "";
        if($beginrunnumber != null){
            $optionalGreaterThan = "where RunNumber >= $beginrunnumber";
        }

        $concatVariables = $this->concatVariables;

        if ($groupBy != null) {
            foreach ($groupBy as $item) {
                array_push($concatVariables, $item);
            }
        }

        $tempTable = $this->division."_temp";

        $join = array();
        $count = count($concatVariables);
        foreach($concatVariables as $concatVar) {
            if (--$count <= 0) {
                $concatVar = 'a.' . $concatVar . ' <=> tsum.' . $concatVar;
                array_push($join, $concatVar);
            } else {
                $concatVar = 'a.' . $concatVar . ' <=> tsum.' . $concatVar. ' and ';
                array_push($join, $concatVar);
            }
        }

        $concatFields = implode(",",$concatVariables);
        $join = implode(" ", $join);

        $updateQuery =
        "CREATE TEMPORARY TABLE $tempTable
        select tsum.sum as checkSum, a.* from $masterTable a join (
        select $concatFields, sum(AmountPaid) as sum,
        count(*) as NumDuplicates from $masterTable
        $optionalGreaterThan 
        group by
        $concatFields having NumDuplicates  > 1 ORDER BY NULL) tsum on $join 
        $optionalGreaterThan";

            DB::STATEMENT($updateQuery);

            DB::update("UPDATE $masterTable a, $cleanTable c, $tempTable x
              SET a.AmountPaid = x.checkSum,
              a.isSummedCheck = 1,
              c.cleanAmtPaid = x.checkSum,
              a.dupCheck = 1,
              c.dupCheck = 1
              WHERE a.id = c.id
              and a.id = x.id");

        DB::update("UPDATE $masterTable x, $cleanTable c,
          (select id from $tempTable group by $concatFields) as xx
          SET x.dupCheck = 0,
          c.dupCheck = 0
          where c.id = x.id
          and x.id = xx.id");

        Log::info('finding checks to sum using query ' . $updateQuery);

    }

    public function partition($list, $p)
    {
        $listlen = count($list);
        $partlen = floor($listlen / $p);
        $partrem = $listlen % $p;
        $partition = array();
        $mark = 0;
        for ($px = 0; $px < $p; $px++) {
            $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
            $partition[$px] = array_slice($list, $mark, $incr);
            $mark += $incr;
        }
        return $partition;
    }
}

