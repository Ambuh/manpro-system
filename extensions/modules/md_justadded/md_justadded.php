<?php
function md_justadded($id){
	
	$carhelper=System::shared('showcars');
	
	echo $carhelper->showCarsList(true,$id,""," limit 4",false);
}
?>