<?php
class siteman_helper{
public function saveSettings(){
 if(isset($_POST['save_settings'])){
   $set=new siteSettings;
   $set->siteName=$_POST['site_name'];
   $set->metaDescription=$_POST['description'];
   $set->metaKeywords=$_POST['keywords'];
   $set->genericCode=$_POST['genericcode'];
   
   System::saveSiteSettings($set);
   
   return System::successText("Settings saved successfully");
   
 }
}
public function mainPage(){

$cont=new objectString;

$form=new form_control;

$mess=$this->saveSettings();

$settings=System::getSiteSettings();

$cont->generalTags($form->formHead());

$input=new input;

$input->setClass("form_button");

$input->setTagOptions("style=\"float:right;\"");

$input->input("submit","save_settings","Save");

$cont->generalTags(System::contentTitle("Manage Site".$input->toString(),"float:left;padding-bottom:3px;"));

$cont->generalTags(System::categoryTitle("Site Settings","margin-bottom:3px;"));

$cont->generalTags($mess);

$input=new input;

$input->setClass("form_input");

$input->input("text","site_name",$settings->siteName);

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Site Name</strong></div>{$input->toString()}</div>");

$cont->generalTags(System::categoryTitle("Metadata Settings","margin-bottom:3px;"));

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Meta Descrip</strong></div><textarea style=\"width:300px;height:70px;\" name=\"description\">{$settings->metaDescription}</textarea></div>");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Meta Keywords</strong></div><textarea style=\"width:300px;height:70px;\" name=\"keywords\">{$settings->metaKeywords}</textarea></div>");

$cont->generalTags(System::categoryTitle("Generic Codes","margin-bottom:3px;"));

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Analytics</strong></div><textarea name=\"genericcode\" style=\"width:300px;height:70px;\">{$settings->genericCode}</textarea></div>");

$cont->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");


$cont->generalTags("</form>");
return $cont->toString();

}

}
?>