<?php
function accounts(){
    
    return new MyAccounts;
    
}
class MyAccounts{
    private $db;
    public function __construct(){
        GLOBAL $db;
        $this->db=$db;
    }
    public function saveAccountRecord($type='Credit',$description=0,$amount=0,$user_id){
        $acc_ob= new AccountRecord;
        
        $values=array("'".$description."'",$user_id,$amount,($this->getPreviousBalance($user_id)+$amount));
        
        $affected_field='credit_amount';
        
        if($type=='Debit'){
        $affected_field='debit_amount';
          $values=array("'".$description."'",$user_id,$amount,($this->getPreviousBalance($user_id)-$amount));
        }
        
        $this->db->insertQuery(array('trans_description','user_id',$affected_field,'balance'),
        'lite_clientaccounts',$values);
    }
    //primary functions
    private function getPreviousBalance($user_id){
        $recs=$this->getAccountRecords("where user_id=".$user_id." order by id desc limit 1");
        for($i=0;$i<count($recs);$i++)
        return $recs[$i]->record_balance;
        
        return 0;
    }
    private function getAccountRecords($whereclause=""){
        
        $res=$this->db->selectQuery(array('*'),'lite_clientaccounts',$whereclause);
        
        $results=array();
        
        while($row=mysqli_fetch_row($res)){
            $cO=new AccountRecord;
            $cO->record_date=$row[0];
            $cO->record_description=$row[1];
            $cO->record_userid=$row[2];
            $cO->record_credit=$row[3];
            $cO->record_debit=$row[4];
            $cO->record_balance=$row[5];
            $results[]=$cO;
        }
        
        return $results;
    }
}
class AccountRecord{
    public $record_date;
    public $record_description;
    public $record_userid;
    public $record_credit;
    public $record_debit;
    public $record_balance;
}
?>