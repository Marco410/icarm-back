<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ApiModel extends Model
{
    public function helloWorld()
    {
        return "Hello world!!!";
    }

    // private function _getConnection()
    // {
    //     $dbConn = mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'));

    //     $dbConn->set_charset("utf8");

    //     return $dbConn;
    // }

    private static function _getConnection()
    {
        $dbConn = mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'));

        $dbConn->set_charset("utf8");

        return $dbConn;
    }

    public static function dbSelect($query, $bindings, $firstResult = false)
    {
        $rslts = DB::select($query, $bindings);
        
        if($firstResult){
            return reset($rslts);
        }

        return $rslts;
    }

    public static function simpleQuery($query, $firstResult = false, $arrayResulset = true)
    {
        $conn = self::_getConnection();

        $_SESSION['DB']['queryLog']['simple'][] = preg_replace("@\n@", "", $query);

        $result = $conn->query($query);
        
        $conn->close();

        if ($arrayResulset) {
            if ($firstResult) {
                $rslts = self::convertResulsetToArray($result);
                if (!empty($rslts)) {
                    return reset($rslts);
                }
                return array();
            }
            return self::convertResulsetToArray($result);
        }
        return $result;
    }

    /**
     * Executes a multiquery and returns all the resultsets in an array
     * 
     * @param string $sql contiene el query que se va a ejecutar
     * @return object mysqli_result
     */
    public static function multiQuery($sql, $arrayResulset = true)
    {
        $conn = self::_getConnection();

        $_SESSION['DB']['queryLog']['multi'][] = preg_replace("@\n@", "", $sql);

        $conn->multi_query($sql);

        $result = array();

        do {
            if ($arrayResulset) {
                array_push($result, self::convertResulsetToArray($conn->store_result()));
            } else {
                array_push($result, $conn->store_result());
            }
        } while (mysqli_more_results($conn) && mysqli_next_result($conn));

        $conn->close();

        return $result;
    }

    private static function convertResulsetToArray($results)
    {
        $data = array();
        if (!empty($results)) {
            if (!is_bool($results)) {
                foreach (self::cast_query_results($results) as $rslt) {
                    $data[] = $rslt;
                }
            } else
                return $results;
        }
        return $data;
    }

    public static function cast_query_results($rs)
    {
        $fields = mysqli_fetch_fields($rs);
        $data = array();
        $types = array();
        foreach ($fields as $field) {
            switch ($field->type) {
                case 1:
                case 3:
                    $types[$field->name] = 'int';
                    break;
                case 4:
                    $types[$field->name] = 'float';
                    break;
                default:
                    $types[$field->name] = 'string';
                    break;
            }
        }
        while ($row = mysqli_fetch_assoc($rs)) array_push($data, $row);
        for ($i = 0; $i < count($data); $i++) {
            foreach ($types as $name => $type) {
                settype($data[$i][$name], $type);
            }
        }
        return $data;
    }

    public static function killProcess()
    {
        $data = self::simpleQuery('SHOW FULL PROCESSLIST');
        foreach ($data as $dt) {
            if ($dt['Time'] > 200) {
                self::simpleQuery('KILL ' . $dt['Id']);
            }
        }

        return true;
    }

    public static function getDataFormatDT($data, $query, $getRecordsTotal = false, $json = false)
    {
        $draw = 1;
        $search = '';
        $order = '';
        $columns = '';
        $aliasTable = 'List';
        $limit = 10;
        $offset = 0;
        $result = '';

        if (isset($data['draw']))
            $draw = $data['draw'];
        if (!empty($data['search']['value']))
            $search = $data['search']['value'];
        if (!empty($data['order'])) {
            $dataOrders = array();
            foreach ($data['order'] as $orders) {
                if (isset($data['columns'][$orders['column']]['data']))
                    $dataOrders[] = $data['columns'][$orders['column']]['data'] . ' ' . $orders['dir'];
            }
            if (!empty($dataOrders)) {
                $order = ' ORDER BY ' . implode(', ', $dataOrders);
            }
        }
        if (!empty($data['columns'])) {
            $columns = $data['columns'];
        }
        if (!empty($data['length'])) {
            $limit = $data['length'];
        }
        if (!empty($data['start'])) {
            $offset = $data['start'];
        }

        if (!empty($data['aliasTable'])) {
            $aliasTable = $data['aliasTable'];
        }

        //dd($columns);

        $result = self::getResultFormatDT($draw, $search, $query, $columns, $aliasTable, $order, $limit, $offset, $getRecordsTotal);

        if ($json) {
            die(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_HEX_AMP));
        }

        return $result;
    }

    private static function getResultFormatDT($draw, $searchValue = null, $query = null, $columns = null, $aliasTable = 'List', $order = null, $limit = null, $offset = null, $getRecordsTotal = false)
    {
        $completeFields = '';
        $fields = '';
        $i = 0;
        foreach ($columns as $k => $v) {
            if (!empty($v['data'])) {
                $m = explode('.', $v['data']);
                if (count($m) > 0) {
                    $m = array_reverse($m);
                    $fields .= ($i == 0) ? ($m[1] . '.' . $m[0]) : (', ' . $m[1] . '.' . $m[0]);
                    $completeFields .= ($i == 0) ? $v['data'] : ', ' . $v['data'];
                } else {
                    $fields .= ($i == 0) ? $v['data'] : ', ' . $v['data'];
                    $completeFields .= ($i == 0) ? $v['data'] : ', ' . $v['data'];
                }
                $i++;
            }
        }

        $conditions = self::getConditionsFormatDT($searchValue, $columns);

        // $sql = 'SELECT sql_calc_found_rows * FROM (' . $query . ') ' . $aliasTable . '  ' . $conditions . ' ' . $order;
        // $sqlFoundRows = ' SELECT found_rows() AS total; ';

        $sql = 'SELECT * FROM (' . $query . ') ' . $aliasTable . '  ' . $conditions . ' ' . $order;

        $sqlFoundRows = ' SELECT count(*) AS total FROM (' . $query . ') ' . $aliasTable . '  ' . $conditions . '; ';

        $sqlRecordsTotal = '';

        if ($getRecordsTotal && $conditions != '') {
            $sqlRecordsTotal = ' SELECT count(*) AS total FROM (' . $query . ') ' . $aliasTable . ';';
        }

        if ($limit > 0) {
            $sql .= ' LIMIT ' . $offset . ',' . $limit . ';';
        } else {
            $sql .= ' ; ';
        }

        $resultset = self::multiQuery($sql . $sqlFoundRows . $sqlRecordsTotal);

        $recordsFiltered = isset($resultset[1][0]['total']) ? $resultset[1][0]['total'] : 0;

        $recordsTotal = isset($resultset[2][0]['total']) ? $resultset[2][0]['total'] : null;

        if ($conditions == '') {
            $recordsTotal = $recordsFiltered;
        }


        $data = array();

        $i = 0;
        foreach ($resultset[0] as $rslt) {
            //dbg($rslt);
            if ($aliasTable == '') {
                // Get field information for all columns
                $rslt = $resultset[0]->fetch_fields();
                //                dbg($rslt);
                $id_flag = true;
                foreach ($rslt as $val) {

                    $data[$i][$val->table][$val->name] = ($rslt[$val->name]);

                    if (strpos($val->name, 'id') > 0 && $id_flag) {
                        $data[$i]["DT_RowId"] = 'row_' . $rslt[$val->name];
                        $id_flag = false;
                    }
                }
            } else {
                $id_flag = true;
                foreach ($rslt as $key => $value) {
                    // $data[$i][$aliasTable][$key] = utf8_encode($value);
                    $data[$i][$aliasTable][$key] = ($value);
                    if ($key == 'id' && $id_flag) {
                        $data[$i]["DT_RowId"] = 'row_' . $i;
                        $id_flag = false;
                    }
                }
            }

            $i++;
        }

        return array(
            "draw" => $draw,
            "recordsFiltered" => $recordsFiltered,
            "recordsTotal" => $recordsTotal,
            "data" => $data
        );
    }

    private static function getConditionsFormatDT($searchValue = null, $columns = null)
    {
        $conditions = '';
        $firstCondition = false;

        if (!empty($searchValue)) {
            // Obtenemos las columnas por las que se puede buscar
            $i = 0;
            foreach ($columns as $AndOr => $v) {
                if (!empty($v['data'])) {
                    if (!empty($v['searchable'])) {
                        $m = explode('.', $v['data']);
                        if (count($m) > 0) {
                            $m = array_reverse($m);
                            if (!$firstCondition) {
                                $conditions .= ' WHERE (' . $m[1] . '.' . $m[0];
                                $firstCondition = true;
                            } else
                                $conditions .= ' OR ' . $m[1] . '.' . $m[0];

                            $conditions .= ' LIKE "%' . $searchValue . '%"';
                        } else {
                            if (!$firstCondition) {
                                $conditions .= ' WHERE (' . $m[0];
                                $firstCondition = true;
                            } else
                                $conditions .= ' OR ' . $m[0];

                            $conditions .= ' LIKE "%' . $searchValue . '%"';
                        }
                    }
                }
            }
        }

        if ($conditions != '') {
            $conditions .= ')';
        }

        return $conditions;
    }
}
