<?php
function macro_macromanager(){
System::adminPageTitle("macro_macromanager","Manage Macros");

include_once("helper.php");

$help=new helper;

$id=0;

$myopt=0;

if(isset($_GET['mopt'])){

$myopt=$_GET['mopt'];

}

if(isset($_GET['id'])){

 $id=$_GET['id'];

}

switch($myopt){
case 1:
$help->editPage($id,"?mid={$_GET['mid']}");
break;

default:
$help->mainPage();

}

return true;
}
?>