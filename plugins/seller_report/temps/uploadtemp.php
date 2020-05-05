<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<link type="text/css" rel="stylesheet" href="temps/css/layout_css.css" />
<link type="text/css" rel="stylesheet" href="temps/css/gen.css" />

</head>

<body style=" font-family:Arial, Helvetica, sans-serif;font-size:12px">
<form action="" method="post" enctype="multipart/form-data" >
<?php  

//System::clearSessionImages(); 

$cr=System::shared("showcars");

$itms=array();

if(isset($_POST['c_owner'])){

$items=System::nameValueToSimpleArray(System::getPostedItems("y"));
$itms=System::nameValueToSimpleArray(System::getPostedItems("y"),true);
$titles=array("your name","your cellphone","your email","your offer");
$status=true;

for($i=0;$i<count($itms);$i++){
	if((trim($itms[$i])=="")&($i!=2)){
	$status=false;
	echo System::getWarningText("Please enter ".$titles[$i]);
	break;
	}
}

if($status){
	
$cars=$cr->getCars(" where id=".$_GET["cid"]." and owner_name<>\"\"");

for($b=0;$b<count($cars);$b++){
	
$int=new interest;
$int->i_owner=$cars[$b]->owner_name;
$int->i_owneremail=$cars[$b]->owner_email;
$int->i_carname=$cars[$b]->man_year." ".$cars[$b]->make." ".$cars[$b]->model;
$int->i_postedOn=$cars[$b]->date_added;
$int->i_clientname=$items['y_name'];
$int->i_clientemail=$items['y_email'];
$int->i_clientcell=$items['y_cell'];
$int->i_offerPrice=$items['y_offer'];
$int->i_link;

$cr->emailOwner($int);

}

echo System::successText("Message sent successfully.");

exit;

}



}

echo System::categoryTitle("Contact Seller","margin-bottom:5px;");

$yn=new input;
$yn->setClass("form_input");
$yn->setTagOptions("style=\"width:200px;\"");
$yn->input("text","y_name",System::getArrayElementValue($itms,0));

$ye=new input;
$ye->setClass("form_input");
$ye->setTagOptions("style=\"width:200px;\"");
$ye->input("text","y_email",System::getArrayElementValue($itms,2));

$yc=new input;
$yc->setClass("form_input");
$yc->setTagOptions("style=\"width:200px;\"");
$yc->input("text","y_cell",System::getArrayElementValue($itms,1));

$yo=new input;
$yo->setClass("form_input");
$yo->setTagOptions("style=\"width:200px;\"");
$yo->input("text","y_offer",System::getArrayElementValue($itms,3));


$btn=new input;
$btn->setTagOptions("style=\"padding-right:20px;padding-left:20px;\"");
$btn->setClass("form_button");
$btn->input("submit","c_owner"," Send ");

$cars=$cr->getCars(" where id=".$_GET["cid"]." and owner_name<>\"\"");

$text=System::getWarningText("Sorry, The owner did not leave his/her contact details.");

for($i=0;$i<count($cars);$i++){

$text="";

echo System::categoryTitle("Your contacts will be sent to the owner.Please make sure you have entered the correct<br/> information.","margin-bottom:5px ;font-weight:normal;");

echo "<div id=\"form_row\"><div id=\"label\"><strong>Your Name</strong></div>{$yn->toString()}</div>";

echo "<div id=\"form_row\"><div id=\"label\"><strong>Cellphone</strong></div>{$yc->toString()}</div>";

echo "<div id=\"form_row\"><div id=\"label\"><strong>Your Email</strong></div>{$ye->toString()}(optional)</div>";

echo "<div id=\"form_row\"><div id=\"label\"><strong>Offer Price</strong></div>{$yo->toString()}</div>";

echo "<div id=\"form_row\">{$btn->toString()}</div>";
}

echo $text;

?>
</form>
</body>
</html>