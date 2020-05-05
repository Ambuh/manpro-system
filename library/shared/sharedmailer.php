<?php
include(dirname(__FILE__)."/../shared_modules/PHPMailer_5.2.0/class.phpmailer.php");
include(dirname(__FILE__)."/../shared_modules/PHPMailer_5.2.0/class.smtp.php");
function sharedmailer(){
  return new SMailer;
}
class SMailer{
	private $theMailer;
	private $mymailer;
	private $mailer;
	private $config;
	private $QLib;
	public function __construct(){
		GLOBAL $config;
		$this->theMailer= new PHPMailer;
		$this->config=$config;
		$this->QLib=System::shared('proman_lib');
	}
	public function sendMessage($message,$reply_to="",$att=array()){
		$sett=$this->QLib->getEmailSettings();
		$this->theMailer->IsSMTP();
		$this->theMailer->Host=$sett->host;
		
		$this->theMailer->CharSet='UTF-8';
		$this->theMailer->SetLanguage("en", 'includes/phpMailer/language/');
        
        $this->theMailer->SMTPDebug=1;
        $this->theMailer->Mailer="smtp";
		$this->theMailer->SMTPAuth=true;
		$this->theMailer->SMTPSecure="ssl";
		$this->theMailer->Username=$sett->email;
		$this->theMailer->Password=$sett->password;
		$this->theMailer->Port=587;
		
		$this->theMailer->From=$message->message_from;
		$this->theMailer->FromName="ManPro";
		
		
		$this->theMailer->isHTML(true);
		$this->theMailer->AddReplyTo($reply_to);
		$this->theMailer->AddAddress($message->message_to,$message->message_toName);
         for($i=0;$i<count($att);$i++){
          $this->theMailer->AddAttachment($att[$i]);
         }
		$this->theMailer->Body=$message->message_content;
		$this->theMailer->AltBody="";
       $this->theMailer->Subject=$message->message_subject;
		
		   if(!$this->theMailer->Send()){
			return System::getWarningText($this->theMailer->ErrorInfo);
			}else{
				 return System::successText("Message sent successfully");
		    }
            
	}
	public function sendMessage1($message,$reply_to=""){
		
		$message->message_header="From: Admin eric@duatechnologies.co.ke \r\n";
		
		if($message->message_from!="")
		$message->message_header="From: ".$message->message_from." \r\n";
		
		if($reply_to!="")
		$message->message_header.="Reply-To: ".$reply_to." \r\n";
		
		$message->message_header.="MIME-Version:1.0\r\n";
		
		$message->message_header.="Content-Type: text/html; charset=ISO-8859-1 \r\n";
		
		$message->message_content=str_replace("</br/>","<br/>",$message->message_content);
		
		$message->message_content="<html><body>".$message->message_content."</body></html>";
		
		@mail($message->message_to,$message->message_subject,$message->message_content,$message->message_header);
		
		return System::successText("Message sent successfully ");
		
	}
    public function sendMessage2(gcpEmail $obj){
    $this->theMailer->IsSMTP(); // Use SMTP
    $this->theMailer->Host        = "smtp.gmail.com"; // Sets SMTP server
    $this->theMailer->SMTPDebug   = 1; // 2 to enable SMTP debug information
    $this->theMailer->SMTPAuth    = TRUE; // enable SMTP authentication
    $this->theMailer->SMTPSecure  = "tls"; //Secure conection
    $this->theMailer->Port        = 587; // set the SMTP port
    $this->theMailer->Username    = 'ambuhmwangi.personal@gmail.com'; // SMTP account username
    $this->theMailer->Password    = '@googleJacksonmburu96'; // SMTP account password
    $this->theMailer->Priority    = 1; // Highest priority - Email priority (1 = High, 3 = Normal, 5 = low)
    $this->theMailer->CharSet     = 'UTF-8';
    $this->theMailer->Encoding    = '8bit';
    $this->theMailer->Subject     = $obj->mailDescription;
    $this->theMailer->ContentType = 'text/html; charset=utf-8\r\n';
    $this->theMailer->From        = $obj->fromMail;
    $this->theMailer->FromName    = $obj->fromName;
    $this->theMailer->WordWrap    = 900; // RFC 2822 Compliant for Max 998 characters per line

        if(count($obj->images)){
            foreach ($obj->images as $image)
                $this->theMailer->AddEmbeddedImage($image[0],$image[1]);
        }$this->theMailer->addAttachment($obj->document, $obj->header.".pdf");

        foreach ($obj->emails as $email) {
            $this->theMailer->AddAddress($email[0]);
        }
   // $this->theMailer->AddAddress( "wahomelinus@gmail.com" );

    $this->theMailer->isHTML( TRUE );

  $this->theMailer->Body    = $obj->content;
  $this->theMailer->AltBody = $obj->header;
  $this->theMailer->Send();
  $this->theMailer->SmtpClose();

  if ( $this->theMailer->IsError() ) { 
    return new name_value(false ,"ERROR<br /><br />");
  }
  else {
    return  new name_value(true,"OK<br /><br />");
  }
}
		
}
		
class SMessage{
	
	public $message_to;
	
	public $message_from;
	
	public $message_subject;
	
	public $message_content;
	
	public $message_header;
    
    public $message_toName;

}

?>