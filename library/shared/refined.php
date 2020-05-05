<?php
  function refined(){
	  return new ref_class();
  }
  class ref_class{
	 private $db;
	public $ud;
	private $um;
	public $cmp;
	  public $Qlib;
	  public $HQuince;
	  function __construct(){
		  GLOBAL $db;$this->db=$db;
		  $this->Qlib=System::shared("proman_lib");
		
		  $this->um=System::shared('usermanager');
		
	    $this->Qlib->getUserDetails($this->ud);
		
	    $this->cmp=$this->Qlib->getCompany($this->ud->user_company);
		
		$this->db->setTablePrefix($this->cmp->company_prefix);
		  
		  $this->HQuince=new HQuince();
	  }
	  public function shcDelete($id){
		  $this->db->deleteQuery("pr_workschedule","where id='".$id."'");
	  }
	  public function projectLayout(){
		  $cont=new objectString();
		  
		  $data=array();$data=$this->Qlib->getProjects();
		  
		  
		  
		  $cont->generalTags("<div class='w3-left w3-full'>");
		  
		  $cont->generalTags("<div class='w3-left w3-half'>Active Sites</div>");
		  
		  $cont->generalTags("<div class='w3-right w3-half'><span class='w3-left w3-padding'>Search:</span><input type='text' class='txtField'></div>");
		  
		  $cont->generalTags("<div class='w3-left w3-full w3-container pj-handler'>");
		  
		  $cont->generalTags($this->cProject());
		  
		  for($i=0;$i<count($data);$i++){
			   $cont->generalTags($this->projectDisp($data[$i]));
		  }
		  
		 
		  
		  $cont->generalTags("</div>");
		  
		  $cont->generalTags("</div>");
		  
		  return $cont->toString();
	  }
	  private function projectDisp($item){
		  $cont=new objectString();

		  $cont->generalTags("<div class='pj-info w3-left w3-cont w3-round' id='pj_".$item->project_id."'>");
		  
		  $cont->generalTags("<div class='pj-image w3-left w3-full'></div>");
		  
		  $cont->generalTags("<div class='pj-progress w3-left w3-full'><span></span></div>");
		  
		  $cont->generalTags("<div class='pj-detail w3-left w3-full'>");
		  
		  $cont->generalTags("<p>".$item->project_name."</p>");
		  
		  $cont->generalTags("<span>".$item->project_location."</span>");
		  
		  $cont->generalTags("</div>");
		  
		  $cont->generalTags("</div>");
		  return $cont->toString();
	  }
	  private function  cProject(){
		  $cont=new objectString();

		  $cont->generalTags("<div class='pj-info w3-left w3-cont w3-round pj-creator'>");
		  
		  $cont->generalTags("<p>+</p><span>New Project</span>");
		  
		  $cont->generalTags("</div>");
		  return $cont->toString();
	  }
	  public function projectMenus(){
		  $cont=new objectString;
		
		if($this->Qlib->positionHasPrivilege($this->ud->user_userType,-2)|$this->ud->user_id==1){
           $cont->generalTags('<div class="men_tab2" id="inMen_1">List Projects</div><div class="men_tab2" id="inMen_2" style="background:#acf7aa;">+ New Project</div>');
		}elseif($this->Qlib->positionHasPrivilege($this->ud->user_userType,2)){
		   $cont->generalTags('<div class="men_tab2" id="inMen_1">List Projects</div>');
		}
		
        return $cont->toString();
	  }
	  public function projectDetails($id){
		 return $this->HQuince->displayProject($id);
	  }
	  public function projectSMenus($id=''){
		  $cont=new objectString();
		  
		  $cont->generalTags("<div class=''>View Projects</div>");
		  
		  $cont->generalTags("<div class=''>Back</div>");
		  
		  return $cont->toString();
	  }							 
  }

?>