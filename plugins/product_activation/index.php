<?php
if(isset($_POST['app_options'])){
switch($_POST['app_options']){
    case 1:
       echo "<?xml version=\"1.0\" encoding=\"utf-16\"?>
<activationDetails xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">
  <givennames>Eric Wekesa</givennames>
  <username>wekesamaina</username>
  <terms>Lease</terms>
  <status>true</status>
<password>********</password>
  <activation_date>2012-11-17T14:41:22.0546875+03:00</activation_date>
  <expiration>2012-11-17T14:41:22.0517578+03:00</expiration>
  <product_pin>ad1930138</product_pin>
  <activation>0</activation>
<computer_name>{$_POST['computer_name']}</computer_name>
<sender>Maina</sender>
<message> Invalid Username</message>
</activationDetails>";
    
        break;

case 2:

echo "<?xml version=\"1.0\" encoding=\"utf-16\"?>
<activationDetails xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">
  <givennames>Eric Wekesa</givennames>
  <username>wekesamaina</username>
  <terms>Lease</terms>
  <status>true</status>
<password>********</password>
  <activation_date>2012-11-17T14:41:22.0546875+03:00</activation_date>
  <expiration>2012-11-17T14:41:22.0517578+03:00</expiration>
  <product_pin>ad1930138</product_pin>
  <activation>0</activation>
<computer_name>ELLIGENT-PC</computer_name>
<sender>Maina</sender>
<message> Invalid Username</message>
</activationDetails>";

break;

}

}
?>
