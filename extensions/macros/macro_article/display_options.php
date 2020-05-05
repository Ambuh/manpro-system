<?php
class macroDisplay{
private $shared;
public function __construct(){

 $this->shared=System::shared("articles");

}
public  function displayOptions($layout=0){

$cont=new objectString;

$select=new input;

$save=new input;

$save->setClass("form_button");

$save->setTagOptions("style=\"margin-left:10px;\"");

$save->input("submit","save_display","Apply");

$select->addItems(array(new name_value("Category Layout",0),new name_value("Article Layout",1)));

$select->setSelected($layout);

$select->setClass("form_select");

$select->select("display_option");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Select Layout</strong></div>{$select->toString()}{$save->toString()}</div>");

return $cont->toString();

}
public function otherSettings($layout){
 $cont = new objectString;
 
 $stat=array("Unpublished","Published");
 
 if($layout==1){

  $list=new list_control;
  
  $list->setColumnNames(array("No"," ","Article Title","Status"));
  
  $list->setHeaderFontBold();
  
  $list->setColumnSizes(array("10%","10%","55%","18%"));
  
  $list->setAlternateColor("#cbe3f8");
  
  $list->setSize("100%","300px");
  
  $art=$this->shared->getArticles();
  
  for($i=0;$i<count($art);$i++)
  $list->addItem(array($i+1,"<input type=\"radio\" name=\"mopt\" value=\"{$art[$i]->article_id}\"/>",$art[$i]->article_title,
  $stat[$art[$i]->article_status]));
  
  $list->showList();
  
  $cont->generalTags($list->toString());
	 
 }else{
 
  $list=new list_control;
  
  $list->setColumnNames(array("No"," ","Article Category"));
  
  $list->setHeaderFontBold();
  
  $list->setColumnSizes(array("10%","10%","60%"));
  
  $list->setAlternateColor("#cbe3f8");
  
  $list->setSize("100%","300px");
  
  $cats=$this->shared->getCategories();
  
  for($i=0;$i<count($cats);$i++)
  $list->addItem(array($i+1,"<input type=\"radio\" name=\"mopt\" value=\"{$cats[$i]->value}\"/>",$cats[$i]->name));
  
  $list->showList();
  
  $cont->generalTags($list->toString());
 
 }
 return $cont->toString();
 
}
}
?>