<?php
header("Content-type:text/html;charset=utf-8");
class EasySQL {
    public $HOST;
    public $USER;
    public $PASS;
    public $CONNECT;
    public $TABLE;
    public $LINE;
    public $ROW;

    function __construct($HOST, $USER, $PASS, $BASE, $TABLE) {
        $this -> HOST = $HOST;
        $this -> USER = $USER;
        $this -> PASS = $PASS;
        $this -> BASE = $BASE;
        $this -> TABLE = $TABLE;
        $CONNECT = mysqli_connect($HOST, $USER, $PASS);
        $this -> CONNECT = $CONNECT;
        mysqli_query($this -> CONNECT, "set names utf8");
        mysqli_select_db($this -> CONNECT, $BASE);
        $this -> size();
        $this -> col();
        $this -> cross();
        for ($LINE_temp = 0; $LINE_temp < $this -> length; $LINE_temp++) {
            $LINE[$LINE_temp] = $LINE_temp;
        }
        for ($ROW_temp = 0; $ROW_temp < $this -> width; $ROW_temp++) {
            $ROW[$ROW_temp] = $ROW_temp;
        }
        $this -> LINE = $LINE;
        $this -> ROW = $ROW;
    }

    function run($RUN) {
        $DO_RUN = mysqli_query($this -> CONNECT, $RUN);
    }

    function get($SEARCH) {
        $DO_SEARCH = mysqli_query($this -> CONNECT, $SEARCH);
        $GET_SEARCH = mysqli_fetch_array($DO_SEARCH, MYSQLI_ASSOC);
        $this -> GET = $GET_SEARCH;
    }

    function eget($E_Q, $E_A) {
        $E_SEARCH = "SELECT * FROM {$this->TABLE} WHERE {$E_Q} = {$E_A}";
        $this -> get($E_SEARCH);
    }

    function size($Another_TABLE) {
        if ($Another_TABLE == '') {
            $Another_TABLE = $this -> TABLE;
        }
        $STRLEN = "SELECT * FROM {$Another_TABLE}";
        $DO_STRLEN = mysqli_query($this -> CONNECT, $STRLEN);
        $DO_STRLEN_id = 0;
        while ($GET_STRLEN = mysqli_fetch_array($DO_STRLEN, MYSQLI_ASSOC)) {
            $DO_STRLEN_id++;
        }
        $this -> length = $DO_STRLEN_id;
        return $DO_STRLEN_id;
    }

    function cross($Another_TABLE) {
        if ($Another_TABLE == '') {
            $Another_TABLE = $this -> TABLE;
        }
        $FUNC_FOR = "SELECT * FROM {$Another_TABLE}";
        $DO_FUNC_FOR = mysqli_query($this -> CONNECT, $FUNC_FOR);
        $FOR_ROW = 0;
        while ($GET_FUNC_FOR = mysqli_fetch_row($DO_FUNC_FOR)) {
            for ($FOR_COL = 0; $FOR_COL < $this -> width; $FOR_COL++) {
                $this -> CROSS[$FOR_ROW][$FOR_COL] = $GET_FUNC_FOR[$FOR_COL];
            }
            $FOR_ROW++;
        }
    }

    function col($Another_TABLE) {
        if ($Another_TABLE == '') {
            $Another_TABLE = $this -> TABLE;
        }
        $ALLC_id = 0;
        $QU_ALLC = "SHOW FULL COLUMNS FROM {$Another_TABLE}";
        $RUN_ALLC = mysqli_query($this -> CONNECT, $QU_ALLC);
        while ($SHOW_ALLC = mysqli_fetch_array($RUN_ALLC)) {
            $GET_ALLC[$ALLC_id] = $SHOW_ALLC['Field'];
            $ALLC_id++;
        }
        $this -> COL = $GET_ALLC;
        $this -> width = $ALLC_id;
    }

    function add($addArr) {
        $add_first = "INSERT INTO {$this->TABLE}".
        "({$this->COL[0]})".
        "VALUES ".
        "('{$addArr[0]}')";
        $this -> run($add_first);
        for ($addtemp = 1; $addtemp < $this -> width; $addtemp++) {
            $add_more = "UPDATE {$this->TABLE} SET {$this->COL[$addtemp]}='{$addArr[$addtemp]}' WHERE {$this->COL[0]}='{$addArr[0]}'";
            $this -> run($add_more);
        }
    }

    function edit($line, $editArr) {
        $hook_edit = $this -> CROSS[$line][0];
        $new_editArr = array();
        for ($edit_temp = 0; $edit_temp < $this -> width; $edit_temp++) {
            if ($editArr[$edit_temp] == "") {
                $new_editArr[$edit_temp] = $this -> CROSS[$line][$edit_temp];
            } else {
                $new_editArr[$edit_temp] = $editArr[$edit_temp];
            }
        }
        for ($edit_run_temp = 0; $edit_run_temp < $this -> width; $edit_run_temp++) {
            $edit_more = "UPDATE {$this->TABLE} SET {$this->COL[$edit_run_temp]}='{$new_editArr[$edit_run_temp]}' WHERE {$this->COL[0]}='{$hook_edit}'";
            $this -> run($edit_more);
        }
    }

    function delete($line) {
        $delete = "DELETE FROM {$this->TABLE} WHERE {$this->COL[0]}='{$this->CROSS[$line][0]}'";
        $this -> run($delete);
    }

    function seek($SEARCH) {
        $fetch = array();
        $fetch_avail = array();
        $fetch_avail_id = -1;
        for ($inSEARCH = 0; $inSEARCH < $this -> width; $inSEARCH++) {
            if ($SEARCH[$inSEARCH] != "") {
                $fetch_avail_id++;
                $fetch_avail[$fetch_avail_id] = $inSEARCH;
                $fetch_id = 0;
                for ($inTable = 0; $inTable < $this -> length; $inTable++) {
                    if ($this -> CROSS[$inTable][$inSEARCH] == $SEARCH[$inSEARCH]) {
                        $fetch[$inSEARCH][$fetch_id++] = $inTable;
                    }
                }
            }
        }
        $seek = $fetch[$fetch_avail[$fetch_avail_id]];
        for ($all_in_fetch = 0; $all_in_fetch < $fetch_avail_id; $all_in_fetch++) {
            $seek = array_intersect($seek, $fetch[$fetch_avail[$all_in_fetch]]);
        }
        $seek = array_values($seek);
        $this -> seek = $seek;
        $this -> seeker = count($seek);
        if ($fetch_avail_id == -1) {
            $this -> seek = $this -> LINE;
            $this -> seeker = count($this -> seek);
        }
    }

    function order($ordercol, $orderfunc) {
        $neworder = $this -> LINE;
        $tempCross = $this -> CROSS;
        $newordercross = array();
        for ($order_t1 = 0; $order_t1 < $this -> length; $order_t1++) {
            for ($order_t2 = $order_t1 + 1; $order_t2 < $this -> length; $order_t2++) {
                if ($orderfunc($tempCross[$order_t1][$ordercol], $tempCross[$order_t2][$ordercol])) {
                    $temp = $tempCross[$order_t1][$ordercol];
                    $tempCross[$order_t1][$ordercol] = $tempCross[$order_t2][$ordercol];
                    $tempCross[$order_t2][$ordercol] = $temp;
                    $temp = $neworder[$order_t1];
                    $neworder[$order_t1] = $neworder[$order_t2];
                    $neworder[$order_t2] = $temp;
                }
            }
        }
        for ($order_p1 = 0; $order_p1 < $this -> length; $order_p1++) {
            for ($order_p2 = 0; $order_p2 < $this -> width; $order_p2++) {
                $newordercross[$order_p1][$order_p2] = $this -> CROSS[$neworder[$order_p1]][$order_p2];
            }
        }
        $this -> CROSS = $newordercross;
    }

    public
    function xy($x, $y) {
        return $this -> CROSS[$x][$y];
    }

    public
    function line($id) {
        return array_column($this -> CROSS, $id);
    }

    public
    function row($id) {
        return $this -> CROSS[$id];
    }

} //end class

?>
