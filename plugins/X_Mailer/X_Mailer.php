<?php
function X_Mailer(){



}
class mailMessenger{



public function sendMessageById($message){

GLOBAL $db;

$users=$db->getUserDetails($message->message_to);

 for($i=0;$i<count($users);$i++){

   mail($users->email_address,$message->message_subject,$message->message_content,$message->message_header);

 }

}

public function sendMessage($message){
$message->message_header="From: admin@".$_SERVER['HTTP_HOST']." \r\n";

$message->message_header.="MIME-Version:1.0\r\n";

$message->message_header.="Content-Type: text/html; charset=ISO-8859-1 \r\n";

$message->message_content=str_replace("</br/>","<br/>",$message->message_content);

$message->message_content="<html><body>".$message->message_content."</body></html>";

mail($message->message_to,$message->message_subject,$message->message_content,$message->message_header);


}

}

class XMessage{

public $message_to;

public $message_from;

public $message_subject;

public $message_content;

public $message_header;

}

?>