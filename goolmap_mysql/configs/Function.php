<?php
/**
 * Created by PhpStorm.
 * User: administrator
 * Date: 12/1/2016
 * Time: 3:24 PM
 */
    include_once dirname(__FILE__)."/mysqlConfig.php";
    $query = "SELECT State, AvgWages FROM zipcode_info";

    $result = mysqli_query($GLOBALS['conn'], $query);
    $highArr = array();
    $lowArr = array();
        while ($row = mysqli_fetch_assoc($result)) {
            if (count($highArr) < 5)
            {
                $highArr[$row["State"]] = $row["AvgWages"];
                $lowArr[$row["State"]] = $row["AvgWages"];
                if (count($highArr) == 5)
                {
                    uasort($lowArr, 'cmp');
                    uasort($highArr, 'cmp1');
                }
            }
            else{
                $val = floatval($row["AvgWages"]);
                $firstH = array_values($highArr);
                $lastL = array_values($lowArr);
                if (floatval($firstH[4]) <= $val)
                {
                    $highArr[$row["State"]] = $val;
                    uasort($highArr, 'cmp1');
                    array_pop($highArr);
                }
                if (floatval($lastL[4]) >= $val)
                {
                    $lowArr[$row["State"]] = $val;
                    uasort($lowArr, 'cmp');
                    array_pop($lowArr);
                }

            }

        }
    $resultArrH = array();
    $resultArrL = array();
    foreach ($highArr as $key=>$value) {
        $query = "SELECT AsText(g) FROM test WHERE State='$key'";
        $result = mysqli_query($GLOBALS['conn'], $query);
        $coordinates = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $coordinates = $coordinates.$row["AsText(SHAPE)"];
        }
        $resultArrH[$value] = $coordinates;
    }
    foreach ($lowArr as $key=>$value) {
        $query = "SELECT AsText(SHAPE) FROM test WHERE state_name='$key'";
        $result = mysqli_query($GLOBALS['conn'], $query);
        $coordinates = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $coordinates = $coordinates.$row["AsText(SHAPE)"];
        }
        $resultArrL[$value] = $coordinates;
    }
    mysqli_close($GLOBALS['conn']);
    $result = array("high"=>$resultArrH, "low" => $resultArrL);
    echo json_encode($result);
    function cmp($a, $b) {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
    function cmp1($a, $b) {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? 1 : -1;
    }
//function array_put_to_position(&$array, $object, $position, $name = null)
//{
//    $count = 0;
//    $return = array();
//    foreach ($array as $k => $v)
//    {
//        // insert new object
//        if ($count == $position)
//        {
//            if (!$name) $name = $count;
//            $return[$name] = $object;
//            $inserted = true;
//        }
//        // insert old object
//        $return[$k] = $v;
//        $count++;
//    }
//    if (!$name) $name = $count;
//    if (!$inserted) $return[$name];
//    $array = $return;
//    return $array;
//}
?>


