<?php
class manager_helper{
private $layout;
private $db;
public function __construct($layout){
GLOBAL $db;
$this->db=$db;
$this->layout=$layout;
}
public function mainPage($bk_link=""){
GLOBAL $db;

//echo system::categoryTitle("Manage menus","margin-left:10px;width:98.5%;margin-bottom:3px;margin-top:3px;");

$list_control=new list_control;

$list_control->setColumnNames(array("No","Menu Name","Status"));

$list_control->setColumnSizes(array("35px","450px","50px"));

$list_control->setSize("870px","350px");

$list_control->setAlternateColor("#cbe7f8");

$list_control->setBackgroundColour("#ffd");

$modules=$db->getModules(" where ismenu=1 and for_super=0");

for($i=0;$i<count($modules);$i++){
 $list_control->addItem(array($i+1,"<a href=\"?".$_SERVER['QUERY_STRING']."&mopt=0&id={$modules[$i]->module_id}\">".$modules[$i]->module_title."</a>",system::statusIcon($modules[$i]->module_status,"margin-left:10px")));
}

$list_control->showList(false);

$this->layout->content=system::backButton($bk_link);

$this->layout->content.=system::contentTitle("Menu List","margin-bottom:10px");

$this->layout->content.=$list_control->toString();

$this->layout->content.="<div id=\"form_row\" style=\"border-bottom:1px solid #444;width:99%;\"></div>";

$this->layout->showLayout();

}
public function editPage($id,$bk_link){
GLOBAL $db;

$layout=new macro_layout;

$page_content=new objectString;

$page_content->generalTags(system::backButton($bk_link));

$page_content->generalTags(System::contentTitle("Edit Menu"));
$page_content->generalTags(System::categoryTitle("Menu details.","margin-bottom:5px;"));

$page_content->generalTags($this->updateMenuDetails($id));

if($id==""){
echo system::warningText("Invalid Request");
return;
}

$module=$db->getModules(" where id=$id");

for($i=0;$i<count($module);$i++){

if($module[$i]->module_for_super==1){
echo system::warningText("Invalid Request");
return;
}

//echo system::categoryTitle("Edit Menu [ {$module[$i]->module_title} ]","margin-left:10px;width:98.5%;margin-bottom:3px;margin-top:3px;");


$page_content->generalTags("<form method=\"POST\" action=\"\">");

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Menu Title</strong></div><input type=\"text\" value=\"{$module[$i]->module_title}\" name=\"menu_title\" class=\"form_input\" ></div>");

$enables="";

$disables="";

if($module[$i]->module_status==1){

$enables="checked=\"cheked\"";

}else{

$disables="checked=\"cheked\"";

}

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Status</strong></div><input type=\"radio\" name=\"status\" value=\"1\" $enables /><div id=\"mini_label\">Enabled</div><input type=\"radio\" name=\"status\" value=\"0\" $disables /><div id=\"mini_label\">Disabled</div></div>");

$select=new input;

$pos=system::shared("positions");

if($pos!=NULL){
 for($p=0;$p<count($pos);$p++){
 
   $select->addItem($pos[$p],$pos[$p]);
  
  }
}

$select->setClass("form_select");

$select->setSelected($module[$i]->module_position);

$select->select("modulepos");

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Position</strong></div>{$select->toString()}</div>");

$submit=new input;

$submit->setClass("form_button");

$submit->input("submit","update_menu","Submit");

$page_content->generalTags("<div id=\"form_row\">{$submit->toString()}</div>");


$page_content->generalTags(system::categoryTitle("Menu items","margin-bottom:10px;"));

$page_content->generalTags($this->quickUpdate());

$page_content->generalTags($this->createLink($id));

$page_content->generalTags("<div style=\"overflow:hidden;float:left;width:445px;\">");

$page_content->generalTags($this->menu_items($id));

$input=new input;

$input->setClass("form_button");

$input->input("submit","enable_btn","Enable");

$input2=new input;

$input2->setClass("form_button_disable");

$input2->input("submit","disable_btn","Disable -");

$input3=new input;

$input3->setClass("form_button_disable");

$input3->setTagOptions("style=\"float:right;\"");

$input3->input("submit","delete_btn","Delete X");

$page_content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;margin-top:1px;\">{$input->toString()}{$input2->toString()}{$input3->toString()}</div>");


$page_content->generalTags("</div>");

$page_content->generalTags("<div style=\"overflow:hidden;float:left;width:410px; margin-left:10px;height:350px;padding-right:5px; \">".system::appInnerTitle("New Item"));

$page_content->generalTags("<div id=\"box\">");

$input= new input;

$input->setClass("form_input");

$input->input("text","nw_input_title");

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Item Title</strong></div>{$input->toString()}</div>");

$input= new input;

$input->setClass("form_input");

$input->input("text","nw_input_alias");

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Item Alias</strong></div>{$input->toString()}</div>");


$select=new input;

$select->setClass("form_select");

$select->addItem("0","User");

$select->addItem("1","Administrator");

$select->addItem("2","Special");

$select->select("nw_accesslevel");

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Access Level</strong></div>{$select->toString()}</div>");

$page_content->generalTags("<div style=\"float:left;margin-bottom:10px;margin-top:10px;\">".system::info("Internal Link")."</div>");

$select=new input;

$select->setClass("form_select");

$comp=$db->getmacros("where for_super=0");


$select->addItem(0,"--macro--");


for($b=0;$b<count($comp);$b++){

$select->addItem($comp[$b]->macro_id,$comp[$b]->macro_title);

}

$select->select("nw_macro");

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Link To</strong></div>{$select->toString()}</div>");

$page_content->generalTags("<div style=\"float:left;margin-bottom:10px;margin-top:10px;\">".system::info("External Link")."</div>");

$input=new input;

$input->setClass("form_input");

$input->input("text","nw_externallink");

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Enter Link</strong></div>{$input->toString()}</div>");

$addbtn=new input;

$addbtn->setClass("form_button_add");

$addbtn->setTagOptions("style=\"float:right;\"");

$addbtn->input("submit","addnew_item","Add Item");

$page_content->generalTags("<div id=\"form_row\">{$addbtn->toString()}</div>");

$page_content->generalTags("<div id=\"form_row\" style=\"margin-top:-2px;\">".system::info("External link overrides internal link.Make sure to leave it blank when assigning an internal link.")."</div>");

$page_content->generalTags("</div>");


$page_content->generalTags("</div>");

$page_content->generalTags("</form>");

$layout->setWidth("900px");

$layout->content=$page_content->toString();

$layout->showLayout();
}

}
private function menu_items($id){

GLOBAL $db;

$list_cont=new list_control;

$list_cont->setSize("443px","280px");

$list_cont->setColumnNames(array("No","","Item Title","Status","Default"));

$list_cont->setColumnSizes(array("30px","30px","200px","50px","40px"));

$list_cont->setAlternateColor("#cbe7f8");

$items=$db->getMenuItems($id,true);

$ct=-1;

for($i=0;$i<count($items);$i++){

$ct++;

  $list_cont->addItem(array($i+1,"<input type=\"checkbox\" name=\"chbox_$ct\" value=\"{$items[$i]->item_id}\"/>","<a href=\"?mid={$_GET['mid']}&mopt=1&id=$id&itemid={$items[$i]->item_id}\">".$items[$i]->item_title."</a>",system::statusIcon($items[$i]->item_status,"margin-left:7px"),System::defaultIcon($items[$i]->item_isdefault)));
  
  if($items[$i]->item_hasChild){
  
    $array=$this->fetchChildItems($items[$i]->item_children);
    
	
	for($b=0;$b<count($array);$b++){
	$ct++;
	$list_cont->addItem(array("","<input type=\"checkbox\" name=\"chbox_$ct\" value=\"{$array[$b]->item_id}\" />","<a href=\"?mid={$_GET['mid']}&mopt=1&id=$id&itemid={$array[$b]->item_id}\">".$array[$b]->item_title."</a>",system::statusIcon($array[$b]->item_status,"margin-left:7px"),System::defaultIcon($array[$b]->item_isdefault)));
	
	}
  }
  
}

$list_cont->showList(false);

return $list_cont->toString();

}
private function fetchChildItems($items,$level=1){
$array=array();
 for($i=0;$i<count($items);$i++){
   
   $items[$i]->item_title=$this->getLevel($level).$items[$i]->item_title;
   
   $array[]=$items[$i];
    
	if($items[$i]->item_hasChild){
	
	  $ar=$this->fetchChildItems($items[$i]->item_children,$level+1);
	 
	 for($b=0;$b<count($ar);$b++){
	 
	  $array[]=$ar[$b];
	  
	  }
	}
	
 }
 return $array;
}
private function getLevel($lev){

$d="";

for($i=0;$i<$lev;$i++){
  $d.=" - ";
}

return $d;

}
public function saveDiplay(){
 
 if(isset($_POST['save_display'])){

 $this->db->updateQuery(array("display_options=".$_POST['display_option']),"menu_items"," where id=".System::getCheckerNumeric("itemid"));
 
 return System::successText("Display saved successfully");
 
 }
}
public function editMenuItem($id,$bklink){

GLOBAL $db;

$itemid=0;

if(isset($_GET['itemid'])){

 $itemid=$_GET['itemid'];

}

$disp_message=$this->saveDiplay();

//echo system::categoryTitle("Item Edit","margin-left:10px; margin-top:2px;margin-bottom:3px;");

$optmessage=$this->updateOption();

$layout=new macro_layout;

$page_content=new objectString;

$page_content->generalTags(system::backButton($bklink));

$page_content->generalTags($this->updateItem());

$m_item=$db->getMenuItemFromDb($itemid,true);

if($m_item==NULL){
 
 echo "Invalid requests";
 
 return;
 
}


if($m_item->item_menuid!=$id){
  echo "Invalid request";
 
 return;

}

$modules=$db->getModules(" where id=$id");


for($i=0;$i<count($modules);$i++){

$page_content->generalTags(system::contentTitle("Menu : : ".$modules[$i]->module_title));

$page_content->generalTags(system::categoryTitle("Edit menu item","margin-bottom:10px;"));

//$page_content->generalTags(system::successText("Changes Saved"));

$enables="";

$disabled="";

if($m_item->item_status==1){

$enables="checked=\"checked\"";

}else{

$disabled="checked=\"checked\"";

}

$page_content->generalTags("<form method=\"POST\" action=\"\" >");

$page_content->generalTags("<div style=\"float:left;overflow:hidden;width:100%;\"><div style=\"float:left;overflow:hidden;width:50%;\">");

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Menu Id</strong></div>{$m_item->item_menuid}</div>");

$page_content->generalTags("<input type=\"hidden\" name=\"menu_id\" value=\"{$m_item->item_menuid}\" />");

$input=new input;

$input->setClass("form_input");

$input->input("text","item_title",$m_item->item_title);

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Item title</strong></div>{$input->toString()}</div>");

$input=new input;

$input->setClass("form_input");

$input->input("text","item_alias",$m_item->item_alias);

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Menu Alias</strong></div>{$input->toString()}</div>");

$page_content->generalTags("<div id=\"form_row\" style=\"margin-top:10px;\"><div id=\"label\"><strong>Item status</strong></div><input type=\"radio\" name=\"item_status\" $enables value=\"1\" /><div id=\"mini_label\">Enabled</div><input type=\"radio\" name=\"item_status\" $disabled value=\"0\"/><div id=\"mini_label\">Disabled</div></div>");

$select=new input;

$select->setClass("form_select");


$select->addItem("0","--No Parent--");

$items=$db->getMenuItems($id,true);

$select->setSelected($m_item->item_parentid);

for($i2=0; $i2<count($items);$i2++){ 

if(($items[$i2]->item_id!=$itemid)&&($items[$i2]->item_parentid!=$itemid)){
  
  $select->addItem($items[$i2]->item_id,$items[$i2]->item_title); 
  
   if($items[$i2]->item_hasChild){
     
	 $array=$this->fetchChildItems($items[$i2]->item_children);
	 
	 for($b=0;$b<count($array);$b++){
	 
	 if(($array[$b]->item_id!=$itemid)&&($array[$b]->item_parentid!=$itemid)){
	 
	   $select->addItem($array[$b]->item_id,$array[$b]->item_title);
	 
	 }
	 
	 }
	   
   } 
    
 }
}

$select->select("parent_item");

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Parent Item</strong></div>{$select->toString()}</div>");

$checkbox=new input;

$chk="";

if($m_item->item_isdefault==1){
$chk="checked=\"checked\"";
}

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Make default</strong></div><input type=\"radio\" name=\"make_default\" $chk /></div>");


$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Item link</strong></div>$m_item->item_link</div>");

$select=new input;

$select->setClass("form_select");

$select->addItem(0,"--Select macro--");

$select->setSelected($m_item->item_macroId);

$mcros=$db->getmacros("where for_super=0 and id=".$m_item->item_macroId);

$selected_macro=NULL;

for($c=0;$c<count($mcros);$c++)
$selected_macro=$mcros[$c];



$comp=$db->getmacros("where for_super=0");

for($i3=0;$i3<count($comp);$i3++){
 
 $select->addItem($comp[$i3]->macro_id,$comp[$i3]->macro_title);
 
}

$select->select("item_macro");

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Link to</strong></div>{$select->toString()}</div>");


$lnk="";

if(!preg_match("/\?/i",$m_item->item_link)){

 $lnk=$m_item->item_link;

}

$input=new input;

$input->setClass("form_input");

$input->input("text","item_externallink",$lnk);

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>or External link</strong></div>{$input->toString()}</div>");

$select=new input;

$select->setClass("form_select");

$select->addItem("0","User");

$select->setSelected($m_item->item_accesslevel);

$select->addItem("1","Administrator");

$select->addItem("2","Public");

$select->select("item_accesslevel");

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Access Level</strong></div>{$select->toString()}</div>");

$subm=new input;

$subm->setClass("form_button");

$subm->input("submit","update_link","Submit");

$page_content->generalTags("<div id=\"form_row\">{$subm->toString()}</div>");

$page_content->generalTags("</div>");

$page_content->generalTags("<div style=\"float:left;overflow:hidden;width:48%;\">");

if($selected_macro!=NULL)
if(file_exists(ROOT."extensions/macros/".$selected_macro->macro_name."/display_options.php")){

include(ROOT."extensions/macros/".$selected_macro->macro_name."/display_options.php");

$disp=new macroDisplay;

$page_content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #eee;font-weight:bold;\">Display Options.</div>");

$page_content->generalTags($disp_message);

$page_content->generalTags($disp->displayOptions($m_item->item_displayOption));

$page_content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #eee;font-weight:bold;\">Other Settings</div>");

$page_content->generalTags($disp->otherSettings($m_item->item_displayOption));

}

$page_content->generalTags("</div>");

$page_content->generalTags("</div>");

$page_content->generalTags(System::contentTitle("Advanced macro assignment"));

$page_content->generalTags($optmessage);

$select=new input;

$select->setClass("form_select");

$select->setId("slmacro");

$select->addItem(0,"--Select macro--");

$select->showAjaxProgress();

$select->enableAjax(true);
   
$comp=$db->getmacros("where for_super=0");

for($i3=0;$i3<count($comp);$i3++){
 
 $select->addItem($comp[$i3]->macro_id,$comp[$i3]->macro_title);
 
}

$select->select("slmacro",System::generateAjaxParams("link_window","macro_menumanager"));

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Select Macro</strong></div>{$select->toString()}</div>");

$page_content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Macro Inner Options</strong></div><div id=\"link_window\" class=\"form_input\"></div></div>");

$update=new input;

$update->setClass("form_button");

$update->input("submit","update_option","Link");

$page_content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\" >{$update->toString()}</div>");

$page_content->generalTags("");

$page_content->generalTags("</form>");

}

$layout->content=$page_content->toString();

$layout->setWidth("900px");

$layout->showLayout();

}
private function updateItem(){

GLOBAL $db;

if(isset($_POST['update_link'])){

  $items=system::getPostedItems("item");
  
   $lnk="?#";
  
  if(system::getPostValue($items,"item_externallink")!=""){
    
	$lnk=system::getPostValue($items,"item_externallink");
  
  }else{
	
   $lnk="?mid={$_GET['itemid']}";
   
   if(isset($_POST['display_option']))
   $lnk.="&sub=".$_POST['display_option'];
   
   if(isset($_POST['mopt']))
   $lnk.="&iid=".$_POST['mopt'];
   

  }
  
  $res=$db->selectQuery(array("macro_id"),"menu_items","where id=".$_GET['itemid']);
  
  $md=0;
  
  while($rw=mysqli_fetch_row($res)){ $md=$rw[0]; }
  
  if(((system::getPostValue($items,"item_macro")==0)&&(system::getPostValue($items,"item_externallink")==""))or(($md==system::getPostValue($items,"item_macro"))&&(system::getPostValue($items,"item_externallink")==""))){
  
  $link=system::getPostValue($items,"item_alias") =="" ? str_replace(" ","_",trim(system::getPostValue($items,"item_title"))):str_replace(" ","_",trim(system::getPostValue($items,"item_alias")));
    
   $db->updateQuery(array("item_title='".system::getPostValue($items,"item_title")."'","parent_id=".system::getPostValue($items,"parent_item"),"status=".system::getPostValue($items,"item_status"),"macro_id=".system::getPostValue($items,"item_macro"),"accesslevel=".system::getPostValue($items,"item_accesslevel"),"alias='".$link."'","link='".$lnk."'"),"menu_items"," where id=".$_GET['itemid']);
  
  if(isset($_POST['make_default'])){
  
    $db->updateQuery(array("user_default=0"),"menu_items","where for_super=0");
  
    $db->updateQuery(array("user_default=1"),"menu_items","where id={$_GET['itemid']} and menu_id={$_POST['menu_id']}");
  
  }
  
  }else{  
  
   echo "asdsad";
  
    $db->updateQuery(array("item_title='".system::getPostValue($items,"item_title")."'","parent_id=".system::getPostValue($items,"parent_item"),"status=".system::getPostValue($items,"item_status"),"link='$lnk'","macro_id=".system::getPostValue($items,"item_macro"),"accesslevel=".system::getPostValue($items,"item_accesslevel")),"menu_items"," where id=".$_GET['itemid']);
  
   if(isset($_POST['make_default'])){
  
    $db->updateQuery(array("user_default=0"),"menu_items","where menu_id={$_POST['menu_id']}");
  
    $db->updateQuery(array("user_default=1"),"menu_items","where id={$_GET['itemid']} and menu_id={$_POST['menu_id']}");
  
  }
  
  }
  
  //$db->removeMenuFromSession($_GET['id']);
  
  //$db->getMenuItems($_GET['id'],true);
  System::hardRefresh();
  
  
  return system::successText("Item Updated");  

}

}
public function updateMenuDetails($id){

GLOBAL $db;

if(isset($_POST['update_menu'])){

 $db->updateQuery(array("module_title='{$_POST['menu_title']}'","status={$_POST['status']}","position='{$_POST['modulepos']}'"),"modules"," where id=$id");

 return system::successText("Changes Saved");

}

return "";

}
private function quickUpdate(){

GLOBAL $db;

  if((isset($_POST['enable_btn']))||(isset($_POST['disable_btn']))||(isset($_POST['delete_btn']))){
     
	 $msg="Item(s) Disabled";
  
     $st=0;
	 
	 if(isset($_POST['enable_btn'])){
	   
	   $st=1;
	   
	   $msg="Item(s) Enabled";
	   
	 }
  
     $items=system::getPostedItems("chbox");
	 
	 $array=array();
	 
	 for($i=0;$i<count($items);$i++){
	   
	   $array[]=$items[$i]->value;
		
	 }
	 
	 $wheropt=implode(" or id=",$array);
	 
	 $plug="";
	 
	 if(count($array)>0){
	   $plug="where id=";
	 }
	 
	 if(count($array)>0){
	 
	 if(!isset($_POST['delete_btn'])){
	 
	 $db->updateQuery(array("status=$st"),"menu_items",$plug.$wheropt);
	 
	 }else{
	 
	 $db->deleteQuery("menu_items",$plug.$wheropt);
	 
	 $msg="Item(s) Deleted";
	 
	 }
	 
	 //$db->removeMenuFromSession($_GET['id']);
  
     //$db->getMenuItems($_GET['id'],true);

      System::hardRefresh();
	 
     return system::successText($msg);
    
	}

  }

}
private function updateOption(){

GLOBAL $db;

if(isset($_POST['update_option'])){
 
   $items=System::getPostedItems('lnk');
   if(count($items)>0){
   
    if(isset($_POST['lnk_opt'])){
   $db->updateQuery(array("link='?mid={$_GET['itemid']}".System::getPostValue($items,'lnk_opt')."'","macro_id={$_POST['slmacro']}"),"menu_items"," where id={$_GET['itemid']}");
   
   $db->removeMenuFromSession($_GET['id']);
  
   $db->getMenuItems($_GET['id'],true);
   
   return System::successText("saved");
    }
	
  }
}

}
private function createLink($id){

GLOBAL $db;

if(isset($_POST['addnew_item'])){

  $items=system::getPostedItems("nw");
  
  if(system::getPostValue($items,"nw_input_title")==""){
    return;
  }
  
  $lnk="#";
  
  if(system::getPostValue($items,"nw_externallink")!=""){
    $lnk=system::getPostValue($items,"nw_externallink");
  }else{
    
	$maxid=$db->selectQuery(array("max(id)"),"menu_items");
	
	$thisid=1;
	
	while($row=mysqli_fetch_row($maxid)){
	  $thisid=$row[0]+1;
	}
	
   $lnk="?mid=$thisid";
   
  }
  
  $link=system::getPostValue($items,"nw_input_alias") =="" ? str_replace(" ","_",trim(system::getPostValue($items,"nw_input_title"))):str_replace(" ","_",trim(system::getPostValue($items,"nw_input_alias"))); 
 
 $db->insertQuery(array("menu_id","item_title","macro_id","accesslevel","link","status","alias"),"menu_items",array("$id","'".system::getPostValue($items,"nw_input_title")."'",system::getPostValue($items,"nw_macro"),system::getPostValue($items,"nw_accesslevel"),"'".$lnk."'",1,"'".$link."'")); 
 
 system::hardRefresh();
  
  return system::successText("Link Added");

}

}

}

?>