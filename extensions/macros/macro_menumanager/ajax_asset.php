<?php
class ajax_run{
  public function main($par){
    GLOBAL $db;
	
	$macros=$db->getMacros("where id=$par");
	
	for($i=0;$i<count($macros);$i++){
	
	if(file_exists(ROOT."extensions/macros/".$macros[$i]->macro_name."/links.php")){

	 include_once(ROOT."extensions/macros/".$macros[$i]->macro_name."/links.php");
	 
	 $options=run_options();
	 
	 for($b=0;$b<count($options);$b++){
	 
	    echo "<div id=\"form_row\"><input type=\"radio\" name=\"lnk_opt\" value=\"{$options[$b]->value}\" /><div id=\"mini_label\"><strong>{$options[$b]->name}</strong></div></div>";
	 
	 }
	
	}else{
	 
	 echo "No Options Available";
	
	}
	
	}
	
  }
}
?>