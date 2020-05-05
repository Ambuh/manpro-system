<?php
function smstool(){
	return new smstools;
}
class smstools{
	public $db;
	public function __construct(){
		GLOBAL $db;
		$this->db=$db;
	}
	public function sendMessage($message,$number){
		if($number!=""){
		  $mess='{"AuthDetails": [ 
        { 
            "UserID": "534", 
            "Token": "'.md5('Dua123').'", 
            "Timestamp": "'.time().'" 
        } 
    ], 
    "MessageType": [ 
        "3" 
    ], 
    "BatchType": [ 
        "0" 
    ], 
    "SourceAddr": [ 
        "Dua_Tech" 
    ], 
    "MessagePayload": [ 
        { 
            "Text": "'.$message.'" 
        } 
    ], 
    "DestinationAddr": [ 
        { 
            "MSISDN": "'.$number.'", 
            "LinkID": "" 
        } 
    ], 
    "DeliveryRequest": [ 
        { 
            
        } 
    ]}';	
    return $this->connectMessage('http://197.248.4.47/smsapi/submit.php',$mess);
		}
	}
	public function connectMessage($URL,$json){
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$results=curl_exec($ch);
		
		curl_close($ch);
		return $results;
	}
}
?>