<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
<title>User Login</title>
<link rel="stylesheet" href="<?php echo self::$template_path; ?>css/general.css" type="text/css" />
<?php 

self::loadHeadScripts();

$option_status=new name_value(0,false);

 ?>
</head>
<?php //echo serialize(array()); 
System::resetPassword($option_status);
?>
<body>
<div id="all">
<div id="header"><div id="inner_header"><div id="title_div"></div></div></div>
<div id="container_main" style="background: none;">
   
<div id="container" style="background: none;">
   
  <?php 
  
  if(isset($_GET['reset_token'])){
  
 ?>
 
  <div id="login_inner" style="margin-bottom:100px;margin-top:100px;height:auto;">
    <div id="form_div">
            <form action="" method="POST">
            <div id="title_div" style="border-bottom:1px solid #05622d;">Password Reset</div>
            <?php echo RESET_MESSAGE; 
			if($option_status->value){
			?>
            
            <input type="hidden"  name="account_email" value="<?php echo $_GET['email']; ?>" />
            <div id="form_row" style="margin-top:20px;"><div id="label" style="margin-left:20px;width:140px;"><strong>Email</strong></div><input type="input" class="form_input" disabled="disabled" value="<?php echo $_GET['email']; ?>"/></div>
            
             <div id="form_row"><div id="label" style="margin-left:20px;width:140px;"><strong>New Password</strong></div><input type="password" class="form_input" name="new_password"/></div>


<div id="form_row" style="margin-top:20px;"><div id="label" style="margin-left:20px;width:140px;"><strong>Repeat Password</strong></div><input type="password" class="form_input" name="repeat_password"/></div>

<div id="form_row" style="margin-top:10px; text-align:center;"><a  class="form_button_delete" style="margin-left:150px;float:left;padding:4px 5px 4px 5px;color:#ffffff;text-decoration:none;" href="?">Cancel</a><input type="submit" class="form_button" name="reset_pass" value="Reset Password" style="float:left;margin-left:20px;"/></div>
           <?php } ?> 
            </form>
     </div>
  </div>
 
 <?php
  
  }else{
  
  if(!isset($_GET['p_option'])&&(!isset($_POST['requnpass']))){ 
  
    $extension->loadMacro();

	
	if(!defined("ADMIN_ROOT")&&(PARENT!=0))
	$extension->loadModule('bottom');
  }else{
	?>
    <div id="login_inner" style="margin-bottom:100px;margin-top:100px;height:auto;">
<div id="form_div">
            <form action="?" method="POST">
            <?php if(isset($_POST['requnpass'])&&($option_status->value==true)){ ?>
            
            <div id="title_div" style="border-bottom:1px solid #05622d;">Request Confirmation</div>
            
            <?php 
			
			echo RESET_MESSAGE; 
					
			}else{ 
			
			$p_option=System::getArrayElementValue($_GET,"p_option","none");
			
			if($p_option=="none")
			$p_option=$option_status->name==2 ? "@forgot_password": "";
			
			?>
            <div id="title_div" style="border-bottom:1px solid #05622d;"><?php if(($p_option=="@forgot_password")){ ?><input type="hidden" name="psttype" value="2"/> Reset password.<?php }else{ ?><input type="hidden" name="psttype" value="1"/>Remind me my username<?php } ?></div>
      <?php
	  
	   echo RESET_MESSAGE; 
	  
	   $emailbox=new input;
	   $emailbox->setClass("form_input");
	   $emailbox->setTagOptions("title=\"Enter your email address\"");
	   $emailbox->input("text","client_email");
	  ?>
  
<div id="form_row" style="margin-top:30px; text-align:center;"><div id="label" style="width:150px;"><strong >Your Email Address</strong></div><?php echo $emailbox->toString(); ?></div>
<div id="form_row" style="margin-top:10px; text-align:center;"><a  class="form_button_delete" style="margin-left:100px;float:left;padding:4px 5px 4px 5px;color:#ffffff;text-decoration:none;" href="?">Cancel</a><input type="submit" class="form_button" name="requnpass" value="Send Request" style="float:left;margin-left:20px;"/></div>
<?php 
		
} ?>
            </form>
            </div>
<?php 
   } 

 }
 ?>

      </div>
    </div>
   </div>
  
<div id="footer"><div id="footer_labels">&copy;<?php echo date("Y",time()); ?>.Staff Collaboration & Management System</div>

</div>
</body>
</html>
