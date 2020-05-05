<?php
function set_main($id){

if(isset($_POST['update_module'])){

   $items=System::nameValueToSimpleArray(System::getPostedItems("cust"));

   $items['custom_edit']=base64_encode($items['custom_edit']);
    
   System::saveModuleSettingsData($id,$items);

}
	
$data=System::getModuleSettingsData($id);

$items=System::nameValueToSimpleArray(System::getPostedItems("cust"));

$cont=new objectString;

$cont->generalTags(System::LoadEditor(base64_decode(System::getArrayElementValue($data,"custom_edit")),"advanced","custom_edit","width:100%;height:300px;"));

return $cont->toString();

}
?>