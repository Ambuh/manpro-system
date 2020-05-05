<?php
include("PHPMailer_5.2.0/class.phpmailer.php");
$ph=new PHPMailer;
$ph->IsSMTP();
$ph->Host="mail.yourdomain.com";
$ph->SMTPAuth=true;
$ph->Username="";
$ph->Password="";
$ph->Port=25;

$ph->SetFrom("eric@duatechnologies.co.ke");
$ph->AddReplyTo("");
$ph->from("");
$ph->AddAddress("to");
$ph->MsgHTML("");
$ph->AltBody="";
$ph->Subject="";

if(!$ph->Send()){
  echo $ph->ErrorInfo;
}else{
  echo "Message sent";
}

?>