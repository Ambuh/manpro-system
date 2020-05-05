<?php
class helper{
public $share;
public $db;
public $options;
public function __construct(){
GLOBAL $db;
$this->db=$db;
$this->share=System::shared("showcars");
$this->options=System::shared("staticoptions");
}
public function deletePosts(){

 $post_ids=System::nameValueToSimpleArray(System::getPostedItems("cs"),true); 
 if(isset($_POST['del_button'])){
 if(count($post_ids)>0){
 $whereclause=" where id=".implode(" or id=",$post_ids);
 
 $cars=$this->share->getCars($whereclause);
 
 for($i=0;$i<count($cars);$i++){
   $images=unserialize($cars[$i]->car_images);
   for($b=0;$b<count($images);$b++)
    if(file_exists("../".$images[$b]->image_path))
	unlink("../".$images[$b]->image_path);
 }
 $this->db->deleteQuery("cars",$whereclause);
 }
}
}
public function markAsFeatured(){
$post_ids=System::nameValueToSimpleArray(System::getPostedItems("cs"),true); 
if(isset($_POST['feat_button']))
if(count($post_ids)>0){
 $whereclause=" where id=".implode(" or id=",$post_ids);

 $this->db->updateQuery(array("featured=1"),"cars",$whereclause);

}

}
public function markUnFeatured(){

$post_ids=System::nameValueToSimpleArray(System::getPostedItems("cs"),true); 
if(isset($_POST['ufeat_button']))
 
 if(count($post_ids)>0){
  $whereclause=" where id=".implode(" or id=",$post_ids);

  $this->db->updateQuery(array("featured=0"),"cars",$whereclause);

 }

}
public function manageListing(){

$cont=new objectString;

$form=new form_control;

$cont->generalTags($form->formHead());

$del=new input;

$del->setClass("form_button_delete");

$del->setTagOptions("style=\"float:right;\"");

$del->input("submit","del_button","Delete Post(s)");

$feat=new input;

$feat->setClass("form_button");

$feat->setTagOptions("style=\"float:right;\"");

$feat->input("submit","feat_button","Feature Post(s)");

$ufeat=new input;

$ufeat->setClass("form_button_add");

$ufeat->setTagOptions("style=\"float:right;\"");

$ufeat->input("submit","ufeat_button","Unmark Featured");

$cont->generalTags(System::contentTitle("Uploads".$del->toString().$ufeat->toString().$feat->toString(),"padding-bottom:5px;float:left;"));

$cont->generalTags($this->deletePosts());

$cont->generalTags($this->markAsFeatured());

$cont->generalTags($this->markUnFeatured());

$cont->generalTags($this->share->showCarsList(false,0,"","",true,"",true));

$cont->generalTags("</form>");

return $cont->toString();

}
public function saveReview(){

 if(isset($_POST['save_page'])){
 $this->db->updateQuery(array("review="."'".base64_encode($_POST['pageedit'])."'","status=".$_POST['status'],"title='".$_POST['title_text']."'"),"reviews","where id=".System::getCheckerNumeric("edit"));
 
 return System::successText("Page updated successfully");
 
 }
	
}
public function saveNewReview(){

 if(isset($_POST['save_newpage'])){
 $this->db->insertQuery(array("review","status","title","created_date"),"reviews",array("'".base64_encode($_POST['pageedit'])."'",$_POST['status'],"'".$_POST['title_text']."'","NOW()"));
 
 return System::successText("Page saved successfully");
 
 }
	
}
private function enableReview(){
	if(isset($_POST['enab_review'])){
	 
	 $chk=System::nameValueToSimpleArray(System::getPostedItems("ck"),true);
	 
	 if(count($chk)>0){
	   $wh=" where id=".implode(" or id=",$chk);
       
	   $this->db->updateQuery(array("status=1"),"reviews",$wh);
	   
	   return System::successText("Review published successfully.");

	 }
	 
	}
}
private function disableReview(){
	if(isset($_POST['disab_review'])){
	 
	 $chk=System::nameValueToSimpleArray(System::getPostedItems("ck"),true);
	 
	 if(count($chk)>0){
	   $wh=" where id=".implode(" or id=",$chk);
       
	   $this->db->updateQuery(array("status=0"),"reviews",$wh);
	   
	   return System::successText("Review unpublished successfully.");

	 }
	 
	}
}
private function deleteReview(){
	if(isset($_POST['del_review'])){
	 
	 $chk=System::nameValueToSimpleArray(System::getPostedItems("ck"),true);
	 
	 if(count($chk)>0){
	   $wh=" where id=".implode(" or id=",$chk);
       
	   $this->db->deleteQuery("reviews",$wh);
	   
	   return System::successText("Review delete successfully.");

	 }
	 
	}
}

public function reviewPage(){

$mess=$this->deleteReview();

$mess2=$this->enableReview();

$mess3=$this->disableReview();

$cont=new objectString;

$enab=new input;

$enab->setClass("form_button_add");

$enab->setTagOptions("style=\"float:right\"");

$enab->input("submit","enab_review","Publish");

$disab=new input;

$disab->setClass("form_button_delete");

$disab->setTagOptions("style=\"float:right\"");

$disab->input("submit","disab_review","Unpublish");

$del=new input;

$del->setClass("form_button_delete");

$del->setTagOptions("style=\"float:right;margin-left:20px;\"");

$del->input("submit","del_review","Delete");

$form=new form_control;

$cont->generalTags($form->formHead());

$maxid=1;

$res=$this->db->selectQuery(array("max(id)"),"reviews");

while($row=mysqli_fetch_row($res))
if($row!=NULL)
$maxid=$row[0]+1;

$cont->generalTags(System::contentTitle("Car reviews".$del->toString().$disab->toString().$enab->toString()."<div class=\"form_button\" style=\"float:right;padding:5px 5px 4px 5px;color:#fff;\"><a href=\"?mid=".System::getCheckerNumeric("mid")."&edit=$maxid&new=yes\" style=\"color:#fff;\" title=\"New\">New Review +</a></div>","float:left;padding-bottom:5px;"));

$cont->generalTags(System::categoryTitle("Reviews","margin-bottom:5px;"));

$cont->generalTags($mess);

$cont->generalTags($mess2);

$cont->generalTags($mess3);

$list=new list_control;

$list->setListId("lid");

$list->setHeaderFontBold();

$state=array("Unpublished","Published");

$res=$this->db->selectQuery(array("id","review","title","status","byname","DATE_FORMAT(created_date,'%d %M %Y')","TIME(created_date)"),"reviews","");
$i=0;


while($row=mysqli_fetch_row($res)){
$list->addItem(array("<input type=\"checkbox\" name=\"ck_{$i}\" value=\"$row[0]\"/>","<a title=\"Click to edit\" href=\"?mid=".System::getCheckerNumeric("mid")."&edit={$row[0]}\">".$row[2]."</a>",$row[5]." @".$row[6],$state[$row[3]],""));
$i++;
}
$list->setColumnNames(array("","Title","Date","Status","By"));

$list->setColumnSizes(array("50px","350px","160px","100px","150px"));

$list->setSize("853px","300px");

$list->showList(false);

$cont->generalTags($list->toString());

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
public function editReview(){
	
$cont=new objectString;	

$mess3=$this->uploadImage();

$mess=$this->saveReview();

$mess2=$this->saveNewReview();

$re=$this->share->readReview(" where id=".System::getCheckerNumeric("edit"));

$isnew=false;

if((count($re)==0)&&(isset($_GET['new']))){
$re[]=new review;
$isnew=true;
}
for($i=0;$i<count($re);$i++){

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
$cont->generalTags(System::contentTitle("Car Review".System::backButton("?mid=".System::getCheckerNumeric("mid")),"float:left;padding-bottom:5px;"));
$cont->generalTags(System::categoryTitle("Edit Review Page".$save->toString(),"margin-bottom:5px;"));

$cont->generalTags($mess);

$cont->generalTags($mess2);

$cont->generalTags($mess3);

$title=new input;

$title->setClass("form_input");

$title->input("text","title_text",$re[$i]->review_title);

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Page Title</strong></div>{$title->toString()}</div>");

$status=new input;

$status->setClass("form_select");

$status->setSelected($re[$i]->review_status);

$status->addItems(array(new name_value("Unpublished",0),new name_value("Published",1)));

$status->select("status");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Status</strong></div>{$status->toString()}</div>");

$cont->generalTags("<div id=\"form_row\">".System::loadEditor($re[$i]->review_data,"advanced","pageedit","width:100%;height:300px;")."</div>");

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
private function addMake(){

if(isset($_POST['add_make'])){
  if($_POST['make_name']=="")
  return System::getWarningText("Please enter a valid make name.");
  
  $this->db->insertQuery(array("make_desc"),"car_make",array("'{$_POST['make_name']}'"));
  
  return System::successText("Make Added Successfully");
  
}
}
private function addMakeAndModelFromXml($data){
	$xml=simplexml_load_string($data);
	$makes=array();
	foreach($xml->children() as $child){
		
	  foreach($child->children() as $child2)
	  if($child2->getName()=="carname"){
	    $makes[]=$child2;
	    $this->db->insertQuery(array("make_desc"),"car_make",array("'$child2'"));
	  }
	  //$makes[]=$child;
	  //echo $child2->getName()."<br/>";
	}
 $whereclause=implode("' or make_desc='",$makes);
 
 $ids=array();
 
 $res=$this->db->selectQuery(array("*"),"car_make","where make_desc='".$whereclause."' order by id asc");
 while($row=mysqli_fetch_row($res)) 
   $ids[]=$row[0];
 print_r($ids);
 
  $c=0;
   foreach($xml->children() as $child){
		
	  foreach($child->children() as $child2){
	  if($child2->getName()=="carmodellist"){
	    
		foreach($child2->children() as $childs){
		 if(isset($ids[$c])){
	      $this->db->insertQuery(array("model_desc","make_id"),"models",array("'{$childs}'",$ids[$c]));
		 }
		}
		$c++;
	  }
	  
	  }
	  //$makes[]=$child;
	  //echo $child2->getName()."<br/>";
	}
	
}
private function addMakes(){

if(isset($_POST['add_makes'])){
  if($_POST['make_names']=="")
  return System::getWarningText("Please enter a valid car makes.");
  
  $mks=explode("/",$_POST['make_names']);
  
  if(count($mks)>0){
  for($i=0;$i<count($mks);$i++)
   if(trim($mks[$i])!="")
   $this->db->insertQuery(array("make_desc"),"car_make",array("'{$mks[$i]}'"));
  
  return System::successText("Makes Added Successfully");
  }
}
}
public function deleteMakes(){
	if(isset($_POST['del_make'])){
		
	 $post_ids=System::nameValueToSimpleArray(System::getPostedItems("nm"),true); 
	 
     if(count($post_ids)>0){
      $whereclause=" where id=".implode(" or id=",$post_ids);

	  $this->db->deleteQuery("car_make",$whereclause);
	  
	  $whereclause=" where make_id=".implode(" or make_id=",$post_ids);
	  
	  $this->db->deleteQuery("models",$whereclause);
	  
	  return System::successText("Make(s) deleted successfully.");
	  
	 }
	}
}
public function makePorpular(){

  $post_ids=System::nameValueToSimpleArray(System::getPostedItems("nm"),true); 
if(isset($_POST['make_pop'])){
    if(count($post_ids)>0){
      $whereclause=" where id=".implode(" or id=",$post_ids);

     $this->db->updateQuery(array("is_porpular=1"),"car_make",$whereclause);
	 
	 return System::successText("Item(s) marked successfully.");
	}

}
     

}
public function unmarkPorpular(){

  $post_ids=System::nameValueToSimpleArray(System::getPostedItems("nm"),true); 
if(isset($_POST['make_unpop'])){
    if(count($post_ids)>0){
      $whereclause=" where id=".implode(" or id=",$post_ids);

     $this->db->updateQuery(array("is_porpular=0"),"car_make",$whereclause);
	 
	 return System::successText("Item(s) unmarked successfully.");
	}

}
     

}
public function savePriority(){

  if(isset($_POST['save_p'])){
    $pr=System::getPostedItems("pi");
    for($i=0;$i<count($pr);$i++){
	$vl=explode("_",$pr[$i]->name);
	 $val=0;
	 if(is_numeric($pr[$i]->value))
	 $val=$pr[$i]->value;
	 
	  $this->db->updateQuery(array("priority=".$val),"car_make","where id=".$vl[1]);
	  
	}
  return System::successText("Priorities saved successfully.");
  }

}
public function makeAndModels(){

$cont=new objectString;

$form=new form_control;

$cont->generalTags($form->formHead());

$del=new input;

$del->setClass("form_button_delete");

$del->setTagOptions("style=\"float:right;\"");

$del->input("submit","del_make","Delete Make");

$pop=new input;

$pop->setClass("form_button");

$pop->setTagOptions("style=\"float:right;\"");

$pop->input("submit","make_pop","Mark Porpular");

$unpop=new input;

$unpop->setClass("form_button_add");

$unpop->setTagOptions("style=\"float:right;\"");

$unpop->input("submit","make_unpop","Unmark Porpular");

$save_p=new input;

$save_p->setClass("form_button_add");

$save_p->setTagOptions("style=\"float:right;margin-right:50px;\"");

$save_p->input("submit","save_p","Save Priority");

$cont->generalTags(System::contentTitle("Make and Model".$del->toString().$unpop->toString().$pop->toString().$save_p->toString(),"float:left;margin_bottom:5px;"));

$cont->generalTags(System::categoryTitle("Available Makes","margin-bottom:5px;"));

$cont->generalTags($this->addMake());

$cont->generalTags($this->addMakes());

$cont->generalTags($this->deleteMakes());

$cont->generalTags($this->makePorpular());

$cont->generalTags($this->unmarkPorpular());

$cont->generalTags($this->savePriority());

$list=new list_control;

$list->setColumnNames(array("","Make","Rank","Priority"));

$makes=$this->options->make(false," ",false);

for($i=0;$i<count($makes);$i++){
$rank="Porpular";
if($makes[$i]->other==0)
$rank="Normal";
if($makes[$i]->value>0){
$input=new input;
$input->setClass("form_select");
$input->setTagOptions("style=\"width:30px;margin-top:0px;height:10px;\"");
$input->input("text","pi_".$makes[$i]->value,$makes[$i]->other2);
$list->addItem(array("<input type=\"checkbox\" name=\"nm_{$i}\" $rank value=\"{$makes[$i]->value}\"/>","<a href=\"?mid=".System::getCheckerNumeric("mid")."&make={$makes[$i]->value}\">".$makes[$i]->name."</a>",$rank,$input->toString()));
}
}

$list->setHeaderFontBold();

$list->setSize("500px","300px");

$list->setAlternateColor("#cbe3f8");

$list->setColumnSizes(array("50px","200px","100px","100px"));

$list->showList(false);

$cont->generalTags($list->toString());

$cont->generalTags("<div style=\"float:left;width:340px;margin-left:10px;\">");

$cont->generalTags(System::categoryTitle("Add Make","margin-bottom:5px;"));



$name=new input;

$name->setClass("form_input");

$name->input("text","make_name","");

$add=new input;

$add->setClass("form_button");

$add->input("submit","add_make","Add Make");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Name</strong></div>{$name->toString()}</div>");

$cont->generalTags("<div id=\"form_row\" style=\"padding:0px;\">{$add->toString()}</div>");

$cont->generalTags("<strong>Or</strong>");

$cont->generalTags(System::categoryTitle("Add Makes","margin:5px 0px 5px 0px;"));

$cont->generalTags("<div id=\"form_row\" style=\"padding:0px\"><small>Add makes separated by '/ '.</small></div>");

$name=new input;

$name->setClass("form_input");

$name->input("text","make_names","");

$add=new input;

$add->setClass("form_button");

$add->input("submit","add_makes","Add Makes");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Names</strong></div>{$name->toString()}</div>");

$cont->generalTags("<div id=\"form_row\" style=\"padding:0px;\">{$add->toString()}</div>");

$cont->generalTags("</div>");

$cont->generalTags("</form>");

return $cont->toString();

}
private function addModel(){

if(isset($_POST['add_model'])){
  if($_POST['model_name']=="")
  return System::getWarningText("Please enter a valid model name.");
  
  $this->db->insertQuery(array("model_desc","make_id"),"models",array("'{$_POST['model_name']}'",System::getCheckerNumeric("make")));
  
  return System::successText("Model Added Successfully");
  
}
}
private function addModels(){

if(isset($_POST['add_models'])){
  if($_POST['model_names']=="")
  return System::getWarningText("Please enter a valid car makes.");
  
  $mks=explode("/",$_POST['model_names']);
  
  if(count($mks)>0){
  for($i=0;$i<count($mks);$i++)
   if(trim($mks[$i])!="")
  $this->db->insertQuery(array("model_desc","make_id"),"models",array("'{$mks[$i]}'",System::getCheckerNumeric("make")));

  return System::successText("Models Added Successfully");
  }
}
}
public function deleteModels(){
	if(isset($_POST['del_model'])){
		
	 $post_ids=System::nameValueToSimpleArray(System::getPostedItems("nm"),true); 
	 
     if(count($post_ids)>0){
      $whereclause=" where id=".implode(" or id=",$post_ids);

	  $this->db->deleteQuery("models",$whereclause);
	  
	  return System::successText("Model(s) deleted successfully.");
	  
	 }
	}
}
public function makeDetails(){

$cont=new objectString;

$makes=$this->options->make(false,"where id=".System::getCheckerNumeric("make"),true);
for($i=0;$i<count($makes);$i++){

$form=new form_control;

$cont->generalTags($form->formHead());

$cont->generalTags(System::contentTitle("Make : {$makes[$i]->name}".System::backButton("?mid=".System::getCheckerNumeric("mid"))));

$del=new input;

$del->setClass("form_button_delete");

$del->setTagOptions("style=\"float:right;\"");

$del->input("submit","del_model","Delete Model");

$cont->generalTags(System::categoryTitle("Models Available ".$del->toString(),"margin-bottom:5px;"));

$cont->generalTags($this->addModel());

$cont->generalTags($this->addModels());

$cont->generalTags($this->deleteModels());

$list=new list_control;

$list->setSize("500px","300px");

$models=$this->options->model(false,"where make_id=".System::getCheckerNumeric("make"),true);

$list->setColumnSizes(array("50px","400px"));

for($d=0;$d<count($models);$d++){

  $list->addItem(array("<input type=\"checkbox\" name=\"nm_{$i}\" value=\"{$models[$d]->value}\"/>",$models[$d]->name));

}

$list->setColumnNames(array("","Model"));

$list->setHeaderFontBold();

$list->showList(false);

$cont->generalTags($list->toString());

$cont->generalTags("<div style=\"float:left;width:340px;margin-left:10px;\">");

$cont->generalTags(System::categoryTitle("Add Make","margin-bottom:5px;"));



$name=new input;

$name->setClass("form_input");

$name->input("text","model_name","");

$add=new input;

$add->setClass("form_button");

$add->input("submit","add_model","Add Model");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Name</strong></div>{$name->toString()}</div>");

$cont->generalTags("<div id=\"form_row\" style=\"padding:0px;\">{$add->toString()}</div>");

$cont->generalTags("<strong>Or</strong>");

$cont->generalTags(System::categoryTitle("Add Models","margin:5px 0px 5px 0px;"));

$cont->generalTags("<div id=\"form_row\" style=\"padding:0px\"><small>Add makes separated by '/ '.</small></div>");

$name=new input;

$name->setClass("form_input");

$name->input("text","model_names","");

$add=new input;

$add->setClass("form_button");

$add->input("submit","add_models","Add Models");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Names</strong></div>{$name->toString()}</div>");

$cont->generalTags("<div id=\"form_row\" style=\"padding:0px;\">{$add->toString()}</div>");

$cont->generalTags("</form>");

}

return $cont->toString();

}
public function saveTerms(){

  if(isset($_POST['submit_terms'])){
	 $this->share->saveTermsOrPolicy($_POST['trms'],1);
	 $this->share->saveTermsOrPolicy($_POST['prv'],2);
	 $this->share->saveTermsOrPolicy($_POST['abt'],3);
	 return System::successText("About, Terms, Policies saved successfully");
  }

}
public function termsAndConditions(){
$cont=new objectString;



$form=new form_control;

$cont->generalTags($form->formHead());

$save=new input;

$save->setClass("form_button");

$save->setTagOptions("style=\"float:right;\"");

$save->input("submit","submit_terms","Submit");

$cont->generalTags(System::contentTitle("About, Terms & Conditions Private Policy".$save->toString(),"float:left;"));

$cont->generalTags($this->saveTerms());

$cont->generalTags(System::categoryTitle("About","margin-bottom:5px;"));

$cont->generalTags("<div style=\"width:100%;float:left;\">".System::loadEditor($this->share->readTermsOrPolicy(3),"advanced","abt","width:100%;height:200px;")."</div>");

$cont->generalTags(System::categoryTitle("Edit Terms & Conditions","margin-bottom:5px;margin-top:10px;"));

$cont->generalTags("<div style=\"width:100%;float:left;\">".System::loadEditor($this->share->readTermsOrPolicy(1),"advanced","trms","width:100%;height:200px;")."</div>");

$cont->generalTags(System::categoryTitle("Edit private policy","margin:10px 0px 5px 0px;"));

$cont->generalTags("<div style=\"width:100%;float:left;\">".System::loadEditor($this->share->readTermsOrPolicy(2),"advanced","prv","width:100%;height:200px;")."</div>");

$cont->generalTags("</div>");

return $cont->toString();

}
}
?>