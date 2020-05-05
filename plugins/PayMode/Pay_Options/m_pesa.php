<?php
function PayStart($account){

  new mpesaPlugin($account);
  
}
class mpesaPlugin{
	
	public function __construct($account){
	
	  echo "Failed|Vendor account ".$account." suspended";
	
	}
}
class PayObject{
	public $mpesa_internalId;
	public $mpesa_ipnId;
	public $mpesa_origin;
	public $mpesa_destination;
	public $mpesa_receivedDate;
	public $mpesa_text;
	public $mpesa_user;
	public $mpesa_password;
	public $mpesa_code;
	public $mpesa_account;
	public $mpesa_msisdn;
	public $mpesa_transactionDate;
	public $mpesa_transactionTime;
	public $mpesa_amount;
	public $mpesa_sender;
}
?>