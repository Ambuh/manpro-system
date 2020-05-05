<?php
ini_set('display_errors','on');
class ajax_run{
    public $qr;
	public $um;
	public $mailer;
    public function __construct(){
        $this->qr=System::shared('proman_lib');
		$this->um=System::shared('usermanager');
		$this->sms=System::shared('smstool');
		$this->mailer=System::shared('sharedmailer');
    }	
	public function main(){
				
        switch($_GET['sq']){
			case 1:
				$this->generateContent(true,'<div class="lspin"></div><i style="width:100%;float:left;font-size:25px;text-align:center;">Creating '.$_POST['YCompany'].'....</i>');
				break;
				
			case 2:
				$theId=0;
				$this->qr->createNewCompany($_POST['YCompany'],$this->qr->prefixWCount(substr(str_replace(' ','',$_POST['YCompany']),0,4)),$theId);
				$this->generateContent(true,'<div class="lspin"></div><i style="width:100%;float:left;font-size:25px;text-align:center;">Creating user '.$_POST['YName'].'....</i>',$theId);
				break;
				
			case 3:
				$id=0;
				$company=$this->qr->getCompany(System::getArrayElementValue($_POST,'companyId'));
				
                $this->qr->addPosition('Administrator',$id,$company->company_prefix);
		       
				$privileges=$this->qr->getDynamicPrivileges();
				
		        for($i=0;$i<count($privileges);$i++){
			      if(($privileges[$i]->value!=-1)&($privileges[$i]->value!=70))
			       $this->qr->addRemovePrivileges($privileges[$i]->value,1,$id,$company->company_prefix);
		        }
		       $theId=$this->um->createNewUser(System::getArrayElementValue($_POST,'FName').' '.System::getArrayElementValue($_POST,'SName'),System::getArrayElementValue($_POST,'YEmail'),System::getArrayElementValue($_POST,'YPass'),$id,$company->company_id,0);
				//$this->sms->sendMessage('Hi '.System::getArrayElementValue($_POST,'FName').',thank you for regestering. You will be contacted shortly',System::getArrayElementValue($_POST,'YPhone'));
				$_POST['logid']=time();
				//$this->sms->sendMessage('Hi Linus,'.System::getArrayElementValue($_POST,'FName').' from '.$_POST['YCompany'].' has registered.Tel:'.System::getArrayElementValue($_POST,'YPhone').' Email:'.System::getArrayElementValue($_POST,'YEmail'),'O715661851');
				//$this->sendEmailMessage('linus@duatech.co.ke','New User','Hi Linus,'.System::getArrayElementValue($_POST,'FName').' from '.$_POST['YCompany'].' has registered.Tel:'.System::getArrayElementValue($_POST,'YPhone').' Email:'.System::getArrayElementValue($_POST,'YEmail'));
				$this->login_access();
				
				$this->generateContent(true,'<div class="lspin"></div><i style="width:100%;float:left;font-size:18px;text-align:center;">Thank you '.System::getArrayElementValue($_POST,'FName').'.Please begin by adding the positions in your organization.</i>');
				break;
				
			case 4:
				sleep(5);
				//$this->qr->createNewCompany();
				$this->generateContent(true,'LOGGED_OUT');
				break;
				
			case 5:
				$this->generateContent(true,$this->resetPanel(1));
				break;
				
			case 6:
				$usrs=$this->um->getUsers('where username=\''.$_POST['email'].'\'');
				if(count($usrs)>0){
				     $this->generateContent(true,$this->resetPanel(2,$_POST['email']));
				}else{
					 $this->generateContent(false,System::getWarningText('Sorry!! Email address does not registered.'));
				}
				break;
			case 7:
				
				$usrs=$this->um->getUsers('where activation_code=\''.$_POST['resetcode'].'\' and username=\''.$_POST['rEmail'].'\'');
				if(count($usrs)==0){
				  $this->generateContent(false,System::getWarningText('Invalid Reset Code!'));
				}else{
				  $this->generateContent(true,$this->resetPanel(3,$_POST['rEmail']));
				}
				//$this->generateContent(true,$_POST['rEmail']);
				break;
				
			case 8:
				$res=$this->um->updateUserPassword2($_POST['rEmail'],$_POST['pass1'],$_POST['pass2'],false);
				if($res->name="success"){
					$this->generateContent(true,$this->resetPanel(4,''));
				}else{
					$this->generateContent(false,'');
				}
				break;
		
		}
		
	}
   public function sendEmailMessage($message_to,$subject,$content){
        //echo $message_to.' '.$subject.' '.$content;
		$mes=new SMessage;
		$mes->message_subject=$subject;
		$mes->message_to=$message_to;
		$mes->message_content=$content;
		$mes->message_from="noreply@app.duatech.co.ke";
		$this->mailer->sendMessage($mes,'linus@duatech.co.ke');
		return System::successText('Email sent successfully');
	}
public function login_access(){

 $sh=System::shared("usermanager");
  
 if(isset($_POST['USR_SUBMIT'])){
   $status=false;
   $res=$sh->getUsers("where password='".sha1($_POST['u_pass'])."' and username='".str_replace("'","#",$_POST['u_email'])."' and status=1");
   $expired_sessions=array();
   if(isset($_SESSION[System::getSessionPrefix().'logid'])){
     $expired_sessions=unserialize($_SESSION[System::getSessionPrefix().'logid']);
   }
   if((count($res)>0)&(!in_array($_POST['logid'],$expired_sessions))){
	$satus=true;
	$expired_sessions[]=$_POST['logid'];
	$_SESSION[System::getSessionPrefix()."USER_LOGGED"]="usid_".$res[0]->user_id."_".$res[0]->user_name."_".$res[0]->user_type."_".$res[0]->user_branch."_".$res[0]->user_company."_".$res[0]->user_username;
	$sh->recordAccess($res[0]->user_id,$res[0]->user_name);
	define("USER_LOGGED","usid_".$res[0]->user_id."_".$res[0]->user_name."_".$res[0]->user_type."_".$res[0]->user_branch."_".$res[0]->user_company."_".$res[0]->user_username);
	//header("location:".$_SERVER['REQUEST_URI']);
	$_SESSION[System::getSessionPrefix().'logid']=serialize($expired_sessions);
   }else{
     if(in_array($_POST['logid'],$expired_sessions)){
	  define("USR_MESSAGE","");
	  $status=true;
	 }
   }
      
   if(!$status)
   define("USR_MESSAGE",System::getWarningText("Login failed.Please enter the correct login details!","text-align:center;"));
 }else{
   if(isset($_SESSION[System::getSessionPrefix().'USER_LOGGED'])&(System::decipherUrl()!="Sign_Out")){
	   define("USER_LOGGED",$_SESSION[System::getSessionPrefix().'USER_LOGGED']);
   }
   
   //echo System::decipherUrl();
	 
   if(System::decipherUrl()=="Sign_Out"){
	   if(isset($_SESSION[System::getSessionPrefix().'USER_LOGGED']))
	   	unset($_SESSION[System::getSessionPrefix().'USER_LOGGED']);   
   }
   define("USR_MESSAGE","");
 }
}
public function generateContent($status=false,$content="",$topMenu="",$message="",$divName="",$divSuf=""){
        
        if($status){
            
            echo json_encode(array("Status"=>"Success","Content"=>$content,"TopMenu"=>$topMenu,"Message"=>$message,"DivName"=>$divName,'DivSufix'=>$divSuf));
        
        }else{
        
            echo json_encode(array("Status"=>"Failed","Content"=>$content,"TopMenu"=>$topMenu,"Message"=>$message,"DivName"=>$divName,'DivSufix'=>$divSuf));
            
        }
        
}
public function resetPanel($level=1,$email=""){
    $cont=new objectString;
	switch($level){
	case 1:
	$cont->generalTags('<div class="popTitle">Request Password Reset</div>');
			
	$cont->generalTags($this->addResultsBar());
			
    $cont->generalTags('<div class="form_row" style="text-align:center;font-size:16px;margin-top:20px;">Enter your email address.</div>');
	
	$email=new input;
	
	$email->setClass('txtField');
			
	$email->setTagOptions('style="width:40%;margin-left:30%;text-align:center;"');
			
	$email->setId('cemail');
			
	$email->input('text','email');
			
	$cont->generalTags('<div class="form_row" style="margin-top:10px;text-align:center;margin-top:30px;">'.$email->toString().'</div>');
	
	$but=new input;
			
	$but->setClass('form_button');
			
	$but->setId('resBnn');
			
	$but->setTagOptions('style="width:50%;margin-left:25%;"');
			
	$but->input('button','resetP','Request Password');
	
	$cont->generalTags('<div class="q_row" style="margin-top:10px;text-align:center;margin-top:30px;width:100%;">'.$but->toString().'</div>');
			
	break;
			
	case 2:	

	$this->um->setResetRequest($email,$rcods);
	
	$users=$this->um->getUsers('where username=\''.$email.'\'');
	
	for($i=0;$i<count($users);$i++){	
	  $this->sendEmailMessage($email,'Password Reset Code','Hi '.$users[$i]->user_name.', please use the following code to reset your password <b>Code:</b>'.$rcods.'');
	}		//$this->login_access();
			
	$cont->generalTags('<div class="popTitle">Enter Reset Code</div>');
			
	$cont->generalTags($this->addResultsBar());
			
	$cont->generalTags('<div class="form_row" style="text-align:center;font-size:16px;margin-top:20px;color:#0d991a;">Please enter the reset code sent to <b class="resetEmail">'.$email.'</b> below.</div>');
			
	$rcode=new input;
	
	$rcode->setClass('txtField');
			
	$rcode->setTagOptions('style="width:40%;margin-left:30%;text-align:center;"');
			
	$rcode->setId('rcode');
	
	$rcods=null;
			
	$rcode->input('text','rcode','');
			
	$cont->generalTags('<div class="form_row" style="margin-top:10px;text-align:center;margin-top:30px;">'.$rcode->toString().'</div>');		
	$but=new input;
			
	$but->setClass('form_button');
			
	$but->setId('resBnn2');
			
	$but->setTagOptions('style="width:50%;margin-left:25%;"');
			
	$but->input('button','resetP','Submit');
	
	$cont->generalTags('<div class="q_row" style="margin-top:10px;text-align:center;margin-top:30px;width:100%;">'.$but->toString().'</div>');
			
	break;
	
	case 3:
	$cont->generalTags('<div class="popTitle">Enter New Password.</div>');
	
	$pass1=new input;
	
	$pass1->setClass('txtField');
			
	$pass1->setTagOptions('style="width:40%;margin-left:30%;text-align:center;"');
			
	$pass1->setId('pass1');
			
	$pass1->input('password','Password1');
			
	$cont->generalTags('<div class="form_row" style="text-align:center;font-size:16px;margin-top:20px;color:#0d991a;">New Password</div>');
	
	$cont->generalTags('<div class="form_row" style="text-align:center;font-size:16px;margin-top:20px;color:#0d991a;">'.$pass1->toString().'</div>');
			
	$cont->generalTags('<div class="form_row" style="text-align:center;width:100%;float:left;font-size:16px;margin-top:20px;color:#0d991a;">Repeat  Password</div>');
			
	$pass2=new input;
	
	$pass2->setClass('txtField');
			
	$pass2->setTagOptions('style="width:40%;margin-left:30%;text-align:center;"');
			
	$pass2->setId('pass2');
			
	$pass2->input('password','Password2');	
			
	$cont->generalTags('<div class="form_row" style="text-align:center;font-size:16px;;width:100%;float:left;margin-top:20px;color:#0d991a;">'.$pass2->toString().'</div>');
	
	$but=new input;
			
	$but->setClass('form_button');
			
	$but->setId('resBnn3');
			
	$but->setTagOptions('style="width:50%;margin-left:25%;"');
			
	$but->input('button','resP','Submit');
	
	$cont->generalTags('<div class="q_row" style="margin-top:10px;text-align:center;margin-top:30px;width:100%;">'.$but->toString().'</div>');
			
	break;
		
	case 4:
		$cont->generalTags('<div class="popTitle">Password Changed.</div>');
			
		$cont->generalTags('<div class="form_row" style="text-align:center;font-size:16px;margin-top:20px;color:#0d991a;">Password Changed Successfully!</div>');
			
		break;
			
    }
	return $cont->toString();
}
private function addResultsBar($theId=""){
		
		$cont=new objectString;
		
		$cont->generalTags('<div style="width:100%;float:left;overflow:hidden"><div class="results_wrap" id="'.$theId.'"></div></div>');
		
		return $cont->toString();
}
	
}
?>