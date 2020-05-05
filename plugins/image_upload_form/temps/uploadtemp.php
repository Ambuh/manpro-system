<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link type="text/css" rel="stylesheet" href="temps/css/gen.css" />
<link type="text/css" rel="stylesheet" href="../../../system/layout_css.css" />
</head>
<style>
#upload_div{

   width:100%;
   height:100%;
   position:fixed;
   top:0px;
   left:0px;
   background:#fff;
   opacity:0.89;
   display:none;

}
#pwait{
	width:250px;
   height:250px;
   position:fixed;
   top:100px;
   left:120px;
   background:#fff;
   z-index:1000;
   display:none;
}
#pwait img{
	 opacity:0.89;
}
</style>
<script language="javascript" type="text/javascript">
function uploadAlert(stat){
try{
//mform.submit();	
 document.getElementById('upload_div').style.display="block";
 document.getElementById('pwait').style.display="block";
 document.getElementById('st').innerHTML=stat;
 
}catch(e){ 
alert(e);
}
}
function closeMe(){

window.parent.document.getElementById("iprop_wrapper").style.display="none";
window.parent.document.getElementById("iprop_form").submit();
window.parent.document.getElementById("iprop").style.display="none";
}
</script>
<body>

<?php 

$form=new form_control("uploadAlert('Please Wait.Uploading images...')");

$form->enableUpload();

echo $form->formHead("","mform");

?>

<!--<form action="" method="" name="mform" enctype="multipart/form-data" >-->
<div id="upload_div"></div>
<div id="pwait"><img src="temps/pleasewait.gif" style="margin-left:65px;float:left"/><div id="form_row" style="float:left;width:100%;color:#333;text-align:center;"><i id="st">Please Wait. Uploading image(s)</i></div></div>
<div id="mg_uploads" style="width:100%;overflow:hidden;">
</div>
<?php  

//System::clearSessionImages(); 
System::hasUploaded();

System::forImageUploads();

$r_items=System::getPostedItems("Remove");

for($i=0;$i<count($r_items);$i++){
$image_name=explode("#",$r_items[$i]->name);
System::removeImageFromSession(str_replace("$",".",$image_name[count($image_name)-1]),"../../");
}

if(count($r_items)>0)
echo System::successText("Image Removed");

 if(isset($_POST['uploadimages'])){
   
   $items= System::getPostedItems("u",1);
   
   if(System::uploadImages("../../images/uploads/","u")){   
   echo System::successText("Image(s) uploaded successfully");
   }else{
	echo System::getWarningText("Failed.Please select image(s) to be uploaded!");
   }
   
 }
 
 
 echo System::categoryTitle("Select image to upload","margin-bottom:5px;background:#0d4a77;color:#fff;");
 
 $images=System::getSessionImages();
 $uploaded=array();
 for($i=0;$i<count($images);$i++){
	 $uploaded[]=$images[$i]->image_view;
 }
 
 echo "<div style=\"width:70%;overflow:hidden;padding-right:3px;float:left;\">";
 
 if(!in_array("View 1",$uploaded)){
 $upl=new input;
 //$upl->setClass("form_button");
 $upl->setTagOptions("style=\"\"");
 $upl->input("file","u_image");
 
 echo System::categoryTitle("<div style=\"float:left;overflow:hidden;\">".$upl->toString()."</div><div style=\"float:left;font-weight:bold;color:#56037c;font-size:14px;margin-top:3px;\">Front View</div>","margin-bottom:5px;");
 }
 if(!in_array("View 2",$uploaded)){
 $upl=new input;
 //$upl->setClass("form_button");
 $upl->setTagOptions("style=\"\"");
 $upl->input("file","u_image2");
 echo System::categoryTitle("<div style=\"float:left;overflow:hidden;\">".$upl->toString()."</div><div style=\"float:left;\">View 2</div>","margin-bottom:5px;");
 }
 if(!in_array("View 3",$uploaded)){
$upl=new input;
 //$upl->setClass("form_button");
 $upl->setTagOptions("style=\"\"");
 $upl->input("file","u_image3");
echo System::categoryTitle("<div style=\"float:left;overflow:hidden;\">".$upl->toString()."</div><div style=\"float:left;\">View 3</div>","margin-bottom:5px;");
 }
 if(!in_array("View 4",$uploaded)){
$upl=new input;
 //$upl->setClass("form_button");
 $upl->setTagOptions("style=\"\"");
 $upl->input("file","u_image4");
echo System::categoryTitle("<div style=\"float:left;overflow:hidden;\">".$upl->toString()."</div><div style=\"float:left;\">View 4</div>","margin-bottom:5px;");
 }
 if(!in_array("View 5",$uploaded)){
$upl=new input;
 //$upl->setClass("form_button");
 $upl->setTagOptions("style=\"\"");
 $upl->input("file","u_image5");
echo System::categoryTitle("<div style=\"float:left;overflow:hidden;\">".$upl->toString()."</div><div style=\"float:left;\">View 5</div>","margin-bottom:5px;");
 }
 if(!in_array("View 6",$uploaded)){
$upl=new input;
 //$upl->setClass("form_button");
 $upl->setTagOptions("style=\"\"");
 $upl->input("file","u_image6");
echo System::categoryTitle("<div style=\"float:left;overflow:hidden;\">".$upl->toString()."</div><div style=\"float:left;\">View 6</div>","margin-bottom:5px;");
 }if(!in_array("View 7",$uploaded)){
$upl=new input;
 //$upl->setClass("form_button");
 $upl->setTagOptions("style=\"\"");
 $upl->input("file","u_image7");
echo System::categoryTitle("<div style=\"float:left;overflow:hidden;\">".$upl->toString()."</div><div style=\"float:left;\">View 7</div>","margin-bottom:5px;");
 }
 if(!in_array("View 8",$uploaded)){
$upl=new input;
 //$upl->setClass("form_button");
 $upl->setTagOptions("style=\"\"");
 $upl->input("file","u_image8");
echo System::categoryTitle("<div style=\"float:left;overflow:hidden;\">".$upl->toString()."</div><div style=\"float:left;\">View 8</div>","margin-bottom:5px;");
 }
 //echo "<div id=\"form_row\" style=\"margin-top:5px\">{$select->toString()}</div>";

 $images=System::getSessionImages();

 for($i=0;$i<count($images);$i++){
 $remove_btn=new input;
 $remove_btn->setClass("form_button_delete");
 $remove_btn->setTagOptions("style=\"float:right;\" title=\"Click to remove image\"onclick=\"uploadAlert('Please Wait.Deleting image...')\"");
 $remove_btn->input("submit","remove#".str_replace(".","$",$images[$i]->image_alias)."","Remove X");
  echo System::categoryTitle("<div style=\"float:left;margin-right:5px; width:100px;\">{$images[$i]->image_description} View</div><img src=\"../../{$images[$i]->image_path}\" style=\"float:left;height:50px;width:100px;\"/>{$remove_btn->toString()}","margin-bottom:3px");
 }
  echo "</div>";
  echo "<div style=\"width:28.5%;float:left;margin-left:3px;\">";
  
   if(count($images)<4){
 $upbtn=new input;
 $upbtn->setClass("form_button");
 $upbtn->setTagOptions("onclick=\"\" style=\"height:100px;width:100%;overflow:hidden;font-size:14px;\"");
 $upbtn->input("submit","uploadimages","Upload Images");
 
 echo "<div id=\"form_row\" style=\"margin-top:0px;\">{$upbtn->toString()}</div>";
 }
  
  if(count($images)>0){
  
  $upbtn=new input;
 $upbtn->setClass("form_button");
 $upbtn->setTagOptions("onclick=\"closeMe()\" style=\"height:50px;width:50%;background:#1d99f9;overflow:hidden;font-size:14px;margin:10px 0px 0px 25px;\"");
 $upbtn->input("button","uploads","Done");
  
  echo "<div id=\"form_row\" style=\"margin-top:0px;\">{$upbtn->toString()}</div>";
  }
  
  echo "</div>"
?>


</form>
</body>
</html>