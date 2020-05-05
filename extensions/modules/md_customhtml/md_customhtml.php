<?php 
function md_customhtml($id){
 
  $data=System::getModuleSettingsData($id);
  
  echo str_replace("../",System::getFolderBackJump(),base64_decode($data['custom_edit']));
 
}
?>