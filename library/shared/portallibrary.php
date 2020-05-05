<?php
function portallibrary(){
	return new portallibrary;
}
class portallibrary{
	private $db;
	public function __construct(){
	  GLOBAL $db;
	  $this->db=$db;
	}
	public function savePortalService($portal_service){
	    
	  $services=$this->getPortalServices("where service_name='".$portal_service->service_name."'");
	  
	  if(count($services)>0)
	  return new name_value(System::getWarningText("Service name already exists!"),false);
	  
	  $this->db->insertQuery(array("service_name","service_type","service_cost","serviceterms"),"subscription_services",array("'".$portal_service->service_name."'",$portal_service->service_type,$portal_service->service_cost,$portal_service->service_terms));
	  
	  return new name_value(System::successText("Service added successfully"),true);
	  
	}
	public function getPortalServices($whereclause){
	  $res=$this->db->selectQuery(array("*"),"subscription_services",$whereclause);
	  $portal_services=array();
	  while($row=mysqli_fetch_row($res)){
		  $serv=new poratalService;
		  $serv->service_id=$row[0];
		  $serv->service_name=$row[1];
		  $serv->service_type=$row[2];
		  $serv->service_cost=$row[3];
		  $serv->service_terms=$row[4];
		  $portal_services[]=$serv;
	  }
	  return $portal_services;
	}
	public function saveSubscribedService($subscribed_service){
		$subscribed_service=new subscribedServices;
		
		$this->db->insertQuery(array(),"");
		
	}
	public function getSubscribedServices($client_id){
	}
	public function saveSoftware($software){
	}
	public function getSoftware($whereclause){
	}
	public function saveSoftwareCopy($software_copy){
	}
	public function getSoftwareCopies($client_id){
	}
	public function saveConfigData($config_data){
	}
	public function getSoftwareConfig($whereclause){
	}
	public function saveInvoice(){
	}
	public function getInvoices($whereclause){
	}
	public function debitAccount($owner_id,$description,$amount){
	}
	public function creditAccounts($owner_id,$description,$amount){
	}
	public function getAccountRecord($whereclause){
		
	}
}
class poratalService{
	public $service_id;
	public $service_name;
	public $service_type;
	public $service_cost;
	public $service_terms;
}
class subscribedServices{
	public $service_client;
	public $service_id;
	public $service_name;
	public $service_type;
	public $service_cost;
	public $service_term;
}
class software{
	public $software_id;
	public $software_name;
	public $sofware_description;
	public $software_sofwarecode;
	public $software_dateadded;
	public $software_softwareversion;
	public $software_status;
	public $software_productflyer;
	public $software_type;
	public $software_systemreq;
	public $software_downloadlink;
}
class softwareCopies{
	public $copy_id;
	public $copy_sofwareid;
	public $copy_clientid;
	public $copy_status;
	public $copy_machinedetails;
	public $copy_lastactivation;
	public $copy_expirydate;
	public $copy_dateaquired;
	public $copy_code;
}
class sofwareConfiguration{
	public $config_clientId;
	public $config_softwareid;
	public $config_copycode;
	public $config_configdata;
}
class invoices{
	public $invoice_id;
	public $invoice_date;
	public $invoice_clientId;
	public $invoice_subtotal;
	public $invoice_tax;
	public $invoice_total;
}
class accounts{
	public $trans_id;
	public $trans_description;
	public $trans_credit;
	public $trans_debit;
	public $trans_balanceamount;
}
?>