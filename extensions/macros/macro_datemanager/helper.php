<?php
class datehelper{
public $company;
public $Me;
private $mess;
private $cal;
public function __construct(){
$this->company=System::shared("companyinterface");
$this->Me=$this->company->getMyEmploymentInfo();
$this->cal=System::shared("calendarmarker");
$this->process();
}
public function mainPage(){

$list=new list_control;
	
$content=new objectString();
  
$content->generalTags(System::contentTitle("Organisation's important dates"));
 
$content->generalTags($this->mess);
 
$content->generalTags(System::categoryTitle("Important dates","margin-bottom:5px;"));

$form=new form_control;

$content->generalTags($form->formHead());

$cal=System::shared("calendarmarker");

if($this->Me->employee_position-2){

$list->setColumnNames(array("No.","","Date","Event"));

$list->setColumnSizes(array("40px","30px","200px","300px"));

}else{

$list->setColumnNames(array("No.","Date","Event"));

$list->setColumnSizes(array("40px","200px","300px"));

}

$list->setHeaderFontBold();

$list->setAlternateColor("#cbe3f8");

$list->setSize("731px","250px");

$dates=$cal->getMarkedDates();

for($i=0;$i<count($dates);$i++){

if($this->Me->employee_position==-2){
 $list->addItem(array($i+1,"<input type=\"radio\" name=\"marked_date\" value=\"{$dates[$i]->importantDate_id}\" />",$dates[$i]->importantDate_date,$dates[$i]->importantDate_description));
}else{
 $list->addItem(array($i+1,"<input type=\"radio\" name=\"marked_date\" value=\"{$dates[$i]->importantDate_id}\" />",$dates[$i]->importantDate_date,$dates[$i]->importantDate_description));

}
}

$list->showList();

$content->generalTags($list->toString());

if($this->Me->employee_position==-2){
	
$button=new input();

$button->setClass("form_button_delete");

$button->setTagOptions("style=\"float:right;margin-left:12px;margin-top:0px;\"");

$button->input("submit","rem_date","Remove");

$content->generalTags("<div id=\"form_row\">{$button->toString()}</div>");
  
$content->generalTags(System::categoryTitle("Add Date"));
  
System::enableDatePicker();

$button=new input();

$button->setClass("form_button_add");

$button->setTagOptions("style=\"margin-left:12px;margin-top:5px;float:right;\"");

$button->input("submit","add_date","Add");

$input=new input;

$input->setTagOptions("style=\"margin-left:13px;float:left\"");

$input->setId("adts");

$input->makeDatePicker("dd/mm/yyyy","0");

$input->setClass("form_input");

$input->input("text","important_date",System::getArrayElementValue($this->cal->buffered_vals,0));

$content->generalTags("<div id=\"form_row\"><div id=\"label\" ><strong style=\"margin-left:0px;\" >New Date</strong></div>{$input->toString()}</div>");

$content->generalTags("<div id=\"form_row\" style=\" margin-top:5px;float:left;\"><div id=\"label\" ><strong style=\"margin-left:0px; margin-top:5px;\" >Description</strong></div><textarea style=\"margin-top:5px; margin-left:12px; font-size:12px;\" class=\"form_input\" name=\"i_descrip\">".System::getArrayElementValue($this->cal->buffered_vals,1)."</textarea></div>");

$content->generalTags("<div id=\"form_row\" style=\"margin-bottom:0px;\">{$button->toString()}</div>");

  
}
 $content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>"); 
  
  return $content->toString();
}
private function process(){
	

$mrk=new cMark;

$mess="";

if(isset($_POST['add_date'])){

$mrk->importantDate_date=$_POST['important_date'];

$mrk->importantDate_description=$_POST['i_descrip'];

$this->mess= $this->cal->markDate($mrk);

}

if((isset($_POST['rem_date']))&(isset($_POST['marked_date']))){

$this->mess= $this->cal->removeMarkedDate($_POST['marked_date']);

}

}
}
?>