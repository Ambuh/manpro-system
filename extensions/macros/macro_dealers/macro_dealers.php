<?php

include_once("dealerhelper.php");

function macro_dealers(){

$hlp=new dealerHelper;

$layout=new macro_layout;

$layout->setWidth("760px","100px");

$layout->content=$hlp->displayPage();

$layout->showLayout(true);

return true;

}
?>