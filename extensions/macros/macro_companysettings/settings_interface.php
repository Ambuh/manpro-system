<?php
class interfaces{
public $branches;
public $company;
public function __construct(){
$this->company=System::shared("companyinterface");
}
public function branchMainInterface(){

$content=new objectString;

$list=new list_control;

$list->setColumnNames(array("No","","Branch Name"));

$list->setColumnSizes(array("30px","30px","250px"));

$list->setAlternateColor("#cbe7f8");

$list->setHeaderFontBold();

$edit_msg=$this->editBranch();

$list->setTitle("All Branches");

$branches=$this->company->getBranches(PARENT);

$list->setSize("380px","270px");

for($i=0;$i<count($branches);$i++){

 $list->addItem(array($i+1,"<input type=\"radio\" name=\"b_id\" value=\"{$branches[$i]->branch_id}\">","<a href=\"?{$_SERVER['QUERY_STRING']}&siop={$branches[$i]->branch_id}\">".$branches[$i]->branch_name."</a>"));

}

$list->showList(false);

$content->generalTags(System::categoryTitle("Manage branches","margin-bottom:5px;"));

$content->generalTags($edit_msg);

$form=new form_control;

$content->generalTags($form->formHead());

$content->generalTags("<div class=\"user_tab\" style=\"height:370px;width:45%;\">");

$content->generalTags(System::categoryTitle("New branch"));

$input=new input;

$input->setClass("form_input");

$input->input("text","new_branch");

$submit=new input;

$submit->setClass("form_button_add");

$submit->setTagOptions("style=\"float:right;margin-right:10px;margin-top:0px;\"");

$submit->input("submit","add_branch","Add");

$content->generalTags("<div id=\"form_row\" ><div id=\"label\"><strong>Branch Name</strong></div>{$input->toString()}{$submit->toString()}</div>");

$content->generalTags("</div>");


$content->generalTags("<div class=\"user_tab\" style=\"height:370px;border:none;width:52%;\">");

$content->generalTags(System::categoryTitle("A list of branches"));

$content->generalTags($list->toString());

$deleteBtn=new input;

$deleteBtn->setTagOptions("style=\"\"");

$deleteBtn->setClass("form_button_delete");

$deleteBtn->input("submit","delete_btn","Delete");

$content->generalTags("<div id=\"form_row\" style=\"margin-top:-5px;\">{$deleteBtn->toString()}</div>");

$content->generalTags("</div>");


$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");

$content->generalTags("</form");

return $content->toString();

}
public function departmentMainInterFace(){

$content=new objectString;

$content->generalTags(System::categoryTitle("Edit departments","margin-bottom:5px;"));

$content->generalTags($this->editDepartment());

$content->generalTags("<div class=\"user_tab\" style=\"height:370px;width:45%;\">");

$form=new form_control;

$content->generalTags($form->formHead());

$content->generalTags(System::categoryTitle("New department"));

$input=new input;

$input->setClass("form_input");

$input->input("text","department_name");

$submit=new input;

$submit->setClass("form_button_add");

$submit->setTagOptions("style=\"margin-top:0px;float:right;margin-right:10px;\"");

$submit->input("submit","add_deps","Add");

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Dep. Name</strong></div>{$input->toString()}{$submit->toString()}</div>");

$content->generalTags("</div>");

$content->generalTags("<div class=\"user_tab\" style=\"height:370px;width:52%;border:none;\">");

$content->generalTags(System::categoryTitle("A list of departments","margin-left:-2px;"));

$list=new list_control;

$list->setColumnNames(array("No.","","Department Name"));

$list->setHeaderFontBold();

$list->setTitle("All Departments");

$list->setColumnSizes(array("30px","30px","290px"));

$list->setAlternateColor("#cbe7f8");

$list->setSize("378px","270px");

$deps=$this->company->getDepartments(PARENT);

for($i=0;$i<count($deps);$i++){

  $list->addItem(array($i+1,"<input type=\"radio\" name=\"dep_id\" value=\"{$deps[$i]->department_id}\"/>",$deps[$i]->department_name));

}

$list->showList(false);

$content->generalTags($list->toString());

$del=new input;

$del->setTagOptions("style=\"margin-top:-3px;\"");

$del->setClass("form_button_delete");

$del->input("submit","delete_btn","Delete X");

$content->generalTags("<div id=\"form_row\" style=\"margin:0px;\" >{$del->toString()}</div>");

$content->generalTags("</form>");

$content->generalTags("</div>");

$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");

return $content->toString();

}
public function departmentEditInterface(){
return "hello";
}
private function editDepartment(){
 
 if(isset($_POST['add_deps'])){
  
  if($_POST['department_name']!=""){ 
   
   return $this->company->createDepartment($_POST['department_name']);
  
  }
  
 }
 if(isset($_POST['delete_btn'])){
 
   if(isset($_POST['dep_id'])){
   
    return $this->company->deleteDepartment($_POST['dep_id']);
   
   }
 
 }
}
public function positionMainInterface(){

$content=new objectString();

$content->generalTags(System::categoryTitle("Manage Positions","margin-bottom:5px;"));

$form=new form_control;

$content->generalTags($this->updatePosition());

$content->generalTags($form->formHead());

$content->generalTags("<div class=\"user_tab\" style=\"width:45%;height:370px;\">");

$content->generalTags(System::categoryTitle("New Position"));

$input=new input;

$input->setClass("form_input");

$input->input("text","new_position");

$add=new input;

$add->setTagOptions("style=\"margin:0px;float:right;margin-right:3px;\"");

$add->setClass("form_button_add");

$add->input("submit","add_button","Add");

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Position Name</strong></div>{$input->toString()}{$add->toString()}</div>");

$content->generalTags("</div>");

$content->generalTags("<div class=\"user_tab\" style=\"width:52%;border:none;height:370px;\">");

$content->generalTags(System::categoryTitle("Positions","margin-left:-2px;"));

$list=new list_control;

$list->setColumnSizes(array("30px","30px","250px"));

$list->setAlternateColor("#cbe7f8");

$list->setColumnNames(array("No.","","Position Title"));

$list->setTitle("All Positions");

$list->setHeaderFontBold();

$list->setSize("380px","270px");

$positions=$this->company->getPositions("");

for($i=0;$i<count($positions);$i++){

 $list->addItem(array($i+1,"<input type=\"radio\" name=\"pos_id\" value=\"{$positions[$i]->position_id}\"/>",$positions[$i]->position_name));

}

$list->showList(false);

$content->generalTags($list->toString());

$delete_btn=new input;

$delete_btn->setClass("form_button_delete");

$delete_btn->setTagOptions("style=\"margin-top:-2px;\" ");

$delete_btn->input("submit","delete_pos","Delete X");

$content->generalTags("<div id=\"form_row\" style=\"margin:0px;\" >{$delete_btn->toString()}</div>");

$content->generalTags("</div>");

$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\" ></div>");

$content->generalTags("</form>");

return $content->toString();

}
private function editBranch(){

 if(isset($_POST['add_branch'])){
 
   $branch=new branch;
   
   $branch->branch_name=$_POST['new_branch'];
 
   return $this->company->createBranch($branch);
 
 }
 if(isset($_POST['delete_btn'])){
 
  if(isset($_POST['b_id'])){
    
	return $this->company->deleteBranch($_POST['b_id']);
	
   }else{
   
    return System::getWarningText("Please select an item to be deleted");
   
   }
 
 }

}
public function branchEditInterface(){
$content=new objectString;

$list=new list_control;

$list->setColumnNames(array("No.","","Departments"));

$list->setColumnSizes(array("30px","30px","250px"));

$list->setHeaderFontBold();

$list->setTitle("All Departments");

$list->setALternateColor("#cbe7f8");

$list->setSize("379px","205px");

$id=System::getCheckerNumeric("siop");

$msg=$this->updateBranch($id);

$branch=$this->company->getBranch($id);

if($branch->branch_name=="") return;

$form=new form_control;

$content->generalTags($form->formHead());

$content->generalTags($msg);

$content->generalTags("<div class=\"user_tab\" style=\"height:390px;width:45%;\" >");

$content->generalTags(System::categoryTitle("Edit {$branch->branch_name} branch.","margin-bottom:5px;margin-left:-2px;"));

$input=new input;

$input->setClass("form_input");

$input->input("text","edit_branch",$branch->branch_name);

$update_btn=new input;

$update_btn->setClass("form_button_add");

$update_btn->setTagOptions("style=\"float:right;margin:0px;margin-right:10px;\"");

$update_btn->input("submit","update_btn","Update");

$content->generalTags("<div id=\"form_row\" ><div id=\"label\"><strong>Branch Name</strong></div>{$input->toString()}{$update_btn->toString()}</div>");

$content->generalTags("</div>");

if($branch->branch_departments!=""){


$departments=$this->company->fetchDepartments(unserialize($branch->branch_departments),true);

for($b=0;$b<count($departments);$b++){

$list->addItem(array($b+1,"<input type=\"radio\" name=\"dep_id\" value=\"{$departments[$b]->department_id}\"/>",$departments[$b]->department_name));

}

}
$content->generalTags("<div class=\"user_tab\" style=\"height:390px;width:52%;border:none;\" >");

$content->generalTags(System::categoryTitle("Departments","margin-bottom:5px;margin-left:-2px;"));

$list->showList(false);

$content->generalTags($list->toString());

$remove=new input;

$remove->setClass("form_button_disable");

$remove->input("submit","remove_dep","Remove -");

$content->generalTags("<div id=\"form_row\" style=\"margin:0px;\">{$remove->toString()}</div>");

$content->generalTags(System::categoryTitle("Add Department","margin-left:-2px;"));

$select=new input;

$select->setClass("form_select");

$select->addItem(0,"Select Department");

$deps=$this->company->getDepartments(PARENT);

$branch_deps=unserialize($branch->branch_departments);

for($i=0;$i<count($deps);$i++){

if(!in_array($deps[$i]->department_id,$branch_deps)){
 $select->addItem($deps[$i]->department_id,$deps[$i]->department_name);
}

}

$select->select("selected_dep");

$add_button=new input;

$add_button->setClass("form_button_add");

$add_button->setTagOptions("style=\"float:right;margin-right:5px;margin-top:-2px;\"");

$add_button->input("submit","add_button","Add");

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Department</strong></div>{$select->toString()}{$add_button->toString()}</div>");

$content->generalTags("</div>");

$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");

$content->generalTags("</div>");

return $content->toString();
}
private function updateBranch($id){

if(isset($_POST['update_btn'])){

 return $this->company->updateBranch($id,$_POST['edit_branch']);

}

if(isset($_POST['add_button'])){

$brn=$this->company->getBranch($id);

$deps=array();

if($brn->branch_departments!="")
$deps=unserialize($brn->branch_departments);


if(!in_array($_POST['selected_dep'],$deps)){
$deps[]=$_POST['selected_dep'];

return $this->company->updateBranch($id,serialize($deps),1);

}

}

if((isset($_POST['remove_dep']))&(isset($_POST['dep_id']))){

  $brn=$this->company->getBranch($id);
  
  $deps=unserialize($brn->branch_departments);
  
  return $this->company->updateBranch($id,serialize(System::removeFromArray($deps,$_POST['dep_id'])),1);

}

}
public function updatePosition(){

if(isset($_POST['add_button'])){

 return $this->company->createPosition($_POST['new_position']);

}

if(isset($_POST['delete_pos'])){

 if(isset($_POST['pos_id'])){
   return $this->company->deletePosition($_POST['pos_id']);
 }
 
}

}

}

?>