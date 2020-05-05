<?php
function searchbar(){

 return new barGetter;

}
class barGetter{
public function getSearchBar(){
 return new searchbar;
}
}
class searchbar extends objectString{
private $values=array();
private $target;
private $targetApp;
private $selected=array();
private $parameters=array();
private $control_labels=array();
private $controlTypesNames=array();
public function setTargetDiv($target){
  $this->target=$target;
}
public function setResponder($responder){
$this->targetApp=$responder;
}
public function addParameters($para){

 $this->parameters=$para;

}
public function addSelected($addname){

 $this->selected[]=$addname;

}
public function addLabels($label){
 $this->control_labels=$label;
}
public function addValues($values){
 $this->values[]=$values;
}
public function addControls($control){

 $this->controlTypesNames=$control;

}
public function showBar(){

 $this->generalTags("<div id=\"form_row\">");

for($i=0;$i<count($this->controlTypesNames);$i++){

 $this->generalTags("<div id=\"label\"><strong>".System::getArrayElementValue($this->control_labels,$i,"No label")."</strong></div>");
 
 switch($this->controlTypesNames[$i]->name){
 
 case "select":
 $select=new input;
 $select->enableAjax(true);
  
 if(System::getArrayElementValue($this->selected,$i,0)!=0)
 for($s=0;$s<count($this->selected[$i]);$s++){
  $select->setSelected($this->selected[$i][$s]);
 }
 
 $select->setClass("form_select");
 
 if((System::getArrayElementValue($this->values,$i,0)!=0)&(System::getArrayElementValue($this->values,$i,0)!=""))
 $select->addItems($this->values[$i]);
 
 $select->showAjaxProgress($this->controlTypesNames[$i]->value);
 $select->setId($this->controlTypesNames[$i]->value);
 $select->select($this->controlTypesNames[$i]->value,System::generateAjaxParams($this->target,$this->targetApp,System::getArrayElementValue($this->parameters,$i)));
 
 $this->generalTags($select->toString());
 
 break;
 
 case "text":
 $input=new input;
 $input->setClass("form_input");
 $input->setId($this->controlTypesNames[$i]->value);
 $input->enableAjax(true);
 $input->showAjaxProgress();
 $input->input("text",$this->controlTypesNames[$i]->value,"",System::generateAjaxParams($this->target,$this->targetApp,System::getArrayElementValue($this->parameters,$i),"onkeyup"));
 $this->generalTags($input->toString());
 break;
 
 }
 
}

$this->generalTags("</div>");

return $this->toString();

}
}
?>