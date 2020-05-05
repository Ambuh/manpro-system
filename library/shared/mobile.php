<?php
  function mobile(){
      return new mobile_class();
  }

  class mobile_class{
      private $db;
      private $QLib;
      private $qr;

       function __construct(){

        GLOBAL $db;
        $this->db=$db;
        $this->qr=new Hquince();
        $this->QLib=System::shared("proman_lib");
      }
      public function projectList(){
        $list=new objectString();

        $proj=$this->QLib->getProjects();
		
        $managers=$this->qr->getProjectManagers();
        
        for($i=0;$i<count($proj);$i++){
            $list->generalTags("<div class='a3-left a3-round a3-border a3-padding a3-margin-bottom a3-boxShadow'>");

            $list->generalTags("<label class='a3-left a3-full'>Created : ".$proj[$i]->project_entryDate."</label>");

            $list->generalTags("<label class='a3-left a3-full'>Project Title : ".$proj[$i]->project_name."</label>");

            $list->generalTags("<label class='a3-left a3-full'>Location  :  ".$proj[$i]->project_location."</label>");

            $list->generalTags("<div class='vReq' id='exp_".$proj[$i]->project_id."'></div>");

            $list->generalTags("</div>");
        }

        return $list->toString();
      }
	  public function listComponents($id){
		
		  $list=new objectString();
		
		  $ws=$this->QLib->getWorkSchedule('where projectId='.$id.' ');
		
		for($i=0;$i<count($ws);$i++){
			if(($ww=$ws[$i]->wk_remaining>-1 ? $ws[$i]->wk_remaining : 0)==0)
			   $list->generalTags("<div class='a3-border-red a3-round a3-left a3-padding a3-margin a3-boxShadow-red'>");
			else
				$list->generalTags("<div class='a3-border a3-round a3-left a3-padding a3-margin a3-boxShadow'>");
		  
			  $list->generalTags("<label class='a3-left a3-full'> Component Name : <b>".$ws[$i]->wk_description."</b></label>");

			  $list->generalTags("<label class='a3-left a3-full'> From Date : <b>".$ws[$i]->wk_frmDate."</b></label>");

			  $list->generalTags("<label class='a3-left a3-full'> Duration : <b>" .($wk=$ws[$i]->wk_days>-1 ? $ws[$i]->wk_days : 0)." days</b></label>");

			  $list->generalTags("<label class='a3-left a3-full'> Remaining  : <b>".($ww=$ws[$i]->wk_remaining>-1 ? $ws[$i]->wk_remaining : 0)." days</b></label>");

			  //$list->generalTags("<span class='a3-left a3-full'> Progress  :<b>".$this->QLib->getMaterialUsage($ws[$i]->wk_id)."%</b></span>");
			
			  $list->generalTags("<div id='sm_".$ws[$i]->wk_id."' class='schSHw a3-right'></div>");

			  $list->generalTags("<div class='lImage' id='uci_m-".$ws[$i]->wk_id."' title='view Upload images' ></div>");

			  $list->generalTags("</div>");
		}
		
		return $list->toString();

		
	}
	  public function listTasks($id){
		  $as =System::shared("assist");

		  $list=new objectString();
		
		  $ws=$as->getTasks('where dadid='.$id);
		
			for($i=0;$i<count($ws);$i++){
				$list->generalTags("<div class='a3-left a3-border a3-padding a3-margin a3-round'>");
				
				$list->generalTags("<label class='a3-full a3-left'>Task Name :<b>".$ws[$i]->tk_description."</b></label>");
		  
				$list->generalTags("<label class='a3-full a3-left'>Start Date :<b>".$ws[$i]->tk_stDate."</b></label>");
		  
				$list->generalTags("<label class='a3-full a3-left'>Remaining Days :<b>".($wk=$ws[$i]->tk_time>-1 ? $ws[$i]->tk_time : 0). ' days'."</b></label>");
		 
				$list->generalTags("</div>");

			}
		  return $list->toString();
	}
	  public function  requisitions ($id){
		  
	  }

  }

?>