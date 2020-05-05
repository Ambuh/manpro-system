<?php

include_once("settings_interface.php");

class helper{

public $settingInterface;

private $message;

public function __construct(){

 $this->settingInterface=new interfaces;

 $this->addProjectCreator();
 
 $this->removeProjectCreator();

}

public function branches(){

$content=new objectString();


if(!isset($_GET['siop'])){

$content->generalTags(System::contentTitle("Branches"));

$content->generalTags($this->settingInterface->branchMainInterface());

}else{

$content->generalTags(System::backButton("?mid={$_GET['mid']}&sopt=0"));

$content->generalTags(System::contentTitle("Edit Branch"));

$content->generalTags($this->settingInterface->branchEditInterface());

}
return $content->toString();

}
public function departments(){

$content=new objectString();
if(!isset($_GET['siop'])){
$content->generalTags(System::contentTitle("Departments"));

$content->generalTags($this->settingInterface->departmentMainInterface());
}else{

$content->generalTags(System::backButton("?mid={$_GET['mid']}&sopt=1"));

$content->generalTags(System::contentTitle("Departments"));

$content->generalTags($this->settingInterface->departmentEditInterface());

}
return $content->toString();

}
public function positions(){

$content=new objectString();

$content->generalTags(System::contentTitle("Positions"));

$content->generalTags($this->settingInterface->positionMainInterface());

return $content->toString();

}

public function hirachy(){

$content=new objectString();

$content->generalTags(System::contentTitle("Project settings"));

$content->generalTags(System::categoryTitle("Project creators.","margin-bottom:5px;"));

$content->generalTags($this->message);

$form=new form_control;

$employees=$this->settingInterface->company->getEmployees();

$creators=$this->settingInterface->company->getProjectCreators();

$content->generalTags($form->formHead());

$list=new list_control;

$list->setColumnNames(array("No","","Name","Status"));

$list->setColumnSizes(array("40px","30px","150px","50px"));

$list->setHeaderfontBold();

$list->setListId("dm");

$list->setAlternateColor("#cbe3f8");

$list->setSize("350px","350px");

$no=0;

for($i=0;$i<count($employees);$i++){

if(!in_array($employees[$i]->id,$creators))
$list->addItem(array(++$no,"<input type=\"checkbox\" name=\"ch$i\" value=\"{$employees[$i]->id}\"/>",$employees[$i]->firstname." ".$employees[$i]->secondname,System::statusIcon($employees[$i]->user_status)));

}

$list->showList();

$content->generalTags("<div style=\"float:left;overflow:hidden;width:350px;\">");

$content->generalTags(System::categoryTitle("Users","margin-bottom:2px;"));

$content->generalTags($list->toString());

$input=new input;

$input->setClass("form_button");

$input->setTagOptions("style=\"float:right;\"");

$input->input("submit","add_user","Add>>");

$content->generalTags("<div id=\"form_row\" style=\"margin-bottom:0px;\">{$input->toString()}</div>");

$content->generalTags("</div>");

$list2=new list_control;

$list2->setColumnNames(array("No","","Name","Status"));

$list2->setColumnSizes(array("40px","30px","200px","50px"));

$list2->setAlternateColor("#cbe3f8");

$list2->setHeaderfontBold();

$list2->setSize("350px","350px");

$no=0;

for($i=0;$i<count($employees);$i++){

if(in_array($employees[$i]->id,$creators)){
	
$list2->addItem(array(++$no,"<input type=\"radio\" name=\"eid\" value=\"{$employees[$i]->id}\"/>",$employees[$i]->firstname." ".$employees[$i]->secondname,System::statusIcon($employees[$i]->user_status)));

}

}


$list2->showList();

$content->generalTags("<div style=\"float:left;overflow:hidden;margin-left:20px;width:350px;\">");

$content->generalTags(System::categoryTitle("Project Creators","margin-bottom:2px;"));

$content->generalTags($list2->toString());

$rem=new input;

$rem->setClass("form_button_delete");

$rem->setTagOptions("style=\"float:right;\"");

$rem->input("submit","remove_user","Remove");

$content->generalTags("<div id=\"form_row\" style=\"marign-bottom:0px;\">{$rem->toString()}</div>");

$content->generalTags("</div>");

$content->generalTags("</form>");

$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");

return $content->toString();

}
public function addProjectCreator(){

if(isset($_POST['add_user'])){

$items=System::nameValueToSimpleArray(System::getPostedItems("ch"),true);

$this->message=$this->settingInterface->company->updateProjectCreator($items);

}

}
public function removeProjectCreator(){

if(isset($_POST['remove_user'])){

 $this->message=$this->settingInterface->company->removeProjectCreator($_POST['eid']);

}

}
}
?>