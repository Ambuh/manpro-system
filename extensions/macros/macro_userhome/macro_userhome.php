<?php

include_once("helper.php");

function macro_userhome(){

$helper=new helper;

$opt=0;

if(isset($_GET['prof'])){

$opt=$_GET['prof'];

}

System::userPageTitle("macro_userhome","User dashboard");


switch($opt){

case 1:
$helper->editPage();
break;

default:
$helper->mainPage();   
}

return true;

}

?>