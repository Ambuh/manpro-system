<?php
class searchHelper{
	private $static_options;
	private $showcars;
	private $previous_values=array();
	public function __construct(){
	 $this->static_options=System::shared("staticoptions");
	 $this->showcars=System::shared("showcars");
	 
	 $items=System::nameValueToSimpleArray(System::getPostedItems("sr"));
	 $prevs=System::nameValueToSimpleArray(System::getPostedItems("sr"),true);
	 
	 if(count($items)>0){
	 
	  $_SESSION[System::getSessionPrefix()."_myserch"]=serialize($prevs);
	 
	 }
	 
	 if(isset($_SESSION[System::getSessionPrefix()."_myserch"])&&($_SESSION[System::getSessionPrefix()."_myserch"]!="")){
	   
	    $this->previous_values=unserialize($_SESSION[System::getSessionPrefix()."_myserch"]);
	   
	  }
	 
	}
	public function searchInterface(){
		echo $this->searchContent();
	}
	private function searchContent(){
		
		$cont=new objectString;
		
		$input=new input;
		
		$cont->generalTags("<form action=\"http://".$_SERVER['HTTP_HOST'].ROOT_DIR."\" method=\"post\">");
		
		$input->setClass("form_input");
		
		$cont->generalTags("<div id=\"s_col\" style=\"margin-left:10px\">");
		
		$input->setTagOptions("style=\"width:285px;\"");
		
		$input->addItems($this->static_options->staticBodyOptions());
		
		$input->setSelected(System::getArrayElementValue($this->previous_values,0));
		
		$input->select("sr_body_type");
		
		$cont->generalTags("<div id=\"s_title\">Body Type</div>");
		
		$cont->generalTags("<div id=\"form_row\">{$input->toString()}</div>");
		
		$cont->generalTags("<div id=\"s_title\">Price Range</div>");
		
		$max=new input;
		
		$max->addItems($this->static_options->maxPrice());
		
		$max->setClass("form_input");
		
		$max->setTagOptions("style=\"width:130px;\"");
		
		$max->setSelected(System::getArrayElementValue($this->previous_values,2));
		
		$max->select("sr_maxprice");
		
		$min=new input;
		
		$min->setTagOptions("style=\"width:130px;\"");
		
		$min->addItems($this->static_options->minPrice());
		
		$min->setSelected(System::getArrayElementValue($this->previous_values,1));
		
		$min->setClass("form_input");
		
		$min->select("sr_minprice");
		
			
		$cont->generalTags("<div id=\"form_row\">{$min->toString()} <div style=\"float:left;margin-right:10px;padding:5px;\"></div> {$max->toString()}</div>");
		
		
		$search_btn=new input;
		
		$search_btn->setClass("form_button");
		
		$search_btn->setTagOptions("style=\"float:right;margin-right:30px;padding-left:20px;padding-right:20px;\"");
		
		$search_btn->input("submit","my_search","Search");
				
		
		
	    //$cont->generalTags("<div id=\"form_row\" style=\"margin:0px;\">{$search_btn->toString()}</div>");		
				$cont->generalTags("</div>");
		
		
		
		$cont->generalTags("<div id=\"s_col\">");
		$cont->generalTags("<div id=\"s_title\">Make/Model</div>");
		
		$make=new input;
		
		$make->addItems($this->static_options->make());
		
		$make->setSelected(System::getArrayElementValue($this->previous_values,3));
		
		$make->setDisabled(array(-1,-2));
		
		$make->setTagOptions("style=\"width:130px;\"");
		
		$make->setClass("form_input");
		
		$make->setId("sr_makes");
		
		$make->setTargetElementId("sr_model");
		
		$make->setTargetElementData($this->showcars->sortMakeAndModel(false,"Any Model"));
				
		$make->select("sr_makes");
		
		$model=new input;
		
		if(System::getArrayElementValue($this->previous_values,4)!=0){
		 $mods=$this->static_options->model(false,"where make_id=".System::getArrayElementValue($this->previous_values,3),true);
		 
		 $model->addItem(0,"Any Model");
		 
		 for($i=0;$i<count($mods);$i++){
			 $model->addItem($mods[$i]->value,$mods[$i]->name);
		 }
		 	
		}else{
			
			$model->addItem("0","Any Model");
			
		}
		
		$model->setSelected(System::getArrayElementValue($this->previous_values,4));
		
		$model->setTagOptions("style=\"width:130px;\"");
		
		$model->setClass("form_input");
		
		$model->setId("sr_model");
		
		$model->select("sr_model");
		
		$cont->generalTags("<div id=\"form_row\">{$make->toString()} <div style=\"float:left;margin-right:10px;padding:5px;\"></div> {$model->toString()}</div>");
		
		$min_year=new input;
		
		$min_year->addItems($this->static_options->years());
		
		$min_year->setSelected(System::getArrayElementValue($this->previous_values,5));
		
		$min_year->setClass("form_input");
		
		$min_year->setTagOptions("style=\"width:100px;\"");
		
		$min_year->select("sr_min_year");
		
		$max_year=new input;
		
		$max_year->addItems($this->static_options->years(false));
		
		$max_year->setClass("form_input");
		
		$max_year->setSelected(System::getArrayElementValue($this->previous_values,6));
		
		$max_year->setTagOptions("style=\"width:100px;\"");
		
		$max_year->select("sr_max_year");
		
		$cont->generalTags("<div id=\"s_title\">Year</div>");
		
		$cont->generalTags("<div id=\"form_row\">{$min_year->toString()}<div style=\"float:left;margin-right:10px;padding:5px;\"></div>{$max_year->toString()}</div>");
		
		$cont->generalTags("</div>");
		
		$loc=new input;
		
		$loc->addItems($this->static_options->locations());
		
		$loc->setTagOptions("style=\"width:300px;\"");
		
		$loc->setSelected(System::getArrayElementValue($this->previous_values,7));
		
		$loc->setClass("form_input");
		
		$loc->select("sr_loc");
		
		$cont->generalTags("<div id=\"s_col\">");
		
		$cont->generalTags("<div id=\"s_title\">Location</div>");
		
		$cont->generalTags("<div id=\"form_row\">{$loc->toString()}</div>");
		
		$cont->generalTags("</div>");
		
		
		//------------------------------advanced search row---------------------------
		$hide=false;
		if((preg_match("/Any/i",System::getArrayElementValue($this->previous_values,8)))&&(preg_match("/Any/i",System::getArrayElementValue($this->previous_values,10)))&&(preg_match("/Any/i",System::getArrayElementValue($this->previous_values,11)))&&(preg_match("/Any/i",System::getArrayElementValue($this->previous_values,12)))&&(preg_match("/Any/i",System::getArrayElementValue($this->previous_values,13)))){
		
		$hide=true;
		  $cont->generalTags("<div id=\"adopt\" style=\"width:100%;float:left;display:none;\"><div id=\"s_col\" style=\"margin-left:10px;\">");
		
		}else{
			
		  if(count($this->previous_values)>0){
		  $hide=false;
		  $cont->generalTags("<div id=\"adopt\" style=\"width:100%;float:left;\"><div id=\"s_col\" style=\"margin-left:10px;\">");
		  }else{
			 $hide=true;
			$cont->generalTags("<div id=\"adopt\" style=\"width:100%;float:left;display:none\"><div id=\"s_col\" style=\"margin-left:10px;\">");  
		  }
		}
		$cont->generalTags("<div id=\"s_title\">Condition</div>");
		
		$cond=new input;
		
		$cond->addItems($this->static_options->staticCondition());
		
		$cond->setSelected(System::getArrayElementValue($this->previous_values,8));
		
		$cond->setClass("form_input");

	    $cond->setTagOptions("style=\"width:130px;\"");
		
		$cond->select("sr_Condition");
				
		$dtype=new input;
		
		$dtype->addItems($this->static_options->staticDriveType());
		
		$dtype->setSelected(System::getArrayElementValue($this->previous_values,9));
		
		$dtype->setTagOptions("style=\"width:130px;\"");
		
		$dtype->setClass("form_input");
		
		$dtype->select("sr_dtype");
		
		$cont->generalTags("<div id=\"form_row\">{$cond->toString()} <div style=\"float:left;margin-right:10px;padding:5px;\"></div> {$dtype->toString()}</div>");
		
		$cont->generalTags("<div id=\"s_title\">Transmission</div>");
		
		$trans=new input;
		
		$trans->addItems($this->static_options->staticTransmission());
		
		$trans->setTagOptions("style=\"width:130px;\"");
		
		$trans->setSelected(System::getArrayElementValue($this->previous_values,10));
		
		$trans->setClass("form_input");
		
		$trans->select("sr_trans");
		
		$col=new input;
		
		$col->addItems($this->static_options->staticColour());
		
		$col->setTagOptions("style=\"width:130px;\"");
		
		$col->setSelected(System::getArrayElementValue($this->previous_values,11));
		
		$col->setClass("form_input");
		
		$col->select("sr_color");
		
		$cont->generalTags("<div id=\"form_row\">{$trans->toString()} <div style=\"float:left;margin-right:10px;padding:5px;\"></div> {$col->toString()}</div>");
		
		$cont->generalTags("</div>");
		
		$cont->generalTags("<div id=\"s_col\">");
		
		$cont->generalTags("<div id=\"s_title\">Drive Setup</div>");
		
		$drive=new input;
		
		$drive->addItems($this->static_options->driveSetup());
		
		$drive->setSelected(System::getArrayElementValue($this->previous_values,12));
		
		$drive->setTagOptions("style=\"width:160px;\"");
		
		$drive->setClass("form_input");
		
		$drive->select("sr_drive");
		
		$cont->generalTags("<div id=\"form_row\">{$drive->toString()} <div style=\"float:left;margin-right:10px;padding:5px;\"></div></div>");
		
		$cont->generalTags("<div id=\"s_title\">Fuel Type</div>");
		
		$fuel=new input;
		
		$fuel->addItems($this->static_options->staticFuelType());
		
		$fuel->setSelected(System::getArrayElementValue($this->previous_values,13));
		
		$fuel->setTagOptions("style=\"width:160px;\"");
		
		$fuel->setClass("form_input");
		
		$fuel->select("sr_fuel");
		
		$cont->generalTags("<div id=\"form_row\">{$fuel->toString()} <div style=\"float:left;margin-right:10px;padding:5px;\"></div></div>");
		
		$cont->generalTags("</div>");
		
		$cont->generalTags("<div id=\"adopt\" style=\"text-decoration:underline;color:#fff;width:100%;float:left;text-indent:5px;cursor:pointer;\" ><div style=\"float:left;text-decoration:underline;\" onclick=\"hideShow('adopt','moreopt')\">- Hide Options</div> {$search_btn->toString()}</div>");
		//----------------------end rows--------------------------
		
		$cont->generalTags("</div>");
		
		if($hide){
		
		$cont->generalTags("<div id=\"moreopt\" style=\"text-decoration:underline;color:#fff;float:left;text-indent:5px;width:100%;cursor:pointer;\" ><div style=\"float:left;text-decoration:underline;\" onclick=\"hideShow('moreopt','adopt')\" >+ More Options</div> {$search_btn->toString()}</div>");
		}else{
			
		 $cont->generalTags("<div id=\"moreopt\" style=\"text-decoration:underline;display:none;color:#fff;float:left;text-indent:5px;width:100%;cursor:pointer;\" ><div style=\"float:left;text-decoration:underline;\" onclick=\"hideShow('moreopt','adopt')\" >+ More Options</div> {$search_btn->toString()}</div>");

		}
		$cont->generalTags("</form>");
		//-------------------------------------------------
		return $cont->toString();
		
	}
	
}
?>
