<?php
function md_adbox($id){

 $data=System::getModuleSettingsData($id);
 
 $ads=System::getArrayElementValue($data,"advalues");
 
 $sec=3;
 
 if(System::getArrayElementValue($data,"ad_sec")!="")
 $sec=System::getArrayElementValue($data,"ad_sec");



 if($ads!=""){
  
  $randomize=false;
  
  $vals=unserialize($ads);
  
  $selected=array();
  
  $c=0;
  
  while ($c<$sec){
	  $randomize=true;
	  for($u=0;$u<$sec+1;$u++){
	   $dat=rand(0,count($vals)-1);
	   if(!in_array($dat,$selected)){
	   $selected[]=$dat;
	   $c++;
	   }
	   
	  }
  }
  
  $width="220";
  
  if(System::getArrayElementValue($data,"ad_width")!="")
   $width=System::getArrayElementValue($data,"ad_width");
  
  for($i=0;$i<count($vals);$i++){
  
  if(($randomize)&(!in_array($i,$selected)))
   continue;
   
    echo "<div style=\"width:{$width}px;float:left;orverflow:hidden;margin-right:10px;\"><a href=\"".$vals[$i]->name."\" target=\"_blank\"><img  src=\"".str_replace("../",System::getFolderBackJump(),$vals[$i]->value)."\" style=\"width:{$width}px;\" /></a></div>";
  
 
  }
 
 }

}
?>