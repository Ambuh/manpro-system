<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link type="text/css" rel="stylesheet" href="temps/css/layout_css.css" />
<link type="text/css" rel="stylesheet" href="temps/css/gen.css" />

</head>

<body>

<form action="" method="post" enctype="multipart/form-data" >
<?php  

GLOBAL $db;

$cr=System::shared("showcars");

if(isset($_POST['report_sold'])){
 
 $cars=$cr->getCars("where id=".$_GET['cid']." and owner_email='".$_POST['det_email']."'");

 for($i=0;$i<count($cars);$i++){
  
  $db->updateQuery(array("sold=1"),"cars","where id=".$_GET['cid']);
  
  echo System::successText("Report sent successfully.");
  return;

 }
 
 if(count($cars)==0)
 echo System::getWarningText("Wrong email address.");


}
//System::clearSessionImages(); 

$cars=$cr->getCars("where id=".$_GET['cid']." and sold=1");

 for($i=0;$i<count($cars);$i++){
 
 echo System::categoryTitle("Car already reported as sold.");
 
 return;
 
 }

echo System::categoryTitle("Report sold.","margin-bottom:5px;");

echo System::categoryTitle("Enter upload email address below","font-weight:normal;margin-bottom:10px;");

$inp=new input;

$inp->setClass("form_input");

$inp->input("text","det_email","");

echo "<div id=\"form_row\" style=\"margin-top:10px;\"><div id=\"label\"><strong>Email</strong></div>{$inp->toString()}</div>";

$inp=new input;

$inp->setClass("form_button");

$inp->input("submit","report_sold","Submit");

echo "<div id=\"form_row\" style=\"margin-top:10px;\">{$inp->toString()}</div>";

?>
</form>
</body>
</html>