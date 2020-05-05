<?php

include_once("helper.php");

function macro_modulemanager(){

$help=new helper;

system::adminPageTitle("macro_modulemanager","Manage Modules");

?>
<div style="float:left;width:100%; margin-bottom:5px; margin-top:5px;">
<?php

$opt=0;

if(isset($_GET['mopt'])){

$opt=$_GET['mopt'];

}

switch($opt){

case 1:
$help->editPage("?mid={$_GET['mid']}");
break;

default:
$help->mainPage();

}
?>
</div>
<?php
return true;
}
?>