<?php
function md_companyprofile(){

if(!defined("PL_COMPANY")){
 echo System::getWarningText("Required plugin not available!");
}
GLOBAL $COMPANY_G;
echo "<div id=\"module_inner\">";

?>
<div class="irow">Company Name:</div>

<div class="irow"><div id="mc"><?php echo $COMPANY_G->company_name; ?></div></div>

<div class="irow">City:</div>

<div class="irow"><div id="mc"><?php echo $COMPANY_G->company_location; ?></div></div>

<div class="irow">Tel:</div>

<div class="irow"><div id="mc"><?php echo $COMPANY_G->company_phone;?></div></div>


<?php

echo "</div>"; 

}
?>