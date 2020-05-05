<?php
class requestHandler{
	public $user_details;
	public $db;
	public $software_handler;
	public function __construct(){
		GLOBAL $db;
                $this->db=$db;
                $this->software_handler=System::shared("softmanager");       
	
	}
	public function userAuthentication($arr){
	
		if(isset($arr['username']) && isset($arr['password'])){
		
			$users=$this->db->getUserDetails(" where username='".str_replace("'","",$arr['username'])."' and password='".$this->db->hashPassword($arr['password'])."' and user_status=1");
			
			for($i=0;$i<count($users);$i++){
				
				$this->user_details=$users[$i];
				return true;
				
			}
			
			echo json_encode(array("Name"=>"Authentication Error","Message"=>"Invalid username or password.","Status"=>0,"object_type"=>4,
			"Object_values"=>""));

			return false;	
		}else{
		
			echo json_encode(array("Name"=>"Authentication Error","Message"=>implode(",",array_keys($arr)),"Status"=>0,"object_type"=>4,
			"Object_values"=>""));
			
			return false;
	
		}
		
	}
	public function productActivation($arr){
	
		$copy_code="";
		
		if(isset($arr['CopyCode']))
			$copy_code=$arr['CopyCode'];
		
		$software=NULL;
		
		$softwares=$this->software_handler->getSoftwareList(" where product_code='".$arr['ProductCode']."' and status=1");
		
		for($i=0;$i<count($softwares);$i++){
			
			$software=$softwares[$i];
		}
		
		if($software==NULL){
			
			echo json_encode(array("Advert_image"=>"","Name"=>"Failed","Message"=>"Product not found or is no longer supported","Status"=>0,"object_type"=>0,"Active_image"=>base64_encode(file_get_contents(dirname(__FILE__)."/activate_icon.png")),
			"Object_values"=>"","Advert_image"=>""));
			
			return;
			
		}
		
		$res=$this->software_handler->softwareInitialisation($this->user_details->id,$software->software_id,"hardisk_serial=".$arr['HardDisk_Serial']."#MotherBoard_Serial=".$arr['MainBoard_Serial'],$arr['CopyCode']);
		
		if(!$res->name){
		echo json_encode(array("Has_advert"=>false,"Advert_image"=>"","Name"=>"Failed","Message"=>$res->value,"Status"=>0,"Object_type"=>0,"Active_image"=>base64_encode(file_get_contents(dirname(__FILE__)."/activate_icon.png")),
			"Object_values"=>"","Advert_image"=>""));
		return;
		}
		
		$res=$this->software_handler->checkTokenValidity($this->user_details->id,$software->software_id,$arr['PinCode']);
		
		if(!$res->name){
		echo json_encode(array("Has_advert"=>0,"Advert_image"=>"","Name"=>"Failed","Message"=>$res->value,"Status"=>0,"Object_type"=>1,"Active_image"=>base64_encode(file_get_contents(dirname(__FILE__)."/inactive_icon.png")),
			"Object_values"=>"","Advert_image"=>""));
		return;
		}
		
		$res->value["CopyCode"]= base64_encode($arr['PinCode']);
		
		echo json_encode(array("Has_advert"=>0,"Advert_image"=>"","Name"=>"Success","Message"=>"Software activated successfully","Status"=>1,"Object_type"=>1,"Active_image"=>base64_encode(file_get_contents(dirname(__FILE__)."/activate_icon.png")),
			"Object_values"=>$res->value,"Advert_image"=>""));
		
		return;
		
	}
       public function createUser($arr){
       
	          if(($arr['firstname']=="")){
			    echo json_encode(array("Name"=>"Failed","Message"=>"Please enter first name","Status"=>0,"object_type"=>2,
			"Object_values"=>""));
	         return;
			  }
			  
			 if(($arr['lastname']=="")){
			    echo json_encode(array("Name"=>"Failed","Message"=>"Please enter last name","Status"=>0,"object_type"=>2,
			"Object_values"=>""));
	         return;
			 }
			  
			  if(($arr['email']=="")){
			    echo json_encode(array("Name"=>"Failed","Message"=>"Please enter email","Status"=>0,"object_type"=>2,
			"Object_values"=>""));
	         return;
			  }

             if(($arr['username']=="")){
			    echo json_encode(array("Name"=>"Failed","Message"=>"Please enter username","Status"=>0,"object_type"=>2,
			"Object_values"=>""));
	         return;
			  }
			
			 if(($arr['password']=="")){
			    echo json_encode(array("Name"=>"Failed","Message"=>"Please enter password","Status"=>0,"object_type"=>2,
			"Object_values"=>""));
	         return;
			  }
				 
       	      if(count($this->db->getUserdetails("where username='".$arr['username']."'"))>0){
       	      	      
       	      	      echo json_encode(array("Name"=>"Failed","Message"=>"Username not available.Please enter a different username","Status"=>0,"Object_type"=>2,
			"Object_values"=>""));
		      return;
       	      }
       	      
			  
       	     $this->db->insertQuery(array("firstname","lastname","email","password","user_status","username","cellphone","country"),"users",
       	      	      array("'{$arr['firstname']}'","'{$arr['lastname']}'","'{$arr['email']}'","'".$this->db->hashPassword($arr['password'])."'",1,
       	      	      	      "'{$arr['username']}'","'{$arr['tel']}'","'{$arr['country']}'"));
       	      
       	      echo json_encode(array("Name"=>"Success","Message"=>$arr['username']." created successfully","Status"=>1,"object_type"=>2,
			"Object_values"=>""));
       
       }
       public function genererateActivationObject($value){
	
	 return array("status" => "",
         "token" => "",
         "application_code" => "",
         "activation_date" => "",
         "username" => "",
         "duration" => "");
        
       }
	public  function productInitialization(){
	
	}
	public function userRegistration(){
	}
	
}
?>