<?php
function run_options(){
$links=array();

$links[]=new name_value("Home","");

$links[]=new name_value("My Account","&sub=1");

$links[]=new name_value("Property & Tenants","&sub=2");

$links[]=new name_value("Collection","&sub=3");

$links[]=new name_value("Payment Options","&sub=4");

return $links;
}
?>