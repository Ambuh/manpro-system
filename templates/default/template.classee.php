<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
 <link rel="stylesheet" href="<?php echo self::$template_path; ?>css/style.css" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo self::$template_path; ?>engine1/style.css" />

<link rel="shortcut icon" href="<?php echo System::getFolderBackJump().str_replace("../","",self::$template_path); ?>favicon.ico" type="image/x-icon" />
<!--<script type="text/javascript" src="<?php //echo self::$template_path; ?>engine1/jquery.js"></script>-->

</head>

<body scroll="auto" style="margin: 0px;<?php if(!defined("USER_LOGGED")):?><?php endif; ?>">

<div id="wrapper"></div>
<div id="wrapper2"></div>

<?php $prlib=System::shared('proman_lib'); ?>
 
	<div id="header"><div class="header_inner"><div class="main_title"><?php echo  $prlib->cmp->company_name=$prlib->cmp->company_name==''? 'Administrator':$prlib->cmp->company_name; ?><div class="m_login"><?php  if(!defined("USER_LOGGED")){ echo $extension->loadModule("slot4"); }else{
	?> <div id="hem_control">login/Register?</div> <?php
} ?></div></div></div>
	<?php if(defined("USER_LOGGED")):?><div class="cnUser"><?php echo $extension->loadModule("slot5"); ?></div><?php endif;?>
	</div></div>

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
	
	  //echo '<div class="thePop"></div>';
	
	   echo $extension->loadModule("slot6");
	
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
 <div class="cus"><?php echo $extension->loadModule('bottom');?></div>
 <div id="footer">
  <div id="footer_inner"><div id="textdiv">&copy;<?php echo date("Y",time()); ?>.Project Management System.</div></div>
 </div>

</body>
</html>