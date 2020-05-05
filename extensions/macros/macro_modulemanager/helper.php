<?php
class helper{
public function mainpage(){
	if(isset($_GET['new'])){
		
		$this->newModule();
		
	}else{
		
		$this->defaultPage();
		
	}
}
public function newModule(){

  GLOBAL $db;

 $layout=new macro_layout;

 $layout->setWidth("900px");

 $content=new objectString;
 
 $content->generalTags(System::contentTitle("New Module".System::backButton("?mid=".System::getCheckerNumeric("mid"))).System::categoryTitle("Details","margin-bottom:10px;"));
 
 $form=new form_control;
 
 $content->generalTags($form->formHead("?mid=".System::getCheckerNumeric("mid")));
 
 $input=new input;
 
 $input->setClass("form_input");
 
 $input->input("text","module_title");
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Module Title</strong></div>{$input->toString()}</div>");
 
 $module_type=new input;
 
 $module_type->setClass("form_select");
 
 $modules=$db->getModules("where for_super=0 group by modulename");
 
 for($i=0;$i<count($modules);$i++)
 $module_type->addItem($modules[$i]->module_name,$modules[$i]->module_name);
 
 $module_type->select("module_type");
 
 $content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Module Type</strong></div>{$module_type->toString()}</div>");
 
 $create=new input;
 
 $create->setClass("form_button");
 
 $create->setTagOptions("style=\"padding:3px 5px 3px 5px;\"");
 
 $create->input("submit","createbtn","Create Module");
 
 $content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\">".$create->toString()."</div></form>");
 
 
 $layout->content=$content->toString();
 
 $layout->showlayout();

}
private function createModule(){
	GLOBAL $db;
	
	if(isset($_POST['createbtn'])){
		
		if($_POST['module_title']=="")
		return System::getWarningText("Please enter module title.");
		
		$db->insertQuery(array("module_title","modulename"),"modules",array("'{$_POST['module_title']}'","'{$_POST['module_type']}'"));
		
		return System::successText("Module created successfully.");
		
	}
	
	return "";
	
}
public function defaultPage(){
$layout=new macro_layout;

//echo system::categoryTitle("Manage Modules","margin-left:10px;width:98.5%;margin-bottom:3px;margin-top:3px;");

$layout->setWidth("900px");

$content=new objectString;

$content->generalTags(System::backButton("?"));

$content->generalTags(System::contentTitle("Modules"));



$content->generalTags($this->updateStatus());

 $content->generalTags($this->createModule());


$form=new form_control;

$form->enableUpload();

$content->generalTags($form->formHead());

$input=new input;

$input2=new input;

$input2->setClass("form_button_disable");

$input2->setTagOptions("style=\"float:right;margin-right:5px;\"");

$input2->input("submit","disab_btn","Disable -");

$input->setClass("form_button");

$input->setTagOptions("style=\"float:right;margin-right:5px;\"");

$input->input("submit","enable_btn","Enable");

$newmodule="<a href=\"?mid=".System::getCheckerNumeric("mid")."&new=1\"><input type=\"button\" class=\"form_button_add\" style=\"float:left;\" value=\"New +\"/></a>";

$content->generalTags(System::categoryTitle($newmodule.$input->toString().$input2->toString(),"margin-bottom:5px;"));

$content->generalTags($this->moduleList());

$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;width:100%;\"></div>");

$content->generalTags("</form>");

$layout->content=$content->toString();


$layout->showLayout();

}
private function moduleList(){

GLOBAL $db;

$list=new list_control;



$list->setColumnSizes(array("30px","30px","250px","230px","45px","100px"));

$list->setSize("875px","350px");

$list->setAlternateColor("#cbe7f8");

$module=$db->getModules(" where for_super=0");

for($i=0;$i<count($module);$i++){

$list->addItem(array($i+1,"<input type=\"checkbox\" name=\"chk_$i\" id=\"chk_$i\" onclick=\"resetChecker('checker')\"  value=\"{$module[$i]->module_id}\" />","<a href=\"?{$_SERVER['QUERY_STRING']}&mopt=1&id={$module[$i]->module_id}\" title=\"Click to edit module '{$module[$i]->module_title}'. \">".$module[$i]->module_title."</a>",$module[$i]->module_name,system::statusIcon($module[$i]->module_status,"margin-left:10px"),$module[$i]->module_position));

}

$list->setColumnNames(array("No","<input type=\"checkbox\" id=\"checker\" onclick=\"checkUncheck('checker','chk',".count($module).")\" />","Module Title","Module Type","Status","Position"));

$list->showList(false);

return $list->toString();

}
private function updateStatus(){

GLOBAL $db;

if((isset($_POST['enable_btn']))||(isset($_POST['disab_btn']))){

 $ms="Module(s) disabled";

 $st=0;
 
 if(isset($_POST['enable_btn'])){
 
  $st=1;
 
  $ms="Module(s) enabled";
    
 }
 
 $items=system::getPostedItems("chk");
 
 $array=array();
 
 for($i=0;$i<count($items);$i++){
 
   $array[]=$items[$i]->value;
 
 }
 
 $plug="";
 
 if(count($array)>0){
  $plug="where id=";
 }
 
 $str=implode(" or id=",$array);
  
 if(count($array)>0){
 
 $db->updateQuery(array("status=$st"),"modules",$plug.$str);
 
 return system::successText($ms);
 }
}

}
public function editPage($bk_link){
 
 GLOBAL $db;
 
 $id=0;
 
 if(isset($_GET['id'])){
 
  $id=$_GET['id'];
  
 }
 
 $cont=new objectString;
 
 $layout=new macro_layout;
 
 $layout->setWidth("895px");
 
 //echo system::categoryTitle("Edit Module","margin-left:10px;margin-bottom:3px;margin-top:3px;");
 
 $cont->generalTags(system::backButton($bk_link));
 
 $msg=$this->updateModule();

 $modules=$db->getModules(" where id=$id");
 
 for($i=0;$i<count($modules);$i++){ 
 
 $cont->generalTags(system::contentTitle("Edit : : {$modules[$i]->module_title}"));
 
 $cont->generalTags($msg);
 
 $cont->generalTags("<div style=\"float:left;width:50%;overflow:hidden;padding-right:10px;\">");
 
 $cont->generalTags(system::categoryTitle("Edit module details"));

 $form=new form_control;

 $form->enableUpload();

 $cont->generalTags($form->formHead());
 
 $input=new input;
 
 $input->setClass("form_input");
 
 $input->input("text","module_title",$modules[$i]->module_title);
 
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Title</strong></div>{$input->toString()}</div>");
 
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Show title</strong></div>".System::radioStatus("module_showtitle",$modules[$i]->module_show_title)."</div>");
 
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Module Name</strong></div>{$modules[$i]->module_name}</div>");
 
 $enabled="";
 
 $disabled="";
 
 if($modules[$i]->module_status==1){
 
 $enabled="checked=\"checked\"";
 
 }else{
 
 $disabled="checked=\"checked\"";
 
 }
 
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Status</strong></div><div id=\"mini_label\">Enabled</div><input type=\"radio\" name=\"module_status\" $enabled value=\"1\"><div id=\"mini_label\" $disabled >Disabled</div><input type=\"radio\" name=\"module_status\" $disabled value=\"0\" ></div>");
 
  
 $select2=new input;
 
 $select2->setClass("form_select");
 
 $select2->addItem("0","User");
 
 $select2->addItem("1","Administrator");
 
 $select2->addItem("2","Special");
 
 $select2->setSelected($modules[$i]->module_accesslevel);
   
 $select2->select("module_accesslevel");
  
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Access Level</strong></div>{$select2->toString()}</div>");  
 
 $select=new input;
 
 $select->setClass("form_select");
 
 $positions=system::shared('positions');

 $select->setSelected($modules[$i]->module_position);
 
 
 for($b=0;$b<count($positions);$b++){
 
   $select->addItem($positions[$b],$positions[$b]);
 
 }
 
 $select->select("module_position");
  
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Positions</strong></div>{$select->toString()}</div>");
 
 $select=new input;
 
 $select->setMultiple("multiple");
 
 $select->setClass("form_select_multi");

 
 $menu_modules=$db->getModules("where ismenu=1 and for_super=0;");
 
 
 for($b=0;$b<count($menu_modules);$b++){

  $select->addItem($menu_modules[$b]->module_id."_",$menu_modules[$b]->module_title);
  
  $select->setInnerTitle($menu_modules[$b]->module_id."_");
  
  $assign=array();
  
  if($modules[$i]->module_menuassign!="")  
    $assign=unserialize($modules[$i]->module_menuassign);
	
	for($j=0;$j<count($assign);$j++){
	
	  $select->setSelected($assign[$j]);
	
	}
  
   
  $items=$db->getMenuItems($menu_modules[$b]->module_id,true);
  
  for($t=0;$t<count($items);$t++){
  
   $select->addItem($items[$t]->item_id,$items[$t]->item_title);
   
    if($items[$t]->item_hasChild){
	
	  $array=$db->getChildItemsArray($items[$t]->item_children);
	  
	  for($d=0;$d<count($array);$d++){
	  
	    $select->addItem($array[$d]->item_id,$array[$d]->item_title);
	  
	  }
	
	}
  
  }
 
  }
 $select->select("module_assignment[]");
 
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Menu Assigment</strong></div>{$select->toString()}</div>");
 
 
 $input=new input;
 
 $input->setClass("form_input");
 
 
 $input->input("text","module_syle_id",$modules[$i]->module_suffix);
 
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Style Id</strong></div>{$input->toString()}</div>");
 
 
 $input=new input;
 
 $input->setClass("form_button");
 
 $input->input("submit","update_module","Update");
 
 $cont->generalTags("<div id=\"form_row\">".$input->toString()."</div>");
 
 $cont->generalTags("</div>");
 
 $cont->generalTags("<div style=\"float:left;width:100%;overflow:hidden;\">");
 
 $cont->generalTags(system::contentTitle("Advanced Settings"));
 
 
 if(file_exists(ADMIN_ROOT."../extensions/modules/{$modules[$i]->module_name}/settings.php")){
 
 include_once(ADMIN_ROOT."../extensions/modules/{$modules[$i]->module_name}/settings.php");
 
 $cont->generalTags(set_main($modules[$i]->module_id));
 
 }else{
   
   $cont->generalTags("<div id=\"form_row\" >N/A</div>");
   
 }
 
$cont->generalTags("</div>");

 
 $cont->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");
 
 $cont->generalTags("</form>");
}
 
 $layout->content=$cont->toString();
 
 $layout->showLayout();
 
}
public function updateModule(){

GLOBAL $db;

if(isset($_POST['update_module'])){

 $setting=system::getPostedItems("module");
 

 //system::getPostedValue($setting,"module_assignment");
 
 $db->updateQuery(array("module_title='".System::getPostValue($setting,"module_title")."'","status=".System::getPostValue($setting,"module_status"),"accesslevel=".System::getPostValue($setting,"module_accesslevel"),"menu_assign='".serialize(System::getPostValue($setting,"module_assignment"))."'","position='".System::getPostValue($setting,"module_position")."'","show_title=".System::getPostValue($setting,"module_showtitle"),"module_suffix='".System::getPostValue($setting,"module_syle_id")."'"),"modules"," where id=".$_GET['id']);
 
return System::successText("Item Updated");
 
}

}

}
?>