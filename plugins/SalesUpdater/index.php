<?php
define("ROOT",dirname(__FILE__)."/../../");

$rdir="/".str_replace(str_replace("\\","/",$_SERVER['DOCUMENT_ROOT']),"",str_replace("//","/",str_replace("\\","/",dirname(__FILE__))."/"));
 
define("ROOT_DIR",$rdir);

define("INTERPLUG","SalesUpdater");

define("FACE","front");

define("PARENT",0);

//define("IS_AJAX",1);

include_once(ROOT."library/globals/loaders.php");
?>