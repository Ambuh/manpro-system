<?php
function systemaccess(){

return new mysystems();

}
class mysystems{

public $profiler;
public $company;

 public function __construct(){
 
   $this->profiler=System::shared("profiler");
   
   $this->company=System::shared("companyinterface");

 } 
 public function requestPage($user,$forrecruit=true){
 
  $content= new objectString;
  
  $content->generalTags(System::categoryTitle("Enter Request details.","margin-bottom:5px;"));
  
  $form=new form_control;
  
  $content->generalTags($form->formHead());
  
  $systemtypes =new input;
  
  $systemtypes->setClass("form_select");
  
  $systemtypes->addItem("0","Please Select");
  
  $systemtypes->addItems($this->getSystemTypes());
  
  $systemtypes->select("system_type");
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>System</strong></div>{$this->systemCheckBoxes($this->getSystemTypes())}</div>");
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Comment</strong></div><textarea class=\"form_input\" name=\"system_details\" style=\"font-size:12px;\" ></textarea></div>");
  
  if($forrecruit){
  
   $content->generalTags("<input type=\"hidden\" name=\"system_userid\" value=\"{$user->id}\" />");
  
   $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Person</strong></div>{$user->firstname} {$user->firstname}</div>");
   
   $content->generalTags(System::categoryTitle("Profile Summary"));
   
   $content->generalTags($this->profiler->basicUserInfo(false,$user));
  
  }else{
  
  $content->generalTags("<input type=\"hidden\" name=\"system_userid\" value=\"{$user->id}\" />");
  
  }
  
  $content->generalTags("<input type=\"hidden\" name=\"POST_ID\" value=\"".time()."\" />");
  
  $input=new input;
  
  $input->setClass("form_button");
   
  $input->setTagOptions("Style=\"float:right;\"");
  
  $input->input("submit","save_request","Submit"); 
   
  $content->generalTags("<div id=\"form_row\">{$input->toString()}</div>");
  
  $content->generalTags("</form>");
  
  return $content->toString();
 
 } 
 public function equipmentPage($user_id=0){
  
  GLOBAL $db;
  
  $content=new objectString();
  
  $content->generalTags(System::categoryTitle("User Details","margin-bottom:5px;"));
  
  $select=new input;
  
  $select->setClass("form_select");
  
  $select->addItem("0","Select User");
  
  $select->addItems($this->company->getEmployees("",true));
  
  $select->setSelected($user_id);
  
  $select->enableAjax(true);
  
  $select->showAjaxProgress();
  
  $select->setId("sel_user");
  
  $select->select("sel_user",System::generateAjaxParams("user_prof","macro_systemaccess","par="));
  
  $content->generalTags("<div id=\"form_row\">");
  
  $content->generalTags("<div style=\"width:200px;overflow:hidden;float:left;\">");
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Employee</strong></div>{$select->toString()}</div>");
  
  $content->generalTags("</div>");
  
  $content->generalTags("<div id=\"user_prof\" style=\"width:520px;height:300px;overflow:hidden;float:left;border-left:1px solid #eee;\">");
  
  $user=$db->getUserDetails(" where id=".$user_id);
  
  $noprev="No Preview";
  
  for($i=0;$i<count($user);$i++){
  
   $content->generalTags($this->profiler->basicUserInfo(false,$user[$i]));
  
  }
  $content->generalTags("<div style=\"margin-left:10px;font-style:italic;\">".$noprev."</div>");
  
  $content->generalTags("</div>");
  
  $content->generalTags("</div>");
  
  $content->generalTags(System::contentTitle("Equipment","margin-bottom:10px;"));
  
  $content->generalTags($this->systemCheckBoxes($this->getEquipment(),true,"600px"));
  
  $submit=new input;
  
  $content->generalTags("<input type=\"hidden\" name=\"POST_ID\" value=\"".time()."\">");
  
  $submit->setClass("form_button");
  
  $submit->setTagOptions("style=\"float:right;\"");
  
  $submit->input("submit","assign_equip","Assign");
  
  
  $content->generalTags("<div id=\"form_row\" style=\"margin-bottom:0px;\">{$submit->toString()}</div>");
  
  return $content->toString();
  
 }
 public function getUserProfile($user_id){
 GLOBAL $db;
 
 $user=$db->getUserDetails("where id=".$user_id);
 $no="<i>No Preview</i>";
 for($i=0;$i<count($user);$i++){
 return $this->profiler->basicUserInfo(false,$user[$i]);
 }
 return $no;
 }
 public function getSystemTypes($added=array()){
 
  GLOBAL $db;
 
 $systems=array();
 
 $merged=array_merge(count($added)==0 ? $added:System::nameValueToSimpleArray($added,true),array(-1,-2));

 
 if(in_array(-1,$merged))
 $systems[]=new name_value("Email","-1");
 
 if(in_array(-2,$merged))
 $systems[]=new name_value("Intranet","-2");
 
  $results=$db->selectQuery(array("id","system_name"),"systems","where company_id=".PARENT." or id=".implode(" or id=",$merged));
  
  while($row=mysqli_fetch_row($results)){
  
  $systems[]=new name_value($row[1],$row[0]);
   
  }
 
 return $systems;
 
 }
 private function systemCheckBoxes($data,$show_checkbox=true,$width="300px"){
 
  $content=new objectString();
  
  $content->generalTags("<div style=\"width:$width;float:left;overflow:hidden;padding:0px 5px 5px 5px;\">");
  $c=0;
  
  for($i=0;$i<count($data);$i++){
  
  $tags=$tags=$show_checkbox ? "<input type=\"checkbox\" value=\"{$data[$i]->value}\" name=\"chk$i\"/>" : ("");
  
  $content->generalTags("<div id=\"mini_cont\">$tags<div id=\"label\">".$data[$i]->name."</div></div>");
  
  }
  
  $content->generalTags("</div>");
  
  return $content->toString();
 
 }
 public function getRequestDetails($request_id,$allowresp=false){
 
  GLOBAL $db;
 
  $content=new objectString;
 
  $request=$this->getSystemAccessRequests("and id=".$request_id);
  
  for($b=0;$b<count($request);$b++){
 
  $system_types=System::nameValueToSimpleArray(System::swapNameValue($this->getSystemTypes(unserialize($request[$b]->request_systemType))));
 
   $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Access to</strong></div>".$this->systemCheckboxes(System::matchArrayValues(unserialize($request[$b]->request_systemType),$system_types),false)."</div>");
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Comment</strong></div><div style=\"width:300px;float:left;\">".$request[$b]->request_reason."</div></div>");
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Status</strong></div>".$this->getStatusText($request[$b]->request_status)."</div>");
  
  if($request[$b]->request_status==1){
	  
  $content->generalTags(System::categoryTitle("Response","margin-bottom:5px;"));
	  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Response Date</strong></div>".$request[$b]->request_responseDate."</div>");

  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Comment</strong></div><div style=\"width:300px;float:left;\">".$request[$b]->request_responseComment."</div></div>");
	  
  }
  
  
  if($allowresp && $request[$b]->request_status==0){
	  
  $content->generalTags(System::categoryTitle("Response","margin-bottom:5px;"));
  
  $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Details</strong></div><textarea class=\"form_input\" style=\"width:300px;font-size:12px;\" name=\"response\"></textarea></div>");
  
  $input=new input;
  
  $input->setClass("form_button");
  
  $input->setTagOptions("Style=\"float:right;\"");
  
  $input->input("submit","sub_response","Respond");
  
  $content->generalTags("<div id=\"form_row\">{$input->toString()}</div>");
  
  }
  
  
  
  $content->generalTags(System::contentTitle("Request by"));
  
  $this_user=$db->getUserDetails("where id=".$request[$b]->request_by);
  
  for($i=0;$i<count($this_user);$i++){
  
  $content->generalTags($this->profiler->basicUserInfo(false,$this_user[$i]));
  
  }
  
  }
  return  $content->toString();
 
 }
 public function getStatusText($status){
   $state="<div id=\"pend\">Pendig</div>";
   
   if($status==1)
   $state="<div id=\"appr\">Done</div>";
   
   return $state;
   
 }
 public function assignEquipment(){
 
 GLOBAL $db;
 
 $equip=System::nameValueToSimpleArray(System::getPostedItems("chk"));
 if($_POST['sel_user']==0)
 return System::getWarningText("Please select employee.");
 
 if(count($equip)<1)
 return System::getWarningText("Select equipment");
  $prev=array();
 $res=$db->selectQuery(array("equipment"),"employee_details","where employee_id=".$_POST['sel_user']);
 while($row=mysqli_fetch_row($res)){
	 if($row[0]!="")
	 $prev=unserialize($row[0]);
 }
$arr=array_unique(array_merge($equip,$prev));
 
 $db->updateQuery(array("equipment='".serialize($arr)."'"),"employee_details","where employee_id=".$_POST['sel_user']);
 
 return System::successText("Equipment assigned successfully");
 
 }
 public function addSystem($system_name){
 GLOBAL $db;
  
 if($system_name=="")
 return System::getWarningText("Please enter system type.");
	  
 if(!in_array($system_name,System::nameValueToSimpleArray(System::swapNameValue($this->getSystemTypes())))){//get existing system
  
  $db->insertQuery(array("system_name","company_id"),"systems",array("'".$system_name."'",PARENT));
  
  return System::successText("System type saved successfully");
 
 }else{
 return System::getWarningText("Failed!.System type already exists.");
 }
 
 }
 public function addEquipment($equipment_name){
 
 GLOBAL $db;
 
 if($equipment_name=="")
 return System::getWarningText("Please enter equipment type.");
 
 if (!in_array($equipment_name,System::nameValueToSimpleArray(System::swapNameValue($this->getEquipment())))){
 
 $db->insertQuery(array("equipment_title","company_id"),"equipment",array("'".$equipment_name."'",PARENT));
 
 return System::successText("Equipment type saved successfully");
 
 }else{
   return System::getWarningText("Failed! Equipment type already exists.");
 }
 
 
 }
 public function respondToRequest($id,$comment,$action_link="?"){
 
 GLOBAL $db;
 
 if($comment=="")
 return System::getWarningText("Please enter response ");
 
 $db->updateQuery(array("response_comments='$comment'","response_date=CURRENT_DATE()","status=1"),"access_requests","where id=".$id);
 
 $res=$db->selectQuery(array("request_by"),"access_requests","where id=".$id);
 
 while($row=mysqli_fetch_row($res)){
 
   $mess=new system_messages;
   
   $mess->message_title="System Access Request Status";
   $mess->message_actionLink=$action_link; 
   $mess->message_targetPerson=$row[0];
   $mess->postMessage();
   
 }
 
 return System::successText("Response sent successfully");
 
 }
 public function postAccessRequest($userid,$reason,$action_link){
 GLOBAL $db;
 
  $systems=System::getPostedItems("chk");
 
 
  if(count($systems)==0)
  return System::getWarningText("Please select the system you want access to.");
 
  $db->insertQuery(array("company_id","System_type","request_by","request_reason","requestdate"),"access_requests",array(PARENT,"'".serialize($systems)."'",$userid,"'$reason'","CURRENT_DATE()"));
  
  $employees=$this->company->getEmployees(" and employee_position=-1 and department=-1");
  for($i=0;$i<count($employees);$i++){
$mess=new system_messages;
   $mess->message_title="System Access Request";
   $mess->message_actionLink=$action_link; 
   $mess->message_targetPerson=$employees[$i]->employee_id;
   $mess->postMessage();
  }
  return System::successText("Request posted successfully!");
 
 }
 public function getEquipment(){
 
  GLOBAL $db;
 
  $array=array();
 
  $array[]=new name_value("Laptop","-1");
  
  $array[]=new name_value("Mobile Phone","-2");
  
  $array[]=new name_value("Printer","-3");
 
  $res=$db->selectQuery(array("*"),"equipment","where company_id=".PARENT);
  
  while($row=mysqli_fetch_row($res)){
  
   $array[]=new name_value($row[1],$row[0]);
  
  }
  
  return $array;
   
 }
 public function getSystemAccessRequests($whereclause=""){
 GLOBAL $db;
 
 $results=$db->selectQuery(array("id","system_type","status","request_reason","response_comments","DATE_FORMAT(requestdate,'%d-%b-%Y')","DATE_FORMAT(response_date,'%d %b-%Y')","request_by","branch","department"),"access_requests"," where company_id=".PARENT." ".$whereclause." order by id desc");
 
 $requests=array();
 
 while($row=mysqli_fetch_row($results)){
 
 $req=new AccessRequest; 
 $req->request_id=$row[0];
 $req->request_systemType =$row[1];
 $req->request_status=$row[2];
 $req->request_reason=$row[3];
 $req->request_responseComment=$row[4];
 $req->request_date=$row[5];
 $req->request_responseDate=$row[6];
 $req->request_by=$row[7];
 $req->request_department=$row[8];
 $req->request_branch=$row[9];
 
 $requests[]=$req;
 
 }
 return $requests;
 }
 
}
class AccessRequest{
public $request_id;
public $request_systemType;
public $request_systemTypeText;
public $request_status;
public $request_reason;
public $request_responseComment;
public $request_date;
public $request_responseDate;
public $request_by;
public $request_department;
public $request_branch;
}
?>