<?php

define("ROOT",dirname(__FILE__)."/../../");

define("FACE","front");

define("IS_AJAX",1);

session_start();

include(ROOT."app_configs.php");

include(ROOT."library/objects/toStringConverter.php");

include_once(ROOT."library/globals/common_controls.php");

function showForm(){
 
 include("temps/uploadtemp.php");
 
}
echo showForm();
?>