<?php

function usermanager(){

  return new userManage;

}

class userManage{

 private $db;

 public function __construct(){

  GLOBAL $db;

  $this->db=$db;

 }


 public function getUsers($whereclause=""){

   $data=array();

   $res=$this->db->selectQuery(array('id','user','username','password','status','user_type','activation_code','company_id','branch_id',"date_format(date_created,'%d-%b-%Y')"),"frontend_users",$whereclause,'','');

   while($row=mysqli_fetch_row($res)){

     $us=new fUser;

	 $us->user_id=$row[0];

	 $us->user_name=$row[1];

	 $us->user_username=$row[2];

     $us->user_password=$row[3];

	 $us->user_status=$row[4];

     $us->user_type=$row[5];

     $us->user_company=$row[7];

     $us->user_branch=$row[8];

	 $us->user_dateCreated=$row[9];

	 $data[]=$us;

   }

   return $data;

 }

 public function changeUserStatus($ids=array(),$status=0){



   $whereclause="";



   if(count($ids)>0){

     $whereclause=" where id=".implode(" or id=",$ids);

   }



   $this->db->updateQuery(array("status={$status}"),'frontend_users',$whereclause,'');



   return new name_value(true,System::successText('User status changed successfully.'));



 }

 public function createNewUser($name,$username,$password,$type,$company,$branch){



    $usrs=$this->getUsers(" where username='".$username."'");



    if(count($usrs)>0)

    return new name_value(false,System::getWarningText("Email address already exists in the system"));



    $this->db->insertQuery(array('user','username','password','user_type','company_id','branch_id'),'frontend_users',

    array("'".$name."'","'".$username."'","sha1('".$password."')",$type,$company,$branch),'','');



    return new name_value(true,System::successText('User created successfully.'));

 }

 public function updateUserPassword($id,$old_pass,$newpass){



   if($old_pass==$newpass)

   return new name_value("Failed",System::getWarningText("Password mismatch"));



   if($newpass!=""){

    $usrs=$this->getUsers(" where id='".$id."' and password=sha1('".$old_pass."')");

	$haschange=false;

	for($i=0;$i<count($usrs);$i++){

	  $haschange=true;

      $this->db->updateQuery(array("password=sha1('".$newpass."')"),"frontend_users"," where id=".$usrs[$i]->user_id,'');



	}

	if(!$haschange)

	return new name_value("Failed",System::getWarningText("Operation failed. Incorrect old password."));



    return new name_value("Success",System::successText("Password changed successfully."));



   }else{



   return new name_value("Failed",System::getWarningText("Operation failed. Invalid new password."));



   }

 }



 public function getUserDetails($id){

	 $udet=array();

	 $res=$this->db->selectQuery(array("*"),"user_details","where user_id=".$id,'','');

	 while($row=mysqli_fetch_row($res)){

	   $det=new userDetails;

	   $det->details_userid=$row[0];

	   $det->details_emailaddress=$row[1];

	   $det->details_cellphone=$row[2];

	   $det->details_country=$row[3];

	   $det->details_location=$row[4];

	   $det->details_lastlogin=$row[5];

	   $det->id=$row[6];

	   $udet[]=$det;

	 }

	 //=new userDetails;

	 return $udet;

 }
    function setResetRequest($email,$rcods){

    }

    public function readUserDetails(){



    if(!isset($_SESSION[System::getSessionPrefix().'USER_LOGGED']))

    return;



    $dets=explode("_",$_SESSION[System::getSessionPrefix()."USER_LOGGED"]);



    $user_id=$dets[1];



    $basic_dets=null;



    $other_dets=null;



    $us=$this->getUsers("where id=".$user_id);



    for($i=0;$i<count($us);$i++)

    $basic_dets=$us[$i];



    $od=$this->getUserDetails($user_id);



    for($i=0;$i<count($od);$i++)

    $other_dets=$od[$i];



    if(count($od)==0){

      $this->initializeUserDetails($user_id);

      return $this->readUserDetails();

    }



    return new name_value("User Details",$basic_dets,$other_dets);



 }

 private function initializeUserDetails($userId){



    $this->db->insertQuery(array('user_id'),'user_details',array($userId));



 }

 public function recordAccess($user_id=0,$user_name=""){



	 $this->db->insertQuery(array('user_id','user_name','access_ip'),'access_logs',array($user_id,"'".$user_name."'","'".$_SERVER['REMOTE_ADDR']."'"),'','');



 }

 public function getAccessLogs($whereclause=""){



	 $res=$this->db->selectQuery(array('*'),'access_logs',$whereclause,'');



	 $results=array();



	 while($row=mysqli_fetch_row($res)){

		$ac=new accessLog;

		$ac->log_date=$row[0];

		$ac->log_user=$row[2];

		$ac->log_ip=$row[3];

		$results[]=$ac;

	 }

	 return $results;

 }

 public function updateUserDetails($userDetOb){



    $this->db->updateQuery(array("email_address='".$userDetOb->details_emailaddress."'","cellphone='".$userDetOb->details_cellphone."'","country='".$userDetOb->details_country."'","location='".$userDetOb->details_location."'"),

    'user_details',"where user_id=".$userDetOb->details_userid,'');



    return new name_value("Success",System::successText("Details updated successfully."));

 }

 public function deleteUser($userId){

    $this->db->deleteQuery("frontend_users","where id=".$userId,'');

 }

 public function updateUserType($type,$id){

	 $this->db->updateQuery(array('user_type='.$type),'frontend_users','where id='.$id,'');

 }

 public function updateUserStatus($status=0,$id=0){

	 $this->db->updateQuery(array('status='.$status),'frontend_users','where id='.$id,'');

 }

}

class fUser{

  public $user_id;

  public $user_name;

  public $user_username;

  public $user_status;

  public $user_type;

  public $user_branch;

  public $user_company;

  public $user_password;

  public $user_dateCreated;

}

class userDetails{

  public $details_userid;

  public $details_emailaddress;

  public $details_cellphone;

  public $details_country;

  public $details_location;

  public $details_lastlogin;

  public $details_ip;

}

class accessLog{

  public $log_date;

  public $log_user;

  public $log_ip;

}
class items_req{
  public $id;
   public $name;
  public $category;
  public $available;
  public $value;
  public $desc;
}class am_assist{
  private $db;

  public function __construct(){
   GLOBAL $db;
   $this->db=$db;
  }
  public function getRegItems_db($whereclause){
		$data=array();
		$res=$this->db->selectQuery(array("*"),"pr_itemuploads",$whereclause,true);
		 while($row=mysqli_fetch_row($res)){
				$dat=new items_req();
				$dat->id=$row[0];
				$dat->name=$row[1];
			  $dat->category=$row[4];
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
	  //print_r($data);
			for ($i=0;$i<count($data);$i++){

			$list->addItem(array("<div style='padding-left:2%;color:rgb(0,0,0,0.7);' id='nm_".$data[$i]->id."' class='sage'>".ucfirst(strtolower($data[$i]->name)."</div>"),'Enter value'),"rw_".$data[$i]->id);
			//$list->addDataRow($this->expandDiv($data[$i]->id,$data[$i]->name));
		    }
			$list->showTable();

		return $list->toString();

  }

  function req_data_insertion($lid){
      $helper=new theProLib;

      $arr=json_decode($lid);
	  
       $site=$helper->getMyActiveSite();
	  
	  if(isset($_POST['sId'])){
		   $siteId=$_POST['sId'];
	   }else{
		   $site=$helper->getMyActiveSite();
	  
            if($site !=null)
                $siteId=$site->site_id;
	   }
    
      $helper->saveRequisition($arr,$siteId);
	  
  }
  function table_display($data){
    $cont=new objectString;
    $submit= new input;

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

      $list->addItem(array("<div style='padding-left:2%;color:rgb(0,0,0,0.7);' id='".$c_arr[0]."' class='sage'>".ucfirst($c_arr[1])."</div>",$c_arr[2],"<button class='ed edm'></button>","<button class='del' id=".$c_arr[0]."></button>"));

   }
   $list->showTable();

   $cont->generalTags("<div class='m_tables'>".$list->toString()."</div>");

   $cont->generalTags("<div class='m_roll'>".$submit->toString().$cancel->toString()."</div>");

   return $cont->toString();
  }

  function get_table_content($const){
    switch($const){

		case 1:

			return $this->table_display(System::getArrayElementValue($_POST,'dt'));

			break;

		case 2:

        	return $this->req_data_insertion(System::getArrayElementValue($_POST,"dat"));

			break;


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
     public function adaptedist($var){
         if($var==0){
             $cont=new objectString();
             $cont->generalTags("<div class='tble_am'>");
             $cont->generalTags("<div class='tble_header'>Add New Material</div>");
             $list=new open_table;
		     $list->setColumnNames(array('No','Description','Quantity'));
		     $list->setColumnWidths(array('4%','20%','20%'));
             $list->setNumberColumns(array(3));
             $list->setEditableColumns(array(1,2));
		     $list->setHoverColor('#cbe3f8');
             $list->addItem(array($var+1,"",""));
		     $list->showTable();
		     $cont->generalTags("<div>". $list->toString()."</div>");
             $cont->generalTags("</div>");
             return $cont->toString();
         }else{
             $cont=new objectString();
             $cont->generalTags("<div id='lstid-".$var++."' class='trow al1'  >");
             $cont->generalTags("<div id='cl_0_0' class='cells' style='text-align:left;width:4%'>".$var++."</div>");
             $cont->generalTags("<div id='cl_0_1' class='cells'  style='text-align:left;width:20%'></div>");
             $cont->generalTags("<div id='cl_0_2' class='cells'  style='text-align:left;width:20%'></div>");
             $cont->generalTags("</div>");
             return $cont->toString();
         }


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

	 public function getTables(){
		 //select table_name from information_schema.tables where table_name like 'pr_%' and table_schema='proman'
		//select table_name,table_schema from information_schema.tables where table_name like '%pr_%' and table_schema='proman'
		 
		 $data=array();
		 
		 $res=$this->db->selectQueryCustom(array("table_name"),"information_schema.tables"," where table_name like '%pr_%' and table_schema='proman'");
		 
		 while($row=mysqli_fetch_row($res)){
		    $data[]=$row[0];	 
		 }
		 
		 return $data;
	 }
	function createTableManpro($data){
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
	function createTbl(){
		$pref=$this->createTableManpro($this->getTables());
		$tables=$this->getMainTables();
		for($i=0;$i<count($pref);$i++){
			for($p=0;$p<count($tables);$p++){
				$tmp=explode("_",$tables[$p]);
				  if(count($tmp) >0){
					  $imp=$this->db->insertQueryCustom("CREATE TABLE IF NOT EXISTS " .$pref[$i]."_".$tmp[1]." like ".$tables[$p]."  ");
				     
				  }
				
			}
		}
		return "1";
	}
	function getMainTables(){
		$data=array();
		
		$res=$this->db->selectQuery(array(" table_name ")," information_schema.tables ","where table_name like 'pr_%' and table_schema='proman'");
		
		while ($row=mysqli_fetch_row($res)){
			$data[]=$row[0];
		}
		return $data;
	}

}

?>
