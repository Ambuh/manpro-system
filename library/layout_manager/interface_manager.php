<?php
class interfaceLoader{

public static $template_path;

public $pref;

private static $user;

public function __construct(){
$conf=new config;

define("SPREF",$conf->session_Prefix);
   
}

private static function loadHeadScripts(){

 $scripts=new loadControlScript;

}
private static function loadFrontTemplate(){

GLOBAL $db;
 
 $conf=new config;
 
 $User;
 
 if(isset($_SESSION[$conf->session_Prefix.'user_session'])){
 
 $User=unserialize($_SESSION[$conf->session_Prefix.'user_session']);
 
 }else{
 $mu =$db->getUserSession();
 $_SESSION[$conf->session_Prefix.'user_session']=serialize($mu);

 $User=unserialize($_SESSION[$conf->session_Prefix.'user_session']);

 
 }
 self::parseRequest();
 
 $extension=new extension_handler;
 
 $temps=$db->getTemplates(" where selected=1 and temp_type=0");
 
 $add="";
 
 if(defined("NOT_DEFAULT")){
 
   $add="../";
 
 }
 
 self::$template_path=System::getFolderBackJump()."templates/".$temps[0]->template_name."/";
 
  include(ROOT."templates/".$temps[0]->template_name."/"."template.class.php");
 
}
private static function loadAdmintemplate(){

GLOBAL $db;

 $User=unserialize($_SESSION[System::getSessionPrefix().'user_session']);

 self::parseRequest();

 $extension=new extension_handler;

 $temps=$db->getTemplates(" where selected=1 and temp_type=1");

 self::$template_path="templates/".$temps[0]->template_name."/";
  
 include(ADMIN_ROOT."templates/".$temps[0]->template_name."/template.class.php");

}
public static function loadTemplate($template_type="front"){
 
 $user=new Manage_User;
 
 GLOBAL $db;
 
 if(isset($_POST['user_login'])){
  
  $user->logIn($_POST['user_username'],$_POST['user_password'],PARENT);
  
 }
 
 if(isset($_GET['logout'])){
  unset($_SESSION['menus']);
  unset($_SESSION['plugins']);
  unset($_SESSION[System::getMenuLinkSessionName()]);
  $user->logOut();
 }
 
 if(!isset($_SESSION['plugins'])){
 
  $plugins=$db->getPlugins(" where status=1");
 
 if($plugins!=NULL){
  
  $_SESSION['plugins']=serialize($plugins);
  
  
  }else{
  
  $_SESSION['plugins']=serialize(array());
 
  }
  
 $plin= $db->getPluginByType(unserialize($_SESSION['plugins']),1);
 
 if($plin!=NULL){
  
 include_once(ROOT."plugins/".$plin->plugin_name."/".$plin->plugin_name.".php");
 
 $function_name=$plin->plugin_name;
 
  $who="your administrator";
 
 if(defined("ADMIN_ROOT")){

   $who=" your vendor/service provider";

 }
 
 if(!$function_name()){
 
  include(ROOT."extensions/macros/sp_errorpage/sp_errorpage.php");
 sp_errorpage("There was an error accessing the page.<br/><small>You have no access or the page doesn't exist.Please contact $who.</small>","http://".str_replace($_SERVER['QUERY_STRING'],'',$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
 
 
 }
 }
 $plugins= $db->getPluginByType(unserialize($_SESSION['plugins']),0,true);
 
 for($i=0;$i<count($plugins);$i++){
   include_once(ROOT."plugins/".$plugins[$i]->plugin_name."/".$plugins[$i]->plugin_name.".php");
   
   $function_name=$plugins[$i]->plugin_name;
   
   $function_name();
   
 }
 
 }else{
 
 $plin= $db->getPluginByType(unserialize($_SESSION['plugins']),1);
 
 if($plin!=NULL){
 
 include_once(ROOT."plugins/".$plin->plugin_name."/".$plin->plugin_name.".php");
 
 $function_name=$plin->plugin_name;
 
 if(!$function_name()){
 
 $who="your administrator";
 
 if(defined("ADMIN_ROOT")){

   $who=" your vendor/service provider";

 }
 $user=$db->getUserSession();

 if($user->user_type!=-1){
 
 include(ROOT."extensions/macros/sp_errorpage/sp_errorpage.php");
 sp_errorpage("There was an error accessing the page.<br/><small>You have no access or the page doesn't exist.Please contact $who.</small>","http://".str_replace($_SERVER['QUERY_STRING'],'',$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
 return;
 }
 
 }
 }
 
 $plugins= $db->getPluginByType(unserialize($_SESSION['plugins']),0,true);
 
 for($i=0;$i<count($plugins);$i++){
   include_once(ROOT."plugins/".$plugins[$i]->plugin_name."/".$plugins[$i]->plugin_name.".php");
   
   $function_name=$plugins[$i]->plugin_name;
   
   $function_name();
   
 }
 
 }
 
 $user=new Manage_User;
 
  if(!defined("IS_AJAX")){
  
  if ($template_type=="front"){
   
     if($user->isLoggedIn()){
 
	$plin=$db->getPlugins("where plugin_type=2 and status=1");
       
	   //if((count($plin)>0)){
	   
	     @include_once(ROOT."plugins/".$plin[0]->plugin_name."/".$plin[0]->plugin_name.".php");
		 
		 //$func=$plin[0]->plugin_name;
		 
		 ///if($func()){
		
          self::loadFrontTemplate();
	  
	    // }
		//}
    
     }else{
     
	 
       $temps=$db->getTemplates(" where selected=1 and temp_type=0");
 
       $add="";
 
       if(defined("NOT_DEFAULT")&!defined("ADMIN_ROOT")){
 
        $add="../";
 
       }
 
       self::$template_path=System::getFolderBackJump()."templates/".$temps[0]->template_name."/";
 
       $extension=new extension_handler;
	   
	   $plin=$db->getPlugins("where plugin_type=2 and status=1");
       
	   if((count($plin)>0)&&(PARENT!=0)){
	   
	    for($c=0;$c<count($plin);$c++)
	     @include_once(ROOT."plugins/".$plin[$c]->plugin_name."/".$plin[$c]->plugin_name.".php");
		 
		 $func=$plin[0]->plugin_name;
		 
		 if($func()){
		   
		   @include_once("login_interface.php");
		   
		 }
	   
	   }else{
        
		 for($c=0;$c<count($plin);$c++)
		include_once(ROOT."plugins/".$plin[$c]->plugin_name."/".$plin[$c]->plugin_name.".php");
		 
		 
         //include("login_interface.php");
		 if(!defined("INTERPLUG")){
		 self::loadFrontTemplate();
		 }else{
		  include_once(ROOT."plugins/".INTERPLUG."/interface.php");
		  $intp=INTERPLUG;
		  $intp();
		 }
	   
	   }
	   
     }
     
    }else{
      if($user->isLoggedIn()){
      
       self::loadAdminTemplate();
       
      }else{
       
        $temps=$db->getTemplates(" where selected=1 and temp_type=1");
		
		$add="";
 
        if(defined("NOT_DEFAULT")){
 
           //$add="../";
 
        }

        self::$template_path=$add."templates/".$temps[0]->template_name."/";
	
        $extension=new extension_handler;
        
	    include("login_interface.php");
      
      }
  
  }
 
 }else{
  
  $plin=$db->getPlugins("where plugin_type=2 and status=1");
       
	   if((count($plin)>0)&&(PARENT!=0)){
	   
	     include_once(ROOT."plugins/".$plin[0]->plugin_name."/".$plin[0]->plugin_name.".php");
		 
		 $func=$plin[0]->plugin_name;
		 
	     $func();
	  }
  
  include_once("ajax_interface.php");
  
  $run_jax =new ajaxRun();
 
  $run_jax->main();
 
 }
}
public static function parseRequest(){
GLOBAL $db;
  
  $conf=new config;
  
  $title_pref="";
  
  if(defined("ADMIN_ROOT"))  
  $title_pref="Admin-";
  
  if($conf->clean_url==1){
  
  $arr=array();

  define("CLEAN_ROOT",System::decipherUrl());

  parse_str(str_replace("?","",System::getRawLink(System::decipherUrl())),$arr);
 
  
  $keys=array_keys($arr);
 
  for($i=0;$i<count($keys);$i++)
  $_GET[$keys[$i]]=$arr[$keys[$i]];
  
  }
  
  if(isset($_GET['mid'])&&!is_numeric($_GET['mid'])){
   
   unset($_GET['mid']);
   
   }

 if(isset($_GET['mid'])){

   $item=$db->getMenuItem($_GET['mid']);
   
   if($item!=NULL){
   
     $_GET['opt']=$item->item_macroId;
	 define("PAGE_TITLE",$title_pref.$item->item_title);
	
   }else{
     unset($_GET['opt']);
   }
 }else{
	 
    $item=$db->getDefaultMenuItem();
	
    if($item!=NULL){
   
     $_GET['opt']=$item->item_macroId;
	 define("PAGE_TITLE",$title_pref.$item->item_title);
	 $_GET['mid']=$item->item_id;
	 
	 
	 parse_str(str_replace("?","",$item->item_link),$arr);
 
  $keys=array_keys($arr);
 
  for($i=0;$i<count($keys);$i++)
  $_GET[$keys[$i]]=$arr[$keys[$i]];
 
	 
   }else{
	   
     unset($_GET['opt']);
   }
   
 }
}
}
?>
