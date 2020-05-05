<?php
function md_userdetails($id){
    
    $smstool=System::shared("smstools");

    
    if(defined('USER_LOGGED')){
        
        echo $smstool->showUnits();
        
    }
    return true;
}
?>