<?php

class editHelper{
private $db;
private $shared;
public function __construct(){

GLOBAL $db;

$this->db=$db;

$this->shared=System::shared("articles");

}
public function articlesPage(){
	
	if(isset($_GET['edit'])){
	  return $this->editArticle();
	}else{
	  return $this->listArticles();
	}
	
}
private function changeArticleStatus(){
	
$items =System::nameValueToSimpleArray(System::getPostedItems("ck"),true);

if(isset($_POST['enab_article'])){  
  
  return $this->shared->changeArticleStatus($items,1);

}

if(isset($_POST['disab_article'])){
  
  return $this->shared->changeArticleStatus($items);

}

if(isset($_POST['del_article'])){
  
  return $this->shared->deleteArticle($items);

}

}
private function listArticles(){
   
$mess=$this->changeArticleStatus();

//$mess2=$this->enableReview();

//$mess3=$this->disableReview();

$cont=new objectString;

$enab=new input;

$enab->setClass("form_button_add");

$enab->setTagOptions("style=\"float:right\"");

$enab->input("submit","enab_article","Publish");

$disab=new input;

$disab->setClass("form_button_delete");

$disab->setTagOptions("style=\"float:right\"");

$disab->input("submit","disab_article","Unpublish");

$del=new input;

$del->setClass("form_button_delete");

$del->setTagOptions("style=\"float:right;margin-left:20px;\"");

$del->input("submit","del_article","Delete");

$form=new form_control;

$cont->generalTags($form->formHead());

$maxid=1;

$res=$this->db->selectQuery(array("max(id)"),"articles");

while($row=mysqli_fetch_row($res))
if($row!=NULL)
$maxid=$row[0]+1;

$cont->generalTags(System::contentTitle("Articles <div class=\"form_button\" style=\"float:right;padding:5px 5px 4px 5px;color:#fff;\"><a href=\"?mid=".System::getCheckerNumeric("mid")."&edit=$maxid&new=yes\" style=\"color:#fff;\" title=\"New\">New Review +</a></div>","float:left;padding-bottom:5px;"));

$cont->generalTags($mess);

$cont->generalTags(System::categoryTitle($del->toString().$disab->toString().$enab->toString(),"margin-bottom:5px;"));

//$cont->generalTags($mess);

//$cont->generalTags($mess2);

//$cont->generalTags($mess3);

$list=new list_control;

$list->setListId("lid");

$list->setHeaderFontBold();

$list->setAlternateColor("#cbe3f8");

$categs=System::nameValueToSimpleArray(System::swapNameValue($this->shared->getCategories()));

$categs[0]="Uncategorized";

$state=array("Unpublished","Published");

$arts=$this->shared->getArticles();

for($i=0;$i<count($arts);$i++){
$list->addItem(array("<input type=\"checkbox\" name=\"ck_{$i}\" value=\"{$arts[$i]->article_id}\"/>","<a title=\"Click to edit\" href=\"?mid=".System::getCheckerNumeric("mid")."&edit={$arts[$i]->article_id}\">".$arts[$i]->article_title."</a>",System::getARrayElementValue($categs,$arts[$i]->article_category),$arts[$i]->article_created,$state[$arts[$i]->article_status]));

}
$list->setColumnNames(array("","Title","Category","Date","Status","By"));

$list->setColumnSizes(array("50px","290px","150px","100px","120px","100px"));

$list->setSize("853px","350px");

$list->showList(false);

$cont->generalTags($list->toString());

$cont->generalTags("</form>");

$cont->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #eee;\"></div>");

return $cont->toString();


}
private function saveArticle(){

if(isset($_POST['save_newpage'])){
	
	return $this->shared->createArticle($_POST['title_text'],$_POST['pageedit'],$_POST['categ'],$_POST['status']);
	
}

if(isset($_POST['save_page'])){
    
	return  $this->shared->updateArticle(System::getCheckerNumeric("edit"),$_POST['title_text'],$_POST['pageedit'],
	$_POST['categ'],$_POST['status']);
	
}

}
private function editArticle(){

$cont=new objectString;	

$mess=$this->saveArticle();

//$mess3=$this->uploadImage();

//$mess=$this->saveReview();

//$mess2=$this->saveNewReview();

//$re=$this->share->readReview(" where id=".System::getCheckerNumeric("edit"));


$art=new article;

$art=$this->shared->getArticles(" where id=".$_GET['edit']);

$isnew=false;

if((count($art)==0)&&(isset($_GET['new']))){
$art[]=new article;
$isnew=true;
}
for($i=0;$i<count($art);$i++){

$form=new form_control;

$form->enableUpload();

$cont->generalTags($form->formHead("?mid=".System::getCheckerNumeric("mid")."&edit=".System::getCheckerNumeric("edit")));

$save=new input;

$save->setClass("form_button");

$save->setTagOptions("style=\"float:right\"");

if(!$isnew){
$save->input("submit","save_page","Submit Page");

}else{
$save->input("submit","save_newpage","Submit Page");

}

$cont->generalTags(System::contentTitle("New Article".System::backButton("?mid=".System::getCheckerNumeric("mid")),"float:left;padding-bottom:5px;"));

$cont->generalTags(System::categoryTitle("Edit Article".$save->toString(),"margin-bottom:5px;"));

$cont->generalTags($mess);

//$cont->generalTags($mess2);

//$cont->generalTags($mess3);

$title=new input;

$title->setClass("form_input");

$title->input("text","title_text",$art[$i]->article_title);

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Page Title</strong></div>{$title->toString()}</div>");

$status=new input;

$status->setClass("form_select");

$status->setSelected($art[$i]->article_status);

$status->addItems(array(new name_value("Unpublished",0),new name_value("Published",1)));

$status->select("status");

$cats=new input;

$cats->setClass("form_select");

$cs=$this->shared->getCategories();

$cats->addItem(0,"Uncategorised");

$cats->addItems($cs);

$cats->setSelected($art[$i]->article_category);

$cats->select("categ");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Category</strong></div>{$cats->toString()}</div>");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Status</strong></div>{$status->toString()}</div>");

$cont->generalTags("<div id=\"form_row\">".System::loadEditor($art[$i]->article_text,"advanced","pageedit","width:100%;height:300px;")."</div>");

}

$cont->generalTags(System::categoryTitle("Upload Images"));

$cont->generalTags("<div id=\"form_row\">Upload images to ../images/data/ folder.</div>");

$inp=new input;

$inp->setClass("form_button");

$inp->input("submit","uploadbtn","Upload Image");

$input=new input;

$input->setClass("form_input");

$input->input("file","fileimage");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Image File</strong></div>{$input->toString()}{$inp->toString()}</div>");

$cont->generalTags("</form>");

return $cont->toString();


}
private function uploadImage(){

if(isset($_POST['uploadbtn'])){
	if(move_uploaded_file($_FILES['fileimage']['tmp_name'],"../images/data/".$_FILES['fileimage']['name'])){
	
	 return System::successText("Image uploaded successfully");
	
	}
}
	
 }
 public function deleteCategory(){
	 
   if(isset($_POST['delete_cat'])){ 	 
   
    $ids=System::nameValueToSimpleArray(System::getPostedItems("chk"),true);
   
    return $this->shared->deleteCategory($ids);
   
   }
	 
 }
 public function saveCategory(){
	 
   if(isset($_POST['save_cat'])){
    
	if(trim($_POST['category_title'])=="")
	return System::getWarningText("Please enter a valid category name");
	
    return $this->shared->createCategory($_POST['category_title']);
   
   } 
 }
 public function categoryPage(){
 
 $cont=new objectString;
 
 $cont->generalTags(System::contentTitle("Manage Categories"));
 
 $cont->generalTags($this->saveCategory());
 
 $cont->generalTags($this->deleteCategory());
 
 $form=new form_control;
 
 $cont->generalTags($form->formHead());
 
 $cont->generalTags("<div style=\"overflow:hidden;width:43%;float:left;\">");
 
 $cont->generalTags(System::categoryTitle("New Category","width:98%;margin-bottom:10px;"));
 
 $input=new input;
 
 $input->setClass("form_input");
 
 $input->input("text","category_title");
 
 $add_cat=new input;
 
 $add_cat->setClass("form_button_add");
 
 $add_cat->setTagOptions("style=\"margin-left:10px;\"");
 
 $add_cat->input("submit","save_cat","Add Category");
 
 $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Category Title</strong></div>{$input->toString()}{$add_cat->toString()}</div>");
 
 $cont->generalTags("</div>");
 
 $cont->generalTags("<div style=\"overflow:hidden;width:55%;float:right;\">");
 
 $cont->generalTags(System::categoryTitle("Categories","width:99%;float:left;margin-bottom:5px;"));
 
 $list=new list_control;
 
 $list->setHeaderFontBold();
 
 $list->setAlternateColor("#cbe3f8");
 
 $list->setColumnNames(array("No."," ","Category Title"));
 
 $list->setListId("catl");
 
 $list->setColumnSizes(array("30px","50px","200px"));

 
 $list->setSize("99.5%","350px");

 $cats=$this->shared->getCategories();
 
 for($i=0;$i<count($cats);$i++)
 $list->addItem(array($i+1,"<input type=\"checkbox\" name=\"chk_{$i}\" value=\"{$cats[$i]->value}\" />",$cats[$i]->name));
 
 $list->showList(false);
 
 $cont->generalTags($list->toString());
 
 $delete_cat=new input;
 
 $delete_cat->setClass("form_button_delete");
 
 $delete_cat->input("submit","delete_cat","Delete X");
 
 $cont->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #eee;\">{$delete_cat->toString()}</div>");
 
 $cont->generalTags("</div></form>");
 
 return $cont->toString();
 
 }
 
}

?>