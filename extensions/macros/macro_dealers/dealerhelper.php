<?php
class dealerHelper{
private $shared;
private $previous_data=array();
private $prev_image="";
private $login_status="";
private $db;
public function __construct(){
 GLOBAL $db;
 $this->db=$db;
 
 $this->shared=System::shared("showcars");
 
 if(isset($_POST['dealer_login'])){
	
  $deal=$this->shared->getDealers("where dealer_email='".str_replace("'","",$_POST['login_email'])."' and password='".sha1($_POST['login_password'])."'");	
  
  if(count($deal)>0){

   for($i=0;$i<count($deal);$i++)
   $_SESSION[System::getSessionPrefix()."dealer_session"]=serialize($deal[$i]);
	  
  }else{
  
  $this->login_status=System::getWarningText("Failed to login.Invalid email/password.","margin-bottom:5px;font-weight:normal;");
  
  }
	
 }

}
public function displayPage(){
	
  if((preg_match("/logout/i",$_SERVER['REQUEST_URI']))&(isset($_SESSION[System::getSessionPrefix()."dealer_session"]))){
  unset($_SESSION[System::getSessionPrefix()."dealer_session"]);
  return $this->logout();
  }else if(preg_match("/logout/i",$_SERVER['REQUEST_URI'])){
    return System::categoryTitle("You are not logged in.");
  }
  	
  if((preg_match("/login/i",$_SERVER['REQUEST_URI']))&(!isset($_SESSION[System::getSessionPrefix()."dealer_session"])))
  return $this->login();
  
  if(preg_match("/register/i",$_SERVER['REQUEST_URI']))
  return $this->register();
  
  
  $urArray=explode("/",$_SERVER['REQUEST_URI']);
  
  $theDealer="";
  
  for($i=0;$i<count($urArray);$i++)
   if(preg_match("/Dealers/i",$urArray[$i])){
   
     if(isset($urArray[$i+1]))
	 $theDealer=$urArray[$i+1];
   
   }
  
 
  
  if(!preg_match("/register/i",$theDealer)& !preg_match("/edit/i",$theDealer) & !preg_match("/login/i",$theDealer) & $theDealer!="")
  return $this->dealersPage($theDealer);
  
  if(isset($_SESSION[System::getSessionPrefix()."dealer_session"])){
   
   return $this->dealersProfile();
   
  }

  return $this->displayDealers();

}
public function logout(){

$cont=new objectString;

$cont->generalTags(System::successText("You have successfully logged out."));

unset($_SESSION[System::getSessionPrefix()."uploadedimage"]);

$sub=new input;
  
  $sub->setClass("form_button");
  
  $sub->setTagOptions("style=\"padding:3px 15px 3px 15px;\" title=\"Cancel\" ");
  
  $sub->input("button","login_button","Login");
  
 $cont->generalTags("<div id=\"form_row\"><a href=\"http://".preg_replace("/\/\//","/",System::basePath()).System::decipherUrl()."/login/\" style=\"overflow:hidden;\">{$sub->toString()}</a></div>");

return $cont->toString();

}
public function pageTitle(){

  $theDealer="";
  
  $urArray=explode("/",$_SERVER['REQUEST_URI']);
  
  for($i=0;$i<count($urArray);$i++)
   if(preg_match("/Dealers/i",$urArray[$i])){
   
     if(isset($urArray[$i+1]))
	 $theDealer=$urArray[$i+1];
   
   }
  
  if(!preg_match("/register/i",$theDealer)& !preg_match("/edit/i",$theDealer) &
   !preg_match("/login/i",$theDealer) & $theDealer!=""){
  
  $deal=$this->shared->getDealers("where dealername='".trim(str_replace(".","",str_replace(" ","",$theDealer)))."'");

  for($d=0;$d<count($deal);$d++)
  return $deal[$d]->dealer_name;
   }else{
   return ucfirst($theDealer);
   }

}
private function dealersProfile(){
	
  $dealer=unserialize($_SESSION[System::getSessionPrefix()."dealer_session"]);

  $deal=$this->shared->getDealers("where id=".$dealer->dealer_id);
  
  for($u=0;$u<count($deal);$u++){
  
   $this->previous_data=array("det_name"=>$deal[$u]->dealer_name,"det_cellphone"=>$deal[$u]->dealer_cellphone,
   "det_description"=>$deal[$u]->dealer_description,"det_email"=>$deal[$u]->dealer_email,
   "det_location"=>$deal[$u]->dealer_location);
  if($deal[$u]->dealer_logo!="")
  $_SESSION[System::getSessionPrefix()."uploadedimage"]=$deal[$u]->dealer_logo;
  
  }
  
    
  unset($_SESSION[System::getSessionPrefix()."uploadedimage"]);
	
  $mess= $this->updateProfile();
  

  $deal=$this->shared->getDealers("where id=".$dealer->dealer_id);
  
  for($u=0;$u<count($deal);$u++){
  
   $this->previous_data=array("det_name"=>$deal[$u]->dealer_name,"det_cellphone"=>$deal[$u]->dealer_cellphone,
   "det_description"=>$deal[$u]->dealer_description,"det_email"=>$deal[$u]->dealer_email,
   "det_location"=>$deal[$u]->dealer_location);
  
  $_SESSION[System::getSessionPrefix()."uploadedimage"]=$deal[$u]->dealer_logo;
  
  }
  $slp=explode("/",$_SERVER['REQUEST_URI']);
 
  if(preg_match("/edit/i",$slp[count($slp)-2]))
  return $this->register(true,$mess);
  
  $cont=new objectString;
  
  $sub=new input;
  
  $sub->setClass("form_button_delete");
  
  $sub->setTagOptions("style=\"float:right;padding:5px;\" title=\"Log Out {$dealer->dealer_name}\" ");
  
  $sub->input("button","logout_button","Log Out");

  
  $cont->generalTags(System::contentTitle("Dealer's Section<a href=\"http://".preg_replace("/\/\//","/",System::basePath()).System::decipherUrl()."/logout/\" style=\"overflow:hidden;\">{$sub->toString()}</a>","color:#30aff7;font-size:16px;"));
  
  $cont->generalTags(System::categoryTitle("Your Profile","margin-bottom:10px;"));
  
  $cont->generalTags("<style>#label{
	  width:120px;
	  color:#444;
  }</style>");
  
  $cont->generalTags("<div style=\"float:left;width:80%\" >");
  
  $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Dealership Name.</strong></div>".System::getArrayElementValue($this->previous_data,"det_name")."</div>");
  
  $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Intro.</strong></div>".System::getArrayElementValue($this->previous_data,"det_description")."</div>");
  
  $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Tel.</strong></div>".System::getArrayElementValue($this->previous_data,"det_cellphone")."</div>");
  
  $logo="";
  
  if(isset($_SESSION[System::getSessionPrefix()."uploadedimage"])){
	  $logo=str_replace("../",System::getFolderBackJump(),$_SESSION[System::getSessionPrefix()."uploadedimage"]);
  }else{
      $logo=str_replace("../",System::getFolderBackJump(),$this->prev_image);
  }
  
  
  $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Logo</strong></div><img style=\"height:50px;\"src=\"{$logo}\"/></div>");
  
  $cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong>Location</strong></div>".System::getArrayElementValue($this->previous_data,"det_location")."</div>");
  
  $cont->generalTags("</div>");
  
  $cont->generalTags("<div style=\"float:left;width:19%;overflow:hidden;\">");
  
  $sub=new input;
  
  $sub->setClass("form_button_add");
  
  $sub->setTagOptions("style=\"float:right;\" title=\"Edit profile\" ");
  
  $sub->input("button","edit_button","Edit");
  
  $cont->generalTags("<div id=\"form_row\"><a href=\"http://".preg_replace("/\/\//","/",System::basePath()).System::decipherUrl()."/Edit/\" style=\"overflow:hidden;\">{$sub->toString()}</a></div>");
  
  $cont->generalTags("</div>");
  
  $cont->generalTags(System::categoryTitle("Your Posts","margin-bottom:10px;"));
  
  $cont->generalTags($this->shared->showCarsList(false,0," and dealer_id=".$dealer->dealer_id,
	$limit="",true,"Dealer's Posts",true,""));
  
  $cont->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #eee;\"></div>");
  
  return $cont->toString();	

}

public function displayDealers(){
	
	$cont=new objectString();
	
	$reg=new input;
	
	$reg->setClass("form_button");
	
	$reg->setTagOptions("style=\"float:right;font-size:14px;background:#707172;padding:2px 50px 2px 50px;\"");
	
	$reg->input("button","input","Register");
	
	$login=new input;
	
	$login->setClass("form_button");
	
	$login->setTagOptions("style=\"float:right;margin-right:5px;font-size:14px;padding:2px 30px 2px 30px;\"");
	
	$login->input("button","input","Login");
	
	$cont->generalTags(System::contentTitle("Dealer's Section <a href=\"http://".preg_replace("/\/\//","/",System::basePath()).System::decipherUrl()."/register/\" style=\"overflow:hidden;\">{$reg->toString()}</a> <a href=\"http://".preg_replace("/\/\//","/",System::basePath()).System::decipherUrl()."/login/\" style=\"overflow:hidden;\">{$login->toString()}</a>","color:#0f8ed2;overflow:hidden;padding-bottom:5px;")); 
	
	//$cont->generalTags("<div style=\"width:60%;float:left;overflow:hidden;min-height:100px;\">");
	
	$inp=new input; 
	
	$inp->setClass("form_input");
	
	$inp->input("text","find_dealer");
	
	$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong style=\"color:#707172;\">Find Dealer</strong></div>{$inp->toString()}</div>");
	
	$cont->generalTags(System::categoryTitle("Registered Dealers","margin-bottom:5px;"));
	
	$cont->generalTags($this->dealersList());
	
	//$cont->generalTags("</div>");
	
	/*$cont->generalTags("<div style=\"width:38%;float:right;margin-left:5px;\">");
	
	$cont->generalTags(System::categoryTitle("Dealer's Login","margin-bottom:5px;background:#0f8ed2;border:none;color:#fff;"));
	
	$inp=new input; 
	
	$inp->setClass("form_input");
	
	$inp->input("text","login_email");
	
	$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong style=\"color:#707172;\">Email</strong></div>{$inp->toString()}</div>");
	
	$inp=new input; 
	
	$inp->setClass("form_input");
	
	$inp->input("password","login_password");
	
	$cont->generalTags("<div id=\"form_row\" style=\"padding-bottom:0px;\"><div id=\"label\"><strong style=\"color:#707172;\">Password</strong></div>{$inp->toString()}</div>");
	
	$login=new input;
	
	$login->setClass("form_button");
	
	$login->setTagOptions("style=\"float:right;margin-right:25px;padding:5px 30px 5px 30px;\"");
	
	$login->input("submit","input","Login");
		
	$cont->generalTags("<div id=\"form_row\" style=\"margin-top:0px;\">{$login->toString()}</div>");
	
	$cont->generalTags("</div>");
*/
		
	return $cont->toString();
	
}
public function dealersPage($dealers_name=""){

$cont=new objectString;

$deal=$this->shared->getDealers("where dealername='".trim(str_replace(".","",str_replace(" ","",$dealers_name)))."' and dealer_status=1");


for($d=0;$d<count($deal);$d++){

$cont->generalTags("<div id=\"form_row\" style=\"padding-left:0px;border-bottom:2px solid #ccc;\"><img src=\"".str_replace("../",System::getFolderBackjump(),$deal[$d]->dealer_logo)."\" style=\"width:100%;margin-left:0px;margin-right:5px;float:left;\"></div>");

//<div style=\"float:left;\"><div id=\"irow\"><strong style=\"margin-left:0px;\">{$deal[$d]->dealer_name}</strong></div><div style=\"float:left;font-size:12px;font-weight:normal;color:#555;\">{$deal[$d]->dealer_description}</div></div>

if(preg_match("/views/i",$_SERVER['REQUEST_URI'])){
 
 $p=explode("/",$_SERVER['REQUEST_URI']);
 
 $carid=0;
 
 if(isset($p[count($p)-2])){
 
   $splitpath=explode("-",$p[count($p)-2]);
 
   if(is_numeric($splitpath[count($splitpath)-1]))
   $carid=$splitpath[count($splitpath)-1];
   
 }
 
 $_SESSION[System::getSessionPrefix()."_backpath"]=$_SESSION[System::getSessionPrefix()."_backpath2"];
  
  $cars=$this->shared->getCars("where id=".$carid);
  
  for($c=0;$c<count($cars);$c++)
  $cont->generalTags($this->shared->viewCar($cars[$c],"",true));

}else{
		
    $cont->generalTags($this->shared->showCarsList(false,0,"and dealer_id=".$deal[$d]->dealer_id,
	$limit="",true,"Dealer's Posts",false,$dealers_name."/"));
	
	$_SESSION[System::getSessionPrefix()."_backpath2"]=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
	
	
}
}
return $cont->toString();

}
public function login(){

 $cont=new objectString;
	
  $sub=new input;
  
  $sub->setClass("form_button_add");
  
  $sub->setTagOptions("style=\"float:right;padding:3px 15px 3px 15px;\" title=\"Cancel\" ");
  
  $sub->input("button","edit_button","Cancel");
	
  $cont->generalTags(System::contentTitle("Dealer's Login<a href=\"http://".preg_replace("/\/\//","/",System::basePath()).System::decipherUrl()."/\" style=\"overflow:hidden;\">{$sub->toString()}</a>","margin-bottom:5px;"));
	
	$form=new form_control;
	
	$cont->generalTags($form->formHead());
	
	$cont->generalTags(System::categoryTitle("Enter Details","margin-bottom:5px;background:#0f8ed2;border:none;color:#fff;"));
	
	$cont->generalTags($this->login_status);
	
	$cont->generalTags("<div style=\"width:38%;float:left;margin-left:5px;\">");
	
	$inp=new input; 
	
	$inp->setClass("form_input");
	
	$inp->input("text","login_email");
	
	$cont->generalTags("<div id=\"form_row\"><div id=\"label\"><strong style=\"color:#707172;\">Email</strong></div>{$inp->toString()}</div>");
	
	$inp=new input; 
	
	$inp->setClass("form_input");
	
	$inp->input("password","login_password");
	
	$cont->generalTags("<div id=\"form_row\" style=\"padding-bottom:0px;\"><div id=\"label\"><strong style=\"color:#707172;\">Password</strong></div>{$inp->toString()}</div>");
	
	$login=new input;
	
	$login->setClass("form_button");
	
	$login->setTagOptions("style=\"float:left;margin-right:25px;padding:5px 30px 5px 30px;\"");
	
	$login->input("submit","dealer_login","Login");
		
	$cont->generalTags("<div id=\"form_row\" style=\"margin-top:0px;\">{$login->toString()}</div>");

   $cont->generalTags("</form>");

   $cont->generalTags("</div>");
 
 return $cont->toString();

}
private function registerDealer(){
	
 if(isset($_POST['remove_image'])){
 $items=System::nameValueToSimpleArray(System::getPostedItems("det"));
 
 if(file_exists(str_replace("../",ROOT,$_SESSION[System::getSessionPrefix()."uploadedimage"])))
 unlink(str_replace("../",ROOT,$_SESSION[System::getSessionPrefix()."uploadedimage"]));
 unset($_SESSION[System::getSessionPrefix()."uploadedimage"]);
 $this->previous_data=$items;
 }
 
 if(isset($_POST['register_dealer'])){
 
   $items=System::nameValueToSimpleArray(System::getPostedItems("det"));
   $tm=time();
   
   $this->previous_data=$items;
   
   $image_uploaded=false;
   
   
   if(!isset($_SESSION[System::getSessionPrefix()."uploadedimage"])){
   
   if(move_uploaded_file($_FILES['logofile']['tmp_name'],ROOT."images/dealers/".$tm.$_FILES['logofile']['name'])){
     $_SESSION[System::getSessionPrefix()."uploadedimage"]="../images/dealers/".$tm.$_FILES['logofile']['name'];
	 $image_uploaded=true;
	 
   }else{
	   $this->previous_data=$items;
	   
   }
 
  }
  
  if(trim($items['det_name'])=="")
  return System::getWarningText("Dealer's name is required.");
  
  $dealers=$this->shared->getDealers("where dealername='".trim(str_replace(" ","",str_replace(".","",$items['det_name'])))."'");
  
  for($c=0;$c<count($dealers);$c++)
  return System::getWarningText("Dealer name already exists. Please enter a different name.");
  
  if(trim($items['det_cellphone'])=="")
  return System::getWarningText("Dealer's cellphone is required.");
  
  if(trim($items['det_email'])=="")
  return System::getWarningText("Dealer's email is required.");
  
  $dealers=$this->shared->getDealers("where dealer_email='".trim(str_replace(" ","",str_replace(".","",$items['det_email'])))."'");
  
  for($c=0;$c<count($dealers);$c++)
  return System::getWarningText("Dealer name already exists. Please enter a different name.");
  
  
  if((!$image_uploaded)&(!isset($_SESSION[System::getSessionPrefix()."uploadedimage"])))
   return System::getWarningText("Dealer's logo required.");
 
 
   if(($items['det_password']!=$items['det_repeatpassword'])or($items['det_password']=="")){
	   
	   $this->previous_data=$items;
	   
	   if($items['det_password']=="")
	   return System::getWarningText("Please enter password");
	   
	   return System::getWarningText("Password mismatch.");
   }
   
   
	   
	   $this->shared->addDealer($items['det_name'],base64_encode($items['det_description']),$_SESSION[System::getSessionPrefix()."uploadedimage"],
	   $items['det_email'],$items['det_cellphone'],$items['det_cellphone'],$items['det_password']);
	   
	   unset($_SESSION[System::getSessionPrefix()."uploadedimage"]);
	   
	   $this->previous_data=array();
	   
	   return System::successText("Dealer registered successfully.");
	   
   
 
 }

}
public function updateProfile(){
	
 if(isset($_POST['rupdate_profile'])){
	 
	 $image_uploaded=false;
	 $tm=time();
  $thisimage="";
  if(!isset($_SESSION[System::getSessionPrefix()."uploadedimage"]))	 
  if(move_uploaded_file($_FILES['logofile']['tmp_name'],ROOT."images/dealers/".$tm.$_FILES['logofile']['name'])){
     $_SESSION[System::getSessionPrefix()."uploadedimage"]="../images/dealers/".$tm.$_FILES['logofile']['name'];
	 $image_uploaded=true;
	 
	 $thisimage="../images/dealers/".$tm.$_FILES['logofile']['name'];
	 
     unset($_SESSION[System::getSessionPrefix()."uploadedimage"]);
	   
   }
	 
	 $items=System::nameValueToSimpleArray(System::getPostedItems("det"));
   $tm=time();
	 
	 if(trim($items['det_cellphone'])=="")
  return System::getWarningText("Dealer's cellphone is required.");
  
  if(trim($items['det_email'])=="")
  return System::getWarningText("Dealer's email is required.");
  
  if((!$image_uploaded)&(!isset($_SESSION[System::getSessionPrefix()."uploadedimage"])))
   return System::getWarningText("Dealer's logo required.");

	 
    if($items['det_password']!=$items['det_repeatpassword']){
	   
	   $this->previous_data=$items;
	 
	   return System::getWarningText("Password mismatch.");
   }
   
   $dealer;
   
   if(isset($_SESSION[System::getSessionPrefix()."dealer_session"]))
     $dealer=unserialize($_SESSION[System::getSessionPrefix()."dealer_session"]);
	    

  $image_path=$thisimage;

  if(isset($_SESSION[System::getSessionPrefix()."uploadedimage"]))
  $image_path=$_SESSION[System::getSessionPrefix()."uploadedimage"];	
		
   $this->shared->updateDealer($dealer->dealer_id,base64_encode($items['det_description']),$image_path,
	   $items['det_email'],$items['det_cellphone'],$items['det_location'],$items['det_password']);


   return System::successText("Profile updated successfully.");

 }
 
}
public function register($update=false,$mess=""){

  $cont=new objectString();
  
  if(!$update){
  $sub=new input;
  
  $sub->setClass("form_button_add");
  
  $sub->setTagOptions("style=\"float:right;padding:3px 15px 3px 15px;\" title=\"Cancel\" ");
  
  $sub->input("button","edit_button","Cancel");
  $cont->generalTags(System::contentTitle("Register Dealer <a href=\"http://".preg_replace("/\/\//","/",System::basePath()).System::decipherUrl()."/\" style=\"overflow:hidden;\">{$sub->toString()}</a>"));
  }
  
  if($update)
  $cont->generalTags(System::contentTitle("Update Profile"));
  
  if($update)
  if(isset($_POST['remove_image'])){
 $items=System::nameValueToSimpleArray(System::getPostedItems("det"));
 unset($_SESSION[System::getSessionPrefix()."uploadedimage"]);
 if(file_exists(str_replace("../",ROOT,$_SESSION[System::getSessionPrefix()."uploadedimage"])));
 //$this->previous_data=$items;
 //$items['det_name']=$_POST['det_name'];
 }
  
  $cont->generalTags("<style>
   #label{
	   width:120px;
   }
  </style>");
  
  $addstyle="box-shadow:0px 0px 5px #ccc;
		   -moz-box-shadow:0px 0px 5px #ccc;
		   -webkit-box-shadow:0px 0px 5px #ccc;
		    border:1px solid #ccc;";
  
  if(!$update)
  $addstyle="box-shadow:0px 0px 5px #f15f5f;
          -moz-box-shadow:0px 0px 5px #f15f5f;
          -webkit-box-shadow:0px 0px 5px #f15f5f;
		   border:1px solid #f1b4b4;
		   -moz-border:1px solid #f1b4b4;
		   -webkit-border:1px solid #f1b4b4";
  
  $cont->generalTags("<style>
	   .form_input3{
		   width:160px;
		   border-radius:5px;
		  ;
		   padding:3px;
		   -moz-border-radius:5px;
		   -webkit-border-radius:5px;
		  $addstyle
	   }
	   .form_input{
		   border:1px solid #ccc;
		   padding:3px;
		   box-shadow:0px 0px 5px #ccc;
		   -moz-box-shadow:0px 0px 5px #ccc;
		   -webkit-box-shadow:0px 0px 5px #ccc;

	   }
	   </style>");
  
  if($update)  
  $cont->generalTags(System::categoryTitle("Edit your profile.","margin-bottom:5px;"));
  
  if(!$update)
  $cont->generalTags(System::categoryTitle("Enter your details below.","margin-bottom:5px;"));
  
  if($update)
  $cont->generalTags($mess);
  
  if(!$update)
  $cont->generalTags($this->registerDealer());
  
  $frm=new form_control;
  
  $frm->enableUpload();
  
  $cont->generalTags($frm->formHead());
  
  $input=new input;
  
  $input->setClass("form_input3");
  
  $input->input("text","det_name",System::getArrayElementValue($this->previous_data,"det_name"));
  
  $done="";
  
  if($update){
  $sub=new input;
  
  $sub->setClass("form_button_add");
  
  $sub->setTagOptions("style=\"float:right;\" title=\"Edit profile\" ");
  
  $sub->input("button","edit_button","Done Editing");
  $done="<a href=\"http://".preg_replace("/\/\//","/",System::basePath()).preg_replace("/edit\//i","",System::decipherUrl())."/\" style=\"overflow:hidden;\">{$sub->toString()}</a>";
  
  }
  
  if(!$update)
  $cont->generalTags("<div id=\"form_row\" style=\"padding-bottom:0px;\"><div id=\"label\"><strong style=\"color:#707172;\">Dealership Name</strong></div>{$input->toString()}</div>");
  
  if($update)
  $cont->generalTags("<div id=\"form_row\" style=\"padding-bottom:0px;\"><div id=\"label\"><strong style=\"color:#707172;\">Dealership Name</strong></div>".System::getArrayElementValue($this->previous_data,"det_name")."{$done}</div>");
  
  $input=new input;
  
  $input->setClass("form_input3");
  
  $input->input("text","det_cellphone",System::getArrayElementValue($this->previous_data,"det_cellphone"));
  
  $cont->generalTags("<div id=\"form_row\" style=\"padding-bottom:0px;\"><div id=\"label\"><strong style=\"color:#707172;\">Cellphone</strong></div>{$input->toString()}</div>");
  
  $cont->generalTags("<div id=\"form_row\" style=\"padding-bottom:0px;\"><div id=\"label\"><strong style=\"color:#707172;\">Description</strong></div><textarea class=\"form_input\" name=\"det_description\" style=\"width:250px;height:100px;\">".System::getArrayElementValue($this->previous_data,"det_description")."</textarea></div>");
  
  $input=new input;
  
  $input->setClass("form_input3");
  
  $input->input("text","det_email",System::getArrayElementValue($this->previous_data,"det_email"));
  
   $cont->generalTags("<div id=\"form_row\" style=\"padding-bottom:0px;\"><div id=\"label\"><strong style=\"color:#707172;\">Email</strong></div>{$input->toString()}</div>");

$input=new input;
  
  $input->setClass("form_input");
  
  $input->input("text","det_location",System::getArrayElementValue($this->previous_data,"det_location"));

 $cont->generalTags("<div id=\"form_row\" style=\"padding-bottom:0px;\"><div id=\"label\"><strong style=\"color:#707172;\">Location</strong></div>{$input->toString()}</div>");

$lf=new input;

$lf->setClass("form_input3");

$lf->input("file","logofile");

if(!isset($_SESSION[System::getSessionPrefix()."uploadedimage"])){
 
 $cont->generalTags("<div id=\"form_row\" style=\"padding-bottom:0px;\"><div id=\"label\"><strong style=\"color:#707172;\">Logo</strong></div>{$lf->toString()}</div>");

}else{

 $del=new input;
 
 $del->setClass("form_button_delete");
 
 $del->setTagOptions("style=\"padding:5px;float:left;margin-left:120px;\"");
 
 $del->input("submit","remove_image","Remove Image"); 

$logo=str_replace("../",System::getFolderBackJump(),$_SESSION[System::getSessionPrefix()."uploadedimage"]);

$cont->generalTags("<div id=\"form_row\" style=\"padding-bottom:0px;\"><div id=\"label\"><strong style=\"color:#707172;\">Logo</strong></div><img src=\"{$logo}\" height=\"50px\"/></div>");

if($update)
unset($_SESSION[System::getSessionPrefix()."uploadedimage"]);

$cont->generalTags("<div id=\"form_row\">{$del->toString()}</div>");

}
 //$cont->generalTags("<div style=\"width:400px;margin:auto;float:left;\">");
 
 $cont->generalTags(System::categoryTitle("Enter your preferred password","margin-bottom:5px;"));
 
 $input=new input;
 
 $input->setClass("form_input3");
 
 $input->input("password","det_password");
 
 $cont->generalTags("<div id=\"form_row\" style=\"padding-bottom:0px;\"><div id=\"label\"><strong style=\"color:#707172;\">Password</strong></div>{$input->toString()}</div>");
  
 $input=new input;
 
 $input->setClass("form_input3");
 
 $input->input("password","det_repeatpassword");
  
 $cont->generalTags("<div id=\"form_row\" style=\"padding-bottom:0px;\"><div id=\"label\"><strong style=\"color:#707172;\">Repeat Password</strong></div>{$input->toString()}</div>");
  
 $sub=new input;
 
 $sub->setClass("form_button");
 
 $sub->setTagOptions("style=\"padding:5px;float:right;\"");
 
 if(!$update) 
 $sub->input("submit","register_dealer","Submit"); 
  
  if($update)
  $sub->input("submit","rupdate_profile","Update Profile");
  
 $cont->generalTags("<div id=\"form_row\">{$sub->toString()}</div>");
  
 $cont->generalTags("<div id=\"form_row\" style=\"border-bottom:1px solid #eee;padding:0px;\"></div>");
 
 $cont->generalTags("</form>");
  
 return $cont->toString();	

}
public function dealersList(){

 $cont=new objectString;
 
 $cont->generalTags("<style>img{box-shadow:none;}</style>");

 $list=new open_table;
 
 $list->hideHeader();
 
 $list->setColumnNames(array("Dealer","Info"));
 
 $list->setColumnWidths(array("240px","470px"));
 
 $list->setHoverColor("#eee");
 
 $dealers=$this->shared->getDealers("where dealer_status=1");
 
 for($i=0;$i<count($dealers);$i++)
 $list->addItem(array("<a href=\"http://".preg_replace("/\/\//","/",System::basePath()).System::decipherUrl()."/".str_replace(" ","",$dealers[$i]->dealer_name)."/\" style=\"overflow:hidden;\"><img src=\"".str_replace("../",System::getFolderBackJump(),$dealers[$i]->dealer_logo)."\" style=\"width:238px;margin:auto;\" /></a>","<div id=\"form_row\" style=\"\"><strong><a href=\"http://".preg_replace("/\/\//","/",System::basePath()).System::decipherUrl()."/".str_replace(" ","",$dealers[$i]->dealer_name)."/\" style=\"overflow:hidden;\">".$dealers[$i]->dealer_name."</a></strong></div><div id=\"form_row\" style=\"padding:0px;\">".$dealers[$i]->dealer_description."</div>"));
 
 //$list->addItem(array("names","names"));
 
 $list->showTable(false);
 
 $cont->generalTags($list->toString());

 if(count($dealers)==0) 
 $cont->generalTags(System::categoryTitle("No Dealers Found","width:99%;"));
 
 return $cont->toString();

}

}
?>