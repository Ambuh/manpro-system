<?php
class myHelper{
	public function defaulTpage(){
	
		$content=new objectString;
		
		$content->generalTags(System::categoryTitle("Payments Made","margin-bottom:5px;"));
		
		$input=new input;
		
		System::enableDatepicker(true);
		
		$input->makeDatePicker();
		
		$input->setClass("form_input");
		
		$input->setId("mydates");
		
		$input->input("text","mydates");
		
		$search=new input;
		
		$search->setClass("form_button");
		
		$search->setTagOptions("style=\"margin-top:0px;margin-left:15px;\"");
		
		$search->input("submit","search_btn","Search");
		
		$all_btn=new input;
		
		$all_btn->setClass("form_button_add");
		
		$all_btn->setTagOptions("style=\"margin-top:0px;margin-left:15px;\"");
		
		$all_btn->input("submit","show_all","Show All");
		
		$content->generalTags("<div id=\"search_bar\"><div id=\"form_row\"><div id=\"label\"><strong>Filter By Date</strong></div>{$input->toString()}{$search->toString()}{$all_btn->toString()}</div></div>");
		
		$content->generalTags($this->myData());
		
		$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");
		
		return $content->toString();
		
	}
	public function myData(){
		
		$list=new list_control;
		
		$list->setColumnNames(array("Date","Description","Amount","Action"));
		
		$list->setSize("875px","300px");
		
		$list->setBackgroundColour("#ffd");
		
		$list->setHeaderFontBold();
		
		$list->setColumnSizes(array("150px","200px","150px","100px"));
		
		$list->showList();
		
		return $list->toString();
		
	}
}
?>