<?php

header("Content-type:text/html;charset=utf-8");
class RD
{
    var $HOST;
    var $USER;
    var $PASS;
    var $CONNECT;
   
function __construct($HOST,$USER,$PASS,$TABLE){
    $this->HOST=$HOST;
    $this->USER=$USER;
    $this->PASS=$PASS;
    $CONNECT=mysqli_connect($HOST,$USER,$PASS);
    mysqli_query($CONNECT,"set names utf8");
    $this->CONNECT=$CONNECT;
    mysqli_select_db($this->CONNECT,$TABLE);
}

// $SEARCH="SELECT * FROM XX WHERE YY='{$YY}'";
function RD_Getdata($SEARCH){
    $DO_SEARCH=mysqli_query($this->CONNECT,$SEARCH);
    $GET_SEARCH=mysqli_fetch_array($DO_SEARCH, MYSQLI_ASSOC);
    $this->DO_SEARCH=$DO_SEARCH;
    $this->GET_SEARCH=$GET_SEARCH;
}

/* 
$ADD="INSERT INTO label (A,B) VALUES "('{$A}','{$B}')";
$EDIT="UPDATE label SET A='{$A}' WHERE X='{$X}'";
$DELETE="DELETE FROM A WHERE X='{$X}'"";
*/
	
function RD_RUN($RUN){
    $DO_RUN=mysqli_query($this->CONNECT,$RUN);
}

// count the all nums of a table
function RD_Strlen($TABLE){
    $STRLEN="SELECT * FROM {$TABLE}";
    $DO_STRLEN=mysqli_query($this->CONNECT,$STRLEN);
    $DO_STRLEN_id=0;
    while($GET_STRLEN=mysqli_fetch_array($DO_STRLEN,MYSQLI_ASSOC))
    {
        $DO_STRLEN_id++;
    }
    return $DO_STRLEN_id;
}
}
?>
