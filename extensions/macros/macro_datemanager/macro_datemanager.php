<?php

include_once("helper.php");

function macro_datemanager(){

System::userPageTitle("macro_datemanager","Calendar Manager","margin-bottom:5px;");	

$helper=new datehelper;

$layout=new macro_layout;

$layout->content=$helper->mainPage();

$layout->setWidth("761px");

$layout->showLayout();

return true;
}

?>