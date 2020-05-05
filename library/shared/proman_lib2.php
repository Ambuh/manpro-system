<?php
function proman_lib(){
    return new theProLib;
}
class theProLib{
	private $db;
	public $ud;
	private $um;
	public $cmp;
    public function __construct(){
		GLOBAL $db;
        $this->db=$db;
		//$this->db->selectQuery(array('*'),'pr_requisitions',$whereclause);
		
		$this->um=System::shared('usermanager');
		
	    $this->getUserDetails($this->ud);
		
	    $this->cmp=$this->getCompany($this->ud->user_company);
		
		$this->db->setTablePrefix($this->cmp->company_prefix);
		
    }
	public function getUserDetails(&$usd){
		$lVals=null;
		
		if(isset($_SESSION[System::getSessionPrefix()."USER_LOGGED"]))
		$lVals=explode('_',$_SESSION[System::getSessionPrefix()."USER_LOGGED"]);
		$usd=new uDetails;
		
		$usd->user_id=System::getArrayElementValue($lVals,1,0);
		$usd->user_name=System::getArrayElementValue($lVals,2,'');
		$usd->user_userType=System::getArrayElementValue($lVals,3,0);
		$usd->user_userEmail=System::getArrayElementValue($lVals,6,0);
		$usd->user_company=System::getArrayElementValue($lVals,5,0);
		//echo $usd->user_userType.'<br/>';
	}
    public function quinceMenu(){
        
        $cont=new objectString;
        
        $cont->generalTags('<div class="menu_wrapp">');
        
		$cont->generalTags('<div class="innlist_wrap">');
		
        $cont->generalTags('<div class="men_header">Menu<div class="tggle_m"><div class="retR"></div></div></div>');
        
        $cont->generalTags('<div class="menu_item" id="m_1"><a href="#">Dashboard</a></div>');
		
		if($this->positionHasPrivilege($this->ud->user_userType,2)|($this->ud->user_id==1))
         $cont->generalTags('<div class="menu_item" id="m_2"><a href="#">Manage Projects</a></div>');
       
		
		if($this->positionHasPrivilege($this->ud->user_userType,4)|($this->ud->user_id==1))
         $cont->generalTags('<div class="menu_item" id="m_4"><a href="#">Material Requisition</a><div class="at_icon" id="al_4">12</div></div>');
		
		if($this->positionHasPrivilege($this->ud->user_userType,22)|($this->ud->user_id==1))
		$cont->generalTags('<div class="menu_item" id="m_22"><a href="#">Office Requisition</a><div class="at_icon" id="al_22"></div></div>');
		
		if($this->positionHasPrivilege($this->ud->user_userType,22)|($this->ud->user_id==1))
		$cont->generalTags('<div class="menu_item" id="m_23"><a href="#">Schedule Of Works</a><div class="at_icon" id="al_23"></div></div>');
		
		if($this->positionHasPrivilege($this->ud->user_userType,12)|($this->ud->user_id==1))		
		$cont->generalTags('<div class="menu_item" id="m_12"><a href="#">Purchases</a><div class="at_icon" id="al_12">12</div></div>');
		
		if($this->positionHasPrivilege($this->ud->user_userType,13)|($this->ud->user_id==1))
		$cont->generalTags('<div class="menu_item" id="m_13"><a href="#">Price Verification</a><div class="at_icon" id="al_13"></div></div>');
		
		//|($this->ud->user_userType==4)
		if($this->positionHasPrivilege($this->ud->user_userType,21))
		$cont->generalTags('<div class="menu_item" id="m_21"><a href="#">Income & Expenses</a><div class="at_icon" id="al_21"></div></div>');
		
		//'.$this->notifIcon().'
	    if($this->positionHasPrivilege($this->ud->user_userType,5)|($this->ud->user_id==1))
        $cont->generalTags('<div class="menu_item" id="m_5"><a href="#">Inventory</a><div class="at_icon" id="al_5">12</div></div>');
		
		if($this->positionHasPrivilege($this->ud->user_userType,11)|($this->ud->user_id==1))
		$cont->generalTags('<div class="menu_item" id="m_11"><a href="#">Plant & Equipment</a><div class="at_icon" id="al_11">12</div></div>');
		
		if($this->positionHasPrivilege($this->ud->user_userType,10)|($this->ud->user_id==1))
		$cont->generalTags('<div class="menu_item" id="m_10"><a href="#">Labour</a><div class="at_icon" id="al_10">12</div></div>');
		
		//$cont->generalTags('<div class="menu_item" id="m_11"><a href="#">Equipment</a></div>');
		
		if($this->positionHasPrivilege($this->ud->user_userType,7)|($this->ud->user_id==1))
        $cont->generalTags('<div class="menu_item" id="m_7"><a href="#">Reports</a></div>');
		
		if($this->positionHasPrivilege($this->ud->user_userType,8)|($this->ud->user_id==1))
		 $cont->generalTags('<div class="menu_item" id="m_8"><a href="#">Users & Privileges</a></div>');
        
		if($this->positionHasPrivilege($this->ud->user_userType,9)|($this->ud->user_id==1))
		  $cont->generalTags('<div class="menu_item" id="m_9"><a href="#">Settings</a></div>');
		
        //$cont->generalTags('<div class="menu_item" id="m_6"><a href="#">Sign Out</a></div>');
        
        $cont->generalTags('<div class="men_header">Group Info</div>');
        
		$cont->generalTags('</div>');
		
        $cont->generalTags('</div>');
        
        return $cont->toString();
        
    }
	public function getUserType($type=0){
		
		$user_types=array('Construction Manager','General Manager','Procurement','Accountant','Director','Administrator');
		
		$user_types[-2]="Store Clerk";
		
		return $user_types[$type];
		
	}

	private function notifIcon($iconId=0){
		return '<div class="nt_ico" id="'.$iconId.'">23</div>';
	}
	//-------------------------------------------SEARCH REQUEST ITEMS-------------------------
	public function getSearchItems($whereclause=""){
		
		$res=$this->db->selectQueryJoin(array('a.id as id','a.description as descr','a.qty as qty','a.unit as unit','a.site_id as site_id','a.request_id as request_id','b.requisition_no as requisition_no','b.partno as partno','date_format(b.req_date,"%d/%b/%Y") as req_date','b.byName as byName'),array('pr_requisitionitems a,pr_requisitions b'),'where a.request_id=b.id and a.qty>0 '.$whereclause.' order by b.req_date desc limit 50');
		
		$results=array();
		
		while($row=mysqli_fetch_assoc($res)){
			$rq=new RItemSearch;
			$rq->item_description=$row['descr'];
	        $rq->item_requestDate=$row['req_date'];
	        $rq->item_rNumber=$row['requisition_no'].$row['partno'];
	        $rq->item_rId=$row['id'];
	        $rq->item_qty=$row['qty'];
	        $rq->item_unit=$row['unit'];
	        $rq->item_siteId=$row['site_id'];
			$rq->item_byName=$row['byName'];
			$results[]=$rq;
		}
		
		return $results;
		
	}
	//-------------------------------------------END SEARCH REQUEST---------------------------
	//-------------------------------------------Labour entries requests----------------------	
	public function setLabourResetRequest($site_id,$theDate,$ltype){
		
		$rq=$this->getLabourResetRequest('where labour_type='.$ltype.' and date(labour_entrydate)=date('.$this->dateFormatForDb($theDate).') and site_id='.$site_id.' and status=0');
		
		if(count($rq)>0)
			return new name_value(false,System::getWarningText("Labour reset request already exists!"));
		
		$this->db->insertQuery(array('site_id','labour_entrydate','labour_type','byname','byid'),'pr_resetLabourRequest',array($site_id,$this->dateFormatForDb($theDate),$ltype,"'".$this->ud->user_name."'",$this->ud->user_id));
		
		return new name_value(true,System::successText("Request submitted successfully!"));
		
	}
	public function changeLabourREStatus($id,$status=0){
		
		$this->db->updateQuery(array("status=".$status,"processed_byname='".$this->ud->user_name."'","processed_by=".$this->ud->user_id),'pr_resetLabourRequest',"where id=".$id);
		
	}
	public function getLabourResetRequest($whereclause="",$opt=0){
	
	$res=null;
	
	if($opt==0){	
	 $res=$this->db->selectQuery(array('date_format(requestdate,"%d-%b-%Y"),site_id,byname,date_format(labour_entrydate,"%d-%b-%Y"),status,labour_type,byid, processed_by,processed_byname,id'),'pr_resetLabourRequest',$whereclause);
	}else{
	 $res=$this->db->selectQuery(array('date_format(requestdate,"%d-%m-%Y"),site_id,byname,date_format(labour_entrydate,"%d-%m-%Y"),status,labour_type,byid, processed_by,processed_byname,id'),'pr_resetLabourRequest',$whereclause);	
	}
	 $request=array();
		while($row=mysqli_fetch_row($res)){
		  $req =new resetLabour;
		  $req->request_id=$row[9];
		  $req->request_entry=$row[3];
		  $req->request_date=$row[0];
		  $req->request_byName=$row[2];
		  $req->request_byId=$row[6];
		  $req->request_siteId=$row[1];
		  $req->request_status=$row[4];
		  $req->request_labourType=$row[5];
		  $req->request_processedByName=$row[7];
	      $req->request_processedById=$row[8];
		  $request[]=$req;
	    }
	  
	 return $request;
		
	}
	//--------------------------------------------End Labour----------------------------------
    public function showCSites(){
        
        $cont=new objectString;
        
		$cont->generalTags('<div class="q_row"><div class="innerTitle">Active Sites</div></div>');
		
        $cont->generalTags('<div class="q_row">');
        
		$cont->generalTags($this->showConList());
		
        $cont->generalTags('</div>');
        
        return $cont->toString();
        
    }
	public function showConList(){
		
		$list=new open_table;
		
		$list->setColumnNames(array('No','Created','Site Title','Project'));
		
		$list->setColumnWidths(array('10%','26%','37%','25%','20%'));
		
		$sites=$this->getSites('',true);
		
		for($i=0;$i<count($sites);$i++){
			$list->addItem(array($i+1,$sites[$i]->site_created,$sites[$i]->site_name,$sites[$i]->site_project));
		}
		
		$list->setHoverColor('#cbe3f8');
		
		$list->showTable();
		
		return $list->toString();
		
	}
    public function showMembers(){
        
        $cont=new objectString;
        
        $inp=new input;
        
        $inp->setClass('quince_input');
        
        $inp->setId('findM');
        
        $inp->input('text','qinp','');
        
        $cont->generalTags('<div class="q_row" style="margin-top:15px;"><div id="label">Find Member</div>'.$inp->toString().'</div>');
        
        $cont->generalTags('<div class="q_row" id="membContent" style="margin-top:15px;">');
        
        $cont->generalTags($this->viewMembersList());
        
        $cont->generalTags('</div>');
        
        return $cont->toString();
        
    }
    public function showReports(){
        
    }
    public function investmentList(){
        
        $cont=new objectString;
        
        $list=new open_table;
        
        $list->setColumnNames(array('No.','Description','Status','Investment Type','Investment Amount'));
        
        $list->setColumnWidths(array('10%','40%','10%','20%','18%'));
        
        $list->setHeaderFontBold();
        
        $list->showTable();
        
        $cont->generalTags($list->toString());
        
        return $cont->toString();
        
    }
	//---------------------------------PRINT PREVIEW------------------------------------
	public function savePrintPreview($style,$colName,$data,$mdata,$mstyle){
		
		//$pr=$this->getPrintPreview($this->ud->user_id);
		
		$this->deletePreview($this->ud->user_id);
		
		$this->db->insertQuery(array('user_id','columnstyles','colNames','thedata','themdata'),'pr_printbank',array($this->ud->user_id,"'".$style."'","'".str_replace("'","''",$colName)."'","'".$mstyle."'","'".str_replace("'","''",$mdata)."'"));
		
		file_put_contents(dirname(__FILE__).'/../../printpreview/'.$this->ud->user_id,$data);
		
		file_put_contents(dirname(__FILE__).'/../../printpreview/mdata_'.$this->ud->user_id,$mdata);
		
		file_put_contents(dirname(__FILE__).'/../../printpreview/mst_'.$this->ud->user_id,$mstyle);
		
	}
	public function getPrintPreview($id){
		$res=$this->db->selectQuery(array("*"),'pr_printbank','where user_id='.$id);
		$pr=array();
		while($row=mysqli_fetch_row($res)){
			$print=new previewData;
			$print->print_userId=$row[0];
			$print->print_colStyle=$row[1];
			$print->print_colNames=$row[2];
			$print->print_colValue=file_get_contents(dirname(__FILE__).'/../../printpreview/'.$this->ud->user_id);//$row[3];
			$print->print_title=$row[4];
			$print->print_mData=file_get_contents(dirname(__FILE__).'/../../printpreview/mdata_'.$this->ud->user_id);
			$print->print_mStyle=file_get_contents(dirname(__FILE__).'/../../printpreview/mst_'.$this->ud->user_id);
			$pr[]=$print;
		}
		return $pr;
	}
	public function deletePreview($id){
		
		$this->db->deleteQuery('pr_printbank','where user_id='.$id);
		
	}
	//---------------------------------END PRINT PRIVIEW--------------------------------
	public function uploadMpFile($name,$id){
		$path_part=pathinfo($_FILES[$name]['name']);
		move_uploaded_file($_FILES[$name]['tmp_name'],dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/mp-pdf-".$id.'_'.$path_part['extension']);
		$this->updateReqFileExt($id,$path_part['extension']);
		$this->updateRequestLevel($id,5,1);
	}
	public function updateReqFileExt($id,$ext=""){
		
		$this->db->updateQuery(array("extType='".$ext."'"),'pr_requisitions','where id='.$id);
			
	}
	public function uploadLabourFile($name,$id){
		move_uploaded_file($_FILES[$name]['tmp_name'],dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/lb-pdf-".$id);
	}
	/*public function showScannedMp($id,$opt=0,$extn=0){
		
		switch($opt){
			case 1: case 2:
			$mt=header('Content-Type: application/pdf');
			$mt=file_get_contents(dirname(__FILE__)."/../../mpFile/lb-pdf-".$id);
			return $mt;
			break;
				
			case 3:
			$mt.=header('Content-Type: application/pdf');
			$rq=$this->getRequisitions('where id='.$id);
			for($i=0;$i<count($rq);$i++){	
			 $mt=file_get_contents(dirname(__FILE__)."/../../mpFile/mp-pdf-".$id.'_'.$rq[$i]->req_extType);
			}
			return $mt;
			break;
			
			default:
			$rq=$this->getRequisitions('where id='.$id);
			for($i=0;$i<count($rq);$i++){	
			  $ext="";
			  if($rq[$i]->req_extType!="")
				 if(is_array(json_decode($rq[$i]->req_extType))){
				  $ext='_'.json_decode($rq[$i]->req_extType)[$extn];	 
				 }else{
				  $ext='_'.$rq[$i]->req_extType;
				 }
			   $mt=header('Content-Type:image/'.$rq[$i]->req_extType);
			   $mt.=file_get_contents(dirname(__FILE__)."/../../mpFile/mp-pdf-".$id.$ext);
		       return $mt;
			}
		}
	}*/
	public function showScannedMp($id,$opt=0,$extn=0){
		
		switch($opt){
			case 1: case 2:
			$mt=header('Content-Type: application/pdf');
			$mt=file_get_contents(dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/lb-pdf-".$id);
			return $mt;
			break;
				
			case 3:
			$mt=header('Content-Type: application/pdf');
			$rq=$this->getRequisitions('where id='.$id);
			for($i=0;$i<count($rq);$i++){
			 $extype="";
			 if($rq[$i]->req_extType!="")
				$extype='_'.$rq[$i]->req_extType;
				
			 $mt=file_get_contents(dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/mp-pdf-".$id.$extype);
			}
			return $mt;
			break;
			
			case 4:
				
				if(isset($_GET['fd'])){
					header('Content-Transfer-Encoding: binary');
					header('Content-Disposition: attachment; filename=fm-mp-'.$id.'.png');
				}
				$mt=null;
				header('Content-Type:image/png');
				//include_once(dirname(__FILE__)."/../../mpFile/show.php");
			    readfile(dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/fm-mp-".$id);
			    break;
				
			case 5:
				//ini_set('display_errors','on');
				$mt=header('Content-Type: application/pdf');
				//echo $this->cmp->company_prefix;
				readfile(dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/fm-mp-".$id);
				break;
				
			case 6:
				$mt=header('Content-Type:image/'.$rq[$i]->req_extType);
			    $mt.=file_get_contents(dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/mp-pdf-".$id.$ext);
				return $mt;
			    break;
			
			default:
			$rq=$this->getRequisitions('where id='.$id);
			for($i=0;$i<count($rq);$i++){	
			  $ext="";
			  if($rq[$i]->req_extType!="")
				 if(is_array(json_decode($rq[$i]->req_extType))){
				  $ext='_'.json_decode($rq[$i]->req_extType)[$extn];	 
				 }else{
				  $ext='_'.$rq[$i]->req_extType;
				 }
			    if(isset($_GET['fd'])){
					header('Content-Transfer-Encoding: binary');
					header('Content-Disposition: attachment; filename=mp-pdf-'.$id.str_replace("_",".",$ext));
				}
			   $mt=header('Content-Type:image/'.$rq[$i]->req_extType);
			   $mt.=file_get_contents(dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/mp-pdf-".$id.$ext);
		       return $mt;
			}
		}
	}
	public function fileExists($id,$opt='pdfDoc'){

		switch($opt){
			case 'llpdf':
			return file_exists(dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/lb-pdf-".$id);	
			break;
			
			case 'fmPdf':
			 return file_exists(dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/fm-mp-".$id);
				
			default:
			$rq=$this->getRequisitions('where id='.$id);
			for($i=0;$i<count($rq);$i++){	
			  $ext="";
			  if($rq[$i]->req_extType!="")
				 $ext='_'.$rq[$i]->req_extType;
				
		      return file_exists(dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/mp-pdf-".$id.$ext);
			}
		}
	}
    public function viewPaymentHistory($userId=0,$limit="Limit 5"){
        
        $cont=new objectString;
        
        $payHist=new open_table;
        
        $payHist->setColumnNames(array("Date","Description","Status","Mode Of Payment"));
        
        $payHist->setColumnWidths(array("15%","40%","15%","28%"));
        
        $payHist->setHeaderfontBold();
        
        $payHist->showTable();
        
        $cont->generalTags($payHist->toString());
        
        return $cont->toString();
        
    }
    public function viewMembersList($whereclause=""){
        
        $cont=new objectString;
        
        $ml=new open_table;
        
        $ml->setColumnNames(array('No.','Member\'s Name','Status','Created','Last login'));
        
        $ml->setColumnWidths(array('10%','30%','15%','15%','20%'));
        
        $ml->setHeaderFontBold();
        
        $ml->showTable();
        
        $cont->generalTags($ml->toString());
        
        return $cont->toString();
        
    }
    public function assetForm(){
        
        $cont=new objectString; 
        
        $cont->generalTags('<div class="q_row">');
        
        $cont->generalTags('<div class="quinceInner" style="margin-top:15px;padding-bottom:20px;">');
        
        $cont->generalTags('<div class="proc"></div>');
        
        $fs=new input;
        
        $fs->setClass('quince_select');
        
        $fs->addItems(array(new name_value('Capital Injection',1),new name_value('Other',2)));
        
        $fs->select('select_source');
        
        $cont->generalTags('<div class="q_row" style="margin-top:20px;"><div id="label">Funding</div><div class="qs_wrap">'.$fs->toString().'</div></div>');
        
        $invNo=new input;
        
        $invNo->setTagOptions('style="margin-left:2px;"');
        
        $invNo->setClass('quince_input');
        
        $invNo->input('text','inp');
        
        $cont->generalTags('<div class="q_row" style="margin-top:20px;"><div id="label">Inventory No.</div>'.$invNo->toString().'</div>');
        
        $desc=new input;
        
        $desc->setClass('quince_input');
        
        $desc->setTagOptions('style="margin-left:2px;width:250px;"');
        
        $desc->input('text','desc');
        
        $cont->generalTags('<div class="q_row" style="margin-top:20px;"><div id="label">Description</div>'.$desc->toString().'</div>');
        
        $amt=new input;
        
        $amt->setClass('quince_input');
        
        $amt->setTagOptions('style="margin-left:2px;"');
        
        $amt->input('text','amount','');
        
        $cont->generalTags('<div class="q_row" style="margin-top:20px;"><div id="label">Amount</div>'.$amt->toString().'</div>');
        
        $cont->generalTags('<div class="q_row" style="margin-top:20px;"><div class="men_tab">Save</div><div class="men_tab">Cancel</div></div>');
        
        $cont->generalTags('</div>');
        
        $cont->generalTags('</div>');
        
        return $cont->toString();
        
    }
    public function viewAssetsList($whereclause=""){
        
        $cont=new objectString;
        
        $al=new open_table;
        
        $al->setColumnNames(array('No.','Id','Description','Purchase Date','Status','Aging','Asset Value'));
        
        $al->setColumnWidths(array('5%','10%','30%','15%','10%','10%','10%'));
        
        $al->setHeaderFontBold();
        
        $al->showTable();
        
        $cont->generalTags($al->toString());
        
        return $cont->toString();
        
    }
	   public function viewStatement(){
        
        $cont=new objectString;
        
        $list=new open_table;
        
        $list->setColumnNames(array('Date','Description','Ref.','Withdrawals','Deposits','Balance'));
        
        $list->setColumnWidths(array('15%','30%','15%','15%','15%','15'));
        
        $list->showTable();
        
        $cont->generalTags($list->toString());
        
        return $cont->toString();
        
    }
    public function accountStatement($whereclause=""){
        
    }
	public function settingsMenu(){
		
		$cont=new objectString;
		
		if($this->ud->user_userType==5){
		  // $cont->generalTags('<div class="men_tab2" id="inMen_29">General Settings</div><div class="men_tab2" id="inMen_30" style="background:#04491B;color:#fff;">Advanced Email/SMS Settings</div>');
		}
		return $cont->toString();
	}
    public function requisitionMenu(){
        
        $cont=new objectString;
        
		//echo count($this->getMyActiveSite())
				
				if($this->positionHasPrivilege($this->ud->user_userType,4))
				$cont->generalTags('<div class="men_tab2" id="inMen_7">Requisitions</div>');
			
			    if($this->positionHasPrivilege($this->ud->user_userType,50))
				$cont->generalTags('<div id="inMen_8" class="men_tab2" style="background:#acf7aa;">+ New Requisition</div>');
				
				if($this->positionHasPrivilege($this->ud->user_userType,51))
				$cont->generalTags('<div class="men_tab2" id="inMen_22" style="background:#078448;color:#fff;">+Upload Scanned Copy</div>');
				//<div class="men_tab2" id="inMen_21">Material Requests</div><div class="men_tab2" id="inMen_22" style="background:#078448;color:#fff;">+Upload Request</div>
			    
        return $cont->toString();
        
    }
    public function assetInvestMenu(){
        
        $cont=new objectString;
        
        //$cont->generalTags('<div class="men_tab2" id="inMen_3">List Assets/Invst.</div><div class="men_tab2" id="inMen_4">+ New</div>');
        
        return $cont->toString();
        
    }
    public function projectMenu(){
        
        $cont=new objectString;
		
		if($this->positionHasPrivilege($this->ud->user_userType,-2)|$this->ud->user_id==1){
           $cont->generalTags('<div class="men_tab2" id="inMen_1">List Projects</div><div class="men_tab2" id="inMen_2" style="background:#acf7aa;">+ New Project</div>');
		}elseif($this->positionHasPrivilege($this->ud->user_userType,2)){
		   $cont->generalTags('<div class="men_tab2" id="inMen_1">List Projects</div>');
		}
		
        return $cont->toString();
        
    }
	public function companyMasterMenu(){
		
		$cont=new objectString;
		
		$cont->generalTags('<div class="men_tab2" id="inMen_100">List Companies</div><div class="men_tab2" id="inMen_112" style="background:#acf7aa;">Refresh tables</div>');
		
		return $cont->toString();
		
	}
    public function sitesMenu(){
        
        $cont=new objectString;
        
        $cont->generalTags('<div class="men_tab2" id="inMen_5">View Sites</div><div class="men_tab2" id="inMen_6" style="background:#acf7aa;">+ New Site</div>');
        
        return $cont->toString();    
    }
	public function labourMenu(){
        
        $cont=new objectString;
        
        $cont->generalTags('<div class="men_tab2" id="inMen_14">Labour Entries</div>');
        
		if($this->ud->user_userType==-4)
		   $cont->generalTags('<div class="men_tab2" id="inMen_16" style="color:#fff;background:#b72010;">X Deletion Requests</div>');	
		//<div class="men_tab2" id="inMen_15" style="background:#acf7aa;">Manage Entries</div>
        return $cont->toString();    
    }
	public function purchaseMenu(){
        
        $cont=new objectString;
         if($this->positionHasPrivilege($this->ud->user_userType,12))
          $cont->generalTags('<div class="men_tab2" id="inMen_12">View Purchases</div>');
		
		if($this->positionHasPrivilege($this->ud->user_userType,54))
		   $cont->generalTags('<div class="men_tab2" id="inMen_13" style="background:#acf7aa;">Record Purchases</div>');
		
		if($this->positionHasPrivilege($this->ud->user_userType,55))
		   $cont->generalTags('<div class="men_tab2" id="inMen_20" style="background:#06680d;color:#fff;"><div id="tm_20" class="at_icon"></div>Overall Purchases</div>');
		
		if($this->positionHasPrivilege($this->ud->user_userType,56))
		   $cont->generalTags('<div class="men_tab2" id="inMen_35" style="background:#0E5ECB;color:#fff;"><div id="tm_20" class="at_icon"></div>Overall Petty Cash Purchases</div>');
		
		 
        return $cont->toString();    
    }
	public function inventoryMenu(){
		
		$cont=new objectString;
			
		  if($this->positionHasPrivilege($this->ud->user_userType,5)|($this->ud->user_id==1))
            $cont->generalTags('<div class="men_tab2" id="inMen_9"><div id="tm_9" class="at_icon"></div>View Inventory</div>');
		
		  if($this->positionHasPrivilege($this->ud->user_userType,80)|($this->ud->user_id==1))
			 $cont->generalTags('<div class="men_tab2" id="inMen_42" style="background:#145075;color:#fff;"><div id="tm_42" class="at_icon"></div>Issue Items</div>');
		
		  if($this->positionHasPrivilege($this->ud->user_userType,57)|($this->ud->user_id==1))
            $cont->generalTags('<div class="men_tab2" id="inMen_10" style="background:#acf7aa;">+From Requisition</div>');
		
		  if($this->positionHasPrivilege($this->ud->user_userType,58)|($this->ud->user_id==1))
            $cont->generalTags('<div class="men_tab2" id="inMen_11" style="background:#acf7aa;">+ Local Purchases</div>');
			
		  if($this->positionHasPrivilege($this->ud->user_userType,59)|($this->ud->user_id==1))
            $cont->generalTags('<div class="men_tab2" id="inMen_19" style="background:#444;color:#fff;"><div id="tm_19" class="at_icon"></div>Overall Received</div>');
			  
			//<div class="men_tab2" id="inMen_11" style="background:#acf7aa;">+ Receive From Local Purchases</div>
			
		/*}else{
		   if($this->($this->ud->user_userType,58))
			   $cont->generalTags('<div class="men_tab2" id="inMen_9"><div id="tm_9" class="at_icon"></div>View Inventory</div>');
		   }
		}*/
        return $cont->toString(); 
		
	}
	public function usersMenu(){
		$cont=new objectString();
		
		$cont->generalTags('<div class="men_tab2" id="inMen_25">Manage Users</div><div class="men_tab2" id="inMen_39" >Positions And Privileges</div><div class="men_tab2" id="inMen_40" style="background:#f37f18;color:#fff;">Approval Levels</div><div class="men_tab2" id="inMen_26" style="background:#444;color:#fff;">Access Log</div>');
		
		return $cont->toString();
		
	}
	public function equipmentMenu(){
		
		$cont=new objectString();
		
		$cont->generalTags('<div class="men_tab2" id="inMen_15" >View Equipment</div>');
		
		if($this->ud->user_userType==5)
		$cont->generalTags('<div class="men_tab2" id="inMen_18" style="background:#1e99e6;color:#fff;">Transfer Requests</div><div class="men_tab2" id="inMen_17" style="background:#9df3b8;">+Add Equipment</div>');
		
		return $cont->toString();
		
	}
	public function officeProcurementMenu(){
		
		$cont=new objectString;
		
		if($this->positionHasPrivilege($this->ud->user_userType,22))		 
		 $cont->generalTags('<div class="men_tab2" id="inMen_31" >View Office Requisitioins</div>');
		   
		if($this->positionHasPrivilege($this->ud->user_userType,53))
		 $cont->generalTags('<div class="men_tab2" id="inMen_32" style="background:#acf7aa;">+ New Office Requisition</div>');
		 		
		return $cont->toString();
		
	}
	public function expenseIncomeMenu(){
		
		$cont=new objectString;
		if($this->positionHasPrivilege($this->ud->user_userType,21))
			$cont->generalTags('<div class="men_tab2" id="inMen_33" style="background:#444;color:#fff;">Income</div>');
			
		//#145075
		return $cont->toString();
		
	}
	public function updateEmailPhone($email,$phone="",$user_id=0){
		
		if($email=="")
		  return new name_value(false,System::getWarningText('Failed: Email address cannot be empty!'));
		
		$emails=$this->ud->user_userEmail;
		
		if($user_id>0){
		  $us=$this->um->getUsers('where id='.$user_id);
		  for($i=0;$i<count($us);$i++){
		   	 $emails=$us[$i]->user_username;
		  }
		}
		if($emails!=$email){
			$res=$this->db->selectQuery(array('*'),'frontend_users',"where username='".$email."'",'','');
			while($row=mysqli_fetch_row($res)){
			 return new name_value(false,System::getWarningText('Failed: Email address already in use!'));
			}
			if($user_id==0){
			   $this->db->updateQuery(array("username='".$email."'","phone_number='".$phone."'"),'frontend_users','where id='.$this->ud->user_id,'');		
			   unset($_SESSION[System::getSessionPrefix()."USER_LOGGED"]);
			}else{
				$this->db->updateQuery(array("username='".$email."'","phone_number='".$phone."'"),'frontend_users','where id='.$user_id,'');
			
			}
			return new name_value(true,System::successText('Details updated successfully.'),'Refresh');
		}else{
			$this->db->updateQuery(array("phone_number='".$phone."'"),'frontend_users','where id='.$this->ud->user_id,'');
			return new name_value(true,System::successText('Details updated successfully.'));
		}
		
	}
	public function getUserPhone($id){
		$res=$this->db->selectQuery(array('phone_number'),'frontend_users','where id='.$id,'');
		while($row=mysqli_fetch_row($res)){
			return $row[0];
		}
	}
	public function verifyMenu(){
		$cont=new objectString;
		if($this->ud->user_userType==4){
		   $cont->generalTags('<div class="men_tab2" id="inMen_27">Verify Purchase Prices</div>');
		   //<div class="men_tab2" id="inMen_28" style="color:#fff;background:#777;">Verify Received Items</div>
		}else{
		   $cont->generalTags('');
		}
		return $cont->toString();
	}
	public function getNotifyType($whereclause=""){
		
		$results=array();
		
		$res=$this->db->selectQuery(array('*'),'pr_notifications',$whereclause);
		while($row=mysqli_fetch_row($res)){
		   $nt=new userNotify;
		   $nt->notify_userId=$row[0];
		   $nt->notify_email=$row[1];
		   $nt->notify_sms=$row[2];
		   $results[]=$nt;
		}
		
		return $results;
		
	}
	public function setNotificationType($id,$email,$sms){
		$nots=$this->getNotifyType('where user_id='.$id);
		if(count($nots)>0){
			$this->db->updateQuery(array('sms='.$sms,'email='.$email),'pr_notifications','where user_id='.$id);
		}else{
			$this->db->insertQuery(array('sms','email','user_id'),'pr_notifications',array($sms,$email,$id));
		}
	}
	public function getUserNotifyType($id){
		$nty=$this->getNotifyType('where user_id='.$id);
		if(count($nty)==0){
			$this->setNotificationType($id,0,0);
			$nty=$this->getNotifyType('where user_id='.$id);
		}
		for($i=0;$i<count($nty);$i++){
			return $nty[$i];
		}
	}
	public function saveEmailSettings($port,$host,$email,$password){
		$settings=array('port'=>$port,'host'=>$host,'email'=>$email,'password'=>$password);
		file_put_contents(dirname(__FILE__).'/../e_settings.mxt',json_encode($settings));
	}
	public function getEmailSettings(){
		
		if(file_exists(dirname(__FILE__).'/../e_settings.mxt')){
			return json_decode(file_get_contents(dirname(__FILE__).'/../e_settings.mxt'));
		}
		return null;
		
	}
    public function getDynamicPrivileges(){
		
		return array(new name_value('<b>PROJECT MANAGEMENT</b>',-1,-1),new name_value('View Projects',2),new name_value('Create/Edit Project',-2),new name_value('Manage BOQ',71),new name_value('Generate Components Of Work',72),new name_value('Assigned To One Project',70),new name_value('<b>REQUISITION MANAGEMENT</b>',-1,-1),new name_value('View Requisitions',4),new name_value('Approve Requisitions',-4,'slc'),new name_value('Generate Requisition',50),new name_value('Upload Scanned Copy(Field Level)',51),new name_value('Upload Scanned Copy(Approved Copy)',60),new name_value('View Scanned Copy',61),new name_value('View Office Requisition',22),new name_value('Approve Office Requisition',52),new name_value('Generate Office Requisition',53),new name_value('<b>ACCOUNTS</b>',-1,-1),new name_value('Income & Expenses',21),new name_value('<b>PURCHASE & VERIFICATION</b>',-1,-1),new name_value('View Purchases',12),new name_value('Record Purchases',54),new name_value('View Overall Purchases',55),new name_value('View Overall Petty Cash  Purchases',56),new name_value('Price Verifications',13),new name_value('<b>INVENTORY MANAGEMENT</b>',-1,-1),new name_value('Issue Items',80),new name_value('View Inventory',5),new name_value('Receive Requisition Items',57),new name_value('View Overall Received',59),new name_value('Plant & Equipment',11),new name_value('<b>LABOUR MANAGEMENT</b>',-1,-1),new name_value('View Labour Records',10),new name_value('Upload Labour Records',17),new name_value('<b>REPORTS</b>',-1,-1),new name_value('Reports',7),new name_value('<b>USER MANAGEMNT</b>',-1,-1),new name_value('View Users',8),new name_value('Create Users',-8),new name_value('<b>SETTINGS</b>',-1,-1),new name_value('Manage Settings',9),new name_value('Positions & Privileges',-39));
		
	}
	public function addRemovePrivileges($type,$status,$pos,$prefix=""){
		if($status){
			$this->db->insertQuery(array('privilege_type','position_id'),'pr_positionprivileges',array($type,$pos),'',$prefix);
		}else{
			$this->db->deleteQuery('pr_positionprivileges','where position_id='.$pos.' and privilege_type='.$type,$prefix);
		}
	}
	public function addRemovePrivileges2($type,$status,$pos){
		if($status){
			$this->db->insertQuery(array('privilege_type','level_id'),'pr_levelprivileges',array($type,$pos));
		}else{
			$this->db->deleteQuery('pr_levelprivileges','where level_id='.$pos.' and privilege_type='.$type);
		}
	}
	public function getMyDynamicPrivileges($pos_id,$swap=false){
		
		$results=array();
		
		$res=$this->db->selectQuery(array('*'),'pr_positionprivileges','where position_id='.$pos_id);
		
		while($row=mysqli_fetch_row($res)){
			
			if(!$swap){
			  $results[$row[1]]=$row[0];
			}else{
			  $results[$row[0]]=$row[1];
			}
		}
		//print_r($results);
		return $results;
		
	}
	public function levelHasPrivilege($levelId,$pType=0){
		
		$getMy=null;
		if(is_array($levelId)){
			for($i=0;$i<count($levelId);$i++){
			 $getMy=$this->getMyLevelDynamicPrivileges(System::getArrayElementValue($levelId,$i),false);
             //if(in_array($pType,$getMy)){
			 if((System::getArrayElementValue($getMy,$pType,-1)!=-1)&(System::getArrayElementValue($getMy,$pType,-1)==$levelId[$i])){
			   return true;
		     }
			}
		}else{
		$getMy=$this->getMyLevelDynamicPrivileges($levelId,false);
		  if((System::getArrayElementValue($getMy,$pType,-1)!=-1)&(System::getArrayElementValue($getMy,$pType,-1)==$levelId)){
			return true;
		  }
		}
		return false;
	}

	public function getMyLevelDynamicPrivileges($level_id){
		
		$results=array();
		
		$res=$this->db->selectQuery(array('*'),'pr_levelprivileges','where level_id='.$level_id);
		
		while($row=mysqli_fetch_row($res)){
			$results[$row[1]]=$row[0];
		}
		
		return $results;
		
	}
	public function addALevel($title,$level){
		
		$rs=$this->getALevels("where thelevel=".$level);
		
		if(count($rs)>0){
			return new name_value(false,System::getWarningText("Level already exists!"));
		}
		
		$this->db->insertQuery(array('title','thelevel'),'pr_alevels',array("'".$title."'",$level));
		
		return new name_value(true,System::successText("Level added successfully."));
		
	}
	public function getALevels($whereclause=""){
		
		$res=$this->db->selectQuery(array('*'),'pr_alevels',$whereclause);
		
		$results=array();
		
		while($row=mysqli_fetch_row($res)){
			$results[]=new name_value($row[1],$row[2],$row[0]);
		}
		
		return $results;
	}
	public function deleteALevel($id){
		$this->db->deleteQuery('pr_alevels','where id='.$id);
	}
	public function getLevelPrivileges(){
		
		return array(new name_value('<b>REQUISITION PRIVILEGES</b>',-1),new name_value('Input Description',0),new name_value('Input Unit',2),new name_value('Input Rates',3),new name_value('Input Quantity',9),new name_value('Input Remarks',4),new name_value('Show All Columns',8),new name_value('View Rates',5),new name_value('Delete Requisition',6),new name_value('Can Delete Requisition Item',7));
		
	}
	public function positionHasPrivilege($position,$type){
		
		if(in_array($type,array_keys($this->getMyDynamicPrivileges($position))))
		   return true;
		   
		   return false;
		
	}
	//-------------------------------------------------USER A LEVEL------------------------------------------
	public function addUserLevel($uid,$levelid=0){
		
		$res=$this->getUserLevels('where user_id='.$uid.' and position_id='.$levelid);
		
		if(count($res)==0)
		 $this->db->insertQuery(array('user_id','position_id'),'pr_usersalevel',array($uid,$levelid));
		
	}
	public function getPositionLevels($pid){
		
		return System::nameValueToSimpleArray($this->getUserLevels('where position_id='.$pid),true);
	
	}
	public function deleteUserLevel($uid,$level_id){
		
		$this->db->deleteQuery('pr_usersalevel','where user_id='.$uid.' and position_id='.$level_id);
		
	}
	public function getActualLevels($posIds=array()){
		
		$whereIds="";
		
		if(count($posIds)>0)
		   $whereIds="where id=".implode(' or id=',$posIds);
		
		return System::nameValueToSimpleArray($this->getAlevels($whereIds));
		
	}
	public function getPositionApprovalLevels($position=0){
		
		return $this->getUserLevels('where position_id='.$position);
		
	}
	public function getUserLevels($whereclause=""){
		
		$results=array();
		
		$al=$this->db->selectQuery(array('*'),'pr_usersalevel',$whereclause);
		
		while($row=mysqli_fetch_row($al)){
			
			$results[]=new name_value($row[1],$row[0]);
			
		}
		
		return $results;
		
	}
	public function getCurrentLevel($id){
		
		$req=$this->getRequisitions('where id='.$id);
		foreach($req as $rq)
		$reqL=array($rq->req_level0,$rq->req_level1,$rq->req_level2,$rq->req_level3,$rq->req_level4,$rq->req_level5);
		
		$lvs=$this->getALevels();
		
		for($i=0;$i<count($lvs)+1;$i++){
			if(isset($reqL[$i]))
			if($reqL[$i]==0)
			  return $i;
		}
		
		return -1;
		
	}
	//-----------------------------------------------END USER A LEVEL----------------------------------------
	//------------------------------------------SEARCH INVENTORY---------------------------------------
	public function searchInventory($whereclause){
		
		return '';
	}
	//---------------------------------------END SEARCH INVENTORY--------------------------------------
	//----------------------------------------EQUIPMENT MANAGER----------------------------------------
	public function addEquipment($reqCode,$descr="",$eqtype=0){
		return $this->db->insertQuery(array('regcode','description','equipment_type','recorded_by','recorded_byname'),'pr_equipment',array("'".$reqCode."'","'".str_replace("'","''",$descr)."'",$eqtype,$this->ud->user_id,"'".str_replace("'","''",$this->ud->user_name)."'"));
		
	}
	public function getEquipment($whereClause="",$withSite=false){
		
		$res=null;
		
		if(!$withSite){
		  $res=$this->db->selectQuery(array('*'),'pr_equipment',$whereClause);
		}else{
		  $res=$this->db->selectQueryJoin(array('a.id','a.regcode','a.description','a.recorded_by','a.recorded_byname','a.equipment_type','a.date_recorded','b.site_id'),array('pr_equipment a','pr_equiplocation b'),'where a.id=b.equip_id and b.isActive=1 '.$whereClause);
		}
		$results=array();
		
		while($row=mysqli_fetch_row($res)){
			$eq=new equip;
			$eq->e_id=$row[0];
			$eq->e_regCode=$row[1];
			$eq->e_description=$row[2];
			$eq->e_recordedById=$row[3];
			$eq->e_recordedByName=$row[4];
			$eq->e_type=$row[5];
			$eq->e_recordedDate=$row[6];
			if(isset($row[7]))
			$eq->e_site=$row[7];
			$results[]=$eq;
		}
		
		return $results;
		
	}
	public function changeELocation($e_id,$site_id){
	  
		$this->db->updateQuery(array('isActive=0'),'pr_equiplocation','where equip_id='.$e_id);
		
		$this->db->insertQuery(array('site_id','equip_id','transfer_byid','transfer_byname'),'pr_equiplocation',array($site_id,$e_id,$this->ud->user_id,"'".$this->ud->user_name."'"));
		
	}
	public function getEquipmentLocations($addedWhere=""){
		
		$res=$this->db->selectQueryJoin(array('a.site_id','a.t_date','a.transfer_byname','a.transfer_byid','b.site_title','c.regcode','c.description'),array('pr_equiplocation a','pr_sites b','pr_equipment c'),'where a.site_id=b.id and a.equip_id=c.id '.$addedWhere);
		$results=array();
		while($row=mysqli_fetch_row($res)){
			$loc=new equipLocation;
			$loc->loc_siteId=$row[0];
			$loc->loc_tDate=$row[1];
			$loc->loc_byName=$row[2];
			$loc->loc_byId=$row[3];
			$loc->loc_siteTitle=$row[4];
			$loc->loc_regCode=$row[5];
			$loc->loc_desctiption=$row[6];
			$results[]=$loc;
		}
		return $results;
	}
	public function getEquipmentLocation($id=0){
		
		$eq=$this->getEquipmentLocations(' and c.id='.$id.' and a.isActive=1');
		
		for($i=0;$i<count($eq);$i++)
		return $eq[$i];
		
		return null;
		
	}
	public function processTransfer($eid,$site_id,$tid=0){
		
		$this->changeELocation($eid,$site_id);
		
		$this->db->updateQuery(array('status=1'),'pr_equiptransfer',' where id='.$tid);
	}
	public function saveTransferRequest($sorce_site,$dest_site,$id){
		
		$this->db->insertQuery(array('source_site','dest_site','equip_id','by_id','byname'),'pr_equiptransfer',array("'".$sorce_site."'",$dest_site,$id,$this->ud->user_id,"'".$this->ud->user_name."'"));
		
	}
	public function getTransferRequests($addedWhere=""){
		
		$res=$this->db->selectQueryJoin(array('a.id','a.source_site','a.dest_site','b.site_title','b.site_title','a.equip_id','c.regcode','c.description','a.byname'),array('pr_equiptransfer a','pr_sites b','pr_equipment c'),'where a.dest_site=b.id and a.equip_id=c.id '.$addedWhere);
		$results=array();
		while($row=mysqli_fetch_row($res)){
			$trn=new equipTransfer;			
			$trn->tr_id=$row[0];
			$trn->tr_destId=$row[2];
			$trn->tr_sourceName=$row[1];;
			$trn->tr_destName=$row[3];
			$trn->tr_equipId=$row[5];
			$trn->tr_equipCode=$row[6];
			$trn->tr_equipName=$row[7];
			$trn->tr_byName=$row[8];
			$results[]=$trn;
		}
		return $results;
	}
	public function deleteEquipment($id){
		
		$this->db->deleteQuery('pr_equipment','where id='.$id);
		
	}
	//----------------------------------------END EQUIPMENT MANAGER------------------------------------
	//------------------------------------------PROJECT DETAILS----------------------------------------
	public function getMyCurrentProject($forM=false){
		$id=0;
		$sa=$this->getSiteAssignments(" and a.user_id=".$this->ud->user_id.' and a.is_active=1');
		for($i=0;$i<count($sa);$i++)
		  $id=$sa[$i]->a_siteId;
		
        if($this->positionHasPrivilege($this->ud->user_userType,70)){
		 $sites=$this->getSites('where id='.$id);
		 for($i=0;$i<count($sites);$i++)
		 return $this->getProject($sites[$i]->site_project);
		}else{
		  $pr=$this->getProjects('where project_manager='.$this->ud->user_id.' and status=1 order by id desc limit 1');
		  for($i=0;$i<count($pr);$i++)
		  return $pr[$i];	
		}
		return null;
		
	}
	public function getMyProjectIds(){
		
		$ids=array();
		
		$res=$this->db->selectQuery(array('id'),'pr_projects','where project_manager='.$this->ud->user_id);
		while($row=mysqli_fetch_row($res)){
			$ids[]=$row[0];
		}
		return $ids;
	}
	public function getAssignedSite($id){
		
		$as=$this->getSiteAssignments(' and a.user_id='.$id.' and a.is_active=1');
		
		for($i=0;$i<count($as);$i++)
			return $as[$i];
		
		return null;
		
	}
	public function getSiteAssignments($addedClause=""){
		
		$res=$this->db->selectQueryJoin(array('a.site_id','b.site_title','a.user_id','c.user','a.is_active','a.date_assigned','a.assigned_byId','a.assigned_byName'),array($this->cmp->company_prefix.'pr_siteassigned a',$this->cmp->company_prefix.'pr_sites b','frontend_users c'),' where a.site_id=b.id and a.user_id=c.id '.$addedClause,'');
		
		$results=array();
		
		while($row=mysqli_fetch_row($res)){
			$as=new sAssign;
			$as->a_siteId=$row[0];
	        $as->a_siteName=$row[1];
	        $as->a_userId=$row[2];
	        $as->a_userName=$row[3];
	        $as->a_status=$row[4];
	        $as->a_date=$row[5];
	        $as->a_byId=$row[6];
	        $as->a_byName=$row[7];
			$results[]=$as;
		}
		return $results;
	}
	public function setSiteAssignment($siteId,$uid){
		$as=$this->changeAssignmentStatus('where user_id='.$uid,0);
		$this->db->insertQuery(array('site_id','user_id','assigned_byid','assigned_byName'),'pr_siteassigned',array($siteId,$uid,$this->ud->user_id,"'".$this->ud->user_name."'"),'');
	}
	public function changeAssignmentStatus($whereclause="",$status=0){
		
		$this->db->updateQuery(array('is_active='.$status),'pr_siteassigned',$whereclause);
		
	}
	public function getMyActiveSite($idOnly=false){
		$id=0;
		$sa=$this->getSiteAssignments(" and a.user_id=".$this->ud->user_id.' and a.is_active=1');
		
		for($i=0;$i<count($sa);$i++)
		  $id=$sa[$i]->a_siteId;
		if($this->positionHasPrivilege($this->ud->user_userType,70)){
		  $sites=$this->getSites('where id='.$id);
		  for($i=0;$i<count($sites);$i++)
		    return $sites[$i];
		}else{
			$pr=$this->getProjects('where project_manager='.$this->ud->user_id.' order by id desc limit 1');
			for($i=0;$i<count($pr);$i++){
				//echo $pr[$i]->project_id;
			  if(!$idOnly){
				 $sites=$this->getSites('where project_id='.$pr[$i]->project_id); 
			     for($g=0;$g<count($sites);$g++)
					 return $sites[$g];
			  }else{
				  $sid=array();
				  $sites=$this->getSites('where project_id='.$pr[$i]->project_id);
				  for($c=0;$c<count($sites);$c++)
					  $sid[]=$sites[$c]->site_id;
				  return $sid;
			  }
			}
		}
		return null;
		
	}
	public function getProject($id){
		
	    $prs=$this->getProjects('where id='.$id);
		for($i=0;$i<count($prs);$i++)
			return $prs[$i];
		
	}
	public function getProjects($whereclause="",$rdFormat=false){
		
		
		$format='%d-%b-%Y';
		
		if($rdFormat)
		  $format='%d/%m/%Y';
		
		$results=array();
		$res=$this->db->selectQuery(array('id','project_name','date_format(start_date,"'.$format.'")','date_format(end_date ,"'.$format.'")','location','project_manager','date_format(entry_date,"'.$format.'")','budget','project_quote','client_id','status','project_desc'),'pr_projects',$whereclause);
		while($row=mysqli_fetch_row($res)){
			$proj=new project;
			$proj->project_id=$row[0];
			$proj->project_name=$row[1];
			$proj->project_start=$row[2];
			$proj->project_end=$row[3];
			$proj->project_location=$row[4];
			$proj->project_manager=$row[5];
			$proj->project_entryDate=$row[6];
			$proj->project_description=$row[11];
			$results[]=$proj;
		}
			
		return $results;
	}
	public function createProject($title,$manager,$start,$end,$location,$desc="",$clerk=0){
		
		$id=$this->db->insertQuery(array('project_name','project_manager','start_date','end_date','location','project_desc'),'pr_projects',array("'".$title."'",$manager,$this->dateFormatForDb($start),$this->dateFormatForDb($end),"'".$location."'","'".$desc."'"));
		
		$this->createSite($title,$id,$clerk,$location);
		
		return new name_value(true,System::successText('Project saved successfully.'));
		
	}
	public function updateProject($id,$title,$start,$end,$location,$desc=""){
		
		$project_title="";
		
		$pr=$this->getProjects('where id='.$id);
		
		for($i=0;$i<count($pr);$i++)
		  $project_title=$pr[$i]->project_name;
		
		$this->db->updateQuery(array('project_name=\''.str_replace("'","''",$title).'\'','start_date='.$this->dateFormatForDb($start),'end_date='.$this->dateFormatForDb($end),'location=\''.str_replace("'","''",$location)."'",'project_desc=\''.str_replace("'","''",$desc).'\''),'pr_projects','where id='.$id);
		
		$this->updateSiteTitle($project_title,$title,$id);
		
	}
	public function updateSiteTitle($oTitle,$newTitle,$pid){
		
		$this->db->updateQuery(array("site_title='".str_replace("'","''",$newTitle)."'"),'pr_sites','where project_id='.$pid.' and site_title=\''.str_replace("'","''",$oTitle).'\'');
		
	}
	public function changeProject($id,$status=3 ){
		
		$s_status=0;
		
		if($status==1)
			$s_status=1;
		
		$sites=$this_>getSites('where project_id='.$id);
		for($i=0;$i<count($sites);$i++)
		$this->updateSiteStatus($sites[$i]->site_id,$s_status);			
		
		$this->db->updateQuery(array('status='.$status),'pr_projects','where id='.$id);
		
	}
    public function createSite($sitename="",$project=0,$clerk=0,$location=""){
		$id=$this->db->insertQuery(array('site_title','project_id','clerk','location'),'pr_sites',array("'".$sitename."'",$project,$clerk,"'".$location."'"));
		$this->setSiteAssignment($id,$clerk);
		return new name_value(true,System::successText('Site created Successfully'));
	}
	public function getSites($whereclause="",$wName=false){
		$res=null;
		if($wName){
			$res=$this->db->selectQueryJoin(array('a.id','a.site_title','b.project_name','a.date_created','c.user','a.location','a.status'),array('pr_sites a','pr_projects b','frontend_users c'),'where a.project_id=b.id and a.clerk=c.id'.$whereclause.' order by a.id desc','');
		}else{
			$res=$this->db->selectQuery(array('*'),'pr_sites',$whereclause);
		}
		
		$results=array();
		while($row=mysqli_fetch_row($res)){
			$st=new site;
			$st->site_id=$row[0];
	        $st->site_name=$row[1];
	        $st->site_created=$row[3];
	        $st->site_project=$row[2];
	        $st->site_clerk=$row[4];
	        $st->site_location=$row[5];
			$st->status=$row[6];
			$results[]=$st;
		}
		return $results;
	}
	public function getSiteArray($whereclause=""){
		$res=null;
		
		$res=$this->db->selectQuery(array('*'),'pr_sites',$whereclause);
		
		$results=array();
		while($row=mysqli_fetch_row($res)){
			$st=new name_value($row[1],$row[0]);
			$results[]=$st;
		}
		return $results;
	}
	public function updateSitestatus($id,$status=0){
		
		$this->db->updateQuery(array('status='.$status),'pr_sites','where id='.$id);
			
	}
	//----------------------------------------POSITIONS MANAGER-------------------------
	public function getPositions($simplePos=false){
		
		if(!$simplePos){
		   return array(new name_value('Select Position',-1),new name_value('Store Clerk',-2),new name_value('Site Agent',-3),new name_value('Site Secretary',-6),new name_value('Office Secretary',-4),new name_value('Construction Manager',0),new name_value('General Manager',1),new name_value('Procurement Officer',2),new name_value('Verification Officer',-5),new name_value('Accountant',3),new name_value('Director',4),new name_value('Administrator',5),new name_value('Achitect',-7),new name_value('Stractural Eng.',-8),new name_value('Mechanical Eng.',-9),new name_value('Electrical Eng.',-10),new name_value('Quantity Surveyor',-11));
		}else{
		   return array(-1=>'Select Position',-2=>'Store Clerk',-3=>'Site Agent',-6=>'Site Secretary ',-4=>'Office Secretary ',0=>'Construction Manager',1=>'General Manager',2=>'Procurement Officer',-5=>'Verification Officer',3=>'Accountant',4=>'Director',5=>'Administrator',-7=>'Achitect',-8=>'Stractural Eng.',-9=>'Mechanical Eng.',-10=>'Electrical Eng.',-11=>'Quantity Surveyor');
		}
		
	}
	public function getDynamicPositions($simple=false,$whereclause="",$prefix=-1){
		
		$results=array();
		
	    $res=$this->db->selectQuery(array('*'),'pr_userpositions',$whereclause.' order by position_title asc',$prefix);
		
		while($row=mysqli_fetch_row($res)){
			$results[]=new name_value($row[1],$row[0]);
		}
	    if($simple){
			return System::nameValueToSimpleArray(System::swapNameValue($results));
		}else{
			return $results;
		}
		
	}
	public function addPosition($pos,&$id=0,$company_prefix=-1){
		
		
		
		$res=$this->getDynamicPositions(false,"where position_title='".$pos."'",$company_prefix);
		
		
		if(count($res)>0){
			return System::getWarningText("A position with the same name already exists.");
		}
			
		$id=$this->db->insertQuery(array('position_title'),'pr_userpositions',array("'".$pos."'"),'',$company_prefix);
		
		return System::successText("Position added successfully.");
	
	}
	public function deletePosition($id){
		$this->db->deleteQuery('pr_userpositions','where id='.$id);
		$this->db->deleteQuery('pr_positionprivileges','where position_id='.$id);
		return new name_value(true,System::successText('Position deleted successfully'));
	}
	//---------------------------------------END POSITION MANAGER-----------------------
	//----------------------------------------MANAGE FOREMAN Requisition--------------------------
	public function saveFMPayment($ftype=0,$desc){

		$site=$this->getMyActiveSite();
		
		if($site!=null)
		return $this->db->insertQuery(array('theformat','site_id','user_id','user_name','descript'),'pr_fmrequests',array($ftype,$site->site_id,$this->ud->user_id,"'".$this->ud->user_name."'","'".str_replace("'","''",$desc)."'"));
		
		return -1;
	}
	public function saveFmFile($name,$id){
		
		move_uploaded_file($_FILES[$name]['tmp_name'],dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/fm-mp-".$id);
		
	}
	public function deleteFmRequest($id){
		if(file_exists(dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/fm-mp-".$id)){
			unlink(dirname(__FILE__)."/../../mpFile/".$this->cmp->company_prefix."ufiles/fm-mp-".$id);
		}
		$this->db->deleteQuery('pr_fmrequests','where id='.$id);
	}
	public function getFMPayment($whereclause=""){
		
		$results=array();
		
		$res=$this->db->selectQuery(array('id','date_format(thedate,"%d-%b-%Y")','theformat','site_id','user_id','user_name','reqId', 'ext_type','descript'),'pr_fmrequests',$whereclause);
		
		while($row=mysqli_fetch_row($res)){
			$fmp=new fmMp;
			$fmp->fm_id=$row[0];
			$fmp->fm_date=$row[1];
			$fmp->fm_format=$row[2];
			$fmp->fm_site=$row[3];
			$fmp->fm_byId=$row[4];
			$fmp->fm_name=$row[5];
			$fmp->fm_reqId=$row[6];
			$fmp->fm_desc=$row[8];
			$results[]=$fmp;
		}
		
		return $results;
			
	}
	public function getFmFileType($id){
		$fmp=$this->getFMPayment('where id='.$id);
		for($i=0;$i<count($fmp);$i++)
			return $fmp[$i]->fm_format;
		
		return 0;
	}
	public function getFMPaymentId($rid){
		 $mp=$this->getFMPayment('where reqId='.$rid);
		 for($i=0;$i<count($mp);$i++){
			 return $mp[$i]->fm_id;
		 }
		 return 0;
	}
	public function linkFmToRequest($rid,$mpId){
		$this->unlinkRequest($mpId);
		$this->db->updateQuery(array('reqId='.$mpId),'pr_fmrequests','where id='.$rid);
		if($rid==0){
		  $this->db->updateQuery(array('is_linked=0','requisition_status=0'),'pr_requisitions','where id='.$mpId);
		}else{
		  $this->db->updateQuery(array('is_linked=1','requisition_status=0'),'pr_requisitions','where id='.$mpId);
		}
	}
	public function unlinkRequest($rid){
		$fmp=$this->getFMPayment('where id='.$rid);
		for($i=0;$i<count($fmp);$i++){
		   $this->db->updateQuery(array('reqId=0'),'pr_fmrequests','where id='.$rid);
		   $this->db->updateQuery(array('is_linked=0','requisition_status=-3'),'pr_requisitions','where id='.$fmp[$i]->fm_reqId);
		}
	}
	//---------------------------------------END FM Requisition--------------------------------------
	//---------------------------------------END PROJECT DETAILS---------------------------------------
	public function dateFormatForDb($date,$reverse=false){
		$alldate=explode('/',$date);
		if(!$reverse){
		   return "'".$alldate[2].'-'.$alldate[1].'-'.$alldate[0]."'";
		}else{
		   return "'".$alldate[0].'-'.$alldate[1].'-'.$alldate[2]."'";
		}
	}
	public function getSystemDate(){
		return date('d',time()).'/'.date('m',time()).'/'.date('Y',time());
	}
	public function getTimestamp($thDate){
		
		return  strtotime(str_replace("'","",$this->dateFormatForDb($thDate)).' 00:00:00');
	
	}
	public function getDay($theDate){
		return date('l',$this->getTimestamp($theDate));
	}
	public function saveLabourData($site_id,$theDate,$theData,$ot=0,$wages=0,$ltype=0,$ext){
		
		$ld=$this->getLabourData('where site_id='.$site_id.' and ltype='.$ltype.' and date(theDate)=date('.$this->dateFormatForDb($theDate).')');
		
		if(count($ld)>0){
			
			$mImage=array();
			
			$extT=array();
			
			if((is_string($ld[0]->l_data))&(is_array(json_decode($ld[0]->l_data)))){
			    
			  $tData=json_decode($ld[0]->l_data);
			 
			  $tData[]=$theData;
				
		      $mImage=$tData;
			  
			}else{
			  $mImage[]=$ld[0]->l_data;
			  $mImage[]=$theData;
			}
	  
			if((is_string($ld[0]->l_extType))&(is_array(json_decode($ld[0]->l_extType)))){
				$tmpExt=json_decode($ld[0]->l_extType);
				$tmpExt[]=$ext;
				$extT=$tmpExt;
			}else{
				$extT[]=$ld[0]->l_extType;
				$extT[]=$ext;
			}
			 
			$this->db->updateQuery(array("extType='".json_encode($extT)."'","theData='".json_encode($mImage)."'"),'pr_labour',"where date(theDate)=date(".$this->dateFormatForDb($theDate).") and ltype=".$ltype." and site_id=".$site_id);
			
			if((is_string($ld[0]->l_data))&(is_array(json_decode($ld[0]->l_data)))){
			    if($ltype==3)
				return $mImage[count($mImage)-1];//json_decode($ld[0]->l_data)[count(json_decode($ld[0]->l_data))-1];
			}else{
				if($ltype==3)
				 return $theData;//return $ld[0]->l_data;
			}
		}
		
		$sites=$this->getSites('where id='.$site_id);
		for($i=0;$i<count($sites);$i++){
		  $this->db->insertQuery(array('project_id','theDate','theData','ot','wages','ltype','site_id','extType'),'pr_labour',array($sites[$i]->site_project,$this->dateFormatForDb($theDate),"'".str_replace("'","''",$theData)."'",$ot,str_replace("'","",$wages),$ltype,$site_id,"'".$ext."'"));
		  return $theData;
		}
	}
	public function getLabourWeekTotal($theDate,$sid){
		$date=date_create(str_replace("'","",$this->dateFormatForDb($theDate)));
		$date2=date_create(str_replace("'","",$this->dateFormatForDb($theDate)));
        date_add($date,date_interval_create_from_date_string("-7 days"));
		date_add($date2,date_interval_create_from_date_string("1 days"));
        
		$res=$this->db->selectQuery(array("sum(wages+ot)"),'pr_labour','where date(theDate)>date(\''.date_format($date,"Y-m-d").'\') and date(theDate)<date(\''.date_format($date2,"Y-m-d").'\') and site_id='.$sid);
		
		while($row=mysqli_fetch_row($res)){
			return $row[0];
		}
		
		return 0;
		
	}
	public function getLabourData($whereclause="",$opt=0){
		$res=null;
		if($opt==0){
		  $res=$this->db->selectQuery(array('project_id','date_format(theDate,"%d-%b-%Y")','theData', 'ot', 'wages', 'ltype', 'site_id', 'extType', 'uploadedImages'),'pr_labour',$whereclause);
		}else{
		   $res=$this->db->selectQuery(array('project_id','date_format(theDate,"%d-%m-%Y")','theData', 'ot', 'wages', 'ltype', 'site_id', 'extType', 'uploadedImages'),'pr_labour',$whereclause);
		}
		$results=array();
		
		while($row=mysqli_fetch_row($res)){
			$ld=new lData;
			$ld->l_project=$row[0];
			$ld->l_date=$row[1];
			$ld->l_data=$row[2];
			$ld->l_ot=$row[3];
			$ld->l_wage=$row[4];
			$ld->l_type=$row[5];
			$ld->l_site=$row[6];
			$ld->l_extType=$row[7];
			$results[]=$ld;
		}
		
		return $results;
		
	}
	public function deleteLabourFiles($theData){
		$files=json_decode($theData);
		if(is_array($files)){
			for($i=0;$i<count($files);$i++){
				if(file_exists(dirname(__FILE__).'/../../mpFile/'.$this->cmp->company_prefix.'ufiles/lb-pdf-'.$files[$i])){
					unlink(dirname(__FILE__).'/../../mpFile/'.$this->cmp->company_prefix.'ufiles/lb-pdf-'.$files[$i]);
				}
			}
		}else{
			if(file_exists(dirname(__FILE__).'/../../mpFile/'.$this->cmp->company_prefix.'ufiles/lb-pdf-'.$theData)){
					unlink(dirname(__FILE__).'/../../mpFile/'.$this->cmp->company_prefix.'ufiles/lb-pdf-'.$theData);
			}
		}
	}
	public function deleteLabourEntry($site_id,$theDate,$ltype){
		$this->db->deleteQuery('pr_labour','where site_id='.$site_id.' and ltype='.$ltype.' and date(theDate)=date('.$this->dateFormatForDb($theDate).')');
	}
	//-------------------------------------------BOQ ITEMS-----------------------------------------------
	public function saveBOQItem($pid,$desc,$qty,$unit,$rate,$amount){
		
		$this->db->insertQuery(array('Description','qty','unit','theRate','amount','project_id'),'pr_boqItems',array("'".$desc."'","'".$qty."'","'".$unit."'","'".$rate."'","'".$amount."'",$pid));
		
	}
	public function getBOQItems($whereclause=""){
		
		$items=array();
		
		$res=$this->db->selectQuery(array('*'),'pr_boqItems',$whereclause);
		
		while($row=mysqli_fetch_row($res)){
			$item=new boqItem;
			$item->item_id=$row[0];
			$item->item_projectId=$row[1];
			$item->item_description=$row[2];
			$item->item_qty=$row[3];
			$item->item_unit=$row[4];
			$item->item_rate=$row[5];
			$item->item_amount=$row[6];
			$items[]=$item;
		}
		return $items;
	}
	//-------------------------------------------END BOQ ITEMS-------------------------------------------
	//-------------------------------------START REQUISITION ITEMS---------------------------------------
	public function saveRequisition($data,$sid,$rid=-1,&$reqId=0){
		
		
		$reqno=1;
		
		$projectId=0;
		
		$site=null;
		
		$sites=$this->getSites('where id='.$sid);
		
		for($c=0;$c<count($sites);$c++){
			$site=$sites[$c];
			$projectId=$sites[$c]->site_project;
		}
		
		//$pr=$this->getMyCurrentProject();
		
		
		$req=$this->getRequisitions(" where project_id=".$projectId.' and is_sub=0');
		
		if(count($req)>0)
			$reqno=count($req)+1;
		if($rid==-1){
		   $req_id=$this->db->insertQuery(array('project_id','requisition_no','req_date','byId','byName','site_id','level0'),'pr_requisitions',array($projectId,$reqno,'now()',$this->ud->user_id,"'".$this->ud->user_name."'",$site->site_id,1));
		   $reqId=$req_id;
		}else{
			$req_id=$rid;
			$this->changeRequisitionStatus($rid,0);
		}
		
		for($i=0;$i<count($data);$i++){
		  $reqI=-1;
		  if(is_numeric(str_replace(',','',$data[$i][1])))
			$reqI=str_replace(',','',$data[$i][1]);
		  if((trim($data[$i][0])=="")&(trim($data[$i][1])=="")&(trim($data[$i][2])=="")){}else{	  
		     $this->saveRequestItem($req_id,$projectId,$site->site_id,$data[$i][0],$reqI,$data[$i][2],0,0,$i);
		  }
		}
		
		$this->updateRequestItemNumber($req_id);
		
		return new name_value(true,System::successText('Requisition posted successfully'));
		
	}
	public function createSubRequisition($parent_req){
		
		$req=$this->getRequisitions('where id='.$parent_req);
		
		for($i=0;$i<count($req);$i++){
			$rid=0;
			$this->saveRequisition(array(),$req[$i]->req_siteId,-1,$rid);
			$this->makeSub($rid,$req[$i]->req_no);
			$this->moveItems($parent_req,$rid);
			$this->updatePartNo($req[$i]->req_no,$req[$i]->req_projectId);
		}
		
	}
	public function moveItems($parent,$sub_id){
	
		$this->db->updateQuery(array('request_id='.$sub_id),'pr_requisitionitems','where request_id='.$parent.' and description<>\'\' and qty>0 and amount=0');
		
	}
	private function updatePartNo($req_no,$pid){
		
		$req=$this->getRequisitions('where project_id='.$pid.' and requisition_no='.$req_no);
		$lets=array('a','b','c','d','e','f','g','h');
		for($i=0;$i<count($req);$i++){
			$this->db->updateQuery(array("partno='".$lets[$i]."'"),'pr_requisitions','where id='.$req[$i]->req_id);
			
		}
		
	}
	public function getNextSub($rid){
		$lets=array('','a','b','c','d','e','f','g','h');
		$req=$this->getRequisitions('where id='.$rid,true);
		for($i=0;$i<count($req);$i++){
			$rno=$this->getRequisitions('where requisition_no='.$req[$i]->req_no.' and project_id='.$req[$i]->req_projectId);
			return System::getArrayElementValue($lets,count($rno)+1,'a');
		}
		
		return 'a';
	}
	private function makeSub($id,$no){
		$this->db->updateQuery(array('is_sub=1','requisition_no='.$no),'pr_requisitions','where id='.$id);
	}
	public function getRequisitionSiteId($rid){
		$rq=$this->getRequisitions('where id='.$rid);
		for($i=0;$i<count($rq);$i++){
			return $rq[$i]->req_siteId;
		}
	}
	public function getMPNo($rid){
		$rq=$this->getRequisitions('where id='.$rid);
		for($i=0;$i<count($rq);$i++){
			return $rq[$i]->req_no;
		}
	}
	public function getRequisitions($whereclause="",$plain=false){
		
		$res=$this->db->selectQuery(array('id','project_id','requisition_no',"date_format(req_date,'%d/%b/%Y')",'byId','byName','requisition_status','level0','level1','level2','level3','level4','site_id',"date_format(level0_date,'%d.%b.%Y')","date_format(level1_date,'%d.%b.%Y')","date_format(level2_date,'%d.%b.%Y')","date_format(level3_date,'%d.%b.%Y')","date_format(level4_date,'%d-%b-%Y')",'level0_approveName','level1_approveName','level2_approveName','level3_approveName','level4_approveName','level0_approveId','level1_approveId','level2_approveId','level3_approveId','level4_approveId',"date_format(level5_date,'%d.%b.%Y')",'level5_approveName','level5_approveId','level5','site_id','extType','is_linked','partno','verifiedby_id','verifiedby'),'pr_requisitions',$whereclause);
		$results=array();
		while($row=mysqli_fetch_row($res)){
			$req=new Requis;
			$req->req_id=$row[0];
			$req->req_projectId=$row[1];
			if(!$plain){
			    $req->req_no=$row[2].$row[35];
			}else{
				$req->req_no=$row[2];
			}
			$req->req_date=$row[3];
			$req->req_byId=$row[4];
			$req->req_byName=$row[5];
			$req->req_status=$row[6];
			$req->req_level0=$row[7];
			$req->req_level1=$row[8];
			$req->req_level2=$row[9];
			$req->req_level3=$row[10];
			$req->req_level4=$row[11];
			$req->req_level5=$row[31];
			$req->req_level0Date=$row[13];
			$req->req_level1Date=$row[14];
	        $req->req_level2Date=$row[15];
	        $req->req_level3Date=$row[16];
	        $req->req_level4Date=$row[17];
			$req->req_level5Date=$row[28];
	        $req->req_level0ApproveName=$row[18];
			$req->req_level1ApproveName=$row[19];
	        $req->req_level2ApproveName=$row[20];
	        $req->req_level3ApproveName=$row[21];
	        $req->req_level4ApproveName=$row[22];
			$req->req_level5ApproveName=$row[29];
			$req->req_level0ApproveId=$row[23];
	        $req->req_level1ApproveId=$row[24];
	        $req->req_level2ApproveId=$row[25];
	        $req->req_level3ApproveId=$row[26];
			$req->req_level4ApproveId=$row[27];
			$req->req_level5ApproveId=$row[30];
			$req->req_siteId=$row[32];
			$req->req_extType=$row[33];
	        $req->req_isLinked=$row[34];
			$req->req_partNo=$row[35];
			$req->req_verifiedById=$row[36];
			$req->req_verifiedByName=$row[37];
			$results[]=$req;
		}
		
		return $results;
	}
	public function changeRequisitionStatus($id,$status=-1){
		
		$this->db->updateQuery(array('requisition_status='.$status),'pr_requisitions','where id='.$id);
		
	}
	public function deleteRequisition($id){
		
		$this->db->deleteQuery('pr_requisitions','where id='.$id);
		
	}
	public function updateRequestItems($id,$desc,$qty=0,$rate=0,$unit="",$amount=0,$rem="",$lno=0){
		
		if(!is_numeric($qty)){
			$qty=-1;
		}
		
		if(!is_numeric(str_replace(",","",$amount))){
			$amount=0;
		}
		
		if(!is_numeric(str_replace(",","",$rate))){
			$rate=-1;
		}
		
		$this->db->updateQuery(array("description='".$desc."'","qty=".$qty,'theRate='.str_replace(",","",$rate),"amount=".str_replace(",","",$amount),"lno=".$lno,"remarks='".str_replace("'","''",$rem)."'","unit='".$unit."'"),'pr_requisitionitems','where id='.str_replace("e","",$id));
		
		
	}
	public function saveRequestItem($rId,$prid,$site_id,$desc,$qty,$unit,$rate=0,$amount=0,$lno=0){
		
		if(!is_numeric(str_replace(',','',$qty))){
			$qty=0;
		}
	    if(!is_numeric($rate)){
			$rate=0;
		}
		$this->db->insertQuery(array('request_id','project_id','site_id','description','qty','unit','theRate','amount','lno'),'pr_requisitionitems',array($rId,$prid,$site_id,"'".str_replace("'","''",$desc)."'",str_replace(',','',$qty),"'".$unit."'",$rate,str_replace(",","",$amount),$lno));
		
		return new name_value(true,System::successText('Request submitted successfully.'));
		
	}
	public function updateRequestItemNumber($rid){
		
	   $req=$this->getRequestItems('where request_id='.$rid.' order by lno');
	   
	   $x=0;
		
	   for($i=0;$i<count($req);$i++){
		 if($req[$i]->item_qty!=-1){
		  $x+=1;
		  $this->db->updateQuery(array('item_no='.$x),'pr_requisitionitems','where id='.$req[$i]->item_id);
		 }else{
		   $this->db->updateQuery(array('item_no=-1'),'pr_requisitionitems','where id='.$req[$i]->item_id);
		 }
	   }
	  						
	}
	public function getRequestItems($whereclause=""){
		
		$req=$this->db->selectQuery(array('*'),'pr_requisitionitems',$whereclause);
		$results=array();
		
		while($row=mysqli_fetch_assoc($req)){
			$itm=new requis_item;
			$itm->item_id=$row['id'];
	        $itm->item_requestId=$row['request_id'];
	        $itm->item_projectId=$row['project_id'];
	        $itm->item_siteId=$row['site_id'];
	        $itm->item_description=$row['description'];
			$itm->item_qty=$row['qty'];
			$itm->item_unit=$row['unit'];
			$itm->item_rate=$row['theRate'];
			$itm->item_amount=$row['amount'];
			$itm->item_remarks=$row['remarks'];
			$itm->item_no=$row['item_no'];
			$results[]=$itm;
		}
		
		return $results;
	}
	public function deleteRequisitionItem($id){
		
		$this->db->deleteQuery('pr_requisitionitems','where id='.$id);
		
	}
	public function deleteRequisitionItems($id){
		
		$this->db->deleteQuery('pr_requisitionitems','where request_id='.$id);
		
	}
	public function updateRequestLevel($id,$level,$value=1){
		echo $level;
		switch($level){
			case 0:
				$this->db->updateQuery(array('level0='.$value,'level0_date='.$this->dateFormatForDb($this->getSystemDate()),'level0_approveName=\''.$this->ud->user_name.'\'','level0_approveId=\''.$this->ud->user_id.'\''),'pr_requisitions','where id='.$id);
				break;
				
			case 1:
				$this->db->updateQuery(array('level1='.$value,'level1_date='.$this->dateFormatForDb($this->getSystemDate()),'level1_approveName=\''.$this->ud->user_name.'\'','level1_approveId=\''.$this->ud->user_id.'\''),'pr_requisitions','where id='.$id);
				break;
				
			case 2:
				$this->db->updateQuery(array('level2='.$value,'level2_date='.$this->dateFormatForDb($this->getSystemDate()),'level2_approveName=\''.$this->ud->user_name.'\'','level2_approveId=\''.$this->ud->user_id.'\''),'pr_requisitions','where id='.$id);
				break;
				
			case 3:
				$this->db->updateQuery(array('level3='.$value,'level3_date='.$this->dateFormatForDb($this->getSystemDate()),'level3_approveName=\''.$this->ud->user_name.'\'','level3_approveId=\''.$this->ud->user_id.'\''),'pr_requisitions','where id='.$id);
				break;
				
			case 4:
				$this->db->updateQuery(array('level4='.$value,'level4_date='.$this->dateFormatForDb($this->getSystemDate()),'level4_approveName=\''.$this->ud->user_name.'\'','level4_approveId=\''.$this->ud->user_id.'\''),'pr_requisitions','where id='.$id);
				break;
				
			case 5:
				//if($this->ud->user_userType==4){
				  $this->db->updateQuery(array('level5='.$value,'level5_date='.$this->dateFormatForDb($this->getSystemDate()),'level5_approveName=\''.$this->ud->user_name.'\'','level5_approveId=\''.$this->ud->user_id.'\''),'pr_requisitions','where id='.$id);
				/*}else{
				  $this->db->updateQuery(array('level5='.$value,'level5_date='.$this->dateFormatForDb($this->getSystemDate()),'level5_approveName=\'Hard Copy\'','level5_approveId=\''.$this->ud->user_id.'\''),'pr_requisitions','where id='.$id);
				}*/
				break;
		}
		
	}
	//-------------------------------------END REQUISITION ITEMS ----------------------------------------
	//----------------------------------------START PURCHASES--------------------------------------------
    public function savePurchase($project_id=1){
		
		return $this->db->insertQuery(array('byid','byname','project_id'),'pr_purchases',array($this->ud->user_id,"'".$this->ud->user_name."'",$project_id));
	
	}
	public function savePurchasedItems($project_id,$site_id,$req_id,$desc,$qty,$unit_type,$purchaseid,$rate=0,$thedate){
		
		$this->db->insertQuery(array('project_id','site_id','description','qty','unit_type','request_id','pid','byname','byid','theRate','date_purchased','date_processed'),'pr_purchaseditems',array($project_id,$site_id,"'".$desc."'",$qty,"'".$unit_type."'",$req_id,$purchaseid,"'".$this->ud->user_name."'",$this->ud->user_id,$rate,$this->dateFormatForDb($thedate),'now()'));
		
	}
	public function saveReceived($project_id=1){
		
		return $this->db->insertQuery(array('byid','byname','project_id'),'pr_received',array($this->ud->user_id,"'".$this->ud->user_name."'",$project_id));
		
	}
	public function saveReceivedItems($project_id,$site_id,$req_id,$desc,$qty,$unit_type,$receiveId,$rate=0,$thedate){
		
		$this->db->insertQuery(array('project_id','site_id','description','qty','unit_type','request_id','pid','byname','byid','date_received','date_processed'),'pr_receivedItems',array($project_id,$site_id,"'".$desc."'",$qty,"'".$unit_type."'",$req_id,$receiveId,"'".$this->ud->user_name."'",$this->ud->user_id,$this->dateFormatForDb($thedate),'now()'));
		
	}
	public function getPurchasedItems($reqId,$other=""){
		
	    $res=$this->db->selectQuery(array('project_id','site_id','description','sum(qty)','byid','byname','request_id','unit_type','pid','(select qty from '.$this->cmp->company_prefix.'pr_requisitionitems where description='.$this->cmp->company_prefix.'pr_purchaseditems.description and request_id='.$reqId.' limit 1) as qty','(select theRate from pr_requisitionitems where description='.$this->cmp->company_prefix.'pr_purchaseditems.description and request_id='.$reqId.' limit 1) as rate','(select item_no from '.$this->cmp->company_prefix.'pr_requisitionitems where description='.$this->cmp->company_prefix.'pr_purchaseditems.description and request_id='.$reqId.' limit 1) as item_number'),'pr_purchaseditems','where request_id='.$reqId.' '.$other.' group by description order by item_number asc');
		
		$results=array();
		
		while($row=mysqli_fetch_row($res)){
			$pi=new pItem;
			$pi->item_projectId=$row[0];
	        $pi->item_siteId=$row[1];
	        $pi->item_description=$row[2];
	        $pi->item_requestId=$row[6];
	        $pi->item_qty=$row[3];
	        $pi->item_unit=$row[7];
	        $pi->item_byId=$row[4];
	        $pi->item_byName=$row[5];
	        $pi->item_purchaseId=$row[8];
			$pi->item_requested=$row[9];
			$pi->item_theRate=$row[10];
			$pi->item_no=$row[11];
			$results[]=$pi;
		}
		
		return $results;
	}
	public function getPurchasedItems2($reqId,$other="",$withSites=false){
		$res="";
		$hasEleven=false;
		if(is_numeric($reqId)){
		  $res=$this->db->selectQuery(array('project_id','site_id','description','qty','byid','byname','request_id','unit_type','pid','date_format(date_purchased,"%d-%b-%Y")','therate'),'pr_purchaseditems','where request_id='.$reqId.' '.$other.'');
		}else{
		  if($withSites){
			 $res=$this->db->selectQueryJoin(array('a.project_id','b.site_id','a.description','a.qty','a.byid','a.byname','a.request_id','a.unit_type','a.pid','date_format(a.date_purchased,"%d-%b-%Y")','a.therate','(select item_no from pr_requisitionitems where description=a.description and a.request_id=request_id limit 1) as item_no'),array('pr_purchaseditems a','pr_requisitions b'),'where a.request_id=b.id '.$other);
			 $hasEleven=true;
		  }else{
		    $res=$this->db->selectQuery(array('project_id','site_id','description','qty','byid','byname','request_id','unit_type','pid','date_format(date_purchased,"%d-%b-%Y")','therate','(select item_no from pr_requisitionitems where description=pr_purchaseditems.description and pr_purchaseditems.request_id=request_id limit 1) as item_no'),'pr_purchaseditems',$other);
			$hasEleven=true;
		  }
		}
		$results=array();
		
		while($row=mysqli_fetch_row($res)){
			$pi=new pItem;
			$pi->item_projectId=$row[0];
	        $pi->item_siteId=$row[1];
	        $pi->item_description=$row[2];
	        $pi->item_requestId=$row[6];
	        $pi->item_qty=$row[3];
	        $pi->item_unit=$row[7];
	        $pi->item_byId=$row[4];
	        $pi->item_byName=$row[5];
	        $pi->item_purchaseId=$row[8];
			$pi->item_requested=$row[9];
			$pi->item_theRate=$row[10];
			if($hasEleven)
			$pi->item_no=$row[11];
			$results[]=$pi;
		}
		
		return $results;
	}
	public function getReceivedItems($reqId,$other=""){
		
	    $res=$this->db->selectQuery(array('project_id','site_id','description','sum(qty)','byid','byname','request_id','unit_type','pid','(select qty from pr_requisitionitems where description='.$this->cmp->company_prefix.'pr_receivedItems.description and request_id='.$reqId.' limit 1) as qty','(select theRate from '.$this->cmp->company_prefix.'pr_requisitionitems where description='.$this->cmp->company_prefix.'pr_receivedItems.description and request_id='.$reqId.' limit 1 ) as rate','(select item_no from '.$this->cmp->company_prefix.'pr_requisitionitems where description='.$this->cmp->company_prefix.'pr_receivedItems.description and request_id='.$reqId.' limit 1) as item_no'),'pr_receivedItems','where request_id='.$reqId.' '.$other.' group by description order by item_no asc');
		
		$results=array();
		
		while($row=mysqli_fetch_row($res)){
			$pi=new pItem;
			$pi->item_projectId=$row[0];
	        $pi->item_siteId=$row[1];
	        $pi->item_description=$row[2];
	        $pi->item_requestId=$row[6];
	        $pi->item_qty=$row[3];
	        $pi->item_unit=$row[7];
	        $pi->item_byId=$row[4];
	        $pi->item_byName=$row[5];
	        $pi->item_purchaseId=$row[8];
			$pi->item_requested=$row[9];
			$pi->item_theRate=$row[10];
			$pi->item_no=$row[11];
			$results[]=$pi;
		}
		
		return $results;
	}
	public function getReceivedItems2($reqId,$other="",$withSite=false){
		$res=null;
		$hasTen=false;
		if(is_numeric($reqId)){
		  $res=$this->db->selectQuery(array('project_id','site_id','description','qty','byid','byname','request_id','unit_type','pid','date_format(date_received,"%d-%b-%Y")','(select item_no from pr_requisitionitems where description=pr_receivedItems.description and request_id='.$reqId.' limit 1) as item_no'),'pr_receivedItems','where request_id='.$reqId.' '.$other.'');
		  $hasTen=true;
		}else{
		   if($withSite){
				$res=$this->db->selectQueryJoin(array('a.project_id','b.site_id','a.description','a.qty','a.byid','a.byname','a.request_id','a.unit_type','a.pid','date_format(a.date_received,"%d-%b-%Y")','(select item_no from pr_requisitionitems where description=a.description and request_id=a.request_id limit 1) as item_no'),array('pr_receivedItems a,pr_requisitions b'),'where a.request_id=b.id  '.$other);
			    $hasTen=true;
		   }else{
			   
  	            $res=$this->db->selectQuery(array('project_id','site_id','description','qty','byid','byname','request_id','unit_type','pid','date_format(date_received,"%d-%b-%Y")','(select item_no from pr_requisitionitems where description=pr_receivedItems.description and pr_receivedItems.request_id=request_id limit 1) as item_no'),'pr_receivedItems',$other);
			    $hasTen=true;
		   }
		}
		$results=array();
		
		while($row=mysqli_fetch_row($res)){
			$pi=new pItem;
			$pi->item_projectId=$row[0];
	        $pi->item_siteId=$row[1];
	        $pi->item_description=$row[2];
	        $pi->item_requestId=$row[6];
	        $pi->item_qty=$row[3];
	        $pi->item_unit=$row[7];
	        $pi->item_byId=$row[4];
	        $pi->item_byName=$row[5];
	        $pi->item_purchaseId=$row[8];
			$pi->item_requested=$row[9];
			if($hasTen)
			$pi->item_no=$row[10];
			$results[]=$pi;
		}
		
		return $results;
	}
	public function getTotalReceived($rid,$item_name,$lid){
			
		$res=$this->db->selectQuery(array('sum(qty) as qty'),'pr_receivedItems','where request_id='.$rid.' and description=\''.str_replace('-1',' ',substr($item_name,0,strlen($item_name)-strlen($lid))).'\'');
		//echo str_replace("$",' ',substr($item_name,0,strlen($item_name)-strlen($lid)));
		while($row=mysqli_fetch_row($res)){
			return $row[0];
		}
		return 0;
	}
	public function deleteReceivedItems($pid){
		
		$this->db->deleteQuery('pr_receivedItems','where pid='.$pid);
		
	}
	public function getTotalProjectReq($id){
		
		$res=$this->db->selectQuery(array('count(id)'),'pr_requisitions','where project_id='.$id);
		
		while($row=mysqli_fetch_row($res)){
			return $row[0];
		}
		
	}
	public function getCurrentSpending($request_id,$itemName=""){
		
		
		$pi=$this->getPurchasedItems2($request_id," and description='".str_replace("'","''",$itemName)."'");
		
		$total=0;
		
		for($i=0;$i<count($pi);$i++){
			$total+=$pi[$i]->item_qty * $pi[$i]->item_theRate;
		}
		
		return $total;
		
	}
	//-----------------------------------------END PURCHASES---------------------------------------------
	//---------------------------------------INVENTORY FUNCTION------------------------------------------
	public function addItemToSite($site_id,$descrip,$qty,$unit_type,$gid,$item_type=0,$entry_date){
		
		$this->db->insertQuery(array('description','qty','unit_type','item_type','site_id','group_id','entry_date','posted_by','posted_byid','reported_date'),'pr_siteinventory',array("'".$descrip."'",$qty,"'".$unit_type."'",$item_type,$site_id,$gid,$this->dateFormatForDb($entry_date),"'".$this->ud->user_name."'",$this->ud->user_id,'now()'));
		
	}
	public function getSiteItems($whereclause=""){
		
		$results=array();
		
		$res=$this->db->selectQuery(array('description', 'qty', 'unit_type', 'site_id', 'item_type', 'id', 'date_format(entry_date,"%d-%b-%Y")', 'group_id', 'posted_by', 'posted_byid', 'date_format(reported_date,"%d-%b-%Y")'),'pr_siteinventory',$whereclause);
		
		while($row=mysqli_fetch_row($res)){
			$itm=new sItem;
			$itm->item_id=$row[5];
			$itm->item_description=$row[0];
			$itm->item_qty=$row[1];
			$itm->item_unitType=$row[2];
			$itm->item_siteId=$row[3];
			$itm->item_itemType=$row[4];
			$itm->item_entryDate=$row[6];
			$itm->item_postedBy=$row[8];
			$results[]=$itm;
		}
		
		return $results;
		
	}
	
	//------------------------------------END INVENTORY FUNCTIONS----------------------------------------
	//-------------------------------------BEGIN ALERT FUNCTIONS-----------------------------------------
	public function saveAlert($for,$atype,$desc,$uid=0){
		$this->db->insertQuery(array('user_id','description','alert_type','uid'),'pr_alerts',array($for,"'".str_replace("'","''",$desc)."'",$atype,$uid));
	}
	public function changeAlertStatus($userId,$aType,$status=1,$spec=0){
		
		$uid="";
		
		if($spec!=0)
			$uid=' and uid='.$spec;
		
		$this->db->updateQuery(array('alert_status='.$status),'pr_alerts','where user_id='.$userId.' and alert_type='.$aType.$uid);
		
	}
	public function getAlerts($whereClause=""){
		
	    $res=$this->db->selectQuery(array('*'),'pr_alerts',$whereClause);
		$results=array();
		while($row=mysqli_fetch_row($res)){
			$al=new alerts;
			$al->alert_date=$row[0];
			$al->alert_status=$row[1];
			$al->alert_userId=$row[2];
			$al->alert_description=$row[3];
			$al->alert_type=$row[4];
			$al->alert_uid=$row[5];
			$results[]=$al;
		}
		
		return $results;
		
	}
	public function getUserAlertTotals(){
		
		$res=$this->db->selectQuery(array('count(*)'),'pr_alerts','where user_id='.$this->ud->user_id.' and alert_status=0');
		
		while($row=mysqli_fetch_row($res)){
			return $row[0];
		}
		
		return 0; 
		
	}
	public function getUserAlertTypes($typeArray=array()){
		
		$uat=$this->getAlertTypes();//user alert types
		
		$tuat=array();
		
		foreach($uat as $at){
			if(in_array($at->value,$typeArray))
				$tuat[]=$at;
		}
		
		return $tuat;
		
	}
	public function getAlertTypes(){
		
		return array(new name_value("Project Management",1),new name_value("Material Requisition",4),new name_value("Office Requisition",22),new name_value("Purchases",12),new name_value("Price Verification",13),new name_value("Inventory",5),new name_value("Plant & Equipment",11),new name_value("Labour",10));
		
	}
	public function enableUserAlertType($uid,$atype){
		
		
		$this->db->deleteQuery('pr_myalerts','where user_id='.$uid.' and alerttype='.$atype);
		
		$this->db->insertQuery(array('user_id','alerttype'),'pr_myalerts',array($uid,$atype));
		
	}
	public function disableUserAlertType($uid,$atype){
		$this->db->deleteQuery('pr_myalerts','where user_id='.$uid.' and alerttype='.$atype);
	}
	public function getMyEnabledAlertTypes($uid){
		
		$res=$this->db->selectQuery(array('*'),'pr_myalerts','where user_id='.$uid);
		
		$results=array();
		
		while($row=mysqli_fetch_array($res)){
			$results[]=new name_value($row[0],$row[1]);
		}
		
		return $results;
		
	}
	//------------------------------------------END ALERT FUNCTIONS------------------------------------------
	//------------------------------------------VERIFICATION FUNCTIONS---------------------------------------
	public function getVerifiedPrices($whereclause=""){
		
		$res=$this->db->selectQuery(array('*'),'pr_pvitems',$whereclause);
		
		$results=array();
		
		while($row=mysqli_fetch_assoc($res)){
			$vr=new vPur;
			$vr->v_itemId=$row['item_id'];
			$vr->v_price=$row['nvprice'];
			$results[]=$vr;
		}
		
		return $results;
		
	}
	public function addverifiedPrices($item_id,$price){
		
		$ri=$this->getRequestItems("where id=".$item_id);
		
		if(!is_numeric($price))
		   $price=0;
		
		$vr=$this->getVerifiedPrices('where item_id='.$item_id);
		
		if(count($vr)>0){
			$this->db->updateQuery(array('nvprice='.$price),'pr_pvitems','where item_id='.$item_id);
		}else{
		    $this->db->insertQuery(array('item_id','nvprice'),'pr_pvitems',array($item_id,$price));
		}
		
		for($i=0;$i<count($ri);$i++){
			$this->db->updateQuery(array("verifiedby_id=".$this->ud->user_id,"verifiedby='".$this->ud->user_name."'"),'pr_requisitions','where id='.$ri[$i]->item_requestId);
		}
		
	}
	public function getPvItemPrice($id){
		$prices=$this->getVerifiedPrices('where item_id='.$id);
		for($i=0;$i<count($prices);$i++){
			return $prices[$i]->v_price;
		}
		return 0;
	}
	//---------------------------------------END VERIFICATION FUNCTIONS--------------------------------------
	//-------------------------------------------OFFICE PROCUREMENT------------------------------------------
	public function getOfficeRequests($whereclause=""){
		
		$results=array();
		
		$resource=$this->db->selectQuery(array('id','date_format(thedate,"%d-%b-%Y") as thedate','requisition_no','generated_byid','generated_byname', 'approved_byid','approve_byname','site_id','total','status'),'pr_officeproc',$whereclause);
		
		while($row=mysqli_fetch_assoc($resource)){
			$op=new officeProc;
			$op->proc_id=$row['id'];
			$op->proc_no=$row['requisition_no'];
			$op->proc_date=$row['thedate'];
			$op->proc_byId=$row['generated_byid'];
			$op->proc_byName=$row['generated_byname'];
			$op->proc_appById=$row['approved_byid'];
			$op->proc_appByName=$row['approve_byname'];
			$op->proc_siteId=$row['site_id'];
			$op->proc_total=$row['total'];
			$op->proc_status=$row['status'];
			$results[]=$op;
		}
		
		return $results;
		
	}
	public function createOfficeRequest($theDate,$siteid){
		
		$reqNo=count($this->getOfficeRequests('where year(thedate)=year('.$this->dateFormatForDb($theDate).')'))+1;
		
		$id=$this->db->insertQuery(array('thedate','requisition_no','site_id','generated_byname','generated_byid'),'pr_officeproc',array($this->dateFormatForDb($theDate),'concat(\''.$reqNo.'/\',year('.$this->dateFormatForDb($theDate).'))',$siteid,"'".$this->ud->user_name."'",$this->ud->user_id));
		
		return $id;
	}
	public function approveOp($id){
	
		$this->db->updateQuery(array('approved_byid='.$this->ud->user_id,'approve_byname=\''.$this->ud->user_name.'\'','status=1'),'pr_officeproc','where id='.$id);
		
	}
	public function addOpItems($pi,$i_desc,$qty=0,$amount=0){
	   
	    $id=$this->db->insertQuery(array('item_description','qty','amount','proc_id'),'pr_opitems',array("'".$i_desc."'",$qty,$amount,$pi));
		
	}
	public function getOpItems($whereclause=""){
		
		//echo $whereclause;
		
		$results=array();
		
		$resource=$this->db->selectQuery(array('*'),'pr_opitems',$whereclause);
		
		while($row=mysqli_fetch_assoc($resource)){
			$opi=new opItem;
			$opi->op_pid=$row['proc_id'];
			$opi->op_itemDesc=$row['item_description'];
			$opi->op_qty=$row['qty'];
			$opi->op_amount=$row['amount'];
			$results[]=$opi;
		}
		
	   return $results;
		
	}
	public function opItem($id){
		
		$this->db->deleteQuery('pr_opitems','where id='.$id);
		
	}
	//----------------------------------------END OFFICE PROCUREMENT-----------------------------------------
	//--------------------------------------------INCOME FUNCTIONS-------------------------------------
	public function saveIncome($invoice_no,$desc,$amount,$siteId,$date){
		
		$this->db->insertQuery(array('invoice_no','description','amount','site_id','income_date','recorded_byid','recorded_byname'),'pr_income',array("'".$invoice_no."'","'".$desc."'",$amount,$siteId,$this->dateFormatForDb($date),$this->ud->user_id,"'".$this->ud->user_name."'"));
		
	}
	public function getTotalIncome($whereclause=""){
		
		$res=$this->db->selectQuery(array("sum(amount)"),'pr_income',$whereclause);
		
		while($row=mysqli_fetch_row($res)){
			return $row[0];
		}
		
		return 0;
		
	}
	public function getIncomeRecords($whereclause=""){
		
		$resource=$this->db->selectQuery(array('id','invoice_no','description','amount','site_id','date_format(income_date,"%d-%b-%Y") as income_date','recorded_byid','recorded_byname'),'pr_income',$whereclause);
		$results=array();
		while($row=mysqli_fetch_assoc($resource)){
			$in=new income;
			$in->inc_id=$row['id'];
			$in->inc_invoice=$row['invoice_no'];
			$in->inc_description=$row['description'];
			$in->inc_amount=$row['amount'];
			$in->inc_site=$row['site_id'];
			$in->inc_date=$row['income_date'];
			$in->inc_byid=$row['recorded_byid'];
			$in->inc_byname=$row['recorded_byname'];
			$results[]=$in;
		}
		return $results;
	}
	public function deleteIncome($id){
		
		$this->db->deleteQuery('pr_income','where id='.$id);
		
	}
	//-------------------------------------------END INCOME FUNCTIONS----------------------------------
	//--------------------------------------------PETTYCASH FUNCTIONS----------------------------------
	public function savePettyCash($desc,$amount,$site,$date){
		
		$this->db->insertQuery(array('theDate','description','site_id','amount','reported_byid','reported_byname'),'pr_pettycash',array($this->dateFormatForDb($date),"'".$desc."'",$site,$amount,$this->ud->user_id,"'".$this->ud->user_name."'"));
		
	}
	public function getTotalPettycash($whereclause=""){
		
		$res=$this->db->selectQuery(array("sum(amount)"),'pr_pettycash',$whereclause);
		
		while($row=mysqli_fetch_row($res)){
			return $row[0];
		}
		
		return 0;
		
	}
	public function getPettyCashRecords($whereclause=""){
		
	    $res=$this->db->selectQuery(array('id','date_format(theDate,"%d-%b-%Y") as theDate','description','site_id','amount','reported_byid','reported_byname'),'pr_pettycash',$whereclause);
		$results=array();
		while($row=mysqli_fetch_assoc($res)){
			$pty=new pettyCash;
			$pty->pc_id=$row['id'];
			$pty->pc_date=$row['theDate'];
			$pty->pc_description=$row['description'];
			$pty->pc_siteId=$row['site_id'];
			$pty->pc_amount=$row['amount'];
			$pty->pc_byId=$row['reported_byid'];
			$pty->pc_byName=$row['reported_byname'];
			$results[]=$pty;
		}
		return $results;
	} 
	public function deletePettyCash($id){
		
		$this->db->deleteQuery('pr_pettycash','where id='.$id);
		
	}
	public function getTotals($table,$field,$whereclause=""){
		
		$res=$this->db->selectQuery(array('sum('.$field.')'),$table,$whereclause);
		
		while($row=mysqli_fetch_row($res)){
			return $row[0];
		}
		
		return 0;
	}
	public function getDbIds($table,$whereclause=""){
		
		$res=$this->db->selectQuery(array('id'),$table,$whereclause);
		
		$results=array();
		
		while($row=mysqli_fetch_row($res)){
			$results[]=$row[0];
		}
		return $results;
	}
	//--------------------------------------------END PETTYCASH FUNCTIONS------------------------------
	//-----------------------------------------------COMPANY FUNCTIONS---------------------------------
	public function getCompany($id){
		
		$comps=$this->getCompanies('where id='.$id,'');
		
		for($i=0;$i<count($comps);$i++){
			return $comps[$i];
		}
		
		return new theCompany;

	}
	public function getCompanies($whereclause=""){
		$results=array();
		
		$res=$this->db->selectQuery(array('*'),'companies',$whereclause,"");
		
		while($row=mysqli_fetch_row($res)){
			$comp=new theCompany;
			$comp->company_id=$row[0];
			$comp->company_name=$row[1];
			$comp->company_status=$row[2];
			$comp->company_prefix=$row[3];
			$comp->company_created=$row[4];
			$results[]=$comp;
		}
		return $results;
	}
	public function createNewCompany($companyName="",$prefix="",&$theId=0){
		
		GLOBAL $config;
		
		$theId=$this->db->insertQuery(array('company_name','company_prefix'),'companies',array("'".$companyName."'","'".$prefix."'"),false,'');
		
		$res=$this->db->generalQuery('show tables where tables_in_'.$config->db_name.' like \'pr_%\'');
		
		while($row=mysqli_fetch_row($res)){
			$this->db->generalQuery('create table '.$prefix.$row[0].' like '.$row[0]);
		}
		
		mkdir(dirname(__FILE__).'/../../mpFile/'.$prefix.'ufiles/');
		
		return new name_value(true,System::successText("Company created successfully."));
		
	}
	public function prefixWCount($prefix=""){
		
		$count=0;
		$res=$this->db->selectQuery(array('*'),'companies','where company_prefix=\''.$prefix.'\'');
		while($row=mysqli_fetch_row($res)){
			$count+=1;
		}
		
	    if($count>0){
			return $this->prefixWCount($prefix.$count);
		}else{
			return $prefix;
		}
		
	}
	public function deleteCompany($id){
		
		GLOBAL $config;
		
		$results=$this->getCompanies("where id=".$id);
		
		for($i=0;$i<count($results);$i++){
		  
		  $this->db->deleteQuery('companies','where id='.$id);
			
		  $res=$this->db->generalQuery('show tables where tables_in_'.$config->db_name.' like \'%'.$results[$i]->company_prefix.'pr_%\'');
		  
		  $tnames=array();
			
		   while($row=mysqli_fetch_row($res)){
			$tnames[]=$row[0];
		    //$this->db->generalQuery('drop table '.$prefix.$row[0]);
		   }
			
		   $this->db->generalQuery('delete from frontend_users where company_id='.$id);
			
		  for($c=0;$c<count($tnames);$c++){
			  $this->db->generalQuery('drop table '.$tnames[$c]);
		  }
		
		  System::deleteFolder(dirname(__FILE__).'/../../mpFile/'.$results[$i]->company_prefix.'ufiles/');
			
		}
		
	}
	//---------------------------------------------END COMPANY FUNCTIONS-------------------------------
	//-----------------------------------------------WORK SCHEDULE----------------------------------------
	public function getWorkSchedule($whereclause="",$format="%d/%b/%Y"){
		
	   $res=$this->db->selectQuery(array('id','description','date_format(fromdate,"'.$format.'")','date_format(toDate,"'.$format.'")','datediff(toDate,fromdate)','(datediff(toDate,fromdate)-datediff(now(),fromdate))','projectId'),'pr_workschedule',$whereclause);
		
		$results=array();
		
		while($row=mysqli_fetch_row($res)){
			$wsh=new workSchedule;
			$wsh->wk_id=$row[0];
			$wsh->wk_description=$row[1];
			$wsh->wk_frmDate=$row[2];
			$wsh->wk_toDate=$row[3];
			$wsh->wk_projectId=$row[6];
			$wsh->wk_days=$row[4];
			$wsh->wk_remaining=$row[5];
			$results[]=$wsh;
		}
		
		return $results;
	}
	public function addWorkSchedule($desc,$frm_date,$to_date,$pid){
		
		$this->db->insertQuery(array('description','fromdate','toDate','projectId'),'pr_workschedule',array("'".str_replace("'","''",$desc)."'",$this->dateFormatForDb($frm_date),$this->dateFormatForDb($to_date),$pid));
		
	}
	public function updateWorkSchedule($desc,$frm_date,$to_date,$id){
	
		$this->db->updateQuery(array('description='."'".str_replace("'","''",$desc)."'",'fromdate='.$this->dateFormatForDb($frm_date),'toDate='.$this->dateFormatForDb($to_date)),'pr_workschedule','where id='.$id);
		
	}
	public function getMaterialEstimates($whereclause=""){
		
		$results=array();
		
		$res=$this->db->selectQuery(array('id','description','qty','component_id','unitType','(select sum(qty) from '.$this->cmp->company_prefix.'pr_issueditems where sch_id=component_id and '.$this->cmp->company_prefix.'pr_issueditems.description='.$this->cmp->company_prefix.'pr_materialestimates.description) as matissued'),'pr_materialestimates',$whereclause);
		
		while($row=mysqli_fetch_row($res)){
			$est=new matEst;
			$est->mat_id=$row[0];
			$est->mat_description=$row[1];
			$est->mat_qty=$row[2];
			$est->mat_unitType=$row[4];
			$est->mat_component=$row[3];
			$est->mat_issued=$row[5]==""? 0.0:$row[5];
			$results[]=$est;
		}
		
		return $results;
		
	}
	public function getMaterialUsage($component_id){
		
		$me=$this->getMaterialEstimates('where component_id='.$component_id);
		
		$qty=0;
		
		$used=0;
		
		foreach($me as $m){
			$qty+=$m->mat_qty;
			
			$used+=$m->mat_issued;
		}
		
		if(($used!=0)&($qty!=0)){
		return number_format(($used/$qty)*100,2);
		}else{ return 0;}
		
	}
	public function addMaterialEst($desc,$qty,$unit,$cid){
		
		$this->db->insertQuery(array('description','qty','component_id','unittype'),'pr_materialestimates',array("'".str_replace("'","''",$desc)."'",$qty,$cid,"'".str_replace("'","''",$unit)."'"));
		
	}
	public function deleteMaterialEst($id){
		
		$this->db->deleteQuery('pr_materialestimates','where id='.$id);
		
	}
	//----------------------------------------------END WORK SCHEDULE-------------------------------------
	//---------------------------------------------------ADD ITMES----------------------------------------
	public function addIssuedItem($descrip,$qty,$unit,$id){
		
		$this->db->insertQuery(array('description','qty','unit','sch_id'),'pr_issueditems',array("'".$descrip."'",$qty,"'".$unit."'",$id));
		
	}	
	//----------------------------------------------END ADD ITEMS------------------------------------------
	//----------------------------------------------STOCK BALANCES-----------------------------------------
	public function getStockBalances(){
		$comp=$this->cmp->company_prefix;
		$res=$this->db->selectQuery(array('description','sum(qty)','unit_type',"(select sum(qty) from ".$comp."pr_issueditems where ".$comp."pr_issueditems.description= ".$comp."pr_receiveditems.description) as issued"),"pr_receiveditems",'group by description');
		
		$results=array();
		
		while($row=mysqli_fetch_row($res)){
			$stB=new stBalance;
	        $stB->bal_description=$row[0];
	        $stB->bal_receivedQty=$row[1];
	        $stB->bal_issuedQty=$row[3];
	        $stB->bal_balance=($stB->bal_receivedQty-$stB->bal_issuedQty);
	        $stB->bal_unit=$row[2];
			$results[]=$stB;
		}
			
		return $results;
	}
	
	//--------------------------------------------END STOCK BALANCES---------------------------------------

}
class project{
	public $project_id;
	public $project_name;
	public $project_start;
	public $project_end;
	public $project_location;
	public $project_manager;
	public $project_entryDate;
	public $project_budget;
	public $project_quote;
	public $project_clientId;
	public $project_description;
}
class site{
	public $site_id;
	public $site_name;
	public $site_created;
	public $site_project;
	public $site_clerk;
	public $site_location;
}
class Requis{
	public $req_id;
	public $req_projectId;
	public $req_siteId;
	public $req_no;
	public $req_date;
	public $req_byId;
	public $req_byName;
	public $req_status;
	public $req_level0;
	public $req_level1;
	public $req_level2;
	public $req_level3;
	public $req_level4;
	public $req_level5;
	public $req_level0Date;
	public $req_level1Date;
	public $req_level2Date;
	public $req_level3Date;
	public $req_level4Date;
	public $req_level5Date;
	public $req_level0ApproveName;
	public $req_level1ApproveName;
	public $req_level2ApproveName;
	public $req_level3ApproveName;
	public $req_level4ApproveName;
	public $req_level5ApproveName;
	public $req_level0ApproveId;
	public $req_level1ApproveId;
	public $req_level2ApproveId;
	public $req_level3ApproveId;
	public $req_level4ApproveId;
	public $req_level5ApproveId;
	public $req_approvedDate;
	public $req_extType;
	public $req_isLinked;
	public $req_verifiedById;
    public $req_verifiedByName;
}
class requis_item{
	public $item_id;
	public $item_requestId;
	public $item_projectId;
	public $item_siteId;
	public $item_description;
	public $item_qty;
	public $item_unit;
	public $item_rate;
	public $item_amount;
	public $item_remarks;
	public $item_no;
}
class uDetails{
	public $user_id;
	public $user_name;
	public $user_userType;
	public $user_userEmail;
	public $user_company;
}
class lData{
	public $l_id;
	public $l_project;
	public $l_site;
	public $l_date;
	public $l_data;
	public $l_ot;
	public $l_wage;
	public $l_type;
	public $l_extType;
}
class boqItem{
	public $item_id;
	public $item_projectId;
	public $item_description;
	public $item_qty;
	public $item_unit;
	public $item_rate;
	public $item_amount;
}
class sItem{
	public $item_id;
	public $item_description;
	public $item_qty;
	public $item_unitType;
	public $item_siteId;
	public $item_itemType;
	public $item_entryDate;
	public $item_postedBy;
}
class pItem{
	public $item_projectId;
	public $item_siteId;
	public $item_description;
	public $item_requestId;
	public $item_qty;
	public $item_unit;
	public $item_byId;
	public $item_byName;
	public $item_purchaseId;
	public $item_requested;
	public $item_theRate;
	public $item_no;
}
class equip{
	public $e_id;
	public $e_regCode;
	public $e_description;
	public $e_recordedById;
	public $e_recordedByName;
	public $e_recordedDate;
	public $e_type;
	public $e_site;
}
class equipLocation{
	public $loc_siteId;
	public $loc_tDate;
	public $loc_byName;
	public $loc_byId;
	public $loc_siteTitle;
	public $loc_regCode;
	public $loc_desctiption;
}
class equipTransfer{
	public $tr_id;
	public $tr_sourceId;
	public $tr_destId;
	public $tr_sourceName;
	public $tr_destName;
	public $tr_equipId;
	public $tr_equipCode;
	public $tr_equipName;
	public $tr_byName;
}
class sAssign{
	public $a_siteId;
	public $a_siteName;
	public $a_userId;
	public $a_userName;
	public $a_status;
	public $a_date;
	public $a_byId;
	public $a_byName;
}
class resetLabour{
	public $request_id;
	public $request_entry;
	public $request_date;
	public $request_byName;
	public $request_byId;
	public $request_siteId;
	public $request_status;
	public $request_labourType;
	public $request_processedByName;
	public $request_processedById;
}
class previewData{
	public $print_userId;
	public $print_colStyle;
	public $print_colNames;
	public $print_colValue;
	public $print_title;
	public $print_mData;
	public $print_mStyle;
}
class fmMp{
	public $fm_id;
	public $fm_date;
	public $fm_format;
	public $fm_byId;
	public $fm_name;
}
class alerts{
	public $alert_date;
	public $alert_status;
	public $alert_userId;
	public $alert_description;
	public $alert_type;
	public $alert_uid;
}
class userNotify{
	public $notify_userId;
	public $notify_sms;
	public $notify_email;
}
class vPur{
	public $v_itemId;
	public $v_price;
}
class officeProc{
	public $proc_id;
	public $proc_date;
	public $proc_no;
	public $proc_byId;
	public $proc_byName;
	public $proc_appById;
	public $proc_appByName;
	public $proc_siteId;
	public $proc_total;
	public $proc_status;
}
class opItem{
	public $op_itemId;
	public $op_itemDesc;
	public $op_qty;
	public $op_pid;
	public $op_amount;
}
class income{
	public $inc_id;
	public $inc_date;
	public $inc_invoice;
	public $inc_description;
	public $inc_amount;
	public $inc_site;
	public $inc_byid;
	public $inc_byname;
}
class pettyCash{
	public $pc_id;
	public $pc_date;
	public $pc_description;
	public $pc_siteId;
	public $pc_amount;
	public $pc_byId;
	public $pc_byName;
}
class RItemSearch{
	public $item_id;
	public $item_description;
	public $item_requestDate;
	public $item_rNumber;
	public $item_rId;
	public $item_qty;
	public $item_unit;
	public $item_siteId;
	public $item_byName;
}
class theCompany{
	public $company_id;
	public $company_name;
	public $company_status;
	public $company_prefix;
	public $company_created;
}
class workSchedule{
	public $wk_id;
	public $wk_description;
	public $wk_frmDate;
	public $wk_toDate;
	public $wk_projectId;
	public $wk_days;
	public $wk_remaining;
}
class stBalance{
	public $bal_description;
	public $bal_receivedQty;
	public $bal_issuedQty;
	public $bal_balance;
	public $bal_unit;
}
class matEst{
	public $mat_id;
	public $mat_description;
	public $mat_qty;
	public $mat_unitType;
	public $mat_component;
	public $mat_issued;
}
?>