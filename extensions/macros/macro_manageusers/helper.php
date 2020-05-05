<?php
class userHelper{
private $previous_vals=array();
private $created_user_message;

public function __construct(){
}
public function mainPage(){
 GLOBAL $db;
 $content=new objectString();
 
 $this->create_user_message=$this->createAction();
 
 $list=new list_control;
 
 $list->setSize("860px","370px");
 
 //$list->setTitle("All Users");
 
 $list->setAlternateColor("#cbe7f8");
 
 $list->setHeaderFontBold();
 
 $list->setColumnSizes(array("30px","30px","240px","180px","120px","50px"));
 
 $updatemsg=$this->updateStatus();
 
 $sess=$db->getUserSession();
   
 $users=$db->getUserDetails("where id<>$sess->id and user_type<>9 and parent_id=0 or id<>$sess->id and user_type<>9 and user_type=1");

 
 for($i=0;$i<count($users);$i++){
 
  $list->addItem(array($i+1,System::checkbox("chk_$i","checker",$users[$i]->id),"<a href=\"?mid={$_GET['mid']}&eid={$users[$i]->id}\">".$users[$i]->firstname." ".$users[$i]->secondname." ".$users[$i]->lastname."</a>",$users[$i]->username,System::userType($users[$i]->user_type),"<div style=\"margin-left:13px;\" >".System::statusIcon($users[$i]->user_status)."</div>"));
 
 }
 
 $list->setColumnNames(array("No.",System::headerCheckbox("checker","chk",count($users)),"Name","Username","Type","Status"));

 $list->showList(false);
 
 $tabsControl=new tabs_layout;
 
 $tabsControl->addTab("View Users");
 
 $tabsControl->setWidth("895px");
 
 $content->generalTags(System::backButton("?"));
 
 $content->generalTags(System::contentTitle("Manage User"));
 
 $content->generalTags($updatemsg);
 
 $form=new form_control;
 
 $content->generalTags($form->formHead());
 
 $submit=new input;
 
 $submit->setTagOptions("style=\"float:right;margin-right:5px;\"");
 
 $submit->setClass("form_button");
 
 $submit->input("submit","enable_user","Enable");

 $submit2=new input;
 
 $submit2->setTagOptions("style=\"float:right;\"");
 
 $submit2->setClass("form_button_disable");
 
 $submit2->input("submit","disable_user","Disable");
 
 $content->generalTags(System::categoryTitle("System users{$submit->toString()}{$submit2->toString()}","margin-bottom:5px;"));
 
 $content->generalTags($list->toString());
 
 $content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");
 
 //$content->generalTags("</div>");
 
 $tabsControl->addTabContent($content->toString());
 
 $tabsControl->setActiveTab($this->setActiveTab());
 
 $tabsControl->addTab("Create user");
 
 $tabsControl->addTabContent($this->createUser());
 
 $tabsControl->addTab("My Account");
 
 $tabsControl->addTabContent($this->myAccount());
 
 $tabsControl->showTabs();
}

public function editPage(){

GLOBAL $db;

$layout=new macro_layout;

$content=new objectString;

$content->generalTags(System::backButton("?mid={$_GET['mid']}"));

$content->generalTags(System::contentTitle("User details"));

$content->generalTags(System::categoryTitle("Basic information"));

$form=new form_control;

$content->generalTags($this->updateUserDetails());

$content->generalTags($form->formHead());

$dets=$db->getUserDetails(" where id={$_GET['eid']} and parent_id=0 and user_type<>9 or id={$_GET['eid']} and user_type=1 and user_type<>9");

for($i=0;$i<count($dets);$i++){
//$content->generalTags();

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>First Name</strong></div>{$dets[$i]->firstname}</div>");

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Middle Name</strong></div>{$dets[$i]->secondname}</div>");

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Surname</strong></div>{$dets[$i]->lastname}</div>");

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Gender</strong></div>".System::genderTypeText($dets[$i]->gender)."</div>");

$content->generalTags(System::categoryTitle("Account Details"));

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Username</strong></div>{$dets[$i]->username}</div>");

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Status</strong></div>".System::radioStatus("status",$dets[$i]->user_status)."</div>");

$select=new input;

$select->setClass("form_select");

$select->addItem(0,"User");

$select->setTagOptions("disabled");

$select->addItem(1,"Administrator");

$select->setSelected($dets[$i]->user_type);

$select->select("update_usertype");

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>User Type</strong></div>{$select->toString()}</div>");

$input=new input;

$input->setClass("form_input");

$input->input("text","update_email",$dets[$i]->email_address);

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Email</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_input");

$input->input("text","update_cellphone",$dets[$i]->cellphone);
$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Tel.</strong></div>{$input->toString()}</div>");

$submit=new input;

$submit->setTagOptions("style=\"float:right;margin-right:10px;\"");

$submit->setClass("form_button_add");

$submit->input("submit","update_user","Update");

$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\">{$submit->toString()}</div>");

}

$content->generalTags("</form>");

$layout->setWidth("900px");

$layout->content=$content->toString();

$layout->showLayout();

}

private function updateUserDetails(){

GLOBAL $db;

if(isset($_POST['update_user'])){

$db->updateQuery(array("user_status={$_POST['status']}","email='{$_POST['update_email']}'","cellphone='{$_POST['update_cellphone']}'"),"users","where id={$_GET['eid']} ");

return System::successText("Changes Updated");

}

}

private function createUser(){

$content=new objectString();

 $content->generalTags(System::backButton("?"));

$content->generalTags(System::contentTitle("User Details"));

$content->generalTags(System::categoryTitle("Basic information"));

$content->generalTags($this->create_user_message);

$form=new form_control;

$content->generalTags($form->formHead());

$input=new input;

$input->setClass("form_input");

$input->input("text","create_firstname",$this->getVals(0));

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Firstname</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_input");

$input->input("text","create_middlename",$this->getVals(1));

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Middlename</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_input");

$input->input("text","create_surname",$this->getVals(2));

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Surname</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_select");

$input->addItem("0","Male");

$input->addItem("1","Female");

$input->setSelected($this->getVals(3));

$input->select("create_gender");

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Gender</strong></div>{$input->toString()}</div>");

$content->generalTags(System::categoryTitle("Account Information","margin-bottom:10px;"));

$input=new input;

$input->setClass("form_input");

$input->input("text","create_username",$this->getVals(4));

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Username</strong></div>{$input->toString()}</div>");

$select=new input;

$select->setClass("form_select");

$select->addItem("0","User");

$select->addItem("1","Administrator");

$select->setSelected($this->getVals(5));

$select->select("create_type");

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>User Type</strong></div>{$select->toString()}</div>");


$input=new input;

$input->setClass("form_input");

$input->input("text","create_email",$this->getVals(6));

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Email</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_input");

$input->input("text","create_tel",$this->getVals(7));

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Tel.</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_input");

$input->input("password","create_password",$this->getVals(8));

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Password</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_input");

$input->input("password","creates_rpassword",$this->getVals(9));

$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Rpt. Password</strong></div>{$input->toString()}</div>");

$submit=new input;

$submit->setId("mId");

$submit->setTagOptions("style=\"float:right;cursor:pointer;margin-right:10px;\"");

$submit->setClass("form_button");

$submit->input("submit","submit_user","Submit");

$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\">{$submit->toString()}</div>");

$content->generalTags("</form>");

return $content->toString();

}

private function myAccount(){

GLOBAL $db;

$cont=new objectString;

$cont->generalTags(System::backButton("?"));

$cont->generalTags(System::contentTitle("Account Details"));

$cont->generalTags(System::categoryTitle("Your Account Details"));

$cont->generalTags($this->updateMyAccount());

$sess=$db->getUserSession();

$form=new form_control();

$cont->generalTags($form->formHead());

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Surname</strong></label></div>".$sess->lastname."</div>");

$input=new input;

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Name</strong></div>".$sess->firstname." ".$sess->secondname."</div>");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>User Type</strong></div>".System::userType($sess->user_type)."</div>");


$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Username</strong></div>{$sess->username}</div>");

$input=new input;

$input->setClass("form_input");

$input->input("text","myac_email",$sess->email_address);

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Email Address</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_input");

$input->input("text","myac_cellphone",$sess->cellphone);

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Cellphone</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setTagOptions("style=\"float:right;margin-right:10px;cursor:pointer;\"");

$input->setClass("form_button_add");

$input->input("Submit","myac_changebtn","Update");

$cont->generalTags("<div id=\"form_row\">{$input->toString()}</div>");

$cont->generalTags("</form>");

$cont->generalTags(System::contentTitle("Change Password"));

$form=new form_control("return validate()");

$script="<script>
var fields=new Array();

var field1=new vField();
field1.fieldId=\"oldpass\";
field1.fieldType=0;
field1.validationType=0;
field1.errorValue='';
field1.failedMessage=\"Empty old password field.\";

var field2=new vField();
field2.fieldId=\"newpass\";
field2.fieldType=0;
field2.validationType=0;
field2.errorValue='';
field2.failedMessage=\"Empty new password field.\";

var field3=new vField();
field3.fieldId=\"cpass\";
field3.fieldType=0;
field3.validationType=0;
field3.errorValue='';
field3.failedMessage=\"Empty confirm password field.\";


fields[0]=field1;

fields.push(field2);

fields.push(field3);
function validate(){

status=false;


status= validateFields(fields);

if(!matchFields('cpass','newpass','ind',true)){

 return false;

}

return status;

}
</script>
";

$cont->generalTags($script);

$cont->generalTags($form->formHead());

$input=new input;

$input->setClass("form_input");

$input->setId("oldpass");

$input->input("password","pass_oldPassword");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Curr. password</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_input");

$input->setId("newpass");

$input->input("password","pass_newPassword");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>New password</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_input");

$input->setId("cpass");

$input->setTagOptions("onkeyup=\"matchFields('cpass','newpass','ind')\" ");

$input->input("password","pass_confirmPassword");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Conf. password</strong></div>{$input->toString()}<div id=\"ind\"></div></div>");

$input=new input;

$input->setTagOptions("style=\"float:right;margin-right:10px;cursor:pointer;\"");

$input->setId("cpass");

$input->setClass("form_button");

$input->input("submit","update_password","Change");

$cont->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\">{$input->toString()}</div>");

$cont->generalTags("</form>");

return $cont->toString();

}

public function updateMyAccount(){

GLOBAL $db;

$sess=$db->getUserSession();

if(isset($_POST['myac_changebtn'])){

$db->updateQuery(array("email='{$_POST['myac_email']}'","cellphone='{$_POST['myac_cellphone']}'"),"users","where id={$sess->id}");

$sess->email_address=$_POST['myac_email'];

$sess->cellphone=$_POST['myac_cellphone'];

$db->updateUserSession($sess);

return System::successText("Details Updated");

}

if(isset($_POST['update_password'])){

$res=$db->selectQuery(array("password"),"users","where id={$sess->id}");

$password="";

while($row=mysqli_fetch_row($res)){

$password=$row[0];

}

if(($_POST['pass_newPassword']!="")&&($_POST['pass_oldPassword']!="")&&($_POST['pass_confirmPassword']!="")&&($db->hashPassword($_POST['pass_oldPassword'])==$password)){

 $db->updatePassword($_POST['pass_newPassword'],$sess->id);
 
 return System::successText("Password changed");

}else{ return System::getWarningText("Password Mismatch"); }

}

}

public function createAction(){

GLOBAL $db;

$msg="";

$checknext=true;

$UiLabels=array("First Name","Middle Name","Surname","Gender","Username","User Type","Email","Tel","Password","Repeat password");

$this->previous_vals=array();

 if(isset($_POST['submit_user'])){

  $items=System::getPostedItems("create");
  
  for($i=0;$i<count($items);$i++){
  
   $this->previous_vals[]=$items[$i]->value;

    if(($items[$i]->value=="")&&($checknext)){
	
	 $msg=System::getWarningText("Required field:{$UiLabels[$i]}");
	
	 $checknext=false;
	
	}  
  
  }
  
  if($checknext){
  
  $available=true;
  
  $res=$db->selectQuery(array("id"),"users","where username='{$_POST['create_username']}'");
  
  while($rw=mysqli_fetch_row($res)){
  
	$available=false;
	
  }  
  
  if($available){
  
     if($_POST['create_password'] == $_POST['creates_rpassword']){
	     
		 $udet=new User_Session;	 
         $udet->username=$this->getVals(4);
         $udet->id=$this->getVals(0);
         $udet->parent_account=$this->getVals(1);
         $udet->user_type=$this->getVals(5);
         //$udet->user_status=$this->getVals(5);
         $udet->parent_id=$this->getVals(3);
         $udet->firstname=$this->getVals(0);
         $udet->secondname=$this->getVals(1);
         $udet->lastname=$this->getVals(2);
         $udet->cellphone=$this->getVals(7);
		 $udet->gender=$this->getVals(3);
         $udet->email_address=$this->getVals(6);
		 
		 $db->createUser($udet,$this->getVals(8));
		 
		 $this->previous_vals=array();
		 
		 System::successText("User Created.");
	 
	 }else{
	 
	  System::getWarningText("Password Mismatch.");
	 
	 }
  
  }else{
  
   return System::getWarningText("Username not available.");
  
  }
  
  return System::successText("User Created");
  
  }else{
   return $msg;
  }

 }

}

private function getVals($index){

if(isset($this->previous_vals[$index])){

   return $this->previous_vals[$index];

}
return "";

}
private function updateStatus(){
GLOBAL $db;

if(isset($_POST['enable_user'])|isset($_POST['disable_user'])){

$items=System::getPostedItems("chk");

$arr=array();

for($i=0;$i<count($items);$i++){

$arr[]=$items[$i]->value;

}

if(count($arr)>0){

if(!isset($_POST['disable_user'])){

$umanager=new Manage_User;

$umanager->removeSessionBlockers($arr);

$string_values=implode(" or id=",$arr);

$db->updateQuery(array("user_status=1"),"users","where id=".$string_values);

return System::successText("User enabled!");
}else{

 $umanager=new Manage_User;

 $umanager->createSessionBlockers($arr);

 $string_values=implode(" or id=",$arr);

  $db->updateQuery(array("user_status=0"),"users","where id=".$string_values);


 return System::successText("User disabled!");


}
}

}

}
private function setActiveTab(){

if(isset($_POST['myac_changebtn'])|(isset($_POST['update_password']))){

  return 2;

}
if(isset($_POST['submit_user'])){

  return 1;

}
return 0;

}

}
?>