<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/19/16
 * Time: 4:34 PM
 */

namespace App\Services;
use App\Models\DivisionMetadata;
use App\Models\MasterImport;
use DB;
use Illuminate\Support\Facades\Hash;


class DivisionService
{
    public function getDivisions() {

        $divisionList = DivisionMetadata::orderBy('division')->lists('division', 'division');

        $divisions = $divisionList->unique();

        return $divisions;
    }

    public function getImportDivision() {

        $currentDivision = DB::table('MASTER_import')->select('division')->first();

        return $currentDivision->division;
    }

    public function updateDivisionMetadata($fileHash) {

        $division = $this->getImportDivision();
        $date = date('Y-m-d');
        $count = DB::table('MASTER_import')->count();

        $repeat = DivisionMetadata::where(['fileHash' => $fileHash])->count();
        $isRepeat = false;

        if($repeat == 0) {
            DivisionMetadata::create(['division' => $division,
                'importDate' => $date,
                'countImported' => $count,
                'fileHash' => $fileHash]);
            $isRepeat = false;

        } else {
            $isRepeat = true;
        }
        return $isRepeat;
    }
}
