<?php
function PayMode(){

  $paths=explode("/",$_SERVER['REQUEST_URI']);
  
  $option_name="";
  
  $option_account="";
  
  if(count($paths)>0){
   $option_name=$paths[count($paths)-3];
   $option_account=$paths[count($paths)-2];
  }

 if(file_exists(dirname(__FILE__)."/Pay_Options/".$option_name.".php")){
 
   $main_func="PayStart";
 
   include_once(dirname(__FILE__)."/Pay_Options/".$option_name.".php");
    
   $main_func($option_account);
 
 }else{
	 echo "FAILED|Option Not Found.Invalid Account Link.";
 }

}
?>