<?php
function md_bottommenu($id){
	
	GLOBAL $db;
	
	echo "<div id=\"form_row\">";
	
	$items=$db->getMenuItems($id);
		
	for($i=0;$i<count($items);$i++){
		
	   echo "<div id=\"linkcell\"><a href=\"{$items[$i]->item_link}\">{$items[$i]->item_title}</a></div>";
	  
	}
	
   echo "</div>";
	
}
?>