<?php
function run_options(){
$options=array();

$options[]=new name_value("Man. branches","&sopt=0");

$options[]=new name_value("Man. Department","&sopt=1");

$options[]=new name_value("Man. positions","&sopt=2");

$options[]=new name_value("Man. hirachy","&sopt=3");

$options[]=new name_value("Project Set.","&sopt=4");

return $options;

}
?>