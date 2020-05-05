<?php
function pr_printer(){
	
	return new showPriview;
	
}
class showPriview{
	public $pl;
    public function __construct(){
		$this->pl=System::shared('proman_lib');
		
		//$this->showPrintable();
	}
	public function showPrintable(){
		
	   if(isset($_GET['tp'])){
		    switch($_GET['ptyp']){
				case 'nprint':
		         $cont=new objectString();
			     //$cont->generalTags($_GET['ptyp']);
		         $cont->generalTags($this->showPrintableView($_GET['tp']));
		         echo  $cont->toString();
				 exit;
				 break;
					
				case 'xprint':
				$cont=new objectString();
				$cont->generalTags($this->showPrintableOR($_GET['tp']));
				echo $cont->toString();
				exit;
				break;
					
				case 'pdfDoc':
		        echo $this->showScanned($_GET['tp'],1);
				exit;
			    break;
					
				case 'mpPdf':
		        echo $this->showScanned($_GET['tp'],3);
				exit;
				break;
					
				case 'mpImage':
					echo $this->showScanned($_GET['tp']);
					exit;
					break;
					
				case 'llpdf':
					header('Content-Type: application/pdf');
		            echo $this->showScanned($_GET['tp'],1);
					exit;
					break;
					
				case 'fm':
					$this->showScanned($_GET['tp'],4);
					exit;
					break;
					
				case 'fmPdf':
					$this->showScanned($_GET['tp'],5);
					exit;
					break;
					
				case 'pl':
					echo $this->printPreview();
					exit;
					break;
					
				case 'llimage':
					  $ld=$this->pl->getLabourData("where theData='".$_GET['tp']."' or theData like '%".$_GET['tp']."%'");
					  for($i=0;$i<count($ld);$i++){ 
						if(isset($_GET['fd'])){
					      header('Content-Transfer-Encoding: binary');
						  if(is_array(json_decode($ld[$i]->l_extType))){
							header('Content-Type:image/'.json_decode($ld[$i]->l_data)[System::getArrayElementValue($_GET,'ext')]);
							header('Content-Disposition: attachment; filename=labourdoc-'.json_decode($ld[$i]->l_data)[System::getArrayElementValue($_GET,'ext')].'.'.json_decode($ld[$i]->l_extType)[System::getArrayElementValue($_GET,'ext')]);
							 echo $this->showScanned(System::getArrayElementValue($_GET,'tp'),2,System::getArrayElementValue($_GET,'ext'));
						  }else{
							header('Content-Type:image/'.$ld[$i]->l_extType);
					        header('Content-Disposition: attachment; filename=labourdoc-'.$ld[$i]->l_data.'.'.$ld[$i]->l_extType);
							echo $this->showScanned(System::getArrayElementValue($_GET,'tp'),2,System::getArrayElementValue($_GET,'ext'));
						  }
						}else{
						 if(is_array(json_decode($ld[$i]->l_extType))){
							header('Content-Type:image/'.json_decode($ld[$i]->l_data)[System::getArrayElementValue($_GET,'ext')]);
						    echo $this->showScanned(System::getArrayElementValue($_GET,'tp'),2,System::getArrayElementValue($_GET,'ext'));	
						 }else{
							header('Content-Type:image/'.$ld[$i]->l_data);
						   echo $this->showScanned(System::getArrayElementValue($_GET,'tp'),2,System::getArrayElementValue($_GET,'ext')); 
						 }
						
						}
					  }
					exit;
					break;
					
				case 'dwEx':
					header('Content-Type: application/vnd.ms-excel');
					header('Content-Disposition: attachment; filename=excel_doc.xlsx');
					echo file_get_contents(dirname(__FILE__).'/../../printpreview/exc_'.$this->pl->ud->user_id);
					exit;
					break;
					
				case 'comI':
					echo $this->showScanned($_GET['tp'],7);
					break;
					
			}
	   }
	}
	public function showPrintableView($id){
		
		$cont=new objectString;
		
		$cont->generalTags('<html>
		 <head>
		  <title>Printable</title>
		  <script type="text/javascript" src="library/scripts/macro_scripts/macro_quince/quince.js" language="javascript" ></script>
		  <link rel="stylesheet" href="system/layout_css.css" type="text/css" />
		  <link rel="stylesheet" href="library/styles/macro_styles/macro_quince/proman.css" type="text/css" />
		 
		  <script type="text/javascript">
		   
		   window.onload=function(){
		      document.getElementById("pTab").addEventListener("click",function(){
			    window.print();
			  });
		   }
		   
		  </script>
		 </head>
		 <body>');
		
		$req=$this->pl->getRequisitions('where id='.$id);
		
		for($i=0;$i<count($req);$i++){
			
	     //print_r($req[$i]);
			
		$proj=$this->pl->getProject($req[$i]->req_projectId);
			
		$cont->generalTags('<div id="form_row" style="margin-bottom:20px;"><img src="images/'.$this->pl->cmp->company_prefix.'logos.png" style="float:left" /><div style="margin-top:40px;float:left;margin-left:10px;font-size:14px;">'.strtoupper($this->pl->cmp->company_name).'</div><div class="mpTab" id="pTab" style="float:right;border:none;"></div></div>');
		
	    $cont->generalTags('<div style="float:left;width:100%;text-align:left;text-decoration:underline;">'.$req[$i]->req_date.'</div>');
			
		$cont->generalTags('<div style="float:left;width:90%;margin-left:5%;text-align:center;text-align:right;">'.$req[$i]->req_approvedDate.'</div>');	
			
		if($proj!=null)
		$cont->generalTags('<div style="float:left;width:100%;text-align:center;font-weight:bold;">'.$proj->project_name.'</div>');
				
		$cont->generalTags('<div style="float:left;width:100%;margin-top:10px;text-align:center;font-weight:bold;">Material Payment No. '.$req[$i]->req_no.'</div>');
		
		$ri=$this->pl->getRequestItems('where request_id='.$req[$i]->req_id.' order by lno asc');
			
					
		//---------------------------------------------HEADER-------------------------------------------
		$cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:5px;float:left;border:1px solid #000;">');
		
		$cont->generalTags('<div style="width:5%;font-weight:bold;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;">No.</div>');
		
		$cont->generalTags('<div style="width:35%;font-weight:bold;border-right:1px solid #000;text-align:left;padding-left:1%;float:left;">Description</div>');
		
		$cont->generalTags('<div style="width:5%;font-weight:bold;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;">Qty</div>');
		
		$cont->generalTags('<div style="width:5%;font-weight:bold;border-right:1px solid #000;text-align:left;padding-left:1%;float:left;">Unit</div>');
		
		$cont->generalTags('<div style="width:10%;font-weight:bold;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;">Rate</div>');
		
		$cont->generalTags('<div style="width:15%;font-weight:bold;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;">Amount</div>');
		
		$cont->generalTags('<div style="width:17%;font-weight:bold;text-align:left;padding-left:1%;float:left;">Remarks</div>');
		
		$cont->generalTags('</div>');
		
		//-------------------------------------------END HEADER---------------------------------------
		
	    $totalAm=0;
			
		for($c=0;$c<count($ri);$c++){
		//-------------------------------------------INNER ROW--------------------------------------------
		if($ri[$c]->item_qty!=-1){
		$cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:0px;float:left;border:1px solid #000;border-top:none;">');
		
		$cont->generalTags('<div style="width:5%;font-size:12px;padding:2px 0px;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;">'.$ri[$c]->item_no.'</div>');
		
		$cont->generalTags('<div style="width:35%;font-size:12px;padding:2px 0px;border-right:1px solid #000;text-align:left;padding-left:1%;float:left;">'.$ri[$c]->item_description.'</div>');
		
		$cont->generalTags('<div style="width:5%;font-size:12px;padding:2px 0px;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;">'.number_format($ri[$c]->item_qty,0).'</div>');
		
		$cont->generalTags('<div style="width:5%;font-size:12px;padding:2px 0px;border-right:1px solid #000;text-align:left;padding-left:1%;float:left;">'.$ri[$c]->item_unit.'</div>');
		
		$cont->generalTags('<div style="width:10%;font-size:12px;padding:2px 0px;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;">'.number_format($ri[$c]->item_rate,2).'</div>');
		
		$cont->generalTags('<div style="width:15%;font-size:12px;padding:2px 0px;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;">'.number_format($ri[$c]->item_amount,2).'</div>');
		
		$totalAm+=$ri[$c]->item_amount;
			
		$cont->generalTags('<div style="width:17%;font-size:12px;text-align:left;padding:2px 0px;padding-left:1%;float:left;">'.$ri[$c]->item_remarks.'</div>');
		
		$cont->generalTags('</div>');
		
		}else{
			
		$cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:0px;float:left;border:1px solid #000;border-top:none;">');
		
		$cont->generalTags('<div style="width:5%;font-size:12px;padding:2px 0px;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;min-height:15px;"> </div>');
		
		$cont->generalTags('<div style="width:35%;font-size:12px;padding:2px 0px;border-right:1px solid #000;text-align:left;padding-left:1%;float:left;">'.$ri[$c]->item_description.'</div>');
		
		$cont->generalTags('<div style="width:5%;font-size:12px;padding:2px 0px;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;min-height:15px;"> </div>');
		
		$cont->generalTags('<div style="width:5%;font-size:12px;padding:2px 0px;border-right:1px solid #000;text-align:left;padding-left:1%;float:left;min-height:15px;"> </div>');
		
		$cont->generalTags('<div style="width:10%;font-size:12px;padding:2px 0px;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;min-height:15px;"> </div>');
		
		$cont->generalTags('<div style="width:15%;font-size:12px;padding:2px 0px;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;min-height:15px;"></div>');
		
		$totalAm+=$ri[$c]->item_amount;
			
		$cont->generalTags('<div style="width:17%;font-size:12px;text-align:left;padding:2px 0px;padding-left:1%;float:left;min-height:15px;">'.$ri[$c]->item_remarks.'</div>');
		
		$cont->generalTags('</div>');
			
		}
			
		}
			
		//---------------------------------------------END INNER ROW---------------------------------------------
		
		$cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:0px;float:left;border:none;">');
		
		$cont->generalTags('<div style="width:5%;font-size:12px;padding:2px 0px;text-align:right;padding-right:1%;float:left;border-left:1px solid #fff;"></div>');
		
		$cont->generalTags('<div style="width:35%;font-size:12px;padding:2px 0px;text-align:left;padding-left:1%;float:left;border-left:1px solid #fff;"></div>');
		
		$cont->generalTags('<div style="width:5%;font-size:12px;padding:2px 0px;text-align:right;padding-right:1%;float:left;border-left:1px solid #fff;"></div>');
		
		$cont->generalTags('<div style="width:5%;font-size:12px;padding:2px 0px;text-align:left;padding-left:1%;float:left;border-left:1px solid #fff;"></div>');
		
		$cont->generalTags('<div style="width:10%;font-size:12px;padding:2px 0px;border:1px solid #000;text-align:right;padding-right:1%;float:left;font-weight:bold;border-top:none;">Total</div>');
		
		$cont->generalTags('<div style="width:15%;font-size:12px;padding:2px 0px;border:1px solid #000;text-align:right;padding-right:1%;float:left;font-weight:bold;border-left:none;border-top:none;">'.number_format($totalAm,2).'</div>');
		
		$cont->generalTags('<div style="width:17%;font-size:12px;text-align:left;padding:2px 0px;padding-left:1%;float:left;"></div>');
		
		$cont->generalTags('</div>');
			
		$cont->generalTags('<div style="width:90%;margin-left:5%;font-size:12px;margin-top:0px;float:left;margin-top:30px;"><div style="float:left;padding:0px 3px">PREPARED BY</div><div style="width:18%;float:left;border-bottom:1px dotted #000;height:14px;"></div><div style="float:left;padding:0px 3px;margin-left:10px">APPROVED BY</div><div style="width:18%;float:left;border-bottom:1px dotted #000;height:14px;"></div><div style="float:left;padding:0px 3px;width:80px;margin-left:10px;">PAID BY</div><div style="width:18%;float:left;border-bottom:1px dotted #000;height:14px;"></div></div>');
			
		$cont->generalTags('<div style="width:90%;margin-left:5%;font-size:12px;margin-top:0px;float:left;margin-top:25px;"><div style="float:left;padding:0px 3px;width:80px;">SIGNATURE</div><div style="width:18%;float:left;border-bottom:1px dotted #000;height:14px;"></div><div style="float:left;padding:0px 3px;width:80px;margin-left:10px">SIGNATURE</div><div style="width:18%;float:left;border-bottom:1px dotted #000;height:14px;"></div><div style="float:left;padding:0px 3px;width:80px;margin-left:10px">SIGNATURE</div><div style="width:18%;float:left;border-bottom:1px dotted #000;height:14px;"></div></div>');
			
		$cont->generalTags('<div style="width:90%;margin-left:5%;font-size:14px;margin-top:0px;float:left;margin-top:25px;">APPROVED BY</div>');
			
		$cont->generalTags('<div style="width:30%;margin-left:5%;font-size:12px;margin-top:0px;float:left;margin-top:10px;"><div style="float:left;padding:0px 3px;width:200px;">'.$req[$i]->req_level1ApproveName.'-'.$req[$i]->req_level1Date.'</div></div>');
			
		$cont->generalTags('<div style="width:25%;margin-left:5%;font-size:12px;margin-top:0px;float:left;margin-top:10px;"><div style="float:left;padding:0px 3px;width:200px;">'.$req[$i]->req_level2ApproveName.'-'.$req[$i]->req_level2Date.'</div></div>');
			
		$cont->generalTags('<div style="width:25%;margin-left:5%;font-size:12px;margin-top:0px;float:left;margin-top:10px;"><div style="float:left;padding:0px 3px;width:200px;">'.$req[$i]->req_level3ApproveName.'-'.$req[$i]->req_level3Date.'</div></div>');
			
		$cont->generalTags('<div style="width:25%;margin-left:5%;font-size:12px;margin-top:0px;float:left;margin-top:10px;"><div style="float:left;padding:0px 3px;width:200px;">'.$req[$i]->req_level4ApproveName.'-'.$req[$i]->req_level4Date.'</div></div>');
			
		
		}
			
		//$cont->generalTags($lst->toString());
		
		$cont->generalTags('</body>
		</html>');
		
		return $cont->toString();
	}
	public function printPreview(){
		
		$cont=new objectString;
		
		$cont->generalTags('<html>
		 <head>
		  <title>Printable</title>
		  <script type="text/javascript" src="library/scripts/macro_scripts/macro_quince/quince.js" language="javascript" ></script>
		  <link rel="stylesheet" href="system/layout_css.css" type="text/css" />
		  <link rel="stylesheet" href="library/styles/macro_styles/macro_quince/proman.css" type="text/css" />
		 
		  <script type="text/javascript">
		   
		   window.onload=function(){
		      document.getElementById("pTab").addEventListener("click",function(){
			    window.print();
			  });
		   }
		   
		  </script>
		 </head>
		 <body>');
		
		$cont->generalTags('<div id="form_row" style="margin-bottom:20px;"><img src="images/'.$this->pl->cmp->company_prefix.'logos.png" style="float:left"/><div style="margin-top:40px;float:left;margin-left:10px;font-size:14px;">'.strtoupper($this->pl->cmp->company_name).'.</div><div class="mpTab" id="pTab" style="float:right;border:none;"></div></div>');
		
		if(isset($_GET['ttls'])){
		
		  $titls=explode('_',$_GET['ttls']);
		  
		 for($v=0;$v<count($titls);$v++){
		  if($v==0){	
		    $cont->generalTags('<div style="float:left;font-size:18px;width:100%;margin-bottom:10px;text-align:center;font-weight:bold;">'.$titls[$v].'</div>');
		  }else{
			 $cont->generalTags('<div style="float:left;width:100%;text-align:center;font-weight:normal;margin-bottom:5px">'.$titls[$v].'</div>');
		  }
		 }
			
		}else{
		  
			$cont->generalTags('<div style="float:left;width:100%;text-align:center;font-weight:bold;">Title Not Available</div>');
			
		}
		
		$tDat=$this->pl->getPrintPreview($this->pl->ud->user_id);
		
		for($i=0;$i<count($tDat);$i++){
		
		if($tDat[$i]->print_colNames!=""){
		
		$cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:5px;float:left;border:1px solid #000;">');
		
	    $cols=json_decode($tDat[$i]->print_colNames);
			
		$styles=json_decode($tDat[$i]->print_colStyle);
			
	    $val=json_decode(strip_tags($tDat[$i]->print_colValue));
		
		$miniData=json_decode(strip_tags($tDat[$i]->print_mData));
			
		$mStyle=json_decode(strip_tags($tDat[$i]->print_mStyle));
		
	    for($x=0;$x<count($cols);$x++){
		  $sty='border-right:1px solid #000;';
		  if($x==(count($cols)-1))
			 $sty="";
		
		  $cellVal=$cols[$x];
			
		  if($cellVal=="")
			$cellVal=" ";
			
		  $cont->generalTags('<div style="font-weight:bold;'.$sty.'text-align:right;float:left;text-indent:2px;'.$styles[$x].'">'.$cellVal.'</div>');
		}
			
		$cont->generalTags('</div>');
		
		for($c=0;$c<count($val);$c++){
			
		$cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:0px;float:left;border:1px solid #000;border-top:none;">');
			
		for($x=0;$x<count($cols);$x++){
		  $sty='border-right:1px solid #000;';
		  if($x==(count($cols)-1))
			 $sty="";
			
		   $cellVal=$val[$c][$x];
			
		  if($cellVal=="")
			$cellVal=" ";
			
		  $cont->generalTags('<div style="font-size:12px;padding:2px 0px;'.$sty.'float:left;min-height:15px;text-indent:2px;'.$styles[$x].'">'.$cellVal.'</div>');
		
		}		
			
		$cont->generalTags('</div>');
	    //if(count($miniData)>1)
		if(isset($miniData[$c])&&(count($miniData[$c])>1)){
		  $cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:0px;float:left;border:1px solid #000;border-top:none;background:#ddd;">');
		//for($x=0;$x<count($mStyle);$x++)
		  for($x=0;$x<count($miniData[$c]);$x++){
			$cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:0px;float:left;border:1px solid #bbb;border-top:none;">');
			for($s=0;$s<count($miniData[$c][$x])-1;$s++){
		    $cont->generalTags('<div style="font-size:12px;padding:2px 0px;'.$mStyle[$s].'float:left;min-height:15px;text-indent:2px;">'.$miniData[$c][$x][$s].'</div>');
			}
			  
			$cont->generalTags('</div>');
		  }
		
		  $cont->generalTags('</div>');	
		}
			
		}
			
		}
		
		}
		$cont->generalTags('</body></html>');
		
		return $cont->toString();
		
	}
	public function showPrintableOR($id){
		
		$cont=new objectString;
		
		$cont->generalTags('<html>
		 <head>
		  <title>Printable</title>
		  <script type="text/javascript" src="library/scripts/macro_scripts/macro_quince/quince.js" language="javascript" ></script>
		  <link rel="stylesheet" href="system/layout_css.css" type="text/css" />
		  <link rel="stylesheet" href="library/styles/macro_styles/macro_quince/proman.css" type="text/css" />
		 
		  <script type="text/javascript">
		   
		   window.onload=function(){
		      document.getElementById("pTab").addEventListener("click",function(){
			    window.print();
			  });
		   }
		   
		  </script>
		 </head>
		 <body>');
		
		$cont->generalTags('<div id="form_row" style="margin-bottom:5px;"><img src="images/'.$this->pl->cmp->company_prefix.'logos.png" style="float:left;margin-left:40px;" /><div class="mpTab" id="pTab" style="float:right;border:none;"></div></div>');
		
		$cont->generalTags('<div style="width:100%;margin-left:0%;margin-top:0px;margin-bottom:20px;float:left;border:none;border-top:none;font-size:14px;">'.strtoupper($this->pl->cmp->company_name).'</div>');
		
		$op=$this->pl->getOfficeRequests('where id='.$id);
		
		for($u=0;$u<count($op);$u++){
		
		  $cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:0px;margin-bottom:20px;float:left;border:none;border-top:none;font-size:14px;">No. '.$op[$u]->proc_no.'<div style="float:right;">'.$op[$u]->proc_date.'</div></div>');
		
		  $cont->generalTags('<div style="width:100%;margin-left:0%;margin-top:0px;margin-bottom:20px;float:left;border:none;border-top:none;font-size:14px;text-align:center;font-weight:bold;">PAYMENT REQUISITION</div>');
			
		  $cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:0px;margin-bottom:20px;float:left;border:none;border-top:none;font-size:14px;"><b>Debit Acc: '.strtoupper($this->pl->cmp->company_name).'</b></div>');
			
		
		//--------------------------------------------HEADER ROW--------------------------------------
		$cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:5px;float:left;border:1px solid #000;">');
		
		  $cont->generalTags('<div style="width:8%;font-weight:bold;text-align:right;padding-right:1%;float:left;">No.</div>');
		
		  $cont->generalTags('<div style="width:68%;font-weight:bold;border-left:1px solid #000;bold;border-right:1px solid #000;text-align:left;padding-left:1%;float:left;">Description</div>');
		
		  $cont->generalTags('<div style="width:20%;font-weight:bold;text-align:right;padding-right:1%;float:left;">Amount</div>');
				
		$cont->generalTags('</div>');

		//------------------------------------------END HEADER ROW------------------------------------
		
		$theItems=$this->pl->getOpItems('where proc_id='.$id);
		
		$amount=0;
		
		for($i=0;$i<count($theItems);$i++){
			
			$cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:0px;float:left;border:1px solid #000;border-top:none;">');
			
			$cont->generalTags('<div style="width:8%;text-align:right;padding-right:1%;float:left;">'.($i+1).'</div>');
		
		    $cont->generalTags('<div style="width:68%;border-right:1px solid #000;border-left:1px solid #000;text-align:left;padding-left:1%;float:left;">'.$theItems[$i]->op_itemDesc.'</div>');
		
		    $cont->generalTags('<div style="width:20%;text-align:right;padding-right:1%;float:left;">'.number_format($theItems[$i]->op_amount,2).'</div>');
			
			$cont->generalTags('</div>');
			
			$amount+=$theItems[$i]->op_amount;
			
		}
		
		$cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:0px;float:left;border:none;">');
			
		//$cont->generalTags('<div style="width:8%;border-right:1px solid #000;text-align:right;padding-right:1%;float:left;">'.($i+1).'</div>');
		
		$cont->generalTags('<div style="width:77%;margin-top:10px;text-align:left;padding-left:2px;float:left;margin-right:0px;font-size:14px;">TOTAL PAYMENT</div>');
		
		$cont->generalTags('<div style="width:21%;text-align:right;padding-right:0.5%;float:right;border:1px solid #000;border-top:none;margin-left:1px;font-weight:bold;padding-top:7px;padding-bottom:5px;font-size:14px;">'.number_format($amount,2).'</div>');
			
		$cont->generalTags('</div>');
		
		$cont->generalTags('<div style="width:90%;margin-left:5%;margin-top:5px;margin-top:30px;float:left;border:1px solid #000;margin-bottom:20px;">');
		
		$cont->generalTags('<div style="width:96%;margin:10px 2%;float:left;"><b>Prepared By: </b>'.$op[$u]->proc_byName.'<div style="float:right;overflow:hidden;width:40%;"><b>Approved By: </b>'.$op[$u]->proc_appByName.'</div></div>');
			
		$cont->generalTags('<div style="width:96%;margin:10px 2%;float:left;"><b>Sign : </b><div style="float:right;overfolw:hidden; width:40%;"><b>Sign: </b></div></div>');
			
		$cont->generalTags('<div style="width:96%;margin:10px 2%;float:left;"><b>Date : </b>'.$op[$u]->proc_date.'</div>');
		
		$cont->generalTags('</div>');
			
		}
		
		$cont->generalTags('</body></html>');
		
		return $cont->toString();
		
	}
	public function showScanned($id,$ap=0,$ext=""){
		
		return $this->pl->showScannedMp($id,$ap,$ext);
		
	}
}
?>