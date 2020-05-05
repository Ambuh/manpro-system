<?php
include "quince_helper.php";
function macro_quince(){
    
    $qc=new HQuince();
    
    echo $qc->launcher(); 
    
    return true;
}
?>