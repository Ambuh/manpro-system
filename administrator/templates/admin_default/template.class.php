<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>

<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="no-cache"  />
<meta http-equiv="expires" content="-1"  />
<meta http-equiv="Cache-Control" content="no-cache" />

<title><?php if(defined("PAGE_TITLE")): echo PAGE_TITLE; endif; ?></title>

<link rel="stylesheet" href="<?php echo self::$template_path; ?>css/general.css" type="text/css" />

<?php self::loadHeadScripts(); ?>

</head>

<?php //echo serialize(array()); ?>

<body>

<div id="all">
<?php 

if(defined("LOGGED")){
 echo LOGGED;
}

?>

<div id="header"><div id="inner_header"><div id="title_div">System Administration</div></div></div>

<div id="top_bar"><div id="inner_bar"><div id="user_title">User: <?php echo $User->username; ?></div></div></div>

<div id="container_main">
   
<div id="container">

   <div id="container_inner">

      <div id="left_col">
                  
         <?php $extension->loadModule('left',1)?>
                  
     </div><!--end left_col-->

 <div id="content_main">
  
  <?php $extension->loadMacro(); ?>
  
 
<!--list object-->
  <?php
  $cont =new list_control;
  $cont->setHeaderFontBold(true);
 $cont->setSize("760px","300px");
 $cont->setBackgroundColour("#fff");
 $cont->addItem(array("client","client","client"));
 $cont->addItem(array("client2","client2","client2"));
 $cont->setListId("KK");
 $cont->setColumnNames(array("No.","Name","Second Name"));
  $cont->setColumnSizes(array("150px","150px","150px;"));
  $cont->setAlternateColor("#ccc");
  $cont->setTitle("Mytitle");
  $cont->showList(false);
  ?>
<!--tabs-->


<?php
$tbs=new tabs_layout;
$tbs->addTab("Wakoli bifwoli");
$tbs->addTabContent($cont->toString()."<br/><br/>");
$tbs->addTabContent("fffffffffff");
$tbs->addTabContent("<input type=\"submit\" value=\"Submit\" /><br/><br/>");
$tbs->addTab("Wekesa Maina");
$tbs->addTab("Jame Makau");
//$tbs->showTabs();
  ?>
  
  <?php 
     $comp_layout=new macro_layout;
      $comp_layout->title="My article";
       $comp_layout->setWidth("762px");
      $comp_layout->show_title=True;
       $comp_layout->content="This it<br/><br/>";
      //$comp_layout->showlayout();
     ?>
      </div>
    </div>
   </div>
  </div>
</div>

<div id="footer"><div id="footer_labels">&copy;2012.Elligent Business Solutions.Client Support System</div>

</div>
</body>
</html>
