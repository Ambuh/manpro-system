<?php
class myhelper{
public function loadBlock($option=0){
 switch($option){
  case 1:
  
  break;
  
  case 2:
  
  break;
  
  case 3:
  
  break;
  
  default:
  $this->defaultOption();
  
 }
} 
private function defaultOption(){
 ?>
 <div id="system_dash">
<img src="<?php echo "../".system::component_path()."com_admincompanies/com_admincompanies.jpg"; ?>" width="40px" height="40px" style="cursor: pointer;"/>Manage Companies</div>
<div style="margin:5px 20px;float:left;width:100%;">
 <?php
  $cont =new list_control;
  $cont->headerFont_bold=true;
 $cont->setSize("695px","450px");
 $cont->setBackgroundColour("#fff");
 $cont->addItem(array("1","client","client","asd"));
 $cont->addItem(array("2","client2","client2","asdasd"));
 $cont->setListId("KK");
 $cont->setColumnNames(array("No.","Company Name","Status","Action"));
 $cont->setColumnSizes(array("40px","250px","100px;","250px;"));
 $cont->setAlternateColor("#ddd");
 $cont->setTitle("All Companies");
 $cont->showList(false);


$tbs=new tabs_layout;
$tbs->addTab("Companies");
$con="<div id=\"inpage_title\">Companies</div><div id=\"app_titles\" style=\"margin-top:10px;\">Company Name<input type=\"text\" style=\"margin-left:20px;\" /><input type=\"submit\" value=\"Search\" style=\"margin-left:20px;\"/></div>";
$tbs->addTabContent($con.$cont->toString());

$tbs->addTabContent("<div id=\"inpage_title\">Create Company</div>".$this->createCompany());
$tbs->setActiveTab(1);
$tbs->addTab("Create Company");
$tbs->showTabs();
 ?>
 </div>
 <?php
}
private function createCompany(){
$os=new objectString;
$os->generalTags("<div style=\"margin:5px 20px;float:left;width:100%;\">");
$os->generalTags("<div id=\"cont_row\"><strong>Company Name</strong><input type=\"text\" /></div>");
$os->generalTags("<div id=\"cont_row\"><strong>Postal Address</strong><input type=\"text\" /></div>");
$os->generalTags("<div id=\"cont_row\"><strong>Email Address</strong><input type=\"text\" /></div>");
$os->generalTags("</div>");
return $os->toString();
}

}
?>