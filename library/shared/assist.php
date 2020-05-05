<?php

require_once __DIR__."/../shared_modules/mpdf/autoload.php";

 function assist(){	 return new m_assist(); }

class m_assist{
   private $db;
   private $Qlib;
   private $pr;private $um;
  public function __construct(){
      GLOBAL $db;
      $this->db=$db;
	  $this->Qlib=System::shared("proman_lib");
	  $this->qr=new HQuince;
	  $this->um=System::shared("usermanager");
  }
	public function viewEquipRequests(){
		$cont=new objectString();
		
		
		return $this->qr->equipmentTransfers();
	}
	public function getEquiptTranfers($whereclause,$value=''){
		
		if($value=='')
			$value="a.equip_value";
		
		$res=$this->db->selectQueryJoin(array('b.site_title','c.description','a.t_date','a.transfer_byname','a.isActive',$value,'a.source_id'),array('pr_equiplocation a','pr_sites b','pr_equipment c'),"where a.equip_id=c.regcode and b.id=a.site_id ".$whereclause);
		
		$data=array();
		
       while($row=mysqli_fetch_row($res)){
		    $loc=new equipLocation;
			$loc->loc_siteId=$row[0];
			$loc->loc_tDate=$row[2];
			$loc->loc_byName=$row[3];
			$loc->loc_byId=$row[4];
			$loc->loc_siteTitle=$row[6];
			$loc->loc_regCode=$row[5];
			$loc->loc_desctiption=$row[1];
			$results[]=$loc;
	   }
		return( $data);
	}
	public function updatedEquipmentLayout($cs,$id){
		switch ($cs){
			case 1:
				$this->db->updateQuery(array('status=2'),'pr_equiptransfer','where id='.$_POST['id']);
				
					return( new name_value(true,'The Eqiupment has been Declined'));
				break;
			case 2:
				$eqip=$this->Qlib->getTransferRequests(" and a.id=".$_POST['id'].' and a.status=0')[0];
					
				if($_POST['sr'] !='-2'){
														
					
					
					$res=$this->getEquiptTranfers(" and a.site_id=".$_POST['sr']." and a.equip_id='".$eqip->tr_equipCode."' limit 1");
					
					
					if(count($res)){
						if($res[0]->loc_regCode < $_POST['vl']){
							return new name_value(false," ".$eqip->tr_equipName." value Exceeds that of Source ");
						}else{
							$this->updateQuery(array('equip_value='.($res[0]->loc_regCode-$_POST['vl'])),'pr_equiplocation',"where site_id=".$_POST['sr']." and equip_id=".$eqip->tr_equipCode);
						}
					}else{
						return new name_value(false," ".$eqip->tr_equipName." does not Exist in that Source ");
					}
					
				}if($eqip->tr_value == $_POST['vl']){
						$this->db->updateQuery(array('status=1'),'pr_equiptransfer','where id='.$eqip->tr_id);
						
					}else if($eqip->tr_value >$_POST['vl']){
						$this->db->updateQuery(array("equid_value=".($eqip->tr_value-$_POST['vl'])),'pr_equiptransfer','where id='.$eqip->tr_id);
					}else{
						return new name_value(true,"Equipment ".$eqip->tr_equipName." amount has surpassed request ");
					}
					$this->db->insertQuery(array('site_id','equip_id','transfer_byid','transfer_byname','equip_value','source_id'),'pr_equiplocation',array(" ".$eqip->tr_destName."","'".$eqip->tr_equipCode."'","'".$this->Qlib->ud->user_id."'","'".$this->Qlib->ud->user_name."'","".$_POST['vl']."","'".$_POST['sr']."'"));
					
					return new name_value(true,"Equipment ".$eqip->tr_equipName." request has been processed");
			case 3:
				//$this->db->updateQuery(array("equip_id='".$_POST['typ']."' "),"pr_equiptransfer"," where id=".$id);
				
				return $this->viewEquipRequests();
				
				
		}
	}
	public function loadEquipment(){
		$cont=new objectString();
		
		$sites=$this->Qlib->getSites();
		
		
		$cont->generalTags("<div class='qs_wrap a3-margin-left'><select class='quince_select'>");
		
		$cont->generalTags("<option value='-2'>---All Equipment---</option>");
		
		foreach($sites as $site){
			$cont->generalTags("<option value='".$site->site_id."'>".$site->site_name."</option>");
		}
		
		$cont->generalTags("</select></div>");
	
		$cont->generalTags("<div class='a3-padding a3-left a3-full a3-margin-top'>".$this->equipmentTable()."</div>");
		
		return($cont->toString());
	}
	public function equipments($site,$added){
		$res=$this->db->selectQuery(array('id','regcode','description','sum(equid_value)','(select sum(equid_value) from '.$this->Qlib->cmp->company_prefix.'pr_equiptransfer where equip_id='.$this->Qlib->cmp->company_prefix.'pr_equipment.regcode and dest_site='.$site.' limit 1 )','(select sum(equid_value) from '.$this->Qlib->cmp->company_prefix.'pr_equiplocation where site_id="'.$site.'" and equip_id='.$this->Qlib->cmp->company_prefix.'pr_equipment.regcode limit 1)'),'pr_equipment',$added);
		
		$data=array();
		
		while($row=mysqli_fetch_row($res)){
			$data[]=array(
            "id" => $row[0],
            "regcode" => $row[1],
            "description" => html_entity_decode($row[2]),
            "value" => $row[3],
            "transfer" => $row[4],
            "instore" => $row[5]
           );
		}
		
		return $data;
	}
	public function loadProjectEquipment($site){
			
		$cont=new objectString();
		
		$list=new open_table;
		
		$list->setColumnNames(array('No','Equiment Name','Requests','in Store',' '));
		
		$list->setColumnWidths(array('10%','35%','15%',"15%","15%",'5%'));
		
		$data=$this->equipments($site,'group by regcode');
		
		
		foreach($data as $val=>$equip){
			$list->addItem(array($val+1,$equip["description"],$mm=($equip["transfer"]=="") ? 0 : $equip["transfer"],$mm=($equip["instore"]=="") ? 0 : $equip["instore"],'<div class="xpand" id="eM_'.$equip["regcode"].'_main"></div>' ));
			
			 $list->addDataRow($this->expandDiv($equip["regcode"],$equip["description"]));
		}
		
		$list->setHoverColor('#cbe3f8');
		
		$list->showTable();
		
		$cont->generalTags("<div class='a3-left a3-padding' style='width:99%'>".$list->toString()."</div>");
		
		return $cont->toString();
	}
	public function equipmentTable($case=0){
		$list=new open_table;
		
		$list->setColumnNames(array('No','Equiment Name'," Stock Qty",'Requests','Comments ',' '));
		
		$list->setColumnWidths(array('10%','35%','15%',"15%","15%",'5%'));
		
		$equip=$this->getEquipment('group by regcode ');
		
		for($i=0;$i<count($equip);$i++){
			//$list->addItem(array($i+1,$equip[$i]->name,$equip[$i]->value,$equip[$i]->status,$equip[$i]->dest_site,'<div class="xpand" id="eQ_'.$equip[$i]->id.'_main"></div>'));
			$list->addItem(array($i+1,$equip[$i]->name,$ww=($equip[$i]->value=='') ? 0 :$equip[$i]->value-$equip[$i]->dest_site,$ww=($equip[$i]->status=='') ? 0 :$equip[$i]->status,'',''));
			
			 $list->addDataRow($this->expandDiv($equip[$i]->id,$equip[$i]->name));
		}
		
		$list->setHoverColor('#cbe3f8');
		
		$list->showTable();
		
		return $list->toString();
	}
	function getEQstatus($status){
		switch($status){
			case 0:
				return "<div class='a3-blue a3-round a3-padding-left a3-center' style='font-size:14px'>Not Processed</div>";
			case 1:
				return "<div class='a3-green a3-round a3-padding-left a3-center' style='font-size:14px'>issued to Store</div>";
			case 2:
				return "<div class='a3-red a3-round a3-padding-left a3-center' style='font-size:14px'>Request Declined</div>";
		}
	}
	public function processListDetails(){
		$list=new open_table;
		
		switch(explode('_',$_POST['bt'])[0]){
			case 'eM':
				$list->hideHeader(true);

				$list->addItem(array('<b>Equipment Name',"Requested By",'Date Requested','Qty','Status','</b> '));

				$list->setColumnWidths(array('25%','15%','15%',"15%","20%","10%"));

				$equip=$this->Qlib->getTransferRequests(" and c.regcode='".explode("_",$_POST['bt'])[1]."'");
				
				for($i=0;$i<count($equip);$i++)
					$list->addItem(array($equip[$i]->tr_equipName,$equip[$i]->tr_byName,$equip[$i]->tr_date,$equip[$i]->tr_value,$this->getEQstatus($equip[$i]->tr_status),''));
				
				$list->setHoverColor('#cbe3f8');
		
		        $list->showTable();
				
				break;
			default:
				$eq=explode("_",$_POST['bt'])[1];
		
				$list->hideHeader(true);

				$list->addItem(array('<b>Site Name',"Stock Count",'Requested','</b> '));

				$list->setColumnWidths(array('20%','25%','15%',"15%"));

				$equip=$this->getEquipment('where id='.$eq,true);
				
				$list->setHoverColor('#cbe3f8');
		
		        $list->showTable();
				
				return( $list->toString());
		}
		
		
		
		
		/*for($i=0;$i<count($equip);$i++){
			$list->addItem(array($i+1,$equip[$i]->e_site,$equip[$i]->value,$equip[$i]->status,$equip[$i]->dest_site,'<div class="xpand" id="eu_'.$equip[$i]->id.'_main"></div>'));
			
			 //$list->addDataRow($this->expandDiv($equip[$i]->id,$equip[$i]->name));
		}*/
		
		
		
		return $list->toString();
	}
	
	public function getEquipment( $whereclause=""){
		$res=$this->db->selectQuery(array("id",'description','sum(equid_value)','(select sum(equid_value) from '.$this->Qlib->cmp->company_prefix.'pr_equiptransfer where  equip_id='.$this->Qlib->cmp->company_prefix.'pr_equipment.regcode)','(select sum(equip_value) from '.$this->Qlib->cmp->company_prefix.'pr_equiplocation where equip_id='.$this->Qlib->cmp->company_prefix.'pr_equipment.regcode)'),"pr_equipment",$whereclause);
		
		$data=array();
		
		while($row=mysqli_fetch_row($res)){
			$eq=new equipTransferEQ();
			$eq->id=$row[0];
			$eq->name=$row[1];
			$eq->value=$row[2];
			$eq->status=$row[3];
			$eq->dest_site=$row[4];
			
			
			$data[]=$eq;
		}
		return($data);
	}
	public function equipmentArray($data,$pr,$usr_id,$usr_name,$Ssite=-1){
		$dt=json_decode($data);
		
		$site=$this->Qlib->getMyActiveSite();
		
		if($site ==null)
			return;
		
		foreach($dt as $equip){

			$this->db->insertQuery(array("dest_site","equip_id","by_id","byname","status","thedate","equid_desc","equid_value","source_site"),"pr_equiptransfer",array("'".$site->site_id."'","'".str_replace("'","''",$equip[0])."'","'".$usr_id."'","'".$usr_name."'",'0',"curdate()","'".$equip[1]."'","'".$equip[2]."'","0"));
		}
			//$
			
			
		
		
	}
	public function issueItemData(){
		$res=$this->db->selectQuery(array(" description ,(select material_unit from ".$this->Qlib->cmp->company_prefix."pr_itemuploads where description=".$this->Qlib->cmp->company_prefix."pr_purchaseditems.description limit 1)"),"pr_purchaseditems",'group by description');
		
		$data=array();
		
		while($row=mysqli_fetch_row($res)){
			
			$data[]=array($row[0],$row[1]);
		}
		
		return $data;
		
	}
	public function updatedMaterialTableSpace($case,$defined){
		$cont=new objectString();
		switch($case){
			case 1:
				$list=new open_table;
		
				$list->setColumnNames(array('No','Item Description','Qty'));
		
				
		        $list->setColumnWidths(array('5%',"22%",'20%'));
		
				$list->setEditableColumns(array(1,2));
				
				$list->hideHeader(true);
				
		     	$list->addItem(array("","Click to type description","Click to type quatity"));
				
				$list->showTable();
				
				$cont->generalTags($list->toString());
				
				$cont->generalTags("<div class='w3-left ' id='mat_$defined'></div>");
				
		
				break;
			case 3:
				
				if(isset($_POST['dat'])){
					$data=array();$data=json_decode($_POST['dat']);					
					
					for($i=0;$i<count($data);$i++){
					   
					    if( $data[$i][1] !=''){
							 
						    $this->addMaterialToTask($_POST['id'],$_POST['pId'],$data[$i][0],$data[$i][1],$data[$i][3],$data[$i][2]);
						}
					}
				}
				
				$cont->generalTags($this->scheduleOfWork($_POST['pId']));

				break;
			case 4:
				
				$levs=$this->Qlib->getALevels("order by thelevel asc");

                $req=$this->Qlib->getRequisitions("where level".(count($levs)-1)."=1 and requisition_status=0 and project_id=".$_POST['cdef']);
				
				$store=$this->getStores("where is_assigned=".$_POST['cdef']." and user_id <>".$this->Qlib->ud->user_id);
				
				if(count($store) >1){
					$cont->generalTags("<div id='label'> Select Store</div>");
				
				    $cont->generalTags("<div class='qs_wrap'><select class='inv_select' id='rm' name='store'>");
				
				    $cont->generalTags("<option value='-1'>Select Store</option>");
                  foreach($store as $st)
                      $cont->generalTags("<option value='".$st->id."'> Requisition ".$st->content."</option>");

				    $cont->generalTags("</select></div>");
				}
					
				
				$cont->generalTags("<div id='label'> Select Requisition</div>");
				
				$cont->generalTags("<div class='qs_wrap'><select class='inv_select' id='rm' name='consign'>");
				
				$cont->generalTags("<option value='-1'>Select Requisition</option>");
				foreach($req as $requisition)
					$cont->generalTags("<option value='".$requisition->req_id."'> Requisition ".$requisition->req_id."</option>");
				
				$cont->generalTags("</select></div>");
				
				break;
			case 5:
				$val=$_POST['bt'];


				$boq=$this->Qlib->getMaterialEstimates("where component_id=".$_POST['id']." and description='boq'");

				if($_POST['val'] !=""){
                    if(count($boq)){
                        if($boq[0]->mat_qty !=''){
                            $this->db->updateQuery(array("".$val." ='".$_POST['val']."'"),'pr_materialestimates',"where component_id=".$_POST['id']." and description='boq'");
                        }else{
                            $this->db->insertQuery(array("".$val."",'description','component_id'),'pr_materialestimates',array("'".$_POST['val']."'","'boq'","'".$_POST['id']."'"));
                        }
                    }else{
                        $this->db->insertQuery(array("".$val."",'description','component_id'),'pr_materialestimates',array("'".$_POST['val']."'","'boq'","'".$_POST['id']."'"));
                    }
				}
				break;
		}
		return $cont->toString();
	}
	private function addMaterialToTask($id,$pId,$desc,$val,$qun="def",$cost="0"){
		$this->checkMaterialUploads($desc,$qun);
		
		$this->db->insertQuery(array("description,qty,component_id,unitType,component_is_child,cost","byName","byId"),"pr_materialestimates",array("'".$desc."'","'".$val."'","'".$pId."'","'".$qun."'","'-2'","'".$cost."'","10","10"));
	}
	public function updatedAdaptedList($whereclause="",$enable=true){

		/*$data=array();$data=$this->getRegItems_db($whereclause);

		$list=new open_table;

		$list->setColumnNames(array('No','Description','Quantity',"Unit"));

		$list->setColumnWidths(array('4%','30%','20%','20%'));		

		$list->setEditableColumns(array(2));

		$list->setNumberColumns(array(4));

		$list->setHoverColor('#cbe3f8');
	   
	   for ($i=0;$i<count($data);$i++){
					$list->addItem(array($i+1,$data[$i]->name," click here  ",$data[$i]->desc));
		   
	   }
	   $list->showTable();

	  return $list->toString();*/
		$data=array();$data=$this->getRegItems_db("");

		$cont=new objectString();
		
		$cont->generalTags("<input type='hidden' id='allow_edit' value='true'>");


		$cont->generalTags("<div class='a3-left a3-border a3-round  mat-container' style='width:98.5%'>");

		$cont->generalTags("<div class='a3-container  a3-left a3-padding a3-full a3-border-bottom a3-border-orange'> 
		<b class='a3-left a3-half a3-header'> Category Name </b> 
		<b class='a3-left a3-half a3-header'>Expand</b> 
		</div>");
		 foreach($data as $category){
			 if($category->category ==0 || $category->category ==""){
                 $cont->generalTags("<div class='a3-container a3-left a3-full tbl-cont a3-border-bottom' id='cat_".$category->id."'> 
			         <div class='a3-left a3-half a3-padding'>".$category->name." </div> 
			        <div class='a3-left a3-half  a3-padding'><i class='xpand3 a3-text-black'></i></div> 
			      ");
				 $list=new open_table;

				 $list->setId($category->id);

		         $list->setColumnNames(array('No','Description','Quantity',"Unit"));
		
		         $list->setColumnWidths(array('4%','20%','10%','20%'));		

		         $list->setEditableColumns(array(2,3));

		         $list->setNumberColumns(array(4));

				 $list->setHoverColor('#cbe3f8');
				
				for ($i=0;$i<count($data);$i++){
					 if($data[$i]->category == $category->id)
					 		$list->addItem(array($i+1,$data[$i]->name," Quantity here",$data[$i]->desc));
					
				}
				$list->showTable();

		        $cont->generalTags("<div class='a3-left a3-full a3-hidden'>".$list->toString()."</div>");

			    $cont->generalTags("</div>");
			 }
			
			
		 }
		 $cont->generalTags("<div class='a3-container a3-left a3-full tbl-cont a3-border-bottom' id='cat_oth'> 
			 <div class='a3-left a3-half a3-padding'>Additional Construction Materials </div> 
			<div class='a3-left a3-half  a3-padding'><i class='xpand3 a3-text-black'></i></div>");

			$list=new open_table;

			$list->setId('oth');

		    $list->setColumnNames(array('No','Description','Quantity',"Unit"));
		
		    $list->setColumnWidths(array('4%','20%','10%','20%'));		

		    $list->setEditableColumns(array(2));

		    $list->setNumberColumns(array(4));

			$list->setHoverColor('#cbe3f8');
				
				
			$list->showTable();

		    $cont->generalTags("<div class='a3-left a3-full a3-hidden'>".$list->toString()."</div>");

			$cont->generalTags("</div>");

    	$cont->generalTags("</div>");
		
		return $cont->toString();

	}
	public function updatedAdaptedList2($whereclause="",$enable=true){
		
		$data=array();$data=$this->getRegItems_db("");

		$cont=new objectString();
		
		$cont->generalTags("<input type='hidden' id='allow_edit' value='true'>");


		$cont->generalTags("<div class='a3-left a3-border a3-round  mat-container' style='width:98.5%'>");

		$cont->generalTags("<div class='a3-container  a3-left a3-padding a3-full a3-border-bottom a3-border-orange'> 
		<b class='a3-left a3-half a3-header'> Category Name </b> 
		<b class='a3-left a3-half a3-header'>Expand</b> 
		</div>");
		 foreach($data as $category){
			 if($category->category ==0 || $category->category ==""){
                 $cont->generalTags("<div class='a3-container a3-left a3-full tbl-cont a3-border-bottom' id='cat_".$category->id."'> 
			         <div class='a3-left a3-half a3-padding'>".$category->name." </div> 
			        <div class='a3-left a3-half  a3-padding'><i class='xpand3 a3-text-black'></i></div> 
			      ");
				 $list=new open_table;

				 $list->setId($category->id);

		         $list->setColumnNames(array('No','Description','Quantity',"Cost","Unit"));
		
		         $list->setColumnWidths(array('4%','20%','10%','10%','20%'));		

		         $list->setEditableColumns(array(2,3));

		         $list->setNumberColumns(array(4));

				 $list->setHoverColor('#cbe3f8');
				
				for ($i=0;$i<count($data);$i++){
					 if($data[$i]->category == $category->id)
					 		$list->addItem(array($i+1,$data[$i]->name," Quantity here","Cost Here",$data[$i]->desc));
					
				}
				$list->showTable();

		        $cont->generalTags("<div class='a3-left a3-full a3-hidden'>".$list->toString()."</div>");

			    $cont->generalTags("</div>");
			 }
			
			
		 }
		 $cont->generalTags("<div class='a3-container a3-left a3-full tbl-cont a3-border-bottom' id='cat_oth'> 
			 <div class='a3-left a3-half a3-padding'>Additional Construction Materials </div> 
			<div class='a3-left a3-half  a3-padding'><i class='xpand3 a3-text-black'></i></div>");

			$list=new open_table;

			$list->setId('oth');

		    $list->setColumnNames(array('No','Description','Quantity',"Cost","Unit"));

		    $list->setBorder(array(1,2,3),'blue',1);

		    $list->setColumnWidths(array('4%','20%','10%',"10%",'20%'));		

		    $list->setEditableColumns(array(2));

		    $list->setNumberColumns(array(4));

			$list->setHoverColor('#cbe3f8');
				
				
			$list->showTable();

		    $cont->generalTags("<div class='a3-left a3-full a3-hidden'>".$list->toString()."</div>");

			$cont->generalTags("</div>");

    	$cont->generalTags("</div>");
		
		return $cont->toString();
	}
	public function macroEquimentList($whereclause=''){
		$list=new open_table;
		
		$list->setColumnNames(array('No','Equipment Name','Reg Code',' ',"Action"));
		
		$list->setColumnWidths(array('10%','36%','17%','0%','15%'));
		
		$eq=$this->Qlib->getEquipment();
		
		for($i=0;$i<count($eq);$i++)
			$list->addItem(array(($i+1),$eq[$i]->e_regCode,$eq[$i]->e_description,$eq[$i]->e_id,"<div class='sm-padding a3-blue a3-border a3-round a3-center' style='width:65px' onclick='addItem(this)'>+ Add </div>"));
		
		
		$list->showTable();

		return $list->toString();
	}
	public function updatedTabletWorkSpace($pn){
		$cont=new objectString();
		
		switch($pn){
			case 1:

			$cont->generalTags($this->taskCreateFunction($_POST["id"]));

			break;
			case 2:
				$cont->generalTags("<div class='a3-full a3-padding a3-left '>");
				
				$cont->generalTags("<div class='left a3-full ' style='margin-bottom:2%'>Appending materials : Materials will be directly linked to this component</div>");
				
				$cont->generalTags("<div class='right a3-button a3-round a3-padding a3-margin a3-blue mater' id='app_".$_POST["id"]."'>Submit Materials</div>");
				
				$cont->generalTags($this->updatedAdaptedList2(" where is_category=0",true));
				
				$cont->generalTags("<div class='right a3-button a3-round a3-padding a3-margin a3-blue mater' id='crt_".$_POST["id"]."'>Create Materials</div>");
				
				$cont->generalTags("<section class='a3-hidden a3-full left mat_holder'></section>");
				
				$cont->generalTags("</div>");

				break;
			case "uMp":
				$cont->generalTags($this->updateCompanyLayout());
				break;
			default:
				
				return "<div class='nFound'>nothing has been found</div>";
				break;
			
		}
		
		return $cont->toString();
	}
	public function processTaskOfWork($cs){
		$sch="";
		if(isset($_POST['sch']))
			$sch=$_POST['sch'];
		
		switch($cs){
            case 0:
                return "hellow";
			case 1:
				$this->db->insertQuery(array('assignedto','fromdate','toDate','description','is_component','projectId'),'pr_workschedule',array("'".$_POST['sr']."'",$this->Qlib->dateFormatForDb($_POST['st']),$this->Qlib->dateFormatForDb($_POST['en']),"'".$_POST['tt']."'","'".$_POST['sch']."'","'".$_POST['pj']."'"));
				break;
			case 2:
				$cont=new objectString();
				
				$tsk=$this->getTasks("where id=".$_POST['id'])[0];
				
				$cont->generalTags("<div class='a3-left a3-container a3-white a3-margin a3-round' style='width:60%;margin-left:25% !important;min-height:300px;background:white' id='tk_name'><h3>Edit Task</h3>");
				
				$cont->generalTags("<div class='a3-left a3-margin-top a3-full'><label class='a3-full a3-left a3-padding-left'>Task Name </label><textarea style='height:40px' type='text' placeholder='".$tsk->tk_description."' class='a3-left a3-round a3-full a3-border a3-padding' id='tk_name'></textarea></div>");
				
				$cont->generalTags("<div class='a3-left a3-margin-top a3-full'><label class='a3-full a3-left a3-padding-left'>Start Date </label><input id='tk_sDate' type='text' style='padding-left:45px !important' value='".$tsk->tk_stDate."' class='quince_date a3-left a3-round a3-full a3-border a3-padding'></div>");
				
				$cont->generalTags("<div class='a3-left a3-margin-top a3-full'><label class='a3-full a3-left a3-padding-left'>End Date</label><input type='text' id='tk_eDate' style='padding-left:45px !important' value='".$tsk->tk_enDate."' class='quince_date a3-left a3-round a3-full a3-border a3-padding'></div>");
				
				$sel=new objectString();
				
				$sel->generalTags("<select class='quince_select' id='ass'>");
				
				$sel->generalTags("<option value='".$this->Qlib->ud->user_id."'>Me</option>");
				
				$user=System::shared("usermanager");
  
		        $data=$user->getUsers(" where id <>".$this->Qlib->ud->user_id." and company_id=".$this->Qlib->ud->user_company);
  
               foreach($data as $users)
                 $sel->generalTags("<option value='".$users->user_id."'>".$users->user_name."</option>");

                $sel->generalTags("</select>");

                 $cont->generalTags("<div class='a3-left a3-margin-top a3-full'><label class='a3-full a3-left a3-padding-left'>Assign task</label><div class='qs_wrap' style='width:180px'> ".$sel->toString()."</div>");
				
				$cont->generalTags("<div class='a3-right a3-margin-top a3-margin-bottom'><label class=' a3-left a3-padding a3-blue a3-border a3-round a3-hover-green a3-margin-right task' id='upT_".$_POST['id']."'>Update Task</label><label class=' a3-left a3-padding  a3-border a3-round a3-red a3-margin-left task' id='can_".$_POST['id']."'>Cancel</label></div>");
				
				$cont->generalTags("</div>");
				
				return $cont->toString();
				break;
			case 3:
				$tk=$this->getTasks("where id=".$_POST['id'])[0];
				
				$sch=$tk->tk_dad; 
				
				$this->db->deleteQuery('pr_workschedule',"where id=".$_POST['id']);
				
				break;
			case 4:
			  	$tk=$this->getTasks("where id=".$_POST['id'])[0];
				
				$sch=$tk->tk_dad; 
				//ALTER TABLE AmBapr_requisitions ADD COLUMN clevels VARCHAR(100) NOT NULL DEFAULT 0;
				
				if($_POST['nm']==''){
					$this->db->updateQuery(array("description='".$_POST['nm']."'"),"pr_workschedule","where id=".$_POST['id']);
				}else if($_POST['st']){
                    $this->db->updateQuery(array("fromdate=".$this->dateFormatForDb(date('d/m/Y',strtotime($_POST['st'])))),"pr_workschedule","where id=".$_POST['id']);
				}else if($_POST['en']){
                    $this->db->updateQuery(array("toDate=".$this->dateFormatForDb(date('d/m/Y',strtotime($_POST['en'])))),"pr_workschedule","where id=".$_POST['id']);
				}else if($_POST['as']){
					$this->db->updateQuery(array("assignedto=".$_POST['as']),"pr_workschedule","where id=".$_POST['id']);
				}
				
				// $this->db->updateQuery(array("description='".$_POST['nm']."'","fromdate=".$this->dateFormatForDb(date('d/m/Y',strtotime($_POST['st']))),"toDate=".$this->dateFormatForDb(date('d/m/Y',strtotime($_POST['en']))),"assignedto=".$_POST['as']),"pr_workschedule","where id=".$_POST['id']);
				break;
			case 5:
				$this->db->deleteQuery('pr_materialestimates','where id='.$_POST['id']);
			
				return $this->getMatEstimateTable("where component_id=".$_POST['cm']." and component_is_child=1",$_POST['cm'],0);
				break;
			case 6:
				
				$cm=$this->db->updateQuery(array("qty=".$_POST['vl']),'pr_materialestimates',"where component_id =".$_POST['cm']." and description='".$_POST['ds']."'");

				return $this->getMatEstimateTable("where component_id=".$_POST['cm']."and component_is_child=1",$_POST['cm'],1);
            case 7:
                return $this->taskOfWork($_POST['sch']);

            case 8:
                return new name_value(true,$this->taskMaterialHandler($_POST['sch'],$_POST['id']));
            case 9:

               $data=json_decode($_POST['dt']);

              for($i=0;$i<count($data);$i++)
                   if($data[$i][1])
                       $this->db->insertQuery(array('description','component_id','qty','unitType','cost','byName','byId','component_is_child'),'pr_materialestimates',array("'".$data[$i][0]."'","'".$_POST['sch']."'","'".$data[$i][1]."'","'".$data[$i][3]."'","'".(is_numeric($data[$i][2]) ? $data[$i][2] :0 ) ."'","'".$this->Qlib->ud->user_name."'","'".$this->Qlib->ud->user_id."'","'".$_POST['dad']."'"));

                if(!$this->Qlib->positionHasPrivilege($this->Qlib->ud->user_userType,70))
                    $cm=($this->getMatEstimateTable("where component_id='".$_POST['sch']."' and component_is_child=-2 ",$_POST['sch'],1));
                else
                    $cm=($this->getMaterialEstimateTable("where component_id='".$_POST['sch']."' group by description ",$_POST['sch'],1));

                return new name_value(true,'Materials Added succesfully ',$cm);
            case 10:

                $this->db->insertQuery(array('type','content','component_id','byid','byname'),'pr_asset',array("'1'","'".$_POST['cont']."'","'".$_POST['sch']."'","'".$this->Qlib->ud->user_id."'","'".$this->Qlib->ud->user_name."'" ));

                return new name_value(true,"idjid");
                break;
            case 11:
                switch(explode("_",$_POST['sch'])[0]){
                    case "gimage":
                        return $this->getTaskImages(explode("_",$_POST['sch'])[1]);
                    case "gnote":
                        return $this->getTaskNotes(explode("_",$_POST['sch'])[1]);
                    case "gmat":
                        return $this->getMaterialEstimatesForTasks(" where component_id=".explode("_",$_POST['sch'])[1],explode('_',$_POST['sch'])[1]);
                }
                break;
            case 12 :
                return $this->scheduleOfWork($_POST['sch'],$this->Qlib->getWorkSchedule('where id='.$_POST['sch'])[0]->wk_projectId);

            case 13:
                $cont= new objectString();

                $cont->generalTags("<header><div class='a3-left a3-half'><b> Issue ".$_POST['desc']." to Tasks</b></div><div class='a3-right a3-red a3-text-white a3-pointer' onclick='close'>&times;</div></header>");

                $cont->generalTags("<div class='a3-left issues a3-full'>");

                $cont->generalTags("<span class='a3-left a3-full table'>");

                $tasks=$this->Qlib->getMaterialEstimates("where description='".$_POST['desc']."'  and component_is_child='".$_POST['id']."' group by component_id asc ",0);

                //print_r("where description='".$_POST['desc']."'  and component_is_child='".$_POST['id']."' ");

                $cont->generalTags("<input type='hidden' id='desc' value='".$_POST['desc']."'>");
                $list= new objectString();

                $list->generalTags("<div class='a3-left header a3-full  a3-padding-top a3-padding-bottom'> <span class='a3-left a3-border-right ' style='width:30%'>Task Name</span> <span style='width:25%' class='a3-left a3-center a3-border-right'> Estimated Materials</span> <span class='a3-left a3-center a3-border-right ' style='width:20%'>Issued Materials</span><span style='width:15%'>Action</span></span></div>");



                for($i=0;$i<count($tasks);$i++){
                    $tk=$this->getTasks("where id=".$tasks[$i]->mat_component);

                   $border=($i % 2) !=' ' ? "a3-white" :  "a3-light-grey";

                    if(count($tk))
                        $list->generalTags("<div class='a3-left $border a3-full a3-padding-right a3-padding-left tpow'> <span class='a3-left a3-border-right' id='cl_".$tk[0]->tk_id."_0' style='width:30%'>". $tk[0]->tk_description." </span> <span class=' a3-left a3-border-right' id='cl_".$tk[0]->tk_id."_1' style='width:25%'>".$tasks[$i]->mat_qty." ".$tasks[$i]->mat_unitType." </span> <span class='edit a3-left a3-border-right' id='cl_".$tk[0]->tk_id."_2' style='width:20%'>".$tasks[$i]->mat_issued." ".$tasks[$i]->mat_unitType."</span> <span class='edit a3-left' id='cl_".$tk[0]->tk_id."_3' style='width:15%'> </span> </div>");


                }

                $cont->generalTags($list->toString()."</span>");

                $cont->generalTags("</div>");

                return $cont->toString();
            case 14:

                $this->setIssuedMaterials( $_POST['desc'],$_POST['val'],'tem',"-2",$_POST['cmp'],"-1");

                return  new name_value(true,"material Issued succesfully");
            case 15:
                $hq= new HQuince();

                return $hq->receiveList("where component_id=".$_POST['val']);
                default:
				$this->db->insertQuery(array('assinedto','st_date','en_date','name','dadid'),'pr_taskschedule',array("'".$_POST['sr']."'",$this->Qlib->dateFormatForDb($_POST['st']),$this->Qlib->dateFormatForDb($_POST['en']),"'".$_POST['tt']."'","'".$_POST['sch']."'"));
				break;
		}
		
		
		
		return $this->scheduleOfWork($sch);
	}
	public function positionHasPrivilege($position,$type){
		
		return $this->Qlib->positionHasPrivilege($position,$type);
		
	}

	public function taskCreateFunction($id,$prj=""){
		
		$cont=new objectString();

		$cont->generalTags("<div style='margin:1%;width:98%;float:left'>");
	  
		$cont->generalTags("<input type='hidden' id='wkip_$id' class='hans'");
		
		$cont->generalTags("<div class='a3-toggle a3-hidden a3-full a3-lightGrey left'>");
		
		$cont->generalTags("<div class='a3-inputs a3-left a3-full a3-padding'>");
		
		$cont->generalTags("<div class='left a3-full a3-padding'>");
		
		$cont->generalTags("<label class='left' >Task Name </label><input type='text' id='tt_val' autocomplete='off'>");
		
		$cont->generalTags("</div>");
		
		$cont->generalTags("<div class='left a3-full a3-padding'>");
		
		$cont->generalTags("<label class='left' >Start Date </label><input type='text' id='st_date' class='quince_date' autocomplete='off'>");
		
		$cont->generalTags("</div>");
		
		$cont->generalTags("<div class='left a3-full a3-padding'>");
		
		$cont->generalTags("<label class='left' >End Date </label><input type='text' id='en_date' class='quince_date' autocomplete='off'>");
		
		$cont->generalTags("</div>");
		
		$cont->generalTags("<div class='left a3-full a3-padding'>");
		
		if(!$this->positionHasPrivilege($this->Qlib->ud->user_userType,70)|($this->Qlib->ud->user_id==1)){
  
		$sel=new objectString();
  
		$sel->generalTags("<select id='as_user' class='quince_select' style='width:210px'>");
  
		$sel->generalTags("<option value=".$this->Qlib->ud->user_id.">me</option>");
  
		$user=System::shared("usermanager");
  
		$data=$user->getUsers(" where id <>".$this->Qlib->ud->user_id." and company_id=".$this->Qlib->ud->user_company);
  
		 foreach($data as $users)
		   $sel->generalTags("<option value='".$users->user_id."'>".$users->user_name."</option>");
  
		$sel->generalTags("</select>");
  
		$cont->generalTags("<label class='left' >Assign task</label><div class='qs_wrap' style='width:180px'> ".$sel->toString()."</div>");
		
		}else{
   
		  $cont->generalTags("<label class='left a3-hidden' >Assign task</label><input disabled=true value='".$this->Qlib->ud->user_id."' id='as_user'>");
		
		}
		$cont->generalTags("</div>");
		
		$cont->generalTags("<button  id='tskIns_$id' class='cel a3-button a3-blue a3-round'>Create</button>");
		
		$cont->generalTags("</div>");
		
		$cont->generalTags("</div>");
		
		return $cont->toString();
	}
  public function scheduleOfWork($id=0,$prj=0){
	  $cont=new objectString();
	  
	  $cont->generalTags("<div style='margin:1%;width:98%;float:left'>");
	  
	  $cont->generalTags("<input type='hidden' id='wkip_$id' data-cent='$prj' class='hans'");
	  
	  $data=array();$data=$this->Qlib->getWorkSchedule(" where id=".$id);
	  
	  $cont->generalTags("<div class='left a3-margin-left innerTitle a3-padding-left a3-padding-top a3-left a3-full a3-margin-bottom' style='font-size:30px'>Component: ".$data[0]->wk_description."</div>");
	  
	  $cont->generalTags("<div class='a3-toggle  a3-full a3-lightGrey left'>");
	  
	  
	  $cont->generalTags("<div class='taskHolder left a3-full a3-padding' >");
	  
	  $cont->generalTags($this->listTasks($id)); 
	  
	  $cont->generalTags("</div>");

	  $cont->generalTags("<section class='a3-left a3-full'  id='matHolder'>");

	  $cont->generalTags("<fieldset class='a3-round left a3-full matHolder'><legend class='a3-man'><b>Material Estimates</b></legend>");

	  $cont->generalTags("<input type='hidden' value='$id' id='control'>");

	  if(!$this->Qlib->positionHasPrivilege($this->Qlib->ud->user_userType,70))
	      $cont->generalTags($this->getMatEstimateTable("where component_id='".$id."' and component_is_child=-2 ",$id,1));
	  else
	      $cont->generalTags($this->getMaterialEstimateTable("where component_id='".$id."' group by description ",$id,1));

	  $cont->generalTags("</fieldset>");

	  $cont->generalTags("</section>");
	  
	  $cont->generalTags("</div>");
	  
	  return $cont->toString();
  }
	public function taskOfWork($sch){
      $cont= new objectString();

      $ws=$this->getTasks('where id='.$sch);

      $main=$this->Qlib->getWorkSchedule("where id=".$ws[0]->tk_dad);

      $cont->generalTags("<h4 class='a3-padding-top a3-padding-left a3-left a3-full a3-margin-left'>Component Name : ".$main[0]->wk_description."</h4>");

      $cont->generalTags("<input type='hidden' class='hans' id='wkpid_$sch'>");

      $cont->generalTags("<section class='a3-padding a3-left a3-full'><div class='a3-left a3-half'>");

      $cont->generalTags(" <b class='a3-left a3-full'><nav class='a3-left a3-margin-right a3-padding'>Task Name :</nav> <p class='a3-text-primary a3-left' style=\"line-height: .4;\">".$ws[0]->tk_description."</p> </b> <b ><nav class='a3-left a3-margin-right a3-padding'>Assigned to :</nav><p style=\"line-height: .4;\" class='a3-text-primary a3-left'>".$this->getAssignedUser($ws[0]->tk_assinedto)."</p></b>");

      $cont->generalTags("</div>");

      $cont->generalTags('<div class="a3-right a3-half" style="position: relative">');

      $cont->generalTags("<button class='a3-right a3-padding a3-border a3-hover-green tasks a3-light-gray a3-pointer' data-dad='".$main[0]->wk_id."' id='upImg_$sch'><i class='fa fa-camera'></i> Take Photos</button>");

      $cont->generalTags("<form method='post' enctype='multipart/form-data'  id='fileImage'> <input name='component' type='file' id='file' hidden></form> ");

      $cont->generalTags("<button class='a3-right a3-padding a3-border a3-hover-green tasks a3-light-gray a3-pointer' data-dad='".$main[0]->wk_id."' id='note_$sch'><i class='fas fa-pen'></i> Add Note </button>");

      $cont->generalTags("<button class='a3-right a3-padding a3-border a3-hover-green tasks a3-primary a3-light-blue a3-pointer' data-dad='".$main[0]->wk_id."' id='mat_$sch'>+ Add Material </button>");

      $cont->generalTags("<button class='a3-right a3-padding a3-border a3-hover-green tasks a3-light-gray a3-pointer' id='bck_".$main[0]->wk_id."'><i class='fas fa-chevron-left'></i> Back </button>");

      $cont->generalTags("<section class='note_area'>");

      $cont->generalTags("<textarea></textarea> <button class='tasks ' id='not_$sch'>Add Note</button>");

      $cont->generalTags("</section>");

      $cont->generalTags("</div>");

      $cont->generalTags("<div class='a3-left a3-full '>");

      $cont->generalTags("<span id='gnote_$sch' class='a3-container a3-navs tasks a3-pointer'> <p>NOTES</p>  <nav>".count($this->getAssets("where component_id=".$sch." and type=1"))."</nav> </span>");

      $cont->generalTags("<span id='gimage_$sch' class='a3-container a3-navs tasks a3-pointer'> <p>IMAGES<p/> <nav>".count($this->getAssets("where component_id=".$sch." and type=2"))."</nav> </span>");

        $cont->generalTags("<span id='gmat_$sch' class='a3-container a3-navs tasks a3-pointer'> <p>Materials<p/> <nav>".count($this->Qlib->getMaterialEstimates("where component_id=".$sch))."</nav> </span>");

      $cont->generalTags("</div>");

      $cont->generalTags("<div id='mat_container' class='a3-left a3-full'>");

      if(!$this->Qlib->positionHasPrivilege($this->Qlib->ud->user_userType,70))
          $cont->generalTags($this->getMatEstimateTable(" where component_id=".$sch." and (description !='boq' and description <> 2 and description <> 3 and description <> 4 and description  <>5  ) ",$sch,0)."</div>");
      else
          $cont->generalTags($this->getMaterialEstimateTable("where component_id='".$sch."' and description !='boq' and description <> 2 and description <> 3 and description <> 4 and description  <>5   group by description  ",$sch,0));

      $cont->generalTags("</section>");


      return $cont->toString();
    }
	private final function getMatEstimateTable($whereclause,$id='1',$status=0){
		$list=new open_table;$ws=array();

		$table=new open_table();

        $table->setId('boqTabl');

		$table->setColumnNames(array(" ","<div class='a3-center'>Estimated Budget</div>","<div class='a3-center'>Actual Budget</div>","Variance"));
		
		$table->setColumnWidths(array("25%","30%","30%","15%"));
		
		$table->showTable();

		$editTable=new open_table();
		
		$editTable->setEditableColumns(array(2,3,5));
		
		$editTable->setColumnWidths(array('5%',"20%",'10%','10%','10%',"10%","10%","10%",'10%','5%'));
		
		$editTable->setId("boqTable");
		
		$boqQty=$this->Qlib->getMaterialEstimates("where component_id=".$id." and description=2");
		
		$boqRate=$this->Qlib->getMaterialEstimates("where component_id=".$id." and description=3");

        $cQty=$this->Qlib->getMaterialEstimates("where component_id=".$id." and description=5");

		$editTable->setColumnNames(array('<b>No','Description','Qty','Rate','Total','Qty','Rate','Total</b>',' ',' '));
		
		$boqQ=(count($boqQty) ?  $boqQty[0]->mat_qty: "0.00");$boqR=(count($boqRate) ? $boqRate[0]->mat_qty :" 0.00");
		

		$list->setColumnNames(array('No','Description','Quantity','used','Action'));
		
		$list->hideHeader(true);
		
		$list->setId("matEst");

		$list->setEditableColumns(array(2,3));

		$list->setColumnWidths(array('5%',"20%",'10%','10%','10%',"10%","10%","10%",'10%','5%'));

		$list->setBorder(array(4),'blue',1);

		$ws=$this->Qlib->getMaterialEstimates($whereclause." group by description ",$status);

		$totalActualCost=0;
		
		$totalEstimatedCost=0;       

		$totalRate=0;

		for($i=0;$i<count($ws);$i++){

		    $matActualCost=($status !=0 ? $this->getMaterialEstimateActualCost($id,$ws[$i]->mat_description) : $this->getMaterialEstimateActualCostComp($id,$ws[$i]->mat_description));

		    if($ws[$i]->mat_description !='boq' & $ws[$i]->mat_description !='2' & $ws[$i]->mat_description !='3' & $ws[$i]->mat_description !='4' & $ws[$i]->mat_description !='5') {

                $list->addItem(array($i + 1, $ws[$i]->mat_description, $ws[$i]->mat_qty . " " . $ws[$i]->mat_unitType, $ws[$i]->mat_estCost, number_format(($ws[$i]->mat_qty * str_replace(',', '', $ws[$i]->mat_estCost)), 2), $ws[$i]->mat_issued . " " . $ws[$i]->mat_unitType, number_format($matActualCost, 2), number_format(($matActualCost* $ws[$i]->mat_issued), 2), ($ws[$i]->mat_actCost * $ws[$i]->mat_issued) != 0 ? (($ws[$i]->mat_qty * $ws[$i]->mat_estCost) > ($ws[$i]->mat_actCost * $ws[$i]->mat_issued) ? "<div class='a3-left'>" . number_format(($ws[$i]->mat_qty * $ws[$i]->mat_estCost) - ($ws[$i]->mat_actCost * $ws[$i]->mat_issued), 2) . "</div><div class='a3-text-green'> Less</div> " : "<div class='a3-left'>" . number_format(($ws[$i]->mat_actCost * $ws[$i]->mat_issued) - ($ws[$i]->mat_qty * $ws[$i]->mat_estCost), 2) . "</div><div class='a3-text-red a3-left'> More</div>") : 0,$ww=( $status !=0 ? "<i class='fas fa-share-alt a3-left task' id='shr_".$id."'></i>" : " "). "<i class='fas fa-trash a3-margin-left task' id='dl_" . $ws[$i]->mat_id . "'></i>"), $ws[$i]->mat_id);

                $totalActualCost += ($ws[$i]->mat_actCost * $ws[$i]->mat_issued);

                $totalEstimatedCost += ($ws[$i]->mat_estCost * $ws[$i]->mat_qty);

                $totalRate += $ws[$i]->mat_actCost;
            }
		}

        $mBoq=$this->Qlib->getMaterialEstimates("where component_id=".$id." and description='boq'");

        $asList= new open_table();

        $asList->setId("tbAs");

		$asList->setColumnNames(array('No','Description','Quantity','used','Action'));
		
		$asList->hideHeader(true);
		
		$asList->showTable();
		
		$asList->setColumnWidths(array('5%',"20%",'10%','10%','10%',"10%","10%","10%",'15%'));
		

		$asList->addItem(array('','','','','<b></b>','','','</b>',' '));

		$obj= new objectString();
		
		$obj->generalTags("<div class='a3-left  a3-padding-left' style='width:90%;margin-left:10%'>");
		
		$obj->generalTags("<container><b class='a3-left a3-margin'>BOQ QTY:</b> <input autocomplete='off' type='text' id='qty_' class='in-inputs' value='".$ww=(count($mBoq) ? $mBoq[0]->mat_qty : "0.00")."'></container>");
		$obj->generalTags("<container><b class='a3-left a3-margin'>BOQ RATE:</b> <input autocomplete='off' type='text' id='cost_' class='in-inputs' value='".$ww=(count($mBoq) ? number_format($mBoq[0]->mat_estCost,2): "0.00")."'></container>");
		$obj->generalTags("<container><b class='a3-left a3-margin'>BOQ TOTAL: </b><input autocomplete='off' type='text' id='tot_' class='in-inputs' style='font-weight: bolder' value='".$ww=(count($mBoq) ? number_format(($mBoq[0]->mat_qty*str_replace(',','',$mBoq[0]->mat_estCost)),"2") : "0.00")."' disabled></container>");
		
		$obj->generalTags("</div>");

		
        $editTable->addItem(array('<b>','Budget Summary',
            $boqQ,$boqR,
            number_format(($boqQ*$boqR),"2"),
            $ww=(count($cQty) ? $cQty[0]->mat_qty : 0 ),
            number_format($totalRate,2),
            number_format($ww=(count($cQty) ? $cQty[0]->mat_qty : 0 )*$totalRate,2).'</b>',
            count($cQty) ? ($cQty[0]->mat_qty != $boqQ ? ( $cQty[0]->mat_qty >$boqQ ? "<div class='a3-left'>".(   $cQty[0]->mat_qty-$boqQ)."</div><div class='a3-text-green'>More</div>" : "<div class='a3-left'>".( $cQty[0]->mat_qty- $boqQ )."</div><div class='a3-text-red'>Less</div>"  )  : 0   ) : 0  ,' '),
            '-3');

        if(count($mBoq))
            $asList->addItem(array('','<b>Profit / Loss</b>','','','<b>'. number_format((($mBoq[0]->mat_qty*str_replace(',','',$mBoq[0]->mat_estCost))-($boqQ*$boqR)),2).'</b>','','','<b>'.number_format(($mBoq[0]->mat_qty*str_replace(',','',$mBoq[0]->mat_estCost))-((count($cQty) ? $cQty[0]->mat_qty : 0 )*$totalRate),2).'</b>',' '));

        $editTable->showTable();

        $obj->generalTags("<input type='hidden' id='control' value='".$id."'>");

        $asList->showTable();

        $list->showTable();
		
		$obj->generalTags($table->toString());
		
		$obj->generalTags($editTable->toString());
		
		$obj->generalTags($list->toString());
		
		$obj->generalTags($asList->toString());
		
		return $obj->toString();
	}
	private final function getMaterialEstimateTable($whereclause,$status=true){
        $ws=$this->Qlib->getMaterialEstimates($whereclause,$status);

        $list= new open_table();

        $list->setColumnNames(array("id","Description","Estimated Qty","Used Qty"));

        $list->setId("ltdEst");

        $list->setColumnWidths(array("5%","30%","10%","10%","10%"));

        $list->setEditableColumns(array(2,3));

        for($i=0;$i<count($ws);$i++)
            $list->addItem(array( ($i+1),$ws[$i]->mat_description,$ws[$i]->mat_qty,$ws[$i]->mat_issued ));

        $list->showTable();

        return $list->toString();
    }
    public function getMaterialEstimatesForTasks($whereclause,$id){
      /*  $ws=$this->Qlib->getMaterialEstimates($whereclause);

        $object=new objectString();

        $list= new open_table();

        $oList= new open_table();

        $oList->setColumnNames(array(" ","<b class='a3-full a3-left a3-center'>Estimated</b>","<b class='a3-full a3-center a3-left'>Actual</b>"," "));

        $list->setColumnNames(array("id","Description","Qty","Rate","Total","Qty","Rate","Total","Action"));

        $oList->setId("overList");

        $list->setId("ltdEst");

        $oList->setColumnWidths(array("25%","30%","30%","10%"));

        $list->setColumnWidths(array("5%","20%","10%","10%","10%","10%","10%","10%","10%"));

        $list->setEditableColumns(array(2,3,5));

        for($i=0;$i<count($ws);$i++)
            $list->addItem(array( ($i+1),$ws[$i]->mat_description,$ws[$i]->mat_qty,number_format($ws[$i]->mat_estCost,2), number_format( $ws[$i]->mat_qty*$ws[$i]->mat_estCost ,2),$ws[$i]->mat_issued,$ws[$i]->mat_actCost,number_format($ws[$i]->mat_issued*$ws[$i]->mat_actCost,2),' ' ));

        $oList->showTable();

        $list->showTable();

        $object->generalTags($oList->toString().$list->toString());

        $object->generalTags( "<div class='a3-right'>" );

        $object->generalTags("</div>");*/

        return $this->getMatEstimateTable($whereclause,$id,0);
    }
    public function getMaterialEstimateActualCostComp($taskId,$desc){

      $comp=$this->Qlib->getWorkschedule("where id=".$taskId,"%d/%b/%Y",1);

      if(count($comp))
          return $this->getMaterialEstimateActualCost($comp[0]->wk_projectId,$desc);
       else
           return 0;
    }
    public function taskMaterialHandler($comp,$task){
      $cont= new objectString();

      $mats=$this->Qlib->getMaterialEstimates("where component_id=".$comp." and (description <> 2 and description <> 'boq' and description<> 3 and description<>4  and description<> 5) group by description ");

      $cont->generalTags("<input type='hidden' id='allow_edit' value='1'>");

      $cont->generalTags("<div class='a3-full a3-left a3-margin'> <button class='a3-green a3-margin-right a3-right a3-round a3-button a3-primary a3-hover-green a3-pointer tasks' id='estMat_$task'>Submit Materials</button></div>");

      $list= new open_table();

      $list->setColumnNames(array("id","Description","Quantity","Cost","Units"));

      $list->setEditableColumns(array(2,3));

      $list->setColumnWidths(array("5%","20%","10%","10%","10%"));

      $list->setId("tskEstimate");

      for($i=0;$i<count($mats);$i++)
          $list->addItem(array( ($i+1),$mats[$i]->mat_description,"Type Quantity","Type cost",$mats[$i]->mat_unitType));

      $list->showTable();

      $cont->generalTags($list->toString());

      $cont->generalTags("<div class='tbl_ins am_btn'>Create Material</div>");

      return $cont->toString();

    }
    public function handleTaskFileUpload($name){
         $task=$this->getTasks("where id='".$_GET['fid']."'");

         if(count($task)==0)
             return;

         $id=$this->getAssets("where component_id='".$task[0]->tk_id."'");

         $imageName=$task[0]->tk_description.count($id);

         $path_part=pathinfo($_FILES[$name]['name']);

         $imageName=str_replace(" ","_",$imageName);

         $new_location=dirname(__FILE__)."/../../mpFile/".$this->Qlib->cmp->company_prefix."ufiles/".$imageName.".".$path_part['extension'];

         if($_FILES[$name]['tmp_name'] !=''){
             $status=move_uploaded_file( $_FILES[$name]['tmp_name'],$new_location);
         }else{
             $status=move_uploaded_file( $_FILES[$name]['name'],$new_location);
         }


         if($status)
             $this->db->insertQuery(array('type','content','component_id','byid','byName'),'pr_asset',array("'2'","'".$imageName.".".$path_part['extension']."'","'".$task[0]->tk_id."'","'".$this->Qlib->ud->user_id."'","'".$this->Qlib->ud->user_name."'"));

         return new name_value(true,'Image Succesfully Uploaded',"mpFile/".$this->Qlib->cmp->company_prefix."ufiles/".$imageName.".".$path_part['extension']."");
    }
     public function getTaskImages($sch){
        $images=$this->getAssets("where component_id=".$sch." and type=2 order by id DESC");

        $cont= new objectString();

        $path="mpFile/".$this->Qlib->cmp->company_prefix."ufiles/";

        $cont->generalTags("<div class='a3-flex a3-left a3-full' id='images'> ");

        foreach ($images as $img)
            $cont->generalTags("<span class='image'> <img src='".$path.$img->as_content."'/ > <i class='fas fa-pen tasks' id='pen_".$img->as_id."'></i> </span>");

        $cont->generalTags("</div>");

        return $cont->toString();
     }
     public function getTaskNotes($sch){
      $notes =$this->getAssets(" where component_id=".$sch." and type=1 order by id DESC");

      $cont= new objectString();

      $cont->generalTags("<section class='a3-left a3-full'>");

      foreach ($notes as $note)
          $cont->generalTags("<span class='note_s'><nav>".($note->as_byName[0])." </nav>
         <p>".($note->as_content)."</p><article class='a3-left' style='color:#0d87cd'>".($note->as_byName)."</article><small>".($note->as_dateProcessed)."</small></span>");

      $cont->generalTags("</section>");

      return $cont->toString();
     }
	public function componentSelections($siteId){
      $comp=$this->Qlib->getWorkSchedule("where projectId=".$siteId);

       $cont= new objectString();

       $cont->generalTags("<label class='a3-left a3-padding a3-margin-right'>Attach to Component</label>");

       $cont->generalTags("<div class='qs_wrap'><select class='quince_select cSelect' name='selectComponent'>");

        $cont->generalTags("<option value='-1'>No Component</option>");

       for($i=0;$i<count($comp);$i++){
           $cont->generalTags("<option value='".$comp[$i]->wk_id."'>".$comp[$i]->wk_description."</option>");
       }
       $cont->generalTags('</select> </div>');

       return " ";
    }
	public function getComponentImages(){
		$img=array();$img=$this->getAssets(" where type=1");
		
		
	}
	public function getMaterialEstimateActualCost($pId,$desc){


     $req= $this->Qlib->getRequisitions("where extType='".$pId."'");

     if(count($req)) {

         $mat = $this->Qlib->getRequestItemPurchase("where request_id='" . $req[0]->req_id . "' and project_id='".$req[0]->req_projectId."' and description='" . $desc . "' ");

        if(count($mat))
             return $mat[0][1];
         else
             return 0;
     }

    }
	public final function schMenus($id,$prj){

		$cont=new objectString();

	  $cont->generalTags("<div class='right'>");

	  $cont->generalTags("<input type='hidden' id='allow_edit'>");
	  
	  $cont->generalTags("<div class='a3-tab a3-round a3-padding a3-blue left a3-margin-right' id='cWk_$id'>Create Task +</div>");
	  
	  $cont->generalTags("<div class='a3-tab a3-round a3-padding a3-green left a3-margin-right a3-tab' id='tCmp_$prj'>Components</div>");
	  
	  $cont->generalTags("<div class='a3-tab a3-round a3-padding a3-yellow left a3-margin-right a3-tab' id='app_$id'> + Add Materials / Labour</div>");
	  
	  $cont->generalTags("</div>");

	  return $cont->toString();
	}
	public function getAssets($whereclause=""){
		$res=$this->db->selectQuery(array("id,type,content,project_id,component_id,byid,byName,date_processed,status,contents"),"pr_asset",$whereclause);
		 
		$data=array();
		
		while ($row=mysqli_fetch_row($res)){
			$as=new dbAsset();
			
			$as->as_id=$row[0];
			$as->as_type=$row[1];
			$as->as_content=$row[2];
			$as->as_projectId=$row[3];
			$as->as_componentId=$row[4];
			$as->as_byId=$row[5];
			$as->as_byName=$row[6];
			$as->as_dateProcessed=$row[7];
			$as->as_status=$row[8];
			$as->as_contents=$row[9];
			
			$data[]=$as;
		}
		return $data;
	}
	
    private function statTasks($case =0){
		$color="#0CA678 !important";
		
		$cont=new objectString();
		
		$cont->generalTags("<div style='background-color:".$color.";color:white;margin-left: 10%;width: 80%;padding: 1%;border-radius: 4px;text-align:center'>In progress</div>");
		
		return $cont->toString();
	}
	public function listTasks($id){
		$list=new open_table;
		
		$list->setColumnNames(array('No','Status','Task','Start Date','To Date','Remaining',"Task Handler",' '));
		
		$list->setColumnWidths(array('5%',"12%",'20%','10%','10%','10%','20%','10%','5%'));
		
		$ws=$this->getTasks('where is_component='.$id);
		
		for($i=0;$i<count($ws);$i++){
		 
			$list->addItem(array($i+1,$this->statTasks($ws[$i]->tk_status),$ws[$i]->tk_description,$ws[$i]->tk_stDate,$ws[$i]->tk_enDate,($wk=$ws[$i]->tk_time>-1 ? $ws[$i]->tk_time : 0). ' days',$this->getAssignedUser($ws[$i]->tk_assinedto),'<i class="fas fa-pen a3-hover-text-blue task a3-left" id="upd_'.$ws[$i]->tk_id.'"></i><i class="a3-left fas fa-chevron-circle-down task a3-hover-text-green task a3-margin-left a3-margin-right sm-padding" id="exp_'.$ws[$i]->tk_id.'"></i><i class="a3-left fas fa-trash a3-margin-left a3-hover-text-red task" id="del_'.$ws[$i]->tk_id.'"></i>'),'m-'.$ws[$i]->tk_id);
		
		}
		
		$list->showTable();
		
		return $list->toString();
	}
	public function getTasks($whereclause){
		$data=array();
		
		$res=$this->db->selectQuery(array("id","projectId","assignedto","status","status","verifiedbywho","is_component",'date_format(fromdate,"%d-%b-%Y")','date_format(toDate,"%d-%b-%Y")','datediff(toDate,fromdate)','(datediff(toDate,fromdate)-datediff(now(),fromdate))','description'),"pr_workschedule",$whereclause);
		
		while($row=mysqli_fetch_row($res)){
			$task=new taskSchedule();
			$task->tk_id=$row[0];
			$task->tk_data=$row[1];
			$task->tk_assinedto=$row[2];
			$task->tk_status=$row[3];
			$task->tk_veri=$row[4];
			$task->tk_veriWho=$row[5];
			$task->tk_dad=$row[6];
			$task->tk_time=$row[9];
			$task->tk_remaining=$row[10];
			$task->tk_stDate=$row[7];
			$task->tk_enDate=$row[8];
			$task->tk_description=$row[11];
			$data[]=$task;
		}
		return $data;
	}


  public function getRegItems_db($whereclause){
		$data=array();
		$res=$this->db->selectQuery(array("*"),"pr_itemuploads",$whereclause);
		 while($row=mysqli_fetch_row($res)){
				$dat=new items_req();
				$dat->id=$row[0];
				$dat->name=$row[1];
			    $dat->desc=$row[3];
			    $dat->category=$row[2];
                $data[]=$dat;
		 }
	  
		 return $data;
  }  
  public function get_aligned_table($id){
   $data=array();

   $data=$this->getRegItems_db("where material_category='".$id."' limit 20");


	$list=new open_table;
	
	$list->setColumnNames(array('Item Description','Value '));

	$list->setColumnWidths(array('30%','20%'));

	$list->setNumberColumns(array(2));
		//$list->setSearchColumn(1);
	$list->setHoverColor('#cbe3f8');
	
	$list->setEditableColumns(array(1));
	
    $list->hideHeader();

    for ($i=0;$i<count($data);$i++){

			$list->addItem(array("<div style='padding-left:2%;color:rgb(0,0,0,0.7);' id='nm_".$data[$i]->id."' class='sage'>".ucfirst(strtolower($data[$i]->name)."</div>"),'Enter value'),"rw_".$data[$i]->id);

		    }
			$list->showTable();

		return $list->toString();

  }

  public function req_data_insertion($lid,$site=0){
      $helper=new theProLib;

	  

      $siteId=$site;
      
      $arr=json_decode($lid);
      
	   if($site !=0){
		   $siteId=$_POST['sId'];
	   }else{
		   $site=$helper->getMyActiveSite();
	  
		   $siteId=$site->site_id;
	   }
            $comp='-1';

            if(isset($_POST['comp'])){
              
                $comp=$_POST['comp'];
				
				$dbComp=$helper->getWorkSchedule("where id=".$_POST['comp']);
				
				$siteId=$dbComp[0]->wk_projectId;
            }
  

	   $helper->saveRequisition($arr,$siteId,'-1',$comp);
	  
	  
	  
    return new name_value(true,System::successText("Requisition  submitted successfully!"));
  }
  
  function table_display($data){
    $cont=new objectString();

    $submit= new input();

    $submit->setClass('a_btn');

    $submit->setId('submit');

    $submit->input('button','C_test','Submit');

    $cancel= new input;

		$cancel->setClass('a_btn');

		$cancel->setId('cancel');

    $cancel->input('button','C_test','cancel');


    $value=array();

    $value=json_decode($data);

    $list=new open_table;

    $list->setColumnNames(array('Item Description','value ','Edit',"Delete"));

    $list->setColumnWidths(array('60%','15%','5%','7%'));

    $list->setNumberColumns(array(4));

    $list->setHoverColor('#cbe3f8');

    //$list->hideHeader();

   for ($i=0;$i<count($value);$i++){

      $c_arr=array();

      $c_arr=explode("/",$value[$i]);

      $list->addItem(array("<div style='padding-left:2%;color:rgba(0,0,0,0.7);' id='".$c_arr[0]."' class='sage' data-item='".$this->getUnit($c_arr[1])."'>".ucfirst($c_arr[1])."</div>",$c_arr[2],"<button class='ed edm'></button>","<button class='del' id=".$c_arr[0]."></button>"));

   }
   $list->showTable();

   $cont->generalTags("<div class='m_tables'>".$list->toString()."</div>");

   $cont->generalTags("<div class='m_roll'>".$submit->toString().$cancel->toString()."</div>");

   return $cont->toString();
  }
	
 private function getUnit($name){
	 $res= $this->db->selectQuery(array('material_unit'),'pr_itemuploads',"where material_name='".$name."'");
	  
	  while($row=mysqli_fetch_row($res)){
		  return $row[0];
	  }
  }
	
 public function get_table_content($const){
    switch($const){

		case 1:

			return $this->table_display(System::getArrayElementValue($_POST,'dt'));

			break;

		case 2:
           
			if(isset($_POST['sId']))
				return $this->req_data_insertion(System::getArrayElementValue($_POST,"dat"),$_POST['sId']);

			else
				return $this->req_data_insertion(System::getArrayElementValue($_POST,"dat"));
			
			


		case 3:

            return $this->adaptedist(System::getArrayElementValue($_POST,"elm"));

			break;

		case 4:

			return  $this->getDeleteCompanyRequest();

         break;

		case 5:

			 return "is_functioning 163t67";

			break;
		case 8:
			
			echo print_r($this->createTbl());
			
			break;
	}
  }
	
   public function adaptedList(){
		$hq=new HQuince;
		
	   $cont=new objectString();

	   $list=new open_table;

	   $list->setColumnNames(array('No','Description','Quantity',"Expand"));
		
	   $list->setColumnWidths(array('4%','20%','20%','10'));

	   $list->setNumberColumns(array(4));

	   $list->setHoverColor('#cbe3f8');

		$data=$this->getRegItems_db("where is_category=1 ");

        for ($i=0;$i<count($data);$i++){

            $list->addItem(array($i+1,$data[$i]->name,"   ",'<div class="shwDet" id="djdj_'.$data[$i]->id.'"></div>'),"rw_".$data[$i]->id);
            $list->addDataRow($hq->expandDiv($data[$i]->id, ""));
        }

	  $list->showTable();

		return $list->toString();

	}
  public function getDeleteCompanyRequest(){
		 $cont=new objectString();

		 $cont->generalTags("<div class=''>By clicking Ok the following will happen:-</div>");

		 $cont->generalTags("<ol>");

		 $cont->generalTags("<li>Loss of all your information.</li>");

		 $cont->generalTags("<li>No access to the project control service.</li>");

		 $cont->generalTags("<li>Any other person trying to log into the website will be blocked </li>");

		 $cont->generalTags("</ol>");

		 $cont->generalTags("<a style='color:red'>Show disclaimer</a>");

		 $cont->generalTags("<div class='disc' style='display:none'>Manpro Company<br/>".$this->showDisclaimer()."</div>");

		 $cont->generalTags("<button type='button' id='mn_1' class='btn-ui btn-round btn'>ok</button>");

		 $cont->generalTags("<button type='button' id='nm_2' class='btn-ui btn-round btn-left-4 btn'>cancel</button>");

		 return $cont->toString();
	 }
	
 
  private function processDeleteCompanyRequest(){
		 /*  use the user id and the company id also include the  */
		 $this->db->insertQueryCustom(array(),"","");
	 }
	
  public function showDisclaimer(){

		$cont=new objectString();

		$cont->generalTags("<textarea>Will not be liable to</textarea>");

		return $cont->toString();
	}
	
  public function getTables(){
		 //select table_name from information_schema.tables where table_name like 'pr_%' and table_schema='proman'
		//select table_name,table_schema from information_schema.tables where table_name like '%pr_%' and table_schema='proman'
		 
		 $res=$this->db->selectQueryCustom(array("table_name"),"information_schema.tables"," where table_name like '%pr_%' and table_schema='proman'");
		 
		 while($row=mysqli_fetch_row($res)){
		    $data[]=$row[0];	 
		 }
		 
		 return $data;
	 }
	
  public function setResetRequest($email_address,&$acode){
    
    $activation_code=System::generateCode(6);
    
    $acode=$activation_code;
    
    $this->db->updateQuery(array("activation_code='".$activation_code."'"),"frontend_users","where username='".$email_address."'",'');
    
   }
	
  public function createTableManpro($data){
		$tables=array();
		$local="";
		for($i=0;$i<count($data);$i++){
			$tmp=array();
			$tmp=explode("_",$data[$i]);
			if(!empty($local)){
				if($local !=$tmp[0]){
					array_push($tables,$local);
					$local=$tmp[0];
				}
			}else{
				$local=$tmp[0];
			}
		}
		return $tables;
		
	}
	
  public function createTbl(){
		$pref=$this->createTableManpro($this->getTables());
		$tables=$this->getMainTables();
		for($i=0;$i<count($pref);$i++){
			for($p=0;$p<count($tables);$p++){
				$tmp=explode("_",$tables[$p]);
				  if(count($tmp) >0){
					  $imp=$this->db->insertQueryCustom("CREATE TABLE IF NOT EXISTS " .$pref[$i]."_".$tmp[1]." like ".$tables[$p]."  ");
				     if(!$imp){
						 $this->table_seter($pref."_".$tmp[1]);
					 }
				  }
				
			}
		}
		return "1";
	}
	
  public function getMainTables(){
		$res=$this->db->selectQuery(array(" table_name ")," information_schema.tables ","where table_name like 'pr_%' and table_schema='proman'");
		while ($row=mysqli_fetch_row($res)){
			$data[]=$row[0];
		}
		return $data;
	}
	
  public function setProjectComponents($list,$project){
		$arr=json_decode($list);
		
		for($i=0;$i<count($arr);$i++){
			   if($arr[$i][3]==0){
				   //give priority to the functions which are activities then fetch the information from the database getting the parent id's;
			   }// $this->db->insertQuery(array('description','fromdate','todate',"projectid","is_activity"),"pr_workschedule",array("'".$arr[$i][0]."'",$this->Qlib->dateFormatForDb($arr[$i][2]),$this->Qlib->dateFormatForDb($arr[$i][1],true),"".$project."","".$arr[$i][3].""));
		}
		
	}
	
  public function showComponents($proj){
		$pr=0;
		if(isset($_POST['list'])){
			
			$this->setProjectComponents($_POST['list'],$proj);
			$pr=1;
		}
		
		$cont=new objectString();
		
		$cont->generalTags("<div class='am-container' id='".$proj."'>");
				
		$cont->generalTags('<div class="q_row">');
		
		$btn=new input;
		
		$btn->setClass('form_button addCoo');
		
		$btn->setTagOptions('style="float:right;margin-top:-1px;" ');
		
		$btn->input('button','newsch','+ Add Component');
		
		$pr=$this->Qlib->getProject($proj);
		
		$cont->generalTags('<div class="thePopW"></div>');
		
		$cont->generalTags('<div class="innerTitle" style="width:100%;float:left;margin-bottom:3px;">Schedule Of Works - '.$pr->project_name.'</div>');
		
		$cont->generalTags('<div class="q_row">'.$btn->toString().'<div class="" style="display:none">Upload Scedules</div></div>');
			
		$cont->generalTags('<div class="thePop"></div>');
		
		$cont->generalTags('<div class="newSch" id="wkp_'.$proj.'" style="margin-bottom:10px;display:none;">');
		
		//$cont->generalTags($this->addResultsBar());
		
		$cont->generalTags('<div class="q_row">New Component</div>');
		
		$cmp=new input;
		
		$cmp->setClass('txtField');
		
		$cmp->setId('txtCompTitle');
		
		$cmp->input('text','newComponent');
		
		$addBtn=new input;
		
		$addBtn->setClass('saveData');
		
		$addBtn->setId('wrkSch');
		
		$addBtn->setTagOptions('style="margin-top:-1px;margin-left:20px;" autocomplete=\'off\'');
		
		$addBtn->input('button','addComp','Add Component');
		
		$from_date=new input;
		
		$from_date->setClass('quince_date');
		
		$from_date->setId('fromDate');
		
		$from_date->input('text','text');

		$from_date->setTagOptions('autocomplete=\'off\'');

		$toDate=new input;
		
		$toDate->setClass('quince_date');
		
		$toDate->setId('toDate');
		
		$toDate->input('text','toDate','');

		$toDate->setTagOptions('autocomplete=\'off\'');
				
		$cont->generalTags('<div class="q_row"><div style="float:left;overflow:hidden;"><div id="label" >From Date</div>'.$from_date->toString().' <div id="label" style="width:50px;margin-left:20px;">To Date</div>'.$toDate->toString().'</div></div>');
		
		$cont->generalTags('<div class="q_row"><div id="label">Component Title</div>'.$cmp->toString().'</div>');
		
		$cont->generalTags('<div class="q_row"><div id="label" style="width:80px;"></div>'.$addBtn->toString().'</div>');
		
		$cont->generalTags('</div>');
		/*if(1==0){
			$cont->generalTags("<div class=''>".$this->qr->excelImporter(true,array(new name_value("Name","desc"),new name_value("From Date","quanty"),new name_value("To Date","units"),new name_value("Parent","prent")))."</div>");
		
		    $cont->generalTags("<div class='saveFAList' id='sup_10' style='display:none'>Save Schedules</div>");
		
		    $cont->generalTags("<div class='btn-upload saveList-data btn-ui' style='padding:10px 25px;background:#1d99f9;border-radius:4px;float:right;color:white;display:none'>Save Scedules ".count($this->getMaterialStatus("where activity_id=0"))." </div>");
		}*/
		
				
		$cont->generalTags('<div class="lstC jaxR" style="width:100%;float:left;" id="ReqList">'.$this->qr->listComponents($proj).'</div>');
		
		
		$cont->generalTags('</div>');
		
		
		return $cont->toString();
		
	}
	
 public function showActivities($cid){
	 $cont=new objectString();
	 
	 
	 $cont->generalTags("<div class='am-container'>");
	 
	    $txtField=new input;
		
		$txtField->setClass('txtField');
		
		$txtField->input('text','txt');

		$txtField->setTagOptions("autocomplete='off'");
		
		$mEstimates=new input;
		
		$mEstimates->setClass('txtField');
		
		$mEstimates->setId('desc_'.$cid);
		
		$mEstimates->input('text','mEst');

		$mEstimates->setTagOptions("autocomplete='off'");

		$qty=new input;
		
		$qty->setClass('txtField unitQty');
		
		$qty->setId('iqty_'.$cid);
		
		$qty->setTagOptions('style="width:50px;text-align:right;" autocomplete=\'off\'');
		
		$qty->input('text','qty');
		
		$unit=new input;
		
		$unit->setClass('txtField');
		
		$unit->setId('txtUnit_'.$cid);
		
		$unit->setTagOptions('style="width:50px;" autocomplete=\'off\'');
		
		$unit->input('text','unitTxt','');
		
		$addB=new input;
		
		$addB->setClass('saveData svbtn_'.$cid);
		
		$addB->setId('addCItem');
		
		$addB->setTagOptions('style="margin-top:-1px;margin-left:10px;padding-top:5px!important" title="Add Item" ');
		
		$addB->input('button','addb',' + ');
		
		$cont->generalTags($this->qr->addResultsBar('addCItem_'.$cid));
		
		$cont->generalTags('<div class="q_row"><div style="float:left;padding-top:5px;margin-right:10px;">MATERIAL ESTIMATES</div><div style="float:left;width:100%;margin-top:20px;"><div id="label" style="width:80px;">Description.</div>'.$mEstimates->toString().'<div id="label" style="width:30px;margin-left:20px;">Qty</div>'.$qty->toString().'<div id="label" style="width:30px;margin-left:20px;">Unit</div>'.$unit->toString().' '.$addB->toString().'</div></div>');
	 
	 $cont->generalTags($this->activities($cid));
	 
	 $cont->generalTags("</div>");
	 
	 return $cont->toString();
 }
	
 public function activities($cid){
		
		$list=new open_table();
		
	    $list->setColumnNames(array("No","Activity","From Date","To Date","Duration","Remaining","%",' '));
		
		$list->setColumnWidths(array('5%','27%',"15%","15%","10%","10%","5%","10%"));
		
		$list->setNumberColumns(array(9));
		
		$list->setHoverColor('#cbe3f8');
		
        $list->setEditableColumns(array(1));
		
	    $data=$this->getActivitities("where is_activity='".$cid."'");
	 
	    for($i=0;$i<count($data);$i++){
			$list->addItem(array($i+1,$data[$i]->name,$data[$i]->stDate,$data[$i]->toDate,$data[$i]->peri." Days","10%","<div class='iTl' id='aCt_".$cid."-".$data[$i]->p_id."'></div>"));
		}		
	    $list->showTable();

		return $list->toString();
}
	private function dateNormal($date){
         $uTime=explode(" ",$date);
		
		 $ntime=explode("-",$uTime[0]);
		
		return " ".$ntime[2]."/ ".$this->getMonth($ntime[1])."/ ".$ntime[0]." ";
	}
	private function dateFormatForDb($date){
		return $this->Qlib->dateFormatForDb($date,false);
	}
	private function getMonth($mon){
		switch($mon){
			case 1:
				return "Jan";
				break;
			case 2:
				return "Feb";
				break;
			case 3:
				return "Mar";
				break;
			case 4:
				return "Apr";
				break;
				
				case 5:
				return "May";
				break;
				case 6:
				return "June";
				break;
			case 7:
			 return "July";
				break;
			case 8:
			return "August";
				break;
			case 9:
			return "September";
			break;
			case 10:
			return "October";
			break;
			case 11:
			return "November";
				break;
			case 12:
				return "December";
				break;
		}
	}
	
  public function getActivitities($whereclause=""){
		$res=$this->db->selectQuery(array("id,description,fromdate,todate,DATEDIFF(todate,fromdate),projectid"),"pr_workschedule",$whereclause);
		
		$data=array();
		
		while($row=mysqli_fetch_row($res)){
			$act=new activities();
			
			$act->id=$row[0];
			$act->name=$row[1];
			$act->stDate=$this->dateNormal($row[2]);
			$act->toDate=$this->dateNormal($row[3]);
			$act->peri=$row[4];
			$act->p_id=$row[5];
			
			$data[]=$act;
		}
		
	  return $data;
	}
	public function getActivityMenu($proj,$act){
		
		$cont=new objectString();
		
		
		$cont->generalTags("<div class='w3-btn w3-pink w3-round am-btn' style='margin-left:2px' id='m_23'>Schedules</div>");
		
		$cont->generalTags("<div class='w3-btn w3-yellow w3-text-white am-btn am-back' id='pmp_".$proj."' style='margin-left:2px;' >Back</div>");
		
		 return $cont->toString();
	}
	public function activityHandler($opt){
		switch ($opt){

			case 3:
				return $this->activityHandlerItemsRelay(true,$_POST['list'],"/");
				break;
			case 4:
				return $this->activityHandlerInsertion($_POST['list'],"/");
				break;

		}
	}
	private function  activityHandlerItemsRelay($temp=false,$lis,$cause=""){
		if(!$temp)
			$key=$cause;
			$Jarr=json_decode($lis);
		    
		$list=new open_table();
		
		$list->setColumnNames(array("No","Material Name","Value","Unit","Action"));
		
		$list->setColumnWidths(array('5%','27%',"15%","10%","15%"));
		
		$list->setNumberColumns(array(5));
		
		$list->setHoverColor('#cbe3f8');
		
        $list->setEditableColumns(array(1,2,3));
		
	    for($i=0;$i<count($Jarr);$i++){
			$arr=explode("/",$Jarr[$i]);
			$list->addItem(array($i+1,$arr[1],$arr[2],$this->getUnit($arr[1]),"<div class='dRw'></div>"));
		}
		
        $list->showTable();

		return $list->toString();
		
	}
	private function activityHandlerInsertion($lst,$cmpid='1',$act_id="2"){
		/*$jArr=json_decode($lst);
		for($i=0;$i<count($jArr);$i++){
			$tmp=explode("/",$jArr);
			$this->Qlib->addMaterialEst($tmp[1],$tmp[2],$tmp[3],$cmpid,$act_id);
		}
		 */
		return $lst;
	}
  public function getMaterialStatus($whereclause){
		$data=array();
		$res=$this->db->selectQuery(array("*"),"pr_materialhandler",$whereclause);
		
		 while($row=mysqli_fetch_row($res)){
			 $mat=new mat_status();		 
			 $mat->id=$row[3];
			 $mat->name=$row[1];
			 $mat->uploaded=$row[2];
			 
			 $data[]=$mat;
		 }
		return $data;
	}
	
  public function showactivityState($proj="",$act_id=""){
	  $cont=new objectString();
	  
	  //$quince =new HQuince();
	  
	  $cont->generalTags("<div class='am_container'>");
	  
	  $cont->generaltags("<div class='am_row'><div class='w3-header w3-text-black w3-hover-text-yellow'>Scedules Container</div></div>");
	  
	  $cont->generalTags("<div class='am_row'>Select Material Estimates</div>");
	  
	  $cont->generalTags("<div class='am_control'>");
	  
	  $cont->generalTags("<input type='hidden' val='".$proj."' id='mn-proc".$proj."' data-chd='mn_act-".$act_id."'>");
	  
	  $cont->generalTags("<div class='btn-subs w3-btn w3-button w3-ripple w3-green w3-right am-btn' id='list-01'>Set Estimates</div>");
	  
	  $cont->generalTags("</div>");
	  
	  $cont->generalTags("<div class='am_row' >".$this->adaptedList()."</div>");
	  
	  $cont->generalTags("<div class='tbl_container'></div>");
	 
	  
	  return $cont->toString();
  }
	private function checkMaterialUpload($id,$actis=''){
		$res="";
		if(!empty($actis))
			$res=$this->db->selectQuery(array("*"),"pr_materialhandler","where actid=");
		
		
		
		while($row=mysqli_fetch_row($res)){
			$data=$row[0];
		}
		return $data;
		//check if the data isempty using javacript
		
	}
	private function table_seter($name){
		/*
		 step 1:change the current tablename to old;
		 step 2:create the new table like the pr_table "GROUP_CONCAT(COLUMN_NAME) from information_schema.columns where TABLE_NAME='tbl1_old' group by TABLE_NAME order by ORDINAL_POSITION"
		 step 3:insert into the new table 'insert into newtable select * from oldtable'
		 step 4:delete the old table 
		*/
		if($this->db->updateQuery(array(""),"",array())){
			
		}
		
		
	}
	//=--------------------company functions start--------------------------//
	public final function manageCompanies(){
		$cont=new objectString();
		
		$cont->generalTags("<div class='a3-left a3-full a3-container a3-margin-left'> <h3>Company Master Managment</h3></div>");
		
		$cont->generalTags("<div class ='a3-left a3-full a3-container'>");
		
		$cont->generalTags("<div class='a3-left a3-tab a3-margin a3-padding a3-round a3-green' id='uMp_".rand(2,1)."'>Update Company Tables</div>");
		
		//$cont->generalTags("<div class='a3-left a3-tab a3-margin a3-padding a3-round a3-green' id='cRp_".rand(2,1)."'>Create Tables</div>");
		
		$cont->generalTags("<div class='a3-left a3-tab a3-margin a3-padding a3-round a3-green' id='vMp_".rand(2,1)."'>View Companies</div>");
		
		$cont->generalTags("<div class='a3-left a3-tab a3-margin a3-padding a3-round a3-blue' id='cMp_".rand(2,1)."'>Create Company</div>");
		
		$cont->generalTags("</div>");
		
		$cont->generalTags("<div class='a3-left a3-flex a3-padding-top a3-light-gray a3-margin-left a3-round' style='width:98%' id='cont-container'>".$this->companyList()."</div>");
		
		return $cont->toString();
	}
	
	private final function companyList(){
		$comps=$this->Qlib->getCompanies();
		
		$list=new objectString();
		
		foreach($comps as $company){
		
			$list->generalTags("<div class='a3-border a3-padding a3-left a3-round a3-margin cp-container '  datac-cp='pr-".rand(2,1)."_".$company->company_id."'><div>");
			
			$list->generalTags("<nav >".ucfirst($company->company_name)."</nav>");
			
			$users=$this->um->getUsers(" where company_id=".$company->company_id);
			
			$list->generalTags("</div><nav class='tRq '><span class='a3-right tRq_inner'>Total users ".count($users)."</span></nav>");
			
			$list->generalTags("</div>");
		}
		return $list->toString();
	}
	public function updateCompanyLayout(){
		$cont=new objectstring();
		
		$cont->generalTags("<fieldset><legend>Update Controls</legend>");
		
		$cont->generalTags("<div class='a3-left a3-full a3-padding'>
		<input type='checkbox' id='create'  class='checks'>Create <br/> <input type='checkbox' id='update' class='checks'> Update
		<br/><input type='checkbox' id='comp' > For all companies</div>");
		
		$cont->generalTags("<div class='a3-left a3-full a3-padding-top'><label class='a3-left a3-full '>Query</label>");
		
		$cont->generalTags("<textarea id='query' class='a3-left a3-padding a3-margin a3-round a3-half'>type query here</textarea></div>");
		
		$cont->generalTags("<div class='a3-left a3-full a3-padding-top'><label class='a3-left  a3-full'>Table Prefix</label>");
		
		$cont->generalTags("<input id='query' class='a3-left a3-padding a3-margin a3-round a3-half a3-border'></div>");
		
		$cont->generalTags("<button class='a3-round a3-margin-left a3-margin-right a3-padding a3-blue a3-hover-green a3-pointer a3-tab'>Run Query</button>");
		
		$cont->generalTags("<button class='a3-round a3-margin-left a3-margin-right a3-padding a3-blue a3-hover-green a3-pointer a3-tab'>Update Column</button>");
		
		$cont->generalTags("<button class='a3-round a3-margin-left a3-margin-right a3-padding a3-blue a3-hover-green a3-pointer a3-tab'>Update Company</button>");
		
		$cont->generalTags("</fieldset>");
		
		return $cont->toString();
	}
	//----------------------------company functions end---------------------------------------///
   //------------------------cutomised user functions-----------------------------//
	
	public function getAssignedUser($s_userid){
		if($s_userid=="")
			return "not assigned";
		
		$user=$this->um->getUsers("where id=".$s_userid);
		
		if(count($user))
			return $user[0]->user_name;
		
	}
	public function getAssginedUserSite($s_userid){
		$proj=$this->Qlib->getMyActiveSite($s_userid);
		
		if(count($proj)){
			return $proj->site_id;
		}else{
			return "not assigned";
		}
	}
	//-----------------------------ADMINISTRATION FUCNTIONS------------------------//
	public function administration(){
		$cont=new objectString();
		
		$cont->generalTags("<div class='a3-left a3-full a3-padding'>Administration</div>");
		
		$cont->generalTags("<div class='a3-left a3-round a3-padding a3-margin a3-blue a3-pointer'>Manage Stores</div>");
		
		$cont->generalTags("<div class='a3-left a3-round a3-container a3-margin a3-light-gray' style='min-height:500px;width:96%'>");
		
		$cont->generalTags("<div class='a3-right a3-blue a3-round a3-padding a3-margin a3-pointer comp'>Create Store</div>");
		
		$cont->generalTags("<div class='a3-toggle a3-hidden a3-full a3-margin-bottom a3-left'>");
		
		$cont->generaltags("<label class='a3-left a3-full'>Store Name</label>");
		
		$cont->generalTags("<input  autocomplete='off' type='text' style='width:40%' id='stName' class='a3-left a3-full a3-margin a3-padding a3-border a3-round' placeholder='type name here'>");
				
		$cont->generaltags("<label class='a3-left a3-full a3-margin-bottom'>Assign Store</label>");
		
		$cont->generalTags("<div class='qs_wrap a3-white a3-margin-left'>");
		
		$cont->generalTags("<select class='quince_select a3-white' id='site'>");
		
		$cont->generalTags("<option value='-2'>Select Site</option>");
		
		$sites=$this->Qlib->getsites();
		
		 foreach($sites as $key => $site)
			 $cont->generalTags("<option value='".$site->site_id."'>".$site->site_name."</option>");
		
		$cont->generalTags("</select>");
		
		$cont->generalTags("</div>");
		
		$cont->generalTags("<div class='a3-right a3-padding a3-margin a3-round a3-blue a3-hover-green a3-pointer comp2' id='cSt'>Create</div>");
		
		$cont->generalTags("</div>");
		
		$cont->generalTags("<div class='container a3-padding a3-full a3-left a3-white' style='background:white'>".$this->listStores()."</div>");
		
		$cont->generalTags("</div>");
		
		return $cont->toString();
	}
	public function adminMenu(){
		$cont=new objectString();
	
		
		return $cont->toString();
	}
	public function getStores($whereclause=''){
		$res=$this->db->selectQuery(array("*"),'pr_stores',$whereclause);
		
		$data=array();
		
		while($row=mysqli_fetch_row($res)){
			$store=new dbAsset();
			
			$store->id=$row[0];
			$store->content=$row[1];
			$store->type=$row[2];

			if(isset($row[3]))
			    $store->user=$row[3];
			
			$data[]=$store;
		}
		return $data;
	}
	public function listStores(){
		
		$list=new open_table;
		
		$list->setColumnNames(array('No','Store',"Site","User"," Action"));
		
		$list->setColumnWidths(array('10%','27%','10%',"20%","10%"));
		
		$whereclause=" ";
		
		$stores=$this->getStores($whereclause);
		
		
		foreach($stores as $key => $st)
			$list->addItem(array( ($key+1),$st->content,$ww=($st->type==0) ? "Not Assigned" :"Assigned",$ws=($st->user =='')? "Not Assigned" :$this->um->getUsers('where id='.$st->user)[0]->user_name  ,"<i class='fas fa-user a3-hover-text-blue a3-margin-left storeF' id='user_".$st->id."'></i><i class='fas fa-trash a3-margin-left storeF' id='del_".$st->id."'></i>"));
		
		$list->setHoverColor('#cbe3f8');
		
		$list->showTable();
		
		return $list->toString();
		
	}
	public function companyAjaxHandler($btn){
		$cont=new objectString();
		switch(explode("_",$btn)[0]){
			case "cSt":
				$store=$this->getStores();
				
				if($_POST['site'] !="-2"){
					$this->db->insertQuery(array("name","id","is_assigned"),"pr_stores",array("'".$_POST['cs']."'","'".(count($store)+1)."'","'".$_POST['site']."'"));
				}else{
					$this->db->insertQuery(array("name","id"),"pr_stores",array("'".$_POST['cs']."'","'".(count($store)+1)."'"));
				}
				
				$cont->generalTags($this->listStores());
				
				break;
		}
		
		return $cont->toString();
	}
	//----------------------------ADMINISTRATION FUNCTIONS END-------------------------------------//
	
	//-----------------------------TABLE SUBMISSIONS FUNCTION INVENTOTY CONTROL--------------------------//
    
    function mnStoreInventory(){
        
        $cont=new objectString();
        
        $cont->generalTags("<div class='a3-padding a3-margin-bottom'>Main Store Inventory</div>");
             
        $list=new open_table;
		
		$list->setColumnNames(array('No','Material Name',"Stock Qty","Received Qty","Issued Qty","Action"));
		
		$list->setColumnWidths(array('10%','27%','15%','15%','15%',"0%"));
		
        //$list->setEditableColumns(array(2));
        
		$whereclause=" ";
        
        $data=$this->getReceivedMaterials('where request_id=0 and store=0');
		
        for($r=0;$r<count($data);$r++){
            $sm=$this->setSumReceived("where description ='".$data[$r]->mat_desc."'  and request_id <>0 group by description");
            
            if($sm !=null){
                $list->addItem(array($r+1,$data[$r]->mat_desc,($data[$r]->mat_value-$sm),'','',"<div class='xpand' id='mn_".($r+1)."'></div>"));
            }else{
                $list->addItem(array($r+1,$data[$r]->mat_desc,($data[$r]->mat_value-$sm),'','',"<div class='xpand' id='mn_".($r+1)."'></div>"));
            }
            $list->addDataRow($this->expandDiv($data[$r]->mat_id,'Office Requisition No. '.($r+1)));
        }
		
		$list->setHoverColor('#cbe3f8');
		
		$list->showTable();
		
        $cont->generalTags("<div class='a3-left a3-padding' style='width:95%'>". $list->toString()."</div>");
        
        return $cont->toString();
    }
	public function setSumReceived($where){
        $res=$this->db->selectQuery(array('sum(qty)'),'pr_receiveditems',$where);
        
        while($row=mysqli_fetch_row($res)){
          return $row[0];   
        }
    }
    private function expandDiv($x,$title){
		
		$cont=new objectString;
		
		$cont->generalTags('<div class="dRw" id="dRw_'.$x.'" ><div class="dxBar">'.$title.'<div class="clM" title="Close" id="rq_'.$x.'">X</div></div><div class="dCont" id="scont_'.$x.'"></div></div>');
		
		return $cont->toString();
		
	}
	function updatedTableFunction($cs){
		
					
		switch($cs){
			case 1:
                
                $data=json_decode($_POST['data']);
		
		        $store=0;$proj=0;
		
			if(isset($_POST['st'])){
              	 foreach($data as $dt){
						  if(is_numeric(trim($dt[1]))){
                              
							  $this->insertMaterialStore($dt[0],$dt[1],$dt[2],$_POST['st'],$_POST['pr']);
						  }
					    }
					 										
				}else{					
					if(isset($_POST['pr'])){
						//main store of that project;
						$store=0;$proj=$_POST['pr'];
						
						foreach($data as $dt){
						  if(is_numeric(trim($dt[1]))){
							  $this->insertMaterialStore($dt[0],$dt[1],$dt[2],$store,$proj);
						  }
					    }
					}else{
                       
						
						$proj=$this->Qlib->getRequisitionSiteId($_POST['req']);
					
					    $st=$this->getStoreAssignment($this->Qlib->ud->user_id,1);
                        
                        if($st=='')
                            $st=$this->getStoreAssignment($proj,0);
						
                     
						
                        
						if($st !=0){
							foreach($data as $dt){
                              if(is_numeric(trim($dt[1]))){
                                  $this->insertMaterialStore($dt[0],$dt[1],$dt[2],$st,$proj);
                               }
					         }
							
						}else{
                           
							foreach($data as $dt){
                              if(is_numeric(trim($dt[1]))){
                                  $this->insertMaterialReceived($dt[0],$dt[1],$dt[2],$_POST['req'],$proj);
                            }
					         }
						}
					}
				}
				
				if(isset($_POST['req']))
					foreach($data as $dta)
                        if(is_numeric(trim($dt[1])))
                            $this->db->insertQuery(array('description','qty','project_id','request_id','unit_type','byname','byid'),'pr_receivedlogs',array("'".$dta[0]."'","'".$dta[1]."'","'".$proj."'","'".$_POST['req']."'","'".$dta[2]."'","'".$this->Qlib->ud->user_name."'","'".$this->Qlib->ud->user_id."'"));
				
				return( new name_value(true,System::successText('Items Received Successfully' )));
				

			case 2:
                $data=json_decode($_POST['data']);
		
		        $store=0;$proj=0;
                
				if(isset($_POST['str'])){
                            //restmove from the storeitems table then insert into issueditems or update table
                    
                    $store=$this->getStores("where id=".$_POST['str']);
                    
                    foreach($data as $dt){
				          if(is_numeric(trim($dt[1]))){									
                                 $this->setIssuedMaterials($dt[0],$dt[1],$dt[2],$store[0]->type,$_POST['cm'],$_POST['str']);
                            }
				    }
                    
                }
                else if(isset($_POST['cm'])){
                    
                    $cmp=$this->Qlib->getWorkSchedule("where id=".$_POST['cm']);
                    
                    $store=$this->getStores("where id=".$cmp[0]->wk_projectId." or user_id=".$this->Qlib->ud->user_id." limit 1");
                   
                    if(count($store)){
                        foreach($data as $dt){
				          if(is_numeric(trim($dt[1]))){									
                                 $this->setIssuedMaterials($dt[0],$dt[1],$dt[2],$cmp[0]->wk_projectId,$_POST['cm'],$store[0]->id);
                            }
				       }
                    }else{
                       foreach($data as $dt){
				          if(is_numeric(trim($dt[1]))){									
                                 $this->setIssuedMaterials($dt[0],$dt[1],$dt[2],$cmp[0]->wk_projectId,$_POST['cm'],0);
                            }
				      } 
                    }
                    
              }
                $hq= new HQuince();
                return( new name_value(true,System::successText('Items Issued Successfully'),' '));

            case 3:
                $select=new objectString();
                
                $select->generalTags("<div class='qs_wrap'><select class='quince_select' id='sel_".$_POST['id']."'>");
                
                $select->generalTags("<option value='-2'>Select User</option>");
                
                $user=$this->um->getUsers("where company_id=".$this->Qlib->cmp->company_id);
                
                foreach($user as $us){
                    $select->generalTags("<option value='".$us->user_id."'>".$us->user_name."</option>");
                }
                
                $select->generalTags("</select></div>");
                
                return $select->toString();
                break;
            case 4:
                                
                $store=$this->getStores("where id=".explode('_',$_POST['btn'])[1]);
                
                
                
                if(count($store)){
                    $where="where id=".$store[0]->type." and user_id=".$_POST['id'];
                    
                    $st=$this->getStores($where);
                    
                    if($st){
                          return  "<div class='a3-text-red'>User Already Assigned</div>";
                    }else{
                        $mn=$this->db->updateQuery(array("user_id=".$_POST['id']),'pr_stores',"where id=".explode('_',$_POST['btn'])[1]);
                
                        return  $user=$this->um->getUsers("where id=".$_POST['id'])[0]->user_name;  
                    }
                }
                $hq= new HQuince();
                return( new name_value(true,System::successText('Items Issued Successfully' ),$hq->receiveList()));

            case 5:
                $data=json_decode($_POST['data']);
                foreach($data as $rec){
                    if(is_numeric(trim($rec[2]))){
                        
                             $this->insertMaterialReceived($rec[0],$rec[2],'n/a',0,0); 
				
                    }
                }
               return( new name_value(true,System::successText('Materials Have been Received')));
          break;
            case 6:
                 $data=json_decode($_POST['data']);
                foreach($data as $rec){
                    if(is_numeric(trim($rec[1]))){
                        
                        if(isset($_POST['st'])){
                            $this->insertMaterialReceived2($_POST['pr'],$_POST['req'],$_POST['st'],$rec[0],$rec[1]);
                        }else{
                            $this->insertMaterialReceived2($_POST['pr'],$_POST['req'],0,$rec[0],$rec[1]);
                        }
                     
                    }
                }
                $hq= new HQuince();
                 return( new name_value(true,System::successText('Items Issued Successfully'),$hq->receiveList()));

			case 7:
				$cont=new objectString();
				
				$req=$this->Qlib->getTransferRequests(" and a.id='".$_POST['bt']."'",'a.equid_value')[0];
				
				$cont->generalTags("<div class='a3-white a3-round a3-padding a3-left' style='margin-left:20%;margin-top:3%;width:60%;height:auto'> <h3>Process Equipment Request no.".$_POST['bt']."</h3>");
				
				$cont->generalTags("<div id='respond' class='a3-round a3-padding'></div>");
				//$list->addItem(array(($i+1),$eqip[$i]->tr_destName,$eqip[$i]->tr_date,$eqip[$i]->tr_totals," "));
						
				$cont->generalTags("<fieldset class='a3-left a3-full a3-border a3-pop'>");
				
				$cont->generalTags("<legend>Current Selection</legend>");
				
				$cont->generalTags("<p>Equipment name: <div class='a3-text-yellow'>".$req->tr_equipName."</div> Requested Value:<div class='a3-text-yellow'>".$req->tr_value."</div>  Approved Value :<div class='a3-text-yellow'>0</div></p>");
				
				$cont->generalTags("</fieldset>");
				
				$sites=$this->Qlib->getSites();
				
				$cont->generalTags("<div class='a3-left a3-full'>");
				
				$cont->generalTags("<div class='a3-left a3-margin a3-padding' style='width:30%'>Select Source</div><div class='qs_wrap a3-margin-top'><select class='quince_select '>");
				
				$cont->generalTags("<option value='-2'>Store</option>");
				
				foreach($sites as $site)
					$cont->generalTags("<option value='".$site->site_id."'>".$site->site_name."</option>");
				
				$cont->generalTags("</select></div><div id='fl-gauge'></div>");
				
				$cont->generalTags("</div>");
				
				
				$cont->generalTags("<div class='a3-left a3-full'><div class='a3-left a3-padding a3-margin' style='width:30%'>No of Equipment</div>< autocomplete='off' type='text' value='".$req->tr_value."' id='eq_val' class='a3-border a3-padding a3-margin-top'></div>");
				
				$cont->generalTags("<div class='a3-right '>");
				
				$cont->generalTags("<button id='edi_".$_POST['bt']."' class='a3-left a3-padding a3-border a3-round a3-pointer a3-margin-right eq-btn'>Process request</button>");
				$cont->generalTags("<button id='dn_".$_POST['bt']."' class='a3-left a3-padding a3-border a3-round a3-pointer a3-margin-right eq-btn'>Deny Request</button>");
				$cont->generalTags("<button id='app_".$_POST['bt']."' class='a3-left a3-padding a3-border a3-round a3-pointer eq-btn'>Cancel </button>");
				
				$cont->generalTags("</div>");
				
				
				$cont->generalTags("</div>");
				
				return $cont->toString();

		}
	}
	public function insertMaterialStore($desc,$val,$unit,$store=0,$proj=0){
		$where="where description='".$desc."' and sch_id=".$proj." and store= ".$store." limit 1";
		
		$mat=$this->getStoreMaterials($where);
		
        if(count($mat) !=0){
             for($i=0;$i<count($mat);$i++){
                 if($mat[$i]->mat_desc==''){
                   $this->db->insertQuery(array("description","qty","unit","sch_id","store"),'pr_storeitems',array("'".$desc."'","'".$val."'","'".$unit."'","'".$proj."'","'".$store."'"));
                 }else{
                      $this->db->updateQuery(array('qty='.($mat[0]->mat_value+$val)),'pr_storeitems',$where);
                 }
             }
           
		}else{
            
           $this->db->insertQuery(array("description","qty","unit","sch_id","store"),'pr_storeitems',array("'".$desc."'","'".$val."'","'".$unit."'","'".$proj."'","'".$store."'"));
		}
	}
    public function getTotalReceived($wherclause){
        $res=$this->db->selectQuery(array('description,sum(qty),unit_type,request_id'),'pr_purchaseditems',$wherclause.' group by description');
        
        $data=array();
        
        while($row=mysqli_fetch_row($res)){
            $data[]=array($row[0],$row[1],$row[2],$row[3]);
        }
        return $data;
    }
    public function insertMaterialReceived2($proj,$req,$store,$desc,$val,$unit='mm'){
        /* check if the materials are available in site store */
        
        $whereclause="where request_id=".$req." and project_id=".$proj." and store=".$store." and description='".$desc."'";
        
       $mat= $this->getReceivedMaterials($whereclause);
        if(count($mat)){
            $this->db->updateQuery(array('qty='.($mat[0]->mat_value+$val)),'pr_receiveditems',$whereclause);
        }else{
            $this->db->insertQuery(array('project_id,store,description,qty,request_id,byname,byId,unit_type'),'pr_receiveditems',array($proj,$store,"'".$desc."'",$val,$req,"'".$this->Qlib->ud->user_name."'",$this->Qlib->ud->user_id,"'".$unit."'"));
        }
    }
	public function insertMaterialReceived($desc,$val,$unit,$req,$proj){
        
        $use='request_id';
        
		$where="where description='".$desc."'  and project_id=".$proj." and request_id=".$req;
        
          if($req==0){
              $where="where description='".$desc."'  and project_id=".$proj." and store=".$req;
              
              $use='store';
          }
              
		
		$mat=$this->getReceivedMaterials($where);
		
		
		if(count($mat) >0){
			$this->db->updateQuery(array("qty=".($mat[0]->mat_value+$val)),'pr_receiveditems',$where);
		}else{
			$this->db->insertQuery(array('description','qty','unit_type',$use,'byId','byname','project_id'),'pr_receiveditems',array("'".$desc."'","'".$val."'","'".$unit."'","'".$req."'","'".$this->Qlib->ud->user_id."'","'".$this->Qlib->ud->user_name."'","'".$proj."'"));
		}
	}
	public function insertMaterialIssued($desc,$val,$unit,$req,$proj){
		$where="where description='".$desc."'  and project_id=".$proj." and request_id=".$req;
		
		$mat=$this->getReceivedMaterials($where);
		
		
		if(count($mat) >0){
			
		}else{
			
		}
	}

	public function getIssuedMaterials($whereclause){
		$res=$this->db->selectQuery(array('*'),'pr_issueditems',$whereclause);
		
		$data=array();
		
		while($row=mysqli_fetch_row($res)){
			$mat=new materialStore();
			
			$mat->mat_id=$row[0];
			$mat->mat_desc=$row[1];
			$mat->mat_store=$row[3];
	        $mat->mat_project=$row[0];
	        $mat->mat_value=$row[2];
			$data[]=$mat;
		}
		return $data;
	}
	public function getReceivedMaterials($whereclause){
		$res=$this->db->selectQuery(array('*'),'pr_receiveditems',$whereclause);
		
		$data=array();
		
		while($row=mysqli_fetch_row($res)){
			$mat=new materialStore();
			
			$mat->mat_id=$row[0];
			$mat->mat_desc=$row[2];
			$mat->mat_store=$row[6];
	        $mat->mat_project=$row[0];
	        $mat->mat_value=$row[3];
			$data[]=$mat;
		}
		return $data;
	}
	public function verifyRequisitionPurcahse($rid,$case=0){

      /* case 1:return materials that have not been completly purcahsed
         case 2:return materials that have been over purchased
         case 3:return the full material values ,over purchase ,verified and bought */
      $data=$this->Qlib->getRequestItems("where request_id=".$rid);

      $materials=null;

	  $balance=0;
      for($i=0;$i<count($data);$i++){
          $inData=$this->getTotalReceived(" where request_id=".$rid." and  description='".$data[$i]->item_description."'");

		  if(count($inData))
				 $balance=$data[$i]->item_qty-$inData[0][1];
				 
				
		  switch($case){
			  case 1:
				  if($balance <=0)
					  $materials[]=array($data[$i]->item_id,$data[$i]->item_description,$data[$i]->item_qty,$balance);
				  
				  break;
			  case 2:
				  if($balance <=0){
					  return true;
				  }else{
					  return false;
				  }
			    break;
		  }
		  
      }
		
		return $materials;
		
    }
	public function selectMP($sid){
		$sel=new input;
		
		$sel->setClass('pur_select');
		
		$sel->setId('selMpR');
		
		$levs=$this->Qlib->getALevels("order by thelevel asc");
		
		$qr=$this->Qlib->getRequisitions('where project_id='.$sid.' and level'.count($levs).'=1 and requisition_status=0');
		
		$sel->addItem(-1,'Select Requisition ');
		
		for($i=0;$i<count($qr);$i++){
			$req=$this->verifyRequisitionPurcahse($qr[$i]->req_id,2);
			
			if($req)
				$sel->addItem($qr[$i]->req_id,' Requisition '.$qr[$i]->req_no);
			
		}
		$sel->select('selCal');
		
		return $sel->toString();
	}
	public function getStoreMaterials($whereclause){
		$res=$this->db->selectQuery(array('theDate,description,sum(qty),unit,unit,store '),'pr_storeitems',$whereclause);
		
		$data=array();
		
		while($row=mysqli_fetch_row($res)){
			$mat=new materialStore();
			
			$mat->mat_id=$row[0];
			$mat->mat_desc=$row[1];
			$mat->mat_store=$row[5];
	        $mat->mat_project=$row[3];
	        $mat->mat_value=$row[2];
			$data[]=$mat;
		}
       
		return $data;
	}
	public function getStoreAssignment($id,$case=0){
		$whereclause="where is_assigned=".$id;
		
		if($case !=0)
			$whereclause=" where user_id=".$id;
		
		$store=$this->getStores($whereclause);
		
		if(count($store))
			return $store[0]->id;
		else
			return( false);
	}
	public function setIssuedMaterials($description,$qty,$mm,$proj=0,$cmp=0,$store=0){
		
		$where=" where description='".$description."' and store=".$store." and sch_id=".$proj; 
		
		$whereclause="where sch_id=".$cmp." and description='".$description."'";
			
		$iss=$this->getIssuedMaterials($whereclause);
		
		$rec=$this-> getStoreMaterials($where);

		
		if($store==0){
            //received items;
            $whereRece=$this->getReceivedMaterials("where description='".$description."' and project_id=".$proj." site_id=".$store);
            
            if(count($whereRece)){
                $this->db->updateQuery(array("qty=".($whereRece[0]->mat_value-$qty)),'pr_receiveditems',"where description='".$description."' and project_id=".$proj." site_id=".$store);
               
                if(count($iss)){
                    $ss=$this->db->updateQuery(array('qty='.($iss[0]->mat_value+$qty)),'pr_issueditems',$whereclause);
                }else{
                  $ss=$this->db->insertQuery(array('description','qty','unit','sch_id'),'pr_issueditems',array("'".$description."'","'".$qty."'","'".$mm."'","'".$cmp."'"));  
                }
               
            }else{
                $ss=$this->db->insertQuery(array('description','qty','unit','sch_id'),'pr_issueditems',array("'".$description."'","'".$qty."'","'".$mm."'","'".$cmp."'")); 
            }
           
        }else{
           if(count($rec)){
			if(count($iss)){
				$ss=$this->db->updateQuery(array('qty='.($iss[0]->mat_value+$qty)),'pr_issueditems',$whereclause);
				
				return $this->db->updateQuery(array('qty='.($rec[0]->mat_value-$qty)),'pr_storeitems',$where);
								
			}else{
				$ss=$this->db->insertQuery(array('description','qty','unit','sch_id'),'pr_issueditems',array("'".$description."'","'".$qty."'","'".$mm."'","'".$cmp."'"));
				
				return $this->db->updateQuery(array('qty='.($rec[0]->mat_value-$qty)),'pr_storeitems',$where);
			
			}			
		 }else{
               $ss=$this->db->insertQuery(array('description','qty','unit','sch_id','store'),'pr_issueditems',array("'".$description."'","'".$qty."'","'".$mm."'","'".$proj."'","'".$store."'"));
          } 
            
            
        }
    }
    function blanlkReq($req){
        $cont=new objectString();
        
        $cont->generalTags("<div class='a3-full a3-left a3-padding'>");
        
        $cont->generalTags("<div class='a3-right a3-blue a3-padding a3-margin a3-round saveList' id='req_".$req."'>Issue Items</div>");
        
        $list=new open_table;
		
		$list->setColumnNames(array('No','Material Name',"Value","unit"));
		
		$list->setColumnWidths(array('10%','27%','10%','10%'));
		
        $list->setEditableColumns(array(2));
        
		$whereclause=" ";
        
        $req=$this->Qlib->getRequestItems("where request_id=".$req);
		
        for($r=0;$r<count($req);$r++){
            $list->addItem(array($r+1,$req[$r]->item_description,'',$req[$r]->item_unit));
        }
		
		$list->setHoverColor('#cbe3f8');
		
		$list->showTable();
		
        $cont->generalTags( $list->toString());
        
        return $cont->toString();
    }
    function showInventory($proj,$case=0){
        $cont=new objectString();
        
        if($case==3){
            $st=$this->getStores("where is_assigned=".$proj);
                if(count($st) >1){
                    $cont->generalTags("<select name='vStock' id='vtl' class='inv_select'>");
                    
                    $cont->generalTags("<option value='-1'>Select Store</option>");

                    for($p=0;$p<count($st);$p++){
                        $cont->generalTags("<option value='".$st[$p]->id."'>".$st[$p]->content."</option>");
                    }


                    $cont->generalTags("</select>");
                }else{
                    return;
                    }
        }else{
           
            if($_POST['qOption'] !='invType'){
                 $st=$this->getStores("where is_assigned={$proj}");
            
           
                if(count($st) >1){
                    $cont->generalTags("<div id='label'>Select Store</div><div class='qs_wrap'><select name='vPl' class='a3-border' name='store' style='width:190px'>");


                    for($p=0;$p<count($st);$p++){
                        $cont->generalTags("<option value='".$st[$p]->id."'>".$st[$p]->content."</option>");
                    }


                    $cont->generalTags("</select></div>");
                }
           /* $req=$this->Qlib->getRequisitions("where project_id=".$proj);
            
            $cont->generalTags("<div id='label'></div><div class='qs_wrap'><select class='inv_select' id='tem' name='sReq'>");
            
            $cont->generalTags("<option value='-2'>Select Requisitions</option>");
            
            for($i=0;$i<count($req);$i++){
                $cont->generalTags("<option value='".$req[$i]->req_id."'>Requisition no.".($i+1)."</option>");
            }*/
            $cont->generalTags("</select></div></div>");
            }
             
        }
        
            return $cont->toString();
    }
	//-----------------------------END FUNCTION INVENTORY CONTROL---------------------------------------//
	
	public function equipmentTranferHandler($rid){
		$cont=new objectString();
		
		$sm=$this->Qlib->getTransferRequests(" and c.regcode='".$rid."'",'a.equid_value')[0];
		
		$cont->generalTags("<div class='innerTitle a3-margin'>Handle Tranfers For ".$sm->tr_equipName."'s </div>");
		
		$tr=$this->Qlib->getTransferRequests(" and c.regcode='".$rid."'  and a.status=0",'a.equid_value');
		
		$list=new open_table();
		
		$list->setHoverColor('#cbe3f8');
		
		$list->setColumnNames(array('No.','Code','Description','Value','Request Source','Destination','Requested By','Date Requested',' '));
		
		$list->setColumnWidths(array('6%','10%','15%','10%','15%','0%','10%','10%','15%'));
		
		
		for($i=0;$i<count($tr);$i++){
			
			$sr=($tr[$i]->tr_sourceName !="-2") ? $this->Qlib->getSites(" where id=".$tr[$i]->tr_sourceName)[0]->site_name : "store" ;
			
			$list->addItem(array(($i+1),$tr[$i]->tr_equipCode,$tr[$i]->tr_equipName,$tr[$i]->tr_value,$sr,$tr[$i]->tr_destName,$tr[$i]->tr_byName,$tr[$i]->tr_date,'<div  class="auth2 a3-margin-left a3-blue a3-hover-green a3-pointer a3-round" style="width:30%;padding:1px 2px" id="upd_'.$tr[$i]->tr_id.' ">Allow</div>'));
		}
	
		$list->showTable();

		$cont->generalTags($list->toString());
		
		return $cont->toString();
	}
	public function updateScheduleEstimates($cs){
         $name=null;
        $cell=explode("_",$_POST['cel'])[2];


		$whereclause="where description='".$_POST['ds']."' and component_id='".$_POST['pj']."'";
     
		switch($_POST['tb']){
          case 'matEst': case "ltdEst":

			  
			  if($cell=='2')
				  $name=$this->db->updateQuery(array("qty='".$_POST['up']."'"),'pr_materialestimates',$whereclause);
			  
			  if($cell=='3')
				  $name=$this->db->updateQuery(array("cost='".$_POST['up']."'"),'pr_materialestimates',$whereclause);
			
           break;
          case 'boqTable':
             $whereclause="where description='".explode("_",$_POST['cel'])[2]."' and component_id='".$_POST['pj']."'";

                  $this->setComponentBOQ($_POST['pj'],$_POST['up'],explode("_",$_POST['cel'])[2]);

          break;
            case "ltdEst":
                if($cell=='2')
                    $name=$this->db->updateQuery(array("qty='".$_POST['up']."'"),'pr_materialestimates',$whereclause);

                if($cell=='3')
                    $name=$this->db->updateQuery(array("cost='".$_POST['up']."'"),'pr_materialestimates',$whereclause);

               if($cell=='5')
                    $this->setTaskEstimation($_POST['pj'],$_POST['up'],explode("_",$_POST['cel'])[2]);
                break;

      }
      return json_encode($this->Qlib->getMaterialEstimates($whereclause));
}
	private final function setComponentBOQ($pj,$mat,$pos,$tb_pos='qty'){
		$val=$this->Qlib->getMaterialEstimates("where component_id=".$pj." and description='".$pos."'");

		if(count($val)){
		    if($val[0]->mat_qty !=0){
                $this->db->updateQuery(array(" $tb_pos='".$mat."'"),'pr_materialestimates',"where component_id=".$pj." and description='".$pos."'");
            }else{
                $this->db->insertQuery(array("description",$tb_pos,"component_id","unitType","component_is_child"),'pr_materialestimates',array("'".$pos."'","'".$mat."'","'".$pj."'","'tk'","'-1'"));
            }
        }else{
            $this->db->insertQuery(array("description",$tb_pos,"component_id","unitType","component_is_child"),'pr_materialestimates',array("'".$pos."'","'".$mat."'","'".$pj."'","'tk'","'-1'"));
        }
  }
	public function setTaskEstimation($pj,$val,$pos){
      $val=$this->getIssuedMaterials("where description='".$pj."' and sch_id=".$pos);


      if(count($val))
          $this->db->updateQuery(array("qty='".$val."'"),'pr_issueditems',"where description='".$pj."' and sch_id=".$pos);
      else
          $this->db->insertQuery(array('description','qty','unit','sch_id','store'),'pr_issueditems',array("'".$pj."'","'".$val."'","(select material_unit from pr_itemuploads where material_name='".$pj."')","'".$pos."'","'-3'"));
    }
	public function updatedCompanyFunction(){
		$cont=new objectString();
		switch($_POST['cs']){
			case 1:
				$arr=array(array('Company name','cmName'),array('Admin Name','FName'),array('Admin Email','YEmail','email'),array('Admin Phone Number','YPass','password'),array('Admin Phone Number','CPass','password'));
				
				$cont->generalTags("<div id='container' class='a3-white a3-left a3-round a3-padding' style='margin-left:25%;width:60%;margin-top:2%'>");
				
				$cont->generalTags("<h3 class='a3-left a3-full a3-center'>Create Company</h3>");
				
				foreach($arr as $input){
					$cont->generalTags("<div class='a3-left a3-full  a3-margin'>");
				
					$cont->generalTags("<label class='a3-left a3-left a3-full'>{$input[0]}</label>");

					$cont->generalTags("<input id='{$input[1]}' type='".($ww=(isset($input[2])) ? $input[2] : 'text' )."' class='a3-left a3-margin-top a3-round a3-border a3-padding ' style='width:90%'>");

					$cont->generalTags("</div>");
				}
				$cont->generalTags("<div class='a3-left a3-full  a3-margin'>");
				
				$cont->generalTags("<div id='create' style='margin-right:10%' class='compBtn a3-right a3-padding a3-blue a3-round a3-text-white'>Create Company</div>");	

				$cont->generalTags("</div>");
				
				$cont->generalTags("</div>");
				break;
			case 2:
				$theId=0;
				$nm=$this->Qlib->createNewCompany($_POST['YCompany'],$this->Qlib->prefixWCount(substr(str_replace(' ','',$_POST['YCompany']),0,4)),$theId);
				
				$status=0;
				$id=0;
				if($nm->name){
					
					$company=$this->Qlib->getCompany($nm->other);
					
					$this->Qlib->addPosition('Administrator',$id,$company->company_prefix);
					$privileges=$this->Qlib->getDynamicPrivileges();
					
					for($i=0;$i<count($privileges);$i++){
					  if(($privileges[$i]->value!=-1)&($privileges[$i]->value!=70))
					   $this->Qlib->addRemovePrivileges($privileges[$i]->value,1,$id,$company->company_prefix);
						
					   $status=1;
		            }
				}
				$um=system::shared("usermanager");
				if($status){
					$theId=$um->createNewUser(System::getArrayElementValue($_POST,'FName'),System::getArrayElementValue($_POST,'YEmail'),System::getArrayElementValue($_POST,'YPass'),$id,$company->company_id,0);
					
					$this->db->insertQuery(array('byName','byId','company_id'),'intsance_hanlder',array("'".$this->Qlib->ud->user_name."'","'".$this->Qlib->ud->user_id."'",$company->company_id),'','');
				}
				return "successfull company created";
				
				break;
		}
		return $cont->toString();
	}
	function getInstance($id=0){

		if($this->Qlib->ud->user_id ==1)
			return 4;
			
		$res=$this->db->selectQuery(array('*'),'intsance_hanlder','where company_id='.$this->Qlib->cmp->company_id,'');
	
		$inst=new instanceV();

		while($row=mysqli_fetch_assoc($res)){
			 $inst->isLaunched=$row[0];
			 $inst->instance_v=$row[1];
			 $inst->byId=$row[4];
			 $inst->byName=$row[4];
			 $inst->comapny_id=$row[9];
			 $inst->instance_p=$row[6];
			 $inst->instance_pu=$row[8];
			 $inst->instance_u=$row[7];

		}
		if($id==0)
			return $inst->instance_v;
		
			return $inst;
	}
	public final function materialManagement($id='-1'){
      $cont= new objectString();

      $material=$this->getRegItems_db("where is_category=1");

      $cont->generalTags("<fieldset class='a3-left a3-full a3-padding a3-margin a3-round' style='width: 95%'><legend>Material Categories</legend>");

      $cont->generalTags("<div class='tab-set a3-left a3-round a3-padding a3-margin a3-light-grey a3-pointer'> <i class='fas fa-plus-circle'></i> <label>Add Category</label></div>");

      for($i=0;$i<count($material);$i++)
           $cont->generalTags("<div class='tab-set a3-left a3-round  a3-margin a3-light-grey a3-pointer' id='tab_".$material[$i]->id."'>  <label class='a3-padding a3-left'>".$material[$i]->name."</label><i class='a3-padding-left opt_select' id='cat_".$material[$i]->id."' style='font-size: 25px;color:white;background:red'>&times</i></div>");

      $cont->generalTags("<div class='a3-left a3-full a3-toggle a3-hidden'> <label class='a3-left'>Category Name :</label> <input id='catName'>  <button class='opt_select' id='ins_1'>Create Category</button>  </div>");

      $cont->generalTags("</fieldset>");

      $cont->generalTags("<div class='a3-left a3-padding a3-margin-left a3-hover-green a3-hover-text-white a3-round a3-boxShadow' style='width:15%;color:#ffffcc;background: #f07e21'> <i class='fas fa-file-excel a3-left' style='font-size: 25px'></i><div class='a3-left a3-margin-left '>Upload Excel File</div></div>");
		
		$cont->generalTags("<div class='a3-right a3-margin-right a3-padding-right'><input checked=true type='checkbox' id='opt_comp' class='selection'> Use System Materials</div>");

      $cont->generalTags("<div class='a3-right a3-padding a3-margin-right opt_select a3-green a3-hover-blue a3-pointer' id='crt'>Create Material</div>");

      $cont->generalTags("<div class='a3-left a3-margin a3-full a3-hidden m3-toggle'>");

      $cont->generalTags(" <section> <label>Material Name</label> <input id='matName'> </section>  ");

      $cont->generalTags(" <section> <label>Material Unit</label> <input id='matName'> </section>  ");

      $cont->generalTags(" <section> <label>Material Category</label> <input id='matName'> </section>  ");

      $cont->generalTags(" <section> <button>Submit</button> </section>  ");

      $cont->generalTags(" </div>");

      $cont->generalTags("<section class='a3-light-gray a3-left table_area a3-margin-left a3-padding a3-margin-top' style='width:95%'>");

      $cont->generalTags($this->tableMaterialUploads($id));

      $cont->generalTags("</section>");

      return $cont->toString();
    }
   public function  tableMaterialUploads($cat='-1'){
      $cont= new objectString();

      $material=$this->getRegItems_db("where is_category=0 and material_category='".$cat."'");

      $category=$this->getRegItems_db("where is_category=1");

      if($cat=='-1')
       $cont->generalTags("<header class='a3-left a3-full a3-margin-bottom'><b>Uncategorized Materials</b></header>");
      else
          $cont->generalTags("<header class='a3-left a3-full a3-margin-bottom'><b>".$this->getRegItems_db("where id='".$cat."'")[0]->name."</b></header>");


      $list=new open_table();

     

      $list->setColumnNames(array('No','Material Name','Material Unit','Action'));

       $list->setColumnWidths(array('10%','35%','20%',"25%","15%"));

       $list->setHoverColor('#cbe3f8');

       for($i=0;$i<count($material);$i++) {
		   
		   $select=new objectString();
		   
           $select->generalTags("<div class='qs_wrap'><select class='quince_select mat_select' id='sel_".$material[$i]->id."'>");

           $select->generalTags("<option value='-1'>select category</option>");

           foreach ($category as $cate){
               $select->generalTags("<option value='".$cate->id."'>".$cate->name."</option>");
           }

           $select->generalTags("</select></div>");

           $list->addItem(array(($i + 1), $material[$i]->name, $material[$i]->desc, ($ww = ($cat != '-1') ? "" : $select->toString()) . '<i class=\'fas fa-trash a3-text-red opt_select\' id="del_'.$material[$i]->id.'" style="font-size:28px"></i>'));
       }
       $list->showTable();

      $cont->generalTags($list->toString());

      return $cont->toString();
   }public function  checkMaterialUploads($desc,$unit){
      $mat=$this->getRegItems_db("where material_name='".$desc."' and material_unit='".$unit."'");

      if(!count($mat))
          $this->db->insertQuery(array("material_name","material_unit","material_category","byName","byId"),'pr_itemuploads',array("'".$desc."'","'".$unit."'","-1","'".$this->Qlib->ud->user_name."'","'".$this->Qlib->ud->user_id."'"));

}public function  materialManagments($cs){
      switch($cs){
          case 1:
              return $this->tableMaterialUploads(explode("_",$_POST['id'])[1]);
          case 2:
             $id=$this->db->insertQuery(array("material_name","material_unit","material_category","is_category","byName","byId"),'pr_itemuploads',array("'".$_POST['nm']."'","'emp'","0","1","'".$this->Qlib->ud->user_name."'","'".$this->Qlib->ud->user_id."'"));

             return "<div class='tab-set a3-left a3-round a3-padding a3-margin a3-light-grey a3-pointer' id='tab_".$id."'>  <label>".$_POST['nm']."</label></div>";
          case 3:
              return $this->db->updateQuery(array("material_category='".$_POST['nm']."'"),'pr_itemuploads',"where id='".$_POST['id']."'");

          case 4:
              return $this->db->deleteQuery('pr_itemuploads','where id="'.$_POST['nm'].'"');
      }
}
	public function verifyRequisitionPurchase($reqId){
		$data=array();
		$pItems=array();$cItems=array();$status=false;
		
		$items=$this->Qlib->getRequestItems("where request_id=".$reqId);
		
		for($i=0;$i<count($items);$i++){
			$purchasedItem=$this->Qlib->getRequestItemPurchase("where request_id=".$reqId." and description='".$items[$i]->item_description."' group by description");
			
			if(count($purchasedItem))
				if($items[$i]->item_qty > $purchasedItem[0][0]){
					
					$cItems[]=$items[$i];
					
					$status=true;
				}else{
					$pItems[]=$items[$i];
				}
					
		}
		
		$data['completed']=$pItems;
		
		$data['todo']=$cItems;
		
		$data['status']=$status;
		
		return $data;
	}
	/*------------------------------------------START OF PROJECT LEVEL FUNCTIONS----------------*/

     public function  updatedProjectLevelFunction($case){
         switch ($case){
             case 0:
                 return $this->projectGannChartLayout($_POST['id']);
                 break;
             case 1:
                 return $this->projectManagementMembers($_POST['id']);
             case 2:
                 return $this->projectMemberInsertion($_POST['id'],$_POST['sel']);
             case 3:
                 return $this->projectManagementMembersNew();
             case 4:
                 return 0;
         }
     }
     public function projectManagements_101($id){
          $mailer =System::shared("sharedmailer");

          $message=new gcpEmail();

          $proj=$this->Qlib->getProjects("where id=".$id);

          if(!count($proj))
              return

          $message->projectName=$proj[0]->project_name;

          $message->content=$this->getProjectReportLayout($id);

          $message->fromMail="manprosystems@gmail.com";

          $message->fromName="MANPO SYSTEMS";

          $message->senderEmail="gcp@gmail.com";

          $message->header=" REPORT'S FOR ".strtoupper($proj[0]->project_name)." PROJECT";

          $message->mailDescription="PROJECT REPORT  ";

          #-----------------IMAGE HANDLING SCRIPT START----------------#
         $comp=array();

          $comps=$this->Qlib->getWorkSchedule(" where projectId='".$id."'");

          for($i=0;$i<count($comps);$i++)
              $comp[]=$comps[$i]->wk_id;

          //print_r($comps);
         // print_r(" where is_component=".implode(" or is_component=",$comp));

         if(!count($comp))
             return;

         $tasks=$this->Qlib->getWorkSchedule(" where is_component=".implode(" or is_component=",$comp),"%d/%b/%Y",1);

         $task=array();

         for($i=0;$i<count($tasks);$i++)
             $task[]=$tasks[$i]->wk_id;

         if(!count($task))
             return ;
          $images=$this->getAssets("where type=2 and ( component_id=".implode(" or component_id=",$task)." )");

          for($i=0;$i<count($images);$i++)
              $message->images[]=array("../../mpFile/".$this->Qlib->cmp->company_prefix."ufiles/".$images[$i]->as_content, 'image_'.$i);

          $email=$this->getAssets("where type=3 and project_id=".$id);

          if(!count($email))
              return;

          for($i=0;$i<count($email);$i++)
              $message->emails[]=array($email[$i]->as_content);

          $document=$this->getProjectReportPDF($id);

          $message->document=$document;

          #------------------IMAGE HANDLING SCRIPT END--------------------#
          $email=$mailer->sendMessage2($message);

          if($email->name){
              unlink($document);
              return $email;
          }else{
              print_r($email);
          }

     }
     public function getProjectReportPDF($id){
         $cont= new objectString();


         # -----THE HEADER SECTION OF THE REPORT -----------

         $project=$this->Qlib->getProjects("where id='".$id."'");

         if(count($project) ==0)
             return ;

         $cont->generalTags('  <section style="float:left;width:100%;">');

         $cont->generalTags('<h3 style="float:left;width:80%;font-size: 18px;margin:2px 10px;padding:5px 20px">Project name : '.ucfirst($project[0]->project_name).'</h3>');

         $cont->generalTags('<h4 style="float:left;width:80%;font-size: 18px;margin:2px 10px;padding:5px 20px">Project location :'.strtoupper($project[0]->project_location).'</h4>');

         $cont->generalTags(' <h5 style="float:left;width:80%;font-size: 18px;margin:2px 10px;padding:5px 20px">Report Date : '.date(" jS  F Y ").'</h5>');

         //  $cont->generalTags('<h5 style="float:left;width:80%;font-size: 18px;margin:2px 10px;padding:5px 20px">Weather  :</h5>');

         $cont->generalTags('</section>');

         #-------END OF THE HEADER SECTION OF THE REPORT

         #-----START DAILY WORK PROGRESS

         $cont->generalTags(' <section style="width:100%;float:left;font-size: 14px;margin:2px 10px;padding:5px 20px">');

         $cont->generalTags('<b style="float: left:width:100%"> 1. DAILY WORK PROGRESS</b>');

         $cont->generalTags('<table style="border:1px solid #bbb; margin:2px 0px;float:left;width:80%;margin-left: 5%;border-bottom: none" cellspacing="0" cellpadding="0">');

         $cont->generalTags('   <tr style="margin-bottom:1px;border-bottom:1px solid #bbb;float:left;width:100%">
                              <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:10%">Activity no </th> 
                              <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">Description </th>
                              <th style="padding-left: 10px;float:left;width:20%">Completion </th></tr>');

         $works=$this->Qlib->getWorkSchedule(" where projectId='".$id."'");

         $schedules=array();
         for($i=0;$i<count($works);$i++){
             $schedules[]=$works[$i]->wk_id;
             $cont->generalTags(' <tr style="margin-bottom:1px;border-bottom:1px solid #bbb;float:left;width:100%"> 
                            <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:10%">'.($i+1).'</td> 
                            <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">'.$works[$i]->wk_description.'</td>
                            <td style="padding-left: 10px;float:left;width:20%">'.$this->Qlib->getMaterialUsage($works[$i]->wk_id).'%</td>
                            </tr>');
         }

         $cont->generalTags('</table>');

         $cont->generalTags('</section>');

         #-----END DAILY WORK PROGRESS

         #---MATERIAL REPORTING START

         $cont->generalTags('<section style="width:100%;float:left;font-size: 14px;margin:2px 10px;padding:5px 20px">
        <b style="width:100%;float:left;margin: 2% 0">2.Materials</b>');

         #---IN STOCK
         if(!count($schedules))
             return;

         $estimate=$this->Qlib->getMaterialEstimates(' where component_id='.implode(" or component_id=",$schedules)." group by description");

         $cont->generalTags('<div style="float: left:width:100%">(a).In stock</div>');

         $cont->generalTags('<table  cellspacing="0" cellpadding="0"  style="border:1px solid #bbb; margin:2px 0px;float:left;width:80%;margin-left: 5%;border-bottom: none"> ');

         $cont->generalTags('<tr >
                                         <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">Material Name </th>
                                         <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:20%">Unit </th>
                                         <th style="padding-left: 10px;float:left;width: 20%">Qty </th></tr>');

         for($i=0;$i<count($estimate);$i++)
             $cont->generalTags(' <tr > 
                                       <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">'.$estimate[$i]->mat_description.'</td>
                                       <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:20%">'.$estimate[$i]->mat_unitType.'</td> 
                                       <td style="padding-left: 10px;float:left;width:20%"> 100 </td>            </tr>');

         $cont->generalTags(' </table> ');

         #---MATERIAL REPORTING END

         #--USED MATERIALS
         $cont->generalTags('<div style="float: left;width: 100%;margin:3% 0 2% 0%" >(b).Issued Materials</div>');

         $cont->generalTags('<table cellspacing="0" cellpadding="0" style="border:1px solid #bbb; margin:2px 0px;float:left;width:80%;margin-left: 5%;border-bottom: none;"> ');

         $cont->generalTags('<tr  >
                             <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">Material Name </th>
                             <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:20%">Unit </th>
                             <th style="padding-left: 10px;float:left;width:20%">Qty </th></tr>');

         for($i=0;$i<count($estimate);$i++)
             $cont->generalTags(' <tr > 
                                       <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">'.$estimate[$i]->mat_description.'</td>
                                       <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:20%">'.$estimate[$i]->mat_unitType.'</td> 
                                       <td style="padding-left: 10px;float:left;width:20%"> '.$estimate[$i]->mat_issued.' </td></tr>');

         $cont->generalTags(' </table> ');

         #--REQUESTED MATERIALS
         $cont->generalTags('<div style="float:left;width:100%;margin:3% 0 2% 0%;" class="head">(c).Requested Materials</div>');

         $cont->generalTags('<table class="a3-left a3-full" cellspacing="0" cellpadding="0" > ');

         $cont->generalTags('<tr >
                                  <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">Material Name </th>
                                  <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:20%">Unit </th>
                                  <th style=";padding-left: 10px;float:left;width:20%">Qty </th></tr>');

         for($i=0;$i<count($estimate);$i++) {
             $cont->generalTags(' <tr style="margin-bottom:1px;border-bottom:1px solid #bbb;float:left;width:100%"> 
                                      <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">'.$estimate[$i]->mat_description.'</td>
                                      <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:20%">'.$estimate[$i]->mat_unitType.'</td> 
                                      <td style=";padding-left: 10px;float:left;width:20%"> 100 </td>            </tr>');
         }
         $cont->generalTags(' </table> ');

         $task=array();

         $tasks=$this->Qlib->getWorkSchedule(" where is_component=".implode(" or is_component=",$schedules),"%d/%b/%Y",1);

         for($i=0;$i<count($tasks);$i++)
             $task[]=$tasks[$i]->wk_id;

         $images=$this->getAssets("where type=2 and ( component_id=".implode(" or component_id=",$task)." )");

         $cont->generalTags("<section name='images' style='width: 100%;float: left;margin:5%'>");

         $cont->generalTags("<b style='float:left;width:100%;margin-bottom: 2%;font-size: 16px;'>3.Images</b>");

         for($i=0;$i<count($images);$i++) {
             $dad=$this->Qlib->getWorkSchedule("where id=".$images[$i]->as_componentId." limit 1",$format="%d/%b/%Y",1);

             $cont->generalTags('<div style="width:45%;float:left ">
                                      <div style="float: left;width:100%;margin-bottom: 1%">('.($i+1).').'.$dad[0]->wk_description.'</div>
                                      <img style="width:100%;float:left " src="../../mpFile/'.$this->Qlib->cmp->company_prefix.'ufiles/'.$images[$i]->as_content.'" alt =' . $images[$i]->as_content . '>
                                      </div>');
         }
         $cont->generalTags("</section>");

         $mpdf = new \Mpdf\Mpdf(['defaultCssFile'=>"../../library/styles/report.css"]);

         $mpdf->WriteHTML($cont->toString());

         $filename='../../mpFile/'.$this->Qlib->cmp->company_prefix.'ufiles/filename.pdf';

         $mpdf->Output($filename, \Mpdf\Output\Destination::FILE);

         return $filename;

     }
     public function getProjectReportLayout($id){
         $cont= new objectString();

         # -----THE HEADER SECTION OF THE REPORT -----------

         $project=$this->Qlib->getProjects("where id='".$id."'");

         if(count($project) ==0)
             return ;

         $cont->generalTags('  <section style="float:left;width:100%;">');

         $cont->generalTags('<h3 style="float:left;width:80%;font-size: 18px;margin:2px 10px;padding:5px 20px">Project name : '.ucfirst($project[0]->project_name).'</h3>');

         $cont->generalTags('<h4 style="float:left;width:80%;font-size: 18px;margin:2px 10px;padding:5px 20px">Project location :'.strtoupper($project[0]->project_location).'</h4>');

         $cont->generalTags(' <h5 style="float:left;width:80%;font-size: 18px;margin:2px 10px;padding:5px 20px">Report Date : '.date(" jS  F Y ").'</h5>');

       //  $cont->generalTags('<h5 style="float:left;width:80%;font-size: 18px;margin:2px 10px;padding:5px 20px">Weather  :</h5>');

         $cont->generalTags('</section>');

         #-------END OF THE HEADER SECTION OF THE REPORT

         #-----START DAILY WORK PROGRESS

         $cont->generalTags(' <section style="width:100%;float:left;font-size: 14px;margin:2px 10px;padding:5px 20px">');

         $cont->generalTags('<b style="float: left:width:100%"> 1. DAILY WORK PROGRESS</b>');

         $cont->generalTags('<table style="border:1px solid #bbb; margin:2px 0px;float:left;width:80%;margin-left: 5%;border-bottom: none" cellspacing="0" cellpadding="0">');

         $cont->generalTags('   <tr style="margin-bottom:1px;border-bottom:1px solid #bbb;float:left;width:100%">
                              <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:10%">Activity no </th> 
                              <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">Description </th>
                              <th style="padding-left: 10px;float:left;width:20%">Completion </th></tr>');

         $works=$this->Qlib->getWorkSchedule(" where projectId='".$id."'");

         $schedules=array();
         for($i=0;$i<count($works);$i++){
             $schedules[]=$works[$i]->wk_id;
             $cont->generalTags(' <tr style="margin-bottom:1px;border-bottom:1px solid #bbb;float:left;width:100%"> 
                            <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:10%">'.($i+1).'</td> 
                            <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">'.$works[$i]->wk_description.'</td>
                            <td style="padding-left: 10px;float:left;width:20%">'.$this->Qlib->getMaterialUsage($works[$i]->wk_id).'%</td>
                            </tr>');
         }

         $cont->generalTags('</table>');

         $cont->generalTags('</section>');

         #-----END DAILY WORK PROGRESS

         #---MATERIAL REPORTING START

         $cont->generalTags('<section style="width:100%;float:left;font-size: 14px;margin:2px 10px;padding:5px 20px">
        <b style="width:100%;float:left;margin: 2% 0">2.Materials</b>');

        #---IN STOCK
         if(!count($schedules))
             return;

         $estimate=$this->Qlib->getMaterialEstimates(' where description <> 2 and description <>3 and description <> 4 and description <> "boq" and component_id='.implode(" or component_id=",$schedules)." group by description");

         $cont->generalTags('<div style="float: left:width:100%">(a).In stock</div>');

         $cont->generalTags('<table  cellspacing="0" cellpadding="0"  style="border:1px solid #bbb; margin:2px 0px;float:left;width:80%;margin-left: 5%;border-bottom: none"> ');

         $cont->generalTags('<tr style="margin-bottom:1px;border-bottom:1px solid #bbb;float:left;width:100%">
                                         <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">Material Name </th>
                                         <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:20%">Unit </th>
                                         <th style="padding-left: 10px;float:left;width: 20%">Qty </th></tr>');

         for($i=0;$i<count($estimate);$i++)
             $cont->generalTags(' <tr style="margin-bottom:1px;border-bottom:1px solid #bbb;float:left;width:100%"> 
                                       <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">'.$estimate[$i]->mat_description.'</td>
                                       <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:20%">'.$estimate[$i]->mat_unitType.'</td> 
                                       <td style="padding-left: 10px;float:left;width:20%"> 100 </td>            </tr>');

         $cont->generalTags(' </table> ');

         #---MATERIAL REPORTING END

         #--USED MATERIALS
         $cont->generalTags('<div style="float: left;width: 100%;margin:3% 0 2% 0%" >(b).Issued Materials</div>');

         $cont->generalTags('<table cellspacing="0" cellpadding="0" style="border:1px solid #bbb; margin:2px 0px;float:left;width:80%;margin-left: 5%;border-bottom: none;"> ');

         $cont->generalTags('<tr  style="margin-bottom:1px;border-bottom:1px solid #bbb;float:left;width:100%">
                             <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">Material Name </th>
                             <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:20%">Unit </th>
                             <th style="padding-left: 10px;float:left;width:20%">Qty </th></tr>');

         for($i=0;$i<count($estimate);$i++)
             $cont->generalTags(' <tr style="margin-bottom:1px;border-bottom:1px solid #bbb;float:left;width:100%"> 
                                       <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">'.$estimate[$i]->mat_description.'</td>
                                       <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:20%">'.$estimate[$i]->mat_unitType.'</td> 
                                       <td style="padding-left: 10px;float:left;width:20%"> '.$estimate[$i]->mat_issued.' </td></tr>');

         $cont->generalTags(' </table> ');

         #--REQUESTED MATERIALS
         $cont->generalTags('<div style="float:left;width:100%;margin:3% 0 2% 0%;">(c).Requested Materials</div>');

         $cont->generalTags('<table class="a3-left a3-full" cellspacing="0" cellpadding="0" style="border:1px solid #bbb; border-bottom: none;margin:2px 0px;float:left;width:80%;margin-left: 5%"> ');

         $cont->generalTags('<tr style="margin-bottom:1px;border-bottom:1px solid #bbb;float:left;width:100%">
                                  <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">Material Name </th>
                                  <th style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:20%">Unit </th>
                                  <th style=";padding-left: 10px;float:left;width:20%">Qty </th></tr>');

         for($i=0;$i<count($estimate);$i++) {
             $cont->generalTags(' <tr style="margin-bottom:1px;border-bottom:1px solid #bbb;float:left;width:100%"> 
                                      <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:50%">'.$estimate[$i]->mat_description.'</td>
                                      <td style="border-right:1px solid #bbb;padding-left: 10px;float:left;width:20%">'.$estimate[$i]->mat_unitType.'</td> 
                                      <td style=";padding-left: 10px;float:left;width:20%"> 100 </td>            </tr>');
         }
         $cont->generalTags(' </table> ');

         $task=array();

         $tasks=$this->Qlib->getWorkSchedule(" where is_component=".implode(" or is_component=",$schedules),"%d/%b/%Y",1);

         for($i=0;$i<count($tasks);$i++)
             $task[]=$tasks[$i]->wk_id;

         $images=$this->getAssets("where type=2 and ( component_id=".implode(" or component_id=",$task)." )");

         $cont->generalTags("<section name='images' style='width: 100%;float: left;margin:5%'>");

         $cont->generalTags("<b style='float:left;width:100%;margin-bottom: 2%;font-size: 16px;'>3.Images</b>");

         for($i=0;$i<count($images);$i++) {
             $dad=$this->Qlib->getWorkSchedule("where id=".$images[$i]->as_componentId." limit 1",$format="%d/%b/%Y",1);

             $cont->generalTags('<div style="width:45%;float:left ">
                                      <div style="float: left;width:100%;margin-bottom: 1%">('.($i+1).').'.$dad[0]->wk_description.'</div>
                                      <img style="width:100%;float:left " src="cid:image_' . $i . '" alt =' . $images[$i]->as_content . '>
                                      </div>');
         }
         $cont->generalTags("</section>");
         /*
           #---LABOUR REPORTS

          $cont->generalTags(' <section class="a3-left a3-full table">');

          $cont->generalTags(' <b class="a3-left a3-full">3.Labour</b> <table class="a3-left a3-full" cellspacing="0" cellpadding="0">');

          $cont->generalTags('<tr><th class="a3-center">Project Name </th><th width="10%">Required </th><th width="10%">Used </th><th class="">Cost</th></tr>');

          $cont->generalTags('<tr><td class="a3-center">Weston hotel</td><td>150</td><td> 130 </td><td class="cost"> 150 </td></tr>');

          $cont->generalTags('</table></section>');

          #---Comments
          $cont->generalTags(' <section class="a3-left a3-full table">');

          $cont->generalTags(' <b class="a3-left a3-full">4.Comments</b> <table class="a3-left a3-full" cellspacing="0" cellpadding="0">');

          $cont->generalTags('<tr><th class="a3-center" width="10%">Project Name </th><th >Cost</th></tr>');

          $cont->generalTags('<tr><td class="a3-center">Weston hotel</td><td>150</td></tr>');

          $cont->generalTags('</table></section>');

          #--comments
          */

         return $cont->toString();
     }
     public function projectMemberInsertion($projectId,$Userid){
        $id= $this->db->insertQuery(array('type','content','project_id','component_id','byid','byName')
             ,'pr_asset',
             array('3','(select username from frontend_users where id='.$Userid.' limit 1)',"'".$projectId."'","-2","'".$this->Qlib->ud->user_id."'","'".$this->Qlib->ud->user_name."'"));


         return $this->projectMemberTable($projectId);

     }
     public function projectManagementMembersNew(){
         $contents=array();
         if(isset($_POST['usr']))
             $contents['user']=$_POST['usr'];
         if(isset($_POST['phn']))
             $contents['phone']=$_POST['phn'];
         if(isset($_POST['email']))
             $contents['email']=$_POST['email'];
         if(isset($_POST['tit']))
             $contents['title']=$_POST['tit'];
         if(isset($_POST['temail']))
             $contents['alt_email']=$_POST['temail'];

         $this->db->insertQuery(array('type','content','project_id','component_id','byid','byName','contents')
            ,'pr_asset',array('3',"'".$contents['email']."'","'".$_POST['id']."'",'-1',"'".$this->Qlib->ud->user_id."'",
                "'".$this->Qlib->ud->user_name."'","'".json_encode
            ($contents)."'"));
         return $this->projectMemberTable($_POST['id']);
     }

     public function  projectGannChartLayout($id){
         $cont= new objectString();

         $project=$this->Qlib->getProjects("where id=".$id);

         $weekdays=array("M",'T','W','T','F','S','S');

         $dates=0;


         if(!count($project))
             return ;

         $startDate=date_create($project[0]->project_start);

         $endDate=date_create($project[0]->project_end);


         $timeline=date_diff($endDate,$startDate)->format("%a")+7;

         $list= new objectString();

         $list->generalTags("<section class='a3-left gann a3-full '>");

         $list->generalTags("<header class='a3-left a3-border a3-full'>".$project[0]->project_name."</header>");



         $scWidth=0;

         $cont->generalTags("<span class='headers a3-border-right' style='width:".(0.25*1140)."px'>Activity</span>");

         $cont->generalTags("<span class='headers a3-border-right' style='width:".(0.15*1140)."px'>Assigned to</span>");

         for($i=0;($timeline/7)>$i;$i++) {
             $cont->generalTags("<span class='headers a3-border-right' style='width:".(0.29967*1140)."px'>");

             $scWidth+=(0.29967*1140);

                 $cont->generalTags("<nav class='a3-left a3-full'>Week ".($i+1)."</nav>");

                 $cont->generalTags("<section class='a3-left a3-full'>");

                 foreach ($weekdays as $day) {
                     $cont->generalTags("<p class='a3-left' style='width: ".(0.14*1140)."px'>" . $day . "</p>");

                     $scWidth+=(0.14*1140);

                     $dates++;
                 }
                 $cont->generalTags("</section>");

             $cont->generalTags("</span>");
         }

         $cont->generalTags(" </div>");

        $comp=$this->Qlib->getWorkSchedule("where projectId=".$id,'%d-%b-%Y');


         for($i=0;$i<count($comp);$i++){



             $cont->generalTags("<section class='crows'>");

             $cont->generalTags("<span class='cel a3-border-right' style='width:".(0.246*1140)."px'><b>".$comp[$i]->wk_description."</b></span>");

             $cont->generalTags("<span class='cel  a3-border-right' style='width:".(14.85*1140)."px'></span>");

             for($t=0;($timeline/7)>$t;$t++) {
                 $cont->generalTags("<span class='cel a3-border-right' style='width:".(0.295*1140)."px'>");


                 foreach ($weekdays as $day) {
                     $cont->generalTags("<p class='a3-left' style='width: ".(0.14*1140)."px'> </p>");


                 }
                 $cont->generalTags("</span>");
             }

             $cont->generalTags("</section>");

             $tasks=$this->getTasks("where projectId=".$comp[$i]->wk_id,'%d/%b/%Y',1);

                 for($t=0;$t<count($tasks);$t++){
                      $cont->generalTags("<section class='crows'>");

                      $cont->generalTags("<span class='cel a3-border-right a3-padding-right' style='width:".(0.246*1140)."px'>".$tasks[$t]->tk_description."</span>");

                      $cont->generalTags("<span class='cel  a3-border-right' style='width:".(0.1485*1140)."px'>". $this->getAssignedUser($tasks[$t]->tk_assinedto)." </span>");

                     for($st=0;($timeline/7)>$st;$st++) {
                         $cont->generalTags("<span class='cel a3-border-right' style='width:".(0.295*1140)."px'>");

                         foreach ($weekdays as $day)
                             $cont->generalTags("<p class='a3-left' style='width: ".(0.14*1140)."px'> </p>");

                         $cont->generalTags("</span>");
                     }

                      $cont->generalTags("</section>");
                  }
         }

         $cont->generalTags("</section>");


         $cont->generalTags("<section class='a3-table hover'>");

         for($i=0;$i<count($comp);$i++){

             $st=date_create($comp[$i]->wk_frmDate);

             $en=date_create($comp[$i]->wk_toDate);

             $comTimeline=0;

             $width=$this->getNumericStartDate($comp[$i]->wk_frmDate,$weekdays);

             $startAt=date_diff($st,$startDate)->format("%a");

             $run=$comp[$i]->wk_remaining;

             $cont->generalTags("<section class='crows'>");

             $cont->generalTags("<span class='cel a3-border-right' style='width:24.6%'> </span>");

             $cont->generalTags("<span class='cel  a3-border-right' style='width:14.85%'></span>");

             for($t=0;($timeline/7)>$t;$t++) {
               // $cont->generalTags("<span class='cel a3-border-right' style='width:29.5%'>");

                 foreach ($weekdays as $day){

                     if( ($startAt+$width) ==$comTimeline)
                         $cont->generalTags("<div class='a3-red sch' style='width:".($comp[$i]->wk_days*14)."% '><i class='fas fa-sort-down' ></i></div>");


                 }


                //$cont->generalTags("</span>");
             }

             $cont->generalTags("</section>");

             $tasks=$this->getTasks("where projectId=".$comp[$i]->wk_id,'%d/%b/%Y',1);

             for($t=0;$t<count($tasks);$t++){
                 $cont->generalTags("<section class='crows'>");

                 $cont->generalTags("<span class='cel a3-border-right a3-padding-right' style='width:24.6%'></span>");

                 $cont->generalTags("<span class='cel  a3-border-right' style='width:14.85%'></span>");

                 for($st=0;($timeline/7)>$st;$st++) {
                     /*$cont->generalTags("<span class='cel a3-border-right' style='width:29.5%'>");

                     foreach ($weekdays as $day)
                         $cont->generalTags("<p class='a3-left' style='width: 14%'> </p>");

                    // $cont->generalTags("</span>");*/
                 }

                 $cont->generalTags("</section>");
             }
         }


         $cont->generalTags("</section>");

         $cont->generalTags("</section>");

         $list->generalTags("<section class='a3-left a3-full ' style='overflow: auto'>");

         $list->generalTags("<section class='a3-table a3-left'  style='width: $scWidth%'>");

         $list->generalTags("<div class='header a3-left a3-full' style='width:100%'>");

         $list->generalTags($cont->toString());

         $list->generalTags("</section>");

         return $list->toString();
     }

    public function getNumericStartDate($dat,$dates){

        foreach($dates as $key=>$date){
            if(strtolower(strftime("%a", strtotime($dat)))==$date)
                return  $key;
            else
                return 0;
        }

    }
    public function projectManagementMembers($projId){
           $cont= new objectString();

           $cont->generalTags("<project>");

           $cont->generalTags("<button class='a3-right a3-border a3-blue a3-hover-green a3-padding a3-margin-right a3-round a3-small'>Add Member</button>");

           $cont->generalTags("<fieldset class='a3-left a3-full'><legend>Member Details</legend>");

           $cont->generalTags("<small class='a3-left a3-full a3-margin-bottom a3-text-blue'>Adding a User to the project allows them to receive emails about the project</small>");

           $cont->generalTags("<label class='a3-left a3-half a3-padding a3-green'>Select From System</label><label class='a3-left a3-half a3-padding a3-light-gray a3-hover-blue'>Other Members</label>");

           $select = new objectString();

           $select->generalTags("<select class='quince_select' name='projSelect'>");

           $users=$this->um->getUsers("where  company_id='".$this->Qlib->cmp->company_id."'");

           $select->generalTags("<option value='-1'>Select System User</option>");

           foreach ($users as $val=>$user)
               $select->generalTags("<option value='".$user->user_id."' data-email='".$user->user_username."'>".$user->user_name."</option>");

           $select->generalTags("</select></div>");

           $cont->generalTags("<section class='a3-left a3-half'><label class='a3-left a3-margin' style='width:130px'> Username :</label> <div class='qs_wrap'>
                           ".$select->toString()."<button class='btn-project a3-left a3-padding a3-border a3-round a3-blue a3-hover-green' id='cat_$projId'> Submit Details</button> </section>");

           $cont->generalTags("<section class='a3-right a3-half a3-light-gray'>
                  <div class='a3-left a3-full'> <label class='a3-left a3-margin' style='width:130px'>Username</label><input name='username' class='a3-left a3-border a3-round a3-padding' type='text'></div>
                  <div class='a3-left a3-full'> <label class='a3-left a3-margin' style='width:130px'>Title</label><input name='title' class='a3-left a3-border a3-round a3-padding' type='text'></div>
                 <div class='a3-left a3-full'>  <label class='a3-left a3-margin' style='width:130px'>Phone Number</label>   <input name='phone' class='a3-left a3-border a3-round a3-padding' type='text'> </div>               
                  <div class='a3-left a3-full'>  <label class='a3-left a3-margin' style='width:130px'>Email Address</label><input name='email1' class='a3-left a3-border a3-round a3-padding' type='text'></div>
                 <div class='a3-left a3-full'>  <label class='a3-left a3-margin' style='width:130px'>Alt Email Address</label><input name='email2' class='a3-left a3-border a3-round a3-padding' type='text'></div>
                 <button class='btn-project a3-padding a3-right a3-round a3-border a3-blue a3-margin' id='projM_$projId'>Submit Details</button>
</section>");

           $cont->generalTags("</fieldset>");

           $cont->generalTags("<div class='a3-left a3-full'>");

           $cont->generalTags("<header>PROJECT MEMBERS</header>");

           $table=$this->projectMemberTable($projId);

           $cont->generalTags("<div class='a3-table-container a3-left a3-full'>".$table."</div>");

           $cont->generalTags("</div>");

           $cont->generalTags("</project>");

           return $cont->toString();
    }
    public function  projectMemberTable($id){
        $list= new open_table();

        $list->setColumnNames(array("id","Member Name","Email Address","Status","  "));

        $list->setColumnWidths(array("5%","25%","20%","10%","15%"));

        $memebers=$this->getAssets("where type=3 and project_id=".$id);

        // print_r("where type=3 and project_id=".$projId);

        for($i=0;$i<count($memebers);$i++) {

            $member=$this->um->getUsers(" where username='".$memebers[$i]->as_content."'");

            $list->addItem(array(($i + 1), $ww=count($member) ? $member[0]->user_name: " User 10".$i, $memebers[$i]->as_content,$ww= $memebers[$i]->as_status ==0 ? "<div class='a3-text-green'>Active</div>" : "Off", "  <i class='fas fa-chevron-circle-down'></i><i class='fas fa-trash'></i>"));
        }
        $list->showTable();

        return $list->toString();
    }
    public function getProjectDynamicprivillages(){
         return array(
             new name_value("Receive General Reports",1),
             new name_value("Receive Daily Reports",2),
             new name_value("Receive Weekly Reports",3),
             new name_value("Receive Monthly Reports",4),
             new name_value("Receive Component Reports",5),
             new name_value("Receive Assigned Task Reports",6)

         );
    }


    /*------------------------------------------END OF PROJECT LEVEL FUCNTIONS-----------------------------*/
}
class materialStore{
	public $mat_id;
	public $mat_desc;
	public $mat_store;
	public $mat_project;
	public $mat_value;
}
class materialEst{
	public $desc;
	public $unit;
	public $qty;
	public $cmp_id;
	public $chdId;
}
	
class dbAsset{
	public $as_id;
	public $as_type;
	public $as_content;
    public $as_user;
    public $as_projectId;
    public $as_componentId;
    public $as_dateProcessed;
    public $as_byId;
    public $as_byName;
    public $as_status;
    public $as_contents;
}
class equipTransferEQ{
	public $id;
	public $name;
	public $value;
	public $status;
}

class instanceV{
	public $comapny_id;
	public $byId;
	public $byName;
	public $system_v;
	public $instance_p;
	public $instance_u;
	public $instance_pu;
	public $isLaunched;
	public $instance_v;
	
}
class recItems{
     public $id;
     public $name;
     public $quatity;
     public $received;
}
class gcpEmail{
     public $content;
     public $projectName;
     public $emails;
     public $senderEmail;
     public $mailDescription;
     public $fromName;
     public $fromMail;
     public $images=array();
     public $document;
}
			
?>