<?php
function payoptions($params){
    return new PayCLass($params);
}
class PayClass{
    private $db;
    private $payClass;
    private $userId;
    private $payObject;
    public function __construct($params=array(0,0)){
        GLOBAL $db;
        $this->db=$db;
        $this->userId=$params[0];
        
        $this->getSystemPayClass($params[1]);
        
        
    }
    private function getSystemPayClass($payOptionId){
        
       $payOb=null;
        
        if($payOptionId==0){
        
          $payOb=$this->getPayOptions('where status=1 and is_default=1');
        
        }else{
          $payOb=$this->getPayOptions('where status=1 and id='.$payOptionId);  
        }
        for($i=0;$i<count($payOb);$i++){
           $this->getPayClass($payOb[$i]->pay_payname);
           $this->payObject=$payOb[$i];
        }
    }
    private function getPayClass($option_name=""){
        
        
        if(!file_exists(dirname(__FILE__)."/../../plugins/PayOptions/".$option_name.".php"))
        return null;
        
        include_once(dirname(__FILE__)."/../../plugins/PayOptions/".$option_name.".php");
        
        $this->payClass=$option_name($this->userId);
        
    }
    private function getPayOptions($whereclause){
        
        $res=$this->db->selectQuery(array('*'),'lite_payoptions',$whereclause);
        
        $results=array();
        
        while($row=mysqli_fetch_row($res)){
            
            $payOb=new PayObject;
            $payOb->pay_id=$row[0];
            $payOb->pay_name=$row[1];
            $payOb->pay_isDefault=$row[2];
            $payOb->pay_status=$row[3];
            $payOb->pay_payname=$row[4];
            $payOb->pay_username=$row[5];
            $payOb->pay_password=$row[6];
            $payOb->pay_url=$row[7];
            
            $results[]=$payOb;
        }
        
        return $results;
        
    }
    public function getPayInfo($service_type,$hideprice=false){
        
        $price_object=$this->getServicePrice($service_type);
        
        $thePrice=0;
        
        if($price_object!=null)
        $thePrice=$price_object->price;
        
        return $this->payClass->getPrePayInfo($thePrice,$hideprice);
    }
    public function processPayment($trans_code="",$posted_fields=array()){
        
        $results=$this->payClass->processTransaction($trans_code,$this->payObject,$posted_fields);
        
        
        return json_decode($results);
        
    }
    public function markAsPending($trans_code=""){
        
        $this->payClass->markAsPending($trans_code);
        
    }
    public function markPendingAsProcessed(){
        $this->payClass->markAsProcessed();
    }
    public function getPendingTotal(){
        return $this->payClass->getPendingTransTotal();
    }
    private function getPaidAmaount($transaction_id){
        
        $service_price=0;
        
        
        //if(count($payobs)==0)
        //return -2;
        
        return -1;
    }
    public function getServicePrice($price_mode){
        
        $prices=$this->getPricing("where price_mode='".$price_mode."'");
        
        for($i=0;$i<count($prices);$i++)
        return $prices[$i];
        
        return null;
        
    }
    public function getPricing($whereclause=""){
      
      $res=$this->db->selectQuery(array('*'),'lite_pricing',$whereclause);
      
      $results=array();
       
      while($row=mysqli_fetch_row($res)){
      
       $price=new Pricing;
       $price->price_mode=$row[0];
       $price->price=$row[1];
       $price->price_isperunit=$row[2];
       $price->price_interval=$row[3];
       
       $results[]=$price;
      }
       
      return $results;
        
    }
    public function savePrice($PriceObj){
        
      $this->db->insertQuery(array("price_mode","price","is_perunit","price_interval"),'lite_pricing',
      array("'".$PriceObj->price_mode."'",$PriceObj->price,$PriceObj->price_isperunit,$PriceObj->price_interval));
        
    }
    public function updatePortalCredentials($option_name,$username,$password){
        
        $this->db->updateQuery(array("username='".$username."'","password='".$password."'"),'lite_portaldetails',"where portal_name='".$option_name."'");
        
        return name_value("Success",System::successText("Credential updated successfully"));
        
    }
   
    private  function getPortalCredentials($option_name){
        
       $res=$this->db->selectQuery(array('*'),'lite_payportaldetails',"where portal_name='".$option_name."'");
       
       $exists=false;
       
       while($row=mysqli_fetch_row($res)){
       
       $exists=true;
       
       if($row[1]!="")
       return new name_value($row[0],$row[1],$row[2]);
       
       }
       
       if(!$exists)
       $this->initializePotalCredentials($option_name);
       
       return null;
        
    }
    public function initializePotalCredentials($option_name){
        
        $this->db->insertQuery(array('portal_name'),'lite_payportaldetails',array("'".$option_name."'"));
        
    }
    
}
class PayObject{
    public $pay_id;
    public $pay_name;
    public $pay_isDefault;
    public $pay_status;
    public $pay_payname;
    public $pay_username;
    public $pay_password;
    public $pay_url;
}
class Pricing{
    public $price_mode;
    public $price;
    public $price_isperunit;
    public $price_interval;
}
?>