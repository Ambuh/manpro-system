<?php
include_once("helper.php");

function macro_companysettings(){

$help=new helper;

$layout=new macro_layout;

$layout->setWidth("763px");

$opt=0;

if(isset($_GET['sopt'])){

$opt=$_GET['sopt'];

}

switch($opt){

case 1;

echo System::userPageTitle("macro_companysettings","Settings: :Manage Departments");

$layout->content=$help->departments();

break;

case 2;

echo System::userPageTitle("macro_companysettings","Settings: :Manage Positions");

$layout->content=$help->positions();

break;

case 3;

echo System::userPageTitle("macro_companysettings","Settings: :Manage Hirachy");

$layout->content=$help->hirachy();

break;

case 4;

echo System::userPageTitle("macro_companysettings","Settings: :Project Creators");

$layout->content=$help->hirachy();

break;

default: case 0:

echo System::userPageTitle("macro_companysettings","Settings: :Manage Branches");

$layout->content=$help->branches();

}
?>
<div style="float:left;width:100%; margin-top:5px;margin-bottom:5px;">
<?php
$layout->showLayout();
?>
</div>
<?php
return true;
}
?>