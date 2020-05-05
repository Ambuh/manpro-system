<?php
header("Access-Control-Allow-Origin: *");
if(!isset($_GET['drt'])){
if(!isset($_POST['ain'])):
echo "Access Violation";
exit;
endif;
}

define("ROOT",dirname(__FILE__)."/../../");

define("FACE","front");

define("IS_AJAX",1);

include_once(ROOT."library/globals/loaders.php");

?>