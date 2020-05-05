<?php
function mpesa($uid){
   return new mpesaClass($uid); 
}
class mpesaClass{
    private $db;
    private $userId;
    public function __construct($userId){
        
        GLOBAL $db;
        
        $this->userId=$userId;
        
        $this->db=$db;
        
    }
    //--------------------------------------------Begin interface functions--------------------------------------------
    
    public function getPrePayInfo($amount="",$hideprice=false){
      if(!$hideprice){
      return "1) Send <strong>Ksh.".number_format(($amount-$this->getPendingTransTotal()),2)."</strong> to till number <strong>505476</strong>(Dua Technologies)<br/></br>
      2) Enter M-Pesa transaction code below.";
      }else{
        return "1) Send the amount to <strong>M-Pesa</strong> till <strong>505476</strong><br/>(Dua Technologies)<br/></br>
      2) Enter <strong>M-Pesa transaction code</strong> below.";
      }   
    }
    public function getPostPayInfo(){
        
    }
    public function getPaymentDetails(){
        
    }
    public function markAsProcessed(){
        $this->markTransactionAsProcessed();
    }
    public function markAsPending($transaction_code){
        
      $this->markTransactionAsPending($transaction_code);
        
    }
    public function processTransaction($trans_code="",$credentials,$posted_fields){
        
        if($trans_code=="")
        return json_encode(array("status"=>'Failed',"response_message"=>System::getWarningText('Please enter a valid transaction code.')));
        
        //echo $trans_code;
        
        if($this->getFromLocalTransaction($trans_code)!=null)
        return json_encode(array("status"=>'Failed',"response_message"=>'Transaction code has already been used.'));
        
        $rawres=$this->getTransactionDetails($trans_code="",$credentials,$posted_fields);
        
        $trans_dets=json_decode($rawres);
        
         if($trans_dets->status=="Success"){
            
            $trans=new mpesaTrans;
            $trans->trans_code=$trans_dets->transcode;
            $trans->trans_userid=$this->userId;
            $trans->trans_status=1;
            $trans->trans_date=$trans_dets->trans_date;
            $trans->trans_number=$trans_dets->number;
            $trans->trans_sender=$trans_dets->sender;
            $trans->trans_amount=$trans_dets->amount;
            
            $this->saveTransaction($trans);
            
            return $rawres;
         }
        
        //$this->saveTransaction();
        
        return $rawres;
    }
    //----------------------------------------------end interface functions---------------------------------------------
    
    //----------------------------------------------begin primary functions---------------------------------------------

    private function getTransactionDetails($transaction_code,$credentials,$post_fields){
       
       $session=curl_init();
       
       $post_fields['username']=$credentials->pay_username;
       $post_fields['password']=$credentials->pay_password;
       
       $fields="";
       
       foreach($post_fields as $key=>$value){
         $fields.=$key.'='.$value.'&';
         rtrim($fields,'&');
       }
       
       curl_setopt($session,CURLOPT_URL,$credentials->pay_url);
       curl_setopt($session,CURLOPT_RETURNTRANSFER,true);
       curl_setopt($session,CURLOPT_HEADER,false);
       curl_setopt($session,CURLOPT_POST,count($post_fields));
       curl_setopt($session,CURLOPT_POSTFIELDS,$fields);
       
       $results=curl_exec($session);
       
       curl_close($session);
       
       
       return $results; 
        
    }
    private function suspendDuplicate($transaction_code){
        
        $trans=$this->getTransactions("where transaction_code='".$transaction_code."'");
        
        if(count($trans)>0){
            $duplicates=array();
            for($i=1;$i<count($trans);$i++){
                $duplicates[]=$trans->trans_id;
            }
            $wherelause="";
            
            if(count($duplicates)>0)
            $wherelause="where id=".implode(" or id=",$duplicates);
            
            $this->db->updateQuery(array('transaction_status=-3'),'lite_m_pesatransactions',$wherelause);
        }
        
        
    }
    private function getFromLocalTransaction($trans_code){
        
       $t_code=$this->getTransactions("where transaction_code='".$trans_code."' and transaction_status=1");
       
       for($i=0;$i<count($t_code);$i++){
         return $t_code[$i];
       }
    
       return null;
       
    }
    private function markTransactionAsPending($trans_code=""){
        
        $this->db->updateQuery(array('transaction_status=-1'),'lite_m_pesatransactions',"where transaction_code='".$trans_code."'");
    
    }
    private function markTransactionAsProcessed(){
        
        $this->db->updateQuery(array('transaction_status=1'),'lite_m_pesatransactions',"where transaction_status=-1 and user_id=".$this->userId);
    
    }
    private function saveTransaction($tranObj){
        
        if((trim($tranObj->trans_code)=="")|($tranObj->trans_userid==0))
        return false;
        
        $this->db->insertQuery(array('transaction_code','user_id','transaction_amount','transaction_status','transaction_date','sender','sender_number')
        ,'lite_m_pesatransactions',array("'".$tranObj->trans_code."'",$tranObj->trans_userid,$tranObj->trans_amount,$tranObj->trans_status,
        "'".$tranObj->trans_date."'","'".$tranObj->trans_sender."'","'".$tranObj->trans_number."'"));
        
        return true;
        
    }
    private function getPendingTrans(){
        
       return $this->getTransactions(" where transaction_status=-1 and user_id=".$this->userId);
        
    }
    public function getPendingTransTotal(){
        
        $total=0;
        
        $pendingTrans=$this->getPendingTrans();
        
        for($i=0;$i<count($pendingTrans);$i++)
        $total+=$pendingTrans[$i]->trans_amount;
        
        return $total;
        
    }
    
    private function getTransactions($whereclause=""){
        
       $res=$this->db->selectQuery(array('*'),'lite_m_pesatransactions',$whereclause);
       
       $results=array();
       
       while($row=mysqli_fetch_row($res)){
         $tranObj=new mpesaTrans;
         $tranObj->trans_id=$row[0];
         $tranObj->trans_code=$row[1];
         $tranObj->trans_userid=$row[2];
         $tranObj->trans_amount=$row[3];
         $tranObj->trans_status=$row[4];
         $tranObj->trans_date=$row[5];
         $tranObj->trans_processedDate=$row[6];
         $tranObj->trans_sender=$row[7];
         $tranObj->trans_number=$row[8];
         $results[]=$tranObj;
       }
       
       return $results;
        
    }
    //--------------------------------end primary functions-------------------------------------------
}
class mpesaTrans{
    public $trans_id;
    public $trans_code;
    public $trans_userid;
    public $trans_amount;
    public $trans_status;
    public $trans_date;
    public $trans_processedDate;
    public $trans_sender;
    public $trans_number;
}
?>