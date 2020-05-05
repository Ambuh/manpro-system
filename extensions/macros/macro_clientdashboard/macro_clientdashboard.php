<?php
include_once("clientdash_helper.php");
function macro_clientdashboard(){

$hlp=new clientDash;

echo $hlp->UI();

return true;
}
?>