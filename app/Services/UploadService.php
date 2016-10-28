<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/11/16
 * Time: 1:30 PM
 */

namespace App\Services;

use App\Models\MasterImport;
use Config;
use DB;
use Excel;
use Illuminate\Support\Facades\Redirect;
use Akeneo\Component\SpreadsheetParser\SpreadsheetParser;


class UploadService
{

    private $sourceDir;
    private $names;
    private $fillable;
    private $file;
    private $errMessage = "";


    public function __construct()
    {
    }

    public function findColumns($file)
    {
        $extension = $file->getClientOriginalExtension(); // getting image extension
        $destinationDir = Config::get('settings.destinationDir');

        $destinationFile = $destinationDir.$file->getClientOriginalName();
        $newFileName = 'import.'.$extension;
        $file->move($destinationDir, $newFileName); // uploading file to given path

        MasterImport::truncate();

        $this->sourceDir = Config::get('settings.sourceDir');
        $this->names = Config::get('settings.columnNames');
        $this->fillable = Config::get('settings.masterImportFillable');
        $isSuccess = true;
        $isError = false;

        $destinationFile = $this->sourceDir . $newFileName;

        if ($extension == "xlsx") {

            $workbook = SpreadsheetParser::open($destinationFile);
            $myWorksheetIndex = $workbook->getWorksheetIndex($workbook->getWorksheets()[0]);
            $i = 0;

                foreach ($workbook->createRowIterator($myWorksheetIndex) as $rowIndex => $values) {
                    if ($i == 0) {
                        $headers = array_map('trim',(array_map('strtoupper', $values)));
                        $i++;
                    } else {
                        //checking for a duplicate column
                        $duplicates = $this->checkDuplicatesInArray($headers);
                        if ($duplicates != null) {
                            $cols = implode(",", (array)$duplicates);
                            $isSuccess = false;
                            $this->errMessage = "Column(s) duplicated: " . $cols;
                            break;
                        }
                        //checking for a missing column
                        if (array_diff($this->names, $headers)) {
                            $result = array_diff($this->names, $headers);
                            $cols = implode(",", $result);
                            $this->errMessage = "Cols missing: " . $cols;
                            $isSuccess = false;
                            break;
                        }
                        //check for one or more element diff caused by empty ending col, add empty string(s) to values array if found
                        if(count($headers) > count($values)){
                            $diff = count($headers) - count($values);
                            for($i=0; $i < $diff; $i++ ) {
                                array_push($values, "");
                            }
                        }
                        $results = json_decode(json_encode(array_combine($headers, $values)));

                        //box/spout takes formatted dates from excel and turns to objects. turn them back to string.
                        foreach($results as &$result) {
                            if (is_object($result)) {
                                $result = $result->date;
                            }
                        }

                        //getting headers, setting them to upper, putting to array as they are an attribute
                        foreach ($this->names as &$header) {
                            $header = $results->$header;
                        }

                        //create key value pairs of MasterImport's fillable items and excel column data
                        $masterImportArray = array_combine($this->fillable, $this->names);

                        $masterImport = new MasterImport();
                        $masterImport->fill($masterImportArray);
                        $masterImport->save();
                        //reset column names array as it was rewritten earlier
                        $this->names = Config::get('settings.columnNames');
                    }
                }
        } else {

            $picked = array();
            $isFirstRow = true;

            if (($handle = fopen($destinationFile, "r")) !== FALSE) {

                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    $numCols = count($data);
                    $data = array_map('trim', (array_map('strtoupper', $data)));
                    $row = array();

                    // make sure all necessary columns are present
                    if ($isFirstRow) {
                        foreach ($this->names as $value)
                            for ($c = 0;
                                 $c < $numCols;
                                 $c++)
                                if (!in_array($data[$c], $this->names))
                                    continue;
                                else
                                    if ($value === $data[$c]) {
                                        $columns[] = $data[$c];
                                        $picked[] = $c;
                                    }
                        $duplicates = $this->checkDuplicatesInArray($columns);
                        if ($duplicates != null) {
                            $cols = implode(",", (array)$duplicates);
                            $this->errMessage = "Column(s) duplicated: " . $cols;
                            break;
                        }
                        if (array_diff($this->names, $columns)) {
                            $result = array_diff($this->names, $columns);
                            $cols = implode(",", $result);
                            $this->errMessage = "Cols missing: " . $cols;
                            break;
                        }
                        $isFirstRow = false;
                        // process remaining rows
                    } else {
                        foreach ($picked as $p)
                            for ($c = 0; $c < $numCols; $c++)
                                if ($c == $p)
                                    $row[] = $this->cleanSymbol($data[$c]);

                        if ($row != $this->names) {
                            array_unshift($row, 'id');
                            DB::update('INSERT INTO MASTER_import VALUES("' . implode('", "', $row) . '")');
                        }
                    }
                }
            }
        }

        if (DB::table('MASTER_import')->count() > 0) {
            return true;
        } else {
            return $this->errMessage;
        }
    }

    public
    function checkDuplicatesInArray($array)
    {
        $duplicates = FALSE;
        $newArray = array();
        foreach ($array as $k => $i) {
            if (!isset($value_{$i})) {
                $value_{$i} = TRUE;
            } else {
                $duplicates |= TRUE;
                array_push($newArray, $i);
            }
        }
        return ($newArray);
    }

    public
    function cleanSymbol($variable)
    {
        $pattern = "~[^A-Za-z0-9?=+&.\\:#/ ]~";
        $replace = "";
        $variable = preg_replace($pattern, $replace, $variable);
        return $variable;
    }
}

