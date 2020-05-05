<?php
include_once("myHelper.php");

function macro_myaccount(){

	$helper=new myHelper;
	
	System::userPageTitle("macro_myaccount","My Account","","png");
	
	$layout=new macro_layout;
	
	$layout->setWidth("900px","200px");
	
	$layout->content=$helper->defaultPage();
	
	$layout->showLayout();

	return true;
}
?>