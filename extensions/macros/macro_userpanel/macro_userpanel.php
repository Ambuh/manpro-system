<?php
include_once("helper.php");
function macro_userpanel(){

 $helper=new helper;
	
 System::userPageTitle("macro_userpanel","User Dashboard",false);
	
 $layout=new macro_layout;
 
 $layout->setWidth("900px","200px");
 
 switch(System::getCheckerNumeric("sub")){
 case 2:
 	 $layout->content=$helper->viewAppDetails();
 	 break;
 
 default:
 	 $layout->content=$helper->defaultPage();
 
 }
 
 $layout->showLayout();
	
 return true;
	
}

?>