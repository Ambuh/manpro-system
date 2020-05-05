<?php
function com_adminstartpage(){
GLOBAL $db;
?>
<div id="system_dash">Dashboard</div>
<div id="app_titles">Browse Modules</div>

<div id="apps">
  
  <?php 
   $comps=$db->getComponents();
   for($i=0;$i<count($comps);$i++){
   ?>
    <a href="?opt=<?php echo $comps[$i]->component_id; ?>"><img src="<?php echo "../".system::component_path()."{$comps[$i]->component_name}/{$comps[$i]->component_name}.jpg"; ?>" width="100px" height="100px" style="cursor: pointer;"/>    </a>
   <?php
   }
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