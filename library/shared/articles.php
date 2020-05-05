<?php
function articles(){
	return new macro_articles();
}
class macro_articles{
	private $db;
	public function __construct(){

	GLOBAL $db;
	
	$this->db=$db;
	
	}
	public function getCategories($whereclause=""){
	
	 $res=$this->db->selectQuery(array("id","category_name"),"article_categories",$whereclause);
	 $results=array();
	 while($row=mysqli_fetch_array($res)){
	 
	 $results[]=new name_value($row['category_name'],$row['id']);
	 
	 }
	
	return $results;
	
	}
	public function createCategory($category_name){
		
		$this->db->insertQuery(array("category_name"),"article_categories",array("'".$category_name."'"));
		
		return System::successText("Category added successfully");
		
	}
	public function deleteCategory($ids=array()){
		
		$ors=implode(" or id=",$ids);
		
		$whereclause="";
		
		if($ors!="")
		$whereclause=" where id=".$ors;
	

		$res=$this->db->selectQuery(array("*"),"articles",str_replace("id","article_category",$whereclause));
		
		while($row=mysqli_fetch_row($res))
		return System::getWarningText("Failed.This category contains articles.");
		
		$this->db->deleteQuery("article_categories",$whereclause);
		
		return System::successText("Category(s) deleted successfully.");
		
	}
	public function createArticle($title,$text,$category=0,$status=0,$created_by=0){
	  
	 $this->db->insertQuery(array("article_title","article_text","article_created","article_category","status"),"articles",array("'".$title."'","'".base64_encode($text)."'","now()",$category,$status));
	 
	 return System::successText("Article created successfully.");
	   
	}
	public function updateArticle($aid,$title,$text,$category=0,$status=0){
	  
	 $this->db->updateQuery(array("article_title='".$title."'","article_text='".base64_encode($text)."'","article_datemodified=now()","article_category=".$category,"status=".$status),"articles","where id=".$aid);
	 
	 return System::successText("Article created successfully.");
	   
	}
	public function deleteArticle($ids=array()){
		
	$ors=implode(" or id=",$ids);
		
	$whereclause="";
		
     if($ors!="")
	  $whereclause=" where id=".$ors;
	
	if($whereclause!=""){
	 $this->db->deleteQuery("articles",$whereclause);
		
	 return System::successText("Article deleted successfully.");
	}
	
	}
	public function getArticles($whereclause=""){
		
	  $res=$this->db->selectQuery(array("id","article_category","article_title","article_text","status","DATE_FORMAT(article_created,'%d %M %Y') as article_created","article_createdById","DATE_FORMAT(article_datemodified,'%d %M %Y') as article_datemodified"),"articles",$whereclause);
	
	  $results=array();
	
	  while($row=mysqli_fetch_array($res)){
	   
	   $art=new article;
	   $art->article_id=$row['id'];
	   $art->article_category=$row['article_category'];
	   $art->article_title=$row['article_title'];
	   if(defined('ROOT_DIR')){
	   $art->article_text=str_replace("../",System::getFolderBackJump(),stripslashes(base64_decode($row['article_text'])));
	   }else{
	   $art->article_text=stripslashes(base64_decode($row['article_text']));
	   }
	   $art->article_status=$row['status'];
	   $art->article_created=$row['article_created'];
	   $art->article_createdById=$row['article_createdById'];
	   $art->article_dateModified=$row['article_datemodified'];
	   
	   $results[]=$art;
	   
	  }
		
	  return $results;
	}
	public function changeArticleStatus($ids,$status=0){
		
		$ors=implode(" or id=",$ids);
		
		$whereclause="";
		
		if($ors!="")
		$whereclause=" where id=".$ors;
		
		if($whereclause!=""){
		$this->db->updateQuery(array("status=".$status),"articles",$whereclause);
		
		return System::successText("Article(s) status changed successfully");
		}
		
	}
	
}
class article{
	public $article_id;
	public $article_title;
	public $article_text;
	public $article_category;
	public $article_status;
	public $article_created;
	public $article_createdByText;
	public $article_createdById;
	public $article_dateModified;	
}
?>