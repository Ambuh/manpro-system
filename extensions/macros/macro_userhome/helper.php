<?php
class helper{

private $profiler;

private $company;

private $Me;

private $db;

public function __construct(){
 
 GLOBAL $db;

 $this->profiler=System::shared("profiler");

 $this->company=System::shared("companyinterface");
 
 $this->Me=$this->company->getMyEmploymentInfo();
 
 
   /*$this->Me->employee_id=$me->id;
 
    $this->Me->employee_position=-2;
	
	$this->Me->employee_department=-2;
	
	//echo $this->Me->employee_position;
	
	
	
	$_SESSION[System::getSessionPrefix()."company_user"]=serialize($this->Me);*/
 
 
 if(!$this->Me->employee_hasDetails){
 
   $me=$db->getUserSession();
 
    $this->Me->employee_id=$me->id;
 
    $this->Me->employee_branch=-1;
 
    $this->Me->employee_position=-2;
	
	$this->Me->employee_department=-2;
 
   $_SESSION[System::getSessionPrefix()."company_user"]=serialize($this->Me);
   
   $user=$db->selectQuery(array("employee_id"),"employee_details","where employee_id=".$me->id);
   
   $exists=false;
   
   while($row=mysqli_fetch_row($user))
   $exists=true;
   
   if(!$exists)    
   $db->insertQuery(array("employee_id","employee_position","department","branch"),"employee_details",array($me->id,-2,-2,-1));  

 }

} 
public function mainPage(){

GLOBAL $db;

$content=new objectString;
 $param=new ajaxparameter;
 
 $param->response_target="messcont";
 $param->ajax_id=0;
 $param->response_function="document.getElementById('messcont').innerHTML=xmlhttp.responseText";
 $param->response_type=OPTION_MACRO;
 $param->response_object="macro_userhome";
 $param->ajax_parameter="";
 $param->ajax_event="onclick";

?>
<div style="float:left;width:100%;margin-top:5px;margin-bottom:5px;">
<?php
  $layout=new macro_layout;
  
  $content->generalTags(System::contentTitle("My Profile"));
  
  $user=$db->getUserSession();
  
  $content->generalTags($this->profileSummary(false,$user));
  
  /*$content->generalTags(System::categoryTitle("Message board."));
  
  $list=new list_control;
  
  $list->setColumnNames(array("Date","Message","Status"));
  
  $list->setColumnSizes(array("100px","150px","60px"));
  
  $list->setHeaderFontBold();
  
  $list->setAlternateColor("#cbe7f8");
    
  $messages=$this->profiler->getMessages($user->parent_id,$user->id);
  
   for($i=0;$i<count($messages);$i++){
 
  $ajaxdiv=new input;
  
  $ajaxdiv->ajaxDiv("id$i",$messages[$i]->message_title."<div id=\"hid\">{$messages[$i]->message_id}</div>",$param);
  
   $list->addItem(array($messages[$i]->message_date,$ajaxdiv->toString(),$this->profiler->isRead($messages[$i]->message_isRead)));
  
  }
  
  $list->setSize("362px","100px");
  
  $list->showList(false);
  
  $content->generalTags("<div class=\"mes_wrap\" style=\"border:none;\">{$list->toString()}</div>");
  
  $content->generalTags("<div class=\"mes_wrap\" id=\"messcont\" style=\"width:48%;margin-top:3px;\"></div>");
  */
  $content->generalTags(System::categoryTitle("Quick Launch","margin-bottom:5px;"));
  
  $items=$db->getMenuItems(1);
  
  $content->generalTags("<div id=\"apps\" style=\"height:320px;border:1px solid #eee;\">");
    
  $arr=array();
  
  $links=array();
  
  for($i=0;$i<count($items);$i++){
  
   if($items[$i]->item_isdefault==0){
   
     $arr[]=$items[$i]->item_macroId;
	 
	 $links[$items[$i]->item_macroId]=$items[$i]->item_link;
	 
	 if($items[$i]->item_hasChild){
	 
	    for($b=0;$b<count($items[$i]->item_children);$b++){
		
		 if(!isset($links[$items[$i]->item_children[$b]->item_macroId])){
		 
           $arr[]=$items[$i]->item_children[$b]->item_macroId;
	 
	       $links[$items[$i]->item_children[$b]->item_macroId]=$items[$i]->item_children[$b]->item_link;
		   
		 }
		}
	 
	 }
  
   }
  
  }
  
  $str=implode(" or id=",$arr);
  
  $macros=$db->getMacros("where id=".$str);
  
  $c=0;
  
  for($i=0;$i<count($macros);$i++){
  
  if($c==0)
   $content->generalTags("<div id=\"form_row\">");
  
    $content->generalTags("<div id=\"app_icon\"><a href=\"{$links[$macros[$i]->macro_id]}\"><img src=\"../extensions/macros/{$macros[$i]->macro_name}/{$macros[$i]->macro_name}.jpg\" />{$macros[$i]->macro_title}</a></div>");
  
    if($c==6){
	 $content->generalTags("</div>");
	 $c=0;
	}
	
	$c++;
  
  }
  
  $content->generalTags("</div>");
  
  $content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");
  
  $layout->content=$content->toString();
  
  $layout->setWidth("763px");
  
  $layout->showLayout();
?>
</div>
<?php
}
public function editPage(){

GLOBAL $db;

$content=new objectString;

$msg=$this->profiler->updateProfile();

$user=$db->getUserSession();

 $id=0;

 if(isset($_GET['mid'])){
 
 $id=$_GET['mid'];
 
 }else{
  $items=$db->getDefaultMenuItem();
  
  $id=$items->item_id;
 
 }

$content->generalTags(System::backButton("?mid=$id"));

$content->generalTags(System::contentTitle("Edit Profile"));

$content->generalTags($msg);

$content->generalTags($this->profiler->editMyinfo(false,$user));

$layout=new macro_layout;

$layout->content=$content->toString();

$layout->setWidth("763px");
?>
<div style="float:left; margin-top:5px;margin-bottom:5px;">
<?php
$layout->showLayout();
?>
</div>
<?php
}
private function profileSummary($fal,$user){
$obj=new objectString;

$obj->generalTags($this->profiler->basicUserInfo($fal,$user,"",true));

return $obj->toString();

}

}
?>