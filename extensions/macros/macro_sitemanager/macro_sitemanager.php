<?php
function macro_sitemanager(){

include("siteman_helper.php");

$hlp=new siteman_helper;

System::adminPageTitle("macro_sitemanager","Site Manager");

$layout=new macro_layout;

$layout->setWidth("900px","100px");

$layout->content=$hlp->mainPage();

$layout->showLayout(true);

return true;
}
?>