<?php
function meta_run($type){

  if(defined("USER_LOGGED")){
  
   if(isset($_GET['dop'])){
	   
	   switch($_GET['dop']){
		   case 1:
		   return "Edit Profile";
		   case 2:
		   return "My Apps";
		   case 3:
		   return "My Subscriptions";
		   case 4:
		   return "My Transactions";
	   }
  
   }
  
  return "";
  }

  //return "User Portal";

}
?>