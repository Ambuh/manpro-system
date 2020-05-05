<?php
class ajax_run{
  public function main($param){
    
	 $prof=System::shared("profiler");
  
     $trunc=substr($param,strpos($param,">")+1);
	 
	 $trunc=str_replace(substr($trunc,strpos($trunc,"<")),"",$trunc);
	 
	 //echo htmlspecialchars($trunc); 
	 $message=$prof->getMessage($trunc);
	 
	 echo System::categoryTitle("<div id=\"label\">From :</div>$message->message_sender");
	 
	 echo System::categoryTitle("<div id=\"label\">Title :</div>$message->message_title");
	 
	 echo "<div id=\"m_area\">".$message->message_content."</div>";
  
  }
}
?>