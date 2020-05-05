<?php
function com_admincompanies(){
GLOBAL $db;
include_once("helper.php");
$helper=new myhelper;

$helper->loadBlock();

return true;
}
?>