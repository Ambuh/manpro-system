<?php
include_once("edit_articlehelper.php");

function macro_articleeditor(){

System::adminPageTitle("macro_articleeditor","Article Manager");
$hlp=new editHelper;

if(!isset($_GET['edit'])){

$layout=new tabs_layout;

if(isset($_POST['save_cat'])|isset($_POST['delete_cat']))
$layout->setActiveTab(1);

$layout->addTab("Manage Articles");

$layout->addTabContent($hlp->articlesPage());

$layout->setWidth("895px","400px");

$layout->addTab("Manage Categories");

$layout->addTabContent($hlp->categoryPage());


$layout->showTabs(true);
}else{

$layout=new macro_layout;

$layout->setWidth("895px","400px");

$layout->content=$hlp->articlesPage();

$layout->showLayout();

}

return true;
}
?>