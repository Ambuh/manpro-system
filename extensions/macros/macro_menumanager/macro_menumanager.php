<?php

function macro_menumanager($id,$menuid=0){

GLOBAL $db;

include("helper.php");

$layout=new macro_layout;

$layout->setWidth("900px");

System::getMenuLinks();

$helper=new manager_helper($layout);

system::adminPageTitle("macro_menumanager","Manage Menus");

$myopt=-1;

$id=0;

if(isset($_GET['id'])){
$id=$_GET['id'];

}

if(isset($_GET['mopt'])){
 
 $myopt=$_GET['mopt'];
 
}

?>
<div style="width:100%;margin-left:0px;margin-bottom:5px; margin-top:5px; overflow:hidden;">
<?php

switch($myopt){

case 0:
$i=0;
if(isset($_GET['mid'])){
$i=$_GET['mid'];
}
$helper->editPage($id,"?mid=".$i);

break;

case 1:
$i=0;
if(isset($_GET['mid'])){
$i=$_GET['mid'];
}
$helper->editMenuItem($id,"?mid=".$i."&mopt=0&id=".$id);

break;

default:
$helper->mainPage("?");

}
?>
</div>
<?php
return true;
}
?>