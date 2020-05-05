<?php
function set_main($id){

$data=System::getModuleSettingsData($id);

$width=System::nameValueToSimpleArray(System::getPostedItems("ad"));
$keys=array_keys($width);
for($i=0;$i<count($keys);$i++)
$data[$keys[$i]]=$width[$keys[$i]];

$issaved=false;

if(isset($_POST['uploadbtn'])){

 
  if(move_uploaded_file($_FILES['idet_image']['tmp_name'],"../images/data_images/".$_FILES['idet_image']['name'])){
  
     $ads=System::getArrayElementValue($data,"advalues");
	 
	 if($ads!=""){
	   $actual_data=unserialize($ads);
	   $c_data=new name_value($_POST['det_url'],"../images/data_images/".$_FILES['idet_image']['name']);
	   $actual_data[]=$c_data;
	   $data['advalues']=serialize($actual_data);
	 }else{
		 
	   $actual_data=array();
	   
	   $c_data=new name_value($_POST['det_url'],"../images/data_images/".$_FILES['idet_image']['name']);
	   
	   $actual_data[]=$c_data;
	   
	   $data['advalues']=serialize($actual_data);
	 
	 }
	 
	 System::saveModuleSettingsData($id,$data);
	 
	 $issaved=true;
  
  }
  
}
if(isset($_POST['delete_btn'])){

   $items=System::nameValueToSimpleArray(System::getPostedItems("ct"),true);
   
   $ads=System::getArrayElementValue($data,"advalues");
   
   if($ads!=""){
   
    $actual_data=unserialize($ads);
	
	$temp_data=array();
	
	for($i=0;$i<count($actual_data);$i++)
	 if(!in_array($i,$items)){
	 
	   $temp_data[]=$actual_data[$i];
	 
	 }else{
	   if(file_exists($actual_data[$i]->value))
	   unlink($actual_data[$i]->value);
	 }
   
    $data['advalues']=serialize($temp_data);
   
   }

 System::saveModuleSettingsData($id,$data);

$issaved=true;

}

if(!$issaved)
System::saveModuleSettingsData($id,$data);


 $data=System::getModuleSettingsData($id);

  $cont=new objectString;
  
  $input=new input;
  
  $input->setClass("form_input");
  
  $input->input("file","idet_image");
  
  $adwidth=new input;
  
  $adwidth->setTagOptions("style=\"width:50px;text-align:right;\"");
  
  $adwidth->setClass("form_input");
  
  $adwidth->input("text","ad_width",System::getArrayElementValue($data,"ad_width"));
  
  $cont->generalTags(System::categoryTitle("Display Settings","width:99%;margin-bottom:5px;"));
  
  $adsec=new input;
  
  $adsec->setTagOptions("style=\"width:50px;text-align:right;\"");
  
  $adsec->setClass("form_input");
  
  $adsec->input("text","ad_sec",System::getArrayElementValue($data,"ad_sec"));
  
  $cont->generalTags("<div id=\"form_row\" style=\"overflow:hidden;margin-top:5px;\"><div id=\"label\"><strong>Width(px)</strong></div>{$adwidth->toString()}</div>");
  
  $cont->generalTags("<div id=\"form_row\" style=\"overflow:hidden;margin-top:5px;\"><div id=\"label\"><strong>Ad Sections</strong></div>{$adsec->toString()}</div>");
  
  $cont->generalTags(System::categoryTitle("Manage Ads","width:99%;margin-bottom:5px;"));
  
  $link=new input;
  
  $link->setClass("form_input");
  
  $link->input("text","det_url");
  
  $cont->generalTags("<div id=\"form_row\" style=\"overflow:hidden;margin-top:5px;\"><div id=\"label\"><strong>Url</strong></div>{$link->toString()}</div>");
  
  $input2=new input;
  
  $input2->setClass("form_button");
  
  $input2->input("submit","uploadbtn","Upload Ad");
  
  $cont->generalTags("<div id=\"form_row\" style=\"overflow:hidden;\"><div id=\"label\"><strong>Image File</strong></div>{$input->toString()}</div>");
  
  $cont->generalTags("<div id=\"form_row\" style=\"overflow:hidden;margin-top:0px;\">{$input2->toString()}</div>");
  
  $del=new input;
  
  $del->setClass("form_button_delete");
  
  $del->setTagOptions("style=\"float:right;margin-right:4px;\"");
  
  $del->input("submit","delete_btn","Delete");
  
  $cont->generalTags(System::categoryTitle("Adverts ".$del->toString(),"width:99%;margin-bottom:3px;"));
  
  $cont->generalTags("<div style=\"width:99%;border:1px solid #ccc;height:250px;float:left;overflow-y:scroll;\">");
  
  $list=new open_table;
  
  $list->setColumnNames(array("checkb","cont"));
  
  $list->setColumnWidths(array("30px","300px"));
  
  $list->setHoverColor("#eee");
  
  $list->hideHeader();
  
  $vals=System::getArrayElementValue($data,"advalues");
  
  if($vals!=""){
  
  $records=unserialize($vals);
  
  for($i=0;$i<count($records);$i++){
  
  $img="<img src=\"".$records[$i]->value."\" style=\"margin:5px;\"/>";
  
  $list->addItem(array("<input type=\"checkbox\" name=\"ct_{$i}\" value =\"$i\"/>","<div id=\"irow\">$img</div><div id=\"irow\"><strong style=\"margin-left:0px;\">Link : </strong>".$records[$i]->name."</div>"));
  
  
  }
  
  }
  
  $list->showTable();
  
  $cont->generalTags($list->toString());
  
  $cont->generalTags("</div>");
  
  return $cont->toString();
  

}
?>