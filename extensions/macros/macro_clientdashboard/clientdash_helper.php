<?php
class clientDash{
 private $user_manager;
 public function __construct(){
	$this->user_manager=System::shared("usermanager");
 }
 public function UI(){
  $opt="";
  if(isset($_GET['sub']))
  $opt=$_GET['sub'];
  
  $css="<style>".file_get_contents(dirname(__FILE__)."/asset/layout.css")."</style>";
  
  switch($opt){
	 case 1:
	 return $css.$this->myAccount();
	 
	 default:
     return $this->mainPage();
  }
 }
 public function mainPage(){
 
  $css="<style>".file_get_contents(dirname(__FILE__)."/asset/layout.css")."</style>";
 
   if(!defined("USER_LOGGED")){
     return $css.$this->idlePage();
   }
   if(isset($_GET['dop'])){
	   switch($_GET['dop']){
		  case 1;
		  return $css.$this->editProfile(); 
		  
		  case 2:
		  return $css.$this->myApps();
		  
		  case 3:
		  return $css.$this->mySubscriptions();
		  
		  case 4:
		  return $css.$this->myTransactions();
		  
	   }
   }
   return $css.$this->getMainDashboard();
 }
 private function idlePage(){
    
	$cont=new objectString;
	
	$cont->generalTags("<div id=\"form_row\" style=\"margin-bottom:0px;margin-top:30px;border-top:1px solid #054a81;\"></div>");
	
	$cont->generalTags("<div id=\"idlebox\" >COMMITTED TO SERVICE.</div>");
	
	
	return $cont->toString();
	
 }
 private function getMainDashboard(){
   
   $cont=new objectString;
   
   $cont->generalTags(System::contentTitle("Dashboard","margin-bottom:3px;width:98%!important;margin-top:3px;margin-left:-1px;float:left;width:100%;border-bottom:none;font-weight:normal;"));
   
   $cont->generalTags("<div id=\"prof_dets\" style=\"width:99%;border:1px solid #eee;background:#f5f5f5;float:left;\">");
   
   $cont->generalTags("<div  style=\"width:50%;float:left;min-height:150px;\">");
   
   $udets=explode("_",USER_LOGGED);
   
   $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Name</strong></div><div style=\"color:#35950f;\">".$udets[2]."</div></div>");
   
   $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Email</strong></div><div style=\"color:#35950f;\">wekesamaina@gmail.com</div></div>");                    
   
   $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Company</strong></div><div style=\"color:#35950f;\">Dua Technologies</div></div>");
   
   $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Cellphone</strong></div><div style=\"color:#35950f;\">0715 661 851</div></div>");
   
   $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Location</strong></div><div style=\"color:#35950f;\">Nairobi</div></div>");
   
   $cont->generalTags("</div><div style=\"width:50%;float:left;\">");
   
   $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Last Login</strong></div><div style=\"color:#35950f;\">0715 661 851</div></div>");
   
   $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>IP Address</strong></div><div style=\"color:#35950f;\">0715 661 851</div></div>");
   
   $cont->generalTags("</div></div>");
   
   $cont->generalTags(System::successText("User Panel","background:#f5f5f5;color:#444;margin-top:10px;width:99%!important;border:1px solid #eee;"));
      
   $cont->generalTags("<div id=\"u_panel\" style=\"min-height:300px;float:left;overflow-y:scroll;border:1px solid #eee;\">".$this->panelIcons()."</div>");
   
   $cont->generalTags(System::successText("My Subscriptions","background:#f5f5f5;color:#444;margin-top:10px;width:99%!important;border:1px solid #eee;"));
   
   $cont->generalTags("<div style=\"width:99%;minheight:100px;border:1px solid #eee;background:#f5f5f5;float:left;\">");
   
   $list=new list_control;
   
   $list->setColumnNames(array("Service","Status","Start","Expires"));
   
   $list->setColumnSizes(array("400px","50px","110px","110px"));
   
   $list->setHeaderFontBold();
   
   $list->addItem(array("<a href=\"?\">Pomelo Backup Service</a>","Active","12/10/2015"));
   
   $list->addItem(array("<a href=\"?\">Pomelo Backup Service</a>","Active","12/10/2015"));
   
   $list->setSize("793px","200px");
   
   $list->setAlternateColor("#aaf78b");
   
   $list->showList(false);
   
   $cont->generalTags($list->toString());
   
   $cont->generalTags("</div>");
     
   return $cont->toString();
   
 }
 public function panelIcons(){
 
  $cont=new objectString();
  
  $cont->generalTags("<div id=\"icon_window\"><a href=\"?dop=1\" style=\"color:#444;\" ><img src=\"".System::getFolderBackJump()."images/upload_icons/edit_profile.png\" style=\"margin:3px 20px 7px 20px;\" title=\"Edit Profile\" /></a><div style=\"width:100%;text-align:center;\"><a href=\"?dop=1\" style=\"color:#444;\" title=\"Edit Profile\">Edit Profile</a></div></div>");
 
 $cont->generalTags("<div id=\"icon_window\"><a href=\"?dop=2\" style=\"color:#444;\"><img src=\"".System::getFolderBackJump()."images/upload_icons/myapps.png\" style=\"margin:3px 20px 7px 20px;\" title=\"Manage Property/Properties\" /></a><div style=\"width:100%;text-align:center;\"><a href=\"?dop=2\" style=\"color:#444;\" title=\"Manage Property/Properties\">My Property</a></div></div>");

$cont->generalTags("<div id=\"icon_window\"><a href=\"?dop=3\" style=\"color:#444;\"><img src=\"".System::getFolderBackJump()."images/upload_icons/managesubs.png\" style=\"margin:3px 20px 7px 20px;\" title=\"View/Manage Tenants\"/></a><div style=\"width:100%;text-align:center;\"><a href=\"?dop=3\" style=\"color:#444;\" title=\"View/Manage Tenants\">Tenants</a></div></div>");

$cont->generalTags("<div id=\"icon_window\"><a href=\"?dop=4\" style=\"color:#444;\"><img src=\"".System::getFolderBackJump()."images/upload_icons/translist.png\" style=\"margin:3px 20px 7px 20px;\" title=\"Rent Collection\"/></a><div style=\"width:100%;text-align:center;\"><a href=\"?dop=4\" style=\"color:#444;\" title=\"Rent Collection\">R. Collection</a></div></div>");
 
  return $cont->toString(); 
 
 }
 private function editProfile(){
 
 $cont=new objectString;
 
 $exp=explode("?",$_SERVER['REQUEST_URI']);

 $udets=explode("_",USER_LOGGED);
 
 $users=$this->user_manager->getUsers("where id=".$udets[1]);
  
 $backbtn="<div class=\"form_button_add\" style=\"float:left;margin-top:-2px;margin-right:5px;\"><a href=\"".$exp[0]."\">Back</a></div>";
 
 $cont->generalTags(System::contentTitle("Edit Profile".$backbtn,"margin-bottom:3px;width:98%!important;margin-top:3px;margin-left:-1px;float:left;width:100%;border-bottom:none;font-weight:normal;"));

 $cont->generalTags("<div id=\"edit_prof\" style=\"width:83%;min-height:200px;float:left;margin-top:3px;\">");

 $cont->generalTags(System::successText("Your Details","background:#efefef;color:#444;"));
 
 for($i=0;$i<count($users);$i++){
 
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\">Name</div>{$users[$i]->user_name}</div>");
 
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\">Email</div>{$users[$i]->user_username}</div>");

 $cont->generalTags("<div id=\"form_row\"><div id=\"label\">Cellphone</div></div>");
 
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\">Location</div></div>");

 $cont->generalTags(System::successText("Change Password","background:#efefef;color:#444;margin-bottom:10px;"));

 $curr_pass=new input;
 $curr_pass->setClass("form_input");
 $curr_pass->input("password","input_pass","");
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\">Current Password.</div>{$curr_pass->toString()}</div>");
 
 $new_pass=new input;
 $new_pass->setClass("form_input");
 $new_pass->input("password","new_pass","");
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\">New Password.</div>{$new_pass->toString()}</div>");

 $rep_pass=new input;
 $rep_pass->setClass("form_input");
 $rep_pass->input("password","rep_pass","");
 $cont->generalTags("<div id=\"form_row\" ><div id=\"label\">Repeat Password.</div>{$rep_pass->toString()}</div>");
 
 $btn=new input;
 $btn->setClass("form_button_add");
 $btn->setTagOptions("style=\"float:right;\"");
 $btn->input("button","save","Save Settings");
 $cont->generalTags("<div id=\"form_row\" style=\"margin-bottom:0px;\">{$btn->toString()}</div>");
 
 }

 $cont->generalTags("</div>");

 $cont->generalTags("<div style=\"float:right;\"><div id=\"icon_window\" style=\"margin:3px;\"><img src=\"".System::getFolderBackJump()."images/upload_icons/edit_profile.png\" style=\"margin:3px 20px 7px 20px;\"/><div style=\"width:100%;text-align:center;\">Edit Profile</div></div></div>");

 
 
 return $cont->toString();
 
 }
 private function myApps(){
 
 $cont=new objectString;
 
 $cont->generalTags(System::contentTitle("My Apps","margin-bottom:3px;width:98%!important;margin-top:3px;margin-left:-1px;float:left;width:100%;border-bottom:none;font-weight:normal;"));

 $exp=explode("?",$_SERVER['REQUEST_URI']);

 $cont->generalTags("<div id=\"form_row\"><a href=\"".$exp[0]."\">--Back--</a></div>");
 
 return $cont->toString();
 
 }
 private function mySubscriptions(){
 
 $cont=new objectString;
 
 $cont->generalTags(System::contentTitle("My Subscriptions","margin-bottom:3px;width:98%!important;margin-top:3px;margin-left:-1px;float:left;width:100%;border-bottom:none;font-weight:normal;"));

 $exp=explode("?",$_SERVER['REQUEST_URI']);

 $cont->generalTags("<div id=\"form_row\"><a href=\"".$exp[0]."\">--Back--</a></div>");
 
 return $cont->toString();
 
 }
 private function myTransactions(){
 
 $cont=new objectString;
 
 $cont->generalTags(System::contentTitle("My Transactions","margin-bottom:3px;width:98%!important;margin-top:3px;margin-left:-1px;float:left;width:100%;border-bottom:none;font-weight:normal;"));
 
 $cont->generalTags("<div style=\"float:left;\"><video src=\"images/showtoon.mp4\"  width=\"670\" controls=\"controls\">
   Your browser does not support the <video> element.   
</video></div>");
 $exp=explode("?",$_SERVER['REQUEST_URI']);

 $cont->generalTags("<div id=\"form_row\"><a href=\"".$exp[0]."\">--Back--</a></div>");
 
 return $cont->toString();
 
 }
 public function myAccount(){
  $cont=new objectString;
  $cont->generalTags(System::contentTitle("My Account.","font-weight:normal;"));
    
  $cont->generalTags("<div id=\"prof_dets\" style=\"width:99%;border:1px solid #eee;background:#f5f5f5;float:left;\">");
  
  $cont->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #ddd;\"><strong>Account Summary</strong></div>");
  
  $cont->generalTags("<div style=\"width:96%;float:left;margin:2%;margin-top:5px;\">");
  
  $cont->generalTags("<div id=\"form_row\"><div id=\"label\">Package</div></div>");
  
  $cont->generalTags("<div id=\"form_row\"><div id=\"label\">Status</div></div>");
  
  $cont->generalTags("<div id=\"form_row\"><div id=\"label\">Last Payment</div></div>");
  
  $cont->generalTags("<div id=\"form_row\"><div id=\"label\">Expiry</div></div>");
  
  $cont->generalTags("<div id=\"form_row\"><div id=\"label\">Amount Due</div></div>");
  
  $cont->generalTags("</div>");
  
  $cont->generalTags("</div>");
  
  $cont->generalTags("<div id=\"prof_dets\" style=\"width:99%;border:1px solid #eee;background:#f5f5f5;float:left;margin-top:5px;\">");
  
  $cont->generalTags("</div>");
  
  return $cont->toString();
 }
}
?>