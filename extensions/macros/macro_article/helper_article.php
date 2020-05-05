<?php
class articleHelper{
	public $shared;
	public function __construct(){
	
	 $this->shared=System::shared("articles");
	
	}
	public function showArticle(){
	
	 $cont=new objectString;
	  
	 $sub=1;
	 
	 if(isset($_GET['sub']))
	 $sub=System::getCheckerNumeric("sub");
	 
	 if($sub=1){
	  
	 $articles=$this->shared->getArticles("where status=1 and id=".System::getCheckerNumeric("iid"));
	  
	  for($i=0;$i<count($articles);$i++){
		  	  
	  $cont->generalTags(System::contentTitle($articles[$i]->article_title));
	  
	  $cont->generalTags($articles[$i]->article_text);
	  
	  }
	  
	 }else{
		 
	 }
	  
	  return $cont->toString();
	
	}
	 
}
?>