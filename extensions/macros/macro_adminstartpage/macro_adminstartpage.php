<?php
function macro_adminstartpage(){
GLOBAL $db;

system::adminPageTitle("macro_adminstartpage","Administrator's Dashboard");
?>

<div style="float:left; margin-bottom:10px; margin-top:3px;">
  
  <?php 
  $layout=new macro_layout;
  
  $layout->setWidth("900px");
  
   $comps=$db->getMacros("where for_super=1 and show_to_admin=1");
   
   $macro_ids=array();
   
   for($i=0;$i<count($comps);$i++){
      $macro_ids[]=$comps[$i]->macro_id;
   }
   
   $link_array=$db->getMenuLinkBymacroId($macro_ids,2);
   
   $business="";
   
   $system="";
   
   for($i=0;$i<count($comps);$i++){
   
   
   $tlink="#";
   
   if(isset($link_array[$comps[$i]->macro_id])){
     $tlink=$link_array[$comps[$i]->macro_id];
   }
   
   $tm="<div class=\"ico\">
    <a href=\"".$tlink."\"><img src=\"../".system::macro_path().$comps[$i]->macro_name."/".$comps[$i]->macro_name.".jpg\" width=\"80px\" height=\"80px\" title=\"".$comps[$i]->macro_description."\"/>    </a>".system::itemTitle($comps[$i]->macro_title)."</div>";
    
	if($comps[$i]->macro_category==0){
	  $business.=$tm;
	}else{
	  $system.=$tm;
	}
   }
   
   $layout->content=system::contentTitle("Control Panel");
   
   $layout->content.="<div id=\"apps\">";
   
   $layout->content.=system::categoryTitle("Business Administration");
   
   $layout->content.=$business;
   
   $layout->content.=system::categoryTitle("System Administration");
   
   $layout->content.=$system;
   
   $layout->content.="</div>";
   
   $layout->content.="<div id=\"form_row\" style=\"border-bottom:1px solid #444;width:99%;\"></div>";
   
   $layout->showlayout();
   
   ?>
</div>

 <div id="apps_window">
    <?php
    $param=new ajaxparameter;
    
    $param->response_target="apps_window";
    $param->ajax_id=0;
    $param->response_function="document.getElementById('myname_23').value=xmlhttp.responseText;";
    $param->response_type=OPTION_MODULES;
    $param->response_object="md_mainmenu";
    $param->ajax_parameter="";
    $param->ajax_event="onclick";
    
    $obj=new input;
    $obj->enableAjax(false);
    //$obj->addItem(0,"select an item");
    //$obj->addItem(1,"Me");
    //$obj->addItem(2,"Me2");
    $obj->input("text","myname_23","hello_man",$param);
    echo $obj->toString();
    ?>
 </div>
<?php
    return true;
}

?>