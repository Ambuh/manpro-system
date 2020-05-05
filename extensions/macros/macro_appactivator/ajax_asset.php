<?php

include_once("request_handler.php");

class ajax_run{
	public $request;
	public function __construct(){
	
		$this->request = new requestHandler;
		
	}
	public function main($par){
		
		parse_str(str_replace("%","=",str_replace("#","&",base64_decode($par))),$arr);
		
						
		if(!isset($arr['rtyp'])){
		   echo json_encode(array("Name"=>"Bad Request","Message"=>"Request Not Understood","Status"=>"0","object_type"=>0,
			"Object_values"=>$this->genererateActivationObject()));
		  return;
		}
		
		
		
		//$this->request->;
	        
		switch($arr['rtyp']){
		
		case 0: //createUser and activate
			$this->request->createUser($arr);
		break;
		
	        case 1://softwareactivation
	        	if(!$this->request->userAuthentication($arr))
			return;
	        	$this->request->productActivation($arr);
	        	break;
		
	        default:
		
		 echo json_encode(array("Name"=>"","Message"=>"Request Not Understood","Status"=>"1","object_type"=>1,
			"Object_values"=>$this->genererateActivationObject()));
		
		}
	
	}

	
}

?>