<?php

function profiler(){

return new profiler;

}
class profiler{

public function basicUserInfo($hideTitle=false,$user,$image_url="",$editable=false,$mini=false){
	
GLOBAL $db;

 $content=new objectString;
 
 $content->generalTags("<div style=\"float:left;width:100%;padding-bottom:10px;\">");
 
 $form=new form_control;
 
 $select=new input;
  
 $select->setClass("form_button_add");
 
 $select->setTagOptions("style=\"float:right;backgrond:none;\"");
 
 $me=$db->getUserSession();
 
 $id;
  
  if(!isset($_GET['mid'])){
     $item=$db->getDefaultMenuItem();
	 $id=$item->item_id;
  }else{
     $id=$_GET['mid'];
  }
  
 $select->input("submit","edit_details","Edit Profile");
 
  if($me->id==$user->id){
  if($editable)
  $content->generalTags("<div id=\"form_row\" style=\"padding:0px;margin:0px;\"><div style=\"font-weight:normal;float:right;background:none;text-decoration:underline;margin-right:10px;\" ><a href=\"?mid=$id&prof=1\" style=\"color:#444!important;\">Edit Profile</a></div></div>");
  
  }
 
 $content->generalTags("<div class=\"user_tab\">");
 
 $employment_dets=$this->getEmployeeDetails($user->id); 
 
 $company=System::shared("companyinterface");

  if($user->profile_image==""){ 
 
  $content->generalTags("<div id=\"prof_image\"><img src=\"../images/profile/default{$user->gender}.png\" width=\"120px\" height=\"120px\"/></div>");

 }else{
 
  $content->generalTags("<div id=\"prof_image\"><img src=\"../images/profile/{$user->profile_image}\" width=\"120px\" height=\"120px\" /></div>");
 
 }

 $content->generalTags(System::categoryTitle("Basic Information","width:98%;"));
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Name</strong></div>{$user->firstname} {$user->secondname} {$user->lastname}</div>");
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Gender</strong></div>".System::genderTypeText($user->gender)."</div>");
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>User Type</strong></div>".System::userType($user->user_type)."</div>");
 
 //$content->generalTags(System::categoryTitle("Gender:","width:98%;"));

 $content->generalTags("</div>");
 
  $content->generalTags("<div class=\"user_tab\" style=\"width:47%;border:none;\">");
  
  if(!$hideTitle){
  
    $content->generalTags(System::categoryTitle("Employee's Information","width:98%;"));
  
  }
  
  if($me->id==$user->id){
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Date of birth</strong></div>{$employment_dets->employee_dob}</div>");
  
  }
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Emplyee No.</strong></div>{$employment_dets->employee_no}</div>");
  
  $position=$company->getPosition($employment_dets->employee_position);
  
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Position</strong></div>{$position->position_name}</div>");
  
  $dep=$company->getDepartment($employment_dets->employee_department);
  
 
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Department</strong></div>$dep->department_name</div>");
  
  
 $bran=$company->getBranch($employment_dets->employee_branch);
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Branch</strong></div>{$bran->branch_name}</div>");
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Email</strong></div>{$user->email_address}</div>");
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Cellphone</strong></div>{$user->cellphone}</div>");
    
    
  $content->generalTags("</div>");
  
  //--------------------
  
  
  $content->generalTags("</div>");
  
  
  
 return $content->toString();

}
public function miniProfile($user){
GLOBAL $db;

 $content=new objectString;
 
 $content->generalTags("<div style=\"float:left;width:100%;\">");
 
 $form=new form_control;
 
 $employment_dets=$this->getEmployeeDetails($user->id); 
 
 $company=System::shared("companyinterface");

  if($user->profile_image==""){ 
 
  $content->generalTags("<div id=\"prof_image2\" style=\"width:70px:height:70px;margin-top:5px;\"><img src=\"../images/profile/default{$user->gender}.png\" width=\"70px\" height=\"70px\"/></div>");

 }else{
 
  $content->generalTags("<div id=\"prof_image2\" style=\"width:70px:height:70px;margin-top:5px;\"><img src=\"../images/profile/{$user->profile_image}\" width=\"70px\" height=\"70px\" /></div>");
 
 }
 
 $content->generalTags("<div style=\"width:195px;float:left;\">");
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\" style=\"width:85px;\"><strong>Emplyee No.</strong></div>{$employment_dets->employee_no}</div>");
  
  $position=$company->getPosition($employment_dets->employee_position);
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\" style=\"width:85px;\"><strong>Position</strong></div>{$position->position_name}</div>");
  
  $dep=$company->getDepartment($employment_dets->employee_department);
  
 $content->generalTags("<div id=\"form_row\"><div id=\"label\" style=\"width:85px;\"><strong>Department</strong></div>$dep->department_name</div>");
 
 $content->generalTags("</div>");
 
return $content->toString();

}
public function editMyInfo($hasimage=false,$user,$image_url=""){
 
 GLOBAL $db;
 
 $content=new objectString;
 
 $form=new form_control;
 
 $form->enableUpload();
  
 System::enableDatePicker();
  
 $content->generalTags($form->formHead());
 
 $content->generalTags("<div class=\"user_tab\" style=\"height:195px;\">");
 
 if($user->profile_image==""){ 
 
  $content->generalTags("<div id=\"prof_image\"><img src=\"../images/profile/default{$user->gender}.png\" width=\"120px\" height=\"120px\" /></div>");

 }else{
 
  $content->generalTags("<div id=\"prof_image\"><img src=\"../images/profile/{$user->profile_image}\" width=\"120px\" height=\"120px\" /></div>");
 
 }
 
 $input=new input;
 
 $input->input("file","edit_image");
 
 $content->generalTags("<div id=\"form_row\" style=\"margin-bottom:0px;\"><div id=\"label\"><strong>Image File</strong></div></div>");
 
 $upload=new input;
 
 $employment_dets=$this->getEmployeeDetails($user->id); 
 
 $upload->setClass("form_button_add");
 
 $upload->input("submit","upload_image","Upload Image");
 
 $content->generalTags("<div id=\"form_row\" style=\"padding-left:6px;\">{$input->toString()}{$upload->toString()}</div>");
 
 $content->generalTags("</div>");
  
 $content->generalTags("<div class=\"user_tab\" style=\"height:195px;width:47%;border:none;\">");
 
 $content->generalTags(System::categoryTitle("Profile Summary"));
 
 $content->generalTags("<div id=\"form_row\" ><div id=\"label\"><strong>Username</strong></div>{$user->username}</div>");
 
 $content->generalTags("<div id=\"form_row\" ><div id=\"label\"><strong>First Name</strong></div>{$user->firstname}</div>");
 
 $content->generalTags("<div id=\"form_row\" ><div id=\"label\"><strong>Last Name</strong></div>{$user->lastname}</div>");
 
 $dob=new input;
 
 $dob->setClass("form_input");
 
 $dob->setId("dob");
 
 $dob->makeDatePicker("dd/mm/yyyy");
 
 $dob->input("text","edit_dob",$employment_dets->employee_dob);
 
 //$update_btn=new input;
 
 //$update_btn->setClass("form_button_add");
 
 //$update_btn->setTagOptions("style=\"float:right;margin:0px;margim-right:3px;\"");
 
 //$update_btn->input("submit","update_dob","Update");
 
 $content->generalTags("<div id=\"form_row\" ><div id=\"label\"><strong>Date Of Birth</strong></div>{$employment_dets->employee_dob}</div>");
 
 $content->generalTags("</div>");
 
 $content->generalTags(System::categoryTitle("Edit details"));
 
 $content->generalTags("<div class=\"user_tab\" style=\"height:110px;margin-top:3px;\" >");
 
 $email=new input;
 
 $email->setClass("form_input");
 
 $email->input("text","update_email",$user->email_address);
 
 $content->generalTags("<div id=\"form_row\" ><div id=\"label\"><strong>Email</strong></div>{$email->toString()}</div>");
 
 $phone=new input;
 
 $phone->setClass("form_input");
 
  $phone->input("text","update_phone",$user->cellphone);
 
 $content->generalTags("<div id=\"form_row\" ><div id=\"label\"><strong>Phone</strong></div>{$phone->toString()}</div>");
 
 $input=new input;
 
 $input->setClass("form_button_add");
 
 $input->setTagOptions("style=\"float:right;margin-right:10px;\"");
 
 $input->input("submit","update_detail","Update");
 
 $content->generalTags("<div id=\"form_row\" >{$input->toString()}</div>");
 
 $content->generalTags("</div>");
  
 $content->generalTags("<div class=\"user_tab\" style=\"height:110px;margin-top:3px;width:47%;border:none;\" >");
 
 $password=new input;
 
 $password->setClass("form_input");
 
 $password->input("password","old_password");
 
 $content->generalTags("<div id=\"form_row\" ><div id=\"label\"><strong>Password</strong></div>{$password->toString()}</div>");
  
 $password=new input;
 
 $password->setClass("form_input");
 
 $password->input("password","change_password");
 
 $content->generalTags("<div id=\"form_row\" ><div id=\"label\"><strong>Password</strong></div>{$password->toString()}</div>");
 
 $password=new input;
 
 $password->setClass("form_input");
 
 $password->input("password","change_password2");
 
 
 $chpass=new input;
 
 $chpass->setClass("form_button_add");
 
 $chpass->setTagOptions("style=\"float:right;\"");
 
 $chpass->input("submit","change_pass","Change");
 
 $content->generalTags("<div id=\"form_row\" ><div id=\"label\"><strong>Rpt. Password</strong></div>{$password->toString()}{$chpass->toString()}</div>");
 
 
 $content->generalTags("</div>");
  
 $content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");
 
 $content->generalTags("</div>");
 
return $content->toString();

}
public function getMessages($company_id,$recipient_id){
GLOBAL $db;

$results=array();

$resource=$db->selectQuery(array("id","company_id","message_title","message_content","recipient","isread","msender","sender_id","message_date"),"messages","where company_id=$company_id and recipient=$recipient_id");

while($row=mysqli_fetch_row($resource)){
 $mess=new messages; 

 $mess->message_id=$row[0];
 $mess->message_companyId=$row[1];
 $mess->message_title=$row[2];
 $mess->message_content=$row[3];
 $mess->message_recepientId=$row[4];
 $mess->message_isRead=$row[5];
 $mess->message_sender=$row[6];
 $mess->message_senderId=$row[7];
 $mess->message_date=$row[8];
 
 $results[]=$mess;
 
}

return $results;

}
public function getMessage($id){

GLOBAL $db;

$resource=$db->selectQuery(array("id","company_id","message_title","message_content","recipient","isread","msender","sender_id","message_date"),"messages","where id=$id");

while($row=mysqli_fetch_row($resource)){
 $mess=new messages; 

 $mess->message_id=$row[0];
 $mess->message_companyId=$row[1];
 $mess->message_title=$row[2];
 $mess->message_content=$row[3];
 $mess->message_recepientId=$row[4];
 $mess->message_isRead=$row[5];
 $mess->message_sender=$row[6];
 $mess->message_senderId=$row[7];
 $mess->message_date=$row[8];
 
 return $mess;
 
}

 return NULL;

}
public function isRead($status){
$results="Unread";

if($status){

$results="Read";

}

return $results;

}
public function updateProfile(){

GLOBAL $db;

$user=$db->getUserSession();

if(isset($_POST['upload_image'])){

 if($_FILES['edit_image']['name']!=""){
 
   $user=$db->getUserSession();

   move_uploaded_file($_FILES['edit_image']['tmp_name'],ROOT."images/profile/{$user->id}_{$_FILES['edit_image']['name']}");
   
   if($user->profile_image!=""){
   
     if(file_exists(ROOT."images/profile/$user->profile_image")){
   
       unlink(ROOT."images/profile/$user->profile_image");
   
     }
   
   }
   
   $db->updateQuery(array("image_url='".$user->id."_".$_FILES['edit_image']['name']."'"),"users","where id=$user->id");
   
   $user->profile_image=$user->id."_".$_FILES['edit_image']['name'];

   $db->updateUserSession($user);
   
   return System::successText("Image changed");

 }

}
if(isset($_POST['update_detail'])){
	
	
  $dets= $db->getUserDetails(" where email='{$_POST['update_email']}' and id<>{$user->id}");

  for($i=0;$i<count($dets);$i++)
  return System::getWarningText("Update failed:Email address '{$_POST['update_email']}' already in use.");

  $db->updateQuery(array("email='{$_POST['update_email']}'","cellphone='{$_POST['update_phone']}'"),"users"," where id={$user->id}");
  
  $user->email_address=$_POST['update_email'];
  
  $user->cellphone=$_POST['update_phone'];

  $db->updateUserSession($user);

 return System::successText("Record updated");

}

if(isset($_POST['change_pass'])){

 if(($_POST['change_password']=="")|($_POST['change_password2']=="")){

  return System::getWarningText("Password Mismatch");
  
  }else{
  
  $res=$db->selectQuery(array("password"),"users","where id=$user->id");
  
  $oldpass="";
  
  while($row=mysqli_fetch_row($res)){
    $oldpass=$row[0];  
  }  
  
  if($db->hashPassword($_POST['old_password'])==$oldpass){
  
  $db->updateQuery(array("password='".$db->hashPassword($_POST['change_password'])."'"),"users","where id={$user->id}");
  
  return System::successText("Password changed");
  
  }else{
  
  return System::getWarningText("Old Password Mismatch");
  
  }
  
  }

}
if(isset($_POST['update_dob'])){

$db->updateQuery(array("dob='".$this->dateOf($_POST['edit_dob'])."'"),'employee_details',"where employee_id={$user->id}");

 return System::successText("Details updated.");

}
}
public function getDepartmentUserIds($department_id,$branch){

  GLOBAL $db;
  
  $array=array();
  
  $res=$db->selectQuery(array("employee_id"),"employee_details","where department=$department_id and branch=$branch");
  
  while($row=mysqli_fetch_row($res)){
  
    $array[]=$row[0];
  
  }
 
 return $array;
 
}
public function getDepartmentUsers($branch,$department,$addwhere=""){

 GLOBAL $db;
 
 
 if(($department=="")||($branch=="")){
 
 $department=0;
 
 $branch=0;
  
 }
 
 $ids=$this->getDepartmentUserIds($department,$branch);
 
 $inner_add="";
 
 //if(count($ids)<=1){
 
   $inner_add=$addwhere;
 
 //}
 
  if(count($ids)==0){
  
   return NULL;
  
  }

 return $db->getUserdetails("where id=".implode(" $addwhere or id=",$ids).$inner_add);
 
 
}
public function getEmployeeDetails($id){
GLOBAL $db;

$employment_details=new employee_details;

$resource=$db->selectQuery(array("id","employee_id","employee_position","access_links","company_id","department","branch","employment_no","DATE_FORMAT(dob,'%d/%m/%Y')","idpassport"),"employee_details","where employee_id='$id' ");

while($row=mysqli_fetch_row($resource)){

 $employment_details->id=$row[0];
 $employment_details->employee_id=$row[1];
 $employment_details->employee_no=$row[7];
 $employment_details->employee_position=$row[2];
 $employment_details->employee_links=$row[3];
 $employment_details->employee_companyId=$row[4];
 $employment_details->employee_department=$row[5];
 $employment_details->employee_branch=$row[6];
 $employment_details->employee_dob=$row[8];
 $employment_details->employee_idPassport=$row[9];
 $employment_details->employee_hasDetails=true;
 
}

return $employment_details;

}
private function dateOf($date){

if($date==""){

  return "0000-00-00";

}

$arr=explode("/",$date);

return $arr[2]."-".$arr[1]."-".$arr[0];

}
}
class messages{
 public $message_id;
 public $message_companyId;
 public $message_title;
 public $message_content;
 public $message_recepientId;
 public $message_isRead;
 public $message_sender;
 public $message_senderId;
 public $message_date;
}
class employee_details{
 public $id;
 public $employee_id;
 public $employee_no;
 public $employee_position;
 public $employee_links;
 public $employee_companyId;
 public $employee_department;
 public $employee_dob;
 public $employee_branch;
 public $employee_hasDetails=false;
 public $employee_idPassport;
}

?>