<?php 

//ini_set('display_error','on');

define("ROOT",dirname(__FILE__)."/");

$rdir="/".str_replace(str_replace("\\","/",$_SERVER['DOCUMENT_ROOT']),"",str_replace("//","/",str_replace("\\","/",dirname(__FILE__))."/"));

//echo '<div style="width:100;color:#bbb;">'.str_replace("\\","/",$_SERVER['DOCUMENT_ROOT']).str_replace("//","/",str_replace("\\","/",dirname(__FILE__))).'</div>';

define("ROOT_DIR",$rdir);

define("PARENT",0);

define("FACE","front");

include_once("library/globals/loaders.php");


?>