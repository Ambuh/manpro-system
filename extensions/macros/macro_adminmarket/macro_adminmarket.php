<?php
function macro_adminmarket(){

GLOBAL $db;

define("ROOT_DIR","../");

include_once("adminmarket_helper.php");

System::adminPageTitle("macro_adminmarket","Manage Market.");

$help=new helper;

if(!isset($_GET['make'])&&(!isset($_GET['edit']))){

$layout=new tabs_layout;

if((isset($_POST['save_p']))|(isset($_POST['make_unpop']))|(isset($_POST['make_pop']))|(isset($_POST['add_make']))|(isset($_POST['add_makes']))|(isset($_POST['del_make']))|(isset($_SESSION[System::getSessionPrefix().'make']))){
$layout->setActiveTab(2);
unset($_SESSION[System::getSessionPrefix().'make']);
}

if(isset($_POST['save_page'])|(isset($_SESSION[System::getSessionPrefix().'edit']))|isset($_POST['del_review'])|isset($_POST['disab_review'])|isset($_POST['enab_review'])){

$layout->setActiveTab(1);

unset($_SESSION[System::getSessionPrefix().'edit']);

}

if(isset($_POST['submit_terms']))
$layout->setActiveTab(3);

$layout->addTab("Manage Listing");

$layout->addTabContent($help->manageListing());

$layout->addTab("Car Review");

$layout->addTabContent($help->reviewPage());

$layout->addTab("Makes & Models");

$layout->addTabContent($help->makeAndModels());

$layout->addTab("About/Terms & Conditions/Private Policy");

$layout->addTabContent($help->termsAndConditions());


$layout->setWidth("890px","200px");

$layout->showTabs(true);
}else{
    
  $layout=new macro_layout;
  
  $layout->setWidth("900px","200px");
  if(isset($_GET['make'])){
  $layout->content=$help->makeDetails();
  $_SESSION[System::getSessionPrefix().'make']=1;
  }
  if(isset($_GET['edit'])){
  $layout->content=$help->editReview();
   $_SESSION[System::getSessionPrefix().'edit']=1;
  }
  $layout->showLayout(true);
}
return true;

}
?>