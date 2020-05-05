<?php
class helper{
	private $softwareHanler;
	private $Me;
	private $db;
	public function __construct(){
	        GLOBAL $db;
	        $this->db=$db;
		$this->softwareHandler=System::shared("softmanager");
		$this->Me=$this->db->getUserSession();
		
	}
	public function defaultPage(){
	
		$content=new objectString;
		
		$content->generalTags(System::categoryTitle("User Controls"));
		
		$content->generalTags("<div id=\"app_window\"></div>");
		
		$content->generalTags(System::categoryTitle("My Apps","margin-bottom:5px"));
		
		$content->generalTags($this->myAppList());
		
		$content->generalTags(System::categoryTitle("Subscribed Services","margin-bottom:5px"));
		
		$content->generalTags($this->subscribedList());
		
		$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");
		
		return $content->toString();
		
	}
	public function viewAppDetails(){
		
		$content=new objectString;
		
		$softwares=$this->softwareHandler->getClientsSoftwares($this->Me->id," and software_id=".System::getCheckerNumeric("id"));
		
		for($i=0;$i<count($softwares);$i++){
		
		$status;
		
		//$content->generalTags($this->softwareHandler->saveToken($this->softwareHandler->createToken($status,0,0,10,false),0,0,3));
			
		$content->generalTags(System::categoryTitle("App Details".System::backButton("?"),"margin-bottom:10px;"));
		
		$content->generalTags("<div style=\"width:380px;margin-bottom:5px;padding-left:5px;\">");
		
		$content->generalTags(System::categoryTitle("Preview","background:#cbe3f8;border:none;"));
		
		$content->generalTags("</div>");
		
		$content->generalTags("<div style=\"width:470px;float:right;border-Left:1px solid #bbb;margin-bottom:5px;padding-left:5px;\">");
				
		$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Software Name</strong></div>{$softwares[$i]->software_name}</div>");
		
		$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Product Type</strong></div>{$softwares[$i]->software_type}</div>");
		
		$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Product Code</strong></div>{$softwares[$i]->software_code}</div>");
		
		$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Status</strong></div>{$softwares[$i]->software_status}</div>");
		
		$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Terms</strong></div></div>");
		
		$content->generalTags("</div>");
		
		$content->generalTags("<div id=\"inner_plain\">");
		
		$content->generalTags(System::categoryTitle("Other Details","margin-bottom:5px;"));
		
		$content->generalTags("<div style=\"width:380px;margin-bottom:5px;padding-left:5px;float:left;\">");
		
		$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Allowed Copies</strong></div></div>");
				
		$content->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Installed</strong></div></div>");
		
		$content->generalTags("</div>");
		
		$content->generalTags("<div style=\"width:470px;float:right;border-Left:1px solid #bbb;margin-bottom:5px;padding-left:5px;\">");
		
		$content->generalTags(System::categoryTitle("Installation History","margin-bottom:5px;"));
		
		$list=new list_control;
		
		$list->setColumnNames(array("No","Copy Id","Activation Date","Status"));
			
		$list->setColumnSizes(array("50px","150px","150px","100px"));
		
		$list->setBackgroundColour("#ffc");
		
		$list->setSize("465px","170px");
		
		$list->showList();
		
		$content->generalTags($list->toString());
		
		$content->generalTags("</div>");
		
		$content->generalTags("</div>");
		
		$content->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #444;\"></div>");
		
		}
		
		return $cont=$content->toString()=="" ? System::getWarningText("Invalid Url or Product id"): $content->toString();
	        
	}
	private function myAppList(){
	
		$list=new list_control;
		
		$this->Me->id;
		
		$softwares=$this->softwareHandler->getClientsSoftwares($this->Me->id);
		
		$list->setColumnNames(array("","Software Name","Version","Product Code"));
		
		$list->setColumnSizes(array("50px","250px","120px","250px"));
		
		$list->setHeaderFontBold();
		
		$list->setBackgroundColour("#ffd");
		
		for($i=0;$i<count($softwares);$i++){
		
		$list->addItem(array($i+1,"<a href=\"?sub=2&id={$softwares[$i]->software_id}\">{$softwares[$i]->software_name}</a>",$softwares[$i]->software_version,$softwares[$i]->software_code));
		
		}
		
		$list->setAlternateColor("#cbe3f8");
		
		$list->setSize("875px","100px");
		
		$list->showList();
		
		return $list->toString();
	
	}
	private function subscribedList(){
	
		$list=new list_control;
		
		$list->setListId("subs");
		
		$list->setBackgroundColour("#ffd");
		
		$list->setColumnNames(array("","Service Id","Subscription Date","Expiry","Status"));
		
		$list->setColumnSizes(array("50px","250px","130px","120px","70px"));
		
		$list->setHeaderFontBold();
		
		$list->setSize("875px","100px");
		
		$list->showList();
		
		return $list->toString();
	
	}

}
?>