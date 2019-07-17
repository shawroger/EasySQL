<?php
header("Content-type:text/html;charset=utf-8");
class EasySQL
{
    public $HOST;
    public $USER;
    public $PASS;
    public $CONNECT;
    public $TABLE;
   
function __construct($HOST,$USER,$PASS,$BASE,$TABLE){
    $this->HOST=$HOST;
    $this->USER=$USER;
    $this->PASS=$PASS;
    $this->BASE=$BASE;
    $this->TABLE=$TABLE;
    $CONNECT=mysqli_connect($HOST,$USER,$PASS);
    $this->CONNECT=$CONNECT;
    mysqli_query($this->CONNECT,"set names utf8");
    mysqli_select_db($this->CONNECT,$BASE);
    $this->size();
    $this->col();
    $this->cross();
}

function run($RUN){
    $DO_RUN=mysqli_query($this->CONNECT,$RUN);
}

function get($SEARCH){
    $DO_SEARCH=mysqli_query($this->CONNECT,$SEARCH);
    $GET_SEARCH=mysqli_fetch_array($DO_SEARCH, MYSQLI_ASSOC);
    $this->GET=$GET_SEARCH;
}
    
function eget($E_Q,$E_A){
    $E_SEARCH="SELECT * FROM {$this->TABLE} WHERE {$E_Q} = {$E_A}";
    $this->get($E_SEARCH);
}

function size($Another_TABLE){
    if($Another_TABLE==''){
        $Another_TABLE=$this->TABLE;
    }
    $STRLEN="SELECT * FROM {$Another_TABLE}";
    $DO_STRLEN=mysqli_query($this->CONNECT,$STRLEN);
    $DO_STRLEN_id=0;
    while($GET_STRLEN=mysqli_fetch_array($DO_STRLEN,MYSQLI_ASSOC))
    {
        $DO_STRLEN_id++;
    }
    $this->length=$DO_STRLEN_id;
    return $DO_STRLEN_id;
}

function cross($Another_TABLE){
    if($Another_TABLE==''){
        $Another_TABLE=$this->TABLE;
    }
    $FUNC_FOR="SELECT * FROM {$Another_TABLE}";
    $DO_FUNC_FOR=mysqli_query($this->CONNECT,$FUNC_FOR);
    $FOR_ROW=0;
    while($GET_FUNC_FOR=mysqli_fetch_row($DO_FUNC_FOR)){
        for($FOR_COL=0;$FOR_COL<$this->width;$FOR_COL++){
            $this->CROSS[$FOR_ROW][$FOR_COL]=$GET_FUNC_FOR[$FOR_COL];
        }
        $FOR_ROW++;
    }
}
    
function col($Another_TABLE) {
    if($Another_TABLE=='') {
        $Another_TABLE=$this->TABLE;
    }
    $ALLC_id=0;
    $QU_ALLC="SHOW FULL COLUMNS FROM {$Another_TABLE}";
    $RUN_ALLC=mysqli_query($this->CONNECT,$QU_ALLC);
    while($SHOW_ALLC=mysqli_fetch_array($RUN_ALLC)) {
        $GET_ALLC[$ALLC_id]=$SHOW_ALLC['Field'];
        $ALLC_id++;
    }
    $this->COL=$GET_ALLC;
    $this->width=$ALLC_id;
  }

function add($addArr) {
$add_first="INSERT INTO {$this->TABLE}".
            "({$this->COL[0]})".
            "VALUES ".
            "('{$addArr[0]}')";
$this->run($add_first);
for($addtemp=1;$addtemp<$this->width;$addtemp++){
        $add_more="UPDATE {$this->TABLE} SET {$this->COL[$addtemp]}='{$addArr[$addtemp]}' WHERE {$this->COL[0]}='{$addArr[0]}'";
        $this->run($add_more);
    }
}

function edit($line,$editArr) {
    $hook_edit=$this->CROSS[$line][0];
    $new_editArr=array();
    for($edit_temp=0;$edit_temp<$this->width;$edit_temp++){
        if($editArr[$edit_temp]==''){
            $new_editArr[$edit_temp]=$this->CORSS[$line][$edit_temp];
        }
        else {
            $new_editArr[$edit_temp]=$editArr[$edit_temp];
        }
    }
    for($edit_run_temp=0;$edit_run_temp<$this->width;$edit_run_temp++){
        $edit_more="UPDATE {$this->TABLE} SET {$this->COL[$edit_run_temp]}='{$new_editArr[$edit_run_temp]}' WHERE {$this->COL[0]}='{$hook_edit}'";
        $this->run($edit_more);
    }
}

function delete($line) {
$delete="DELETE FROM {$this->TABLE} WHERE {$this->COL[0]}='{$this->CROSS[$line][0]}'";
$this->run($delete);
}

}//end class
?>
