<?php
function set_main($id){
	
	$data=System::nameValueToSimpleArray(System::getPostedItems("set"));
	
	if(count($data)>0)
	System::saveModuleSettingsData($id,$data);
	
	
	$set=System::getModuleSettingsData($id);
	
	$cont=new objectString;
	
	$inp=new input;
	
	$inp->setClass("form_input");
	
	$inp->input("text","set_url",System::getArrayElementValue($set,"set_url"));
	
	$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Page url</strong></div>{$inp->toString()}</div>");
	
	$inp=new input;
	
	$inp->setClass("form_input");
	
	$inp->setTagOptions("style=\"width:70px;\"");
	
	$inp->input("text","set_width",System::getArrayElementValue($set,"set_width"));
	
	$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>width(px)</strong></div>{$inp->toString()}</div>");
	
	$inp=new input;
	
	$inp->setClass("form_input");
	
	$inp->setTagOptions("style=\"width:70px;\"");
	
	$inp->input("text","set_height",System::getArrayElementValue($set,"set_height"));
	
	$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>height(px)</strong></div>{$inp->toString()}</div>");

    $inp=new input;
	
	$inp->setClass("form_select");
	
	$inp->addItems(array(new name_value("True",1),new name_value("False",0)));
	
	$inp->setSelected(System::getArrayElementValue($set,"set_showborder"));
	
	$inp->select("set_showborder");

    $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Show Border</strong></div>{$inp->toString()}</div>");

	
	return $cont->toString();
}
?>