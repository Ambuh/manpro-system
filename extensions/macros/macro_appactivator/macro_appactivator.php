<?php
function macro_admincompanies(){
GLOBAL $db;
include_once("helper.php");
$helper=new myhelper;
System::adminPageTitle("macro_admincompanies","Manage Companies");
?>
<div style="float:left;margin-top:3px; margin-bottom:5px;">
<?php

//echo system::categoryTitle("Manage company accounts","margin-left:10px;width:99%;margin-top:5px;");
$helper->loadBlock(System::getCheckerNumeric("mopt"));

?>
</div>
<?php
return true;
}
?>