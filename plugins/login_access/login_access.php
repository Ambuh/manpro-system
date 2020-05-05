<?php

login_access();

function login_access(){

 $sh=System::shared("usermanager");
  
 if(isset($_POST['USR_SUBMIT'])){
   $status=false;
   $res=$sh->getUsers("where password='".sha1($_POST['u_pass'])."' and username='".str_replace("'","#",$_POST['u_email'])."' and status=1");
   $expired_sessions=array();
   if(isset($_SESSION[System::getSessionPrefix().'logid'])){
     $expired_sessions=unserialize($_SESSION[System::getSessionPrefix().'logid']);
   }
   if((count($res)>0)&(!in_array($_POST['logid'],$expired_sessions))){
	$satus=true;
	$expired_sessions[]=$_POST['logid'];
	$_SESSION[System::getSessionPrefix()."USER_LOGGED"]="usid_".$res[0]->user_id."_".$res[0]->user_name."_".$res[0]->user_type."_".$res[0]->user_branch."_".$res[0]->user_company."_".$res[0]->user_username;
	$sh->recordAccess($res[0]->user_id,$res[0]->user_name);
	define("USER_LOGGED","usid_".$res[0]->user_id."_".$res[0]->user_name."_".$res[0]->user_type."_".$res[0]->user_branch."_".$res[0]->user_company."_".$res[0]->user_username);
	header("location:".$_SERVER['REQUEST_URI']);
	$_SESSION[System::getSessionPrefix().'logid']=serialize($expired_sessions);
   }else{
     if(in_array($_POST['logid'],$expired_sessions)){
	  define("USR_MESSAGE","");
	  $status=true;
	 }
   }
      
   if(!$status)
   define("USR_MESSAGE",System::getWarningText("Login failed.Please enter the correct login details!","text-align:center;"));
 }else{
   if(isset($_SESSION[System::getSessionPrefix().'USER_LOGGED'])&(System::decipherUrl()!="Sign_Out")){
	   define("USER_LOGGED",$_SESSION[System::getSessionPrefix().'USER_LOGGED']);
   }
   
   //echo System::decipherUrl();
	 
   if(System::decipherUrl()=="Sign_Out"){
	   if(isset($_SESSION[System::getSessionPrefix().'USER_LOGGED']))
	   	unset($_SESSION[System::getSessionPrefix().'USER_LOGGED']);   
   }
   define("USR_MESSAGE","");
 }
}

?>