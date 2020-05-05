<?php
class helper{
public function __construct(){

}
public function mainPage(){

GLOBAL $db;
echo "<div style=\"float:left;margin-top:5px;width:100%;margin-bottom:5px;\">";
//echo System::categoryTitle("Macros","margin-left:10px;margin-top:3px;margin-bottom:3px;");
$layout=new macro_layout;

$layout->setWidth("900px");

$cont=new objectString;

$list=new list_control;

$list->setColumnSizes(array("30px","30px","250px","250px","130px"));

$list->setSize("875px","350px");

$list->setAlternateColor("#cbe7f8");

$cont->generalTags(System::backButton("?"));

$cont->generalTags(System::contentTitle("Macros","margin-bottom:3px;"));

$cont->generalTags($this->updateMacros());

$macros=$db->getMacros(" where for_super=0");

for($i=0;$i<count($macros);$i++){

 $list->addItem(array($i+1,System::checkBox("chk_".$i,"checker",$macros[$i]->macro_id),"<a href=\"?mid={$_GET['mid']}&mopt=1&id={$macros[$i]->macro_id}\">".$macros[$i]->macro_title."</a>",$macros[$i]->macro_name,System::statusIcon($macros[$i]->macro_status)));

}

$list->setColumnNames(array("No",System::headerCheckbox("checker","chk",count($macros)),"Macro Title","Macro Type","Status"));

$list->showList(false);


$cont->generalTags("<form action=\"\" method=\"POST\">");

$submit=new input;

$submit->setClass("form_button");

$submit->setTagOptions("style=\"float:right;margin-right:5px;\"");

$submit->input("submit","enable_btn","Enable");

$submit2=new input;

$submit2->setClass("form_button_disable");

$submit2->setTagOptions("style=\"float:right;margin-right:5px;\"");

$submit2->input("submit","disable_btn","Disable -");

$cont->generalTags(System::categoryTitle($submit->toString().$submit2->toString(),"margin-bottom:3px;"));

$cont->generalTags($list->toString());

$cont->generalTags("</form>");

$cont->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");

$layout->content=$cont->toString();

$layout->showLayout();
echo "</div>";
}
public function editPage($id,$bk_link){

if(isset($_GET['mac'])){

 return $this->macroPage();

}

GLOBAL $db;

echo "<div style=\"float:left;margin-top:5px;width:100%;margin-bottom:5px;\">";

$layout=new macro_layout;

$cont=new objectString;

$update_message=$this->updateMacro();

$macro=$db->getMacros(" where id=$id");

for($i=0;$i<count($macro);$i++){

$cont->generalTags(System::backButton($bk_link));

$cont->generalTags(System::contentTitle("Edit : : {$macro[$i]->macro_title}"));

$cont->generalTags($update_message);

$cont->generalTags("<form method=\"POST\" action=\"\">");

$title_input=new input;

$title_input->setClass("form_input");

$title_input->input("Text","macro_title",$macro[$i]->macro_title);

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Title</strong></div>{$title_input->toString()}</div>");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Macro Type</strong></div>{$macro[$i]->macro_name}</div>");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Status</strong></div>".System::radioStatus('macro_status',$macro[$i]->macro_status)."</div>");

$update_bt=new input;

$update_bt->setClass("form_button");

$update_bt->input("submit","update","Update");

$update_bt->setClass("button");

$cont->generalTags("<div id=\"form_row\" >{$update_bt->toString()}</div>");

$cont->generalTags(System::contentTitle("Advanced Settings"));

if(file_exists(ROOT."extensions/macros/{$macro[$i]->macro_name}/settings.php")){

include_once(ROOT."extensions/macros/{$macro[$i]->macro_name}/settings.php");

$cont->generalTags(@set_main($macro[$i]->macro_id));

}else{

$cont->generalTags("<div id=\"form_row\" >N/A</div>");

}
$cont->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\" ></div>");

$cont->generalTags("</form>");

$layout->setWidth("900px");

$layout->content=$cont->toString();

$layout->showLayout();
echo "</div>";
}


}
private function macroPage(){
	
	GLOBAL $db;
	
	$layout=new macro_layout;
	
	$macro=$db->getMacros(" where id=".System::getCheckerNumeric("id"));

   for($i=0;$i<count($macro);$i++){
	   
	$layout->content=System::contentTitle("Macro [".$macro[$i]->macro_title."]".System::backButton("?mid=".System::getCheckerNumeric("mid")."&mopt=".System::getCheckerNumeric("mopt")."&id=".System::getCheckerNumeric("id")),"margin-bottom:5px;");
	   
	if(file_exists(ROOT."extensions/macros/{$macro[$i]->macro_name}/settings.php")){

      include_once(ROOT."extensions/macros/{$macro[$i]->macro_name}/settings.php");

      $layout->content.=set_mainHelper($macro[$i]);

    }
   }
	$layout->setWidth("900px");
	
	$layout->showLayout();
	
}
private function updateMacros(){

GLOBAL $db;

if((isset($_POST['enable_btn']))|((isset($_POST['disable_btn'])))){

$st=0;

if(isset($_POST['enable_btn'])){
 
 $st=1;

}

$itms=System::getPostedItems("chk");

$array=array();

for($i=0;$i<count($itms);$i++){

 $array[]=$itms[$i]->value;


}

if(count($array)>0){

$ar=implode(" or id=",$array);

$db->updateQuery(array("status=$st"),"macros","where id=".$ar);
 
if(isset($_POST['enable_btn'])){ 

return System::successText("Item(s) Enabled");

}else{

return System::successText("Item(s) disabled");

}

}

}


}
public function updateMacro(){

GLOBAL $db;

 if(isset($_POST['update'])){
 
  $items=System::getPostedItems("macro");
  
  $db->updateQuery(array("status=".System::getPostValue($items,'macro_status'),"macro_title='".System::getPostValue($items,'macro_title')."'"),"macros","where id=".$_GET['id']);
 
  return System::successText("Macro Updated");
   
  
 }
 
}
}
?>