<?php
header("Content-type: application/octet-stream");
header("Content-Transfer-encoding:binary");
$ext=explode("_",$_GET['crt']);
header("Content-disposition:attachment;filename=\"".$_GET['fname'].".".$ext[count($ext)-1]."\"");
//echo ROOT."downloads/".$ext[0]."_".PARENT.".".$ext[1];
readfile(ROOT."downloads/".$_GET['crt']);
exit;
?>