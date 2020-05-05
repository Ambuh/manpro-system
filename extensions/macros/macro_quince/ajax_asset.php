<?php

ini_set('display_errors','1');

require 'vendor/autoload.php';//'PHPExcel/IOFactory.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing as drawing; // Instead PHPExcel_Worksheet_Drawing
use PhpOffice\PhpSpreadsheet\Style\Alignment as alignment; // Instead PHPExcel_Style_Alignment
use PhpOffice\PhpSpreadsheet\Style\Fill as fill; // Instead PHPExcel_Style_Fill
use PhpOffice\PhpSpreadsheet\Style\Color as color_; //Instead PHPExcel_Style_Color
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup as pagesetup; // Instead PHPExcel_Worksheet_PageSetup
use PhpOffice\PhpSpreadsheet\IOFactory as io_factory; // Instead PHPExcel_IOFactory
include(dirname(__FILE__).'/quince_helper.php');

class ajax_run{

    public $qr;

    public function __construct(){

        $this->qr=new HQuince;

    }

    public function main(){

        

		if(!isset($_SESSION[System::getSessionPrefix()."USER_LOGGED"])){

			$this->generateContent(true,"LOGGED_OUT","");

			return;

		}

		

        switch($_GET['sq']){

        

        case 1://Main content load

		$op=$_POST['vName'];

		if((explode('_',$_POST['vName'])[0]!="m")&&(explode('_',$_POST['vName'])[0]!="inMen")&&(explode('_',$_POST['vName'])[0]!="tb")&&(explode('_',$_POST['vName'])[0]!="mc")){

			$op=explode('_',$_POST['vName'])[0];

		}

        switch($op){

			
            case 'm_1':case 'tb_1':

            $this->generateContent(true,$this->qr->dashboardContent(),"");

            break;
			
			case "m_6":case "mc_6":

			
			  session_destroy();
			break;

            case 'm_2': case 'inMen_1':case 'tb_2':

            $this->generateContent(true,$this->qr->listProjects(),$this->qr->contribMenu());

            break;

            

            case 'm_3': case 'inMen_5':case 'tb_3':

            $this->generateContent(true,$this->qr->showSites(),$this->qr->sitesMenu());

            break;

            

            case 'm_4':case 'tb_4':

			//$this->qr->showRequisition();

			

			if(!$this->qr->positionHasPrivilege($this->qr->ud->user_userType,70)){

			   $this->generateContent(true,$this->qr->showProjectMps(),$this->qr->reqMenu());

			}else{

			   $this->generateContent(true,$this->qr->loadMyRequisitions(),$this->qr->reqMenu());

			}

			

            break;

            

			case 'm_5':case 'inMen_9':case 'tb_5':

            $this->generateContent(true,$this->qr->viewInventory(),$this->qr->inventoryMenu());

            break;
            case "inMen_44":
                $as=System::shared("assist");
                
                $this->generateContent(true,$as->mnStoreInventory());
                break;
            

            case 'm_7':case 'inMen_3':case 'tb_7':

          //  $this->generateContent(true,$this->qr->viewReports(),$this->qr->investMenu());

			$this->generateContent(true,$this->qr->viewReports());
			
            break; 

            

			case 'm_8':case 'tb_8':case 'inMen_25':

			$this->generateContent(true,$this->qr->systemUsers(),$this->qr->usersMenu());	

			break;

				

			case 'inMen_26':

			$this->generateContent(true,$this->qr->accessLogs(),'');

			break;

            case 'inMen_37':

                $as=System::shared("assist");

                $this->generateContent(true,$as->materialManagement());

                break;
			

			case 'tb_30':

			$this->generateContent(true,$this->qr->searchRItems(),'<div style="padding:5px 5px;font-size:18px;">Find Requisition Items</div>');	

			break;

				

			case 'm_12':case 'inMen_12':

			$this->generateContent(true,$this->qr->viewPurchases(),$this->qr->purchaseMenu());	

			break;

			

			case 'm_13': case 'inMen_27': case 'tb_13'://Verifications

			$this->generateContent(true,$this->qr->verification(),$this->qr->verifyMenu());

			break;

				

			case 'm_21': //Income and Expenses

			$this->generateContent(true,$this->qr->recordIncome(),$this->qr->expenseIncomeMenu());

			break;

				

			case 'm_22': case 'inMen_31'://Income and Expenses

			$this->generateContent(true,$this->qr->incomeExpenses(),$this->qr->officeProcurementMenu());

			break;

				

			case 'm_23':

			$this->generateContent(true,$this->qr->viewScheduleOfWork(),' ');

			break;

				

			case 'inMen_32': 

			$this->generateContent(true,$this->qr->newOfficeRequisition());

			break;

			

			case 'inMen_42':

			$this->generateContent(true,$this->qr->showIssueItems());

			break;

				

			case 'inMen_33':

			$this->generateContent(true,$this->qr->recordIncome());

			break;

				

			case 'inMen_34':

			$this->generateContent(true,$this->qr->recordPettyCash());

			break;

				

			case 'tb_16':

			$this->generateContent(true,$this->qr->projectAssignment());

			break;

				

			case 'inMen_28':

			$this->generateContent(true,$this->qr->verifyMatPayment());

			break;

				

			case 'inMen_13':

			$this->generateContent(true,$this->qr->viewPurchases(true));	

			break;

				

            case 'inMen_2':

            $this->generateContent(true,$this->qr->createProject());

            break;

            

            case 'inMen_4':

            $this->generateContent(true,$this->qr->newAssetInvestForm());

            break;

            

            case 'inMen_6':

            $this->generateContent(true,$this->qr->createSite());

            break;

				

			case 'inMen_7':

			if(!$this->qr->positionHasPrivilege($this->qr->ud->user_userType,70)){

			   $this->generateContent(true,$this->qr->showProjectMps(),$this->qr->reqMenu());

			}else{

			   $this->generateContent(true,$this->qr->loadMyRequisitions(),$this->qr->reqMenu());

			}

            break;

				

			case 'inMen_8':

            $this->generateContent(true,$this->qr->newRequisition());

            break;

            

			case 'inMen_10':

			$this->generateContent(true,$this->qr->receiveFromHq());

			break;

			

			case 'inMen_11':

			$this->generateContent(true,$this->qr->receiveFromLocal());

			break;

			

			case 'rNu':

			$this->generateContent(true,$this->qr->viewRequestItems(explode('_',$_POST['vName'])[1]));	

			break;

				

			case 'exp':

			$this->generateContent(true,$this->qr->displayProject(explode('_',$_POST['vName'])[1]));

			break;

				

			case 'rq':

			$this->generateContent(true,$this->qr->showORDetails(explode('_',$_POST['vName'])[1]));

			break;

				

			case 'm_10': case 'tb_10':case 'inMen_14': 

			$this->generateContent(true,$this->qr->showLabour(),$this->qr->labourMenu());

			break;

			

			case 'm_11': case 'tb_11': case 'inMen_15':

		    $this->generateContent(true,$this->qr->loadEquipment(),$this->qr->equipmentMenu());		

		    break;

				

			case 'inMen_17':

			$this->generateContent(true,$this->qr->addEquipmentPanel(),$this->qr->equipmentMenu());		

		    break;

				

			case 'inMen_18':

			$this->generateContent(true,$this->qr->equipmentTransfers(),$this->qr->equipmentMenu());		

		    break;
				
			case "inMen_98":
				
			  $as=System::shared("assist");
				
			  $this->generateContent(true,$as->viewEquipRequests());
				
			break;
				
			case "inMen_99":
				
			  $this->generateContent(true,$this->qr->equipmentRequest());
				
			break;
           
				

			case 'mc_14':

			$this->generateContent(true,$this->qr->changePassPanel());

			break;

			

			case 'mc_15': case 'inMen_29':

			$this->generateContent(true,$this->qr->showSettings(),$this->qr->settingsMenu());

			break;

				

			case 'tb_9': case 'm_9':

			$this->generateContent(true,$this->qr->advancedESSettings());

			break;

				

			case 'tb_100':case 'inMen_100':

			$this->generateContent(true,$this->qr->manageCompanies(),$this->qr->companyMasterMenu());

			break;

				

			case 'inMen_102':

			$this->generateContent(true,$this->qr->createCompanyForm(),$this->qr->companyMasterMenu());

			break;

				

			case 'inMen_16':

			$this->generateContent(true,$this->qr->showResetLabourRequest());

			break;

				

			case 'inMen_19':

			$this->generateContent(true,$this->qr->showOverallReceived());

			break;

				

			case 'inMen_20':

			$this->generateContent(true,$this->qr->showOverallPurchased());

			break;

				

			case 'inMen_21':

			$this->generateContent(true,$this->qr->foremanRequests());

			break;

				

			case 'inMen_22':

			$this->generateContent(true,$this->qr->newForemanRequests());

			break;

				

			case 'inMen_35':

			$this->generateContent(true,$this->qr->oPettyCashPurhases());

			break;

				

			case 'inMen_39':

			$this->generateContent(true,$this->qr->privilegeManager(),"");

			break;

				

			case 'inMen_40':

			$this->generateContent(true,$this->qr->manageApprovalLevels(),"");

			break;

				
			case "inMen_112":
				$us=System::shared("usermanager");
				 $tmp=new am_assist;
				
				$this->generateContent(true,$tmp->createTbl(),"");
			break;
			case "m_14":
				 $tmp=System::shared("assist");
				
				$this->generateContent(true,$tmp->administration(),$tmp->adminMenu());
			break;

            default:

            $this->generateContent(true,"","");

        }

        break;

        

        case 2://Select Pay Option

        switch($_POST['vOpt']){

          case 1:  

          $this->generateContent(true,'<div style="overflow:hidden;float:left;width:100%;">'.$this->qr->contributionInput().'</div>',$this->qr->contribMenu());

          break;

          

          case 2:  

          $this->generateContent(true,'<div style="overflow:hidden;float:left;width:100%;">'.$this->qr->contributionInput().'</div>',$this->qr->contribMenu());

          break;

          

          default:

          $this->generateContent(true,"");

        }

        break;

        

        case 3://Search content load

         switch($_POST['sSource']){

            

            case 'findM':

            $this->generateContent(true,$this->qr->membersList());

            break;

            

            case 'accFrom': case 'accTo':

            $this->generateContent(true,$this->qr->viewPayList());

            break;

            

            case 'rFrom': case 'rTo':

            $this->generateContent(true,"");

            break;

            

         }

        break;

        

        case 4 :

        switch($_POST['vOption']){

            case 1:

            $this->generateContent(true,$this->qr->viewOverallReport($_POST['sOp']));

            break;

            

            case 2:

            $this->generateContent(true,$this->qr->showInvestmentList());

            break;

				

			default:

		    $this->generateContent(true,'');

            

        }

        break;

        

		case 5:

		//echo $this->qr->showReqList();

		$op=explode('_',System::getArrayElementValue($_GET,'si'));

		switch($op[0].'_'.$op[1]){

			case 'sid_1':case 'sid_7':

			echo $this->qr->showReqList();	

			break;

				

			case 'sid_2':

			echo $this->qr->showReceiveInventoryList();

			break;

			

			case 'sid_4':

			echo $this->qr->billOfQty();

			break;

				

			case 'sid_5':case 'sid_11':

			echo @$this->qr->showUploadedExcel();

			break;

			

			case 'sid_7':

			echo $this->qr->receiveList();

			break;

				

			case 'sid_8':

			echo $this->qr->showUploadedExcel(true);

			break;

				

			case 'sid_10':

			echo $this->qr->addEquipmentList();	

			break;

				

			default:

			echo "Invalid Option";

		}

		break;

				

		case 6://Submission of list content

		switch($_POST['sType']){

			case 1:

			$reqId=0;	

			$status=$this->qr->saveRequisition(json_decode($_POST['listData']),$_POST['siteId'],$_POST['replc'],$reqId);

			$rz=$this->qr->getRDet($reqId);

			$this->qr->linkFmToRequest($_POST['frid'],$reqId);

			
			$this->qr->notifyUsers('Construction Manager,Administrator',array('Construction Manager'=>$this->qr->getSiteName($rz->req_siteId).'\'s Material Payment '.$rz->req_no.' from '.$this->qr->ud->user_name.' is on queue awaiting approval','Administrator'=>$this->qr->getSiteName($rz->req_siteId).'\'s Material Payment '.$rz->req_no.' from '.$this->qr->ud->user_name,'Subject'=>$this->qr->getSiteName($rz->req_siteId).': Material Payment '.$rz->req_no.' is on queue.'),4,0,true,true,$this->qr->getSiteProjectId($rz->req_siteId));


			$this->generateContent($status->name,'Refresh','',$status->value);

			break;

			

			case 2://update requisition

			//print_r(json_decode($_POST['listData']));

			$status=$this->qr->updateRequisitionItems(json_decode($_POST['listData']),$_POST['oid']);

			$this->generateContent($status->name,'','',$status->value);

			break;

				

			case 3:

			$this->generateContent(true,'','',System::successText('Done'));

			break;

				

			case 4:

			$this->qr->saveBOQ(json_decode($_POST['listData']),$_POST['upid']);	

			break;

				

			case 5:

			$nm=json_decode($_POST ['extF']);

			if(count($nm)>0){

			$this->qr->saveLabourData($_POST['uid'],$_POST['theDate'],$_POST['listData'],explode(':',$nm[1])[1],explode(':',$nm[0])[1],$_POST['lType'],"");

		    

			$sum=array('','summary','detailed','scanned copies');

				

			$this->qr->notifyUsers('Secretary,Accounts,Administrator',array('Secretary'=>$this->qr->getSiteName($_POST['uid']).': '.$this->qr->ud->user_name.' has uploaded labour '.$sum[$_POST['lType']].' report for '.$_POST['theDate'].'','Accounts'=>$this->qr->getSiteName($_POST['uid']).': '.$this->qr->ud->user_name.' has uploaded labour '.$sum[$_POST['lType']].' report for '.$_POST['theDate'].'','Administrator'=>$this->qr->getSiteName($_POST['uid']).': '.$this->qr->ud->user_name.' has uploaded labour '.$sum[$_POST['lType']].' report for '.$_POST['theDate'].'','Subject'=>$this->qr->getSiteName($_POST['uid']).': Labour Upload'),10,false,false);

				

			$this->generateContent(true,'Replace','',$this->qr->loadLabour(),'lForm');

			}else{

				

				$sum=array('','summary','detailed','scanned copies');

				

				

				echo "asdad";

				$this->qr->saveLabourData($_POST['uid'],$_POST['theDate'],$_POST['listData'],0,0,$_POST['lType'],'');

				

				$this->qr->notifyUsers('Secretary,Accounts,Administrator',array('Secretary'=>$this->qr->getSiteName($_POST['uid']).': '.$this->qr->ud->user_name.' has uploaded labour '.$sum[$_POST['lType']].' report for '.$_POST['theDate'].'','Accounts'=>$this->qr->getSiteName($_POST['uid']).': '.$this->qr->ud->user_name.' has uploaded labour '.$sum[$_POST['lType']].' report for '.$_POST['theDate'].'','Administrator'=>$this->qr->getSiteName($_POST['uid']).': '.$this->qr->ud->user_name.' has uploaded labour '.$sum[$_POST['lType']].' report for '.$_POST['theDate'].'','Subject'=>$this->qr->getSiteName($_POST['uid']).': Labour Upload'),10,0,false,false);

				

			    $this->generateContent(true,'Replace','',$this->qr->loadLabour(),'lForm');

			}

			

			break;

				

			case 6:

			$data=json_decode($_POST['listData']);

			

			if(isset($_SESSION[System::getSessionPrefix().'RID'])){

				/*for($c=0;$c<count($data);$c++){

					if($data[$c][1]==0){

					  $this->generateContent(true,'','',System::getWarningText('Invalid Quantity'));

					  return;

					}

				}

				for($c=0;$c<count($data);$c++){

				

						$this->qr->addItemToSite(explode('_',$_SESSION[System::getSessionPrefix().'RID'])[1],$data[$c][0],$data[$c][1],$data[$c][2],explode('_',$_SESSION[System::getSessionPrefix().'RID'])[0],1);

					

				}*/

				$status=false;

				for($i=0;$i<count($data);$i++){

					if($data[$i][1]!="")

						$status=true;

				}

				

				$this->qr->saveReceived($data,$_POST['tDate']);

				if($status){

					

				  $request=$this->qr->getRDet($_SESSION[System::getSessionPrefix().'_rid']);

				

				  $this->qr->notifyUsers('Administrator,Accounts,General Manager',array('Administrator'=>$this->qr->getSitename($request->req_siteId).': Items received on '.$_POST['tDate'].' for material payment '.$request->req_no.' recorded by '.$this->qr->ud->user_name,'Accounts'=>$this->qr->getSitename($request->req_siteId).': Items received on '.$_POST['tDate'].' for material payment '.$request->req_no.' recorded by '.$this->qr->ud->user_name,'General Manager'=>$this->qr->getSitename($request->req_siteId).': Items received on '.$_POST['tDate'].' for material payment '.$request->req_no.' recorded by '.$this->qr->ud->user_name,'Subject'=>$this->qr->getSitename($request->req_siteId).": Items Received"),5,0,false,false);

			      $this->generateContent(true,'Refresh','',System::successText('Items submited successfully'));

				}else{

				   $this->generateContent(true,'','',System::getWarningText('Item Quantities Required'));

				}

			}else{

				$this->generateContent(true,'Refresh','',System::successText('Items submited successfully'));

			}

			

			break;

				

			case 7:

			$data=json_decode($_POST['listData']);

			

			//if(isset($_SESSION[System::getSessionPrefix().'RID'])){

				for($c=0;$c<count($data);$c++){

					if($data[$c][1]==0){

					  $this->generateContent(true,'','',System::getWarningText('Invalid Quantity'));

					  return;

					}

				}

				

				for($c=0;$c<count($data);$c++){

				

						$this->qr->addItemToSite(explode('_',$_POST['upid'])[0],$data[$c][0],$data[$c][1],$data[$c][2],explode('_',$_POST['upid'])[1],2,$_POST['tDate']);

					

					//explode('_',$_SESSION[System::getSessionPrefix().'RID'])[0]

				}

				

				$this->qr->notifyUsers('Administrator,Accounts,General Manager',array('Administrator'=>$this->qr->getSiteName(explode('_',$_POST['upid'])[0]).': Items received on '.$_POST['tDate'].' for petty cash recorded by '.$this->qr->ud->user_name,'Accounts'=>$this->qr->getSiteName(explode('_',$_POST['upid'])[0]).': Items received on '.$_POST['tDate'].' for petty cash recorded by '.$this->qr->ud->user_name,'General Manager'=>$this->qr->getSiteName(explode('_',$_POST['upid'])[0]).': Items received on '.$_POST['tDate'].' for petty cash recorded by '.$this->qr->ud->user_name,'Subject'=>$this->qr->getSiteName(explode('_',$_POST['upid'])[0]).": Item Receied"),5,0,false,false);

				

				$this->generateContent(true,'Refresh','',System::successText('Items submited successfully'));

			//}else{

			//	$this->generateContent(true,'Refresh','',System::successText('Items submited successfully'));

			//}

			

			break;

				

			case 8:

				$data=json_decode($_POST['listData']);

				for($i=0;$i<count($data);$i++){

					if((trim($data[$i][1])!="")|(trim($data[$i][2])!="")){

						if(trim($data[$i][1])==""){

						  $this->generateContent(true,' ','',System::getWarningText('Rate Required For '.$data[$i][0]));

						  return;

						}elseif(trim($data[$i][2])==""){

						  $this->generateContent(true,' ','',System::getWarningText('Quantity Required For '.$data[$i][0]));	

						  return;

						}

					}

				}

				$this->qr->savePurchase($data,$_POST['tDate']);

				

				$request=$this->qr->getRDet($_SESSION[System::getSessionPrefix().'_rid']);

				

				$this->qr->notifyUsers('Administrator,Accounts,General Manager',array('Administrator'=>$this->qr->getSiteName($request->req_siteId).': Purchases made on '.$_POST['tDate'].' for material payment '.$request->req_no.' recorded by '.$this->qr->ud->user_name,'Accounts'=>$this->qr->getSiteName($request->req_siteId).': Purchases made on '.$_POST['tDate'].' for material payment '.$request->req_no.' recorded by '.$this->qr->ud->user_name,'General Manager'=>$this->qr->getSiteName($request->req_siteId).': Purchases made on '.$_POST['tDate'].' for material payment '.$request->req_no.' recorded by '.$this->qr->ud->user_name,'Subject'=>$this->qr->getSiteName($request->req_siteId).': Item Purchase'),12,0,false,false);

				$this->generateContent(true,'Refresh','',System::successText('Items submited successfully'));

				break;

				

			case 10:

				$data=json_decode($_POST['listData']);

				
				$id=0;

				for($i=0;$i<count($data);$i++){

				  $id=$this->qr->addEquipment($data[$i][2],$data[$i][1],$data[$i][3]);

					if(isset($_POST['siteId']))
						$this->qr->changeELocation($id,$_POST['siteId']);

				}

				$this->generateContent(true,'Refresh','',System::successText('Equipment Added Successfully'));

				break;

				

			case 11:

				

				break;

				

			case 12:

				$this->qr->saveVPPrices(json_decode($_POST['listData']));

				$this->generateContent(true,'Retain','',System::successText('Prices submitted successfully.'));

				break;

			case 13:

				$dets=$this->qr->saveOR(json_decode($_POST['listData']),$_POST['tDate']);

				if($dets->name){

					$this->qr->notifyUsers('Director',array('Director'=>$this->qr->ud->user_name.' has posted  office requisition for approval.','Subject'=>' Office Requisition'),22);

				    $this->generateContent(true,'Refresh','',$dets->value);

				}else{

					$this->generateContent(false,'Refresh','',$dets->value);

				}

				break;

				

			case 14:

				$stat=$this->qr->saveIncome(json_decode($_POST['listData']),$_POST['siteId'],$_POST['tDate']);

				if($stat->name){

				  $this->generateContent(true,'Refresh','',$stat->value);

				}else{

				  $this->generateContent(false,'Refresh','',$stat->value);

				}

				break;

				

			case 15:

				$stat=$this->qr->savePettyCash($_POST['listData'],$_POST['siteId'],$_POST['tDate']);

				

				if($stat->name){

				  $this->generateContent(true,'Refresh','',$stat->value);

				}else{

				  $this->generateContent(false,'Refresh','',$stat->value);

				}

				

				break;

				

			case 16:
				
				 $stat=$this->qr->addIssuedItems(json_decode($_POST['listData']),$_POST['siteId'],$_POST['str'],$_POST['type']);
				
				 if($stat->name){

				    $this->generateContent(true,'Refresh','',$stat->value,'issueMess');

				 }else{

					$this->generateContent(false,'','',$stat->value,'issueMess'); 

				 }

				break;
			
            case 17:
			    $sm= System::shared("assist");
				
				$sm->equipmentArray($_POST['listData'],$_POST['siteId'],$this->qr->ud->user_id,$this->qr->ud->user_name,$_POST['sRc']);
				
				$this->generateContent(true,$sm->viewEquipRequests(),$sm->viewEquipRequests(),"",'issueMess'); 
				
			break;

		}

			

		break;

				

		case 8:

		 switch($_POST['sop']){

			 case 1:

			  $status=$this->qr->createUser();

		      $this->generateContent($status->name,$this->qr->usersList(),'',$status->value);

			 break;

				 

			 case 2:

			 $status=$this->qr->createPro();

			 $this->generateContent($status->name,'','',$status->value);

			 break;

				 

			 case 3:

			 $status=$this->qr->saveSite($_POST['siteName'],$_POST['project'],$_POST['clerk'],$_POST['location']);

			 $this->generateContent($status->name,'','','',$status->value);

			 break;

				 

			 case 4:

				 $status=$this->qr->changePassword($_POST['oPass'],$_POST['newPass']);

				 $this->generateContent($status->name,'','',$status->value);

			break;

				 

			 case 5:

			     $status=$this->qr->createNewCompany($_POST['companyName'],$_POST['prefix']);

				 $this->generateContent($status->name,'','',$status->value);

			 break;

				 

			 case 6:

				 $results=$this->qr->createCompanyUser();

				 $this->generateContent($results->name,'','',$results->value,'#userC_'.$_POST['companyId']);

			 break;

				 

			 case 7:

				 $this->qr->addWorkSchedule(System::getArrayElementValue($_POST,'title'),System::getArrayElementValue($_POST,'fromDate'),System::getArrayElementValue($_POST,'toDate'),$_POST['pid']);

				 $this->generateContent(true,$this->qr->listComponents($_POST['pid']),'',System::successText('Component saved successfully','',''));

			 break;

				 

			 case 8:

				 $this->qr->addMaterialEst($_POST['description'],$_POST['qty'],$_POST['unit'],$_POST['cid']);

				 $this->generateContent(true,$this->qr->listEstMaterial($_POST['cid']),'',System::successText("Saved"),'#addCItem_'.$_POST['cid'],$_POST['cid']);

			 break;

		 }

	    break;

		

		case 9:

		 switch($_POST['stype']){

			 case 'qs_clerk':

				 $this->generateContent(true,$this->qr->showClerkList("where user_type=-2 and user like '%".System::getArrayElementValue($_POST,'srch')."%'"),'','');

				 break;

				 

			 case 'st_clerk':

				 $this->generateContent(true,$this->qr->showClerkList("where user_type=-2 and user like '%".System::getArrayElementValue($_POST,'srch')."%'"),'','');

				 break;

				 

			 case 'proManager':

				 $this->generateContent(true,$this->qr->showClerkList("where user_type=0 and user like '%".System::getArrayElementValue($_POST,'srch')."%'"),'','');

				 break;

			 case 'ld_mt':

				 $this->generateContent(true,$this->qr->formatForSearch(array(array(1,"Wekesa"))));

				 break;

			 case 'site':

				 $this->generateContent(true,$this->qr->findSite(""));

		 }

		break;

				

		case 10:

		$this->generateContent(true,$this->qr->loadLabour());		

		break;

				

		case 11:

		 switch($_POST['qOption']){

             case 'invType':case 'siteSel':case 'vtl':

                 $st=System::shared("assist");
                 
              	 $this->generateContent(true,$this->qr->showInventory($_POST['siteId'],$_POST['invT']),$st->showInventory($_POST['siteId'],$_POST['invT']));

				 break;

				 

			 case 'mat_pay': case 'trDate':

				 $added="";

				 if(isset($_POST['map'])){

				   //$this->qr->inventoryList('where site_id='.$_POST['siteId'].' and group_id='.$_POST['map'].' and item_type='.$_POST['invT'])
				  

				   $this->generateContent(true,$this->qr->listReceived($_POST['map']));

				 }else{

				   $this->generateContent(true,$this->qr->inventoryList('where site_id='.$_POST['siteId'].' and item_type='.$_POST['invT']." and date(entry_date)=date(".$this->qr->QLib->dateFormatForDb($_POST['btnVal']).")",$_POST['siteId'],$_POST['btnVal'])); 

				 }

				 break;

				 

			 case 'rm':

                
				 $this->generateContent(true,'<div class="q_row">'.$this->qr->blankMp($_POST['reqId']).'</div>');

				 break;
             case 'tem':
                 $as=System::shared("assist");
                 
                 $this->generateContent(true,$as->blanlkReq($_POST['reqId']));
                 
                 break;

		 }		

				

		break;

		

		case 12:
				
			switch(explode('_',$_GET['si'])[0]){

				case 'pdfUp':
					

			     $this->qr->uploadMpFile($_GET['fid']);

				 $path_part=pathinfo($_FILES['nFile_'.$_GET['fid']]['name']);

				 if($path_part['extension']=='pdf'){

			       $this->generateContent(true,$this->qr->showPdf($_GET['fid'],'mpPdf'));

				 }else{

					$this->generateContent(true,$this->qr->showImage($_GET['fid'],'mpImage')); 

				 }

				break;

			    case 'llpdf':

				 $dt=explode('-',$_GET['fid'])[1].'-'.time();

				 $path_part=pathinfo($_FILES['nFile_'.$_GET['fid']]['name']);

                 

				 $dt=$this->qr->saveLabourData(explode('-',$_GET['fid'])[1],explode('-',$_GET['fid'])[0],$dt,0,0,explode('-',$_GET['fid'])[2],$path_part['extension']);

				 $this->qr->uploadLabourFile($_GET['fid'],$dt);	

				 if($path_part['extension']=='pdf'){

				  $this->generateContent(true,$this->qr->showPdf($dt,'llpdf'),'Prepend','+ Add Another File');

				 }else{

				   $this->generateContent(true,$this->qr->showImage($dt,'llimage'),'Prepend','+ Add Another File');

				 }

				 //$this->generateContent(true,$this->qr->showImage($dt,'scnned'));

				break;

				

				case 'fmUp':

					$typ=0;

					$pth=pathinfo($_FILES['nFile_fr']['name']);

					if($pth['extension']=="pdf"){

						$typ=1;

					}

					$id=$this->qr->saveFMPayment($typ,System::getArrayElementValue($_POST,'nFileDesc',''));

					$this->qr->saveFmFile('nFile_fr',$id);

					if($typ==1){

					  $this->generateContent(true,$this->qr->showPdf($id,'fmPdf'),'Done','Done');

					}else{

					  $this->generateContent(true,$this->qr->showImage($id,'fm'),'Done','Done');

					}

					break;
					
					
				case 'copass':
					$this->qr->saveComponentImage('nFile_copI');
					
					$this->generateContent(true,'','Prepend','+ Add Another File');
					break;
                case 'plain':


                    if($_FILES['component']['type'] !=''){
                        $type=explode("/",$_FILES['component']['type'])[1];
                    }else{
                        $type=explode(".",$_FILES['component']['name'])[1];
                    }


                    $as=System::shared('assist');

                    $images=array('jpg','jpeg','png','pdf','tiff','jiff','bmp');

                    if(in_array(strtolower($type),$images)){
                        $this->generateContent(true, $as->handleTaskFileUpload('component'));
                    }else{
                        $this->generateContent(true,new name_value('failed','The image type is not Supported'));
                    }

                    break;

			}

			break;



				

		case 13:

				$this->generateContent(true,$this->qr->deleteItem($_POST['iType']));

		break;

				

		case 14:

				$this->generateContent(true,$this->qr->showPurchaseDetails($_POST['srch'],$_POST['rid'],$_POST['zrate'],$_POST['rqty']));

		break;		

			

		case 15:

				$this->generateContent(true,$this->qr->showReceivedDetails($_POST['srch'],$_POST['rid'],$_POST['zrate'],$_POST['rqty'],$_POST['lid']));

		break;

		

		case 16://Manage equipment

				$this->generateContent(true,$this->qr->manageEquipment($_POST['eid']));

			break;

				

		case 19:

				switch(explode('_',$_POST['butId'])[0]){

					case 'be':

				     $this->qr->changeELocation($_POST['equip'],$_POST['site']);

				     $this->generateContent(true,System::successText('Equipment Assigned Successfully'));

					 break;

						

					case 'rq':

						$this->qr->notifyUsers('Administrator',array('Administrator'=>'Equipment transfer request by '.$this->qr->ud->user_name.'. from '.$this->qr->getSiteName($_POST['site']),'Subject'=>$this->qr->getSiteName($_POST['site']).": Equipment Transfer"),11);

						$this->qr->saveTransferRequest($_POST['site'],$_POST['equip']);

						$this->generateContent(true,System::successText('Transfer Request Sent!'));

					break;

				}

			break;

		case 20:

				$this->qr->processTransfer($_POST['rid']);

				$this->generateContent(true,System::successText('Item Transfered Successfully'));

				break;

		case 17://load purchases

			switch(System::getArrayElementValue($_POST,'opId')){

			

				case 'selSite':

					$this->generateContent(true,'','',$this->qr->selectMP($_POST['sId']));

					break;

					case 'selSt':

					$this->generateContent(true,'','',$this->qr->selectMP($_POST['sId'],false,1));

					break;
					

				case 'selSiteR':

					$this->generateContent(true,'','',$this->qr->selectMP($_POST['sId'],true));

					break;

					

				case 'selMp':

					$this->generateContent(true,'','',$this->qr->listPurchases($_POST['sId']));

					break;

					

				case 'selMpR':

				    $this->generateContent(true,'','',$this->qr->purchaseList($_POST['sId']));

					break;

			}

			

			break;

				

		case 18://itemsearch

			$this->generateContent(true,$this->qr->searchInventory(System::getArrayElementValue($_POST,'theText','')),'');

			break;

				

		case 21:

			$this->generateContent(true,$this->qr->deleteMaterialPayment($_POST['rid'],$_POST['pid']));

			break;

				

		case 22:

			$this->generateContent(true,$this->qr->showUserPanel($_POST['userId']));	

			break;

				

		case 23:

			$data=$this->qr->processUserUpdates($_POST['but']);

			$this->generateContent(true,'','',$data);

			break;

				

		case 24:

			$where="";

		    if($_POST['pid']!=-1){

				$where=" and project_id=".$_POST['pid'];

			}

			if($_POST['typ']!=3){

				$levs=$this->qr->getALevels("order by thelevel asc");
				
				if($_POST['typ']==1)
				$where.=" and level".(count($levs)-1)."=0";

				if($_POST['typ']==2)
				   $where.=" and level".(count($levs)-1)."=1";

			}
            //echo $where;
				
			$this->generateContent(true,$this->qr->reqList($where));

			break; 

				

		case 25:

			//'5_2','6_4'

			//'5_2_19','0_10_9','12_10_12','0_7_20'

			$this->generateContent(true,$this->qr->getMyNotifications(),$this->qr->getUserAlertTotals());

			break;

    	case 26:

			$this->generateContent(true,$this->qr->listProjectMp($_POST['pid']));

			break;

			

		case 27:

		    $sm=System::shared('assist');

			$fmMp=$this->qr->fmRequestPanel();

			$sites=$this->qr->getSites("where id=".$_POST['siteId']);

			if(count($sites)>0){

			 for($x=0;$x<count($sites);$x++){

			  $this->generateContent(true,$this->qr->getDeletedMp($sites[$x]->site_id,$sites[$x]->site_project).$fmMp,$sm->componentSelections($_POST['siteId']));

			  }

			}else{

			   $this->generateContent(true,"");

			}

			break;

		

		case 28:

			$res=$this->qr->setLabourResetRequest($_POST['theSite'],$_POST['theDate'],$_POST['lType']);

			if($res->name){

				$this->qr->notifyUsers('Secretary',array('Secretary'=>$this->qr->ud->user_name.' from '.$this->qr->getSiteName($_POST['theSite']).' has requested for labour data reset on an entry made on '.$_POST['theDate']),10);

				$this->generateContent(true,$res->value);

			}else{

				$this->generateContent(false,$res->value);

			}

			break;

				

		case 29:

			

			switch(explode('_',System::getArrayElementValue($_POST,'req'))[0]){

				case 'appRes':

					$this->qr->approveLabourRest(explode('_',System::getArrayElementValue($_POST,'req'))[2]);

				break;

					

				case 'decRes':

					$this->qr->declineLabourRequest(explode('_',System::getArrayElementValue($_POST,'req'))[2]);

				break;

			}

				

			$this->generateContent(true,System::successText('Done'));

			break;	

				

		case 30:

			if($_POST['prevOp']=="print"){

			  $this->qr->savePrintPreview($_POST['colStyle'],$_POST['colN'],$_POST['listData'],$_POST['sData'],$_POST['mstyl']);

			  $this->generateContent(true,$this->qr->showpreview($_POST['btnId']));	

			}else{

			  $this->qr->processExcelFilePrev($_POST['colN'],$_POST['listData'],$_POST['sData']);

			  $this->generateContent(true,$this->qr->excelDownloadPreview());

			}

			break;

				

		case 31:

			$this->qr->deleteReceivedItems(explode('#',$_POST['pid'])[1]);

			$this->generateContent(true,"Deleted","",$this->qr->getTotalReceived(explode('#',$_POST['pid'])[2],explode('_',$_POST['pid'])[0],explode('#',$_POST['pid'])[3]));	

			break;

				

		case 32:

			$this->generateContent(true,$this->qr->loadFPanel($_POST['theOp']));

			break;	

				

		case 34:
           // print_r($_POST);
			$this->generateContent(true,$this->qr->listItemsReceived($_POST['theOp'],$_POST['frmDate'],$_POST['toDate']));

			break;

				

		case 35:

			$this->generateContent(true,$this->qr->listItemsPurchased($_POST['theOp'],$_POST['frmDate'],$_POST['toDate']));

			break;

				

		case 37:

		
			$this->generateContent(true,$this->qr->equipmentList($_POST['siteId']));

			break;

				

		case 36:

			$this->generateContent(true,$this->qr->listLocalPurchases($_POST['site'],$_POST['fromDate'],$_POST['toDate']));	

			break;

			

		case 38:

			$this->generateContent(true,$this->qr->importerOnly());	

			break;

				

		case 39:

		$this->generateContent(true,$this->qr->loadLabour($_POST['copy']));		

		break;

				

		case 40:

		$this->generateContent(true,$this->qr->loadMessages());		

		break;

				

		case 41:

		    $status=$this->qr->updateEmailPhone($_POST['email'],$_POST['phone']);

			if($status->name){

				$this->generateContent(true,$status->value,$status->other);

			}else{

				$this->generateContent(false,$status->value);

			}

			break;

				

		case 42:

			  $this->qr->setNotificationType($_POST['email'],$_POST['sms'],$_POST['userId']);

			  $this->generateContent(true,System::successText('Notification settings updated successfully.'));

			break;

				

		case 43:

			  $this->qr->saveEmailSettings($_POST['port'],$_POST['host'],$_POST['email'],$_POST['password']);

			  $this->generateContent(true,System::successText('Notification settings updated successfully.'));

			  break;

				

		case 44:

			  $this->generateContent(true,$this->qr->sendTestEmail());

			 break;

				

		case 45:

			  $this->generateContent(true,$this->qr->processListDetails());

			 break;

				

		case 46:

			  $this->generateContent(true,$this->qr->deleteFmRequest($_POST['delId']));

			 break;

				

		case 47:

			  $this->generateContent(true,$this->qr->foremanRequests(' and  site_id='.System::getArrayElementValue($_POST,'siteId',0),true));				

			  break;

				

		case 48:

			  if($this->qr->getFmFileType($_POST['rId'])==1){

				$this->generateContent(true,$this->qr->showPdf($_POST['rId'],'fmPdf'));

			  }else{

			    $this->generateContent(true,$this->qr->showImage($_POST['rId'],'fm',0,false,'bLess'));

			  }

			  break;

				

		case 49:

			  $this->qr->linkFmToRequest($_POST['fmrid'],$_POST['reqId']);

			  $rz=$this->qr->getRDet($_POST['reqId']);

			  $this->generateContent(true,'View Attached');

			  $this->qr->notifyUsers('General Manager,Administrator',array('General Manager'=>$this->qr->getSiteName($rz->req_siteId).'\'s Material Payment '.$rz->req_no.' from '.$this->qr->ud->user_name.' is on queue awaiting approval.','Administrator'=>$this->qr->getSiteName($rz->req_siteId).'\s Material Payment '.$rz->req_no.' from '.$this->qr->ud->user_name.' is now on queue.','Subject'=>$this->qr->getSiteName($rz->req_siteId).'\s Material Payment '.$rz->req_no),4,0,true,true,$rz->req_projectId);

			  break;

				

		case 50:

			  $this->generateContent(true,$this->qr->loadTransferItems($_POST['rid'])); 

			  break;

				

		case 51:

			  $this->qr->createSubRequisition($_POST['reqId']);

			  $this->generateContent(true,'');

			  break;

		

		case 52:

			  $this->qr->sendTestSms();

			  $this->generateContent(true,'Message sent successfully');

			  break;

				

		case 53://verify list

			 $this->generateContent(true,'<div class="listWrap" style="float:left;width:100%;"></div>',$this->qr->verifyPanel($_POST['siteId']));

			 break;

		case 54:

			 $this->generateContent(true,$this->qr->loadVerficationList($_POST['mpId']));

			 break;

				

		case 55:

			 $this->qr->updateProject($_POST['prid'],$_POST['pTitle'],$_POST['sDate'],$_POST['eDate'],$_POST['location'],$_POST['desc']);

			 $this->generateContent(true,System::successText("Project updated successfully"));

			 break;

				

		case 56:

			 $this->generateContent(true,$this->qr->loadProjectEditor($_POST['pid']));

			 break;

				

		case 57:

			 $this->generateContent(true,$this->qr->getProjectListOnly());

			 break;

		

		case 58:

			 $stat=$this->qr->approveRequest($_POST['pid']);

			 if($stat->name){

			   $this->generateContent(true,$stat->value);

			 }else{

			   $this->generateContent(true,$stat->value);

			 }

			 break;

				

		case 59:

			 $status=array(1=>0,2=>1);

			 $this->generateContent(true,$this->qr->showExpenseList('where status='.$status[$_POST['utype']]));

			 break;

				

		case 60:

			 $this->generateContent(true,$this->qr->loadIncomeContent($_POST['theBtn']));

			 break;

				

		case 61:

			 $this->generateContent(true,$this->qr->incomeRecord($_POST['fromDate'],$_POST['toDate'],$_POST['siteId']));

			 break;

				

		case 62:

			 $this->generateContent(true,$this->qr->showPettycashOptions($_POST['theBtn']));

			 break;

				

		case 63:

			 $this->generateContent(true,$this->qr->pettyCashList($_POST['pFromDate'],$_POST['pToDate'],$_POST['theSite']));

			 break;

			

		case 64:

			 if($_POST['stat']==1){

			   $this->qr->enableUserAlertType($_POST['alertType']);

			 }else{

			   $this->qr->disableUserAlertType($_POST['alertType']);

			 }

			 $this->generateContent(true,'Done');

			 break;

				

		case 66:

			 $this->qr->addPosition($_POST['pos_title']);

			 $this->generateContent(true,$this->qr->showPositionList());

			 break;

				

		case 67:

			 $this->generateContent(true,$this->qr->friList($_POST['searchF']));

			 break;

				

		case 68://addRemovePrevileges

			 $this->generateContent(true,$this->qr->addRemovePrivileges($_POST['pType'],$_POST['pStatus'],$_POST['pos']));

			 break;

		

		case 69:
           // print_r($_POST);
			 $stat=$this->qr->addALevel($_POST['title'],$_POST['tLevel']);

			 if($stat->name){

			   $this->generateContent(true,$this->qr->approvalLevelsList());

			 }else{

			   $this->generateContent(false,$stat->value);

			 }

			 break;

				

		case 70://addRemovePrevileges

			 $this->generateContent(true,$this->qr->addRemovePrivileges2($_POST['pType'],$_POST['pStatus'],$_POST['pos']));

			 break;

				

		case 71://Edit work schedule

			 

			 //$this->qr->assignApprovalLevel(System::getArrayElementValue($_POST,'app'),System::getArrayElementValue($_POST,'stat'));

			 $this->generateContent(true,$this->qr->showEditSchedule(System::getArrayElementValue($_POST,'wkshId')));

			 break;

				

					

		case 72:

			 $this->generateContent(true,$this->qr->showComponent($_POST['pid']));

		     break;

				

				

		case 73:

				

			 $this->qr->updateWorkSchedule($_POST['title'],$_POST['fromDate'],$_POST['toDate'],$_POST['tid']);

			 $this->generateContent(true,$this->qr->listComponents($_POST['pid']),'',System::successText('Component Updated Successfully'));

		     break;	

				

		case 74:

			 $this->qr->assignApprovalLevel(System::getArrayElementValue($_POST,'app'),System::getArrayElementValue($_POST,'stat'));	

		     break;
				
		case 75:
			 $this->generateContent(true,$this->qr->componentImageUpLoader(System::getArrayElementValue($_POST,'compId')));
			 break;
				
		
        case 76:
		    $asit=new am_assist();
            echo   $asit->req_data_insertion(System::getArrayElementValue($_POST,'lid'),System::getArrayElementValue($_POST,'cont'));
		     
			 break;
		case 77:

		        $asit=System::shared("assist");
				
				$asit->get_table_content($_POST['const']);
				
            if(System::getArrayElementValue($_POST,'const')==2){

                if(!$this->qr->positionHasPrivilege($this->qr->ud->user_userType,70)){

				   $this->generateContent(true,$this->qr->showProjectMps(),$this->qr->reqMenu());

                   $this->qr->notifyUsers('Administrator,Accounts,General Manager',array('Administrator'=>$this->qr->getSiteName($_POST['sId']).':  '.$_POST['tDate'].' Requisition  made by '.$this->qr->ud->user_name,'Accounts'=>$this->qr->getSiteName($_POST['sId']).':  material requisition  recorded by '.$this->qr->ud->user_name,'General Manager'=>$this->qr->getSiteName($_POST['sId']).': '.$_POST['tDate'].' Requisition  recorded by '.$this->qr->ud->user_name,'Subject'=>$this->qr->getSiteName($_POST['sId']).': Requisition'),4,0,false,false);

                }else{

                   $this->generateContent(true,$this->qr->loadMyRequisitions(),$this->qr->reqMenu());

                }
                
            }

		break;

			case 80:
				$ref=System::shared("refined");
				
				$this->generateContent(true,$ref->shcDelete($_POST['id']));
				
				break;	
			case 81:
				$update=System::shared("assist");
				
				$this->generateContent(true,$update->scheduleOfWork(System::getArrayElementValue($_POST,'Vname'),
				System::getArrayElementValue($_POST,'prj') ),$update->schMenus(System::getArrayElementValue($_POST,'Vname'),System::getArrayElementValue($_POST,'prj')));
				break;
			case 82:
				$update=System::shared("assist");
				
				$this->generateContent(true,$update->processTaskOfWork($_POST['cs']));
				
				break;
			case 83:
				
				$update=System::shared("assist");
				
				$this->generateContent(true,$update->updatedTabletWorkSpace(System::getArrayElementValue($_POST,'jm')));
				break;
			case 84:
				$update=System::shared("assist");
			  
				
				$this->generateContent(true,$update->updatedMaterialTableSpace(System::getArrayElementValue($_POST,'def'),System::getArrayElementValue($_POST,'cdef')));
				break;
			case 85:
				$update=System::shared("assist");
				
				$this->generateContent(true,$update->updatedEquipmentLayout($_POST['cs'],$_POST['id']),$this->qr->equipmentTransfers());
				
				break;
			case 86:
			
				$this->generateContent(true,$this->qr->showInventory($_POST['pj'],3," ",$_POST['st']));
				break;
			case 87:
				$as=System::shared("assist");
				
				$this->generateContent(true,$as->companyAjaxHandler($_POST['bt']));
				
				break;
			case 88:
				$as=System::shared("assist");
				
				$this->generateContent(true,$as->updatedTableFunction($_POST['cs']));
				
				break;
            case 89:
                $as=System::shared("assist");

                $this->generateContent(true,$as->updateScheduleEstimates($_POST['cs']));

                break;
            case 90:
                $as=System::shared("assist");
                $this->generateContent(true,$as->materialManagments($_POST['cs']));
                break;
            case 91:

                $as=System::shared("assist");

                $this->generateContent(true,$as->updatedProjectLevelFunction($_POST['cs']));

                break;

      }

    }

    public function generateContent($status=false,$content="",$topMenu="",$message="",$divName="",$divSuf=""){

        

        if($status){

            

            echo json_encode(array("Status"=>"Success","Content"=>$content,"TopMenu"=>$topMenu,"Message"=>$message,"DivName"=>$divName,'DivSufix'=>$divSuf));

        

        }else{

        

            echo json_encode(array("Status"=>"Failed","Content"=>$content,"TopMenu"=>$topMenu,"Message"=>$message,"DivName"=>$divName,'DivSufix'=>$divSuf));

            

        }

        

    }

}

?>