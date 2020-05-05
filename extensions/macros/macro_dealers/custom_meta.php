<?php
function meta_run($type){
	include_once("dealerhelper.php");
    $hlp=new dealerHelper;
	switch($type){
	  case TITLE:
	  return $hlp->pageTitle();//"hello there";
	  break;
	  case META:
	  //return $hlp->metaTags();
	}

  return false;
 
}
?>