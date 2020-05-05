<?php
class HQuince{
    public $QLib;
    private $um;
    public $ud;
    public $mailer;
    public $sms;
    public function __construct(){
        $this->QLib=System::shared("proman_lib");
        $this->um=System::shared('usermanager');
        $this->QLib->getUserDetails($this->ud);
        $this->mailer=System::shared('sharedmailer');
        $this->sms=System::shared('smstool');
    }
    public function launcher(){

        $cont=new objectString;

        $cont->generalTags('<div class="fadeDiv"></div>');

        $cont->generalTags('<div class="conBox"></div>');

        $cont->generalTags('<div class="fadeDiv2"></div>');

        $cont->generalTags('<div class="conBox2"></div>');

        $cont->generalTags('<div class="fadeDiv3"></div>');

        $cont->generalTags('<div class="conBox3"></div>');

        $cont->generalTags($this->QLib->quinceMenu());

        $cont->generalTags($this->mainContainer());

        return $cont->toString();

    }
    public function mainContainer(){

        $cont=new objectString;

        $cont->generalTags('<div class="quince_content">');

        $cont->generalTags("<input type=\"hidden\" id=\"main_url\" value=\"".System::ajaxUrl(OPTION_MACRO,"macro_quince")."\" />");

        $cont->generalTags('<div class="quince_bread_wrap"><div class="am_upgrade fas fa-bars" ></div><div  class="s_i1" id="sec_image"></div><div style="float:left;overflow:hidden;"><div id="quince_title">Dashboard</div></div><div class="menu_loader"></div></div>');

        $cont->generalTags('<div class="dashWrap">');

        $cont->generalTags('<div id="quince_inner_content">');

        $cont->generalTags($this->dashboardContent());

        $cont->generalTags('</div>');

        $cont->generalTags('</div>');

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function dashboardContent(){

        $cont=new objectString;

        $cont->generalTags('<div style="float:left;width:97%;margin:1%;px;margin-top:20px;">');

        $cont->generalTags('<div class="divStart"></div>');

        $cont->generalTags($this->dashDetails($this->ud->user_userType));
        //$cont->generalTags($this->dashTabs());

        $cont->generalTags('</div>');

        //$cont->generalTags('<div class="detSide">');

        /*$cont->generalTags('<div class="memo_title">Memos</div>');


        $cont->generalTags('<div class="memoCont">');

        $cont->generalTags('<div class="mesDiv"></div>');

        $cont->generalTags('</div>');
        */
        //$cont->generalTags('</div>');

        //$cont->generalTags('</div>');

        //$cont->generalTags('</div>');
        if($this->positionHasPrivilege($this->ud->user_userType,2)){
            $cont->generalTags('<div class="quince_bread_wrap" style="height:20px"><div class="in_tit">Active Projects</div></div>');

            $cont->generalTags('<div  class="qlist_v" style="width:98%;float:left;margin:0px 1%;">');

            $cont->generalTags($this->displayGeneralView());

            $cont->generalTags('</div>');

        }

        return $cont->toString();

    }
    public function dashDetails($userType){

        $cont=new objectString;

        if($this->QLib->positionHasPrivilege($userType,70)&($this->ud->user_id !=1) &($this->ud->user_id !=2)){
            $cont->generalTags('<div class="innerTitle">Project Details</div>');

            $pr=$this->QLib->getMyCurrentProject();

            if($pr!=null){
                $cont->generalTags('<div class="proTile">');

                $cont->generalTags('<div class="pT">'.$pr->project_name.'</div>');

                $us=$this->um->getUsers('where id='.$pr->project_manager);

                for($i=0;$i<count($us);$i++)
                    $cont->generalTags('<div class="trw"><div class="tlabel">Cons. Manager</div><div class="txtDiv">'.$us[$i]->user_name.'</div></div>');

                $cont->generalTags('<div class="trw"><div class="tlabel">Start Date</div><div class="txtDiv"> '.$pr->project_start.'</div></div>');

                $cont->generalTags('<div class="trw"><div class="tlabel">Est. End Date</div><div class="txtDiv">'.$pr->project_end.'</div></div>');

                $cont->generalTags('<div class="trw"><div class="tlabel">Location</div><div class="txtDiv">'.$pr->project_location.'</div></div>');

                $cont->generalTags('</div>');

            }else{

                $cont->generalTags('<div class="nFound">No Project Assinged!</div>');

            }

        }else{
            //print_r($this->ud->user_id);
            /*case 2:case 1:
				$cont->generalTags($this->actionTabs(array(new name_value('','Requisitions','4'),new name_value('','Inventory','5'),new name_value('','Labour','10'))));
				//,new name_value('','Equipment','11')
			break;

			case 0:
				$cont->generalTags($this->actionTabs(array(new name_value('','Requisitions','4'),new name_value('','Inventory','5'),new name_value('','Labour','10'),new name_value('','Assign Project','16'))));
				break;

			case -4:
				$cont->generalTags($this->actionTabs(array(new name_value('','Requisitions','4'),new name_value('','Inventory','5'),new name_value('','Labour','10'))));
				break;
			/*case -3:
				$st=$this->QLib->getMyActiveSite();
				if($st!=null){
				 $cont->generalTags($this->actionTabs(array(new name_value('','Requisitions','4'))));
				}else{
				 $cont->generalTags('<div class="nFound">No Project Assigned!</div>');
				}
				break;*/
            /*case -5;
				$cont->generalTags($this->actionTabs(array(new name_value('','Requisitions','4'),new name_value('','Price Verifiaction','13'))));
				break;

			case 3:
				//new name_value('','Projects','2')new name_value('','Construction Sites','3')
				$cont->generalTags($this->actionTabs(array(new name_value('','Requisitions','4'),new name_value('','Inventory','5'),new name_value('','Labour','10'),new name_value('','Reports','7'),new name_value('','Price Verifiaction','13'))));
				break;

			case -7:case -8:case -9:case -10:
				$cont->generalTags($this->actionTabs(array(new name_value('','Requisitions','4'),new name_value('','Item Search','30'))));
				break;
				*/

            //,new name_value('','Construction Sites','3')
            $action_array=array();


            if($this->QLib->positionHasPrivilege($userType,2)|($this->ud->user_id==1)|($this->ud->user_id ==2))
                $action_array[]=new name_value('','Projects','2');

            if($this->QLib->positionHasPrivilege($userType,4)|($this->ud->user_id==1)|($this->ud->user_id ==2))
                $action_array[]=new name_value('','Requisitions','4');

            if($this->QLib->positionHasPrivilege($userType,5)|($this->ud->user_id==1)|($this->ud->user_id ==2))
                $action_array[]=new name_value('','Inventory','5');

            if($this->QLib->positionHasPrivilege($userType,10)|($this->ud->user_id==1)|($this->ud->user_id ==2))
                $action_array[]=new name_value('','Labour','10');

            if($this->QLib->positionHasPrivilege($userType,7)|($this->ud->user_id==1)|($this->ud->user_id ==2))
                $action_array[]=new name_value('','Reports','7');

            if($this->QLib->positionHasPrivilege($userType,2)|($this->ud->user_id==1)|($this->ud->user_id ==2))
                $action_array[]=new name_value('','Users & Privileges','8');

            /*if($this->QLib->positionHasPrivilege($userType,13)|($this->ud->user_id==1))
				   $action_array[]=new name_value('','Price Verification','13');
				  */
            if($this->QLib->positionHasPrivilege($userType,9)|($this->ud->user_id==1) |($this->ud->user_id ==2))
                $action_array[]=new name_value('','Settings','9');

            if($this->ud->user_id==1 |($this->ud->user_id ==2))
                $action_array[]=new name_value('','Company Master','100','comTab');
            // $action_array[]=new name_value(" ",'Table Refresh','104','detTab');
            $cont->generalTags($this->actionTabs($action_array));
            //,new name_value('','Equipment','11')

        }

        return $cont->toString();

    }
    public function actionTabs($acTab=array()){

        $cont=new objectString;

        for($i=0;$i<count($acTab);$i++){
            $cont->generalTags('<div class="acTab '.$acTab[$i]->other2.'" id="tb_'.$acTab[$i]->other.'" title="'.$acTab[$i]->value.'"><div class="at_icon" id="cal_'.$acTab[$i]->other.'"></div><div class="tab_icon" id="ct_'.$acTab[$i]->other.'"></div><div class="cTxt" id="utab_'.$acTab[$i]->other.'" >'.$acTab[$i]->value.'</div></div>');
        }
        return $cont->toString();

    }
    public function dashTabs(){

        $cont=new objectString;

        //$cont->generalTags('<div class="mainDashTab" id="dsh_1"></div>');

        $cont->generalTags('<div class="mainDashTab" id="dsh_2">View Account</div>');

        $cont->generalTags('<div class="mainDashTab" id="dsh_3">Manage Users</div>');

        $cont->generalTags('<div class="mainDashTab" id="dsh_4">Group Settings</div>');

        $cont->generalTags('<div class="mainDashTab" id="dsh_5">Write Memo</div>');

        return $cont->toString();
    }
    public function showProjectDetails(){
        $cont=new objectString;

        //$cont->

        return $cont->toString();

    }
    public function searchRItems($search=""){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row"><div class="innerTitle">Find Item</div></div>');

        $inp=new input;

        $inp->setClass('txtField');

        $inp->setId('txtRFind');

        $inp->input('text','srch');

        $cont->generalTags('<div class="q_row"><div id="label" style="font-size:16px;width:50px;">Search</div>'.$inp->toString().'</div>');

        $cont->generalTags('<div class="q_row" id="sPfind">'.$this->friList($search).'</div>');

        return $cont->toString();

    }
    public function friList($search=""){

        $lst=new open_table;

        $lst->setColumnNames(array("Date","Description","Qty","Unit","Req. No","Site","By"," "));

        $lst->setRightAlign(array(2));

        $lst->setColumnWidths(array('10%','30%','10%','5%','7%','13%','10%','10%'));

        $items=array();

        if($search!="")
            $items=$this->QLib->getSearchItems('and a.description like \'%'.$search.'%\'');

        for($i=0;$i<count($items);$i++)
            $lst->addItem(array($items[$i]->item_requestDate,$items[$i]->item_description,$items[$i]->item_qty,$items[$i]->item_unit,$items[$i]->item_rNumber,$this->getSiteName($items[$i]->item_siteId),$items[$i]->item_byName));

        $lst->showTable();

        return $lst->toString();

    }
    public function loadMyRequisitions($id=0){
        $site=$this->QLib->getMyActiveSite();
        if($site==null)
            return '<div class="q_row nFound">Site Not Assigned</div>';

        return $this->listProjectMp($site->site_project);
    }
    public function displayGeneralView(){

        $cont=new objectString;

        $list=new open_table;

        $list->setColumnNames(array("Date Posted","Title","Started","Est. End","Location"));

        $pr=$this->QLib->getProjects();

        $list->setColumnWidths(array("15%","30%","15%","15%","20%"));

        for($i=0;$i<count($pr);$i++){
            $list->addItem(array($pr[$i]->project_entryDate,$pr[$i]->project_name,$pr[$i]->project_start,$pr[$i]->project_end,$pr[$i]->project_location));
        }

        $list->setHeaderFontBold();

        $list->setSize("100%","100px");

        $list->showTable();

        $cont->generalTags('<div class="quince_main_view">'.$list->toString().'</div>');

        return $cont->toString();

    }
    public function menuTab($id,$value=""){

        $cont=new objectString;

        $cont->generalTags('<div class="men_tab">'.$value.'</div>');

        return $cont->toString();
    }
    public function displayProject($pid=0){

        //@under development
        $cont=new objectString;

        //$cont->generalTags('<div class="q_row" style="margin-top:20px;"><div class="innerTitle">Project Details</div><div class="men_tab2" id="inMen_1" style="float:right;">Back</div><div class="a3-right a3-padding a3-round a3-blue a3-margin-right a3-tabs" id="inMen_1" style="float:right;">Members</div></div>');
        $cont->generalTags('<div class="q_row" style="margin-top:20px;"><div class="innerTitle">Project Details</div><div class="men_tab2" id="inMen_1" style="float:right;">Back</div></div>');


        $pr=$this->QLib->getProjects('where id='.$pid);

        for($i=0;$i<count($pr);$i++){
            $cont->generalTags('<div class="q_row" id="pd_disp"><div class="PDWrap">');

            $usrs=$this->um->getUsers('where id='.$pr[$i]->project_manager);

            $usr=new fUser;

            for($s=0;$s<count($usrs);$s++)
                $usr=$usrs[$s];

            $cont->generalTags('<div id="form_row"><div class="label" style="width:100px"><b>Project Title:</b></div><div class="txtDivT">'.$pr[$i]->project_name.'</div><div class="label" style="width:120px"><b>Project Manager</b></div><div class="txtDivT">'.$usr->user_name.'</div><div class="label" style="width:120px"><b>Location</b></div><div class="txtDivT">'.$pr[$i]->project_location.'</div></div>');

            $cont->generalTags('<div id="form_row"><div class="label" style="width:100px"><b>Description:</b></div><div class="txtDivT">'.$pr[$i]->project_description.'</div><div class="label" style="width:120px"><b>Duration</b></div><div class="txtDivT">Start : '.$pr[$i]->project_start.' </br >Ends : '.$pr[$i]->project_end.'</div></div>');

            $cont->generalTags('</div>');

            $showB=new input;

            $showB->setClass('quince_select');

            $showB->addItems(array(new name_value('Bill Of Quantities',1),new name_value('Material',2),new name_value('Labour',1)));

            $showB->select('select');

            $cont->generalTags('<div id="form_row" style="margin-top:20px;margin-bottom:10px!important;"><div class="label" style="width:70px;font-size:14px;">BOQ</div><div class="qs_wrap">'.$showB->toString().'</div></div>');

            $cont->generalTags('<div style="float:left;width:100%;overflow:hidden;" class="pr_cont">');

            $saveBtn='<div class="saveFList" style="" id="sid_4_'.$pr[$i]->project_id.'">Save</div>';


            $itms=$this->QLib->getBOQItems('where project_id='.$pr[$i]->project_id);
            if(count($itms)==0)
                if($this->QLib->positionHasPrivilege($this->ud->user_userType,71)){
                    $cont->generalTags('<div id="form_row" style="margin:5px 0px!important;">'.$this->excelImporter(true,array(new name_value('Description','desc'),new name_value('Unit ','unit'),new name_value('Qty','qty'),new name_value('Rate','rate'),new name_value('Amount','amt')),$pr[$i]->project_id).$saveBtn.'</div>');
                }
            //$cont->generalTags('<div class="spd" title="Add item"></div><div class="nWrap" style="width:200px;border:1px solid #ddd;height:30px;float:left;border-radius:5px;"></div>');
            $cont->generalTags($this->addResultsBar());

            if(count($itms)>0){
                $find=new input;

                $find->setClass('txtField');

                $find->input('text','txtFilter');

                $cont->generalTags('<div id="form_row"><div class="label" style="width:70px">Filter</div>'.$find->toString().'</div>');

            }

            $cont->generalTags('<div id="form_row" style="margin-top:20px;"><div id="ReqList" >'.$this->billOfQty($itms).'</div></div>');


            $cont->generalTags('</div>');

            //$cont->generalTags('<div id="form_row" style="margin-top:0px;"><div class="addBtn">+</div></div>');

            $cont->generalTags('</div>');
        }
        return $cont->toString();

    }
    //----------------------------------VERIFICATION FUNCTION------------------------------------
    public function verification(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags('<div class="innerTitle">Price Verification</div>');

        $cont->generalTags('</div>');

        $sites=$this->QLib->getSites('where status=1');

        $ver=new input;

        $ver->setClass('quince_select');

        $ver->addItem(-1,'Select Site');

        $ver->addItems($this->QLib->getSiteArray('where status=1'));

        $ver->select('verify');

        $cont->generalTags('<div class="q_row"><div id="label">Sites</div><div class="qs_wrap" id="vfy">'.$ver->toString().'</div><div class="csite" style="margin-left:10px;float:left;overflow:hidden;"></div></div>');

        $cont->generalTags('<div class="q_row" id="verList"></div>');

        return $cont->toString();

    }
    public function showVerifications(){

        $cont=new objectString;

        $list=new open_table;

        $list->setColumnNames(array('No.','Site Name','Requisition','Status','Action',' '));

        $list->setColumnWidths(array('10%','15%','25%','10%','15%','10%'));

        $list->showTable();

        $cont->generalTags($list->toString());

        return $cont->toString();

    }
    public function verifyMatPayment(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row" style="margin-top:20px;margin-bottom:10px;"><div class="innerTitle">Verify Receipt Of Items On Site.</div></div>');

        $site=new input;

        $site->setClass('quince_select');

        $site->addItem(-1,'Select Site');

        $site->addItems($this->QLib->getSiteArray('where status=1'));

        $site->select('sites');

        $cont->generalTags('<div class="q_row"><div id="label">Site</div><div class="qs_wrap" id="vfy">'.$site->toString().'</div></div>');

        $cont->generalTags('<div class="q_row" id="verify_wrap"></div>');

        return $cont->toString();

    }
    public function verifyPanel($sid=0){

        $cont=new objectString;

        $levs=$this->QLib->getALevels("order by thelevel asc");

        $mat=$this->QLib->getRequisitions('where level'.count($levs).'=1 and site_id='.$sid);

        //$mat=$this->QLib->getRequisitions('where site_id='.$sid);

        $selMp=new input;

        $selMp->setClass('quince_select');

        $selMp->addItem(-1,'Select Requisition');

        for($i=0;$i<count($mat);$i++)
            $selMp->addItem($mat[$i]->req_id,'Requisition '.$mat[$i]->req_no);

        $selMp->select('selMp');

        $cont->generalTags('<div id="label">Select</div><div class="qs_wrap" id="vMp">'.$selMp->toString().'</div>');

        $list=new open_table;

        $list->setColumnNames(array('No'));

        return $cont->toString();

    }
    public function loadVerficationList($rid=0){

        $cont=new objectString;

        $req=$this->QLib->getRequisitions('where id='.$rid.'');

        $cont->generalTags($this->addResultsBar());

        $list=new open_table;

        $amount=0;

        for($i=0;$i<count($req);$i++){
            $save="";
            if($this->positionHasPrivilege($this->ud->user_userType,13))
                $save='<div id="sid_12" class="saveFList">Save</div>';

            $cont->generalTags('<div class="q_row"><div style="float:left;font-size:20px;margin-top:10px;">Requisition '.$req[$i]->req_no.'</div>'.$save.'</div>');




            $list->enablePrintExport(true,array('Price Verification','<b>SITE</b> : '.$this->getSiteName($req[$i]->req_siteId),'Requisition '.$req[$i]->req_no,'<b>Price Verified By</b>:'.$req[$i]->req_verifiedByName));
            if($this->positionHasPrivilege($this->ud->user_userType,13)){

                $list->setColumnNames(array('No','Item Description','Qty','Unit','Price','Amount','Remarks'));

                $list->setNumberColumns(array(4));

                $list->setRightAlign(array(4,5));

                $list->setColumnFormular(2,4,5);

                $list->setEditableColumns(array(4));

                $list->setColumnWidths(array('10%','38%','12%','8%','10%','10%','10%'));
            }else{

                $list->setRightAlign(array(2,3,4));

                $list->setColumnNames(array('No','Item Description','Qty','Unit','Quote Price','Verified Price'));

                $list->setColumnWidths(array('10%','32%','12%','8%','15%','15%','15%'));
            }
            $ri=$this->QLib->getRequestItems('where request_id='.$rid.' order by lno asc');
            $amount=0;
            for($i=0;$i<count($ri);$i++){
                if($this->positionHasPrivilege($this->ud->user_userType,13)){
                    $price=$this->QLib->getPvItemPrice($ri[$i]->item_id);
                    $total=$price*$ri[$i]->item_qty;
                    $amount+=$total;
                    if((trim($ri[$i]->item_description)=="")&($ri[$i]->item_qty<0)){}else{
                        $list->addItem(array($this->filterNegative($ri[$i]->item_no),$ri[$i]->item_description,$this->filterNegative(number_format($ri[$i]->item_qty,2)),$ri[$i]->item_unit,$this->filterNegative($price,$ri[$i]->item_no),$this->filterNegative(number_format($total,2),''),$ri[$i]->item_remarks),$ri[$i]->item_id);
                    }
                }else{

                    $price=$this->QLib->getPvItemPrice($ri[$i]->item_id);
                    $total=$ri[$i]->item_rate-$price;
                    $amount+=$total;

                    if((trim($ri[$i]->item_description)=="")&($ri[$i]->item_qty<0)){}else{
                        $list->addItem(array($this->filterNegative($ri[$i]->item_no),$ri[$i]->item_description,$this->filterNegative($ri[$i]->item_qty),$ri[$i]->item_unit,$this->filterNegative(number_format($ri[$i]->item_rate,2).' KES'),$this->filterNegative($price.' KES',$ri[$i]->item_no)),$ri[$i]->item_id);
                    }
                }
            }

            $list->showTable();

            $cont->generalTags($list->toString());

        }



        if($this->ud->user_userType==-5)
            $cont->generalTags('<div class="Rtotal">TOTAL '.number_format($amount,2).'</div>');

        //$cont->generalTags('<div class="addBtn">+</div>');

        return $cont->toString();
    }
    public function saveVPPrices($data){

        for($i=0;$i<count($data);$i++){
            $this->QLib->addVerifiedPrices($data[$i][count($data[$i])-1],$data[$i][3]);
        }
    }
    public function loadProjectEditor($id=0){

        $cont=new objectString;

        $pr=$this->QLib->getProjects('where id='.$id,true);

        $cont->generalTags($this->popTitle('Edit Project Details.'));

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags('<input type="hidden" id="proId" value="'.$id.'"/>');

        $cont->generalTags('<div class="mText" style="display:none;width:100%;float:left;"></div>');

        for($i=0;$i<count($pr);$i++){

            $title=new input;

            $title->setClass('txtField');

            $title->setId('ptitle');

            $title->input('text','proTitle',$pr[$i]->project_name);

            $cont->generalTags('<div class="q_row"><div id="label">Title</div>'.$title->toString().'</div>');

            $cont->generalTags('<div class="q_row"><div id="label">Description</div><textarea class="txtField" id="pDesc">'.$pr[$i]->project_description.'</textarea></div>');

            $sDate=new input;

            $sDate->setClass('quince_date');

            $sDate->setId('sdate');

            $sDate->input('text','start',$pr[$i]->project_start);

            $cont->generalTags('<div class="q_row"><div id="label">Starts</div>'.$sDate->toString().'</div>');

            $eDate=new input;

            $eDate->setClass('quince_date');

            $eDate->setId('edate');

            $eDate->input('text','end',$pr[$i]->project_end);

            $cont->generalTags('<div class="q_row"><div id="label">End</div>'.$eDate->toString().'</div>');

            $locat=new input;

            $locat->setClass('txtField');

            $locat->setId('elocation');

            $locat->input('text','proTitle',$pr[$i]->project_location);

            $cont->generalTags('<div class="q_row"><div id="label">Location</div>'.$locat->toString().'</div>');

            $submit=new input;

            $submit->setClass('form_button');

            $submit->setId('updatePro');

            $submit->input('button','updateUser','Update Project');

            $cont->generalTags('<div class="q_row">'.$submit->toString().'</div>');

        }

        $cont->generalTags('</div>');

        return $cont->toString();
    }
    public function updateProject($id,$title,$start,$end,$location,$desc=""){
        $this->QLib->updateProject($id,$title,$start,$end,$location,$desc);
    }
    private function popTitle($title=""){
        return '<div class="mesRow" style="color:#fff;background:#145075;border:none;font-size:16px;">'.$title.'</div>';
    }
    //--------------------------------------------END VERIFICATION FUCTIONS-------------------------------
    //--------------------------------------------Income & Expenses--------------------------------------
    public function incomeExpenses(){
        $cont=new objectString;

        $cont->generalTags('<div class="q_row" style="margin-top:10px;margin-bottom:10px;"><div class="innerTitle">General Expenses</div></div>');

        $type=new input;

        $this->QLib->changeAlertStatus($this->ud->user_id,22);

        $type->setClass('quince_select');

        $type->addItems(array(new name_value('Pending',1),new name_value('Approved',2)));

        $type->select('seltype');


        /*$fdate=new input;

		$fdate->setClass('quince_date');

		$fdate->input('text','frmDate',$this->QLib->getSystemDate());

		$tdate=new input;

		$tdate->setClass('quince_date');

		//$tdate->input('text','toDate',$this->QLib->getSystemDate());
		<div style="float:right;"><div id="label" style="font-size:16px;width:50px;">From</div>'.$fdate->toString().'<div id="label" style="font-size:16px;width:50px;margin-left:10px;">To</div>'.$tdate->toString().'</div>*/
        $cont->generalTags('<div class="q_row"><div id="label" style="font-size:16px;width:50px;">Find</div><div class="qs_wrap" id="oProc">'.$type->toString().'</div></div></div>');

        $cont->generalTags('<div class="oReq" style="width:100%;overflow:hidden;">'.$this->showExpenseList().'</div>');

        return $cont->toString();
    }
    public function showExpenseList($whereclause='where status=0'){

        $cont=new objectString;

        $list=new open_table;

        $list->setColumnNames(array('No.','Date','Description','Status','Generated By',' '));

        $list->setColumnWidths(array('5%','15%','35%','10%','15%','19%'));

        $req=$this->QLib->getOfficeRequests($whereclause);

        $status=array('Pending','Approved');

        for($i=0;$i<count($req);$i++){
            $list->addItem(array($i+1,$req[$i]->proc_date,'Office Requisition No. '.$req[$i]->proc_no,$status[$req[$i]->proc_status],$req[$i]->proc_byName,'<div class="vReq" id="rq_'.$req[$i]->proc_id.'"></div>'));

            //$list->addDataRow($this->expandDiv($req[$i]->proc_id,'Office Requisition No. '.$req[$i]->proc_no));
        }

        $list->showTable();

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags($list->toString());

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function newOfficeRequisition(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row" style="margin-top:10px;margin-bottom:10px;"><div class="innerTitle"><div class="offPro"></div>New Expense </div></div>');

        $cont->generalTags("<script>is_native=1;</script><input type='hidden' id='allow_edit' value='1'>");

        $cont->generalTags('<div class="q_row" style="margin:3px 1%;">'.$this->addResultsBar().'</div>');

        $sites=new input;

        $sites->setClass('quince_select');

        //$sites->setId('sSite');

        $sites->addItem(-1,"General Expenses");

        $tSites=$this->QLib->getSites('where status=1');

        for($i=0;$i<count($tSites);$i++){
            $sites->addItem($tSites[$i]->site_id,strtoupper($tSites[$i]->site_name));
        }

        $sites->select('sites');

        $date=new input;

        $date->setClass('quince_date');

        $date->setId('theDate');

        $date->input('text','theDate',$this->QLib->getSystemDate());

        $cont->generalTags('<div class="q_row"><div id="label" style="font-size:16px;width:50px;" >Date</div>'.$date->toString().'<div 
		id="label" style="font-size:16px;width:auto;margin-left:20px;margin-right:30px" >Account or Project Name </div>
		<div class="qs_wrap">'.$sites->toString().'</div><div class="saveFList" id="sid_13" style="margin-top:-5px;">Submit Expense</div>');

        $cont->generalTags("<div style='border:1px solid #bbb;float:left;padding:2.4px;border-radius:4px;overflow:hidden'></div></div>");

        $list=new open_table;

        //$list->canDeleteRow(true);

        $list->setRightAlign(array(2));

        $list->setNumberColumns(array(2));

        $list->calculateTotalOnly(2);

        $list->setColumnNames(array('No.','Description','Amount'));

        $list->setColumnWidths(array('10%','55%','28%'));

        $list->setEditableColumns(array(1,2));

        $list->showTable();

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags($list->toString());

        $cont->generalTags('</div>');

        //$cont->generalTags("<script>var hasFormular=true;var fCol2=2;var fCol1=2</script>");

        $cont->generalTags('<div class="q_row"><div class="addBtn">+</div></div>');

        $cont->generalTags('<div class="q_row"><div class="Rtotal">TOTAL</div></div>');

        return $cont->toString();

    }
    public function showORDetails($id){

        $cont=new objectString;

        $op=$this->QLib->getOfficeRequests('where id='.$id);

        $cont->generalTags('<div class="thePop"></div>');

        $appBtn="";

        for($x=0;$x<count($op);$x++){

            if($this->QLib->positionHasPrivilege($this->ud->user_userType,52)){
                if($op[$x]->proc_status==0)
                    $appBtn='<div id="or_'.$op[$x]->proc_id.'" class="app_button">Approve</div>';
            }

            $prinBtn='<div id="xprint_'.$op[$x]->proc_id.'" class="iprint">Print</div>';


            $cont->generalTags('<div class="q_row" style="margin-top:10px;margin-bottom:10px;"><div class="innerTitle"><div class="offPro"></div>Office Requisition No.'.$op[$x]->proc_no.'</div><div class="men_tab2" id="inMen_31" style="float:right;">Back</div>'.$prinBtn.''.$appBtn.'</div>');

            $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

            $cont->generalTags('<div class="q_row"><div id="label" style="font-size:16px;">Generated By:</div><div class="txtDivT" style="font-size:16px;">'.$op[$x]->proc_byName.'</div></div>');

            $cont->generalTags('<div class="q_row"><div id="label" style="font-size:16px;">Date:</div><div class="txtDivT" style="font-size:16px;">'.$op[$x]->proc_date.'</div></div>');

        }

        $cont->generalTags($this->showOpItems('where proc_id='.$id.''));

        return $cont->toString();

    }
    public function showOpItems($whereclause=""){

        $cont=new objectString;

        $list=new open_table;

        $list->setRightAlign(array(2));

        $list->setColumnNames(array('No.','Description','Amount'));

        $list->setColumnWidths(array('10%','68%','20%'));

        $ri=$this->QLib->getOpItems($whereclause);

        $amount=0;

        for($i=0;$i<count($ri);$i++){
            $amount+=$ri[$i]->op_amount;
            $list->addItem(array($i+1,$ri[$i]->op_itemDesc,number_format($ri[$i]->op_amount,2)));
        }

        $list->showTable();

        $cont->generalTags('<div class="q_row">'.$list->toString().'</div>');

        $cont->generalTags('<div class="Rtotal">TOTAL '.number_format($amount,2).'</div>');

        return $cont->toString();

    }
    public function recordIncome(){

        $cont=new objectString;

        $count=new objectString();


        $cnt=new input;

        $cnt->setClass('quince_select');

        $cnt->addItems(array(new name_value('Record Income',1),new name_value('View Income',2)));

        $cnt->select('select');

        $count->generalTags("<div id='label'>Select Action :</div><div class='qs_wrap'>".$cnt->toString()."</div>");

        $cont->generalTags('<input type="hidden" id="allow_edit">');

        $cont->generalTags('<div class="inco" style="width:100%;float:left;">');

        $cont->generalTags($this->incomeContent($count->toString()));

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function loadIncomeContent($type){
        if($type==2){
            return $this->viewIncomeRecords();
        }else{
            return $this->incomeContent();
        }

    }
    public function incomeContent($cnt=''){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

        $sites=new input;

        $sites->setClass('quince_select');

        $sites->addItem(-1,"Select Site");

        $sites->setId('sSite');

        $tSites=$this->QLib->getSites('where status=1');

        for($i=0;$i<count($tSites);$i++){
            $sites->addItem($tSites[$i]->site_id,strtoupper($tSites[$i]->site_name));
        }

        $sites->select('sites');

        $date=new input;

        $date->setId('theDate');

        $date->setClass('quince_date');

        $date->input('text','theDate',$this->QLib->getSystemDate());

        $cont->generalTags('<div class="q_row">'.$cnt.'<div id="label" style="font-size:16px;width:50px;" >Date</div>'.$date->toString().'<div id="label" style="font-size:16px;width:50px;margin-left:20px;" >Site</div><div class="qs_wrap">'.$sites->toString().'</div><div id="sid_14" class="saveFList" style="margin-top:-5px;">Submit Income</div></div>');

        $list=new open_table;

        $list->setRightAlign(array(3));

        $list->setNumberColumns(array(3));

        $list->calculateTotalOnly(3);


        $list->setColumnNames(array('No.','Invoice No','Description','Amount'));

        $list->setColumnWidths(array('5%','18%','50%','20%'));

        $list->setEditableColumns(array(1,2,3));

        $list->showTable();

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags($list->toString());

        $cont->generalTags('</div>');

        $cont->generalTags('<div class="q_row"><div class="addBtn">+</div></div>');

        $cont->generalTags('<div class="Rtotal">TOTAL 0.00</div>');


        return $cont->toString();

    }
    public function viewIncomeRecords(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

        $date=new input;

        $date->setClass('quince_date fbdFrm');

        $date->setId('theDate');

        $date->input('text','theDate',$this->QLib->getSystemDate());

        $tdate=new input;

        $tdate->setClass('quince_date tbdTo');

        $tdate->setId('theDate2');

        $tdate->input('text','theDate2',$this->QLib->getSystemDate());

        $site=new input;

        $site->setClass('quince_select');

        $site->addItem(-1,"All Sites");

        $sites=$this->getSites('where status=1');

        for($i=0;$i<count($sites);$i++){
            $site->addItem($sites[$i]->site_id,$sites[$i]->site_name);
        }

        $site->select('selSite');

        $cont->generalTags('<div class="q_row"><div id="label" style="width:40px;font-size:16px;">From:</div>'.$date->toString().'<div id="label" style="width:40px;font-size:16px;margin-left:10px;">To:</div>'.$tdate->toString().'<div id="label" style="width:40px;font-size:16px;margin-left:30px;">Site</div><div class="qs_wrap" id="inSite">'.$site->toString().'</div></div>');

        $cont->generalTags('<div class="q_row" id="vincome_list">');

        $cont->generalTags($this->incomeRecord($this->QLib->getSystemDate(),$this->QLib->getSystemDate()));

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function incomeRecord($fromDate="",$toDate="",$id=-1){

        $cont=new objectString;

        $list=new open_table;

        $whereclause="";
        //$list->canDeleteRow(true);

        if(($fromDate!="")&($toDate!="")){

            $whereclause='where date(income_date)>=date('.$this->QLib->dateFormatForDb($fromDate).') and date(income_date)<=date('.$this->QLib->dateFormatForDb($toDate).')';

            if($id!=-1){
                $whereclause.=" and site_id=".$id;
            }

        }
        $list->setRightAlign(array(5,6));

        $list->calculateTotalOnly(4);

        $inc=$this->QLib->getIncomeRecords($whereclause);

        $list->setColumnNames(array('No.','Date','Site','Invoice No','Description','Amount','By'));

        $list->setColumnWidths(array('5%','12%','12%','13%','25%','15%','13%'));

        $list->enablePrintExport(true,array('View Income',' ',' '));

        //$list->setEditableColumns(array(1,2,3));

        $amount=0;

        for($i=0;$i<count($inc);$i++){
            $amount+=$inc[$i]->inc_amount;
            $list->addItem(array($i+1,$inc[$i]->inc_date,$this->getSiteName($inc[$i]->inc_site),$inc[$i]->inc_invoice,$inc[$i]->inc_description,number_format($inc[$i]->inc_amount,2),$inc[$i]->inc_byname));
        }

        $list->addItem(array(' ',' ',' ',' ',' ','TOTAL '.number_format($amount,2).'',' '),0,array('','','','',' ','font-weight:bold'));

        $list->showTable();

        $cont->generalTags($list->toString());

        return $cont->toString();

    }
    public function saveIncome($listData,$siteId,$date){

        for($i=0;$i<count($listData);$i++){

            if($listData[$i][0]==""){
                return new name_value(false,System::getWarningText("Error on item number ".($i+1).".Please enter invoice number."));
            }

            if($listData[$i][1]==""){
                return new name_value(false,System::getWarningText("Error on item number ".($i+1).".Please enter description."));
            }

            if($listData[$i][2]==""){
                return new name_value(false,System::getWarningText("Error on item number ".($i+1).".Please enter amount."));
            }

        }

        for($i=0;$i<count($listData);$i++){
            $this->QLib->saveIncome($listData[$i][0],$listData[$i][1],$listData[$i][2],$siteId,$date);
        }

        return new name_value(true,System::successText('Income saved successfully.'));

    }
    public function recordPettyCash(){

        $cont=new objectString;

        $cnt=new input;

        $cnt->setClass('quince_select');

        $cnt->addItems(array(new name_value('Record Petty Cash',1),new name_value('View Petty Cash',2)));

        $cnt->select('pCash');

        $cont->generalTags('<div class="q_row" style="margin-top:10px;margin-bottom:10px;"><div class="innerTitle"><div class="pettyCsh"></div>Petty Cash</div><div id="label" style="width:40px;margin-left:100px;font-size:16px;">Action</div><div class="qs_wrap" id="inPetty" style="">'.$cnt->toString().'</div></div>');

        $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

        $cont->generalTags('<div class="q_row" id="pCo">');

        $cont->generalTags($this->rPettyCash());

        $cont->generalTags('</div>');

        return $cont->toString();
    }
    public function showPettycashOptions($opt){

        if($opt==1){
            return $this->rPettyCash();
        }else{
            return $this->listPettyCash();
        }

    }
    public function listPettyCash($from="",$toDate="",$tssite=-1){

        if($from=="")
            $from=$this->QLib->getSystemDate();

        if($toDate=="")
            $toDate=$this->QLib->getSystemDate();

        $cont=new objectString;

        $date=new input;

        $date->setClass('quince_date pettyFrm');

        $date->setId('theDate');

        $date->input('text','theDate',$this->QLib->getSystemDate());

        $tdate=new input;

        $tdate->setClass('quince_date pettyTo');

        $tdate->setId('theDate2');

        $tdate->input('text','theDate2',$this->QLib->getSystemDate());

        $site=new input;

        $site->addItem(-1,"Select Site");

        $tSites=$this->QLib->getSites('where status=1');

        for($i=0;$i<count($tSites);$i++){
            $site->addItem($tSites[$i]->site_id,strtoupper($tSites[$i]->site_name));
        }

        $site->setClass('quince_select');

        $site->select('theSite');

        $cont->generalTags('<div class="q_row"><div id="label" style="width:40px;font-size:16px;">From:</div>'.$date->toString().'<div id="label" style="width:40px;font-size:16px;margin-left:10px;">To:</div>'.$tdate->toString().'<div id="label" style="font-size:16px;width:50px;margin-left:20px;" >Site</div><div class="qs_wrap" id="pSite">'.$site->toString().'</div></div>');

        $cont->generalTags('<div class="listPetty" style="float:left;width:100%;">');

        $cont->generalTags($this->pettyCashList($from,$toDate,$tssite));

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function pettyCashList($from,$toDate,$site){

        $cont=new objectString;

        $list=new open_table;

        $list->setRightAlign(array(4,5));

        $list->enablePrintExport(true,array('Petty Cash'));

        $list->setColumnNames(array('No.','Date','Site','Description','Amount','By'));

        $list->setColumnWidths(array('5%','12%','17%','35%','13%','15%'));

        //$cont->generalTags('where date(theDate)>=date('.$this->QLib->dateFormatForDb($from).') and date(theDate)<=date('.$this->QLib->dateFormatForDb($toDate).')');

        $pc=$this->QLib->getPettyCashRecords('where date(theDate)>=date('.$this->QLib->dateFormatForDb($from).') and date(theDate)<=date('.$this->QLib->dateFormatForDb($toDate).') and site_id='.$site);

        $amount=0;

        for($i=0;$i<count($pc);$i++){
            $list->addItem(array(($i+1),$pc[$i]->pc_date,$this->getSiteName($pc[$i]->pc_siteId),$pc[$i]->pc_description,number_format($pc[$i]->pc_amount,2),$pc[$i]->pc_byName));
            $amount+=$pc[$i]->pc_amount;
        }

        $list->addItem(array(' ',' ',' ',' ','TOTAL '.number_format($amount,2),' '),0,array(' ',' ',' ',' ','font-weight:bold;'));

        $list->showTable();

        $cont->generalTags($list->toString());

        return $cont->toString();

    }
    public function rPettyCash(){

        $cont=new objectString;

        $sites=new input;

        $sites->setId('sSite');

        $sites->setClass('quince_select');

        $sites->addItem(-1,"Select Site");

        $tSites=$this->QLib->getSites('where status=1');

        for($i=0;$i<count($tSites);$i++){
            $sites->addItem($tSites[$i]->site_id,strtoupper($tSites[$i]->site_name));
        }

        $sites->select('sites');

        $date=new input;

        $date->setId('theDate');

        $date->setClass('quince_date');

        $date->input('text','theDate',$this->QLib->getSystemDate());

        $cont->generalTags('<div class="q_row"><div id="label" style="font-size:16px;width:50px;" >Date</div>'.$date->toString().'<div id="label" style="font-size:16px;width:50px;margin-left:20px;" >Site</div><div class="qs_wrap">'.$sites->toString().'</div><div id="sid_15" class="saveFList" style="margin-top:-5px;">Submit Petty Cash</div></div>');

        $list=new open_table;

        $list->canDeleteRow(true);

        $list->setRightAlign(array(2));

        $list->setNumberColumns(array(2));

        $list->calculateTotalOnly(2);

        $list->setColumnNames(array('No.','Description','Amount'));

        $list->setColumnWidths(array('13%','60%','20%'));

        $list->setEditableColumns(array(1,2));

        $list->showTable();

        $cont->generalTags($list->toString());

        $cont->generalTags('<div class="q_row"><div class="addBtn">+</div></div>');

        $cont->generalTags('<div class="Rtotal">TOTAL 0.00</div>');

        return $cont->toString();

    }
    public function savePettyCash($listData,$site,$date){
        $data=json_decode($listData);

        for($i=0;$i<count($data);$i++){

            if($data[$i][0]==""){
                return new name_value(false,System::getWarningText("Error on item number ".($i+1).".Please enter description."));
            }

            if($data[$i][1]==""){
                return new name_value(false,System::getWarningText("Error on item number ".($i+1).".Please enter amount."));
            }

        }

        for($i=0;$i<count($data);$i++){
            $this->QLib->savePettyCash($data[$i][0],$data[$i][1],$site,$date);
        }

        return new name_value(true,System::successText("Petty cash recorded successfully."));
    }
    public function saveOR($list,$date){

        for($i=0;$i<count($list);$i++){
            if(trim($list[$i][0])==""){
                return new name_value(false,System::getWarningText("Description cannot be empty for item number ".($i+1)));
            }
            if(trim($list[$i][1])==""){
                return new name_value(false,System::getWarningText("Amount cannot be empty for item number ".($i+1)));
            }
        }
        if(isset($_POST['siteId'])){
            $id=$this->QLib->createOfficeRequest($date,$_POST['siteId']);

            for($i=0;$i<count($list);$i++)
                $this->QLib->addOpItems($id,$list[$i][0],0,$list[$i][1]);

            return new name_value(true,System::successText("Expense submitted succesfully."));
        }else{
            return new name_value(false,System::getWarningText($_POST['siteId']));

        }
    }
    public function expenseIncomeMenu(){

        return $this->QLib->expenseIncomeMenu();

    }
    public function officeProcurementMenu(){
        return $this->QLib->officeProcurementMenu();
    }
    public function approveRequest($theId){

        switch(explode('_',$theId)[0]){
            case 'or':
                $or=$this->QLib->getOfficeRequests('where id='.explode('_',$theId)[1]);
                for($i=0;$i<count($or);$i++){
                    $this->notifyUsers('Accounts,Administrator',array('Accounts'=>$this->ud->user_name.' has approved office requision '.$or[$i]->proc_no.'.','Administrator'=>$this->ud->user_name.' has approved office requision '.$or[$i]->proc_no.'.','Subject'=>' Office Requisition '.$or[$i]->proc_no.'.'),22);
                }
                $this->QLib->approveOp(explode('_',$theId)[1]);
                return new name_value(true,System::successText('Requisition approved successfully.'));
                break;
        }

    }
    //--------------------------------------------End Income & Expenses--------------------------
    public function listProjects(){

        $cont=new objectString;

        $cont->generalTags('<div class="thePop"></div>');

        $cont->generalTags('<div class="q_row" style="margin-top:20px;margin-bottom:10px;"><div class="innerTitle">Active Projects</div></div>');

        $cont->generalTags('<div class="q_row" id="refCont">');


        $cont->generalTags(System::divCont($this->projectList(),'q_col','','style="width:100%;"'));



        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function getProjectListOnly(){
        return System::divCont($this->projectList(),'q_col','','style="width:100%;"');
    }
    public function projectList(){

        $list=new open_table;

        $list->setColumnNames(array('Created','Project Title','Location','<div class="a3-right">Budget</div>',' ',' '));

        $list->setColumnWidths(array('18%','30%','10%','18%','10%','5%','8%'));

        $list->setHoverColor('#cbe3f8');

        $list->setId('plist');

        $proj=$this->QLib->getProjects();

        $managers=$this->getProjectManagers();

        //print_r($managers);

        for($i=0;$i<count($proj);$i++){
            $list->addItem(array($proj[$i]->project_entryDate,$proj[$i]->project_name,$proj[$i]->project_location,"<div class='a3-right a3-text-orange'>".number_format($proj[$i]->project_budget,2)."</div>",'<div class="vReq" id="exp_'.$proj[$i]->project_id.'" title="View projects details"></div>',$ep=($this->QLib->positionHasPrivilege($this->ud->user_userType,-2)|$this->ud->user_id==1) ? '<div class="edpro" title="Click to edit project" id="pid_'.$proj[$i]->project_id.'"></div>':''));

            $list->addDataRow('<div class="dRow" id="pd_'.$proj[$i]->project_id.'"></div>');

        }

        $list->showTable();
        if(isset($_POST['isPhone'])){
            if($_POST['isPhone'] <500){
                $sm=System::shared('mobile');

                return $sm->projectList();
            }else{
                return $list->toString();
            }
        }

        return $list->toString();
    }
    public function viewPurchases($forRec=false){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row">');

        if(!$forRec){
            $cont->generalTags('<div class="innerTitle">View Purchases</div>');
        }else{

            $cont->generalTags('<div class="innerTitle">Record Purchases</div>');

            $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');
        }

        $this->QLib->changeAlertStatus($this->ud->user_id,12);

        $cont->generalTags('</div>');

        $mp=new input;

        $mp->setClass('pur_select');

        $mp->addItem(-1,"Select Requisition");

        if(!$forRec){
            $mp->setId('selMp');
        }else{
            $mp->setId('selMpR');
        }
        $mp->select('selectMp');

        $sites=new input;

        $sites->setClass('pur_select');

        $sites->addItem(-1,'Select Site');

        $saveBtn="";

        if(!$forRec){
            $sites->setId('selSt');
        }else{

            $sites->setId('selSiteR');

            $thDate=new input;

            $thDate->setClass('quince_date');

            $thDate->setId('theDate');

            $thDate->input('text','thedate',date('d',time()).'/'.date('m',time()).'/'.date('Y',time()));

            $saveBtn='<div id="label" style="width:50px;margin-left:20px;">Date</div>'.$thDate->toString();

            $saveBtn.='<div id="sid_8_0" class="saveFList">Save Purchases</div>';

        }
        $pr=$this->QLib->getProjects();

        for($i=0;$i<count($pr);$i++)
            $sites->addItem($pr[$i]->project_id,$pr[$i]->project_name);

        $sites->select('sites');
        $cont->generalTags('<div class="q_row"><div id="label">Select Site</div><div class="qs_wrap">'.$sites->toString().'</div><div id="label" style="margin-left:30px;">Select Requisition</div><div class="qs_wrap" id="sMp">'.$mp->toString().'</div>'.$saveBtn.'</div>');

        $cont->generalTags('<div class="q_row" id="pWin" style="margin-top:20px;"></div>');

        return $cont->toString();

    }
    public function loadPurchases(){

        $cont=new open_table;

        $cont->generalTags("");

        return $cont->toString();

    }/*
	public function selectMP($sid,$forRec=false){

		$sel=new input;

		$sel->setClass('pur_select');

		if(!$forRec){
		  $sel->setId('selMp');
		}else{
		  $sel->setId('selMpR');
		}
		//$qr=$this->QLib->getRequisitions('where project_id='.$sid.' and level'.(count($levs)-1).'=1 and requisition_status=0');

		$levs=$this->QLib->getALevels("order by thelevel asc");

        //$us=System::shared("assist");

       // $purchase=$us->getTotalReceived("where project_id=".$sid);

        $req=array();

        foreach($purchase as $pur)
            if(!in_array($pur[3],$req))
                array_push($req,$pur[3]);

       f(count($levs) >1)
			$qr=$this->QLib->getRequisitions('where id=');
		else
			$qr=$this->QLib->getRequisitions('where project_id='.$sid.'  and requisition_status=1');*


		$sel->addItem(-1,'Select Requisition');

		foreach($req as $key=> $r)
		 $sel->addItem($r,'Requisition '.($key+1));

		$sel->select('selCal');

		return $sel->toString();

	}*/
    public function selectMP($sid,$forRec=false,$state=2){

        $sel=new input;

        $as=System::shared("assist");

        $sel->setClass('pur_select');

        if(!$forRec){
            $sel->setId('selMp');
        }else{
            $sel->setId('selMpR');
        }
        $levs=$this->QLib->getALevels("order by thelevel asc");

        $qr=$this->QLib->getRequisitions('where project_id='.$sid.' and level'.count($levs).'=1 and requisition_status=0');

        $sel->addItem(-1,'Select Requisition ');

        for($i=0;$i<count($qr);$i++){

            $data=$as->verifyRequisitionPurchase($qr[$i]->req_no);

            if($data['status'] & $state !=1){
                $sel->addItem($qr[$i]->req_id,' Requisition '.$qr[$i]->req_no);
            }else if($state==1){
                $sel->addItem($qr[$i]->req_id,' Requisition '.$qr[$i]->req_no);
            }

        }
        $sel->select('selCal');

        return $sel->toString();

    }
    public function listPurchases($rid){

        $cont=new objectString();

        $list=new open_table;

        $pi=$this->QLib->getPurchasedItems($rid);

        $req=$this->QLib->getRequisitions('where id='.$rid);



        for($i=0;$i<count($req);$i++){

            $sites=$this->getSites('where id='.$req[$i]->req_siteId);

            for($x=0;$x<count($sites);$x++)
                $list->enablePrintExport(true,array('Purchases','<b>SITE</b> : '.$sites[$x]->site_name,'Requisition '.$req[$i]->req_no));

        }
        $list->setColumnNames(array('No.','Desription','Unit','Approved Price','Approved Qty','Purchased Qty','Purchased Qty','Variance','',' '));

        $list->setColumnWidths(array('5%','20%','8%','8%','8%','8%','8%','8%','8%','1%','4%'));

        $list->setHoverColor('#cbe3f8');

        //print_r($pi);@changes linus

        $amountsArr=0;$requested=0;$item_qty=0;$app_qty=0;

        for($x=0;$x<count($pi);$x++){
            $share="";
            if($pi[$x]->item_requested<$pi[$x]->item_qty)
                $share='<div class="tr_items" title="Move Excess Quantity"></div>';

            $amountsArr+=($pi[$x]->item_rate*$pi[$x]->item_qty);
            $requested+=($pi[$x]->item_requested*$pi[$x]->item_theRate);

            $item_qty+=$pi[$x]->item_qty;$app_qty +=$pi[$x]->item_requested;

            $list->addItem(array(($x+1),trim($pi[$x]->item_description),$pi[$x]->item_unit,$pi[$x]->item_rate,number_format($pi[$x]->item_requested,2),$pi[$x]->item_qty,number_format($pi[$x]->item_requested-$pi[$x]->item_qty)." ".$pi[$x]->item_unit,' ',' ','<div class="xpand" title="View Details" id="rq_'.$x.'_'.str_replace(' ','#',trim($pi[$x]->item_description)).'_'.$rid.'_'.$pi[$x]->item_theRate.'_'.$pi[$x]->item_requested.'"></div>',$share));

            $list->addDataRow('<div class="dRw" id="dRw_'.$x.'" ><div class="dxBar">'.$pi[$x]->item_description.' [Budget @'.$pi[$x]->item_theRate.'/'.$pi[$x]->item_unit.']<div class="clM" title="Close" id="rq_'.$x.'">X</div></div><div class="dCont" id="scont_'.$x.'"></div></div>');

        }

        $list->showTable();

        $cont->generalTags($list->toString());

        //@linus

        if(count($pi)){
            $cont->generalTags("<div class='a3-right a3-border a3-small a3-round' style='width:30%'>
			<nav class=' a3-left  a3-padding a3-border-bottom a3-full' > <b style='width:100px;float:left'>Approved Budget</b> ".number_format($requested,2)."</nav>");
            $cont->generalTags("<nav class=' a3-full a3-left a3-padding a3-border-bottom'  ><b style='width:100px;float:left'>Total Purchases </b>".number_format($amountsArr,2)."</nav>");
            $cont->generalTags("<nav class=' a3-full a3-left a3-padding a3-border-bottom'  ><b style='width:100px;float:left'>Budget Variance </b>".number_format( ($requested-$amountsArr),2)."</nav>");
            //$cont->generalTags("<nav class=' a3-full a3-left a3-padding a3-border-bottom'  ><b style='width:100px;float:left'>Variance </b>".($ww=($app_qty>$item_qty) ? "still saving":" Excess Qty")."</nav></div>");//@linus
        }


        //$cont->generalTags("<div class='a3-right'><b>Total Purchased :</b>.".$amountsArr."</div>");

        return $cont->toString();

    }
    private function filterNegative($numb=0,$checkVal=null){

        if($checkVal==null){
            if($numb<0)
                return "";
        }else{
            if($checkVal<0)
                return "";
        }
        return $numb;
    }
    public function exportDiv($x,$title){
        return $this->expandDiv($x, $title);
    }
    public function expandDiv($x, $title){

        $cont=new objectString;

        $cont->generalTags('<div class="dRw" id="dRw_'.$x.'" ><div class="dxBar">'.$title.'<div class="clM" title="Close" id="rq_'.$x.'">X</div></div><div class="dCont" id="scont_'.$x.'"></div></div>');

        return $cont->toString();

    }
    private function compareSpending($requested,$qty,$rate,$actual){
        if($requested<$qty){
            return '<div style="float:left;color:#f00;font-weight:bold;">Excess Qty</div>';
        }else if(($rate*$qty)<$actual){
            return '<div style="float:left;color:#f00;font-weight:bold;">Rate Exceeded</div>';
        }else  if(($rate*$qty)>$actual){
            if(($actual/$qty) <$rate){
                return '<div style="float:left;color:green;font-weight:bold;">Saving on Rate</div>';
            }else{
                return '<div style="float:left;color:green;font-weight:bold;">Less Qty</div>';
            }

        }else{
            if($qty<$requested)
                return "less";

        }
    }
    public function showPurchaseDetails($srch,$id,$rate=0,$rqty=0){

        $cont=new objectString;

        $list=new open_table;

        $list->setId(str_replace(str_split('()*\\\'-_#/'),'',$srch));

        //$list->setId(str_replace("'","",str_replace(')','',str_replace('(','',str_replace(' ','',str_replace('_','',str_replace('-','',str_replace('.','',str_replace('#','',$srch)))))))));

        $list->setColumnNames(array('Date','Item','Qty','Total','By'));

        $list->setColumnWidths(array('15%','20%','10%','15%','10%'));

        $list->setHoverColor('#cbe3f8');

        $list->hideHeader(true);

        $pi=$this->QLib->getPurchasedItems2($id,' and description="'.str_replace('#',' ',$srch).'" ');

        $budget=0;

        $spent=0;

        for($i=0;$i<count($pi);$i++)
            if($pi[$i]->item_theRate>$rate){
                $list->addItem(array($pi[$i]->item_requested,'<span style="color:#f00;float:left;font-weight:bold;">'.$pi[$i]->item_qty.' '.$pi[$i]->item_unit.' @'.$pi[$i]->item_theRate.'</span>','<div style="float:left;width:100%;text-align:right;font-weight:bold;">'.number_format($pi[$i]->item_qty*$pi[$i]->item_theRate,2).'</div>','By '.$pi[$i]->item_byName));
                $spent+=$pi[$i]->item_qty*$pi[$i]->item_theRate;
            }else{
                $list->addItem(array($pi[$i]->item_requested,$pi[$i]->item_qty.' '.$pi[$i]->item_unit.' @'.$pi[$i]->item_theRate,'<div style="float:left;width:100%;text-align:right;font-weight:bold;">'.number_format($pi[$i]->item_qty*$pi[$i]->item_theRate,2).'</div>','By '.$pi[$i]->item_byName));
                $spent+=$pi[$i]->item_qty*$pi[$i]->item_theRate;
            }

        $list->addItem(array(' ',' ',' '));

        $list->addItem(array('<b>TOTAL SPENT</b>','','<div style="float:left;width:100%;text-align:right;font-weight:bold;">'.number_format($spent,2).'</div>'));

        $list->addItem(array('<b>BUDGET</b>','','<div style="float:left;width:100%;text-align:right;font-weight:bold;">'.number_format($rate*$rqty,2).'</div>'));

        $list->addItem(array('<b>Excess</b>','','<div style="width:100%;font-weight:bold ;float:left;text-align:right;color:#f00;">'.($val=($rate*$rqty)-$spent<0 ? number_format($spent-($rate*$rqty),2):'').'</div>'));

        $list->showTable();

        $cont->generalTags($list->toString());

        return $cont->toString();

    }
    public function purchaseList($id=0){
        $as=System::shared("assist");

        $cont=new objectString();

        $cont->generalTags("<input value='1' id='allow_edit' type='hidden' />");

        $list=new open_table;

        $_SESSION[System::getSessionPrefix().'_rid']=$id;

        $list->setNumberColumns(array(2,3));

        $list->setEditableColumns(array(2,3));

        $list->setCalculator(2,3,5);

        $data=$as->verifyRequisitionPurchase($id);

        //$ri=$this->QLib->getRequestItems('where request_id='.$id." and qty<>-1");

        $list->setColumnNames(array('No.','Desription','Rate','Qty','Unit',' '));

        $list->setHoverColor('#cbe3f8');

        $list->setColumnWidths(array('10%','43%','15%','15%','15%','0%'));

        $todo=$data['todo'];

        for($i=0;$i<count($todo);$i++)
            if($todo[$i]->item_description !=null  && $todo[$i]->item_unit !=null)
                $list->addItem(array($i+1,$todo[$i]->item_description,'Add Rate ','Add Qty ',$todo[$i]->item_unit,''));

        $list->showTable();

        $cont->generalTags($list->toString());

        return $cont->toString();

    }
    public function showReceivedDetails($srch,$id,$rate=0,$rqty=0,$lid=0){

        $cont=new objectString;

        $list=new open_table;

        $list->setId(str_replace("'","",str_replace(')','',str_replace('(','',str_replace(' ','',str_replace('_','',str_replace('-','',str_replace('.','',str_replace('#','',$srch)))))))).$lid);

        //str_replace('#','-1',$srch).$lid;

        $list->setColumnNames(array('Date','Item','Qty','Total','By',' '));

        $list->setColumnWidths(array('15%','20%','25%','5%','10%','23%'));

        $list->setHoverColor('#cbe3f8');

        $list->hideHeader(true);

        $pi=$this->QLib->getReceivedItems2($id,' and description="'.str_replace('#',' ',$srch).'" ');

        for($i=0;$i<count($pi);$i++){
            $bt="";
            if($this->ud->user_userType==-2)
                $bt='<div class="delEn" id="'.str_replace('#','-1',$srch).$lid.'_'.$i.'#'.$pi[$i]->item_purchaseId.'#'.$pi[$i]->item_requestId.'#'.$lid.'">Reset Entry</div>';

            $list->addItem(array($pi[$i]->item_requested,$pi[$i]->item_qty,'By '.$pi[$i]->item_byName,'','',$bt));
        }
        $list->addItem(array(' ',' ',''));

        $list->showTable();

        $cont->generalTags($list->toString());

        return $cont->toString();

    }
    public function listReceived($rid){

        $list=new open_table;

        //$pi=$this->QLib->getReceivedItems($rid);

        $pi=$this->QLib->getRequestItems("where request_id=".$rid);

        // print_r($pi);

        $list->setHoverColor('#cbe3f8');

        $req=$this->QLib->getRequisitions('where id='.$rid);

        $titles=array();

        for($i=0;$i<count($req);$i++){

            $titles[]="Received Items";

            $sites=$this->QLib->getSites('where id='.$req[$i]->req_siteId);

            for($c=0;$c<count($sites);$c++){
                $titles[]=$sites[$c]->site_name;
                $titles[]='Requisition '.$req[$i]->req_no;
            }
            $list->enablePrintExport(true,$titles);

        }

        if(!$this->positionHasPrivilege($this->ud->user_userType,70)){

            $list->setColumnNames(array('No.','Description','Unit','Purchased','Approved','Received','Difference',' '));

            $list->setColumnWidths(array('5%','25%','10%','15%','14%','10%','15%','%5'));




            for($x=0;$x<count($pi);$x++){
                $data=$this->QLib->getRequestItemPurchase("where request_id=".$rid." and description='".$pi[$x]->item_description."'  group by description limit 1");
                $mData=$this->QLib->getRequestReceivedItems("where request_id=".$rid." and description='".$pi[$x]->item_description."'   limit 1");

                $ww=count($data) ? $data[0][0]: 0 ;

                $re=count($mData) ? $mData[0][0] : 0;

                $state="Within Budget";

                if( ($pi[$x]->item_qty-$ww) >0 ){
                    $state="Excess Purchase";
                }else if( ($pi[$x]->item_qty-$ww) <0 ){
                    $state="Over Purchase";
                }
                $list->addItem(array(($x+1),$pi[$x]->item_description,$pi[$x]->item_unit,$ww,$pi[$x]->item_qty,$re,$state));

                /// $list->addItem(array( ($x+1),$pi[$x]->item_description,$pi[$x]->item_unit,$pi[$x]->item_purchaseQty,number_format($pi[$x]->item_requested,2),$pi[$x]->item_qty,number_format($pi[$x]->item_purchaseQty-$pi[$x]->item_qty)));

            }

        }else{


            $list->setColumnNames(array('No.','Desription','Received','Unit',' '));

            $list->setColumnWidths(array('5%','55%','21%','8%','10%'));

            //$this->QLib->getCurrentSpending($rid,str_replace('-',' ',$pi[$x]->item_description)))

            for($x=0;$x<count($pi);$x++){
                $mData=$this->QLib->getRequestReceivedItems("where request_id=".$rid." and description='".$pi[$x]->item_description."'   limit 1");

                $re=count($mData) ? $mData[0][0] : 0;

                $list->addItem(array(($x+1),$pi[$x]->item_description,$re,$pi[$x]->item_unit,' '));

                //$list->addItem(array( ($x+1),$pi[$x]->item_description,'<div id="qty_'.$x.'" style="width:100%;float:left;">'.$pi[$x]->item_qty.'</div>',$pi[$x]->item_unit));

                //'<div class="xpand2" title="View Details" id="rq_'.$x.'_'.str_replace(' ','#',$pi[$x]->item_description).'_'.$rid.'_'.$pi[$x]->item_theRate.'_'.$pi[$x]->item_requested.'"></div>'

                //$list->addDataRow('<div class="dRw" id="dRw_'.$x.'" ><div class="dxBar">'.$pi[$x]->item_description.' <div class="clM" title="Close" id="rq_'.$x.'">X</div></div><div class="dCont" id="scont_'.$x.'"></div></div>');

            }

        }

        //print_r($pi);

        $list->showTable();

        //print_r($list->toString());
        return $list->toString();

    }
    public function deleteReceivedItems($id){
        $this->QLib->deleteReceivedItems($id);
    }
    public function getTotalReceived($rid,$item_name,$lid){
        return $this->QLib->getTotalReceived($rid,$item_name,$lid);
    }
    public function savePurchase($data,$tdate){

        $req=$this->QLib->getRequisitions('where id='.$_SESSION[System::getSessionPrefix().'_rid']);

        for($x=0;$x<count($req);$x++){
            $tid=$this->QLib->savePurchase($req[$x]->req_projectId);
            for($c=0;$c<count($data);$c++){

                if(is_numeric(trim($data[$c][1]))){

                    if(trim($data[$c][1])>0){

                        $this->QLib->savePurchasedItems($req[$x]->req_projectId,0,$req[$x]->req_id,$data[$c][0],$data[$c][2],$data[$c][3],$tid,$data[$c][1],$tdate);

                    }

                }

            }
        }

        //$this->QLib->savePurchasedItems($project_id,$site_id,$req_id,$desc,$qty,$unit_type,$purchaseid);

    }
    public function saveReceived($data,$thdate){

        $req=$this->QLib->getRequisitions('where id='.$_SESSION[System::getSessionPrefix().'_rid']);

        for($x=0;$x<count($req);$x++){
            $tid=$this->QLib->saveReceived($req[$x]->req_projectId);
            for($c=0;$c<count($data);$c++){

                if(is_numeric(trim($data[$c][1]))){
                    if(trim($data[$c][1])>0){
                        $this->QLib->saveReceivedItems($req[$x]->req_projectId,0,$req[$x]->req_id,$data[$c][0],$data[$c][1],$data[$c][2],$tid,0,$thdate);
                    }
                }
            }
        }

    }
    public function getSite($id){
        $site=$this->QLib->getSites('whe');
    }
    public function showOverallReceived(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row"><div class="innerTitle" style="width:100%;">Overall Received</div></div>');

        $sites=$this->QLib->getSites('where status=1');

        $st=new input;

        $st->setClass('quince_select');

        $st->addItem(-1,'View From All Sites');

        for($i=0;$i<count($sites);$i++)
            $st->addItem($sites[$i]->site_id,$sites[$i]->site_name);

        $st->select('selSite');

        $fDate=new input;

        $fDate->setTagOptions('style="margin-right:20px;"');

        $fDate->setId('recFrom');

        $fDate->setClass('quince_date');

        $fDate->input('text','qDate','');

        $tDate=new input;

        $tDate->setId('recTo');

        $tDate->setClass('quince_date');

        $tDate->input('text','tDate','');

        $cont->generalTags('<div class="q_row"><div class="qs_wrap" id="orec">'.$st->toString().'</div><div style="float:right;"><div id="label" style="width:50px">From</div>'.$fDate->toString().'<div id="label" style="width:30px;">To</div>'.$tDate->toString().'</div></div>');

        $cont->generalTags('<div class="q_row" id="orecDiv">');

        $cont->generalTags($this->listItemsReceived(-1));

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function showOverallPurchased(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row"><div class="innerTitle" style="width:100%;">Overall Purchases</div></div>');

        $sites=$this->QLib->getSites('where status=1');

        $st=new input;

        $st->setClass('quince_select');

        $st->addItem(-1,'View From All Sites');

        for($i=0;$i<count($sites);$i++)
            $st->addItem($sites[$i]->site_id,$sites[$i]->site_name);

        $st->select('selSite');

        $fDate=new input;

        $fDate->setTagOptions('style="margin-right:20px;"');

        $fDate->setId('recFromp');

        $fDate->setClass('quince_date');

        $fDate->input('text','qDate','');

        $tDate=new input;

        $tDate->setId('recTop');

        $tDate->setClass('quince_date');

        $tDate->input('text','tDate','');

        $cont->generalTags('<div class="q_row"><div class="qs_wrap" id="opur">'.$st->toString().'</div><div style="float:right;"><div id="label" style="width:50px">From</div>'.$fDate->toString().'<div id="label" style="width:30px;">To</div>'.$tDate->toString().'</div></div>');


        $cont->generalTags('<div class="q_row" id="orecDiv">');

        $cont->generalTags($this->listItemsPurchased(-1));

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function listItemsReceived($siteId,$frmdate="",$tDate=""){

        $cont=new objectString;

        $list=new open_table;

        $site="All Sites";

        if($siteId!=-1){
            $sites=$this->getSites('where id='.$siteId);
            for($x=0;$x<count($sites);$x++)
                $site='<b>SITE</b> : '.$sites[$x]->site_name;
        }

        $list->enablePrintExport(true,array('Overall Received',$site));

        $items=array();

        $whr="";

        if(($frmdate!="")&($tDate!="")){
            if($siteId ==-1){
                $whr=" and date(date_processed)>=date(".$this->QLib->dateFormatForDb($frmdate).') and date('.$this->QLib->dateFormatForDb($tDate).')>=date(date_processed)';
            }else{
                $whr=" and date(a.date_processed)>=date(".$this->QLib->dateFormatForDb($frmdate).') and date('.$this->QLib->dateFormatForDb($tDate).')>=date(a.date_processed)';
            }
        }

        if($siteId==-1){
            $items=$this->QLib->getReceivedItems2('',' '.$whr.' order by date_processed desc limit 100',true);
        }else{

            $items=$this->QLib->getReceivedItems2('',' '.$whr.' and b.site_id='.$siteId.' order by a.date_processed desc',true);
        }
        $sites=System::nameValueToSimpleArray(System::swapNameValue($this->QLib->getSiteArray('')));

        //print_r($sites);

        $list->setColumnNames(array('No.','On Mp.','Date','Site','Req No.','Description','Qty','Unit','Recorded By'));

        $list->setColumnWidths(array('5%','0%','9%','15%','13%','20%','7%','9%','14%'));

        for($i=0;$i<count($items);$i++){
            //$cont->generalTags($items[$i]->item_siteId.",");
            $list->addItem(array($i+1,$items[$i]->item_no,$items[$i]->item_requested,System::getArrayElementValue($sites,$this->QLib->getRequisitionSiteId($items[$i]->item_requestId),''),'Requisition '.$this->QLib->getMPNo($items[$i]->item_requestId),$items[$i]->item_description,$items[$i]->item_qty,$items[$i]->item_unit,$items[$i]->item_byName));
        }

        $list->showTable();

        $cont->generalTags($list->toString());

        return $cont->toString();

    }
    public function oPettyCashPurhases(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row"><div class="innerTitle" style="width:100%;">Overall Petty Cash Purchases</div></div>');

        $sites=new input;

        $sites->setClass('quince_select');

        $sites->addItem(-1,'All Active Sites');

        $sts=$this->QLib->getSites('where status=1');

        for($i=0;$i<count($sts);$i++){
            $sites->addItem($sts[$i]->site_id,$sts[$i]->site_name);
        }

        $sites->select('sites');

        $fDate=new input;

        $fDate->setClass('quince_date');

        $fDate->setId('pFromDate');

        $fDate->input('text','fDate','');

        $tDate=new input;

        $tDate->setClass('quince_date');

        $tDate->setId('pToDate');

        $tDate->input('text','toDate','');

        $cont->generalTags('<div class="q_row"><div class="qs_wrap" id="opetty">'.$sites->toString().'</div><div style="float:right;"><div  class="label" style="width:50px;" >From</div>'.$fDate->toString().'<div class="label" style="margin-left:30px;width:30px;">To</div>'.$tDate->toString().'</div></div>');

        $cont->generalTags('<div class="q_row" id="purWrap">'.$this->listLocalPurchases().'</div>');

        return $cont->toString();
    }
    public function listLocalPurchases($siteId=-1,$fromDate="",$toDate=""){

        $cont=new objectString;

        $list=new open_table;

        $site="All Sites";

        if($siteId!=-1){
            $sites=$this->QLib->getSites('where id='.$siteId);
            for($i=0;$i<count($sites);$i++){
                $site='<b>SITE</b>: '.$sites[$i]->site_name;
            }
        }

        $list->enablePrintExport(true,array('Petty Cash Purchases',$site));

        $list->setColumnNames(array('No','Date','Site','Description','Qty','Unit','Recorded By'));

        $list->setColumnWidths(array('5%','10%','15%','25%','14%','10%','20%'));

        $items=null;

        if($siteId==-1){
            if(($fromDate!="")&($toDate!="")){
                $items=$this->QLib->getSiteItems('where date(reported_date)>='.$this->QLib->dateFormatForDb($fromDate).' and date(reported_date)<='.$this->QLib->dateFormatForDb($toDate).'  order by reported_date desc limit 100');
            }else{
                $items=$this->QLib->getSiteItems('order by reported_date desc limit 100');
            }
        }else{
            if(($fromDate!="")&($toDate!="")){
                $items=$this->QLib->getSiteItems('where site_id='.$siteId.' and date(reported_date)>='.$this->QLib->dateFormatForDb($fromDate).' and date(reported_date)<='.$this->QLib->dateFormatForDb($toDate).' order by reported_date desc');
            }else{
                $items=$this->QLib->getSiteItems('where site_id='.$siteId.' order by reported_date desc limit 100');
            }
        }
        $sites=System::nameValueToSimpleArray(System::swapNameValue($this->QLib->getSiteArray('')));

        for($i=0;$i<count($items);$i++){
            $list->addItem(array($i+1,$items[$i]->item_entryDate,System::getArrayElementValue($sites,$items[$i]->item_siteId),$items[$i]->item_description,$items[$i]->item_qty,$items[$i]->item_unitType,$items[$i]->item_postedBy));
        }

        $list->showTable();

        $cont->generalTags($list->toString());

        return $cont->toString();

    }
    public function listItemsPurchased($site_id=-1,$frmdate="",$tDate=""){

        $cont=new objectString;

        $list=new open_table;

        $site="All Sites";

        if($site_id!=-1){
            $sites=$this->getSites('where id='.$site_id);
            for($x=0;$x<count($sites);$x++)
                $site='<b>SITE</b> : '.$sites[$x]->site_name;
        }

        $list->enablePrintExport(true,array('Overall Purchases',$site));

        $whr="";

        if(($frmdate!="")&($tDate!="")){
            if($site_id==-1){
                $whr=" and date(date_processed)>=date(".$this->QLib->dateFormatForDb($frmdate).') and date('.$this->QLib->dateFormatForDb($tDate).')>=date(date_processed)';
            }else{
                $whr=" and date(a.date_processed)>=date(".$this->QLib->dateFormatForDb($frmdate).') and date('.$this->QLib->dateFormatForDb($tDate).')>=date(a.date_processed)';
            }
        }

        $list->setColumnNames(array('No.','Item No.','Date','Site','Req No.','Description','Qty','Cost','Unit','Recorded By'));

        $list->setColumnWidths(array('5%','6%','9%','10%','13%','20%','6%','10%','9%','10%'));

        $sites=System::nameValueToSimpleArray(System::swapNameValue($this->QLib->getSiteArray('')));
        if($site_id==-1){
            $items=$this->QLib->getPurchasedItems2('',' '.$whr.' order by date_processed desc limit 100',true);
        }else{
            $items=$this->QLib->getPurchasedItems2('',' '.$whr.' and b.site_id='.$site_id.' order by a.date_processed ',true);
        }
        for($i=0;$i<count($items);$i++){
            //$cont->generalTags($items[$i]->item_siteId.",");
            $list->addItem(array($i+1,$items[$i]->item_no,$items[$i]->item_requested,System::getArrayElementValue($sites,$this->QLib->getRequisitionSiteId($items[$i]->item_requestId),''),'Requisition '.$this->QLib->getMPNo($items[$i]->item_requestId),$items[$i]->item_description,$items[$i]->item_qty,$items[$i]->item_theRate,$items[$i]->item_unit,$items[$i]->item_byName));
        }

        $list->showTable();

        $cont->generalTags($list->toString());

        return $cont->toString();

    }
    public function billOfQty($items=array()){

        $list=new open_table();

        //if(count($items)==0)
        $list->setEditableColumns(array(1,2,3));

        $list->setRightAlign(array(5));

        $list->setNumberColumns(array(2));

        //if(count($items)==0)
        $list->canDeleteRow(true,'bIt');

        $list->setHoverColor('#cbe3f8');

        $list->setColumnNames(array('No.','Description','Unit','Qty','Rate','Amount'));

        $list->setColumnWidths(array('10%','32%','10%','10%','10%','20%'));

        for($i=0;$i<count($items);$i++){
            $amt=0;
            if(is_numeric($items[$i]->item_amount))
                $amt=$items[$i]->item_amount;
            $list->addItem(array($i+1,$items[$i]->item_description,$items[$i]->item_unit,$items[$i]->item_qty,$items[$i]->item_rate,number_format($amt,2)),$items[$i]->item_id);
        }

        $this->loadExcelData($list,true);

        $list->showTable();

        return $list->toString();

    }
    public function projectPreview(){

        $cont=new objectString;

        $cont->generalTags('<div class="innerTitle" style="width:100%;border-bottom:1px solid #bbb;text-align:right;">Project Details</div>');

        return $cont->toString();

    }
    public function getProjectManagers(){

        $mans=array();

        $users=$this->um->getUsers('where user_type=0');

        for($i=0;$i<count($users);$i++){
            $mans[$users[$i]->user_id]=$users[$i]->user_name;
        }

        return $mans;

    }

    public function showSites(){

        $cont=new objectString;

        $cont->generalTags($this->QLib->showCSites());

        return $cont->toString();

    }
    public function settingsMenu(){
        return $this->QLib->settingsMenu();
    }
    public function notifyUsers($position="",$desc=array(),$mType=0,$site_id=0,$has_sms=true,$has_email=true,$uid=0){

        $exp=explode(",",$position);

        for($i=0;$i<count($exp);$i++){

            if($exp[$i]=='Construction Manager'){
                $usrs=$this->um->getUsers('where user_type=0');
                for($c=0;$c<count($usrs);$c++){
                    $this->QLib->saveAlert($usrs[$c]->user_id,$mType,System::getArrayElementValue($desc,'Construction Manager',''),$uid);
                    $note=$this->QLib->getUserNotifyType($usrs[$c]->user_id);
                    if(($note->notify_sms==1)&&($has_sms)){
                        $this->sms->sendMessage('Hello '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Construction Manager','')),$this->QLib->getUserPhone($usrs[$c]->user_id));
                    }

                    if(($note->notify_email==1)&&($has_email)){
                        $this->sendEmailMessage($usrs[$c]->user_username,System::getArrayElementValue($desc,'Subject',''),'Hi '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Construction Manager','')));
                    }

                }
            }

            if($exp[$i]=='Verification Officer'){
                $usrs=$this->um->getUsers('where user_type=-5');
                for($c=0;$c<count($usrs);$c++){
                    $this->QLib->saveAlert($usrs[$c]->user_id,$mType,System::getArrayElementValue($desc,'Verification Officer',''),$uid);
                    $note=$this->QLib->getUserNotifyType($usrs[$c]->user_id);
                    if(($note->notify_sms==1)&&($has_sms)){
                        $this->sms->sendMessage('Hello '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Verification Officer','')),$this->QLib->getUserPhone($usrs[$c]->user_id));
                    }

                    if(($note->notify_email==1)&&($has_email)){
                        $this->sendEmailMessage($usrs[$c]->user_username,System::getArrayElementValue($desc,'Subject',''),'Hi '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Verification Officer','')));
                    }

                }
            }

            if($exp[$i]=='Secretary'){
                $usrs=$this->um->getUsers('where user_type=-4');
                for($c=0;$c<count($usrs);$c++){
                    $this->QLib->saveAlert($usrs[$c]->user_id,$mType,System::getArrayElementValue($desc,'Secretary',''),$uid);
                    $note=$this->QLib->getUserNotifyType($usrs[$c]->user_id);
                    if(($note->notify_sms==1)&&($has_sms)){
                        $this->sms->sendMessage('Hello '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Secretary','')),$this->QLib->getUserPhone($usrs[$c]->user_id));
                    }

                    if(($note->notify_email==1)&&($has_email)){
                        $this->sendEmailMessage($usrs[$c]->user_username,System::getArrayElementValue($desc,'Subject',''),'Hi '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Secretary','')));
                    }

                }
            }

            if($exp[$i]=='Procurement'){
                $usrs=$this->um->getUsers('where user_type=2');
                for($c=0;$c<count($usrs);$c++){
                    $this->QLib->saveAlert($usrs[$c]->user_id,$mType,System::getArrayElementValue($desc,'Procurement',''),$uid);
                    $note=$this->QLib->getUserNotifyType($usrs[$c]->user_id);
                    if(($note->notify_sms==1)&($has_sms)){
                        $this->sms->sendMessage('Hello '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Procurement','')),$this->QLib->getUserPhone($usrs[$c]->user_id));
                    }

                    if(($note->notify_email==1)&($has_email)){
                        $this->sendEmailMessage($usrs[$c]->user_username,System::getArrayElementValue($desc,'Subject',''),strip_tags(System::getArrayElementValue($desc,'Procurement','')));
                    }

                }
            }

            if($exp[$i]=='General Manager'){
                $usrs=$this->um->getUsers('where user_type=1');
                for($c=0;$c<count($usrs);$c++){
                    $this->QLib->saveAlert($usrs[$c]->user_id,$mType,System::getArrayElementValue($desc,'General Manager',''),$uid);
                    $note=$this->QLib->getUserNotifyType($usrs[$c]->user_id);
                    if(($note->notify_sms==1)&($has_sms)){
                        $this->sms->sendMessage('Hello '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'General Manager','')),$this->QLib->getUserPhone($usrs[$c]->user_id));
                    }

                    if(($note->notify_email==1)&($has_email)){
                        $this->sendEmailMessage($usrs[$c]->user_username,System::getArrayElementValue($desc,'Subject',''),'Hi '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'General Manager','')));
                    }

                }
            }

            if($exp[$i]=='Accounts'){
                $usrs=$this->um->getUsers('where user_type=3');
                for($c=0;$c<count($usrs);$c++){
                    $this->QLib->saveAlert($usrs[$c]->user_id,$mType,System::getArrayElementValue($desc,'Accounts',''),$uid);
                    $note=$this->QLib->getUserNotifyType($usrs[$c]->user_id);
                    if(($note->notify_sms==1)&($has_sms)){
                        $this->sms->sendMessage('Hello '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Accounts','')),$this->QLib->getUserPhone($usrs[$c]->user_id));
                    }

                    if(($note->notify_email==1)&($has_email)){
                        $this->sendEmailMessage($usrs[$c]->user_username,System::getArrayElementValue($desc,'Subject',''),'Hi '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Accounts','')));
                    }

                }
            }

            if($exp[$i]=='Administrator'){

                $usrs=$this->um->getUsers('where user_type=5');
                for($c=0;$c<count($usrs);$c++){
                    $this->QLib->saveAlert($usrs[$c]->user_id,$mType,System::getArrayElementValue($desc,'Administrator',''),$uid);
                    $note=$this->QLib->getUserNotifyType($usrs[$c]->user_id);
                    if(($note->notify_sms==1)&($has_sms)){
                        $this->sms->sendMessage('Hello '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Administrator','')),$this->QLib->getUserPhone($usrs[$c]->user_id));
                    }

                    if(($note->notify_email==1)&($has_email)){
                        $this->sendEmailMessage($usrs[$c]->user_username,System::getArrayElementValue($desc,'Subject',''),'Hi '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Administrator','')));
                    }

                }

            }

            if($exp[$i]=='Director'){

                $usrs=$this->um->getUsers('where user_type=4');
                for($c=0;$c<count($usrs);$c++){
                    $this->QLib->saveAlert($usrs[$c]->user_id,$mType,System::getArrayElementValue($desc,'Administrator',''),$uid);
                    $note=$this->QLib->getUserNotifyType($usrs[$c]->user_id);
                    if(($note->notify_sms==1)&($has_sms)){
                        $this->sms->sendMessage('Hello '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Director','')),$this->QLib->getUserPhone($usrs[$c]->user_id));
                    }

                    if(($note->notify_email==1)&($has_email)){
                        $this->sendEmailMessage($usrs[$c]->user_username,System::getArrayElementValue($desc,'Subject',''),'Hi '.explode(' ',$usrs[$c]->user_name)[0].','.strip_tags(System::getArrayElementValue($desc,'Director','')));
                    }

                }

            }

        }

    }
    public function sendTestEmail(){
        $mes=new SMessage;
        $mes->message_subject="Test Email";
        $mes->message_to=$this->ud->user_userEmail;
        $mes->message_content="This is a test email";
        $mes->message_from="noreply@app.duatech.co.ke";
        $this->mailer->sendMessage($mes,$this->ud->user_userEmail);
        return System::successText('Test email sent');
    }
    public function sendEmailMessage($message_to,$subject,$content){
        //echo $message_to.' '.$subject.' '.$content;
        $mes=new SMessage;
        $mes->message_subject=$subject;
        $mes->message_to=$message_to;
        $mes->message_content=$content;
        $mes->message_from="noreply@app.duatech.co.ke";
        $this->mailer->sendMessage($mes,$this->ud->user_userEmail);
        return System::successText('Email sent successfully');
    }
    public function sendTestSms(){
        $res=$this->sms->sendMessage('This is a test message',$this->QLib->getUserPhone($this->ud->user_id));
        return $res;
    }
    public function getMyNotifications(){

        $al=$this->QLib->getAlerts('where user_id='.$this->ud->user_id.' and alert_status=0');

        $alert=array();

        for($i=0;$i<count($al);$i++){
            if(isset($alert[$al[$i]->alert_type.'_'.$al[$i]->alert_uid])){
                $alert[$al[$i]->alert_type.'_'.$al[$i]->alert_uid]+=1;
            }else{
                $alert[$al[$i]->alert_type.'_'.$al[$i]->alert_uid]=1;
            }
        }

        $als=array_keys($alert);

        $showAlerts=array();

        for($i=0;$i<count($als);$i++){
            $showAlerts[]=explode('_',$als[$i])[0].'_'.$alert[$als[$i]].'_'.explode('_',$als[$i])[1];
        }

        return json_encode($showAlerts);

    }
    public function getNotiFicationTypes(){
        return array(new name_value('Requisition',4),new name_value('Inventory',5),new name_value('Purchases',12),new name_value('Plant & Equipment',11),new name_value('Labour',10));
    }
    public function viewReports(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row"><div class="innerTitle">View Reports</div></div>');

        $sel=new input;

        $sel->setClass('quince_select');

        $sel->setId('invest_select');

        $sel->addItems(array(new name_value("Select Report",-1),new name_value("Overall Summary",1)));

        //,new name_value("Requisition",2),new name_value("Equipment",3)

        $sel->select('selected');

        $sites=new input;

        $sites->setClass('quince_select');

        $sites->addItem(-1,'Select Site');

        $psites=$this->QLib->getSites('where status=1');

        for($i=0;$i<count($psites);$i++){
            $sites->addItem($psites[$i]->site_id,$psites[$i]->site_name);
        }

        $sites->select('site_sel');

        $cont->generalTags('<div class="q_row" style="margin-top:10px;"><div id="label" style="font-size:16px;width:50px;">Type</div><div class="qs_wrap">'.$sel->toString().'</div><div id="label" style="font-size:16px;width:50px;margin-left:30px;">Site</div><div class="qs_wrap" id="sOp">'.$sites->toString().'</div></div>');

        $cont->generalTags('<div class="q_row" id="listWrap" style="margin-top:10px;">');

        //$cont->generalTags($this->viewAssetsList());

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function showAssetList(){

        return $this->QLib->viewAssetsList();

    }
    public function showInvestmentList(){
        return $this->QLib->investmentList();
    }
    public function createSite(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row"><div class="innerTitle">New Site</div></div>');

        $cont->generalTags('<div class="q_row" style="margin-top:5px;margin-bottom:5px;">'.$this->addResultsBar().'</div>');

        $site=new input;

        $site->setClass('txtField');

        $site->setId('siteName');

        $site->input('text','siteName');

        $cont->generalTags('<div class="q_row"><div id="label">Site Name</div>'.$site->toString().'</div>');

        $pro=new input;

        $pro->addItem(-1,'Select Project');

        $pro->setId('project');

        $proj=$this->QLib->getProjects();

        for($i=0;$i<count($proj);$i++){
            $pro->addItems(array(new name_value($proj[$i]->project_name,$proj[$i]->project_id)));
        }

        $pro->setClass('quince_select');

        $pro->select('project');

        $cont->generalTags('<div class="q_row"><div id="label">Project</div><div id="qs_project" class="qs_wrap">'.$pro->toString().'</div></div>');

        $pc=new input;

        $pc->setId('clerk');

        $pc->setClass('quince_select');

        $usr=$this->um->getUsers('where user_type=0');

        $pc->setId('clerk');

        $pc->addItem(-1,'Select Clerk');

        for($i=0;$i<count($usr);$i++){
            $pc->addItems(array(new name_value($usr[$i]->user_name,$usr[$i]->user_id)));
        }

        $pc->select('clerk');

        //$cont->generalTags('<div class="q_row"><div id="label">Store Clerk</div><div id="qs_clerk" class="qs_wrap">'.$pc->toString().'</div></div>');

        $cont->generalTags('<div class="q_row"><div id="label">Store Clerk</div>'.$this->searchInput('qs_clerk').'</div>');

        $loc=new input;

        $loc->setId('siteLocation');

        $loc->setClass('txtField');

        $loc->input('text','loc');

        //$cont->generalTags('<div class="q_row"><div id="label">Store Clerk</div>'.$this->searchInput('st_clerk').'</div>');

        $cont->generalTags('<div class="q_row"><div id="label">Location</div>'.$loc->toString().'</div>');

        $sub=new input;

        $sub->setId('s_site');

        $sub->setClass('saveData');

        $sub->input('button','saveSite','Save Site');

        $cont->generalTags('<div class="q_row">'.$sub->toString().'</div>');

        return $cont->toString();

    }
    public function searchInput($siteId,$info=""){

        $cont=new input;

        $cont->generalTags('<div style="float:left;"><div class="schUField" style="float:left;min-height:16px" id="'.$siteId.'">'.$info.'</div>');

        $sWr=new input;

        $sWr->setId('cf_'.$siteId);

        $sWr->setClass('scField');

        $sWr->input('text','sWr','');

        $cont->generalTags('<div class="sWrap" id="wr_'.$siteId.'">
		<div class="sBar">'.$sWr->toString().'</div>
		<div class="incon" id="in_'.$siteId.'"></div>
		</div></div>');

        $cont->generalTags('<input type="hidden" value="-1" id="vh_'.$siteId.'" class="lsch"/>');

        return $cont->toString();

    }
    //--------------------------Search-----------------------------
    public function showClerkList($whereclause=""){

        $list=new open_table;

        $list->setColumnNames(array('Name',' '));

        $list->setColumnWidths(array('60%','38%'));

        $list->setHoverColor('#cbe3f8');

        $list->hideHeader(true);

        $usrs=$this->um->getUsers($whereclause);

        for($i=0;$i<count($usrs);$i++){
            $list->addItem(array('<div style="width:100%;float:left;" id="dv_'.$usrs[$i]->user_id.'">'.$usrs[$i]->user_name.'</div>','<div class="selC" id="sl_'.$usrs[$i]->user_id.'">Select</div>'));
        }

        $list->showTable();

        return $list->toString();

    }
    public function findSite($whereclause=""){

        $list=new open_table;

        $list->setColumnNames(array('Name',' '));

        $list->setColumnWidths(array('60%','38%'));

        $list->hideHeader(true);

        $list->setHoverColor('#cbe3f8');

        $sites=$this->QLib->getSites($whereclause);

        for($i=0;$i<count($sites);$i++){
            $list->addItem(array('<div style="width:100%;float:left;" id="dv_'.$sites[$i]->site_id.'">'.$sites[$i]->site_name.'</div>','<div class="selC" id="sl_'.$sites[$i]->site_id.'">Select</div>'));
        }

        $list->showTable();

        return $list->toString();

    }
    public function formatForSearch($values){

        $list=new open_table;

        $list->setColumnNames(array('Name',' '));

        $list->setColumnWidths(array('60%','38%'));

        $list->hideHeader(true);

        $list->setHoverColor('#cbe3f8');

        for($i=0;$i<count($values);$i++){
            $list->addItem(array('<div style="width:100%;float:left;" id="dv_'.$values[$i][0].'">'.$values[$i][1].'</div>','<div class="selC" id="sl_'.$values[$i][0].'">Select</div>'));
        }

        $list->showTable();

        return $list->toString();
    }
    //---------------------------------------------end search--------------------------------------
    public function saveSite(){
        return $this->QLib->createSite($_POST['siteName'],$_POST['project'],$_POST['clerk'],$_POST['location']);
    }
    public function showIssueItems(){

        $destString="";

        $position=null;

        $as=System::shared("assist");

        $cont=new objectString;

        if($this->positionHasPrivilege($this->ud->user_userType,57) & $this->positionHasPrivilege($this->ud->user_userType,70) & $this->ud->user_userType !=1){	/* this is mainstore manager
            limited to only one site
            1.check if user is assgined to a store .
            2.get stores .
            3.get all values assigned to that project and store if assigned .

            */

            $position=1;

            $select=new objectString();

            $mSites=$this->QLib->getMyActiveSite();

            if($mSites !=null){
                $stores=$as->getStores("where is_assigned=".$mSites->site_project);

                if(count($stores) & !count($as->getStores("where is_assigned=".$mSites->site_project."  and user_id=".$this->ud->user_id))){
                    $select->generalTags("<div id='label' class='a3-margin-left a3-margin-right'>Select Store</div><div class='qs_wrap'><select class='quince_select proj' name='storeHandler'>");

                    $select->generalTags("<option value='-2'>Select Store</option>");


                    foreach($stores as $st){
                        $select->generalTags("<option value='".$st->id."'>".$st->content."</option>");

                    }
                    $select->generalTags("</select></div>");
                }


                $wsc=$this->QLib->getWorkSchedule('where projectId='.$mSites->site_project);


                $select->generalTags("<div id='label' class='a3-margin-left a3-margin-right'>Select Component</div><div class='qs_wrap'><select class='quince_select proj' name='compHandler'>");

                $select->generalTags("<option value='-2'>Select Component</option>");

                foreach($wsc as $st){
                    $select->generalTags("<option value='".$st->wk_id."'>".$st->wk_description."</option>");

                }
                $select->generalTags("</select></div>");

                $cont->generalTags($select->toString());


            }else{
                $cont->generalTags('<div class="nFound">No Site Assigned.</div>');

                return $cont->toString();
            }

        }else if($this->positionHasPrivilege($this->ud->user_userType,57) & $this->positionHasPrivilege($this->ud->user_userType,80 ) &!$this->positionHasPrivilege($this->ud->user_userType,70) & $this->ud->user_userType !=1){

            $sites=$this->QLib->getSites('');

            $select=new objectString();

            $select->generalTags("<div class='qs_wrap'><select id='vtl' class='inv_select t-select' name='siteHandler'>");

            $select->generalTags("<option value='-3'>select site</option>");

            foreach($sites as $site)
                $select->generalTags("<option value='".$site->site_id."'>".$site->site_name."</option>");

            $select->generalTags("</select></div>");

            $cont->generalTags("<div id='label'>Select project</div>".$select->toString()." <div id='appStore'></div>");

            $cont->generalTags("<div id='invL'></div>");

            return $cont->toString();

        }else{
            $cont->generalTags('<div class="nFound">This function is restricted to issueing personel  only.</div>');



            return $cont->toString();
        }


        $cont->generalTags('<div class="q_row"><div class="a3-right a3-round a3-padding a3-blue a3-text-white a3-margin a3-pointer a3-tab" id="issBtn_'.$position.'" data-case="'.$position.'">Issue Items</div></div>');

        $cont->generalTags('<div class="q_row">'.$this->addResultsBar('issueMess').'</div>');

        $cont->generalTags("<input id='allow_edit' type='hidden' value='10'>");

        $cont->generalTags('<div class="q_row"><div id="ReqList">'.$this->receiveList().'</div></div>');

        $cont->generalTags('<div id="form_row" style="margin-top:0px;"><div class="addBtn">+</div></div>');

        return $cont->toString();

    }
    public function receiveList(){

        $cont=new objectString;

        $list=new open_table;

        $list->canDeleteRow(true);

        $list->SetRightAlign(array(2));

        $list->setEditableColumns(array(2));

        $list->setColumnNames(array('No.','Description','Qty','Unit'));

        $list->setColumnWidths(array('10%','40%','15%','10%'));

        $this->loadExcelData($list,true);

        $dem=System::shared("assist");

        $mat=$dem->issueItemData();

        for($i=0;$i<count($mat);$i++)
            $list->addItem(array(($i+1),$mat[$i][0],"click here ",$mat[$i][1]));

        $list->showTable();

        $cont->generalTags($list->toString());

        return $cont->toString();

    }
    public function addIssuedItems($vals,$id,$str,$type){



        foreach($vals as $val){

            if(is_numeric(trim($val[1]))){
                $this->QLib->addIssuedItem($val[0],$val[1],$val[2],$id,$str,$type);
            }
        }


        return new name_value(true,System::successText('Items added successfully'));
    }
    public function viewInventory(){

        $cont=new objectString;

        $sel=new input;

        $sel->setClass('inv_select');

        $sel->setId('siteSel');

        $sel->addItem(-1,'Select Site');

        $sites=$this->QLib->getSites('where status=1');

        for($i=0;$i<count($sites);$i++){
            $sel->addItem($sites[$i]->site_id,$sites[$i]->site_name);
        }

        $sel->select('rprt');

        $frm_date=new input;

        $frm_date->setClass('quince_date');

        $frm_date->setId('rFrom');

        $frm_date->input('text','from_date','');

        $to_date=new input;

        $to_date->setClass('quince_date');

        $to_date->setId('rTo');

        $to_date->input('text','from_date','');

        $type=new input;

        $type->setClass('inv_select');

        $type->setId('invType');

        $type->addItems(array(new name_value("From Requisition",1),new name_value("Stock Balances",3)));

        $type->select('selType');

        if($this->QLib->positionHasPrivilege($this->ud->user_userType,70)){
            $cont->generalTags('<div class="q_row" style="padding-bottom:5px;"><div class="innerTitle">Inventory</div>');
        }else{
            $cont->generalTags('<div class="q_row" style="padding-bottom:5px;"><div class="innerTitle">Inventory</div>');
        }

        $this->QLib->changeAlertStatus($this->ud->user_id,5);

        if(!$this->QLib->positionHasPrivilege($this->ud->user_userType,70)){
            //<div id="label" style="margin-left:30px;width:50px;">Type</div><div class="qs_wrap">'.$type->toString().'</div>
            $cont->generalTags('<div id="form_row" style="margin-top:20px;"><div id="label" style="width:80px">Select Site</div><div class="qs_wrap" style="margin-right:10px;">'.$sel->toString().'</div><div id="label" style="margin-left:30px;width:40px;">View</div><div class="qs_wrap">'.$type->toString().'</div></div>');
        }else{
            $ts=$this->QLib->getMyActiveSite();
            if(isset($ts->site_id)){
                $tSite=$ts->site_id;
                $cont->generalTags('<input type="hidden" id="siteSel" value="'.$tSite.'">');
                $cont->generalTags('<div id="form_row" style="margin-top:20px;"><div id="label" style="margin-left:20px;">View</div><div class="qs_wrap">'.$type->toString().'</div></div>');
            }
        }

        $cont->generalTags('</div>');

        $tSite=0;
        if($this->QLib->positionHasPrivilege($this->ud->user_userType,70)){
            $ts=$this->QLib->getMyActiveSite();
            if(isset($ts->site_id)){
                $tSite=$ts->site_id;

                $cont->generalTags('<div class="q_row" id="invL">'.$this->showInventory($tSite,1,"where site_ids=".$tSite).'</div>');
            }else{
                $cont->generalTags('<div class=".nFound" style="color:#E70B94;width:100%;float:left;text-align:center;">No Project Assigned</div>');
            }
        }else{
            $cont->generalTags('<div class="q_row" id="invL"></div>');
        }

        $cont->generalTags('<div class="q_row" id="qReport" style="margin-top:10px;"></div>');



        return $cont->toString();

    }
    public function userLevelPrivileges($userType){
        //echo $userType;
        switch($userType){
            case 2:
                return array(5,11,4,12,10);
            case 1:
                return array(4,12,22,5,11,10);
            case 0:
                return array(5,11,4);
            case -3:
                return array(4);
            case -2:
                return array(5,11);

            default:
                return array(4,12,22,5,11,10,13);
        }

    }
    public function showSettings(){

        $cont=new objectString();

        $cont->generalTags('<div class="q_row"><div class="innerTitle">Profile Summary</div></div>');

        $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

        $cont->generalTags('<div class="profWrap">');

        $email=new input;

        $email->setId('uEmail');

        if($this->ud->user_userType<5){
            $email->setTagOptions('disabled=disabled');
        }

        $email->setClass('txtField');

        $email->input('text','email',$this->ud->user_userEmail);

        $cont->generalTags('<div class="q_row"><div id="label" style="font-size:16px;font-weight:bold;">Name</div><div class="txtField" style="border:none;">'.$this->ud->user_name.'</div><div id="label" style="font-size:16px;font-weight:bold;">Email</div>'.$email->toString().'</div>');

        //$cont->generalTags('<div class="q_row"></div>');

        $pos=$this->QLib->getDynamicPositions(true);

        $phone=new input;

        $phone->setClass('txtField');

        $phone->setId('uPhone');

        $phone->input('text','phone',$this->QLib->getUserPhone($this->ud->user_id));

        $cont->generalTags('<div class="q_row"><div id="label" style="font-size:16px;"><b>Position</b></div><div class="txtField" style="border:none;">'.System::getArrayElementValue($pos,$this->ud->user_userType,'').'</div><div id="label" style="font-size:16px;font-weight:bold;">Phone</div>'.$phone->toString().'</div>');

        $cont->generalTags('<div class="q_row"><div class="form_button" style="float:left;" id="update_prof">Update Details</div></div>');

        $cont->generalTags('</div>');

        //$cont->generalTags('<div class="q_row"><div class="innerTitle">General Settings</div></div>');

        $cont->generalTags('<div class="q_row" style="margin-top:30px;"><div class="innerTitle">Notifications Settings</div></div>');

        $cont->generalTags('<div class="q_row" style="margin-top:30px;"><div id="us_'.$this->ud->user_id.'" style="width:98%;float:left;margin-left:1%;display:none;"></div></div>');

        $cont->generalTags('<div class="profWrap">');

        $cont->generalTags('<div class="q_row" style="margin-bottom:2px;"><div id="label" style="font-size:16px;"><b>Enable</b></div></div>');

        $cont->generalTags($this->notificationSetPanel($this->ud->user_id));

        $userAt=$this->QLib->getUserAlertTypes($this->userLevelPrivileges($this->ud->user_userType));

        $eAltypes=System::nameValueToSimpleArray($this->QLib->getMyEnabledAlertTypes($this->ud->user_id),true);

        //$cont->generalTags(print_r($eAltypes));

        $list=new open_table;

        $list->setColumnNames(array('Alert','Status','Alert','Status'));

        $list->setColumnWidths(array('39%','10%','39%','10%'));

        $list->setSize("100%","200px");

        $align=0;

        for($i=0;$i<count($userAt);$i++){

            if(isset($userAt[$i+1])){
                $chcked="";
                $chcked2="";
                if(in_array($userAt[$i]->value,$eAltypes))
                    $chcked='checked="checked"';

                if(in_array($userAt[$i+1]->value,$eAltypes))
                    $chcked2='checked="checked"';

                $list->addItem(array($userAt[$i]->name,'<input type="checkbox" class="acheck" id="acheck_'.$userAt[$i]->value.'" '.$chcked.'><div  class="upStat" id="ap_'.$userAt[$i]->value.'"></div>',$userAt[$i+1]->name,'<input type="checkbox" class="acheck" id="acheck_'.$userAt[$i+1]->value.'" '.$chcked2.'><div class="upStat" id="ap_'.$userAt[$i+1]->value.'"></div>'));
                $i+=1;

            }else{

                if(isset($userAt[$i])){
                    $chckedd="";
                    if(in_array($userAt[$i]->value,$eAltypes))
                        $chckedd='checked="checked"';

                    $list->addItem(array($userAt[$i]->name,'<input type="checkbox" class="acheck" id="acheck_'.$userAt[$i]->value.'" '.$chckedd.'><div class="upStat" id="ap_'.$userAt[$i]->value.'"></div>'));

                }

            }

        }

        $list->showTable();

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags($list->toString());

        $cont->generalTags('</div>');

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function enableUserAlertType($atype){

        $this->QLib->enableUserAlertType($this->ud->user_id,$atype);
    }
    public function disableUserAlertType($atype){

        $this->QLib->disableUserAlertType($this->ud->user_id,$atype);

    }
    public function notificationSetPanel($id){

        $cont=new objectString;

        $note=$this->QLib->getUserNotifyType($id);

        $sms_status="";

        $email_status="";

        if($note->notify_sms==1)
            $sms_status='checked="checked"';

        if($note->notify_email==1)
            $email_status='checked="checked"';

        $cont->generalTags('<div class="q_row" style="margin-top:0px;"><div id="label" style="font-size:16px;">Email Notifications</div><input type="checkbox" class="emailN_'.$id.'" value="" style="margin-top:10px;" '.$email_status.'/><div id="label" style="font-size:16px;margin-left:50px;">SMS Notifications</div><input type="checkbox" value="" style="margin-top:10px;" class="smsN_'.$id.'" '.$sms_status.'/></div>');

        $cont->generalTags('<div class="q_row"><div class="savenSet" style="float:left;font-size:12px;font-weight:normal;padding:10px 25px;" id="updateN_'.$id.'">Update Details</div></div>');


        return $cont->toString();

    }
    public function advancedESSettings(){

        $es=$this->getEmailSettings();

        $cont=new objectString();

        $cont->generalTags('<div class="q_row"><div class="innerTitle">Requisition Approval Levels</div></div>');

        $cont->generalTags('<div class="q_row"><div class="innerTitle">SMS Management</div></div>');

        $cont->generalTags('<div class="profWrap" style="margin-top:5px">');

        $cont->generalTags('<div class="q_row"><div id="label"><b>Sender Id</b></div>0</div>');

        $cont->generalTags('<div class="q_row"><div id="label"><b>Available Units</b></div><div style="float:left;">0</div></div>');

        $cont->generalTags('<div class="q_row"><div class="form_button" style="float:left;margin-left:0px" id="buyUn">Buy SMS Units</div><div class="form_button" style="float:left;margin-left:12px;background:#444;" id="testSms" >Send Test Message</div></div>');

        $cont->generalTags('</div>');

        $cont->generalTags('<div class="q_row" style="margin-top:15px;s"><div class="innerTitle">Email Settings</div></div>');

        $cont->generalTags('<div class="profWrap" style="margin-top:5px">');

        $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

        //$cont->generalTags('<div class="infoDiv">Enter your SMTP email settings for system emails.</div>');

        $cont->generalTags('<div style="float:left;width:50%" class="am_set">');

        $port=new input;

        $port->setClass('txtField');

        $port->setId('portNo');

        $port->setTagOptions('style="width:50px;"');

        $port->input('text','port',($prt=$es==null ? 0: $es->port));

        $cont->generalTags('<div class="q_row"><div id="label"><b>Port</b></div>'.$port->toString().'<div class="form_button_add" id="sndEmail" style="float:right;padding:10px 15px!important;background:#066E08;">Test Email</div></div>');

        $ser=new input;

        $ser->setClass('txtField');

        $ser->setId('smtphost');

        $ser->input('text','service',($prt=$es==null ? 0: $es->host));

        $cont->generalTags('<div class="q_row"><div id="label"><b>SMTP Server</b></div>'.$ser->toString().'</div>');

        $email=new input;

        $email->setClass('txtField');

        $email->setId('emailAdd');

        $email->input('text','email',($prt=$es==null ? 0: $es->email));

        $cont->generalTags('<div class="q_row"><div id="label"><b>Email</b></div>'.$email->toString().'</div>');

        $pass=new input;

        $pass->setClass('txtField');

        $pass->setId('pass');

        $pass->input('password','pass',($prt=$es==null ? 0: $es->password));

        $cont->generalTags('<div class="q_row"><div id="label"><b>Password</b></div>'.$pass->toString().'</div>');

        $cont->generalTags('<div class="q_row"><div class="form_button" style="float:left;" id="updateEs">Update Details</div></div>');

        $cont->generalTags('</div>');

        $cont->generalTags('<div style="float:left;width:50%" class="am_set">');

        $cont->generalTags('<div class="q_row" style="text-align:center;">Add Email Notification Types To List</div>');

        $types=new input;

        $types->setClass('quince_select');

        $types->addItems($this->getNotificationTypes());

        $types->select('select');

        $cont->generalTags('<div class="q_row" style="margin-top:30px;"><div id="label"><b>Notification Type</b></div><div class="qs_wrap">'.$types->toString().'</div></div>');

        $cont->generalTags('<div class=""></div>');

        $cont->generalTags('</div>');

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function setNotificationType($email,$sms,$user_id){
        $this->QLib->setNotificationType($user_id,$email,$sms);
    }
    public function getEmailSettings(){
        return $this->QLib->getEmailSettings();
    }
    public function saveEmailSettings($port,$host,$email,$password){
        $this->QLib->saveEmailSettings($port,$host,$email,$password);
    }
    public function showInventory($site_id=0,$typ=1,$whereclause="",$set=-2){

        $cont=new objectString;

        switch($typ){

            case 1:
                $mp=new input;

                $mp->setId('mat_pay');

                $mp->setClass('inv_select');

                $levs=$this->QLib->getALevels('');

                $wheres="where level".count($levs)."=1 and project_id=".$site_id." order by id desc";


                $req=$this->QLib->getRequisitions($wheres);

                $mp->addItem(-1,"Select Requisition");

                for($i=0;$i<count($req);$i++){
                    $mp->addItem($req[$i]->req_id,"Requisition ".$req[$i]->req_no);
                }

                $mp->select('mp');

                $cont->generalTags('<div id="form_row" style="text-align:center;font-size:16px;">From Requisition</div>');

                $cont->generalTags('<div id="form_row"><div id="label">Select Requisition</div><div class="qs_wrap">'.$mp->toString().'</div></div>');

                $cont->generalTags('<div class="listWrap" style="float:left;width:100%;">');

                $cont->generalTags($this->listReceived($site_id));

                $cont->generalTags('</div>');

                break;

            case 2:

                $tDate=new input;

                $tDate->setClass('trDate');

                $tDate->setId('trDate');

                $tDate->input('tdate','txtdate');

                $cont->generalTags('<div id="form_row" style="text-align:center;font-size:16px;">Sourced Locally</div>');

                $cont->generalTags('<div id="form_row"><div id="label">Select Date</div>'.$tDate->toString().'</div>');

                $cont->generalTags('<div class="listWrap" style="float:left;width:100%;">');

                $cont->generalTags($this->listReceived($site_id));

                $cont->generalTags('</div>');

                break;


            case 4:case 3:

            $cont->generalTags('<div id="form_row" style="text-align:center;font-size:16px;">Stock Balances.</div>');

            $cont->generalTags($this->viewSTockInventory($_POST['st'],$site_id));

            break;
            case 6:
                $as=System::shared("assist");

                $stores=$as->getStores("where is_assigned=".$site_id);

                if(count($stores)){

                }else{

                }

                break;

        }



        return $cont->toString();

    }
    public function viewSTockInventory($id=-2,$site_id=0){

        $list=new open_table;

        $list->setColumnNames(array('No.','Stock Item','Qty','Units'));

        $list->setColumnWidths(array('10%','50%','15%','10%'));

        $as =System::shared('assist');

        $stores=$as->getStores('where  is_assigned='.$id);

        $whereclause='where sch_id='.$site_id." and store=".$id." group by description";

        if($id ==0)
            $whereclause='where sch_id='.$site_id." group by description";


        if($id==0 & count($stores)==0){
            $bal=$as->getReceivedMaterials("where project_id=".$site_id);
        }else{
            $bal=$as->getStoreMaterials($whereclause);
        }


        for($i=0;$i<count($bal);$i++)
            $list->addItem(array($i+1,$bal[$i]->mat_desc,$bal[$i]->mat_value,$bal[$i]->mat_project));

        $list->showTable();

        return $list->toString();

    }
    public function updateEmailPhone($email,$phone=""){
        return $this->QLib->updateEmailPhone($email,$phone);
    }
    public function inventoryList($whereclause="",$siteId=0,$date=""){

        $list=new open_table;

        $sites=$this->QLib->getSites('where id='.$siteId);

        for($i=0;$i<count($sites);$i++){

            $list->enablePrintExport(true,array('Locally Purchased','<b>SITE</b> : '.$sites[$i]->site_name.' <b>Date</b> : '.$date));

        }

        $list->setColumnNames(array('Date','Description','Qty','Unit','Posted By'));

        $list->setColumnWidths(array('20%','48%','10%','10%','10%'));

        $items=$this->QLib->getSiteItems($whereclause);

        for($i=0;$i<count($items);$i++)
            $list->addItem(array($items[$i]->item_entryDate,$items[$i]->item_description,$items[$i]->item_qty,$items[$i]->item_unitType,$items[$i]->item_postedBy));

        $list->showTable();

        return $list->toString();

    }
    public function receiveFromLocal(){

        $cont=new objectString();

        if(!$this->QLib->positionHasPrivilege($this->ud->user_userType,70)){

            $cont->generalTags('<div class="nFound">This function is restricted to personel assigned to one site only.</div>');

            return $cont->toString();

        }

        $site=$this->QLib->getMyActiveSite(true);

        $cont->generalTags('<input type="hidden" id="uid" value="'.$site->site_id.'_'.$site->site_project.'"/>');

        $cont->generalTags('<div class="q_row"  style="border-bottom:1px solid #ddd;padding-bottom:15px;"><div class="innerTitle">Receive Items From Local Purchases</div><div class="saveFList" id="sid_7_0">Submit Items</div></div>');

        $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

        $theDate=new input;

        $theDate->setId('theDate');

        $theDate->setClass('quince_date');

        $theDate->setTagOptions('style="margin-left:20px;padding:7px 5px 7px 5px;text-indent:28px;"');

        $theDate->input('text','tdate',date('d',time()).'/'.date('m',time()).'/'.date('Y',time()));

        $cont->generalTags($this->excelImporter(false,array(),0,false,$theDate->toString()));

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags('<div id="ReqList" style="width:100%;float:left;">');

        $cont->generalTags($this->showReceiveInventoryList());

        $cont->generalTags('</div>');

        $cont->generalTags('</div>');

        $cont->generalTags('<div class="q_row"><div class="addBtn">+</div></div>');

        return $cont->toString();

    }
    public function blankMp($rid){

        $cont=new objectString;

        $sid=0;



        $sia=$this->QLib->getSiteItems('where group_id='.$rid);

        $req=$this->QLib->getRequisitions('where id='.$rid);


        for($c=0;$c<count($req);$c++){

            $_SESSION[System::getSessionPrefix().'_rid']=$rid;

            $cont->generalTags($this->addResultsBar());

            //$rid=$req[$c]->req_id;

            $cont->generalTags("<input type='hidden' value='1' id='allow_edit' >");
            $cont->generalTags('<div id="form_row"><b style="font-size:14px;padding-top:10px;font-weight:normal;"><div style="float:left;font-size:20px;">Requisition '.$req[$c]->req_no.'</div></b><div class="saveList a3-right a3-blue a3-padding a3-round a3-hover-green a3-pointer" id="sid_6_'.$req[$c]->req_id.'">Submit</div></div>');

            $list=new open_table;

            // print_r($rid);

            $items=$this->QLib->getRequestItems('where request_id='.$rid." and qty<>-1");

            $list->setColumnNames(array('No.','Description','Qty','Unit'));

            $list->setEditableColumns(array(2));

            $list->setNumberColumns(array(2));

            $list->setColumnWidths(array('10%','68%','10%','10%'));

            for($i=0;$i<count($items);$i++){
                $sid=$items[$i]->item_siteId;
                $list->addItem(array($i+1,$items[$i]->item_description,'click here',$items[$i]->item_unit));
            }

            $_SESSION[System::getSessionPrefix().'RID']=$rid.'_'.$sid;


            $list->showTable();

            $cont->generalTags($list->toString());
        }
        return $cont->toString();

    }
    public function getABlankMp(){

        $cont=new objectString();

        $cont->generalTags("<input type='hidden' id='allow_edit' value='1'>");

        $cont->generalTags("<button type='button' class='a3-right a3-blue a3-hover-green a3-pointer a3-round a3-padding a3-margin saveList' id='rac_1'>Receive</button>");

        $list=new open_table;

        $sm=System::shared("assist");

        $list->setColumnNames(array('No.','Description','Purchased Qty','Received Qty','Unit'));

        $list->setEditableColumns(array(3));

        $list->setNumberColumns(array(3));

        $list->setColumnWidths(array('10%','48%','0%','10%','10%'));

        $det=$sm->getTotalReceived('');

        //print_r($det);
        //ALTER TABLE `pr_receiveditems` ADD `store` VARCHAR(12) NULL DEFAULT 0 AFTER `store`
        for($i=0;$i<count($det);$i++)
            $list->addItem(array($i+1,$det[$i][0],$det[$i][1],'click here',$det[$i][2]));

        $list->showTable();

        $cont->generalTags("<div class='a3-left a3-margin a3-full'>".$list->toString()."</div>");

        return $cont->toString();
    }
    public function addItemToSite($site_id,$descrip,$qty,$unit_type,$gid,$item_type=0,$entry_date=''){
        $this->QLib->addItemToSite($site_id,$descrip,$qty,$unit_type,$gid,$item_type,$entry_date);
    }
    public function showReceiveInventoryList(){

        $list=new open_table;

        $list->canDeleteRow(true);

        $list->setColumnNames(array('No','Description','Qty','Unit'));

        $list->setColumnWidths(array('10%','60%','10%','10%'));

        $list->setHoverColor('#cbe3f8');

        $list->setNumberColumns(array(2));

        $list->setEditableColumns(array(1,2,3));

        $this->loadExcelData($list);

        $list->showTable();

        return $list->toString();

    }
    public function receiveFromHq(){
        $position=0;
        $cont=new objectString();

        $thDate=new input;

        $thDate->setClass('quince_date a3-right');

        $thDate->setId('theDate');

        $thDate->input('text','thedate',date('d',time()).'/'.date('m',time()).'/'.date('Y',time()));


        if($this->positionHasPrivilege($this->ud->user_userType,57) & $this->positionHasPrivilege($this->ud->user_userType,70)){
            //assigned to one site and has the receibing privillage;
            $site=$this->QLib->getMyActiveSite();

            $cont->generalTags('<div class="q_row"><div class="innerTitle">Receive From Requisition</div></div>');

            if($site==null){

                $cont->generalTags('<div class="nFound">No Project Assigned!</div>');

                return $cont->toString();
            }

            $con=new input;

            $con->setClass('inv_select');

            $con->setId('rm');

            $con->addItem(-1,'Select Requisition');

            $levs=$this->QLib->getALevels("order by thelevel asc");

            $req=$this->QLib->getRequisitions("where level".(count($levs))."=1 and requisition_status=0 and project_id=".$site->site_project);

            for($i=0;$i<count($req);$i++)
                $con->addItem($req[$i]->req_id,'Requisition '.$req[$i]->req_no);

            $con->select('consign');

            $cont->generalTags('<div class="q_row" style="border-bottom:1px solid #ddd;padding-bottom:15px;"><div id="label">Requisition </div><div class="qs_wrap">'.$con->toString().'</div><div class="a3-right a3-padding">Date:'.$thDate->toString().'</div></div>');


        }else if($this->positionHasPrivilege($this->ud->user_userType,57) & $this->positionHasPrivilege($this->ud->user_userType,80 ) &!$this->positionHasPrivilege($this->ud->user_userType,70) & $this->ud->user_userType !=1){
            //user admin user
            $cont->generalTags('<div class="q_row"><div class="innerTitle">Receive Materials To Main Store</div></div>');


            $cont->generalTags("<div class='a3-left a3-full'>".$this->getABlankMp()."</div>");


        }else if($this->positionHasPrivilege($this->ud->user_userType,57) & !$this->positionHasPrivilege($this->ud->user_userType,70) &$this->ud->user_userType !=1){
            //has the privillage of issing items;

            $cont->generalTags('<div class="q_row"><div class="innerTitle">Receive Materials</div></div>');

            $cont->generalTags("<div class='q_row' id='cont'>");

            $cont->generalTags("<div class='a3-left' id='label'>Select Project</div>");

            $proj=$this->QLib->getProjects("");

            $cont->generalTags("<div class='qs_wrap' id='mRec'><select name='proj' class=' aQuince_select' id='label' style='width:120%' >");

            $cont->generalTags("<option value='-2'>Select project</option>");

            for($i=0;$i<count($proj);$i++)
                $cont->generalTags("<option value='".$proj[$i]->project_id."'>".$proj[$i]->project_name."</option>");

            $cont->generalTags("</select></div><div class='a3-right a3-padding'>Date :".$thDate->toString()."</div></div>");
        }else{
            $cont->generalTags('<div class="nFound">This function is restricted to personel assigned to one site only.</div>');

            return $cont->toString();
        }





        /*$thDate=new input;

		$thDate->setClass('quince_date');

		$thDate->setId('theDate');

		$thDate->input('text','thedate',date('d',time()).'/'.date('m',time()).'/'.date('Y',time()));*/

        //$cont->generalTags('<div class="q_row" style="border-bottom:1px solid #ddd;padding-bottom:15px;"><div id="label">Requisition </div><div class="qs_wrap">'.$con->toString().'</div><div id="label" style="margin-left:30px;width:50px">Date</div>'.$thDate->toString().'</div>');


        $cont->generalTags('<div class="listWrap" style="width:100%;float:left;"></div>');

        return $cont->toString();

    }
    public function showProjectMps(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row">');

        //$this->QLib->changeAlertStatus($this->ud->user_id,4);

        $cont->generalTags('<div class="q_row"><div class="innerTitle">Material Requisitions</div></div>');

        $pr=$this->QLib->getProjects();

        for($i=0;$i<count($pr);$i++)
            $cont->generalTags('<div class="req_box"><div class="at_picon" id="pIc_'.$pr[$i]->project_id.'">0</div><div class="prN" id="pmp_'.$pr[$i]->project_id.'">'.$pr[$i]->project_name.'</div>
		<div class="tRq"><div class="tRq_inner">Total: '.number_format($this->QLib->getTotalProjectReq($pr[$i]->project_id),0).'</div></div>
		</div>');

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function listProjectMp($id=0){

        $cont=new objectString;
        //get the assigned projects

        if($id !=0){
            $pr=$this->QLib->getProjects('where id='.$id);
        }else{
            $site=$this->QLib->getMyActiveSite(true);

            if($site !=null){
                $pr=$this->QLib->getProject('where id='.$site->site_id)->project_id;
            }
            return ;

        }


        $ltype=new input;

        $ltype->addItems(array(new name_value('All Requisitions',3),new name_value('Pending Approval',1),new name_value ('Approved',2)));

        $ltype->setId('lstT');

        $ltype->setClass('quince_select');

        $ltype->select('qselect');

        $cont->generalTags('<div class="thePop"></div>');

        for($i=0;$i<count($pr);$i++){
            $cont->generalTags('<div class="q_row"><div class="innerTitle">Requisitions - <b>'.$pr[$i]->project_name.'</b></div></div>');
            $this->QLib->changeAlertStatus($this->ud->user_id,4,1,$pr[$i]->project_id);
            $cont->generalTags('<div class="q_row" id="req_filters" ><div class="qs_wrap" style="margin-left:10px;">'.$ltype->toString().'</div><input type="hidden" id="lstP" value="'.$pr[$i]->project_id.'"/></div>');

        }

        $cont->generalTags('<div class="q_row"><div id="rListWrap">'.$this->reqList("and project_id=".$id).'</div></div>');

        return $cont->toString();

    }
    public function showRequisition(){

        //if($this->ud->user_userType==-3)
        //return $this->foremanRequests();

        $cont=new objectString;

        if($this->ud->user_userType==0){
            $cont->generalTags('<div class="q_row"><div class="innerTitle">My Requisitions</div></div>');
        }else{

            $proj=new input;

            $proj->setClass('quince_select');

            $proj->setId('lstP');

            $proj->addItem(-1,'From All Active Projects');

            $pr=$this->QLib->getProjects('where status=1');

            for($i=0;$i<count($pr);$i++){
                $proj->addItem($pr[$i]->project_id,'From '.$pr[$i]->project_name);
            }

            $proj->select('selProj');

            $ltype=new input;

            $ltype->addItems(array(new name_value('Pending Approval',1),new name_value ('Approved',2),new name_value('All Requisitions',3)));

            $ltype->setId('lstT');

            $ltype->setClass('quince_select');

            $ltype->select('qselect');

            $cont->generalTags('<div class="q_row" id="req_filters" style="margin-top:20px"><div class="innerTitle">View Requisitions</div><div class="qs_wrap" style="margin-left:10px;">'.$proj->toString().'</div><div class="qs_wrap" style="margin-left:10px;">'.$ltype->toString().'</div></div>');
        }

        $cont->generalTags('<div class="q_row" id="rListWrap">');

        $cont->generalTags($this->reqList());

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function processListDetails(){

        $cont=new objectString;

        switch(explode('_',System::getArrayElementValue($_POST,'srch'))[0]){
            case 'fm':
                if($this->ud->user_userType==-3){
                    $cont->generalTags('<div class="fmRw"><div class="delFmReq" title="Delete Request" id="dfr_'.explode('_',System::getArrayElementValue($_POST,'srch'))[1].'_'.explode('_',System::getArrayElementValue($_POST,'srch'))[2].'">X Delete Request</div></div>');
                }
                if($this->QLib->getFmFileType(explode('_',System::getArrayElementValue($_POST,'srch'))[1])==1){
                    $cont->generalTags($this->showPdf(explode('_',System::getArrayElementValue($_POST,'srch'))[1],"fmPdf"));
                }else{
                    $cont->generalTags($this->showImage(explode('_',System::getArrayElementValue($_POST,'srch'))[1],'fm'));
                }
                //
                return $cont->toString();
                break;


            case 'lpm':
                $cont->generalTags('<div style="margin:20px 2px;float:left;width:100%;">'.$this->listPrivileges(explode('_',System::getArrayElementValue($_POST,'srch'))[1]).'</div>');
                break;
            case 'apl':
                $cont->generalTags('<div style="margin:20px 2px;float:left;width:100%;">'.$this->listLevelPrivileges(explode('_',System::getArrayElementValue($_POST,'srch'))[1]).'</div>');
                break;
            case 'ex':
                $cont->generalTags('<div style="margin:5px 2px;float:left;width:100%;">'.$this->listEstMaterials(explode('-',explode('_',System::getArrayElementValue($_POST,'srch'))[1])[1]).'</div>');
                break;

            case 'comp':
                $cont->generalTags('<div style="margin:5px 2px;float:left;width:100%;">'.$this->manageCompanyDetails(explode('_',System::getArrayElementValue($_POST,'srch'))[1]).'</div>');
                break;

            case 'uci':
                $cont->generalTags($this->componentImageUpLoader(explode('_',System::getArrayElementValue($_POST,'srch'))[1]));
                break;
            case 'djdj':

                $am_updates= new am_assist();
                $cont->generalTags($am_updates->get_aligned_table(System::getArrayElementValue($_POST,'lid')));
                break;
            case "iNc":
                $income=$this->QLib->getIncomeRecords("where site_id=".explode('_',System::getArrayElementValue($_POST,'srch'))[1]);

                $lst=new open_table;

                $lst->hideHeader(true);

                $lst->setColumnWidths(array('10%','10%','20%','30%','20%'));

                $lst->setColumnNames(array('Id','Date','<div class="a3-right">Amount</div>','Description ','Recorded By'));

                foreach($income as $dt)
                    $lst->addItem(array($dt->inc_id,$dt->inc_date,'<div class="a3-right a3-text-orange">'.number_format($dt->inc_amount,2).'</div>',$dt->inc_description,$dt->inc_byname));

                $lst->showTable();

                $cont->generalTags($lst->toString());

                break;
            case "eQ":

                $cont->generalTags("mkidkid");

                break;

        }
        return $cont->toString();
    }
    public function manageCompanyDetails($id){

        $cont=new objectString;

        $cont->generalTags('<div style="margin:5px 2px;float:left;width:100%;"><b>Add Company User</b></div>');

        $cont->generalTags($this->addResultsBar('userC_'.$id));

        $adBtn=new input;

        $adBtn->setClass('saveData sod_'.$id);

        $adBtn->setId('sod');

        $adBtn->setTagOptions('style="margin-top:-1px;margin-left:10px;"');

        $adBtn->input('button','btn','Add User');

        $txtFName=new input;

        $txtFName->setClass('txtField');

        $txtFName->setId('fName_'.$id);

        $txtFName->input('text','txtField','');

        $txtSName=new input;

        $txtSName->setId('sName_'.$id);

        $txtSName->setClass('txtField');

        $txtSName->input('text','txtField','');

        $cont->generalTags('<div class="q_row"><div id="label">First Name</div>'.$txtFName->toString().'<div id="label" style="margin-left:20px;">Last Name</div>'.$txtSName->toString().'</div>');

        $txtEmail=new input;

        $txtEmail->setClass('txtField');

        $txtEmail->setId('txtEmail_'.$id);

        $txtEmail->input('text','txtEmail');

        $cont->generalTags('<div class="q_row" style="border-bottom:1px solid #efefef;padding-bottom:10px;"><div id="label">Email</div>'.$txtEmail->toString().'</div>');

        $txtPass=new input;

        $txtPass->setClass('txtField');

        $txtPass->setId('txtPass_'.$id);

        $txtPass->input('password','password','');

        $txtRPass=new input;

        $txtRPass->setClass('txtField');

        $txtRPass->setId('txtRPass_'.$id);

        $txtRPass->input('password','rpass');

        $cont->generalTags('<div class="q_row"><div id="label">Password</div>'.$txtPass->toString().'<div 
		id="label" style="margin-left:20px;">Rpt. Password</div>'.$txtRPass->toString().'</div>');

        $cont->generalTags('<div class="q_row">'.$adBtn->toString().'</div>');

        $cont->generalTags('<div style="margin:5px 2px;float:left;width:100%;"><b>Company Administrator</b></div>');

        $cont->generalTags($this->companyPositions());

        return $cont->toString();

    }
    public function companyPositions(){

        $lst=new open_table;

        $lst->hideHeader(true);

        $lst->setColumnWidths(array('10%','40%'));

        $lst->setColumnNames(array('No.','Position'));

        $lst->showTable();

        $lst->toString();

        return $lst->toString();

    }
    public function listEstMaterials($cid=0){

        $cont=new objectString;

        $txtField=new input;

        $txtField->setClass('txtField');

        $txtField->input('text','txt');

        $mEstimates=new input;

        $mEstimates->setClass('txtField');

        $mEstimates->setId('desc_'.$cid);

        $mEstimates->input('text','mEst');

        $qty=new input;

        $qty->setClass('txtField unitQty');

        $qty->setId('iqty_'.$cid);

        $qty->setTagOptions('style="width:50px;text-align:right;"');

        $qty->input('text','qty');

        $unit=new input;

        $unit->setClass('txtField');

        $unit->setId('txtUnit_'.$cid);

        $unit->setTagOptions('style="width:50px;"');

        $unit->input('text','unitTxt','');

        $addB=new input;

        $addB->setClass('saveData svbtn_'.$cid);

        $addB->setId('addCItem');

        $addB->setTagOptions('style="margin-top:-1px;margin-left:10px;padding-top:5px!important" title="Add Item" ');

        $addB->input('button','addb',' + ');

        $cont->generalTags($this->addResultsBar('addCItem_'.$cid));

        $cont->generalTags('<div class="q_row"><div style="float:left;padding-top:5px;margin-right:10px;">MATERIAL ESTIMATES</div><div style="float:left;width:100%;margin-top:20px;"><div id="label" style="width:80px;">Description.</div>'.$mEstimates->toString().'<div id="label" style="width:30px;margin-left:20px;">Qty</div>'.$qty->toString().'<div id="label" style="width:30px;margin-left:20px;">Unit</div>'.$unit->toString().' '.$addB->toString().'</div></div>');

        $cont->generalTags('<div class="mEst jaxR'.$cid.'" style="margin-top:20px;float:left;width:100%;" id="">'.$this->listEstMaterial($cid).'</div>');

        //$cont->generalTags('<div class="q_row">LABOUR ESTIMATES</div>');

        $cont->generalTags($this->labourEst());

        return $cont->toString();

    }
    public function labourEst(){

        $cont=new objectString;

        $list2=new open_table;

        $list2->hideHeader(true);

        $list2->setColumnNames(array('No.','Privilege','Status'));

        $list2->setColumnWidths(array('10%','55%','20%'));

        $list2->showTable();

        $cont->generalTags($list2->toString());

        return $cont->toString();

    }
    public function listEstMaterial($id=0){

        $list=new open_table;

        $list->hideHeader(true);

        $list->canDeleteRow(true,5);

        $list->setId('ls');

        $list->setColumnNames(array('No.','Description','Status'));

        $list->setColumnWidths(array('10%','30%','15%','15%','15%'));

        $me=$this->QLib->getMaterialEstimates('where component_id='.$id,$id);

        for($i=0;$i<count($me);$i++){
            $list->addItem(array($i+1,$me[$i]->mat_description,$me[$i]->mat_qty.' '.$me[$i]->mat_unitType,$me[$i]->mat_issued.' '.$me[$i]->mat_unitType.' Used'),$me[$i]->mat_id);
        }

        $list->showTable();

        return $list->toString();
    }
    public function addMaterialEst($desc,$qty,$unit,$cid){
        $this->QLib->addMaterialEst($desc,$qty,$unit,$cid);
    }
    //-------------------------------Work Schedule Inner------------------------------------------------------
    public function listLevelPrivileges($id=0){

        $list=new open_table;

        //$list->hideHeader(true);

        $list->setColumnNames(array('No.','Privilege','Status'));

        $list->setColumnWidths(array('10%','55%','20%'));

        $dp=$this->QLib->getLevelPrivileges();

        $mp=$this->QLib->getMyLevelDynamicPrivileges($id);

        $x=0;

        for($i=0;$i<count($dp);$i++)
            if($dp[$i]->value==-1){
                $list->addItem(array(' ',$dp[$i]->name,' '));
                $x=0;
            }else{
                if(System::getArrayElementValue($mp,$dp[$i]->value,0)==0){

                    $leveOptions='';

                    if(System::getArrayElementValue($mp,$dp[$i]->other,'')=='slc')
                        $levelOptions='<div class="lvOption">Add/Remove Level</div>';

                    $list->addItem(array($x+1,$dp[$i]->name,'<input type="checkbox" style="margin-top:4px;" class="pchk2" id="'.$id.'_'.$dp[$i]->value.'"/><div id="'.$id.'_'.$dp[$i]->value.'_nm"></div>'.$leveOptions));
                    $x+=1;
                }else{

                    $leveOptions='';

                    if(System::getArrayElementValue($mp,$dp[$i]->other,'')=='slc')
                        $levelOptions='<div class="lvOpts">Add/Remove Level</div>';

                    $list->addItem(array($x+1,$dp[$i]->name,'<input type="checkbox" checked="checked" style="margin-top:4px;" class="pchk2" id="'.$id.'_'.$dp[$i]->value.'"/><div id="'.$id.'_'.$dp[$i]->value.'_nm"></div>'.$leveOptions));
                    $x+=1;
                }
            }

        $list->showTable();

        return $list->toString();

    }
    public function listPrivileges($id=0){

        $list=new open_table;

        $list->hideHeader(true);

        $list->setColumnNames(array('No.','Privilege','Status'));

        $list->setColumnWidths(array('10%','55%','20%'));

        $dp=$this->QLib->getDynamicPrivileges();

        $mp=$this->QLib->getMyDynamicPrivileges($id);

        $dlevel=$this->QLib->getALevels();

        $x=0;

        for($i=0;$i<count($dp);$i++)
            if($dp[$i]->value==-1){
                $list->addItem(array(' ',$dp[$i]->name,' '));
                $x=0;
            }else{
                if(System::getArrayElementValue($mp,$dp[$i]->value,0)==0){
                    $leveOptions='';
                    if($dp[$i]->other=='slc')
                        $leveOptions='<div class="lvOpts" id="lvO_'.$id.'">Assign Levels</div><div style="width:100%;overflow:hidden;"><div class="lvOptsWrap" id="lvOpp_'.$id.'">'.$this->showDLevels($dlevel,$dp[$i]->value,$id).'</div></div>';
                    $list->addItem(array($x+1,$dp[$i]->name,'<input type="checkbox" style="margin-top:4px;" class="pchk" id="'.$id.'_'.$dp[$i]->value.'"/><div class="pbar" id="'.$id.'_'.$dp[$i]->value.'_nm"></div>'.$leveOptions));
                    $x+=1;

                }else{
                    $leveOptions='';
                    if($dp[$i]->other=='slc')
                        $leveOptions='<div class="lvOpts" id="lvO_'.$id.'">Assign Levels</div><div style="width:100%;overflow:hidden;"><div class="lvOptsWrap" id="lvOpp_'.$id.'" >'.$this->showDLevels($dlevel,$dp[$i]->value,$id).'</div></div>';
                    $list->addItem(array($x+1,$dp[$i]->name,'<input type="checkbox" checked="checked" style="margin-top:4px;" class="pchk" id="'.$id.'_'.$dp[$i]->value.'"/><div class="pbar" id="'.$id.'_'.$dp[$i]->value.'_nm"></div>'.$leveOptions));
                    $x+=1;
                }
            }
        $list->showTable();

        return $list->toString();

    }
    private function showDLevels($dlev,$id,$tid){
        $cont=new objectString;

        $cont->generalTags('<div style="background:#444;color:#fff;padding:4px 5px;border-radius:5px;margin-bottom:10px;">Assign Approval Level</div>');

        $lid=0;
        $lval=System::getArrayElementValue($dlev,0,new name_value(0,0,-1));
        $lid=$lval->other;

        $levs=$this->QLib->getPositionLevels($tid);

        $lvArray=array('Level One','Lev. Two','Lev. Three','Level Four','Level Five','Level Six');

        $i=1;
        foreach($dlev as $dl){
            $checked="";

            if(in_array($dl->other,$levs))
                $checked='checked="checked"';

            $cont->generalTags('<div class="drow" style="width:100%;float:left"><div class="cells" style="width:60%;float:left;text-align:center;">'.$dl->name.' ('.$lvArray[$dl->value].')</div><input class="dyLev" '.$checked.' type="checkbox" id="chckdl_'.$dl->other.'_'.$id.'_'.$tid.'" style="margin-top:9px;"/><div id="prdl_'.$dl->other.'_'.$id.'_'.$tid.'" style="float:left;font-size:8px;padding:10px 0px;"></div></div>');
        }

        return $cont->toString();
    }
    public function addRemovePrivileges($type,$status,$pos){
        $this->QLib->addRemovePrivileges($type,$status,$pos,$this->QLib->cmp->company_prefix);
    }
    public function addRemovePrivileges2($type,$status,$pos){
        $this->QLib->addRemovePrivileges2($type,$status,$pos,$this->QLib->cmp->company_prefix);
    }
    public function getFmFileType($id){
        return $this->QLib->getFmFileType($id);
    }
    public function showTables(){

    }
    public function loadTransferItems($id){

        $cont=new objectString;

        $req=$this->QLib->getRequisitions('where id='.$id,true);

        $okbtn="";

        for($i=0;$i<count($req);$i++){
            $cont->generalTags('<div class="mesRow" style="font-size:16px;background:#145075;color:#fff;"><div id="inneTitle" >The following items will be transfered to <b>Requisition '.$req[$i]->req_no.$this->QLib->getNextSub($id).'</b></div></div>');

            $list=new open_table;

            $list->setColumnNames(array('No','Description','Qty','Unit'));

            $list->setColumnWidths(array('5%','60%','10%','10%'));

            $cont->generalTags('<div class="splitList" style="width:96%;float:left;margin-left:2%;margin-top:10px;overflow-y:scroll;height:300px;border:1px solid #ddd;">');

            $ri=$this->QLib->getRequestItems('where request_id='.$id.' and description<>\'\' and qty>0 and amount=0');

            for($x=0;$x<count($ri);$x++){
                $list->addItem(array($ri[$x]->item_no,$ri[$x]->item_description,$ri[$x]->item_qty,$ri[$x]->item_unit));
                $okbtn='<div class="yesbtn">Extract Mat. Payment</div>';
            }

            $list->showTable();

            $cont->generalTags($list->toString());

            $cont->generalTags('</div>');

            $cont->generalTags('<div class="q_row"><div class="declbtn">Cancel</div>'.$okbtn.'</div>');

        }

        return $cont->toString();

    }
    public function deleteFmRequest($id){
        $this->QLib->deleteFmRequest($id);
    }
    public function linkFmToRequest($fmrid,$id){
        $this->QLib->linkFmToRequest($fmrid,$id);
    }
    public function foremanRequests($whereclause="",$asC=false){

        $cont=new objectString;

        $site=$this->QLib->getMyActiveSite();

        if($whereclause==""){
            $cont->generalTags('<div class="q_row"><div class="innerTitle">My Requests</div></div>');
        }else{
            $cont->generalTags('<div class="q_row"><div class="popTitle">Scanned Copies</div></div>');
        }
        if($site!=null){

            $list=new open_table;

            $list->setAsCopy($asC);
            //$list->canDeleteRow(true);

            if($whereclause==""){

                $list->setColumnNames(array('No.','Date','Description','Format',' '));

                $list->setColumnWidths(array('10%','15%','30%','25%','10%'));

                $req=$this->QLib->getFMpayment('where user_id='.$this->ud->user_id.' and reqId=0');

            }else{

                $list->setColumnNames(array('No.','Date','Description','Format','Site Name','By',' ','Action'));

                $list->setColumnWidths(array('5%','10%','20%','15%','13%','10%','10%','10%'));

                $req=$this->QLib->getFMpayment('where reqId=0 '.$whereclause);
            }
            $format=array('Image','PDF');

            for($i=0;$i<count($req);$i++){

                if($whereclause==""){
                    $list->addItem(array($i+1,$req[$i]->fm_date,$req[$i]->fm_desc,$format[$req[$i]->fm_format],'<div id="fm_'.$req[$i]->fm_id.'_'.$i.'" class="shwDet"></div>'));
                    $list->addDataRow($this->expandDiv($req[$i]->fm_id, 'Material request posted on ' . $req[$i]->fm_date));
                }else{
                    $list->addItem(array($i+1,$req[$i]->fm_date,$req[$i]->fm_desc,$format[$req[$i]->fm_format],$this->getSiteName($req[$i]->fm_site),$req[$i]->fm_name,'<div id="fm_'.$req[$i]->fm_id.'_'.$i.'" class="shwDet"></div>','<div class="attDiv" id="fmr_'.$req[$i]->fm_id.'"></div>'));
                    $list->addDataRow($this->expandDiv($req[$i]->fm_id, 'Material request posted on ' . $req[$i]->fm_date));
                }
            }

            $list->showTable();

            if($whereclause==""){
                $cont->generalTags('<div class="q_row">'.$list->toString().'</div>');
            }else{
                $cont->generalTags('<div class="q_row" style="width:96%;margin-left:2%;">'.$list->toString().'</div>');
            }

        }else{
            $cont->generalTags('<div class="nFound">No Project Assigned!</div>');
        }

        return $cont->toString();

    }
    public function fmRequestPanel(){

        $cont=new objectString;

        $cont->generalTags('<div class="mpReq" title="Click to link to forman requests">Attach Scanned Copy</div><div class="ldiv">Scanned Copy</div>');

        $cont->generalTags('<div class="thePopW" style="overflow-y:scroll;"></div>');

        return $cont->toString();

    }
    public function newForemanRequests(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row"><div class="innerTitle">Scanned Copy</div></div>');

        $site=$this->QLib->getMyActiveSite();

        if($site!=null){

            $ut=new input;

            $ut->setClass('quince_select');

            $ut->setTagOptions('disabled=disabled');

            $ut->addItems(array(new name_value('From Scanned Document',1),new name_value('From Excel Document',2)));

            $ut->select('sel');

            $cont->generalTags('<div class="q_row" style="margin-bottom:5px;"><div id="label">Upload Type</div><div class="qs_wrap" id="fReq">'.$ut->toString().'</div></div>');

            $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

            $cont->generalTags('<div class="fmu_wrap">');

            $cont->generalTags($this->fmUpload());

            $cont->generalTags('</div>');

        }else{
            $cont->generalTags('<div class="nFound">No Project Assigned!</div>');
        }

        return $cont->toString();

    }
    public function loadFPanel($opt){

        $cont =new objectString;

        if($opt==1){
            $cont->generalTags($this->fmUpload());
        }else{
            $cont->generalTags('<div class="q_row" >'.$this->excelImporter(true,array(),4,true,'<div class="saveFAList" id="sid_11">Save Entry</div>').'</div>');

            $cont->generalTags('<div class="q_row" style="border-bottom:1px solid #dddd"><div id="ReqList"></div></div>');

        }

        return $cont->toString();

    }
    public function fmUpload($accept_type="pdf,image/*"){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row">'.$this->fileUploader('fmUp','fr','application/pdf,image/*','Description').'</div>');

        $cont->generalTags('<div class="q_row"><div id="tFrame"></div></div>');

        return $cont->toString();
    }
    public function saveComponentImage(){
        //get the company prefix
        //check if the directory of the company existe //if it does not then create
        //save the file ,when succesful save alo in the database;

        $this->QLib->saveComponentImage('nFile_copI',explode('_',$_GET['si'])[1]);


    }
    public function saveFMPayment($ftype=0,$desc=""){
        $site=$this->QLib->getMyActiveSite();
        if($site!==null)
            $this->notifyUsers('Construction Manager',array('Construction Manager'=>$site->site_name.': '.$this->ud->user_name.' has posted a request for material.','Subject'=>$site->site_name.': Item Request'),4);
        return $this->QLib->saveFMPayment($ftype,$desc);

    }
    public function saveFmFile($name,$id=0){
        $this->QLib->saveFmFile($name,$id);
    }
    public function deleteItem($opts){

        $op=explode('_',$opts);

        switch(System::getArrayElementValue($op,2)){
            case 0://delete requisition item
                $this->QLib->deleteRequisitionItem(System::getArrayElementValue($op,1));
                $req=$this->QLib->getRequestItems('where id='.System::getArrayElementValue($op,1));
                for($i=0;$i<count($req);$i++)
                    $this->QLib->updateRequestItemNumber($req[$i]->item_requestId);
                break;

            case 1:
                $this->QLib->deleteEquipment(System::getArrayElementValue($op,1));
                break;

            case 2:
                $this->QLib->deletePosition(System::getArrayElementValue($op,1));
                break;

            case 3:
                $this->QLib->deleteALevel(System::getArrayElementValue($op,1));
                break;

            case 4:
                $this->QLib->deleteCompany(System::getArrayElementValue($op,1));
                break;
            case 5:
                $this->QLib->deleteMaterialEst(System::getArrayElementValue($op,1));
                break;
        }

        return "";

    }
    /*public function reqList($addw=" and level5=0"){

		$list=new open_table;

		$req=array();

		if(($this->ud->user_userType>-4)|($this->ud->user_userType==-4)|($this->ud->user_userType==-5)|($this->ud->user_userType<-6)){
		    switch($this->ud->user_userType){

				case 1://GENERAL MANAGER

					$pr=count($this->QLib->getMyProjectIds())>0 ? ' and (project_id='.implode(' or project_id=',$this->QLib->getMyProjectIds()).') ' : "";

					$req=$this->QLib->getRequisitions('where requisition_status=0 '.$addw.' order by id desc');

					$list->setColumnNames(array('MP No.','Date','Site Agent','Cons. Manager','Gen. Manager','Procurement','Accounts','Gen. Manager','Director','Action'));

		            $list->setColumnWidths(array('8%','10%','10%','10%','10%','10%','10%','10%','9%','4%'));

					break;

				case 2://PROCUREMENT

					//$pr=count($this->QLib->getMyProjectIds())>0 ? ' and (project_id='.implode(' or project_id=',$this->QLib->getMyProjectIds()).')' : "";

					$req=$this->QLib->getRequisitions('where requisition_status=0 '.$addw.' order by id desc');

					$list->setColumnNames(array('MP No.','Date','Site Agent','Cons. Manager','Gen. Manager','Procurement','Accounts','Gen. Manager','Director','Action'));

		            $list->setColumnWidths(array('8%','10%','10%','10%','10%','10%','10%','10%','9%','4%'));

					break;

				case 3:

					//$pr=count($this->QLib->getMyProjectIds())>0 ? ' and (project_id='.implode(' or project_id=',$this->QLib->getMyProjectIds()).')' : "";

					$req=$this->QLib->getRequisitions('where requisition_status=0 '.$addw.' order by id desc');

					$list->setColumnNames(array('MP No.','Date','Site Agent','Cons. Manager','Gen. Manager','Procurement','Accounts','Gen. Manager','Director','Action',' '));

		            $list->setColumnWidths(array('8%','10%','10%','10%','10%','10%','8%','8%','9%','8%','4%'));

					break;

				default:
					//echo $this->ud->user_userType;
					$req=null;
					if($this->ud->user_userType==0){
					  $req=$this->QLib->getRequisitions('where (requisition_status=0 or requisition_status=-3)   '.$addw.' order by id desc');
					}else{
					   $req=$this->QLib->getRequisitions('where requisition_status=0 '.$addw.' order by id desc');
					}
					$list->setColumnNames(array('MP No.','Date','Site Agent','Cons. Manager','Gen. Manager','Procurement','Accounts','Gen. Manager','Director','Action'));


		            $list->setColumnWidths(array('8%','10%','10%','10%','10%','10%','10%','10%','9%','4%'));
			}

			for($i=0;$i<count($req);$i++){
			 if($this->ud->user_userType==3){
			 $level0='<div class="ch_dw"></div>';$level1='<div class="ch_d"></div>';$level2='<div class="ch_d"></div>';$level3='<div class="ch_d"></div>';$level4='<div class="ch_d"></div>';$level5='<div class="ch_d"></div>';
			 if($req[$i]->req_level0==1){$level0='<div class="ch_a">'.$req[$i]->req_level1Date.'</div>';$level1='<div class="ch_dw"></div>';}
		     if($req[$i]->req_level1==1){$level1='<div class="ch_a">'.$req[$i]->req_level1Date.'</div>';$level2='<div class="ch_dw"></div>';}
			 if($req[$i]->req_level2==1){$level2='<div class="ch_a">'.$req[$i]->req_level2Date.'</div>';$level3='<div class="ch_dw"></div>';}
			 if($req[$i]->req_level3==1){$level3='<div class="ch_a">'.$req[$i]->req_level3Date.'</div>';$level4='<div class="ch_dw"></div>';}
			 if($req[$i]->req_level4==1){$level4='<div class="ch_a">'.$req[$i]->req_level4Date.'</div>';$level5='<div class="ch_dw"></div>';}
			 if($req[$i]->req_level5==1){$level5='<div class="ch_a">'.$req[$i]->req_level5Date.'</div>';}
			 $proj=$this->QLib->getProject($req[$i]->req_projectId);
			 $list->addItem(array($req[$i]->req_no,$this->statusColor($req[$i]->req_date,'color:#f00;font-weight:bold;',$req[$i]->req_status),'<div class="ch_a">'.$req[$i]->req_date.'</div>',$level0,$level1,$level2,$level3,$level4,$level5,'<div id="rNu_'.$req[$i]->req_id.'" class="vReq" title="Click to manage/view"></div>',$this->reqExract($req[$i]->req_id,$req[$i]->req_level2,$req[$i]->req_level4)));
			 }else{
			   $level0='<div class="ch_dw"></div>';$level1='<div class="ch_d"></div>';$level2='<div class="ch_d"></div>';$level3='<div class="ch_d"></div>';$level4='<div class="ch_d"></div>';$level5='<div class="ch_d"></div>';
			   if($req[$i]->req_level0==1){$level0='<div class="ch_a">'.$req[$i]->req_level0Date.'</div>';$level1='<div class="ch_dw"></div>';}
			   if($req[$i]->req_level1==1){$level1='<div class="ch_a">'.$req[$i]->req_level1Date.'</div>';$level2='<div class="ch_dw"></div>';}
			   if($req[$i]->req_level2==1){$level2='<div class="ch_a">'.$req[$i]->req_level2Date.'</div>';$level3='<div class="ch_dw"></div>';}
			   if($req[$i]->req_level3==1){$level3='<div class="ch_a">'.$req[$i]->req_level3Date.'</div>';$level4='<div class="ch_dw"></div>';}
			   if($req[$i]->req_level4==1){$level4='<div class="ch_a">'.$req[$i]->req_level4Date.'</div>';$level5='<div class="ch_dw"></div>';}
			   if($req[$i]->req_level5==1){$level5='<div class="ch_a">'.$req[$i]->req_level5Date.'</div>';}
			   $proj=$this->QLib->getProject($req[$i]->req_projectId);
			   $list->addItem(array($req[$i]->req_no,$this->statusColor($req[$i]->req_date,'color:#f00;font-weight:bold;',$req[$i]->req_status),'<div class="ch_a">'.$req[$i]->req_date.'</div>',$level0,$level1,$level2,$level3,$level4,$level5,'<div id="rNu_'.$req[$i]->req_id.'" class="vReq" title="Click to manage/view"></div>'));
			 }
			}

		}else{

			$reqs=$this->QLib->getRequisitions('where byId='.$this->ud->user_id." and level2<>1 and requisition_status=0 order by id desc");

			$list->setColumnNames(array('No.','Date','Project',' '));

			$list->setColumnWidths(array('8%','20%','50%','20%'));

			for($i=0;$i<count($reqs);$i++){

			  $proj=$this->QLib->getProject($reqs[$i]->req_projectId);
			  $list->addItem(array($reqs[$i]->req_no,$reqs[$i]->req_date,$proj->project_name,'<div id="rNu_'.$reqs[$i]->req_id.'" class="vReq" title="Click to manage/view"></div>'));
			}

		}

		$list->setHoverColor('#cbe3f8');

		$list->showTable();

		return $list->toString();

	}*/
    public function reqList($addw=" and level5=0"){

        $list=new open_table;

        $req=array();

        $level_t=array('Req No.','Date','Requisition');

        $widths=array('8%','0%','10%');

        $levs=$this->QLib->getALevels("order by thelevel asc");

        for($i=0;$i<count($levs);$i++){
            $level_t[]=$levs[$i]->name;
            $widths[]='10%';
        }

        $level_t[]='Action';

        $widths[]='7%';

        $req=$this->QLib->getRequisitions('where requisition_status=0 '.$addw.' order by id desc');

        $list->setColumnNames($level_t);
        //$list->setColumnNames(array('MP No.','Date','Site Agent','Cons. Manager','Gen. Manager','Procurement','Accounts','Gen. Manager','Director','Action'));

        $list->setColumnWidths($widths);

        //$list->setColumnWidths(array('8%','10%','10%','10%','10%','10%','10%','10%','9%','4%'));




        for($i=0;$i<count($req);$i++){

            $level0='<div class="ch_dw"></div>';$level1='<div class="ch_d"></div>';$level2='<div class="ch_d"></div>';$level3='<div class="ch_d"></div>';$level4='<div class="ch_d"></div>';$level5='<div class="ch_d"></div>';$level6='<div class="ch_d"></div>';

            //print_r(System::nameValueToSimpleArray($this->QLib->getPositionApprovalLevels($this->ud->user_userType),true));

            $levv1=count($levs);///error iko hapo

            if($req[$i]->req_level0==0){$level0='<div class="ch_a">'.$req[$i]->req_level0Date.'asdasd</div>'; $level1='<div class="ch_dw"></div>';}

            for($c=0;$c<count($levs)+1;$c++){
                switch($c){

                    case 1:
                        if($req[$i]->req_level1==1){$level0='<div class="ch_a">'.$req[$i]->req_level1Date.'</div>'; $level1='<div class="ch_dw"></div>';}
                        break;

                    case 2:
                        if($req[$i]->req_level2==1){$level1='<div class="ch_a">'.$req[$i]->req_level2Date.'</div>';$level2='<div class="ch_dw"></div>';}
                        break;

                    case 3:
                        if($req[$i]->req_level3==1){$level2='<div class="ch_a">'.$req[$i]->req_level3Date.'</div>';$level3='<div class="ch_dw"></div>';}
                        break;

                    case 4:
                        if($req[$i]->req_level4==1){$level3='<div class="ch_a">'.$req[$i]->req_level4Date.'</div>';$level4='<div class="ch_dw"></div>';}
                        break;

                    case 5:
                        if($req[$i]->req_level5==1){$level4='<div class="ch_a">'.$req[$i]->req_level5Date.'</div>';}
                        $proj=$this->QLib->getProject($req[$i]->req_projectId);
                        break;

                }
            }

            $listVal=array($req[$i]->req_no,$this->statusColor($req[$i]->req_date,'color:#f00;font-weight:bold;',$req[$i]->req_status),'<div class="ch_a">'.$req[$i]->req_date.'</div>');

            $levv=0;

            for($c=0;$c<count($levs);$c++){
                switch($c){

                    case 0:
                        $listVal[]=$level0;
                        break;

                    case 1:
                        $listVal[]=$level1;
                        break;

                    case 2:
                        $listVal[]=$level2;
                        break;

                    case 3:
                        $listVal[]=$level3;
                        break;

                    case 4:
                        $listVal[]=$level4;
                        break;

                    case 5:
                        $listVal[]=$level5;
                        break;
                }
                $levv+=1;
            }

            $listVal[]='<div id="rNu_'.$req[$i]->req_id.'" class="vReq" title="Click to manage/view"></div>';
            //$listVal[]='';
            //$this->reqExract($req[$i]->req_id,$req[$i]->req_level2,$req[$i]->req_level4)
            $list->addItem($listVal);
            /*}else{
			   $level0='<div class="ch_dw"></div>';$level1='<div class="ch_d"></div>';$level2='<div class="ch_d"></div>';$level3='<div class="ch_d"></div>';$level4='<div class="ch_d"></div>';$level5='<div class="ch_d"></div>';
			   if($req[$i]->req_level0==1){$level0='<div class="ch_a">'.$req[$i]->req_level0Date.'</div>';$level1='<div class="ch_dw"></div>';}
			   if($req[$i]->req_level1==1){$level1='<div class="ch_a">'.$req[$i]->req_level1Date.'</div>';$level2='<div class="ch_dw"></div>';}
			   if($req[$i]->req_level2==1){$level2='<div class="ch_a">'.$req[$i]->req_level2Date.'</div>';$level3='<div class="ch_dw"></div>';}
			   if($req[$i]->req_level3==1){$level3='<div class="ch_a">'.$req[$i]->req_level3Date.'</div>';$level4='<div class="ch_dw"></div>';}
			   if($req[$i]->req_level4==1){$level4='<div class="ch_a">'.$req[$i]->req_level4Date.'</div>';$level5='<div class="ch_dw"></div>';}
			   if($req[$i]->req_level5==1){$level5='<div class="ch_a">'.$req[$i]->req_level5Date.'</div>';}
			   $proj=$this->QLib->getProject($req[$i]->req_projectId);
			   $list->addItem(array($req[$i]->req_no,$this->statusColor($req[$i]->req_date,'color:#f00;font-weight:bold;',$req[$i]->req_status),'<div class="ch_a">'.$req[$i]->req_date.'</div>',$level0,$level1,$level2,$level3,$level4,$level5,'<div id="rNu_'.$req[$i]->req_id.'" class="vReq" title="Click to manage/view"></div>'));
			 }*/
        }

        /*}else{

			$reqs=$this->QLib->getRequisitions('where byId='.$this->ud->user_id." and level2<>1 and requisition_status=0 order by id desc");

			$list->setColumnNames(array('No.','Date','Project',' '));

			$list->setColumnWidths(array('8%','20%','50%','20%'));

			for($i=0;$i<count($reqs);$i++){

			  $proj=$this->QLib->getProject($reqs[$i]->req_projectId);
			  $list->addItem(array($reqs[$i]->req_no,$reqs[$i]->req_date,$proj->project_name,'<div id="rNu_'.$reqs[$i]->req_id.'" class="vReq" title="Click to manage/view"></div>'));
			}

		}*/

        $list->setHoverColor('#cbe3f8');

        $list->showTable();

        return $list->toString();

    }
    private function reqExract($rid,$level2=0,$level4=0){

        $cont=new objectString;
        if(($level2==1)&($level4==0))
            $cont->generalTags('<div class="exMp" id="spid_'.$rid.'" title="Split Requisition"></div>');

        return $cont->toString();

    }
    public function createSubRequisition($parent_req){
        $this->QLib->createSubRequisition($parent_req);
    }
    private function statusColor($val,$color="",$checker=0){
        if($checker!=-3){
            return $val;
        }else{
            return '<div style="float:left;'.$color.'">'.$val.'</div>';
        }
    }
    public function viewRequestItems($id){

        $level=0;$level_value=0;

        $cont=new objectString;

        $editable=true;



        $req=$this->QLib->getRequisitions('where id='.$id);


        $sBut=new input;

        $sBut->setClass('saveFList');

        $sBut->setId('sid_2_'.$id);

        $sBut->setTagOptions('style="float:right;"');

        for($i=0;$i<count($req);$i++){
            $uploadrates="";
            $excelIcon=false;
            $picon='<div class="iprint" id="nprint_'.$req[$i]->req_id.'">Print</div>';
            //$this->QLib->getCurrentLevel($req[$i]->req_id);
            //echo $this->ud->user_userType;
            //echo  count($this->QLib->getALevels());
            //echo $this->QLib->getCurrentLevel($req[$i]->req_id);;

            //echo $this->QLib->getCurrentLevel($req[$i]->req_id);

            //print_r($this->QLib->getActualLevels(System::nameValueToSimpleArray($this->QLib->getPositionApprovalLevels($this->ud->user_userType),true)));
            //----------------------------------------------//
            if(in_array($this->QLib->getCurrentLevel($req[$i]->req_id)-1,$this->QLib->getActualLevels(System::nameValueToSimpleArray($this->QLib->getPositionApprovalLevels($this->ud->user_userType),true)))){

                $levelFinder=System::nameValueToSimpleArray($this->QLib->getPositionApprovalLevels($this->ud->user_userType),true);

                $but="";

                $delete_button="";

                $canApprove=array();

                if(count($levelFinder)>0){
                    $where='where id='.implode(' or id=',$levelFinder);
                    $alevs=$this->QLib->getALevels($where);
                    foreach($alevs as $cn)
                        $canApprove[]=$cn->value;
                }
                //print_r($this->QLib->getPositionApprovalLevels($this->ud->user_userType));

                //$this->QLib->getCurrentLevel($req[$i]->req_id)
                //echo System::getArrayElementValue($levelFinder,$this->QLib->getCurrentLevel($req[$i]->req_id)-1,-1);

                //if(System::getArrayElementValue($levelFinder,$this->QLib->getCurrentLevel($req[$i]->req_id)-1,-1)!=-1){

                if(in_array($this->QLib->getCurrentLevel($req[$i]->req_id)-1,$canApprove)){

                    $sBut->input('submit','savebn','Approve Requisition');
                    //$levelFinder[0]
                    $but=$sBut->toString();
                }



                //CHECK RATE IMPORTER
                if($this->QLib->levelHasPrivilege(System::getArrayElementValue($levelFinder,$this->QLib->getCurrentLevel($req[$i]->req_id)-1,-1),3))
                    $uploadrates=$this->ratesImporter();


                //if($this->QLib->levelHasPrivilege(System::getArrayElementValue($levelFinder,$this->QLib->getCurrentLevel($req[$i]->req_id),-1),-1,6)){

                if($this->positionHasPrivilege($this->ud->user_userType,65) | ($this->ud->user_userType==1)){

                    $del=new input;
                    $del->setClass('delMp');
                    $del->setTagOptions('title="Discard this Requisition"');
                    $del->setId('dl_'.$req[$i]->req_id.'_'.$req[$i]->req_projectId);
                    $del->input('button','deReq','X Discard');
                    $delete_button=$del->toString();
                }
                $but=$delete_button.$but;


            }else{
                $but="";
            }

            $pr=$this->QLib->getProject($req[$i]->req_projectId);
            //print_r($id);
            if($level>-1){

                $cont->generalTags('<div class="q_row"><div class="innerTitle">Requisition - <b>'.$pr->project_name.'</b></div>'.$this->attachButton($req[$i]->req_projectId,$req[$i]->req_isLinked,$req[$i]->req_id).'<div class="bBut" id="bc_'.$req[$i]->req_projectId.'">Back</div>'.$but.$picon.'</div>');

                $cont->generalTags('<div class="q_row" style="text-align:center;font-size:14px;text-decoration:underline;">Materials Payment No '.$req[$i]->req_no.' '.$req[$i]->req_date.'</div>');

                // $cont->generalTags($uploadrates);

                $cont->generalTags('<div class="q_row" style="text-align:left;font-size:14px;">Generated By: <b>'.$req[$i]->req_byName.'</b></div>');

            }else{
                //if(($req[$i]->req_level4==0))
                //$picon="";
                $bck='';
                //if($this->ud->user_userType==-4)
                $bck='<div class="bBut" id="bc_'.$req[$i]->req_projectId.'">Back</div>';

                $cont->generalTags('<div class="q_row"><div class="innerTitle">Material Requisition - '.$pr->project_name.'</div>'.$bck.$but.$picon.'</div>');

                $cont->generalTags('<div class="q_row" style="text-align:center;font-size:14px;text-decoration:underline;">Material Requisition No '.$req[$i]->req_no.'</div>');

            }

            $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

            $cont->generalTags('<div class="thePop">');

            //$cont->generalTags('');

            $cont->generalTags('</div>');

            $cont->generalTags('<div class="q_row">'.$this->requestItems($req[$i]->req_id,$level,$level_value,$editable,$req[$i],$excelIcon).'</div>');

            if($this->QLib->positionHasPrivilege($this->ud->user_userType,61)|($this->ud->user_id==1)){

                if(($this->QLib->positionHasPrivilege($this->ud->user_userType,60)|($this->ud->user_id==1))&(!$this->QLib->fileExists($req[$i]->req_id))){

                    $cont->generalTags('<div class="q_row"><div class="innerTitle" style="font-size:16px;"> Upload Signed Copy</div></div>');

                    $cont->generalTags('<div class="q_row">'.$this->fileUploader('pdfUp',$req[$i]->req_id,'application/pdf,image/*').'</div>');

                }

                $cont->generalTags('<div class="q_row"><div class="innerTitle">Signed Scanned Copy</div></div>');

                $cont->generalTags('<div class="q_row" id="tFrame">');

                $cont->generalTags($this->QLib->fileExists($req[$i]->req_id));

                if($this->QLib->fileExists($req[$i]->req_id)){
                    if(($req[$i]->req_extType=='pdf')|($req[$i]->req_extType=='')){
                        $cont->generalTags($this->showPdf($req[$i]->req_id,"mpPdf"));
                    }else{
                        $cont->generalTags($this->showImage($req[$i]->req_id,'mpImage'));
                    }
                }
                $cont->generalTags('</div>');

            }

        }

        return $cont->toString();

    }
    public function attachButton($site_id,$isLinked=0,$rid=0){

        $cont=new objectString;

        $sites=$this->QLib->getSites('where project_id='.$site_id);

        for($i=0;$i<count($sites);$i++){
            if($isLinked==0){
                if($this->ud->user_userType==0){
                    $cont->generalTags('<div class="attachF noAFile" id="st_'.$sites[$i]->site_id.'_'.$rid.'">Attach File</div><div class="ldiv" style="margin-top:0px;">View File</div>');
                }else{
                    $cont->generalTags('<div class="ldiv2" style="margin-top:0px;display:block;">No Attachment</div>');
                }
            }else{
                $cont->generalTags('<div class="ldiv" style="margin-top:0px;display:block;" id="fmp_'.$this->QLib->getFMPaymentId($rid).'">View Attached</div>');
            }
            $cont->generalTags('<div class="thePopW" style="overflow-y:scroll;"></div>');
        }
        return $cont->toString();

    }
    public function ratesImporter(){
        $cont=new objectString;

        $cont->generalTags('<div class="q_row" style="margin-bottom:5px;"><div class="excelImp">Import Rates From Excel File</div></div>');

        $cont->generalTags('<div class="q_row" style="margin-top:0px;"><div class="impRateWrap">
		<div class="impInnerWrap">
		<div class="q_row">'.$this->excelImporter(true,array(),4,true,'<div class="saveFList" style="display:none" id="sid_8"></div>'.$this->rateBar()).'</div>
		<div id="ReqList" style="overflow:scroll;float:left;width:100%;height:180px;"></div>
		</div>
		</div></div>');

        return $cont->toString();
    }
    public function rateBar(){
        $cont=new objectString;

        $cont->generalTags('<div class="rateWrap"><div class="label">Description Column</div><input type="text" value="" class="descField" style="width:30px;"/><div class="label">Rate Column</div><input type="text" value="" class="rateField" style="width:30px;"/><div class="label">Remarks Column</div><input type="text" value="" class="remarkField" style="width:30px;"/><div class="loadRate">Load Rates</div>
		</div><div class="clM2">X</div>');

        return $cont->toString();
    }
    public function uploadMpFile($id){

        $this->QLib->uploadMpFile('nFile_'.$id,$id);

    }
    public function uploadLabourFile($id,$dt){

        $this->QLib->uploadLabourFile('nFile_'.$id,$dt);

    }
    public function showImage($id,$opt='pdfDoc',$imgExt=0,$showDw=true,$tid="scanImg"){

        $cont=new objectString();

        if($showDw)
            $cont->generalTags('<div class="q_row" style="margin-bottom:10px;float:left;"><a class="form_button" href="?tp='.$id.'&ptyp='.$opt.'&fd=yes&ext='.$imgExt.'" style="float:left;">Download</a></div>');

        $strIm="";
        //if($tid!='scanImg')
        $strIm='style="width:100%;float:left;"';

        $cont->generalTags('<div id="'.$id.'" style="float:left;"><img style="float:left;margin:3px 0px" src="?tp='.$id.'&ptyp='.$opt.'&ext='.$imgExt.'" '.$strIm.'></div>');

        return $cont->toString();

    }
    public function savePrintPreview($style,$colName,$data,$mdata,$mstyle){
        $this->QLib->savePrintPreview($style,$colName,$data,$mdata,$mstyle);
    }
    public function showPreview($btnId=""){
        return '<iframe class="nframe" src="?tp=1&ptyp=pl&pid='.$this->ud->user_id.'&ttls='.$btnId.'"></iframe>';
    }
    public function processExcelFilePrev($colnames,$data){

        $excel= new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $excel->setActiveSheetIndex(0);

        $rowCount=1;
        $excCols=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        //add list columns
        $headerData=json_decode($colnames);
        for($i=0;$i<count($headerData);$i++)
            $excel->getActiveSheet()->setCellValue($excCols[$i].$rowCount,$headerData[$i]);
        $rowCount+=1;

        $lData=json_decode($data);

        for($i=0;$i<count($lData);$i++){
            for($x=0;$x<count($lData[$i]);$x++){
                $excel->getActiveSheet()->setCellValue($excCols[$x].$rowCount,$lData[$i][$x]);
            }
            $rowCount+=1;
        }

        $xwriter=\PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel,'Xlsx');

        $xwriter->save(dirname(__FILE__).'/../../../printpreview/exc_'.$this->ud->user_id);

    }
    public function excelDownloadPreview(){
        $cont=new objectString;

        $cont->generalTags('<div class="excelProlog">Your file is ready for download. Click below to download</div>');

        $cont->generalTags('<a class="dwlBtn" href="?tp=1&ptyp=dwEx">Click Here Download Excel File</div>');

        return $cont->toString();
    }
    public function showPdf($id,$opt='pdfDoc'){

        $cont=new objectString;
        //$cont->generalTags('asdsad');
        if($this->QLib->fileExists($id,$opt))
            $cont->generalTags('<iframe src="?tp='.$id.'&ptyp='.$opt.'" style="width:100%;float:left!important;border:1px solid #ddd;height:500px;margin-bottom:10px;"></iframe>');

        return $cont->toString();

    }
    public function deleteMaterialPayment($rid,$pid){

        $req=$this->QLib->getRequisitions('where project_id='.$pid.' order by id desc limit 1');
        for($i=0;$i<count($req);$i++){
            if($req[$i]->req_id==$rid){
                $this->QLib->deleteRequisition($rid);
                $this->QLib->deleteRequisitionitems($rid);
            }else{
                $this->QLib->changeRequisitionStatus($rid);
                $this->QLib->deleteRequisitionitems($rid);
            }
        }

    }
    public function fileUploader($tid="",$reqId,$accept="",$title=""){

        $cont=new objectString;

        $cont->generalTags('<form id="nFileform" action="" method="post" enctype="multipart/form-data" >');

        if($title!=""){

            $desc=new input;

            $desc->setClass('txtField');

            $desc->setId('nfup');

            $desc->input('text','nFileDesc');

            $cont->generalTags('<div class="q_row" style="margin-bottom:20px;margin-left:0px;padding-top:10px;"><div id="label">'.$title.'</div>'.$desc->toString().'</div>');

        }

        $cont->generalTags('<label class="fLabel" id="'.$tid.'" title="Select PDF File" for="nFile">Select File</label><div class="fUp">Upload File</div>');

        $cont->generalTags('<div class="fileSpan"><input type="file" name="nFile_'.$reqId.'" id="nFile" accept="'.$accept.'"></div>');

        $cont->generalTags('</form>');

        $cont->generalTags('<div class="lstatus" style="float:left;width:100%;"></div>');

        return $cont->toString();

    }
    public function requestItems($id,$level=0,$value=0,$editable=true,$theReq=null,$excelIcon=false){


        //
        $cont=new objectString;

        $addOp='';

        $list=new open_table;

        $list->setHoverColor('#cbe3f8');

        if($excelIcon)
            $list->enablePrintExport(true,array(),array(),1);

        $amount=0;

        $levelFinder=System::nameValueToSimpleArray($this->QLib->getPositionApprovalLevels($this->ud->user_userType),true);
//System::nameValueToSimpleArray($this->QLib->getPositionApprovalLevels($this->ud->user_userType),true);

        //echo $this->QLib->getCurrentLevel($theReq->req_id);

        $levelAction=$this->QLib->getCurrentLevel($theReq->req_id);

        //echo $this->QLib->getCurrentLevel($theReq->req_id);


        if(in_array($this->QLib->getCurrentLevel($theReq->req_id)-1,$this->QLib->getActualLevels(System::nameValueToSimpleArray($this->QLib->getPositionApprovalLevels($this->ud->user_userType),true)))){

            $editables=array();
            //-----------------------------------SET EDITABLES-----------------------------------------------

            $lpriv=array();


            if(count($this->QLib->getPositionLevels($this->ud->user_userType))>1){

            }else{
                //Common error in mysql when fetching this arr

                $det=array();$det=$this->QLib->getPositionLevels($this->ud->user_userType);
                if(count($det) !=0){
                    $lpriv=array_keys($this->QLib->getMyLevelDynamicPrivileges(System::getArrayElementValue($this->QLib->getPositionLevels($this->ud->user_userType),0)));
                }
                // print_r(count());


                // print_r($theReq);
            }

            if(in_array(0,$lpriv))
                $editables[]=1;

            if(in_array(2,$lpriv))
                $editables[]=3;


            if(in_array(3,$lpriv)){
                $editables[]=4;
                $list->setColumnFormular(2,4,5);
            }
            if(in_array(4,$lpriv))
                $editables[]=6;


            if(in_array(9,$lpriv)){
                $editables[]=2;
                $list->setColumnFormular(2,4,5);
            }

            $list->setEditableColumns($editables);

            //$list->setEditableColumns(array(1,2,3));

            $list->setNumberColumns(array(4));
            //---------------------------------END SET EDITABLE COLUMNS--------------------------------------


            if(in_array(7,$lpriv))
                $list->canDeleteRow(true,0);

            $list->setRightAlign(array(4,5));

        }
        if(!$this->QLib->levelHasPrivilege($levelFinder,8)){
            $list->setColumnNames(array('No.','Description','Qty','Unit'));
            $list->setColumnWidths(array('15%','50%','15%','15%'));
        }else{
            $list->setColumnNames(array('No.','Description','Qty','Unit','Rate','Amount','Remarks'));	$list->setColumnWidths(array('15%','20%','12%','10%','10%','10%','20%'));
        }


        $ri=$this->QLib->getRequestItems("where request_id='".$id."' limit 1");

        for($k=0;$k<count($ri);$k++)
            if($ri[$k]->item_no==0){
                $this->QLib->updateRequestItemNumber($id);
            }

        $reqI=$this->QLib->getRequestItems("where request_id='".$id."' order by lno asc");
        for($i=0;$i<count($reqI);$i++){
            if(!$this->QLib->levelHasPrivilege($levelFinder,8)){
                $list->addItem(array($this->filterNegative($reqI[$i]->item_no),$reqI[$i]->item_description,$this->filterNegative($reqI[$i]->item_qty),$reqI[$i]->item_unit),$reqI[$i]->item_id);
                $amount+=$reqI[$i]->item_amount;
            }else{

                $tNum=number_format($reqI[$i]->item_amount,2);

                if($this->filterNegative($reqI[$i]->item_no)==""){
                    echo $this->filterNegative($reqI[$i]->item_no);
                    $reqI[$i]->item_rate="";
                    $reqI[$i]->item_amount="";
                    $tNum=$reqI[$i]->item_amount;

                }
                $list->addItem(array($this->filterNegative($reqI[$i]->item_no),$reqI[$i]->item_description,$this->filterNegative($reqI[$i]->item_qty),$reqI[$i]->item_unit,$reqI[$i]->item_rate,$tNum,$reqI[$i]->item_remarks.""),$reqI[$i]->item_id);
                $amount+=$reqI[$i]->item_amount;
            }

        }
        $addOp='<div class="Rtotal">TOTAL '.number_format($amount,2).'</div>'.$addOp;


        /*case 1:
				//echo "ss";
				$ri=$this->QLib->getRequestItems('where request_id='.$id.' limit 1');

				for($k=0;$k<count($ri);$k++)
					if($ri[$k]->item_no==0){
						$this->QLib->updateRequestItemNumber($id);
					}

				$reqI=$this->QLib->getRequestItems('where request_id='.$id.' order by lno asc');

				if((($theReq->req_level1==0)|($theReq->req_level1==1))&($theReq->req_level2==0)){
				  $list->setEditableColumns(array(1,2,3));
				  $list->setNumberColumns(array(2,4));
				}else{
					if(($theReq->req_level3==1)&($theReq->req_level4==0)){
						$list->setEditableColumns(array(1,2,3,4));
				        $list->setNumberColumns(array(2,4));
						$list->setColumnFormular(2,4,5);
					}
				}

				$list->setRightAlign(array(4,5));

				if($theReq->req_level4==0)
				$list->canDeleteRow(true,0);

				if($theReq->req_level1==0)
				$addOp='<div id="form_row"><div class="addBtn">+</div></div>';

				$list->setColumnNames(array('No.','Description','Qty','Unit','Rate','Amount','Remarks'));		$list->setColumnWidths(array('15%','30%','12%','10%','10%','10%','10%'));

				for($i=0;$i<count($reqI);$i++){
				  $tNum=$reqI[$i]->item_amount==""? 0: number_format($reqI[$i]->item_amount,2);
				 if($this->filterNegative($reqI[$i]->item_no)==""){
					$reqI[$i]->item_rate="";
					$reqI[$i]->item_amount="";
					$tNum=$reqI[$i]->item_amount;
				 }
			     $list->addItem(array($this->filterNegative($reqI[$i]->item_no),$reqI[$i]->item_description,$this->filterNegative($reqI[$i]->item_qty),$reqI[$i]->item_unit,$reqI[$i]->item_rate,$tNum,$reqI[$i]->item_remarks.""),$reqI[$i]->item_id);
				 $amount+=$reqI[$i]->item_amount;
		        }
				$addOp='<div class="Rtotal">TOTAL '.number_format($amount,2).'</div>'.$addOp;
				break;

		    case 2:
				//if((($value==0)&($editable))|($editable)){
				if(($theReq->req_level3==0)&($theReq->req_level1==1)){
				  $list->canDeleteRow(true);
				  $list->setEditableColumns(array(1,2,3,4,6));
				  $list->setNumberColumns(array(2,4));
				  $list->setColumnFormular(2,4,5);
				  $addOp='<div id="form_row"><div class="addBtn">+</div></div>';
				}
                $list->setRightAlign(array(4,5));

				$list->setColumnNames(array('No.','Description','Qty','Unit','Rate','Amount','Remarks'));	$list->setColumnWidths(array('15%','30%','12%','10%','10%','10%','10%'));

				$ri=$this->QLib->getRequestItems('where request_id='.$id.' limit 1');

				for($k=0;$k<count($ri);$k++)
					if($ri[$k]->item_no==0){
						$this->QLib->updateRequestItemNumber($id);
					}

				$reqI=$this->QLib->getRequestItems('where request_id='.$id.' order by lno asc');
				for($i=0;$i<count($reqI);$i++){
				 $tNum=number_format($reqI[$i]->item_amount,2);
				 if($this->filterNegative($reqI[$i]->item_no)==""){
					$reqI[$i]->item_rate="";
					$reqI[$i]->item_amount="";
					$tNum=$reqI[$i]->item_amount;
				 }

			     $list->addItem(array($this->filterNegative($reqI[$i]->item_no),$reqI[$i]->item_description,$this->filterNegative($reqI[$i]->item_qty),$reqI[$i]->item_unit,$reqI[$i]->item_rate,$tNum,$reqI[$i]->item_remarks.""),$reqI[$i]->item_id);
				 $amount+=$reqI[$i]->item_amount;
		        }
				$addOp='<div class="Rtotal">TOTAL '.number_format($amount,2).'</div>'.$addOp;
				break;

			 case 3:
				if((($value==0)&($editable))|($editable)){
				  $list->canDeleteRow(true);
				  $list->setEditableColumns(array(1,2,3,4,6));
				  $list->setNumberColumns(array(2,4));
				  $list->setColumnFormular(2,4,5);
				}
				$list->setRightAlign(array(4,5));
				$addOp='<div id="form_row"><div class="addBtn">+</div></div>';
				$list->setColumnNames(array('No.','Description','Qty','Unit','Rate','Amount','Remarks'));		$list->setColumnWidths(array('15%','30%','12%','10%','10%','10%','10%'));

				$ri=$this->QLib->getRequestItems('where request_id='.$id.' limit 1');

				for($k=0;$k<count($ri);$k++)
					if($ri[$k]->item_no==0){
						$this->QLib->updateRequestItemNumber($id);
					}

				$reqI=$this->QLib->getRequestItems('where request_id='.$id.' order by lno asc');
				for($i=0;$i<count($reqI);$i++){
				 if($this->filterNegative($reqI[$i]->item_no)==""){
					$reqI[$i]->item_rate="";
					$reqI[$i]->item_amount="";
				 }
			     $list->addItem(array($this->filterNegative($reqI[$i]->item_no),$reqI[$i]->item_description,$this->filterNegative($reqI[$i]->item_qty),$reqI[$i]->item_unit,$reqI[$i]->item_rate,$reqI[$i]->item_amount,$reqI[$i]->item_remarks.""),$reqI[$i]->item_id);
				 $amount+=$reqI[$i]->item_amount;
		        }
				$addOp='<div class="Rtotal">TOTAL '.number_format($amount,2).'</div>'.$addOp;
			 break;


			case 4:
				$list->setColumnNames(array('No.','Description','Qty','Unit','Rate','Amount','Remarks'));		$list->setColumnWidths(array('15%','30%','12%','10%','10%','10%','10%'));
			    $list->setRightAlign(array(4,5));

				$ri=$this->QLib->getRequestItems('where request_id='.$id.' limit 1');

				for($k=0;$k<count($ri);$k++)
					if($ri[$k]->item_no==0){
						$this->QLib->updateRequestItemNumber($id);
					}

				$reqI=$this->QLib->getRequestItems('where request_id='.$id.' order by lno asc');
				for($i=0;$i<count($reqI);$i++){
				 if($this->filterNegative($reqI[$i]->item_no)==""){
					$reqI[$i]->item_rate="";
					$reqI[$i]->item_amount="";
				 }
			     $list->addItem(array($this->filterNegative($reqI[$i]->item_no),$reqI[$i]->item_description,$this->filterNegative($reqI[$i]->item_qty),$reqI[$i]->item_unit,$reqI[$i]->item_rate,$reqI[$i]->item_amount,$reqI[$i]->item_remarks.""),$reqI[$i]->item_id);
				 $amount+=$reqI[$i]->item_amount;
		        }
				$addOp='<div class="Rtotal">TOTAL '.number_format($amount,2).'</div>'.$addOp;
				break;

			case 5: case -4:
				$list->setColumnNames(array('No.','Description','Qty','Unit','Rate','Amount','Remarks'));		$list->setColumnWidths(array('15%','30%','12%','10%','10%','10%','10%'));
			    $list->setRightAlign(array(4,5));

				$ri=$this->QLib->getRequestItems('where request_id='.$id.' limit 1');

				if($this->ud->user_userType==5){
					$list->setEditableColumns(array(3));
				}

				for($k=0;$k<count($ri);$k++)
					if($ri[$k]->item_no==0){
						$this->QLib->updateRequestItemNumber($id);
					}

				$reqI=$this->QLib->getRequestItems('where request_id='.$id.' order by lno asc');
				for($i=0;$i<count($reqI);$i++){
				 if($this->filterNegative($reqI[$i]->item_no)==""){
					$reqI[$i]->item_rate="";
					$reqI[$i]->item_amount="";
				 }
			     $list->addItem(array($this->filterNegative($reqI[$i]->item_no),$reqI[$i]->item_description,$this->filterNegative($reqI[$i]->item_qty),$reqI[$i]->item_unit,$reqI[$i]->item_rate,$reqI[$i]->item_amount,$reqI[$i]->item_remarks.""),$reqI[$i]->item_id);
				 $amount+=$reqI[$i]->item_amount;
		        }
				$addOp='<div class="Rtotal">TOTAL '.number_format($amount,2).'</div>'.$addOp;
				break;

			    default:
				$list->setColumnNames(array('No.','Description','Qty','Unit'));		$list->setColumnWidths(array('15%','40%','12%','10%'));
			    $list->setRightAlign(array(4,5));

				$ri=$this->QLib->getRequestItems('where request_id='.$id.' limit 1');

				for($k=0;$k<count($ri);$k++)
					if($ri[$k]->item_no==0){
						$this->QLib->updateRequestItemNumber($id);
					}

				$reqI=$this->QLib->getRequestItems('where request_id='.$id.' order by lno asc');
				for($i=0;$i<count($reqI);$i++){
				 if($this->filterNegative($reqI[$i]->item_no)==""){
					$reqI[$i]->item_rate="";
					$reqI[$i]->item_amount="";
				 }
			     $list->addItem(array($this->filterNegative($reqI[$i]->item_no),$reqI[$i]->item_description,$this->filterNegative($reqI[$i]->item_qty),$reqI[$i]->item_unit));
				 $amount+=$reqI[$i]->item_amount;
		        }
				$addOp='';//'<div class="Rtotal">TOTAL '.number_format($amount,2).'</div>'.$addOp;
				break;*/


        //}

        //$list->setColumnNames(array('No.','Description','Qty','Unit'));

        //$list->setColumnWidths(array('15%','50%','15%','15%'));


        $list->showTable();

        $cont->generalTags($list->toString());

        $cont->generalTags($addOp);

        return $cont->toString();

    }
    public function getActualLevels($leveIds){

        $where="";

        $levals=array();

        if(count($leveIds)>0){
            $where="where id=".implode(" or id=",$leveIds);
        }

        $levs=$this->QLib->getAlevels($where);

        foreach($levs as $lev)
            $levals[$lev->value]=$lev->other;

        return $levals;

    }
    public function getALevels($where){
        return $this->QLib->getAlevels($where);
    }
    public function showLabour(){

        $cont=new objectString;

        if($this->ud->user_userType==-6){
            // $cont->generalTags('<div class="q_row"><div class="innerTitle">Manage/View Labour</div></div>');
        }else{

            $cont->generalTags('<div class="q_row"><div class="innerTitle">View Labour Entries</div></div>');

        }

        $this->QLib->changeAlertStatus($this->ud->user_id,10);

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags($this->addResultsBar());

        $qt=new input;

        $qt->setClass('labour_date');

        $qt->input('text','qdate','');

        $sites=new input;

        $sites->setClass('quince_select');

        $sites->addItem(-1,'Select Site');

        $sts=$this->QLib->getSites('');
        //print_r($sts);
        for($i=0;$i<count($sts);$i++)
            $sites->addItem($sts[$i]->site_id,$sts[$i]->site_name);

        $sites->select('sSite');

        $selSite='<div id="label">Select Site</div><div class="qs_wrap" id="lSites">'.$sites->toString().'</div>';

        $site=$this->QLib->getMyActiveSite();

        $vType=new input;

        $vType->addItems(array(new name_value('Summary',1),new name_value('Detailed',2),new name_value('Scanned Copy',3)));

        $vType->setClass('l_select');

        $vType->select('sel');

        $but='<div class="res_button">Reset</div>';

        //echo $this->QLib->positionHasPrivilege($this->ud->user_userType,70);

        if($this->QLib->positionHasPrivilege($this->ud->user_userType,70)){

            if($site!=null){
                $selSite='<input type="hidden" value="'.$site->site_id.'" class="quince_select" />';
                $reset="";
                //echo $this->QLib->positionHasPrivilege($this->ud->user_userType,70);
                if($this->QLib->positionHasPrivilege($this->ud->user_userType,17))
                    $reset='<div class="resBtn" title="Delete Entry">Request Deletion</div>';

                $cont->generalTags('<div id="form_row" style="border-bottom:1px solid #ddd;padding-bottom:20px;">'.$selSite.'<div id="label" style="margin-left:12px;">Select Date</div>'.$qt->toString().'<div id="label" style="margin-left:50px;">View Type</div><div class="qs_wrap">'.$vType->toString().'</div>'.$reset.'</div>');

            }else{
                $cont->generalTags('<div id="form_row" style="font-size:14px!important;text-align:center;">No labour data available!</div>');
            }
        }else{

            $cont->generalTags('<div id="form_row" style="border-bottom:1px solid #ddd;padding-bottom:10px;float:left;">'.$selSite.'<div id="label" style="margin-left:12px;">Select Date</div>'.$qt->toString().'<div id="label" style="margin-left:50px;">View Type</div><div class="qs_wrap">'.$vType->toString().'</div></div>');

        }


        $cont->generalTags('<div class="lForm" style="width:100%;float:left;overflow:hidden;"></div>');

        $cont->generalTags('</div>');

        return $cont->toString();

    }

    //------------------------------------------SHOW LABOUR REPORT---------------------------------------
    public function showLabourReports(){

        $cont=new objectString();

        $cont->generalTags('<div id="form_row"><div class="innerTitle">View Labour</div></div>');

        $frmDate=new input;

        $frmDate->setClass('quince_date');

        $frmDate->input('text','frmDate','');

        $toDate=new input;

        $toDate->setClass('quince_date');

        $toDate->input('text','frmDate','');

        $cont->generalTags('<div class="form_row" id="vRow"><div id="label" style="width:70px;font-size:16px;">Select Site</div>'.$this->searchInput('site').'<div id="label" style="margin-left:40px;width:70px;font-size:16px;float:left!important;">From Date</div>'.$frmDate->toString().'<div id="label" style="margin-left:40px;width:70px;font-size:16px;float:left!important;">To Date</div>'.$toDate->toString().'</div>');

        $cont->generalTags('<div class="showRepInner"></div>');

        return $cont->toString();

    }
    //------------------------------------------END LABOUR REPORT-------------------------------------------
    //------------------------------------------VIEW OVERALL REPORTS----------------------------------------
    public function viewOverallReport($site_id=0){

        $cont=new objectString;

        if($site_id>0){

            $cont->generalTags('<div class="q_row"><div class="innerTitle">Overall Summary For - <b>'.$this->getSiteName($site_id).'</b></div></div>');

            $cont->generalTags($this->showOverallList($site_id));

        }

        return $cont->toString();

    }
    public function showOverallList($theId=0){

        $whereIncome="where site_id=-1";

        $whereOffice="where proc_id=-1";

        $whereLabour="where site_id=-1";

        $whereRequisition="where request_id=-1";

        if($theId !=0){
            $whereIncome=' where site_id='.$theId;
        }

        $wherePettyCash="where site_id=-1";

        if($theId !=0){
            $wherePettyCash=' where site_id='.$theId;
        }

        $whereOffice=" where proc_id=-1";
        if($theId !=0){

            $ids=$this->QLib->getDbIds('pr_officeproc',"where site_id='".$theId."'");
            if(count($ids)>0){
                $whereOffice=" where proc_id=".implode(' or proc_id=',$ids);
            }

        }

        if($theId>0){
            $whereRequisition=" where request_id=-1";

            $levels=$this->getALevels('');

            $ids=$this->QLib->getDbIds('pr_requisitions','where site_id='.$theId." and level".count($levels)."=1 ");

            if(count($ids)>0){

                $whereRequisition=" where request_id=".implode(' or request_id=',$ids)." ";
            }

        }

        if($theId>0){

            $whereLabour="where site_id=".$theId;

        }

        $siteId="";

        $list=new open_table;

        $list->setRightAlign(array(1,3));

        $list->setColumnNames(array('Description','Amount','Description','Amount','%age'));

        $list->setColumnWidths(array('29%','20%','20%','20%','10%'));

        //print_r($whereRequisition);


        $reqtotal=$this->QLib->getTotals('pr_requisitionitems','amount',$whereRequisition,2);

        $income=$this->QLib->getTotals('pr_income','amount',$whereIncome);

        $project=$this->QLib->getProjects("where id=".$theId)[0];

        $pettyCash=$this->QLib->getTotals('pr_pettycash','amount',$wherePettyCash);

        $office_req=$this->QLib->getTotals('pr_opitems','amount',$whereOffice);

        $labour=$this->QLib->getTotals('pr_labour','wages',$whereLabour);

        $total=($reqtotal+$office_req+$labour)==0 ? 1 : $reqtotal+$office_req+$labour;

        //@linus Wahome changes
        $list->addItem(array('Project Value',number_format($project->project_budget,2),'Material /Labour',number_format($reqtotal,2),(round(($reqtotal/$total),2)*100)."%"));

        $list->addItem(array('Total Income',number_format($income,2),'Expenses',number_format($office_req,2),(round(($office_req/$total),2)*100).'%'),$theId);

        $list->addItem(array(' ','','Labour',number_format($labour,2),(round(($labour/$total),2)*100).'0%'));

        $list->addItem(array(' ',' ','<b class="a3-text-blue">Profit / Loss </b>',number_format($project->project_budget-($reqtotal+$office_req+$labour)),''));

        $list->addItem(array(' ',' ','<b class="a3-text-orange">Cash Flow</b> ',number_format($income-($reqtotal+$office_req+$labour)),(($income-($reqtotal+$office_req+$labour)/$total)*100).'%'));

        //@linus changes
        //linus changes @3 time
        $list->addItem(array('Total Received Income',number_format($income,2),'Total Running Expenses',number_format(($reqtotal+$office_req+$labour),2),''),0,array('font-weight:bold','font-weight:bold','font-weight:bold','font-weight:bold'));

        $list->showTable();

        return $list->toString();

    }
    //------------------------------------------END OVERALL REPORTS-----------------------------------------
    public function createProject(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row" style="margin-top:10px;margin-bottom:10px;"><div class="innerTitle">New Project</div></div>');

        $cont->generalTags($this->addResultsBar());

        $title=new input;

        $title->setId('proTitle');

        $title->setClass('txtField');

        $title->input('text','title','');

        $cont->generalTags('<div class="q_row"><div id="label">Project Title</div>'.$title->toString().'</div>');

        //$cont->generalTags('<div class="q_row"><div id="label">Client</div><div class="txtDiv">Not Specified</div ><div class="sIcon" title="Find Client"></div><div class="sc">'.$this->searchContent().'</div></div>');

        $cont->generalTags('<div class="q_row"><div id="label">Description</div><textarea class="txtField" id="txtDesc"></textarea></div>');

        $sel=new input();

        $usr=$this->um->getUsers('where user_type=1');

        $sel->setId('proManager');

        $sel->addItem(-1,'Select Manager');

        for($i=0;$i<count($usr);$i++){
            $sel->addItems(array(new name_value($usr[$i]->user_name,$usr[$i]->user_id)));
        }

        $sel->setClass('quince_select');

        $sel->select('pm');

        //$cont->generalTags('<div class="q_row"><div id="label">Manager</div>'.$this->searchInput('proManager').'</div>');

        //div id="proMan" class="qs_wrap">'.$sel->toString().'</div>

        //$cont->generalTags('<div class="q_row"><div id="label">Description</div><textarea class="pdesc"></textarea></div>');

        $pc=new input;

        $pc->setId('clerk');

        $pc->setClass('quince_select');

        $usr=$this->um->getUsers('where user_type=0');

        $pc->setId('clerk');

        $pc->addItem(-1,'Select Clerk');

        for($i=0;$i<count($usr);$i++){
            $pc->addItems(array(new name_value($usr[$i]->user_name,$usr[$i]->user_id)));
        }

        $pc->select('clerk');

        //$cont->generalTags('<div class="q_row"><div id="label">Store Clerk</div><div id="qs_clerk" class="qs_wrap">'.$pc->toString().'</div></div>');

        //$cont->generalTags('<div class="q_row"><div id="label">Store Clerk</div>'.$this->searchInput('qs_clerk').'</div>');



        $start=new input;

        $start->setClass('quince_date');

        $start->setId('proStart');

        $start->input('text','start','');

        $cont->generalTags('<div class="q_row"><div id="label">Starts</div>'.$start->toString().'</div>');

        $endDate=new input;

        $endDate->setClass('quince_date');

        $endDate->setId('proEnd');

        $endDate->input('text','endDate');

        $cont->generalTags('<div class="q_row"><div id="label">Ends</div>'.$endDate->toString().'</div>');

        $loc=new input;

        $loc->setClass('txtField');

        $loc->setId('proLocation');

        $loc->input('text','title','');

        $cont->generalTags('<div class="q_row"><div id="label">Location</div>'.$loc->toString().'</div>');

        $loc=new input;

        $loc->setClass('txtField');

        $loc->setId('proBudget');

        $loc->input('text','title','');

        $cont->generalTags('<div class="q_row"><div id="label">Budget</div>'.$loc->toString().'</div>');

        $sub=new input;

        $sub->setId('s_proj');

        $sub->setClass('saveData');

        $sub->input('submit','saven','Create Project');

        $cont->generalTags('<div class="q_row">'.$sub->toString().'</div>');

        return $cont->toString();

    }
    public function loadLabour($copyNo=-1){

        $cont=new objectString;

        $site=0;
        $type=0;

        //print_r($_POST);

        if(isset($_POST['siteId'])){
            if($_POST['siteId']==0){
                $site=System::getArrayElementValue($_POST,'uid',0);
            }else{
                $site=$_POST['siteId'];
            }
        }else{
            $site=$_POST['uid'];
        }

        if(isset($_POST['lType'])){
            $type=$_POST['lType'];
        }else{
            $type=$_POST['sType'];
        }

        $lb=null;

        if($copyNo!=-1){
            $lb=$this->QLib->getLabourData('where date(theDate)=date('.$this->QLib->dateFormatForDb($_POST['theDate']).') and copyn='.$copyNo.' and site_id='.$site.' and ltype='.$type.' limit 1');
        }else{
            $lb=$this->QLib->getLabourData('where date(theDate)=date('.$this->QLib->dateFormatForDb($_POST['theDate']).') and site_id='.$site.' and ltype='.$type.' limit 1');
        }
        $lb2=$this->QLib->getLabourData('where date(theDate)=date('.$this->QLib->dateFormatForDb($_POST['theDate']).') and site_id='.$site.' and ltype='.$type);

        $lCount=count($lb2);

        //echo $_POST['theDate'].' and '.$_POST['siteId'];

        $but="";

        if(count($lb)==0){
            //echo $this->QLib->dateFormatForDb($_POST['theDate']);
            if($this->QLib->positionHasPrivilege($this->ud->user_userType,17)){

                if($_POST['lType']==1){

                    $txtOT=new input;


                    $txtOT->setTagOptions('style="width:100px;text-align:right;"');

                    $txtOT->setClass('lField');

                    $txtOT->setId('txtOT');

                    $txtOT->input('text','txtOT',0);


                    $wages=new input;

                    $wages->setTagOptions('style="width:100px;text-align:right;"');

                    $wages->setClass('lField');

                    $wages->setId('wages');

                    $wages->input('text','wages','0');

                    $cont->generalTags('<div id="form_row" style="padding-top:15px;"><div id="label" style="margin-left:10px;">Total Wages</div>'.$wages->toString().'<div id="label" style="margin-left:40px;">Over Time</div>'.$txtOT->toString().'</div>');

                }
                if($_POST['lType']<3){

                    if($copyNo==-1)
                        $cont->generalTags('<div class="qreq" style="float:left;width:100%;">');

                    $cont->generalTags('<div id="form_row" style="font-size:15px;text-indent:20px">Attach Excel File</div>');
                    $cont->generalTags('<div id="form_row">'.$this->excelImporter(false,array(),4,true,'<div class="saveFAList" id="sid_5">Save Labour Records</div>').'</div>');

                    //$cont->generalTags($this->QLib->getDay($_POST['theDate']));


                    $cont->generalTags('<div id="ReqList" style="min-height:200px;border-top:1px solid #ddd;float:left;width:100%;overflow-x:scroll;"></div>');

                }else{

                    //$cont->generalTags('<div style="padding:10px 0px;text-align:center;font-size:20px;">Upload Excel File  First</div>');

                    if($copyNo==-1)
                        $cont->generalTags('<div class="qreq" style="float:left;width:100%;">');

                    $cont->generalTags('<div class="q_row">'.$this->fileUploader('llpdf',$_POST['theDate'].'-'.$_POST['siteId'].'-'.$_POST['lType'],'application/pdf,image/*').'</div>');

                    $cont->generalTags('<div id="tFrame"></div>');

                }
            }else{

                $cont->generalTags('<div style="padding:10px 0px;text-align:center;font-size:20px;">No Entries Found</div>');
                if($this->QLib->positionHasPrivilege($this->ud->user_userType,17)){
                    $cont->generalTags('<div class="q_row">'.$this->fileUploader('llpdf',36,'application/pdf,image/*').'</div>');

                    $cont->generalTags('<div id="tFrame"></div>');
                }
            }
        }else{

            for($i=0;$i<count($lb);$i++){

                $cont->generalTags($but);

                $pr=$this->QLib->getProjects('where id='.$lb[$i]->l_project);

                if($lb[$i]->l_type==1){

                    if($copyNo==-1)
                        $cont->generalTags('<div class="qreq" style="float:left;width:100%;">');

                    $cont->generalTags('<div id="form_row" style="font-size:16px;text-align:center;text-decoration:underline;">Summary</div>');

                }else{
                    if($lb[$i]->l_type==2){

                        //$cont->generalTags('<div id="form_row">'.$this->excelImporter(false,array(),4,true,'<div class="saveFAList" id="sid_5">Save Entry</div>').'</div>');
                        $copies=new input;

                        $copies->setClass('quince_select');
                        for($r=0;$r<$lCount;$r++)
                            $copies->addItem($r,'Copy '.($r+1));

                        $copies->select('select','copies');

                        if($copyNo==-1)
                            if($this->QLib->positionHasPrivilege($this->ud->user_userType,17)){
                                $cont->generalTags('<div class="q_row"><div id="label">Select Copy</div><div class="qs_wrap" id="ldd">'.$copies->toString().'</div><div class="addRF" style="margin-left:10px">+Add A Copy</div></div>');
                            }else{
                                $cont->generalTags('<div class="q_row"><div id="label">Select Copy</div><div class="qs_wrap" id="ldd">'.$copies->toString().'</div></div>');
                            }
                        if($copyNo==-1)
                            $cont->generalTags('<div class="qreq" style="float:left;width:100%;">');

                        $cont->generalTags('<div id="form_row" style="font-size:16px;text-align:center;text-decoration:underline;">Details</div>');

                    }else{

                        if($copyNo==-1)
                            $cont->generalTags('<div class="qreq" style="float:left;width:100%;">');

                        $cont->generalTags('<div id="form_row" style="font-size:16px;text-align:center;text-decoration:underline;"></div>');
                    }
                }

                if(($lb[$i]->l_type==3)&($this->QLib->positionHasPrivilege($this->ud->user_userType,17)))
                    $cont->generalTags('<div class="q_roq">'.$this->fileUploader('llpdf',$_POST['theDate'].'-'.$_POST['siteId'].'-'.$_POST['lType'],'application/pdf,image/*').'</div>');


                for($x=0;$x<count($pr);$x++){

                    $cont->generalTags('<div id="form_row"><div id="label" style="margin-left:10px;font-weight:bold;">Project</div><div class="txtDiv" style="font-size:16px;">'.$pr[$x]->project_name.'</div></div>');

                }

                if($lb[$i]->l_type==1){

                    $dwr="";

                    if(date('w',strtotime(str_replace("'","",$this->QLib->dateFormatForDb($_POST['theDate']))))==5)
                        $dwr='<div id="label" style="margin-left:40px;font-weight:bold">Week Total</div><div class="txtDiv" style="font-size:14px;">'.number_format($this->QLib->getLabourWeekTotal($_POST['theDate'],$site),2).'</div>';

                    $cont->generalTags('<div id="form_row"><div id="label" style="margin-left:10px;font-weight:bold;">Over Time</div><div class="txtDiv" style="font-size:14px;">Ksh '.number_format($lb[$i]->l_ot,2).'</div><div id="label" style="margin-left:40px;font-weight:bold">Wages</div><div class="txtDiv" style="font-size:14px;">Ksh '.number_format($lb[$i]->l_wage,2).'</div><div id="label" style="margin-left:40px;font-weight:bold">Totals</div><div class="txtDiv" style="font-size:14px;">'.number_format(($lb[$i]->l_ot+$lb[$i]->l_wage),2).'</div>'.$dwr.'</div>');

                }
                if($lb[$i]->l_type!=3){
                    $cont->generalTags('<div style="width:100%;float:left;overflow-x:scroll;">'.$this->showLabourDetails(json_decode($lb[$i]->l_data)).'</div>');
                }
                if($lb[$i]->l_type==3){

                    if($lb[$i]->l_extType=="pdf"){
                        $cont->generalTags($this->showPdf($lb[$i]->l_data,'llpdf'));
                    }else{
                        $cont->generalTags('<div class="q_row" id="tFrame">');
                        if(is_string($lb[$i]->l_data) & (is_array(json_decode($lb[$i]->l_data)))){
                            $imgs=json_decode($lb[$i]->l_data);
                            $extT=json_decode($lb[$i]->l_extType);
                            for($x=0;$x<count($imgs);$x++)
                                if($extT[$x]!='pdf'){
                                    $cont->generalTags($this->showImage($imgs[$x],'llimage',$x));
                                }else{
                                    $cont->generalTags($this->showPdf($imgs[$x],'llpdf'));
                                }
                        }else{
                            //echo "asdas";
                            $cont->generalTags($this->showImage($lb[$i]->l_data,'llimage','',true,$x));
                        }
                        $cont->generalTags('</div>');
                    }

                }
                if($copyNo==-1)
                    $cont->generalTags('</div>');

            }
        }
        return $cont->toString();

    }
    public function importerOnly(){

        $cont=new objectString;

        $cont->generalTags('<div id="form_row" style="font-size:15px;text-indent:20px">Attach Excel File</div>');

        $cont->generalTags('<div id="form_row">'.$this->excelImporter(false,array(),4,true,'<div class="saveFAList" id="sid_5">Save Entry</div>').'</div>');

        $cont->generalTags('<div id="ReqList" style="min-height:200px;border-top:1px solid #ddd;float:left;width:100%;overflow-x:scroll;"></div>');

        return $cont->toString();

    }
    public function showLabourDetails($data=array()){

        $cont=new objectString;

        $list=new open_table;

        $c='A';


        $cnames=array();
        $cw=array();
        $widths=0;
        for($i=0;$i<count($data[0]);$i++){
            $cnames[]=$c++;
            $cw[]='153px';
            $widths+=155;
        }

        $list->setSize($widths.'px','200px');

        $list->setColumnNames($cnames);

        $list->setColumnWidths($cw);

        for($i=0;$i<count($data);$i++){
            $list->addItem($data[$i]);
        }

        $list->showTable();

        $cont->generalTags($list->toString());

        return $cont->toString();

    }
    public function showUploadedExcel($asCopy=false){

        $list=new open_table();

        $list->setHoverColor('#cbe3f8');

        $this->loadOpenExcelData($list);

        $list->setAsCopy($asCopy);

        $list->showTable();

        return $list->toString();

    }
    public function saveLabourData($site_id,$theDate,$theData,$ot,$wages,$ltype,$ext){

        return $this->QLib->saveLabourData($site_id,$theDate,$theData,$ot,$wages,$ltype,$ext);

    }
    public function equipmentRequest(){
        $cont=new objectString();

        $cont->generalTags("<div class='a3-full innerTitle a3-padding'>Request Equipment</div>");

        $as=System::shared("assist");

        $sm=$this->QLib->getMyActiveSite();

        if($sm==null){
            $cont->generalTags("<div class='nFound'>No Site assigned</div>");
            return $cont->toString();
        }


        $cont->generalTags("<div class='a3-search a3-right a3-padding a3-border-a3-round'><div class='a3-blue a3-round a3-padding saveFList' id='app_17'>+Submit Transfer Request</div></div>");



        $cont->generalTags("<div id='cont-hold' class='a3-left a3-margin-left' style='width:60%;height:80px;overflow-y:scroll'>".$as->macroEquimentList('')."</div>");

        $cont->generalTags("<div class='a3-left a3-full a3-lightgray a3-round a3-margin-left a3-margin-top ' style='width:% !important;height:' id='Reflist'>");

        $list=new open_table;

        $list->setColumnNames(array('No','Equipment Name','Reg Code','Quatity','Action'));

        $list->setColumnWidths(array('10%','36%','17%',"20%",'10%'));

        $list->showTable();

        $cont->generalTags($list->toString()."<label class='a3-left a3-full a3-center a3-text-red'>No Equipment Selected</label></div>");



        return $cont->toString();
    }
    public function loadEquipment(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row"><div class="innerTitle">Equipment List</div></div>');

        $sm=System::shared("assist");

        if($this->positionHasPrivilege($this->ud->user_userType,70) & $this->ud->user_userType !=1){

            $site=$this->QLib->getMyActiveSite();

            if($site ==null)
                return "No project Assigned";

            $cont->generalTags($sm->loadProjectEquipment($site->site_id));
        }else{
            $cont->generalTags($sm->loadEquipment());
        }

        /*
		$sel=new input;

		$sel->addItem(-1,'Select Site');

		$sel->setClass('quince_select');

		$sites=$this->QLib->getSites('where status=1');

		for($i=0;$i<count($sites);$i++){
		   $sel->additem($sites[$i]->site_id,$sites[$i]->site_name);
		}

		$sel->addItem(-2,'---Show All Equipment---');

		$sel->select('sites');

		if(($this->ud->user_userType==-3)|($this->ud->user_userType>-1))
		$cont->generalTags('<div class="q_row" style="border-bottom:1px solid #ddd;padding-bottom:10px;"><div class="label" style="width:40px;">Sites</div><div class="qs_wrap" id="equip">'.$sel->toString().'</div></div>');

		$cont->generalTags('<div class="q_row" style="margin-top:3px;margin-bottom:3px">'.$this->addResultsBar().'</div>');

		 if($this->positionHasPrivilege($this->ud->user_userType,70)){

		   $site=$this->QLib->getMyActiveSite($this->ud->user_id);

			 print_r($site);

			if(isset($site->site_id)){
				$cont->generalTags('<div class="nFound">Project Not Assigned</div>');
			}else{

			    $cont->generalTags('<div class="q_row" id="equipWrap">'.$this->equipmentList($site[0]->site_id).'</div>');
			}
		}else{

			$cont->generalTags('<div class="q_row" id="equipWrap"></div>');

		}*/

        return $cont->toString();

    }
    public function equipmentList($siteId=-1){

        $list=new open_table;

        if($siteId==-2)
            $list->enablePrintExport(true,array('Equipment List','All Sites'));

        if(($siteId!=-2)&($siteId!=-1)){
            $sites=$this->getSites('where id='.$siteId);
            for($i=0;$i<count($sites);$i++){
                $list->enablePrintExport(true,array('Equipment List',$sites[$i]->site_name));
            }
        }

        if($this->ud->user_userType==5)
            $list->canDeleteRow(true,1);

        $list->setNumberColumns(array(0));

        if(!$this->positionHasPrivilege($this->ud->user_userType,70)){

            $list->setColumnNames(array('No','Code','Description','Type','Created','Current Location',' '));

            $list->setColumnWidths(array('5%','10%','32%','10%','13%','15%','10%'));

        }else{

            $list->setColumnNames(array('No','Code','Description','Type',' '));

            $list->setColumnWidths(array('5%','10%','42%','20%','18%'));

        }
        $list->setHoverColor('#cbe3f8');

        $eq=array();

        if($siteId==-2){

            $eq=$this->QLib->getEquipment();
        }else{

            if($siteId!=-1)
                $eq=$this->QLib->getEquipment('and b.site_id='.$siteId,true);
        }

        $asite_id=0;

        if($this->positionHasPrivilege($this->ud->user_userType,70)){
            $theSite=$this->QLib->getMyActiveSite();
            if($theSite==null)
                return '<div class="nFound">No Project Assigned</div>';
            $asite_id=$theSite->site_id;
        }

        $nm=1;


        for($i=0;$i<count($eq);$i++){
            $lc="Not Assigned";

            $el=$this->QLib->getEquipmentLocation($eq[$i]->e_id);
            if($el!=null)
                $lc=$el->loc_siteTitle;

            if(!$this->positionHasPrivilege($this->ud->user_userType,70)){
                $list->addItem(array($nm,$eq[$i]->e_regCode,$eq[$i]->e_description,$this->getEquipmentType($eq[$i]->e_type),$eq[$i]->e_recordedDate,$lc,'<div class="mEQ" id="mEQ_'.$eq[$i]->e_id.'" title="Manage Equipment"></div>'),$eq[$i]->e_id);
                $list->addDataRow($this->expandDiv($eq[$i]->e_id, $eq[$i]->e_description));
                $nm++;
            }else{

                if($el==null){
                }elseif($el->loc_siteId==$asite_id){
                    $list->addItem(array($nm,$eq[$i]->e_regCode."mo",$eq[$i]->e_description,$this->getEquipmentType($eq[$i]->e_type),'<div class="mEQ" id="mEQ_'.$eq[$i]->e_id.'" title="Manage Equipment"></div>'),$eq[$i]->e_id);
                    $list->addDataRow($this->expandDiv($eq[$i]->e_id, $eq[$i]->e_description));
                    $nm++;
                }
            }

        }
        $list->showTable();

        return $list->toString();

    }
    public function loadMessages(){
        $cont=new objectString;

        $als=$this->QLib->getAlerts(' where user_id='.$this->ud->user_id.' order by alert_date desc limit 50');
        for($i=0;$i<count($als);$i++)
            $cont->generalTags('<div class="mesRow"><b>'.$als[$i]->alert_date.'</b>  |- '.$als[$i]->alert_description.'</div>');

        return $cont->toString();
    }
    public function addEquipmentPanel(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row"><div class="innerTitle">Add New Equipment</div></div>');

        $cont->generalTags("<input type='hidden' id='allow_edit' value=1''>");
        $saveBtn=new input;

        $saveBtn->setClass('saveFAList');

        $saveBtn->setId('sid_10_0');

        $saveBtn->input('button','sList','Save');


        $cont->generalTags('<div class="q_row">'.$saveBtn->toString().'</div>');

        $cont->generalTags('<div style="margin-left:40px;width:80%;overflow:hidden">'.$this->excelImporter(true,array(new name_value('Registration No.','reg'),new name_value('Description','desc'))).'</div>');

        $cont->generalTags('<div class="q_row"><div id="ReqList">'.$this->addEquipmentList().'</div></div>');

        $cont->generalTags('<div class="q_row"><div class="addBtn">+</div></div>');

        return $cont->toString();

    }
    public function addEquipmentList(){

        $list=new open_table;

        $list->setEditableColumns(array(1,2,3));

        //$list->canDeleteRow(true);

        $list->setColumnNames(array('No.','Code/Registration No','Description','Qty'));

        $list->setColumnWidths(array('8%','20%','45%','15%'));

        $this->loadExcelData($list,true);

        $list->showTable();

        return $list->toString();

    }
    public function manageEquipment($id){

        $cont=new objectString();

        $equip_value=0;

        $equip_ass=array();

        $el=$this->QLib->getEquipment("where id=".$id,false);

        if($el){
            $equip_value=$el[0]->e_site;
        }

        /*	$sites=new input;

		$sites->setClass('quince_select');

		$sites->setId('e_'.$id);

		if($el!=null)
		$sites->setSelected($el->loc_siteId);

		$st=$this->QLib->getSites('where status=1');

		$sites->addItem(-1,'Not Assigned');

		for($i=0;$i<count($st);$i++)
		 $sites->addItem($st[$i]->site_id,$st[$i]->site_name);

		$sites->select("sel");

		$cont->generalTags('<div class="q_row"><div class="label">Location</div><div class="qs_wrap">'.$sites->toString().'</div></div>');
		*/
        $btn="";

        $list=new open_table();

        $list->setId("equip");

        if(!$this->positionHasPrivilege($this->ud->user_userType,70)){

            $eqip=$this->QLib->getTransferRequests(" and a.equip_id=".$id." GROUP BY a.source_site",true);

            $list->setColumnNames(array('Id','Site Assigned','Date Assigned','Qty',' '));

            $list->setColumnWidths(array('10%','26%','27%','15%','20%'));

            for($i=0;$i<count($eqip);$i++){
                $equip_ass[]=$eqip[$i]->tr_totals;
                $list->addItem(array(($i+1),$eqip[$i]->tr_destName,$eqip[$i]->tr_date,$eqip[$i]->tr_totals," "));
            }
            $list->addItem(array("","","",""," "));

            $list->addItem(array("","","","<b class='a3-right'>Total Quantity</b>",$equip_value));

            $list->addItem(array("","","","<b class='a3-right'>Assigned Quantity</b>",array_sum($equip_ass)));

            $list->addItem(array("","","","<b  class='a3-right'>UnAssigned Quantity</b>",$ww=(($equip_value-array_sum($equip_ass))<0) ? "<nav class='a3-text-red'>Above Value !</nav>" : ($equip_value-array_sum($equip_ass) )));

            $list->setHoverColor('#cbe3f8');

            $update=new input;

            $update->setClass('form_button');

            $update->setTagOptions('style="margin-left:50px;float:right"');

            $update->setId('be_'.$id);

            $update->input('button','upTxt','Assign To Site');

            (($equip_value-array_sum($equip_ass))>0) ? $btn=$update->toString() :null;


            //show the equipments issues to the sites if overdue.

        }
        if($this->positionHasPrivilege($this->ud->user_userType,70)){
            $rtrans=new input;

            $rtrans->setClass('form_button');

            $rtrans->setTagOptions('style="margin-left:50px;float:right"');

            $rtrans->setId('rq_'.$id);

            $rtrans->input('button','upTxt','Request Transfer');

            $btn=$rtrans->toString();

        }
        $list->showTable();

        $cont->generalTags($list->toString());

        $cont->generalTags('<div class="q_row">'.$btn.'</div>');

        return $cont->toString();

    }
    public function equipmentTransfersTable($addWhere=""){



        $list=new open_table();

        $list->setHoverColor('#cbe3f8');

        $list->setColumnNames(array('No.','Code','Description','Value','Source','Destination','Requested By',' '));

        $list->setColumnWidths(array('6%','10%','15%','10%','15%','0%','10%','15%'));

        $tr=$this->QLib->getTransferRequests('group by c.regcode');



        for($i=0;$i<count($tr);$i++){

            $sr=($tr[$i]->tr_sourceName !="-2") ? $this->QLib->getSites(" where id=".$tr[$i]->tr_sourceName)[0]->site_name : "store" ;

            $list->addItem(array($tr[$i]->tr_id,$tr[$i]->tr_equipCode,$tr[$i]->tr_equipName,$tr[$i]->tr_value,$sr,$tr[$i]->tr_destName,$tr[$i]->tr_byName,'<div  class="auth a3-margin-left" id='.$tr[$i]->tr_equipCode.' >Allow</div>'));
        }

        $list->showTable();

        return $list->toString();
    }
    public function equipmentTransfers(){
        $cont=new objectString();

        $where='';

        $cont->generalTags("<div class='innerTitle a3-padding-left'>General Equipment Requests</div>");


        $cont->generalTags('<div class="q_row">'.$this->equipmentTransfersTable('').'</div>');

        return $cont->toString();
    }
    public function processTransfer($tid){
        $tr=$this->QLib->getTransferRequests(' and a.id='.$tid);

        for($i=0;$i<count($tr);$i++){
            $this->QLib->processTransfer($tr[$i]->tr_equipId,$tr[$i]->tr_destId,$tid);
        }

    }
    public function changeELocation($e_id,$site_id){
        $this->QLib->changeELocation($e_id,$site_id);
    }
    public function saveTransferRequest($dest_site,$id){
        $site=$this->QLib->getMyActiveSite();
        $this->QLib->saveTransferRequest($site->site_name,$dest_site,$id);
    }
    public function infoMes($mess=""){
        return '<div class="theM">'.$mess.'</div>';
    }
    public function getEquipmentType($type){

        switch($type){
            case 1:
                return "EQUIPMENT";
            case 2:
                return "PLANT";
            case 3:
                return "MACHINERY";
        }

    }
    public function addEquipment($code,$desc,$qty){

        $site=$this->QLib->getMyActiveSite();

        if($site==null){
            return $this->QLib->addEquipment($code,$desc,$qty,"-2");
        }else{
            return $this->QLib->addEquipment($code,$desc,$qty,$site->site_id);
        }


    }
    public function newRequisition2(){

        $cont=new objectString;

        $sub=new input;

        $sub->setClass('saveFList');

        $sub->setId('sid_1_0');

        $sub->setTagOptions('style="float:right;"');

        $sub->input('button','saveReq','Submit Requisition');

        $cont->generalTags('<div class="q_row" style="border-bottom:1px solid #ddd;padding-bottom:10px;"><div class="innerTitle" style="margin-top:2px;">New Requisition</div>'.$sub->toString ().'</div>');

        $pids=array();

        $pr=$this->QLib->getProjects('where project_manager='.$this->ud->user_id);

        for($c=0;$c<count($pr);$c++)
            $pids[]=$pr[$c]->project_id;

        $whr="";

        if(count($pids)>0)
            $whr=' and (project_id='.implode(' or project_id=',$pids).')';

        $sites=$this->QLib->getSites('where status=1 '.$whr);

        $ssite=new input;

        $ssite->setClass('quince_select');

        $ssite->setId('sSite');

        $ssite->addItem(-1,"Select Site");

        for($i=0;$i<count($sites);$i++)
            $ssite->addItem($sites[$i]->site_id,$sites[$i]->site_name);

        $ssite->select('selectSite');

        if($this->QLib->positionHasPrivilege($this->ud->user_userType,70)){
            $ss=$this->QLib->getMyActiveSite();
            if($ss==null)
                return '<div class="q_row nFound">Site Not Assigned</div>';

            $cont->generalTags('<div class="q_row"><div class="mpSite" style="float:left;width:100%;"><div id="label" style="width:50px;font-size:16px;">Site</div><div style="float:left;margin-top:5px;">'.$ss->site_name.'</div></div></div>');

            $cont->generalTags('<input type="hidden" value="'.$ss->site_id.'" id="sSite" />');


        }else{

            $cont->generalTags('<div class="q_row"><div class="mpSite" style="float:left;width:100%;"><div id="label" style="width:50px;">Site</div><div class="qs_wrap">'.$ssite->toString().'</div></div></div>');

        }
        $cont->generalTags('<div class="dlMp" style="float:left;width:100%;overflow:hidden;"></div>');

        $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

        //$cont->generalTags('<div class="hideFirst" style="width:100%;float:left;overflow:hidden;display:none;">');

        $cont->generalTags('<div style="margin-left:55px;float:left;width:70%;">'.$this->excelImporter().'</div>');


        //$cont->generalTags('');

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags('<div id="ReqList" style="float:left;width:100%;">');

        $cont->generalTags($this->showReqList());

        $cont->generalTags('</div>');

        $cont->generalTags('<div class="addBtn" title="Add a row">+</div>');

        $cont->generalTags('</div>');

        //$cont->generalTags('</div>');

        //$cont->generalTags('<div class="kindNote"><b>Note:</b> A copy of the original document must be attached.</div>');

        return $cont->toString();

    }
    public function newRequisition(){

        $cont=new objectString;

        $sub=new input;

        $sub->setClass('dataCont a_btn');

        $sub->setId('s_id_3');

        $sub->setTagOptions('style="float:right;border:none;padding:10px 20px ;border-radius:4px;"');

        $sub->input('button','C_firm','Submit Requisition');

        $cont->generalTags('<div class="q_row" style="border-bottom:1px solid #ddd;padding-bottom:10px;"><div class="innerTitle" style="margin-top:2px;">New Requisition</div>'.$sub->toString ().'</div>');

        $pids=array();

        $pr=$this->QLib->getProjects('where project_manager='.$this->ud->user_id);

        for($c=0;$c<count($pr);$c++)
            $pids[]=$pr[$c]->project_id;

        $whr="";

        if(count($pids)>0)
            $whr=' and (project_id='.implode(' or project_id=',$pids).')';

        $sites=$this->QLib->getSites('where status=1 '.$whr);

        $ssite=new input;

        $ssite->setClass('quince_select');

        $ssite->setId('sSite');

        $ssite->addItem(-1,"Select Site");

        for($i=0;$i<count($sites);$i++)
            $ssite->addItem($sites[$i]->site_id,$sites[$i]->site_name);

        $ssite->select('selectSite');

        //$cont->generalTags('<div class="q_row"><div class="mpSite" style="float:left;width:100%;"><div id="label" style="width:50px;">Site</div><div class="qs_wrap">'.$ssite->toString().'</div></div></div>');

        if($this->QLib->positionHasPrivilege($this->ud->user_userType,70)){
            $ss=$this->QLib->getMyActiveSite();
            $cont->generalTags('<div class="q_row"><div class="mpSite" style="float:left;width:100%;"><div style="float:left;margin-top:5px;">'.$ss->site_name.'</div>');

            $cont->generalTags('<input type="hidden" value="'.$ss->site_id.'" id="sSite" />');


            $componen=$this->QLib->getWorkSchedule("where projectId=".$ss->site_id."");


            $cont->generalTags("<div class='qs_wrap a3-margin-left'><select class='quince_select' name='selectComponent'>");

            $cont->generalTags("<option value='-1'>Append To Component</option>");

            foreach($componen as $component)
                $cont->generalTags("<option value='".$component->wk_id."'>".$component->wk_description."</option>");


            $cont->generalTags("</select></div></div></div>");
        }else{

            $cont->generalTags('<div class="q_row"><div class="mpSite" style="float:left;width:100%;"><div id="label" style="width:50px;">Site</div><div class="qs_wrap">'.$ssite->toString().'</div></div></div>');

        }

        $cont->generalTags('<div class="dlMp" style="float:left;width:100%;overflow:hidden;"></div>');
        /*
		  adding three lines as a test for creating my own button as an object and calling it to  display
		*/
        $james= new input;

        $james->setClass('a_btn');

        $james->setId('md_james');

        $james->input('button','C_test','Create list');


        //$cont->generalTags('<div class="q_row">'.$james->toString().'<div>');
        //$cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

        //$cont->generalTags('<div class="hideFirst" style="width:100%;float:left;overflow:hidden;display:none;">');

        //$cont->generalTags('<div style="margin-left:55px;float:left;width:70%;">'.$this->excelImporter(true,array(new name_value('Description Field','desc','B'),new name_value('Qty','qty','C'),new name_value('Unit','unit','D'),new name_value('Use','suse','E'))).'</div>');


        $cont->generalTags('');

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags('<div id="Reflist" style="float:left;width:100%;">');

        $as =System::shared("assist");

        $cont->generalTags($as->updatedAdaptedList());

        $cont->generalTags('</div>');

        //$cont->generalTags('<div class="addBtn" title="Add a row">+</div>');

        $cont->generalTags('</div>');
        $cont->generalTags("<div class='am_tbl' style=''></div>");
        $cont->generalTags('<div class="tbl_ins am_btn">Add Material</div>');
        $cont->generalTags('</div>');
        $cont->generalTags("<div class='info-select-tbl'></div>");

        return $cont->toString();

    }

    public function adaptedList(){
        $list=new open_table;

        //$list->canDeleteRow(true);

        $list->setColumnNames(array('No','Description','Quantity',"Unit"));
        //,'Rate','Amount','Remarks'
        $list->setColumnWidths(array('4%','30%','20%','20%'));


        $list->setNumberColumns(array(4));

        $list->setHoverColor('#cbe3f8');

        $concern=new  am_assist();

        $data=array();


        $data=$concern->getRegItems_db("");
        // print_r($data);

        for ($i=0;$i<count($data);$i++)
            $list->addItem(array($i+1,$data[$i]->name,"   ",$data[$i]->unit));




        $list->showTable();

        return $list->toString();

    }	public function getSites($whereclause=""){
    return $this->QLib->getSites($whereclause);
}
    public function getDeletedMP($site_id,$project_id){

        $cont=new objectString;

        $req=$this->QLib->getRequisitions('where requisition_status=-1 and project_id='.$project_id.' and site_id='.$site_id);

        $qs=new input;

        $qs->setClass('quince_select');

        $qs->addItem(-1,"Select Deleted Requisition");

        for($x=0;$x<count($req);$x++){
            $qs->addItem($req[$x]->req_id,"Requisition ".$req[$x]->req_no);
        }

        $qs->select('sel');

        if(count($req)>0)
            $cont->generalTags('<div class="q_row"><div id="label" style="width:50px;">Replace</div><div class="qs_wrap">'.$qs->toString().'</div></div>');

        return $cont->toString();

    }
    public function systemUsers(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row" style="margin-top:0px;">');

        if($this->QLib->positionHasPrivilege($this->ud->user_userType,-8)|($this->ud->user_id==1))
            $cont->generalTags('<div class="addUser">+Add User</div>');



        $cont->generalTags('<div class="nUser" style="float:left;width:100%;display:none;">');

        //$cont->generalTags('<div class="q_row" style="border-bottom:1px solid #ddd;float:left;padding-bottom:10px;">');

        $cont->generalTags('<div class="innerTitle" style="margin-bottom:5px;padding-bottom:10px;border-bottom:1px solid #ddd;width:100%;margin-bottom:10px;">Create User</div>');

//		/$cont->generalTags('</div>');


        $cont->generalTags('<div style="width:100%;float:left;overflow:hidden"><div class="results_wrap"></div></div>');

        $fName=new input;

        $fName->setClass('txtField');

        $fName->setId('fName');

        $fName->input('text','name','');

        $sName=new input;

        $sName->setClass('txtField');

        $sName->setId('sName');

        $sName->input('text','sName','');

        $cont->generalTags('<div id="form_row"><div id="label" style="width:80px;">First Name</div>'.$fName->toString().'<div id="label" style="margin-left:30px;">Last Name</div>'.$sName->toString().'</div>');

        $sel=new input;

        $sel->setId('position');

        $sel->setClass('quince_select');

        $sel->addItems($this->QLib->getDynamicPositions());

        $sel->select('select');

        $email=new input;

        $email->setId('email');

        $email->setClass('txtField');

        $email->input('text','theEmail','');

        $cont->generalTags('<div id="form_row"><div id="label" style="width:80px;">Email</div>'.$email->toString ().'<div id="label" style="margin-left:30px;">Position</div><div id="upos" class="qs_wrap">'.$sel->toString().'</div></div>');

        $cont->generalTags('<div id="form_row" style="font-size:14px;margin-top:20px;">Login Credentials</div>');

        $pass=new input;

        $pass->setClass('txtField');

        $pass->setId('pass');

        $pass->input('password','txtpass','');

        $rpass=new input;

        $rpass->setClass('txtField');

        $rpass->setId('rpass');

        $rpass->input('password','txtpass','');

        $cuser=new input;

        $cuser->setId('s_user');

        $cuser->setClass('saveData');

        $cuser->setTagOptions('style="float:right;"');

        $cuser->input('button','suser','Create User');

        $cont->generalTags('<div id="form_row"><div id="label" style="width:80px;">Password</div>'.$pass->toString().'<div id="label" style="margin-left:30px;">Rpt. Password</div>'.$rpass->toString().$cuser->toString().'</div>');

        $cont->generalTags('</div>');

        $cont->generalTags('<div id="form_row" style="font-size:14px;margin-top:10px;font-size:22px;">System Users</div>');

        $cont->generalTags('<div class="jaxR" style="width:100%;float:left;">');

        $cont->generalTags($this->usersList());

        $cont->generalTags('</div>');

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function projectAssignment(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags('<div class="innerTitle" style="font-size:22px;">Assignment Users To Projects</div>');

        $cont->generalTags('</div>');

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags($this->usersList());

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function accessLogs(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row" style="margin-top:0px;">');

        $frm=new input;

        $frm->setClass('quince_date');

        $frm->input('text','frmDate','');

        $to=new input;

        $to->setClass('quince_date');

        $to->input('text','toDate','');

        $cont->generalTags('<div class="innerTitle" style="margin:15px 0px;padding-bottom:10px;border-bottom:1px solid #ddd;width:100%;margin-bottom:10px;">Access Logs<div style="float:right;"><div class="label">From</div>'.$frm->toString().'<div class="label" style="margin-left:10px;">To</div>'.$to->toString().'</div></div>');

        $cont->generalTags($this->logList());

        $cont->generalTags('</div>');

        return $cont->toString();
    }
    public function logList(){

        $list=new open_table;

        $list->setColumnNames(array('Date','User','IP'));

        $list->setColumnWidths(array('20%','40%','20%'));

        $whereid="";

        $ids=array();

        //if($this->ud->user_id!=1){
        $this->QLib->cmp->company_id=$this->QLib->cmp->company_id==''? 0:$this->QLib->cmp->company_id;

        $thIds=$this->um->getUsers('where company_id='.$this->QLib->cmp->company_id);
        for($i=0;$i<count($thIds);$i++){
            $ids[]=$thIds[$i]->user_id;
        }
        $whereid='where user_id='.implode(' or user_id=',$ids);

        //}

        $al=$this->um->getAccessLogs($whereid." order by access_time desc limit 100");

        for($i=0;$i<count($al);$i++){
            $list->addItem(array($al[$i]->log_date,$al[$i]->log_user,$al[$i]->log_ip));
        }

        $list->showTable();

        return $list->toString();

    }
    public function addResultsBar($theId=""){

        $cont=new objectString;

        $cont->generalTags('<div style="width:100%;float:left;overflow:hidden"><div class="results_wrap" id="'.$theId.'"></div></div>');

        return $cont->toString();
    }
    public function showResetLabourRequest(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row"><div class="innerTitle">Reset Labour Entries Request</div></div>');

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags($this->addResultsBar());

        $list=new open_table;

        $list->setColumnNames(array("No.","Request Date","Request By","Site","Entry Date","Entry Type","Action"));

        $list->setColumnWidths(array("5%","13%","22%","15%","15%","15%","14%"));

        $list->setHoverColor('#cbe3f8');

        $typ=array("Invalid","Summary","Detailed","Doc");

        $req=$this->QLib->getLabourResetRequest('where status=0');

        for($i=0;$i<count($req);$i++){
            $site="";

            $s=$this->QLib->getSites('where id='.$req[$i]->request_siteId);
            for($x=0;$x<count($s);$x++)
                $site=$s[$x]->site_name;

            $list->addItem(array($i+1,$req[$i]->request_date,$req[$i]->request_byName,$site,$req[$i]->request_entry,$typ[$req[$i]->request_labourType],'<div class="appR" title="Approve request" id="appRes_'.$i.'_'.$req[$i]->request_id.'">Approve</div><div class="decL" title="Decline request" id="decRes_'.$i.'_'.$req[$i]->request_id.'">Decline</div>'));
        }
        $list->showTable();

        $cont->generalTags($list->toString());

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function setLabourResetRequest($site_id,$theDate,$ltype){
        return $this->QLib->setLabourResetRequest($site_id,$theDate,$ltype);
    }
    public function approveLabourRest($id){

        $lr=$this->QLib->getLabourResetRequest("where id=".$id,2);
        for($i=0;$i<count($lr);$i++){

            $lab=$this->QLib->getLabourData('where site_id='.$lr[$i]->request_siteId.' and ltype='.$lr[$i]->request_labourType.' and date(theDate)=date('.$this->QLib->dateFormatForDb(str_replace('-','/',$lr[$i]->request_entry)).')',2);

            for($l=0;$l<count($lab);$l++){
                if($lab[$l]->l_type==3){
                    $this->QLib->deleteLabourFiles($lab[$l]->l_data);
                }

                $this->QLib->deleteLabourEntry($lab[$l]->l_site,str_replace('-','/',$lab[$l]->l_date),$lab[$l]->l_type);
            }

            $this->QLib->changeLabourREStatus($id,1);
        }
    }
    public function declineLabourRequest($id){
        $this->QLib->changeLabourREStatus($id,-1);
    }
    public function usersList(){

        $list=new open_table;

        $list->setHoverColor('#cbe3f8');

        $list->setColumnNames(array('No.','User\'s Name','Email/Username','Position','Site','Status','Created',' '));

        $list->setColumnWidths(array('10%','18%','18%','13%','14%','8%','13%','5%'));

        $usrs=null;

        if($this->ud->user_userType==0){
            $usrs=$this->um->getUsers('where id<>'.$this->ud->user_id.' and user_type<0 and user_type<>-4 and user_type<>-5 and id<>'.$this->ud->user_id.' and company_id='.$this->QLib->cmp->company_id);
        }else{
            $this->QLib->cmp->company_id=$this->QLib->cmp->company_id==''? 0:$this->QLib->cmp->company_id;
            $usrs=$this->um->getUsers('where id<>'.$this->ud->user_id.' and company_id='.$this->QLib->cmp->company_id);
        }
        $user_types=$this->QLib->getDynamicPositions(true);/*array('Construction Manager','General Manager','Procurement','Accountant','Director','Administrator');

		$user_types[-2]="Store Clerk";

		$user_types[-3]="Foreman";

		$user_types[-4]="Secretary";*/

        $status=array('<div style="color:#e30c0c;">inactive</div>','<div style="color:#0eb116;">Active</div>');

        for($i=0;$i<count($usrs);$i++){
            $list->addItem(array($i+1,$usrs[$i]->user_name,$usrs[$i]->user_username,System::getArrayElementValue($user_types,$usrs[$i]->user_type),'',$status[$usrs[$i]->user_status],$usrs[$i]->user_dateCreated,'<div class="u_more" id="umore_'.$usrs[$i]->user_id.'" title="Show Details"></div>'));
            $list->addDataRow($this->expandDiv($usrs[$i]->user_id, $usrs[$i]->user_name));
        }
        $list->showTable();

        return $list->toString();

    }
    public function showUserPanel($id){

        $cont=new objectString;

        $pos=$this->QLib->getDynamicPositions();

        $usr=$this->um->getUsers('where id='.$id);

        for($i=0;$i<count($usr);$i++){

            $sel=new input;

            $sel->setClass('quince_select');

            $sel->setId('pos_'.$id);

            $sel->addItems($pos);

            $sel->setSelected($usr[$i]->user_type);

            $sel->select('pos');

            $status=new input;

            $status->setClass('quince_select');

            $status->setId('status_'.$id);

            $status->setSelected($usr[$i]->user_status);

            $status->addItems(array(new name_value('Active',1),new name_value('InActive',0)));

            $status->select('stat');

            $upDate=new input;

            $upDate->setClass('form_button');

            $upDate->setId('upsp_'.$id);

            $upDate->setTagOptions('style="margin-top:-1px;margin-left:5px;"');

            $upDate->input('button','udate','Update Details');

            $email=new input;

            $email->setClass('txtField');

            $email->setId('uem_'.$usr[$i]->user_id);

            $email->input('text','txtEmail',$usr[$i]->user_username);

            $cont->generalTags('<div id="us_'.$usr[$i]->user_id.'" style="display:none;"></div>');

            if($this->QLib->positionHasPrivilege($this->ud->user_userType,-8)|($this->ud->user_id==1)){
                $cont->generalTags('<div class="q_row"><div id="label">Position</div><div class="qs_wrap">'.$sel->toString().'</div><div id="label" style="margin-left:40px;">User Status</div><div class="qs_wrap">'.$status->toString().'</div>'.$upDate->toString().'</div><div class="q_row"><div id="label">Email</div>'.$email->toString().'</div>');
            }

            if($this->QLib->positionHasPrivilege($usr[$i]->user_type,70)){
                $cont->generalTags('<div class="q_row" style="border-bottom:1px solid #ddd;">Site Details</div>');

                $sites=new input;

                $st=$this->QLib->getSites('where status=1');

                $sites->setClass('quince_select');

                $sites->setId('selS_'.$id);

                $as=$this->QLib->getAssignedSite($id);

                if($as!=null)
                    $sites->setSelected($as->a_siteId);

                $sites->addItem(-1,'None');

                for($x=0;$x<count($st);$x++)
                    $sites->addItem($st[$x]->site_id,$st[$x]->site_name);

                $sites->select('selSite');

                $usite=new input;

                $usite->setClass('form_button');

                $usite->setId('sSite_'.$id);

                $usite->setTagOptions('style="margin-top:-1px;margin-left:10px;"');

                $usite->input('button','asgn','Update Site');

                $cont->generalTags('<div class="q_row"><div id="label">Assigned To</div><div class="qs_wrap">'.$sites->toString().'</div>'.$usite->toString().'</div>');
            }

            if($this->ud->user_userType>3){

                $cont->generalTags('<div class="q_row" style="border-bottom:1px solid #ddd;">Notifications</div>');
                $cont->generalTags($this->notificationSetPanel($usr[$i]->user_id));

            }

        }

        return $cont->toString();

    }
    public function processUserUpdates($btn){
        switch(explode('_',$btn)[0]){
            case 'upsp':
                $users=$this->um->getUsers('where id='.explode('_',$btn)[1]);
                for($i=0;$i<count($users);$i++){
                    if($users[$i]->user_username!=$_POST['email']){
                        $us=$this->QLib->updateEmailPhone($_POST['email'],$this->QLib->getUserPhone($users[$i]->user_id),$users[$i]->user_id);
                        if($us->name==false){
                            return System::getWarningText('Email address already exists.');
                        }
                    }
                    $this->um->updateUserType($_POST['pos'],explode('_',$btn)[1]);
                    $this->um->updateUserStatus($_POST['stat'],explode('_',$btn)[1]);
                    return System::successText("Settings updated successfully.");
                }
                break;

            case 'sSite':
                $this->QLib->setSiteAssignment($_POST['site'],explode('_',$btn)[1]);
                return System::successText("Settings updated successfully.");
                break;
        }
    }
    public function createUser(){

        return $this->um->createNewUser(System::getArrayElementValue($_POST,'firstName').' '.System::getArrayElementValue($_POST,'lastName'),System::getArrayElementValue($_POST,'email'),System::getArrayElementValue($_POST,'password'),System::getArrayElementValue($_POST,'position'),$this->QLib->cmp->company_id,0);

    }
    public function createCompanyUser(){
        $id=0;
        $company=$this->QLib->getCompany(System::getArrayElementValue($_POST,'companyId'));
        $this->QLib->addPosition('Administrator',$id,$company->company_prefix);
        $privileges=$this->QLib->getDynamicPrivileges();
        for($i=0;$i<count($privileges);$i++){
            if(($privileges[$i]->value!=-1)&($privileges[$i]->value!=70))
                $this->QLib->addRemovePrivileges($privileges[$i]->value,1,$id,$company->company_prefix);
        }
        $theId=$this->um->createNewUser(System::getArrayElementValue($_POST,'firstName').' '.System::getArrayElementValue($_POST,'secondName'),System::getArrayElementValue($_POST,'email'),System::getArrayElementValue($_POST,'pass'),$id,$company->company_id,0);
        return new name_value(true,System::successText("User added successfully"));
    }
    public function changePassPanel(){

        $cont=new objectString();

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags('<div class="innerTitle">Change Password?</div>');

        $cont->generalTags($this->addResultsBar());

        $cont->generalTags('</div>');

        $oPass=new input;

        $oPass->setClass('txtField');

        $oPass->setId('oPass');

        $oPass->input('password','oPass','');

        $cont->generalTags('<div class="q_row"><div id="label" style="width:100%;color:#076aa3;">Enter details below.</div></div>');

        $cont->generalTags('<div class="q_row"><div id="label">Old Password</div>'.$oPass->toString().'</div>');

        $nPass=new input;

        $nPass->setClass('txtField');

        $nPass->setId('nPass');

        $nPass->input('password','nPass','');

        $cont->generalTags('<div class="q_row"><div id="label">New Password</div>'.$nPass->toString().'</div>');

        $rPass=new input;

        $rPass->setClass('txtField');

        $rPass->setId('rPass');

        $rPass->input('password','rpass','');

        $cont->generalTags('<div class="q_row"><div id="label">Repeat Password</div>'.$rPass->toString().'</div>');

        $sData=new input;

        $sData->setClass('saveData');

        $sData->setId('cPassword');

        $sData->input('button','saveData','Change Password');

        $cont->generalTags('<div class="q_row">'.$sData->toString().'</div>');

        return $cont->toString();

    }
    public function changePassword($oldPass,$newPass){

        return $this->um->updateUserPassword($this->ud->user_id,$oldPass,$newPass);

    }
    public function createPro(){
        return $this->QLib->createProject($_POST['title'],0,$_POST['sDate'],$_POST['eDate'],$_POST['loc'],$_POST['pDesc'],$_POST['bud'],0);
    }
    public function uploadWindow(){

        $cont=new objectString;

        $cont->generalTags('<div style="width:150px;float:left;overflow:hidden;">');

        $cont->generalTags('<div id="eProg"><i>Uploading...</i></div>');

        $cont->generalTags('<div class="pBar"><div id="pBarI"></div></div>');

        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function excelSettings($extended=false,$fields=array()){

        $cont=new objectString;

        if($extended){

            $cont->generalTags('<div style="float:left;overflow:hidden;width:'.(100+(110*count($fields))).'px;padding-bottom:5px;">');

            $finalString="";

            for($c=0;$c<count($fields);$c++){

                $tField=new input;

                $tField->setTagOptions('style="width:25px;margin-top:5px;text-align:center;text-transform:uppercase;"');

                $tField->setClass('form_input');

                $tField->input('text',$fields[$c]->value,'');

                $finalString.='<div id="label" style="width:'.($ex=strlen($fields[$c]->name)>6 ? '100':  '55').'px;">'.$fields[$c]->name.'</div>'.$tField->toString();

            }

            $cont->generalTags($finalString);

        }else{

            $cont->generalTags('<div style="float:left;overflow:hidden;width:400px;padding-bottom:5px;">');

            $desc=new input;

            $desc->setTagOptions('style="width:25px;margin-top:5px;text-align:center;text-transform:uppercase;"');

            $desc->setClass('form_input');

            $desc->input('text','desc','B');

            $qty=new input;

            $qty->setClass('form_input');

            $qty->setTagOptions('style="width:25px;margin-top:5px;text-align:center;text-transform:uppercase;"');

            $qty->input('qty','qty','C');

            $unit=new input;

            $unit->setClass('form_input');

            $unit->setTagOptions('style="width:25px;margin-top:5px;text-align:center;text-transform:uppercase;"');

            $unit->input('unit','unit','D');

            $cont->generalTags('<div id="label" style="width:100px;">Description Field</div>'.$desc->toString().'<div id="label" style="width:55px;">Qty Field</div>'.$qty->toString().'<div id="label" style="width:70px;">Unit Field</div>'.$unit->toString());
        }
        $cont->generalTags('</div>');

        return $cont->toString();

    }
    public function loadExcelData(&$list,$gen=false){

        if(isset($_FILES['excelFile'])){
            $ExcelObj=$ExcelObj=\PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excelFile']['tmp_name']);//PHPExcel_IOFactory::load($_FILES['excelFile']['tmp_name']);

            $ExcelObj->setActiveSheetIndex(0);

            $sheetnames=array();

            $Sheets=$ExcelObj->getAllSheets();

            for($i=0;$i<count($Sheets);$i++)
                $sheetnames[]=$Sheets[$i]->getTitle();

            $sheet=$ExcelObj->getActiveSheet()->toArray(null,true,true,true);

            if(!$gen){

                if($_POST['desc']=="")
                    unset($_POST['desc']);

                if($_POST['qty']=="")
                    unset($_POST['qty']);

                if($_POST['unit']=="")
                    unset($_POST['unit']);
                $column_names=array(System::getArrayElementValue($_POST,'desc','A'),System::getArrayElementValue($_POST,'qty','B'),System::getArrayElementValue($_POST,'unit','C'));

            }else{

                $keys=array_keys($_POST);

                $column_names=array();

                for($z=0;$z<count($keys);$z++){
                    if(($_POST[$keys[$z]]=="")&&($keys[$z]!="uid"))
                        unset($_POST[$keys[$z]]);
                }
                $alp=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                for($z=0;$z<count($keys);$z++){
                    if($keys[$z]!="uid")
                        $column_names[]=System::getArrayElementValue($_POST,$keys[$z],$alp[$z]);
                }

            }
            $sheet_arrays=$sheet;

            if(count($sheet)>0){

                if(count($sheet[1])){

                    for($l=1;$l<count($sheet)+1;$l++){

                        $data=array();

                        $data[]=$l;

                        for($i=0;$i<count($column_names);$i++){

                            $data[]=System::getArrayElementValue($sheet[$l],strtoupper($column_names[$i]),'');

                            if($data[count($data)-1]=="")
                                $data[count($data)-1]=" ";

                        }

                        $list->addItem($data,$l-1);
                    }

                }
            }
        }


    }
    public function loadExcelsData(&$list){

        if(isset($_FILES['excelFile'])){
            $ExcelObj=$ExcelObj=\PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excelFile']['tmp_name']);//PHPExcel_IOFactory::load($_FILES['excelFile']['tmp_name']);

            $ExcelObj->setActiveSheetIndex(0);

            $sheetnames=array();

            $Sheets=$ExcelObj->getAllSheets();

            for($i=0;$i<count($Sheets);$i++)
                $sheetnames[]=$Sheets[$i]->getTitle();

            $sheet=$ExcelObj->getActiveSheet()->toArray(null,true,true,true);

            if(!$gen){

                if($_POST['desc']=="")
                    unset($_POST['desc']);

                if($_POST['qty']=="")
                    unset($_POST['qty']);

                if($_POST['unit']=="")
                    unset($_POST['unit']);
                $column_names=array(System::getArrayElementValue($_POST,'desc','A'),System::getArrayElementValue($_POST,'qty','B'),System::getArrayElementValue($_POST,'unit','C'));

            }else{

                $keys=array_keys($_POST);

                $column_names=array();

                for($z=0;$z<count($keys);$z++){
                    if(($_POST[$keys[$z]]=="")&&($keys[$z]!="uid"))
                        unset($_POST[$keys[$z]]);
                }
                $alp=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                for($z=0;$z<count($keys);$z++){
                    if($keys[$z]!="uid")
                        $column_names[]=System::getArrayElementValue($_POST,$keys[$z],$alp[$z]);
                }

            }
            $sheet_arrays=$sheet;

            if(count($sheet)>0){

                if(count($sheet[1])){

                    for($l=1;$l<count($sheet)+1;$l++){

                        $data=array();

                        $data[]=$l;

                        for($i=0;$i<count($column_names);$i++){

                            $data[]=System::getArrayElementValue($sheet[$l],strtoupper($column_names[$i]),'');

                            if($data[count($data)-1]=="")
                                $data[count($data)-1]=" ";

                        }

                        $list->addItem($data,$l-1);
                    }

                }
            }
        }

    }
    public function loadOpenExcelData(&$list){

        if(isset($_FILES['excelFile'])){
            $ExcelObj=\PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excelFile']['tmp_name']);

            $ExcelObj->setActiveSheetIndex(0);

            //$ExcelObj->listWorksheetInfo();

            $sheetnames=array();

            $Sheets=$ExcelObj->getAllSheets();

            for($i=0;$i<count($Sheets);$i++)
                $sheetnames[]=$Sheets[$i]->getTitle();

            $sheet=$ExcelObj->getActiveSheet()->toArray(null,true,true,true);

            $sheet_arrays=$sheet;

            if(count($sheet)>0){

                if(count($sheet[1])){

                    for($l=1;$l<count($sheet)+1;$l++){


                        $data=array();

                        //$data[]=$l;

                        $colNames=array();

                        $keys=array_keys($sheet[$l]);


                        for($g=0;$g<count($sheet[$l]);$g++)
                            if($keys[$g]=="X"){
                                $colNames[$g]=$keys[$g];
                                break;
                            }else{
                                $colNames[$g]=$keys[$g];
                            }
                        $list->setColumnNames($colNames);

                        $col_widths=array();

                        $total_width=0;

                        for($c=0;$c<count($colNames);$c++){
                            $col_widths[]='150px';
                            $total_width=count($colNames)*153;
                        }
                        $list->setColumnWidths($col_widths);

                        $list->setSize($total_width.'px','200px');



                        for($i=0;$i<count($colNames);$i++){

                            $data[]=$sheet[$l][strtoupper($colNames[$i])];
                            if($data[count($data)-1]=="")
                                $data[count($data)-1]=" ";

                        }

                        $list->addItem($data,$l-1);
                    }

                }
            }
        }

    }
    public function showReqList(){



        $list=new open_table;

        $list->canDeleteRow(true);

        $list->setColumnNames(array('No.','Description','Qty','Unit'));
        //,'Rate','Amount','Remarks'
        $list->setColumnWidths(array('10%','30%','20%','18%'));

        $list->setEditableColumns(array(1,2,3));

        $list->setNumberColumns(array(2));

        $list->setSearchColumn(1);

        $list->setHoverColor('#cbe3f8');

        //,'Rate','Amount','Remarks'
        $this->loadExcelData($list);
        //$list->addItem(array('1','','1','1','1','1','rem'));

        $list->showTable();

        return $list->toString();

    }
    public function saveBOQ($data,$pid){
        for($i=0;$i<count($data);$i++){
            $this->QLib->saveBOQItem($pid,$data[$i][0],$data[$i][2],$data[$i][1],$data[$i][3],$data[$i][4]);
        }
    }
    public function excelImporter($extended=false,$fields=array(),$tranId=0,$no_settings=false,$wrp=""){

        $cont=new objectString();

        $exwrap="";

        if(!$no_settings)
            $exwrap='<div class="excSet"></div><div class="excSetWrap">'.$this->excelSettings($extended,$fields).'</div>';

        $cont->generalTags('<form action="" enctype="multipart/form-data" id="excelForm" method="post"><div class="q_row" style="margin-top:0px;margin-bottom:5px;"><label class="impLabel" title="Click to select file" for="excelFile">Upload Excel File</label><div class="excSetLoadWrap">'.$this->uploadWindow().'</div>'.$exwrap.$wrp.'</div>');

        $cont->generalTags('<input type="hidden" name="uid" id="uid" value="'.$tranId.'" />');

        $cont->generalTags('<div style="width:0px;height:0px;overflow:hidden;"><input type="file" name="excelFile" id="excelFile" value="" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" style="width:-0.9;height:-0.9;z-index:0;"/></div></form>');

        return $cont->toString();

    }
    public function searchContent(){

        $cont=new objectString;

        $cont->generalTags('<div class="cTitle">Find Client<div title="Close Window" style="float:right;color:#f00;margin-right:5px" class="cls">X</div></div>');

        $srch=new input;

        $srch->setTagOptions('style="border-radius:20px;"');

        $srch->setClass('txtField');

        $srch->input('text','txtF');

        $cont->generalTags('<div class="q_row" style="border-bottom:1px solid #ddd;padding-bottom:7px;"><div class="label">Find </div>'.$srch->toString().'<div class="addBtn" title="Add Client">+</div></div>');

        $list=new list_control;

        $list->setColumnNames(array('No','Client Name'));

        $list->setColumnSizes(array('10%','87%'));

        $list->setSize("100%",'100px');

        $list->showList();

        $cont->generalTags('<div class="q_row" style="margin-top:0px">'.$list->toString().'</div>');

        return $cont->toString();

    }
    public function saveRequisition($rq,$sid,$rid=-1,&$reqId=0){

        return $this->QLib->saveRequisition($rq,$sid,$rid,$reqId);

    }
    public function updateRequisitionItems($rq,$tid=0){

        $id=0;

        for($i=0;$i<count($rq);$i++){
            $id=$rq[$i][count($rq[$i])-1];

            $rate=$rq[$i][3];

            /*if($this->ud->user_userType==0){
				$rate=0;
			}*/



            //echo preg_match('/e/i',$rq[$i][count($rq[$i])-1]);
            if(preg_match('/e/i',$rq[$i][count($rq[$i])-1])){

                $r=$this->QLib->getRequisitions('where id='.$tid);
                for($g=0;$g<count($r);$g++){

                    $qty=System::getArrayElementValue($rq[$i],1,0)==''? -1: System::getArrayElementValue($rq[$i],1,0);
                    $rate=System::getArrayElementValue($rq[$i],3)==''? 0: System::getArrayElementValue($rq[$i],3);
                    $amount=System::getArrayElementValue($rq[$i],4)==''? 0: System::getArrayElementValue($rq[$i],4);
                    //if(System::getArrayElementValue($rq[$i],1,0)==""){}else{
                    $this->QLib->saveRequestItem($r[$g]->req_id,$r[$g]->req_projectId,$r[$g]->req_siteId,$rq[$i][0],$qty,System::getArrayElementValue($rq[$i],2),$rate,$amount,$i);
                    //}
                }

            }else{

                $this->QLib->updateRequestItems($rq[$i][count($rq[$i])-1],str_replace("'","''",$rq[$i][0]),$rq[$i][1],$rate,System::getArrayElementValue($rq[$i],2,''),System::getArrayElementValue($rq[$i],4,0),System::getArrayElementValue($rq[$i],5,''),$i);
            }

        }

        $this->QLib->updateRequestItemNumber($tid);

        if(!preg_match('/e/i',$id)){
            $rI=$this->QLib->getRequestItems('where id='.$id);

            for($c=0;$c<count($rI);$c++){
                //$this->ud->user_userType
                //switch($this->QLib->getCurrentLevel($rI[$c]->item_requestId)){
                if(in_array($this->QLib->getCurrentLevel($rI[$c]->item_requestId)-1,$this->QLib->getActualLevels(System::nameValueToSimpleArray($this->QLib->getPositionApprovalLevels($this->ud->user_userType),true)))){
                    //if(in_array($this->QLib->getCurrentLevel($rI[$c]->item_requestId)-1,$this->QLib->getActualLevels(System::nameValueToSimpleArray($this->QLib->getPositionApprovalLevels($this->ud->user_userType),true)))){

                    $levelFinder=System::nameValueToSimpleArray($this->QLib->getPositionApprovalLevels($this->ud->user_userType),true);

                    //echo $this->QLib->getCurrentLevel($rI[$c]->item_requestId);

                    $canApprove=array();

                    if(count($levelFinder)>0){
                        $where='where id='.implode(' or id=',$levelFinder);
                        $alevs=$this->QLib->getALevels($where);
                        foreach($alevs as $cn)
                            $canApprove[]=$cn->value;
                    }

                    //print_r($canApprove);


                    if(in_array($this->QLib->getCurrentLevel($rI[$c]->item_requestId)-1,$this->QLib->getActualLevels(System::nameValueToSimpleArray($this->QLib->getPositionApprovalLevels($this->ud->user_userType),true)))){
                        $this->QLib->updateRequestLevel($rI[$c]->item_requestId,($this->QLib->getCurrentLevel($rI[$c]->item_requestId)));
                        //if($this->QLib->levelHasPrivilege($levelFinder,$this->QLib->getCurrentLevel($rI[$c]->item_requestId)))
                    }
                    /*case 0:
				$rq=$this->QLib->getRequisitions('where id='.$rI[$c]->item_requestId);
				for($x=0;$x<count($rq);$x++){
					if($rq[$x]->req_level0==0){
					 $this->notifyUsers('General Manager',array('General Manager'=>$this->getSiteName($rq[$x]->req_siteId).'\'s Requisition '.$rq[$x]->req_no.' awaiting approval'),4,0,true,true,$rq[$x]->req_projectId);
					 $this->QLib->updateRequestLevel($rI[$c]->item_requestId,0);

					}else if(($rq[$x]->req_level0==0)&($rq[$x]->req_level4==0)){
					  $this->QLib->updateRequestLevel($rI[$c]->item_requestId,0);
					}
				}
				break;/*

				/*case 1:
				$rq=$this->QLib->getRequisitions('where id='.$rI[$c]->item_requestId);
				for($x=0;$x<count($rq);$x++){
					if($rq[$x]->req_level1==0){
					 $this->notifyUsers('Procurement',array('Procurement'=>$this->getSiteName($rq[$x]->req_siteId).'\'s Requisition '.$rq[$x]->req_no.' awaiting approval'),4,0,true,true,$rq[$x]->req_projectId);
					 $this->QLib->updateRequestLevel($rI[$c]->item_requestId,1);
					}else if(($rq[$x]->req_level3==1)&($rq[$x]->req_level4==0)){
					  $this->QLib->updateRequestLevel($rI[$c]->item_requestId,4);
					}
				}
				break;

				case 2:
				$rq=$this->QLib->getRequisitions('where id='.$rI[$c]->item_requestId);
				for($x=0;$x<count($rq);$x++){
				$this->notifyUsers('Accounts,Verification Officer',array('Accounts'=>$this->getSiteName($rq[$x]->req_siteId).'\'s Requisition '.$rq[$x]->req_no.' awaiting approval','Verification Officer'=>$this->getSiteName($rq[$x]->req_siteId).'\'s Requisition '.$rq[$x]->req_no.' awaiting verification'),4,0,true,true,$rq[$x]->req_projectId);
			    $this->QLib->updateRequestLevel($rI[$c]->item_requestId,2);
				}
				break;

				case 3:
				$rq=$this->QLib->getRequisitions('where id='.$rI[$c]->item_requestId);
				for($x=0;$x<count($rq);$x++){
				$this->notifyUsers('General Manager',array('General Manager'=>$this->getSiteName($rq[$x]->req_siteId).'\'s Requisition '.$rq[$x]->req_no.' awaiting approval'),4,0,true,true,$rq[$x]->req_projectId);
			    $this->QLib->updateRequestLevel($rI[$c]->item_requestId,3);
				}
				break;

				case 4:
				$rq=$this->QLib->getRequisitions('where id='.$rI[$c]->item_requestId);
				for($x=0;$x<count($rq);$x++){
				$this->notifyUsers('Director,Adminitrator',array('Director'=>$this->getSiteName($rq[$x]->req_siteId).'\'s Requisition '.$rq[$x]->req_no.' awaiting approval','Administrator'=>$this->getSiteName($rq[$x]->req_siteId).'\'s Requisition '.$rq[$x]->req_no.' has been approved on all stages'),4,0,true,true,$rq[$x]->req_projectId);
			    $this->QLib->updateRequestLevel($rI[$c]->item_requestId,5);
				}
				break;*/
                }
            }
        }
        return new name_value(true,System::successText('Update Successful'));
    }
    public function getRDet($id){

        $reqs=$this->QLib->getRequisitions('where id='.$id);

        for($i=0;$i<count($reqs);$i++){

            return $reqs[$i];
        }

    }
    public function getSiteName($id){

        $sites=$this->getSites('where id='.$id);

        for($i=0;$i<count($sites);$i++){
            return $sites[$i]->site_name;
        }

        return '';

    }
    public function getSiteProjectId($id){

        $sites=$this->getSites('where id='.$id);

        for($i=0;$i<count($sites);$i++){
            return $sites[$i]->site_project;
        }

        return '';

    }
    public function manageRequisition($opt=0){

        if($opt==0){
            return $this->QLib->viewRequisitions();
        }else{
            return $this->QLib->newRequisition();
        }

    }
    public function searchInventory($stxt=""){
        $res="";
        if(trim($stxt)!=""){
            $si=$this->QLib->getSiteItems('where description like \'%'.str_replace("'","''",$stxt).'%\'');
            for($i=0;$i<count($si);$i++){
                $res.='<div class="searchRW" id="src'.$i.'"><div class="dvdata" id="sn_1">'.$si[$i]->item_description.'</div><div class="dvdtata" style="display:none;" id="sn_3">'.$si[$i]->item_unitType.'</div><div class="dvdtata" style="display:none;" id="ed_2"></div></div>';
            }
            return '<div class="innerSearch">'.$res.'</div>';
        }else{
            return "";
        }
    }
    //-----------------------------------------------PRIVILEGE MANAGEMENT--------------------------------
    public function privilegeManager(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags('<div class="innerTitle">Manage Positions & Privileges</div>');

        $cont->generalTags('</div>');

        $pType=new input;

        $pType->setClass("quince_select");

        $pType->addItems(array(new name_value("Positions",1),new name_value("Privileges",2)));

        $pType->select("pType");

        //$cont->generalTags('<div class="q_row"><div id="label" style="width:80px;">Manage</div><div class="qs_wrap">'.$pType->toString().'</div></div>');

        $cont->generalTags($this->userPositions());

        return $cont->toString();

    }
    public function userPositions(){

        $cont=new objectString;

        //$cont->generalTags('<div class="profWrap" style="margin-top:20px;">');

        $txtPosition=new input;

        $txtPosition->setClass('txtField');

        $txtPosition->setId('txtPos');

        $txtPosition->input('text','pos');

        $addPosBtn=new input;

        $addPosBtn->setClass('form_button addPos');

        $addPosBtn->setTagOptions('style="margin-top:-1px;margin-left:10px;"');

        $addPosBtn->input('button','saveBtn','+ Add Position');

        $cont->generalTags('<div class="q_row"><div id="label">New Position</div>'.$txtPosition->toString().$addPosBtn->toString().'</div>');

        $cont->generalTags('<div class="q_row" id="mPosition">'.$this->showPositionList().'</div>');

        //$cont->generalTags('</div>');

        return $cont->toString();

    }
    public function showPositionList(){

        $cont=new objectString;

        $list=new open_table;

        $list->setColumnNames(array('No.','Position',' ',' '));

        $list->canDeleteRow(true,2);

        $list->setColumnWidths(array('10%','40%','20%','23%'));

        $pos=$this->QLib->getDynamicPositions(false,'where id<>'.$this->ud->user_userType);

        for($i=0;$i<count($pos);$i++){
            $list->addItem(array($i+1,$pos[$i]->name,'<div class="shwDet" id="lpm_'.$pos[$i]->value.'"></div>'),$pos[$i]->value);
            $list->addDataRow($this->expandDiv($pos[$i]->value, $pos[$i]->name));
        }

        $list->showTable();

        $cont->generalTags($list->toString());

        return $cont->toString();

    }
    public function addPosition($pos){
        $this->QLib->addPosition($pos);
    }
    public function manageApprovalLevels(){
        $cont=new objectString;

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags('<div class="innerTitle">Manage Approval Levels</div>');

        $cont->generalTags('</div>');

        $txtNA=new input;

        $txtNA->setClass('txtField');

        $txtNA->setId('txtNa');

        $txtNA->input('text','tNa','');

        $addAl=new input;

        $addAl->setClass('form_button');

        $addAl->setId('addAl');

        $addAl->setTagOptions('style="margin-left:10px;margin-top:-1px;"');

        $addAl->input('button','addAl','Add Level');

        $level=new input;

        $level->setClass('quince_select');

        $level->setId('levelA');

        $level->addItem(-1,'Select Level');

        $level->addItems(array(new name_value('Level One',0),new name_value('Level Two',1),new name_value('Level Three',2),new name_value('Level Four',3),new name_value('Level Five',4),new name_value('Level Six',5)));

        $level->select('level');



        $cont->generalTags('<div class="q_row"><div id="label">New Level</div>'.$txtNA->toString().' '.'<div class="qs_wrap" style="margin-left:10px;">'.$level->toString().'</div>'.$addAl->toString().'</div>');

        $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

        $cont->generalTags('<div class="q_row" id="aLev">');

        $cont->generalTags($this->approvalLevelsList());

        $cont->generalTags('</div>');

        return $cont->toString();
    }
    public function approvalLevelsList(){

        $list=new open_table;

        $list->canDeleteRow(true,3);

        $list->setColumnNames(array('No','Level Title','Level',' '));

        $list->setColumnWidths(array('10%','50%','10%','20%'));

        $levels=$this->QLib->getALevels();

        $lv=array('Level One','Level Two','Level Three','Level Four','Level Five','Level Six');

        for($i=0;$i<count($levels);$i++){
            $list->addItem(array($i+1,$levels[$i]->name,$lv[$levels[$i]->value],'<div class="shwDet" id="apl_'.$levels[$i]->other.'"></div>'),$levels[$i]->other);
            $list->addDataRow($this->expandDiv($levels[$i]->other, $levels[$i]->name . ' Level Privileges'));
        }
        $list->showTable();

        return $list->toString();

    }
    public function addALevel($title,$level){
        return $this->QLib->addALevel($title,$level);
    }
    //---------------------------------------------END PRIVILEGE MANAGEMENT---------------------------------
    //-----------------------------------------------ALERTS-------------------------------------------------
    public function getUserAlertTotals(){
        return $this->QLib->getUserAlertTotals();
    }
    //---------------------------------------------END ALERTS-----------------------------------------------
    //---------------------------------------------SHEDULE OF WORK------------------------------------------
    public function viewScheduleOfWork(){

        $cont=new objectString;


        if($this->positionHasPrivilege($this->ud->user_userType,70)){
            $cm=$this->QLib->getMyActiveSite();

            if($cm !=null){
                $cont->generalTags($this->showComponent($cm->site_id));

                return($cont->toString());
            }else{

                $cont->generalTags("<div class='a3-center a3-left a3-text-center a3-text-red a3-full'>No site Assigned</div>");

                return $cont->toString();
            }
        }

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags('<div class="innerTitle" style="width:100%;float:left;margin-bottom:3px;">Schedule Of Works</div>');

        $cont->generalTags('</div>');

        //$cont->generalTags('<div class="q_row"><div id="label" style="font-size:16px;">New Component</div>'.$txtField->toString().'</div>');

        //$cont->generalTags('<div class="q_row"><div id="label" style="font-size:16px;">From</div>'.$txtField->toString().'</div>');

        //$cont->generalTags('<div class="q_row">'.$this->showComponent().'</div>');

        $pr=$this->QLib->getProjects();

        for($i=0;$i<count($pr);$i++)
            $cont->generalTags('<div class="req_box"><div class="at_picon" id="pIc_'.$pr[$i]->project_id.'">0</div><div class="prNs" id="pmp_'.$pr[$i]->project_id.'">'.$pr[$i]->project_name.'</div>
		</div>');


        return $cont->toString();

    }
    public function showComponent($id=0){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row">');

        $btn=new input;

        $btn->setClass('form_button addCoo');

        $btn->setTagOptions('style="float:right;margin-top:-1px;"');

        $btn->input('button','newsch','+ Add Component');

        $pr=$this->QLib->getProjects( "where id=".$id)[0];

        $cont->generalTags('<div class="thePopW"></div>');

        $cont->generalTags('<div class="innerTitle" style="width:100%;float:left;margin-bottom:3px;">Schedule Of Works - '.$pr->project_name.'</div>');

        $cont->generalTags('<div class="q_row">'.$btn->toString().'<div class="" style="display:none">Upload Scedules</div></div>');

        $cont->generalTags('<div class="thePop"></div>');

        $cont->generalTags('<div class="newSch" id="wkp_'.$id.'" style="margin-bottom:10px;display:none;">');

        $cont->generalTags($this->addResultsBar());

        $cont->generalTags('<div class="q_row">New Component</div>');

        $cmp=new input;

        $cmp->setClass('txtField');


        $cmp->setId('txtCompTitle');

        $cmp->input('text','newComponent');



        $addBtn=new input;

        $addBtn->setClass('saveData');

        $addBtn->setId('wrkSch');

        $addBtn->setTagOptions('style="" data-st="hh" ');

        $addBtn->input('button','addComp','Add Component');

        $from_date=new input;

        $from_date->setClass('quince_date');

        $from_date->setId('fromDate');

        $from_date->setTagOptions('data-begin="'.$pr->project_start.'"  data-end="'.$pr->project_end.'"');

        $from_date->input('text','text');

        $toDate=new input;

        $toDate->setClass('quince_date');

        $toDate->setTagOptions('data-begin="'.$pr->project_start.'"  data-end="'.$pr->project_end.'"');

        $toDate->setId('toDate');

        $toDate->input('text','toDate','');

        $cont->generalTags('<div class="q_row"><div style="float:left;overflow:hidden;"><div id="label" >From Date</div>'.$from_date->toString().' <div id="label" style="width:50px;margin-left:20px;">To Date</div>'.$toDate->toString().'</div></div>');

        $cont->generalTags('<div class="q_row"><div id="label">Component Title</div>'.$cmp->toString().'</div>');

        $cont->generalTags('<div class="q_row"><div id="label" style="width:80px;"></div>'.$addBtn->toString().'</div>');

        $cont->generalTags('</div>');



        $cont->generalTags('<div class="lstC jaxR" style="width:100%;float:left;">'.$this->listComponents($id).'</div>');



        $cont->generalTags('</div>');

        return $cont->toString();

    }

    public function componentImageUpLoader($id=0){

        $cont=new objectString;

        $cont->generalTags('<div style="width:100%;float:left;overflow-y:scroll;height:100%;">');

        $cont->generalTags("<div class='a3-sticky a3-left a3-full'>");

        $cont->generalTags('<div class="mesRow" style="background:#145075;color:#fff;border:none;font-size:16px;">Component Images</div>');

        $cont->generalTags('<div class="q_row" style="border-bottom:1px solid #ddd;">');

        $cont->generalTags($this->fileUploader('copass_'.$id,'copI','image/*','',true));

        $cont->generalTags('</div></div>');


        $cont->generalTags("<div class='side-images'>".$this->showComponentImages($id)."</div>");

        $cont->generalTags('</div>');

        return $cont->toString();
    }
    public function showComponentImages($id){

        $cont=new objectString;

        $numb=$this->QLib->listComponentImages($id);

        for($i=0;$i<$numb;$i++){

            $cont->generalTags($this->showImage($id.'_'.$i,'comI',0,false));

        }

        return $cont->toString();

    }
    public function listComponents($id){

        $list=new open_table;

        $list->setColumnNames(array('No','Component','From Date','To Date','Duration','Remaining','Est Cost','%',' ',' '));

        $list->setColumnWidths(array('5%','20%','10%','10%','10%','10%','5%','10%','5%','5%','5%'));

        $ws=$this->QLib->getWorkSchedule('where projectId='.$id);

        for($i=0;$i<count($ws);$i++){

            $cost=$this->QLib->getMaterialEstimates("where component_id=".$ws[$i]->wk_id);

            $list->addItem(array($i+1,$ws[$i]->wk_description,$ws[$i]->wk_frmDate,$ws[$i]->wk_toDate,($wk=$ws[$i]->wk_days>-1 ? $ws[$i]->wk_days : 0). ' days',($ww=$ws[$i]->wk_remaining>-1 ? $ws[$i]->wk_remaining : 0).' days',$ww=($cost !=null ? $cost[0]->mat_estCost : "0.00" ),$this->QLib->getMaterialUsage($ws[$i]->wk_id).'%','<div class="schSHw" id="sm_'.$ws[$i]->wk_id.'"></div>','<div class="edwk" id="ew_'.$ws[$i]->wk_id.'"></div>','<div class="lImage" id="uci_m-'.$ws[$i]->wk_id.'" title="View Upload Image"></div>'),'m-'.$ws[$i]->wk_id);

            $list->addDataRow($this->expandDiv('m-' . $ws[$i]->wk_id, $ws[$i]->wk_description));

        }


        $list->showTable();

        return $list->toString();


    }
    public function showEditSchedule($id,$edSch=''){

        $cont=new objectString;

        $wks=$this->QLib->getWorkSchedule('where id='.$id,'%d/%m/%Y');

        $cont->generalTags('<div class="mesRow" style="color: #fff;background: #145075;border: none;font-size: 16px;">Edit Component</div>');

        $cont->generalTags('<div class="q_row">'.$this->addResultsBar().'</div>');

        foreach($wks as $wk){

            $txtTitle=new input;

            $txtTitle->setClass('txtField');

            $txtTitle->setId('wkTitle');

            $txtTitle->input('text','txtTitle',$wk->wk_description);

            $cont->generalTags('<div class="q_row"><div id="label">Title</div>'.$txtTitle->toString().'</div>');

            $frmDate=new input;

            $frmDate->setId('wkFrmDate');

            $frmDate->setClass('quince_date');

            $frmDate->input('text','frmDate',$wk->wk_frmDate);

            $cont->generalTags('<div class="q_row"><div id="label">From Date</div>'.$frmDate->toString().'</div>');

            $toDate=new input;

            $toDate->setClass('quince_date');

            $toDate->setId('wkToDate');

            $toDate->input('text','frmDate',$wk->wk_toDate);

            $cont->generalTags('<div class="q_row"><div id="label">To Date</div>'.$toDate->toString().'</div>');

            $updateBtn=new input;

            $updateBtn->setClass('form_button updateComp');

            $updateBtn->setId('update_'.$id.'_'.$wk->wk_projectId);

            $updateBtn->input('button','update_comp','Update Component');

            $cont->generalTags('<div class="q_row">'.$updateBtn->toString().'');

            $cont->generalTags("<div class='sub-btn' id='dCm_".$id."' style='background:red;border-radius:4px;color:white;padding:7px 15px;margin:3px 1px;float:left'>Delete Component</div>");

            $cont->generalTags("</div>");

        }

        return $cont->toString();

    }
    public function addWorkSchedule($desc,$frm_date,$to_date,$pid){

        $this->QLib->addWorkSchedule($desc,$frm_date,$to_date,$pid);

    }
    public function updateWorkSchedule($desc,$frm_date,$to_date,$id){
        $this->QLib->updateWorkSchedule($desc,$frm_date,$to_date,$id);
    }
    public function assignApprovalLevel($app,$stat=0){

        if($stat){
            $this->QLib->addUserLevel(explode('_',$app)[1],explode('_',$app)[3]);
        }else{
            $this->QLib->deleteUserLevel(explode('_',$app)[1],explode('_',$app)[3]);
        }
    }
    //---------------------------------------------END OF SCHEDULE OF WORK
    /*  --------------Start of  report fucntions -----------*/

    public function newAssetInvestForm() {
        $cont=new objectString();

        $cont->generalTags("<figure> ");

        $cont->generalTags("<figcaption>");

        $cont->generalTags("Graphical representation");

        $cont->generalTags("</figcaption>");

        $cont->generalTags("<div class='button-container a3-full'>");

        $cont->generalTags("<button class='button a3-padding a3-border a3-green a3-left a3-round a3-margin-right a3-hover-lime-green'>Purchases</button>");

        $cont->generalTags("<button class='button a3-padding a3-border a3-blue a3-left a3-round a3-margin-right a3-hover-lime-green'>Expenses</button>");

        $cont->generalTags("<button class='button a3-padding a3-border a3-light-grey a3-left a3-round a3-margin-right a3-hover-lime-green'>Material Usage</button>");

        $cont->generalTags("<button class='button a3-padding a3-border a3-left a3-round a3-margin-right a3-hover-lime-green' style='background:#acf7aa;'>Plant and Equipment</button>");

        $cont->generalTags("<button class='button a3-padding a3-border a3-left a3-round a3-margin-right a3-hover-lime-green' style='background:#2b3d34;color:white'>Labour</button>");


        $cont->generalTags("<button class='button a3-padding a3-border a3-orange a3-right a3-round  a3-hover-lime-green'><i class='fas fa-print  a3-right'></i>Pdf print</button>");

        $cont->generalTags("<button class='button a3-padding a3-border a3-orange a3-right a3-round  a3-hover-lime-green a3-margin-right'><i class='fas fa-file-invoice a3-right '></i>Excel Download</button>");


        $cont->generalTags("</div>");

        $cont->generalTags("<setion class='a3-left a3-full report-container'>");

        $cont->generalTags('<svg viewBox="-1 -1 2 2" style="transform: rotate(-90deg)"></svg>');

        $cont->generalTags("</section>");

        $cont->generalTags("</figure>");


        return($cont->toString());

    }

    public function reportArray(){
        $arr=array();

        return(json_encode($arr));
    }
    public function reportObject(){

    }
    public function reportContainer(){

    }

    /*----------------end of  report fucntions----------- */

    //--------------------------------------------COMPANY MANAGEMENT----------------------------------------
    public function manageCompanies(){

        $usr=$this->um->getUsers("where id=".$this->ud->user_id)[0];


        $cont=new objectString;

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags('<div class="innerTitle">Company Master</div>');

        $cont->generalTags('</div>');

        $cont->generalTags("<div class='q_row'>");

        $cont->generalTags("<div class='a3-right a3-padding a3-margin-left a3-blue a3-pointer' onClick='promtUser(this)'>Create Company</div>");

        $cont->generalTags("</div>");

        $cont->generalTags('<div class="q_row">'.$this->companyList().'</div>');

        $as=System::shared("assist");

        if($this->ud->user_id ==2){
            return $as->manageCompanies();
        }else{
            return $cont->toString();
        }


    }
    public function companyList(){

        $list=new open_table();

        $comps=$this->QLib->getCompanies();

        $list->canDeleteRow(true,4);

        $list->setColumnNames(array('No.','Company Name','Status','Date Created',' '));

        $list->setColumnWidths(array('10%','30%','20%','15%','10'));

        for($i=0;$i<count($comps);$i++){
            $list->addItem(array($i+1,$comps[$i]->company_name,'',$comps[$i]->company_created,'<div id="comp_'.$comps[$i]->company_id.'" class="shwDet"></div>'),$comps[$i]->company_id);
            $list->addDataRow($this->expandDiv($comps[$i]->company_id, $comps[$i]->company_name));
        }

        $list->showTable();

        return $list->toString();

    }
    public function createCompanyForm(){

        $cont=new objectString;

        $cont->generalTags('<div class="q_row">');

        $cont->generalTags('<div class="innerTitle">Create Company</div>');

        $cont->generalTags($this->addResultsBar());

        $cont->generalTags('</div>');

        $txtField=new input;

        $txtField->setClass('txtField');

        $txtField->setId('cname');

        $txtField->input('text','compName');

        $cont->generalTags('<div class="q_row"><div id="label">Company Title</div>'.$txtField->toString().'</div>');

        $txtPref=new input;

        $txtPref->setClass('txtField');

        $txtPref->setId('cprefix');

        $txtPref->input('text','txtPrefix');

        $cont->generalTags('<div class="q_row"><div id="label">Company Prefix</div>'.$txtPref->toString().'</div>');

        $saveBtn=new input;

        $saveBtn->setClass('saveData');

        $saveBtn->setId('s_company');

        $saveBtn->input('button','saveBtn','Create Company');

        $cont->generalTags('<div class="q_row">'.$saveBtn->toString().'</div>');

        return $cont->toString();

    }
    public function companyMasterMenu(){
        return $this->QLib->companyMasterMenu();
    }
    public function createNewCompany($companyName,$prefix){

        return $this->QLib->createNewCompany($companyName,$prefix);

    }
    //------------------------------------------END COMPANY MANAGEMENT--------------------------------------
    public function positionHasPrivilege($pos,$type){
        return $this->QLib->positionHasPrivilege($pos,$type);
    }
    public function viewPayList(){
        return $this->QLib->viewPaymentHistory();
    }
    public function membersList($whereclause=""){
        return $this->QLib->viewMembersList($whereclause);
    }
    public function investMenu(){
        return $this->QLib->assetInvestMenu();
    }
    public function verifyMenu(){
        return $this->QLib->verifyMenu();
    }
    public function contribMenu(){
        return $this->QLib->projectMenu();
    }
    public function sitesMenu(){
        return $this->QLib->sitesMenu();
    }
    public function manageMembers(){

        return $this->QLib->showMembers();
    }
    public function reqMenu(){
        return $this->QLib->requisitionMenu();
    }
    public function inventoryMenu(){
        return $this->QLib->inventoryMenu();
    }
    public function labourMenu(){
        return $this->QLib->labourMenu();
    }
    public function usersMenu(){
        return $this->QLib->usersMenu();
    }
    public function purchaseMenu(){
        return $this->QLib->purchaseMenu();
    }
    public function equipmentMenu(){
        return $this->QLib->equipmentMenu();
    }
}
?>