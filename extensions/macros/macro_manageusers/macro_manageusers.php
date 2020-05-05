<?php

include_once("helper.php");

function macro_manageusers(){

$helper=new userHelper;

System::adminPageTitle("macro_manageusers","User Manager");

//echo System::categoryTitle("Manage/Create company administrators","margin-left:10px; margin-top:3px;width:99%;");

?>
<div style="width:98%;margin-top:3px; margin-bottom:10px; float:left;">
<?php

System::getCheckerNumeric("eid");

if(!isset($_GET['eid'])){
  
 $helper->mainPage();
 
 }else{
 
 $helper->editPage();
 
 }
?>

</div>
<?php
return true;
}
?>