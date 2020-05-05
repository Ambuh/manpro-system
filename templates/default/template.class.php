<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >



<title><?php if(defined("USER_LOGGED")){ echo System::getPageTitle(); }else{ echo "User LogIn"; } ?></title>
<?php 
  self::loadHeadScripts();
  System::loadPageInfo();
 ?>
    <link rel="manifest" href="/manifest.json"><link rel="stylesheet" href="<?php echo self::$template_path; ?>css/style.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo self::$template_path; ?>engine1/style.css" />
    <link rel="stylesheet" href="<?php echo self::$template_path; ?>css/fnt/css/all.css" type="text/css" />
    <link rel="shortcut icon" href="<?php echo System::getFolderBackJump().str_replace("../","",self::$template_path); ?>favicon.ico" type="image/x-icon" />

</head>

<body scroll="auto" style="margin: 0px; background: <?php  if(!defined("USER_LOGGED")){ echo "url(".System::getFolderBackJump()."images/blurred.jpg)"; }else{ echo "#444"; } ?>;background-size: cover;background-repeat: no-repeat;background-position: center">

	<div id="wrapper"><div class="w3-closer"><div class="w3-sel">&times;</div></div></div>
<div id="wrapper2"></div>

<?php $prlib=System::shared('proman_lib'); //check out the comments on the page 32 the #hem_control should be available if the user is not logged in the system; ?>
    <?php  if(defined("USER_LOGGED")): ?>
	<div id="header"><div class="header_inner"><div class="main_title"><?php echo  $prlib->cmp->company_name=$prlib->cmp->company_name==''? ' ':$prlib->cmp->company_name; ?>
		<?php  if(!defined("USER_LOGGED")){ ?> <div class="hem_control" id="hem_control">login/Register</div> <?php } ?>
	<?php  //echo  ?>
	<div class="m_login"><?php  if(!defined("USER_LOGGED")){  }
	?> <div id="hem_control">login/Register?</div> <?php
 ?></div></div></div>
	<?php if(defined("USER_LOGGED")):?><div class="cnUser"><?php echo $extension->loadModule("slot5"); ?></div><?php endif;?>
	</div></div>
<?php endif; ?>
<?php if($extension->isAvailable('slot1')){?><div id="top_slot"><div id="topslo"><div style="float:right;width:400px;margin-top:20px;"><?php $extension->loadModule('slot1'); ?></div></div></div><?php } ?>


<div id="main_content" <?php if(!defined("USER_LOGGED")){ ?>style="margin-top:0px;"<?php }else{?>style="margin:80px;" class="halfDiv" <?php } ?>>
  
  <div id="inner_content" <?php if(!defined("USER_LOGGED")):?>  style="background:none;margin:0px;border:none;" <?php endif; ?>>
  <?php if(defined("USER_LOGGED")):?>
  <!--<div id="left_col"><?php //$extension->loadModule('left');?></div>-->
  <?php endif; ?>
  <div id="main_col" <?php if(!defined("USER_LOGGED")):?> style="width:100%;margin:0px;border:none;" class="l_bg" <?php endif; ?> >
    <div id="content_area" <?php if(defined("USER_LOGGED")){ ?> style="width:100%;background: #efefef;" <?php }else{?>  <?php } ?> >
  
  <?php if(defined("USER_LOGGED")){ ?>
  
      <?php 
      
      $extension->loadMacro(); 
      
      }else{
	

	
	   echo $extension->loadModule("slot4");
	
      } ?>
  
  <?php if($extension->isAvailable('bottom')):?>
    <!--<div id="bot_slot">
    
    <div id="inner_title">Our Products</div>
     
     <div  class="box1"><?php //$extension->loadModule('bottom');?></div>
     
     <div class="box1"><?php //$extension->loadModule('slot3');?></div>
     
    </div>-->
    
  <?php endif; ?>
    
    
    </div>
    <!--<div id="spec_slot"></div>-->
    
 </div>   
   
  
  </div>
  
 </div>
    <?php  if(defined("USER_LOGGED")): ?>
 <div class="cus"><?php echo $extension->loadModule('bottom');?></div>

 <div id="footer">
  <div id="footer_inner"><div id="textdiv">&copy;2018.Project Management System.</div></div>
 </div>
 <?php   endif; ?>
</body>
</html>