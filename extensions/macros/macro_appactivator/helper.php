<?php
class myhelper{
public $company;
private $update_profile;
private $create_message;
private $category_message;
private $categories;
private $account_types;
private $prev_values=array();
private $admins;

public function __construct(){

 GLOBAL $db;

 $this->prev_values=array();

 $this->company=System::shared("companyinterface");
 
 $this->category_message=$this->createDeleteCats();
 
 $this->categories=$this->company->getCompanyCategories();
 
 $this->account_types=$this->company->getAccountTypes();
 
 $this->admins=$db->getUserDetails("where user_type=1");
 
 $this->update_profile=$this->updateCompanyProfile();
 
}
public function loadBlock($option=0){

 $this->create_message=$this->createCompany();

 switch($option){
  case 1:
  $this->companyDetails();
  
  break;
  
  case 2:
  
  break;
  
  case 3:
  
  break;
  
  default:
  $this->mainPage();
  
 }
} 
private function mainPage(){

$content =new objectString;

$tbs=new tabs_layout;

$tbs->addTab("Companies");

$tbs->addTabContent($this->companyList());

$tbs->setWidth("762px");

$tbs->addTab("Create Company");

$tbs->addTabContent($this->createCompanyForm());

$tbs->addTab("Manage Categories");

$tbs->addTabContent($this->categories());

$tbs->setActiveTab($this->setActiveTab());

$tbs->showTabs();

}

private function companyList(){

 $content=new objectString;

 $cont =new list_control;
 
 $cont->setHeaderFontBold();
 
 $cont->setSize("730px","320px");
 
 $cont->setBackgroundColour("#fff");
 
 $cont->setListId("KK");
 
 $cont->setColumnNames(array("No.","","Company Name","Status","Email","Tel.","Users"));
 
 $cont->setColumnSizes(array("40px","30px","150px","50px","220px","90px","90px"));
 
 $status_message=$this->changeStatus();
 
 $comp=$this->company->getCompanies();
 
 for($i=0;$i<count($comp);$i++){
 
   $cont->addItem(array($i+1,"<input type=\"radio\" name=\"comp_id\"value=\"{$comp[$i]->company_id}\" />","<a href=\"?mid={$_GET['mid']}&mopt=1&id={$comp[$i]->company_id}\">".$comp[$i]->company_name."</a>","<div style=\"margin-left:7px\">".System::statusIcon($comp[$i]->company_status)."</div>","{$comp[$i]->company_email}","{$comp[$i]->company_phone}","{$comp[$i]->company_users}"));
 
 }
 
 $cont->setAlternateColor("#cbe7f8");
 
 //$cont->setTitle("All Companies");
 
 $cont->showList(false);
 
 $content->generalTags(System::backButton("?"));
 
 $content->generalTags(System::contentTitle("View/Edit Companies"));
 
 $content->generalTags(System::categoryTitle("Registered companies","margin-bottom:5px;"));
 
 $content->generalTags($status_message);
 
 $form=new form_control;
  
 $content->generalTags($form->formHead());
 
 $content->generalTags($cont->toString());
 
 $submit=new input;
 
 $submit->setClass("form_button");
 
 $submit->input("submit","status_enable","Enable");
 
 $submit2=new input;
 
 $submit2->setClass("form_button_disable");
 
 $submit2->input("submit","status_disable","Disable");
 
 $content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\">{$submit->toString()}{$submit2->toString()}</div>");
 
 $content->generalTags("</form>");
 
 return $content->toString();

}
private function createCompanyForm(){
$content2=new objectString;

$content2->generalTags(System::backButton("?"));

$content2->generalTags(System::contentTitle("Company Details"));

$content2->generalTags(System::categoryTitle("Name and username","margin-bottom:5px;"));

$content2->generalTags($this->create_message);

$form=new form_control;

$content2->generalTags($form->formHead());

$input=new input;

$input->setClass("form_input");

$input->input("text","new_companyname",$this->getValue(0));

$content2->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Company Name</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_input");

$input->input("text","new_username",$this->getValue(1));

$content2->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Username</strong></div>{$input->toString()}</div>");

$content2->generalTags(System::categoryTitle("Category/Description","margin-bottom:5px;"));

$select=new input;

$select->setClass("form_select");

$select->setSelected($this->getValue(2));

$select->addItem(0,"Select Category");

for($i=0;$i<count($this->categories);$i++){

  $select->addItem($this->categories[$i]->category_id,$this->categories[$i]->category_name);

}

$select->select("new_category");

$content2->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Category</strong></div>{$select->toString()}</div>");

$content2->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Description</strong></div><textarea class=\"form_input\" name=\"new_description\">{$this->getValue(3)}</textarea></div>");

$content2->generalTags(System::categoryTitle("Contact details","margin-bottom:5px;"));

$input=new input;

$input->setClass("form_select");

$input->input("text","new_email",$this->getValue(4));

$content2->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Email Address</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_select");

$input->input("text","new_tel",$this->getValue(5));

$content2->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Tel.</strong></div>{$input->toString()}</div>");

$content2->generalTags(System::categoryTitle("Company administration","margin-bottom:5px;"));

$input=new input;

$input->setClass("form_select");

$input->setSelected($this->getValue(6));

$input->addItem(0,"No Admin");

for($i=0;$i<count($this->admins);$i++){

  $input->addItem($this->admins[$i]->id,$this->admins[$i]->firstname);

}

$input->select("new_admin");

$content2->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Select Admin</strong></div>{$input->toString()}</div>");

$select=new input;

$select->setClass("form_select");

$select->addItem(0,"Not Selected");

$select->setSelected($this->getValue(7));

for($b=0;$b<count($this->account_types);$b++){

$select->addItem($this->account_types[$b]->account_id,$this->account_types[$b]->account_title);

}

$select->select("new_accounttype");

$content2->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Account Type</strong></div>{$select->toString()}</div>");

$content2->generalTags(System::categoryTitle("Physical Location","margin-bottom:5px;"));

$content2->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Location</strong></div><textarea class=\"form_input\" name=\"new_location\">{$this->getValue(8)}</textarea></div>");

$submit=new input;

$submit->setClass("form_button");

$submit->setTagOptions("style=\"float:right;\"");

$submit->input("submit","create_company","Submit");

$content2->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\">{$submit->toString()}</div>");

$content2->generalTags("</form>");
//$content2->generalTags(system::categoryTitle("Add company category","margin-bottom:10px;"));

return $content2->toString();

}
private function categories(){

$content=new objectString;

$list=new list_control;

$list->setColumnSizes(array("30px","30px","290px"));

$list->setColumnNames(array("No","","Name"));

//$list->setTitle("Company categories");

$list->setSize("400px","350px");

$list->setAlternateColor("#cbe7f8");


for($c=0;$c<count($this->categories);$c++){

 $list->addItem(array($c+1,"<input type=\"radio\" name=\"cat_id\" value=\"{$this->categories[$c]->category_id}\"/>",$this->categories[$c]->category_name)); 

}

$list->showList(false);

$content->generalTags(System::backButton("?"));

$content->generalTags(System::contentTitle("Categories"));

$content->generalTags(System::categoryTitle("Manage Company Categories","margin-bottom:5px;"));

$content->generalTags($this->category_message);

$form=new form_control;

$content->generalTags($form->formHead());

$content->generalTags("<div style=\"float:left;width:400px;\">");

$content->generalTags($list->toString());

$content->generalTags("</div>");

$content->generalTags("<div style=\"float:left;width:310px; margin-left:10px;\">");

$content->generalTags(System::categoryTitle("Add Category"));

$input=new input;

$input->setClass("form_input");

$input->input("text","add_category");

$content->generalTags("<div id=\"form_row\" style=\"margin-top:20px;\"><div id=\"label\" style=\"margin-left:5px;\"><strong>Category Name</strong></div>{$input->toString()}</div>");

$submit_add=new input;

$submit_add->setClass("form_button ");

$submit_add->setTagOptions(" style=\"margin-left:220px;\"");

$submit_add->input("submit","submit_category","Add");

$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\">{$submit_add->toString()}</div>");

$content->generalTags("</div>");

$deletebtn=new input;

$deletebtn->setClass("form_button_delete");

$deletebtn->input("submit","delete_btn","Delete X");

$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\">{$deletebtn->toString()}</div>");

$content->generalTags("</form>");

return $content->toString();

}
private function companyDetails(){
GLOBAL $db;
 $layout=new macro_layout;
 
 $layout->setWidth("762px");
 
 $content=new objectString();
 
 $content->generalTags(System::backButton("?mid={$_GET['mid']}"));
 
 $content->generalTags(System::contentTitle("Company Profile"));
 
 
 
 $id=System::getCheckerNumeric("id");
 
 $company=$this->company->getCompanyById($id);
 
 if($company!=NULL){
 
 $content->generalTags(System::categoryTitle("Company Details for $company->company_name.","margin-bottom:5px;"));
 
 $content->generalTags($this->update_profile);
 
 $form=new form_control;
 
 $content->generalTags($form->formHead());
 
 $content->generalTags("<div style=\"width:45%;float:left;\">");
 
 $input=new input;
 
 $input->setClass("form_input");
 
 $input->input("text","edit_cname",$company->company_name);
  
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Company Name</strong></div>{$input->toString()}</div>");
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Co. Username</strong></div>{$company->company_username}</div>");
 
 $select=new input;
 
 $select->setClass("form_select");
 
 $select->addItem(0,"No Category");
 
 $select->setSelected($company->company_category);
 
 for($d=0;$d<count($this->categories);$d++){
  
   $select->addItem($this->categories[$d]->category_id,$this->categories[$d]->category_name);
 
 }
 
 $select->select("edit_category");
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Category</strong></div>{$select->toString()}</div>");
 
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Sign-up Date</strong></div>{$company->company_dateCreated}</div>");
 
 $select =new input;
 
 $select->setClass("form_select");
 
 $select->addItem(0,"Select Type");
 
 $select->select("edit_caccounttype");

 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Account Type</strong></div>{$select->toString()}</div>");

 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Status</strong></div>".System::radioStatus("edit_cstatus",$company->company_status)."</div>");

 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Users</strong></div>{$company->company_users}</div>");
 
 $select=new input;
 
 $select->setClass("form_select");
 
 $select->addItem(0,"No Admin");
 
 for($i=0;$i<count($this->admins);$i++){
 
   if($this->admins[$i]->parent_id==$company->company_id){
   
     $select->setSelected($this->admins[$i]->id);
   
   }
   
   $select->addItem($this->admins[$i]->id,$this->admins[$i]->firstname);
   
 }
 
 $select->select("edit_cadministrator");
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Administator</strong></div>{$select->toString()}</div>");
 
 $content->generalTags("</div>");

 
 $content->generalTags("<div style=\"width:50%;float:left;\">");
 
 $input=new input;
 
 $input->setClass("form_input");
 
 $input->input("text","edit_location",$company->company_location);
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Location</strong></div>{$input->toString()}</div>");
 
 $input=new input;
 
 $input->setClass("form_input");
 
 $input->input("text","edit_cemail",$company->company_email);
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Email</strong></div>{$input->toString()}</div>");
 
 $input=new input;
 
 $input->setClass("form_input");
 
 $input->input("text","edit_ctel",$company->company_phone);
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Tel:</strong></div>{$input->toString()}</div>");
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Description</strong></div><div class=\"form_input\" style=\"height:70px;padding:1px;overflow-y:scroll;\" name=\"uneditable\">{$company->company_description}</div></div>");


  $content->generalTags("</div>");
  
  $button=new input;
  
  $button->setClass("form_button_add");
  
  $button->setTagOptions("style=\"float:right;\"");
  
  $button->input("submit","update_company","Update");
  
  $content->generalTags("<div id=\"form_row\">{$button->toString()}</div>");
  
 $content->generalTags(System::categoryTitle("User accounts under {$company->company_name}","margin-bottom:3px;"));
 
 $list=new list_control;
 
 $list->setColumnSizes(array("30px","30","250px","220px","60px","100px"));
 
 $list->setColumnNames(array("No.","","Name","Username","Status","Type"));
 
 $list->setHeaderFontBold();
 
 $list->setSize("730px","280px");
 
 $list->setAlternateColor("#cbe7f8");
 
 $users=$db->getUserdetails("where parent_id=$company->company_id");
 
 for($b=0;$b<count($users);$b++){
 
  $list->addItem(array($b+1,"<input type=\"radio\" name=\"admin\" value=\"{$users[$b]->id}\" />",$users[$b]->firstname." ".$users[$b]->secondname." ".$users[$b]->lastname,$users[$b]->username,"<div style=\"margin-left:10px;float:left;\">".System::statusIcon($users[$b]->user_status)."</div>",System::userType($users[$b]->user_type)));
 
 }
  
 $list->showList(false);
 
 
 
 $content->generalTags($list->toString());
 
 $input=new input;
 
 $input->setClass("form_button");
 
 $input->input("submit","set_admin","Make Admin");
 
 $content->generalTags("<div id=\"form_row\" style=\"border-bottom:2px solid #444;\">{$input->toString()}</div>");
 
 $content->generalTags("</form>");
 
 }
 $layout->content=$content->toString();
 
 $layout->showLayout();
 
}
private function changeStatus(){

$message="Enabled";

$status=1;

if(isset($_POST['status_enable'])||(isset($_POST['status_disable']))){

 if(isset($_POST['status_disable'])){

  $message="Disabled";
  
  $status=0;

 }

if(!isset($_POST['comp_id']))
return;

$this->company->changeCompanyStatus($status,$_POST['comp_id']);

return System::successText("Account $message");

}

}
private function createCompany(){
GLOBAL $db;

$labels=array("Company Name","Username","Category","Description","Email Address","Tel","Select Admin","Account Type","Location");

if(isset($_POST['create_company'])){

  $items=System::getPostedItems("new");
  
  for($i=0;$i<count($items);$i++){
 
     $this->prev_values[]=$items[$i]->value;
   
  }
 
 $ind=$this->checkEmpty();
 
 if(!$this->usernameAvailable(System::getPostValue($items,"new_username"))){ 
 
   return System::getWarningText("Username not available"); 
 
 }
 
 
 
 if($ind>-1){

  return System::getWarningText("Field '{$labels[$ind]}' Required");
 
 }else{ 
 
 $this->prev_values=array();

 $db->insertQuery(array("cname","email","phone","adminid","datecreated","acctount_type","company_description","location","company_username"),"companies",array("'".System::getPostValue($items,"new_companyname")."'","'".System::getPostValue($items,"new_email")."'","'".System::getPostValue($items,"new_tel")."'",System::getPostValue($items,"new_admin"),"CURDATE()",System::getPostValue($items,"new_accounttype"),"'".System::getPostValue($items,"new_description")."'","'".System::getPostValue($items,"new_location")."'","'".System::getPostValue($items,"new_username")."'"));

  mkdir(ROOT.System::getPostValue($items,"new_username"));

 $res=$db->selectQuery(array("max(id)"),"companies","");
  
  while($row=mysqli_fetch_row($res)){
  
  $cont="<?php
define(\"ROOT\",str_replace(\"".System::getPostValue($items,"new_username")."/\",\"\",dirname(__FILE__).\"/\"));

define(\"PARENT\",{$row[0]});

if(isset(\$_GET['fd'])){
include_once(ROOT.'plugins/downloader/downloader.php');
}

define(\"NOT_DEFAULT\",\"\");

define(\"FACE\",\"front\");

include_once(\"../library/globals/loaders.php\");

?>";
  
 file_put_contents(ROOT.System::getPostValue($items,"new_username")."/index.php",$cont);
  
 }
  
  

  return System::successText("Company created");
 
 }

}

}
private function usernameAvailable($uname){

GLOBAL $db;

$status=true;

$res=$db->selectQuery(array("id"),"companies","where company_username='$uname'");

while($row=mysqli_fetch_row($res)){
  $status=false;
}

return $status;

}

private function checkEmpty(){

 for($i=0;$i<count($this->prev_values);$i++){
 
   if($this->prev_values[$i]==""){

	 return $i;
	 
   }
 
 }
  
 return -1;
 
}
private function getValue($i){

if(isset($this->prev_values[$i])){

 return $this->prev_values[$i];

}

}
private function updateCompanyProfile(){

GLOBAL $db;

if(isset($_POST['update_company'])){

$items=System::getPostedItems("edit");

$current_id=0;

//$db->updateQuery(array("user_type=0"),"users","where parent_id={$_GET['id']} and user_type=1");

$db->updateQuery(array("parent_id={$_GET['id']}","user_type=1"),"users","where id=".System::getPostValue($items,"edit_cadministrator"));

//echo System::getPostValue($items,"edit_cadministrator");

$db->updateQuery(array("cname='".System::getPostValue($items,"edit_cname")."'","acctount_type=".System::getPostValue($items,"edit_caccounttype"),"email='".System::getPostValue($items,"edit_cemail")."'","location='".System::getPostValue($items,"edit_location")."'","adminid=".System::getPostValue($items,"edit_cadministrator"),"phone='".System::getPostValue($items,"edit_ctel")."'","status=".System::getPostValue($items,"edit_cstatus"),"category=".System::getPostValue($items,"edit_category")),"companies","where id={$_GET['id']}");

return System::successText("Profile Updated");

}
if(isset($_POST['set_admin'])&&(isset($_POST['admin']))){

//$db->updateQuery(array("user_type=0"),"users","where parent_id={$_GET['id']} and user_type=1");

$db->updateQuery(array("parent_id={$_GET['id']}","user_type=1"),"users","where id={$_POST['admin']}");

$count=0;
  
 $resource=$db->selectQuery(array("id"),'users',"where parent_id={$_GET['id']}");
 
 while($rw=mysqli_fetch_row($resource)){
 
   $count++;
 
 }
 
 $db->updateQuery(array("users=$count"),"companies","where id={$_GET['id']}");

return System::successText("Admin Changed");

}
 
}
public function createDeleteCats(){
if(isset($_POST['submit_category'])){

return $this->company->createCompanyCategory($_POST['add_category']);

}

if(isset($_POST['delete_btn'])){

 return $this->company->deleteCompanyCategory($_POST['cat_id']);

}

}
public function setActiveTab(){

 $active=0;
 
 if(isset($_POST['create_company'])){
 
   $active=1;
 
 }
 if(isset($_POST['delete_btn'])||(isset($_POST['submit_category']))){
 
    $active=2;
  
 }
 
 return $active;
 
}

}
?>