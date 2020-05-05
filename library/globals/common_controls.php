<?php

define("OPTION_MODULES",0);
            
define("OPTION_LIB_ETC",1);
            
define("OPTION_MACRO",2);
            
define("OPTION_PLUGIN",3);

include_once("file_handler.php");

define("TITLE",1);

define("META",2);
	  
class loadControlScript{
    public function __construct(){
    
    if(defined("NOT_DEFAULT")){
    ?>
    <script type="text/javascript" src="../library/scripts/controls.js" language="javascript" ></script>
    <link rel="stylesheet" href="../system/layout_css.css" type="text/css" >
    <link rel="stylesheet" href="../library/scripts/css/jquery.datepick.css" type="text/css" />
   	<script type="text/javascript" src="../system/jscripts/tiny_mce/tiny_mce.js"></script>
    <?php
    }else{
    ?>
    
    <link rel="stylesheet" href="<?php echo System::getFolderBackJump(); ?>system/layout_css.css" type="text/css" />
       
    <script type="text/javascript" src="<?php echo System::getFolderBackJump(); ?>library/scripts/jquery-1.6.1.min.js" language="javascript" ></script>
   <script type="text/javascript" src="<?php echo System::getFolderBackJump(); ?>library/scripts/controls.js" language="javascript" ></script>
    <script type="text/javascript" src="<?php echo System::getFolderBackJump(); ?>library/scripts/jquery.form.js" language="javascript" ></script>
    
    <?php echo $this->getMacroScriptsAndStyles(); ?>

    <link rel="stylesheet" href="<?php echo System::getFolderBackJump(); ?>library/scripts/css/jquery.datepick.css" type="text/css" />
    
    <?php
    }
 }
 private function getMacroScriptsAndStyles(){
 GLOBAL $db;
  $op=0;
  
  if(isset($_GET['opt']))
  $op=$_GET['opt'];
  
  $macros=$db->getMacros(" where id=".$op);
  
  $styles="";
  
  $the_scripts="";
  
  
  for($i=0;$i<count($macros);$i++){
    if(is_dir(dirname(__FILE__)."/../styles/macro_styles/".$macros[$i]->macro_name."/")){
   
     $files_in=scandir(dirname(__FILE__)."/../styles/macro_styles/".$macros[$i]->macro_name."/");
     
	 for($c=0;$c<count($files_in);$c++)
	 if(is_file(dirname(__FILE__)."/../styles/macro_styles/".$macros[$i]->macro_name."/".$files_in[$c]))
	$styles.="<link rel=\"stylesheet\" href=\"".System::getFolderBackJump()."library/styles/macro_styles/{$macros[$i]->macro_name}/{$files_in[$c]}\" type=\"text/css\" />\n";
	 
   }
  }
  
  
  for($i=0;$i<count($macros);$i++){
    if(is_dir(dirname(__FILE__)."/../scripts/macro_scripts/".$macros[$i]->macro_name."/")){
   
     $files_in=scandir(dirname(__FILE__)."/../scripts/macro_scripts/".$macros[$i]->macro_name."/");
     
	 for($c=0;$c<count($files_in);$c++)
	 if(is_file(dirname(__FILE__)."/../scripts/macro_scripts/".$macros[$i]->macro_name."/".$files_in[$c]))
	$the_scripts.="<script type=\"text/javascript\" src=\"".System::getFolderBackJump()."library/scripts/macro_scripts/{$macros[$i]->macro_name}/{$files_in[$c]}\" language=\"javascript\" ></script>\n";
	 
   }
  }
  return $the_scripts.$styles;
 }
}
class System{
    
    public static function warningText($text,$style=""){
        
        if($text!=""){
            
          echo "<div id=\"warning_msg\" style=\"$style\">".$text."</div>";
          
        }
        
    }
    
    public static function getWarningText($text,$style=""){
        
        if($text!=""){
            
          return "<div id=\"warning_msg\" style=\"$style\">".$text."</div>";
          
        }
        
    }
    public static function getRootLink(){
        return "http://".preg_replace("/\/\//i","/",$_SERVER['HTTP_HOST'].ROOT_DIR);
    }
	public static function enableDatePicker(){
	
	if(defined("EN_PICKER")) return;
	
	if(defined("NOT_DEFAULT")){	
	?>
	  <script language="javascript" type="text/javascript" src="../library/scripts/jquery-1.6.1.min.js"></script>
    
    <script language="javascript" type="text/javascript" src="../library/scripts/jquery.datepick.js"></script>

	<?php 
	}else{
	?>
     <script language="javascript" type="text/javascript" src="<?php echo self::getFolderBackJump(); ?>library/scripts/jquery.datepick.js"></script>
     <script type="text/javascript" src="<?php echo self::getFolderBackJump(); ?>library/scripts/priceformat.js" language="javascript" ></script>
    <?php
	}
	
	define("EN_PICKER",1);
	
	}
	public static function ajaxUrl($module_type,$module_name){
	 $conf=new config;
	 return $conf->baseUrl.System::getFolderBackjump()."plugins/ajax_handler/?drt=1&typ=".$module_type."&nm=".$module_name;
	}
	public static function tableRow($table_id,$row_id,$content=array(),$column_width=array(),$border=""){
		$data="<div class=\"trow\" id=\"lst{$table_id}$row_id\">";
		
		for($i=0;$i<count($content);$i++){
			 
		  $data.="<div class=\"cells\" style=\"width:{$column_width[$i]};border:$border\">{$content[$i]}</div>";
		
		}
		$data.="</div>";
		
		return $data;
	}
	public static function loadEditor($content="",$theme="advanced",$ids="elm1",$style=""){
	 
	 $cont=new objectString();
	 
	 if(!defined("TINYMC_ENABLED")){
	  define("TINYMC_ENABLED",1);
	 $cont->generalTags("<script type=\"text/javascript\">
	tinyMCE.init({
		mode : \"textareas\",
		theme : \"advanced\",
		plugins : \"autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks\",
	
// Theme options
		theme_advanced_buttons1 : \"save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect\",
		");
		if(($theme=="advanced")||($theme=="advanced2")){
		$cont->generalTags("theme_advanced_buttons2 : \"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor\",
		theme_advanced_buttons3 : \"tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen\",");
		}
		if($theme=="advanced2"){
		$cont->generalTags("theme_advanced_buttons4 : \"insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks\",
		theme_advanced_toolbar_location : \"top\",
		theme_advanced_toolbar_align : \"left\",
		theme_advanced_statusbar_location :\"bottom\",
		theme_advanced_resizing : true,	
		");
		}
		$cont->generalTags("
		// Example content CSS (should be your site CSS)
		content_css : \"tinymc/css/content.css\",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : \"tinymc/lists/template_list.js\",
		external_link_list_url : \"tinymc/lists/link_list.js\",
		external_image_list_url : \"tinymc/lists/image_list.js\",
		media_external_list_url : \"tinymc/lists/media_list.js\",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : \"Some User\",
			staffid : \"991234\"
		}
	});
</script> 

");
	 }
	 
	 $cont->generalTags("
<div>
			<textarea id=\"{$ids}\" name=\"{$ids}\" style=\"{$style}\" rows=\"15\" cols=\"80\" >
			{$content}
			</textarea>
			
		</div>
       ");
	 
	return $cont->toString();
	}
	public static function divCont($value,$class="",$id="",$style=""){
		
		$cont=new objectString;
		
		$cont->generalTags('<div class="'.$class.'" id="'.$id.'" '.$style.' >'.$value.'</div>');
		
		return $cont->toString();
		
	}
	public static function getPageTitle(){
		
	  GLOBAL $db;
	  
	  $item=$db->getMenuItem($_GET['mid']);
	 
	  $macros=$db->getMacro($item->item_macroId);
	  
	  
	  if(file_exists(dirname(__FILE__)."/../../extensions/macros/".$macros->macro_name."/custom_meta.php")){
	  
	    include_once(dirname(__FILE__)."/../../extensions/macros/".$macros->macro_name."/custom_meta.php");
		
		$title=meta_run(TITLE);
		
		if($title!=false)
		return $title;
	  
	  }
	 
	 if(defined("PAGE_TITLE")): return PAGE_TITLE; endif;
	  
	 
	}
	public static function enableTimePicker(){
	
	if(defined("NOT_DEFAULT")){	
	?>
	  <script language="javascript" type="text/javascript" src="../library/scripts/jquery-1.6.1.min.js"></script>
     <script language="javascript" type="text/javascript" src="../library/scripts/jquery-ui-sliderAccess.js"></script>
     <script language="javascript" type="text/javascript" src="../library/scripts/jquery-ui-timepicker-addon.js"></script>

	<?php 
	}else{
	?>
    <script language="javascript" type="text/javascript" src="library/scripts/jquery-1.6.1.min.js"></script>
    <script language="javascript" type="text/javascript" src="library/scripts/jquery-ui-sliderAccess.js"></script>
    <script language="javascript" type="text/javascript" src="library/scripts/jquery-ui-timepicker-addon.js"></script>
    <?php
	}

	define("EN_TIMEPICKER",1);
	
	}
	public static function getMenuLinks(){
	
	
	
	}
	public static function showCaptcha($public_key){
	 include_once("recaptchalib.php");
	 
	 return(recaptcha_get_html($public_key));
	 
	}
	public static function checkCaptchaAnswer($private_key){
	
	 include_once("recaptchalib.php");
	 
	 $resp= recaptcha_check_answer($private_key,$_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
								
	 if(!$resp->is_valid){
	 
	 return false;
	 
	 }
	 return true;
	
	}
	public static function generateCode($length,$alpha_numeric=false){
        
       $data=array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
       
       $thecode="";
        
       if(!$alpha_numeric)
       $data=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        
       for($i=0;$i<($length+1);$i++)
       if(!$alpha_numeric){
        $thecode.= $data[rand(0,25)];
       }else{
        $thecode.= $data[rand(0,34)];
       }
       return $thecode;
    }
	public static function getMacroSettingsData($macro_id){
		
		GLOBAL $db;
		
		$resource=$db->selectQuery(array("*"),"macrodata"," where macro_id=".$macro_id);
		
		while($row=mysqli_fetch_row($resource)){
		   if(preg_match("/WIN/i",PHP_OS)){
		   
		   return unserialize(base64_decode($row[1]));
		   }
		   $l_data=array();
		   
		   $arr=unserialize(base64_decode($row[1]));
		   
		   $keys=array_keys($arr);
		   		   
		   for($i=0;$i<count($arr);$i++)
		   $l_data[$keys[$i]]=stripslashes($arr[$keys[$i]]);
		   
		   return $l_data;
		   
		}
		return false;
		
	}
	public static function saveMacroSettingsData($macro_id,$data){
		
		GLOBAL $db;
		
		if(self::getMacroSettingsData($macro_id)==false){ 
		 $db->insertQuery(array("macro_id","data"),"macrodata",array($macro_id,"'".base64_encode(serialize($data))."'"));
		 return self::successText("Settings saved successfully");
		}else{
		 $db->updateQuery(array("data='".base64_encode(serialize($data))."'"),"macrodata"," where macro_id=".$macro_id);
		}
		return self::successText("Settings saved successfully");
		
	}
	public static function saveModuleSettingsData($module_id,$data){
		
		GLOBAL $db;
		
		if(self::getModuleSettingsData($module_id)==false){ 
		 $db->insertQuery(array("module_id","data"),"moduledata",array($module_id,"'".base64_encode(serialize($data))."'"),'');
		 return self::successText("Settings saved successfully");
		}else{
		 $db->updateQuery(array("data='".base64_encode(serialize($data))."'"),"moduledata"," where module_id=".$module_id,'');
		}
		return self::successText("Settings saved successfully");
		
	}
	
	public static function getModuleSettingsData($module_id){
		
		GLOBAL $db;
		
		$resource=$db->selectQuery(array("*"),"moduledata"," where module_id=".$module_id,'');
		
		while($row=mysqli_fetch_row($resource)){
		   if(preg_match("/WIN/i",PHP_OS)){
		   
		   return unserialize(base64_decode($row[1]));
		   }
		   $l_data=array();
		   
		   $arr=unserialize(base64_decode($row[1]));
		   
		   $keys=array_keys($arr);
		   		   
		   for($i=0;$i<count($arr);$i++)
		   $l_data[$keys[$i]]=stripslashes($arr[$keys[$i]]);
		   
		   return $l_data;
		   
		}
		return false;
		
	}
	public static function getUserEmail($id){
	
	 GLOBAL $db;
	 
	 $users=$db->getUserDetails("where id=".$id);
	 
	 for($i=0;$i<count($users);$i++){
	 
	   return $users[$i]->email_address;
	 
	 }
	
	 return NULL;
	 
	}
	public function pageIndicator($ids,$interv=20,$range=2,$current_page=2,$base_url="",$added_url=""){
	$pages=0;
	$string="";
	
	//pages
	$in=0;
	if(round((count($ids)/$interv))>1)
	$pages=round((count($ids)/$interv),0.1);
	
	if((count($ids)/$interv)-round((count($ids)/$interv),0.01)>0){
		$pages=round((count($ids)/$interv),0.01)+1;
	}
	
	$pageids=array();
	for($i=0;$i<$pages;$i++)
	if(isset($ids[$in])){
	$pageids[]=$ids[$in];
	$in+=$interv;
	}
	
	$rg=0;	
	$cnt=0;
	
	
	
	if ($pages>1){
	
	$start_at=$current_page-round($range/2)<1 ? 1:$current_page-round($range/2);
	
	$end_at=$current_page+round($range/2);
	
	for($i=0;$i<$pages;$i++){
		
	if((($i+1)>=$start_at) && (($i+1)<=$end_at)){
	 $state="";
	  if($current_page==$i+1){
		$state="style=\"color:#107ded!important;font-weight:bold;text-decoration:underline;\"";
	 }
	 $string.= "<div id=\"i_page\"><a href=\"{$base_url}?{$added_url}page=".($i+1)."\" $state >".($i+1)."</a></div>";
	}else{
		if((($i+1)==1)&&(($current_page-round($range/2))>1)){
			if(($i+1)!=$start_at-1){
			$state="";
			 if($current_page==$i+1){
			   $state="style=\"color:#107ded!important;font-weight:bold;text-decoration:underline;\"";
			 }
			$string.= "<div id=\"i_page\"><a href=\"{$base_url}?{$added_url}page=1\" $state >First</a></div>";
	        $string.= "<div id=\"i_page\"><a href=\"{$base_url}?{$added_url}page=".($current_page-1)."\" $state >Prev</a></div>";
			}else{
			 $state="";
			 if($current_page==$i+1){
			   $state="style=\"color:#107ded!important;font-weight:bold;text-decoration:underline;\"";
			 }
			 $string.= "<div id=\"i_page\"><a href=\"{$base_url}?{$added_url}page=1\" $state >1</a></div>";
			}
		}else{
			if((($pages-$end_at)>1)){
		      $state="";
			  
			 if($current_page==$end_at-1){
			   $state="style=\"color:#107ded!important;text-decoration:underline;font-weight:bold;\"";
			 }
			  
			 		  
			  if(($i+1)>$end_at-2){
			  $string.= "<div id=\"i_page\"><a href=\"{$base_url}?page=".($current_page+1)."\" $state >Next</a></div>";
	          $string.= "<div id=\"i_page\"><a href=\"{$base_url}?page=".$pages."\" $state >Last>></a></div>";
               break;
			  }
			
			}else{
				if(($i+1)==$end_at+1){
			   $state="";
			   
			 if($current_page==$end_at+1){
			   $state="style=\"color:#107ded!important;font-weight:bold;text-decoration:underline;\"";
			}
				  $string.= "<div id=\"i_page\"><a href=\"{$base_url}?page=".$pages."\" $state >".$pages."</a></div>";
				  break;  
				}
             
			}
		}
	}
	}
	
	 
	}
	
	$pagedDet=new pageDetails;
	if(isset($ids[($current_page-1)*$interv])){
	
	  $pagedDet->page_minid=$ids[($current_page-1)*($interv)];
	
	}else{
	 if(isset($ids[$current_page-1])){
	 $pagedDet->page_minid=$ids[$current_page-1]-1;
	 }else{
		 $pagedDet->page_minid=1;
	 }
	}
	if(isset($ids[(($current_page-1)*$interv)+($interv-1)])){
	 $pagedDet->page_maxid=$ids[(($current_page-1)*$interv)+($interv-1)];
	}else{
	  if(isset($ids[count($ids)-1]))
	  $pagedDet->page_maxid=$ids[count($ids)-1];
	}
	if($string!="")
	$pagedDet->page_layout="<div id=\"i_page\" style=\"margin-right:20px;\">Page $current_page of $pages</div>".$string;
	
    return $pagedDet; 
	
	}
	public static function getTimeFields($pref){
	  $hour=new input;
	  $hour->addItem(-1,"H");
	  $hour->setHourValues();
	  $hour->setClass("form_select");
	  $hour->select($pref."_hour");
	  
	  $min=new input;
	  $min->addItem(-1,"M");
	  $min->setMinuteValues();
	  $min->setTagOptions("style=\"margin-left:3px;\"");
	  $min->setClass("form_select");
	  $min->select($pref."_minute");

      $ampm=new input;
	  $ampm->setAmPm();
	  $ampm->setTagOptions("style=\"margin-left:3px;\"");
	  $ampm->setClass("form_select");
	  $ampm->select($pref."_ampm");

	  
	  return $hour->toString().$min->toString().$ampm->toString();
	}
	public static function imageGallary($images){
	
	 $cont=new objectString;
	 if(count($images)>0)
	 
	 $arr=array();
	 $views=array();
	 for($i=0;$i<count($images);$i++){
	 $arr[]=self::getFolderBackJump().$images[$i]->image_path;
	 $views[]=$images[$i]->image_view;
	 }
	 
	 $cont->generalTags("<script language=\"javascript\">
	 
	 var l_im=new Array(\"".@implode("\",\"",$arr)."\");
	 var l_view=new Array(\"".@implode("\",\"",$views)."\");
	 var l_c=0;
	
	 function lnext(){
     
	 l_c+=1;
	 
	 if(l_c<l_im.length){
		 
		rr=l_im[l_c];
	    document.getElementById('l_view').innerHTML=l_view[l_c];
	   document.getElementById('m_image').style.width=\"\";
	   document.getElementById('m_image').style.height=\"\";
	   document.getElementById('m_image').src=rr;
	   
	 }else{
	   l_c=0;
	   rr=l_im[l_c];
	   document.getElementById('l_view').innerHTML=l_view[l_c];
	   document.getElementById('m_image').style.width=\"\";
	   document.getElementById('m_image').style.height=\"\";
	   document.getElementById('m_image').src=rr;

	 }
	 
	 }
	 
	 function lprev(){

	 l_c-=1;
	 
	 if(l_c>-1){
		 
		rr=l_im[l_c];
		document.getElementById('l_view').innerHTML=l_view[l_c];
		document.getElementById('m_image').style.width=\"\";
	   document.getElementById('m_image').style.height=\"\";
	   document.getElementById('m_image').src=rr;
	   
	 }else{
	   l_c=l_im.length-1;
	   rr=l_im[l_c];
	   document.getElementById('l_view').innerHTML=l_view[l_c];
	   document.getElementById('m_image').style.width=\"\";
	   document.getElementById('m_image').style.height=\"\";
	   document.getElementById('m_image').src=rr;

	 }
	 
	 }
	 	 
	 </script>");
	 if(count($images)>0){
	 $im=self::getFolderBackJump()."{$images[0]->image_path}";
	 }else{
	  $im="";	 
	 }
	 $cont->generalTags("<img id=\"m_image\" src=\"$im\" style=\"margin-top:0px;\"/>");
	 
	 for($i=0;$i<count($images);$i++){
	
	 $cont->generalTags("<div style=\"display:none;\"><img id=\"m_image{$i}\" src=\"".self::getFolderBackJump()."{$images[$i]->image_path}\" style=\"margin:auto;margin-top:0px;\"/></div>");
	
	 }

     return $cont->toString();
	
	}
	public static function gallaryArrows($width=500,$height=500){
		
		$cont=new objectString();
		
		$cont->generalTags("<div id=\"arrow_l\" onclick=\"lprev(),reposDiv('m_image',$width,$height)\"></div><div id=\"arrow_r\" onclick=\"lnext(),reposDiv('m_image',$width,$height)\"></div><div style=\"display:none;width:165px;height:30px;float:left;font-weight:bold;\"><strong  id=\"l_view\" style=\"margin-top:5px;float:left;font-size:14px;\"></strong></div>");
//<div id=\"zoomin\" onclick=\"nzoomin()\"></div><div id=\"zoomout\" onclick=\"nzoomout()\"></div>
		$cont->generalTags("<script>
		function nzoomin(){
		 document.getElementById('m_image').style.width=(document.getElementById('m_image').offsetWidth+20)+\"px\";
		 reposDiv('m_image',$width,$height);
		}
		function nzoomout(){
		 document.getElementById('m_image').style.width=(document.getElementById('m_image').offsetWidth-20)+\"px\";
		 reposDiv('m_image',$width,$height);
		 
		}
		document.getElementById('l_view').innerHTML=l_view[0];
		</script>");
		
				
		return $cont->toString();
		
	}
	public static function addImagesToSession($path="",$prefix="im"){
	
	 $items=self::getPostedItems($prefix,1);
	 //$desc=self::getPostedItems("desc");
	 
	 $generated_aliases=array();
	
	 if(isset($_SESSION[self::getSessionPrefix().'_image_session'])){
	 
	   $images=unserialize($_SESSION[self::getSessionPrefix().'_image_session']);
	   
	    $img_view=array($prefix."_image"=>"View 1",$prefix."_image2"=>"View 2",$prefix."_image3"=>"View 3",$prefix."_image4"=>"View 4",$prefix."_image4"=>"View 5",$prefix."_image6"=>"View 6",$prefix."_image7"=>"View 7",$prefix."_image8"=>"View 8");
	   
	   for($i=0;$i<count($items);$i++){
		if(trim($items[$i]->value)!=""){
		$alias=time()."_".rand(1,1000).$items[$i]->value;

	    $new_image=new imageSession;
	    $new_image->image_name=$items[$i]->name;
		$new_image->image_description=$img_view[$items[$i]->name];
		$new_image->image_view=$img_view[$items[$i]->name];
	    $typ=explode(".",$items[$i]->value);
	    $new_image->image_alias=$alias.".".$typ[count($typ)-1];
		 $generated_aliases[]=$new_image->image_alias;
		
		$new_image->image_sTime=time();
	    $new_image->image_path="images/uploads/".$new_image->image_alias;
		
		if($path=="")
		$new_image->image_path=$path.$new_image->image_alias;
		
		$images[]=$new_image;
		
	   }
	   
	   $_SESSION[self::getSessionPrefix().'_image_session']=serialize($images);
	   }
	 }else{
	   $img_view=array($prefix."_image"=>"View 1",$prefix."_image2"=>"View 2",$prefix."_image3"=>"View 3",$prefix."_image4"=>"View 4",$prefix."_image4"=>"View 5",$prefix."_image6"=>"View 6",$prefix."_image7"=>"View 7",$prefix."_image8"=>"View 8");
	   $images=array();
	   for($i=0;$i<count($items);$i++){
	    if(trim($items[$i]->value)!=""){
		$alias=time()."_".rand(1,1000).$items[$i]->value;
	    $new_image=new imageSession;
	    $new_image->image_name=$items[$i]->name;
		$new_image->image_description=$img_view[$items[$i]->name];
		$new_image->image_view=$img_view[$items[$i]->name];
		$typ=explode(".",$items[$i]->value);
	    $new_image->image_alias=$alias.".".$typ[count($typ)-1];
        $generated_aliases[]=$new_image->image_alias;
		
		$new_image->image_sTime=time();
	    $new_image->image_path="images/uploads/".$new_image->image_alias;
		
		if($path=="")
		$new_image->image_path=$path.$new_image->image_alias;
		
		$images[]=$new_image;
		
	   }
  
       $_SESSION[self::getSessionPrefix().'_image_session']=serialize($images);
	   }
	 }
	  return $generated_aliases;
	}
	public static function removeImageFromSession($imagename,$path_prefix,$clear_files=true){
		
		$session_images=unserialize($_SESSION[self::getSessionPrefix().'_image_session']);
		
		$new_array=array();
		
		for($i=0;$i<count($session_images);$i++)
		if(!preg_match("/".$imagename."/i",$session_images[$i]->image_alias)){
			$new_array[]=$session_images[$i];
		}else{
			if($clear_files)
			if(file_exists($path_prefix."images/uploads/".$imagename))
			 unlink($path_prefix."images/uploads/".$imagename);
			 
		}
		
		$_SESSION[self::getSessionPrefix().'_image_session']=serialize($new_array);
		
	}
	public static function hasUploaded(){
	  $_SESSION[System::getSessionPrefix()."_HASUPLOAD"]=1;
	}
	public static function wasUploaded(){
	  unset($_SESSION[System::getSessionPrefix()."_HASUPLOAD"]);
	}
	public static function isUpload(){
	 
	 if (isset($_SESSION[System::getSessionPrefix()."_HASUPLOAD"]))
	 return true;
	 
	 return false;
	 
	}
	public static function forImageUploads(){
		$_SESSION[System::getSessionPrefix()."_forUpload"]=1;
	}
	public static function isForImageUploads(){
		if(isset($_SESSION[System::getSessionPrefix()."_forUpload"])){
		 return true;
		}
		return false;
	}
	public static function forImageUploadsToNormal(){
	 
	 unset($_SESSION[System::getSessionPrefix()."_forUpload"]);
	 
	}
	public static function uploadImages($path,$prefix){
		
		include_once("simpleimage.php");
		
		$status=false;
			
		$images=self::getPostedItems($prefix,1);
		
		self::hasUploaded();
		
		$aliases=self::addImagesToSession($path,$prefix);
		
		for($i=0;$i<count($images);$i++){
		
		$_SESSION[System::getSessionPrefix()."_HASUPLOAD"]=1;
		
		 $mypath=$path;
		 
		  if($path=="")
		  $mypath="images/uploads/";
		  
		  if(trim($_FILES[$images[$i]->name]['name'])!=""){
		  $status=true;
		 
		   $typ=explode(".",$_FILES[$images[$i]->name]['name']);
		   $image = new SimpleImage();
		   $image->load($_FILES[$images[$i]->name]['tmp_name']);
		   if($image->getWidth()>640){
		   $image->resizeToWidth(640);
		   }
		   if(isset($aliases[$i]))
		   $image->save($mypath.$aliases[$i]);
		   //move_uploaded_file($_FILES[$images[$i]->name]['tmp_name'],$mypath.$aliases[$i]);
		   
		  }
		  //echo System::warningText($_FILES[$images[$i]->name]['error']);
		
		}
		
		return $status;
	}
	public static function uploadRawImages($path,$prefix,&$result_paths=array()){
	include_once("simpleimage.php");
		
		$status=false;
			
		$images=self::getPostedItems($prefix,1);
		
		//self::hasUploaded();
		
		$aliases=array(time()."View_1",time()."View_2",time()."View_3",time()."View_4");
		
		for($i=0;$i<count($images);$i++){
		//$_SESSION[System::getSessionPrefix()."_HASUPLOAD"]=1;
		
		 $mypath=$path;
		 
		  if($path=="")
		  $mypath="images/uploads/";
		  
		  if(trim($_FILES[$images[$i]->name]['name'])!=""){
		  $status=true;
		   $typ=explode(".",$_FILES[$images[$i]->name]['name']);
		   $image = new SimpleImage();
		   $image->load($_FILES[$images[$i]->name]['tmp_name']);
		   if($image->getWidth()>640){
		   $image->resizeToWidth(640);
		   }
		   //if(isset($aliases[$i]))
		   $image->save($mypath.$aliases[$i].$_FILES[$images[$i]->name]['name']);
		   $result_paths[]=$aliases[$i].$_FILES[$images[$i]->name]['name'];
		   //move_uploaded_file($_FILES[$images[$i]->name]['tmp_name'],$mypath.$aliases[$i]);
		   
		  }
		  //echo System::warningText($_FILES[$images[$i]->name]['error']);
		
		}
		
		return $status;
	}
	public static function getSessionImages($asnamevalue=false){
		$myarray=array();
		if (isset($_SESSION[self::getSessionPrefix().'_image_session'])){
		 if($asnamevalue){
		    $si=unserialize($_SESSION[self::getSessionPrefix().'_image_session']);
			for($i=0;$i<count($si);$i++)
			$myarray[]=new name_value($i,$si[$i]->image_path);
		 }else{
		   return unserialize($_SESSION[self::getSessionPrefix().'_image_session']);	
		 }
		}
		
		return $myarray;
	}
	public static function clearSessionImages($pathpref="",$clear_files=true){
		
	   $images=array();
		
	   if(isset($_SESSION[self::getSessionPrefix().'_image_session']))
	   $images=unserialize($_SESSION[self::getSessionPrefix().'_image_session']);
	   
	   for($i=0;$i<count($images);$i++)
	   self::removeImageFromSession($images[$i]->image_alias,$pathpref,$clear_files);
	   
	   unset($_SESSION[self::getSessionPrefix().'_image_session']);
	   
	}
	public static function getDefaultProfileImage($gender){
	
	$path="../";
	
    if(!defined("NOT_DEFAULT"))
	 $path="";

	
	return "<div id=\"prof_image\"><img src=\"{$path}images/profile/default{$gender}.png\" width=\"120px\" height=\"120px\"/></div>";
	
	}
	public static function popupBox($contents,$id,$style="",$closebywrapper=false,$reloadonexit=true,$forimage=false){
	
	$content=new objectString;
	
	$wr="";
	
	$third_param="false";
	
	if($reloadonexit){
	$third_param="true";
	
	}
	if($closebywrapper)
	$wr="onclick=\"hideThese('{$id}_wrapper','$id','{$id}_form',{$third_param})\"";
	
	$fim="";
	
	if($forimage)
	$fim="_image";
	
	$content->generalTags("<div id=\"{$id}_wrapper\" class=\"pop_up_wrapper\" $wr ></div><div id=\"$id\" class=\"pop_up{$fim}\" style=\"$style\"><div style=\"width:100%;overflow:hidden; margin-bottom:3px;\"><div id=\"closebn2\" onmousedown=\"hideThese('{$id}_wrapper','$id','',{$third_param})\" >X</div></div>$contents</div>");
	
	//".self::categoryTitle("<div style=\"width:100%;overflow:hidden; margin-bottom:3px;\"><div id=\"closebn\" onmousedown=\"hideThese('{$id}_wrapper','$id','',{$third_param})\" >Close X</div>")."
	
	return $content->toString();
	
	}
	public static function showImage($image){
		
		$path="../";
	
    if(!defined("NOT_DEFAULT"))
	 $path="";

	
	return "<div id=\"prof_image\"><img src=\"{$path}$image\" width=\"120px\" height=\"120px\"/></div>";
		
	}
	public static function getSiteSubfolders(){
	
	  $myroot=defined("ADMIN_ROOT") ? ADMIN_ROOT : ROOT;	
		
	  return str_replace(str_replace("\\","/",$_SERVER['DOCUMENT_ROOT']),"",str_replace("\\","/",$myroot));

		
	}
	public static function matchArrayValues($name_value,$swapvalues){
		
		for($i=0;$i<count($name_value);$i++){
		 $name_value[$i]->name=$swapvalues[$name_value[$i]->value];
		}
		return $name_value;
	}
	public static function getPostedItems($prefix,$type=0){
     $array=array();
	 
	 $keys=array_keys($_POST);
	 
	 if($type==1)
	 $keys=array_keys($_FILES);
	 	 
	 for($i=0;$i<count($keys);$i++){
	 
	 if(preg_match("/$prefix/i",$keys[$i])){
	   
	   $temp=new name_value;
	  
	   $temp->name=$keys[$i];
	   
	   if($type==0){
	   $temp->value=$_POST[$keys[$i]];
	   }else{
	   $temp->value=$_FILES[$keys[$i]]['name'];
	   }
	   $array[]=$temp;	 
	  
	  }
	  
	 }
	 
	 return $array;	
	
    }
	public static function getMenuLinkSessionName(){
		
		return System::getSessionPrefix()."menu_to_link";
		   
	}
	public static function getRawLink($alias){
		
		GLOBAL $db;
		
				
		$myMenu=array();
		
		if(isset($_SESSION[self::getMenuLinkSessionName()])){
			
		 $myMenu=unserialize($_SESSION[self::getMenuLinkSessionName()]);
		
		 if(isset($myMenu["$alias"])){
		 
		  return $myMenu[$alias];
		 
		 }
		 
		}else{
			
			$modules=$db->getModules(" where status=1 and ismenu=1 and for_super=0");
			
			for($i=0;$i<count($modules);$i++){
				$myMenu=$db->getMenuItems($modules[$i]->module_id);
				
				$myMenu=unserialize($_SESSION[self::getMenuLinkSessionName()]);
				
				if(isset($myMenu["$alias"])){
		 
		  return $myMenu[$alias];
		 
		 }

				
			}
			
		}
		
		return "";
		
	}
	public static function decipherUrl(){
		if(!defined("ADMIN_ROOT")){
		
		$paths="";
			
		if(ROOT_DIR=="/"){
			
		$paths=explode("/",$_SERVER['REQUEST_URI']);
		
		}else{
		
		$paths=explode("/",str_replace(ROOT_DIR,"",$_SERVER['REQUEST_URI']));
		
		}
		
		for($i=0;$i<count($paths);$i++)
		if($paths[$i]!="")
		return str_replace("?".$_SERVER['QUERY_STRING'],"",$paths[count($paths)-2]);
		}else{
		 return "";
		}
		
	}
	public static function basePath(){
		
		return preg_replace("/\/\//i","/",$_SERVER['HTTP_HOST'].ROOT_DIR);
		
	}
	private static function decipherUrlPathCount(){
		if(preg_match("/WIN/i",PHP_OS)){
		$paths=explode("/",str_replace(ROOT_DIR,"",$_SERVER['REQUEST_URI']));
		}else{
		$paths=array_filter(explode("/",$_SERVER['REQUEST_URI']));
		}
	    $c=0;
		
		if(preg_match("/WIN/i",PHP_OS))
		$c=-1;
		
		for($i=0;$i<count($paths);$i++)
		if(($paths[$i]!="")&&($paths[$i]!="?"))
		$c+=1;
		
		if(count($paths)>0)
		if((System::getArrayElementValue($paths,count($paths)-1)!="?")&&(System::getArrayElementValue($paths,count($paths)-1)!="")){
		if(preg_match("/WIN/i",PHP_OS))
		$c-=1;
		
		}
		return $c;
		
	}
	public static function getFolderBackJump(){
		$c =self::decipherUrlPathCount();
		
		$path_jump="";
		
		for($i=0;$i<$c;$i++){
		$path_jump.="../";
		
		}
		
		return $path_jump;
	}
	public static function getDaysOfTheWeek(){
	
	return array(new name_value("Monday",0),new name_value("Tuesday",1),new name_value("Wednesday",2),new name_value("Thursday",3),new name_value("Friday",4),new name_value("Saturday",5),new name_value("Sunday",6));
	
	}
	public static function getDay(){
	
	return date("l",time());
	
	}
	public static function getTodaysDate(){
	
	 return date("d",time())."/".date("M",time())."/".date("Y",time());
	
	}
	public static function getDayValue(){
	
	$days=self::getDaysOfTheWeek();

     for($i=0;$i<count($days);$i++){
		 
	  if($days[$i]->name==self::getDay())
	  return $i;
	 //echo $days[$i]->name."<br/>";
	 }
    	
	}
	public static function isAbove18(){
	
	 return "-6588";
	
	}
	public static function getDayById($id){
	
	$days=self::getDaysOfTheWeek();
	
	$day=self::getArrayElementValue($days,$id,0);
	
	if($day->value!=0)
	return $day->name;
	
	return "Invalid day";
	
	}
	public static function ajaxViewMoreButton($id="id",$content="",$params="",$style="float:right;"){

	 $ajaxdiv=new input;
	 
	 $ajaxdiv->enableAjax(true);
	 
	 $ajaxdiv->setTagOptions("style=\"$style\"");
	 
	 $ajaxdiv->setClass("a_viewmore");
	 
	 $ajaxdiv->showAjaxProgress();
	 
	 $ajaxdiv->setId($id);
	 
	 $ajaxdiv->ajaxDiv($id,"<div style=\"display:none;\">$content</div>",$params);
	
	 return $ajaxdiv->toString();
		
	}
	public static function resetPassword(&$option_status=""){
		
		GLOBAL $db;
		$mail=new mailMessenger;
		
		if(isset($_POST["psttype"])){
			
			if($_POST["psttype"]==2){
				
				if(($_POST['client_email']=="")|(!preg_match("/@/i",$_POST['client_email']))||(count(explode(".",$_POST['client_email']))<2)){
					
				define("RESET_MESSAGE",self::getWarningText("Error! Invalid email address","text-align:center;"));
				$option_status->name=2;
				$option_status->value=false;
								
				}else{
				
				$users=$db->getUserDetails("where email='".$_POST['client_email']."'");
				
				for($i=0;$i<count($users);$i++){
				
				 $mess=new XMessage;
				 
				 $mess->message_content="Dear ".$users[$i]->firstname."<br/><br/>";
				 
				 $mess->message_content.="Please click on the link below to reset your password.<br/>";
				 
				 $mess->message_content.="<a href=\"http://".$_SERVER["HTTP_HOST"]."/".self::getSiteSubfolders()."?reset_token=".$db->generateUserToken($users[$i]->id)."&email=".$_POST['client_email']."\">Reset password</a><br/><br/>";
				 
				 $mess->message_content.="Admin.";
				 
				 $mess->message_subject="Passord reset";
				 
				 $mess->message_to=$_POST['client_email'];
				 
				 $mail->sendMessage($mess);
				
				 define("RESET_MESSAGE",self::info("Reset link has been sent to your email address.","margin-top:30px;float:left;font-weight:bold;color:#60a719;"));
				 
				 $option_status->name=2;
				 
				 $option_status->value=true;
				
				 return;
				
				}
				
				 define("RESET_MESSAGE",self::getWarningText("Error email address not found.","text-align:center;"));
				 
				 $option_status->name=2;
				$option_status->value=False;

				
			
				}
				
			
			}else{
			
			 $users=$db->getUserDetails("where email='".$_POST['client_email']."'");
				
				if(($_POST['client_email']=="")|(!preg_match("/@/i",$_POST['client_email']))||(count(explode(".",$_POST['client_email']))<2)){
					
				define("RESET_MESSAGE",self::getWarningText("Error! Invalid email address","text-align:center;"));
				$option_status->name=0;
				$option_status->value=false;
				return;		
				}else{
				$found=false;
				for($i=0;$i<count($users);$i++){
			
			    		    
			     $mess=new XMessage;
				 
				 $mess->message_content="Dear ".$users[$i]->firstname."<br/><br/>";
				 
				 $mess->message_content.="Please find your username below.<br/>";
				 
				 $mess->message_content.="<strong>Username</strong>: {$users[$i]->username}<br/>";
				 				 
				 $mess->message_content.="Admin.";
				 
				 $mess->message_subject="Username reminder";
				 
				 $mess->message_to=$_POST['client_email'];
				 
				 $mail->sendMessage($mess);

			
			    define("RESET_MESSAGE",self::info("Username sent to your email address."."<a  class=\"form_button\" style=\"margin-left:150px; margin-top:20px;float:left;padding:4px 5px 4px 5px;color:#ffffff;text-decoration:none;\" href=\"?\">Login</a>","margin-top:30px;float:left;font-weight:bold;color:#60a719;"));
				
				$option_status->name=0;
				$option_status->value=true;
				return;
				}
				define("RESET_MESSAGE",self::getWarningText("Error! email address not found.","text-align:center;"));
				 
				 $option_status->name=0;
				$option_status->value=False;
				}
			}
		
		
		}
		
		if(isset($_GET['reset_token'])&&(isset($_GET['email']))){
		
		 $users=$db->getUserDetails("where email='".$_GET['email']."'");
		 $found=false;
		 for($i=0;$i<count($users);$i++){
		   $found=true;
		   if(($users[$i]->token==$_GET['reset_token'])&&(time()-$users[$i]->tokenTime<3600)){
		   
		    if(isset($_POST['account_email'])){
		   if(($_POST['new_password']==$_POST['repeat_password'])&&($_POST['new_password']!="")&&($_POST['repeat_password']!="")){
			
			 $db->updatePassword($_POST['new_password'],$users[$i]->id);
			 
			 define("RESET_MESSAGE",self::info("Password reset succeeded.","margin-top:30px;float:left;font-weight:bold;color:#60a719;")."<a  class=\"form_button\" style=\"margin-left:150px; margin-top:20px;float:left;padding:4px 5px 4px 5px;color:#ffffff;text-decoration:none;\" href=\"?\">Login</a>");
			
			
			$option_status->name=-1;
			$option_status->value=false;
			
			return;
			
			}else{
				
				 define("RESET_MESSAGE",self::info("Password missmatch.","color:#FF0000;"));
				
			}
			
			$option_status->name=-1;
			$option_status->value=true;
			
			return;
			
			}
			
			$option_status->name=-1;
			$option_status->value=true;
			
		   
		   }else{
		  
		   define("RESET_MESSAGE",self::info("Invalid or expired token!","color:#FF0000;float:left;margin-top:10px;"));
		    $option_status->name=-1;
				$option_status->value=False;
               return;
		   }
		   
		 }
		if(!$found){
			define("RESET_MESSAGE",self::info("Invalid or expired token!","color:#FF0000;float:left;margin-top:10px;"));
		    $option_status->name=-1;
				$option_status->value=False;
		}
		}
		
		
		if(!defined("RESET_MESSAGE")){
			define("RESET_MESSAGE","");
			
		}
		
	}
	public static function getMultiplePostedItems($prefix_array){
     $array=array();
	 
	 $keys=array_keys($_POST);
	 
	 for($i=0;$i<count($keys);$i++){
	 
	 for($b=0;$b<count($prefix_array);$b++){
	 
	 if(preg_match("/{$prefix_array[$b]}/i",$keys[$i])){
	    
	   $temp=new name_value;
	  
	   $temp->name=$keys[$i];
	  
	   $temp->value=$_POST[$keys[$i]];
	    
	   $array[]=$temp;	 
	  
	  }
	 
	 }
	  
	 }
	 
	 return $array;	
	
    }
	public static function genderTypeText($type){
	  
	  $gender="Male";
	  
	  if($type==1){
	  
	    $gender="Female";
	  
	  }
	  
	  return $gender; 
	  
	}
	public static function nameValueToSimpleArray($pair,$numeric_key=false){
	
		
	 $array=array();
	 
	 for($i=0;$i<count($pair);$i++){
	  if(!$numeric_key){
	  $array[$pair[$i]->name]=$pair[$i]->value;
	  }else{
	  $array[$i]=$pair[$i]->value;
	  }
	 }
	 return $array;
	
	}
	public static function swapNameValue($namevalueobject){
		
		$array=array();
		
		for($i=0;$i<count($namevalueobject);$i++)
		$array[]= new name_value($namevalueobject[$i]->value,$namevalueobject[$i]->name);
		
		return $array;
	
	}
	public static function backButton($link){
	
	return "<div id=\"tools\"><div id=\"bk_btn\"><a href=\"$link\"> </a></div></div>";
	
	}
	public static function defaultIcon($status=0){
	
	  if($status){
	  
	    return "<div id=\"default_i\"></div>";
	  
	  }
	  
	  return "";
	
	}
	public static function compareDates($date1,$date2){
	 
	  $firstDate_array=explode("/",$date1);
	  
	  $secondDate_array=explode("/",$date2);
	  
	  //validation check 1
	  
	  if((count($firstDate_array)!=3)or(count($secondDate_array)!=3)){
	  
	    return NULL;
	  
	  }
	  
	  //validation check 2
	  for($i=0;$i<count($firstDate_array);$i++){
	  
	    if(!is_numeric($firstDate_array[$i])){
		
		 return NULL;
		
		}
	  
	  }
	 
	  for($i=0;$i<count($secondDate_array);$i++){
	  
	    if(!is_numeric($secondDate_array[$i])){
		
		 return NULL;
		
		}
	  
	  }
	  
	  $yearOk=false;
	  
	  for($i=2;$i>-1;$i--){
	  
	    if(($firstDate_array[$i]<$secondDate_array[$i])&&($i==1)&&($yearOk==true)){
		
		  return false;
		
		}else{
		
		 if(($firstDate_array[$i]<$secondDate_array[$i])){
		 
		   return false;
		 
		 }else{
		 
		  if(($firstDate_array[$i]>$secondDate_array[$i]))
		  
		  return true;
		 
		 
		 }
		
		}
		
		if($i==2){
		  
		    $yearOk=true;
		  		
		}
	  
	  }
	 
	 return true;
	 
	}
	public static function userType($type){
	
	switch($type){
	 case 0:
	 return "User";
	 
	 case 1:
	  return "Admin";
	
	 case 9:
	 return "Super Admin";
	 
	}
	
	}
	
	public static function installExtension($from){
	
	$handler=new file_handler;
	
	return $handler->installExtension($from);
	
	}
	
	public static function deleteFolder($path){
	
	  $handler=new file_handler;
	  
	  return $handler->deleteFolder($path);
	
	}
	public static function createInstallationFile($path,$given_name){
	
	 $handler=new file_handler;
	 
	 
	 $handler->createInstallationFile($path,$given_name,"macro","Eric Wekesa",0,0,1,"User Home","Default page for users");
	
	}		
	public static function getPostValue($posted,$key){
	 
	 $results=NULL;
	
	 for($i=0;$i<count($posted);$i++){
	  
	   if($posted[$i]->name==$key){
	   
	   $results=$posted[$i]->value;
	   
	   break;
	   }
	  
	 } 
	 
	 return $results;
	
	}
	public static function removeFromArray($array,$value,$multiple=false){
	
	 $new_array=array();
	
	 $keys=array_keys($array);
	
	 for($i=0;$i<count($keys);$i++){
	
	  if($array[$keys[$i]]!=$value){
	  
	    $new_array[]=$array[$keys[$i]];
        
		if(!$multiple) break;
		 	  
	  }
	  
	 }
		
	return $new_array;
		
	} 
	
	public static function shared($libname,$param=""){
		
	
	$path=ROOT."library/shared/".$libname.".php";
	
	  if(file_exists($path)){
		   
		    include_once($path);
			
			return $libname($param);
		   
		 }else{
		     
			 self::warningText("Shared service error: $libname Not Found");
			 
			 return NULL;
			 
		}
	
	}
	public static function loadFromExtensions($type,$extension_name){
	
	 switch($type){
		 
		 case OPTION_MACRO:
		 
		 $path=ROOT."extensions/macros/".$extension_name."/share.php";
		 
		 if(file_exists($path)){
		   
		   include_once($path);
		   
		   return $extension_name();
		
		 }else{
		 
		  self::warningText("Shared service error: $extension_name not found or could not be shared");
		 
		 }
		 break;
		 
		 case OPTION_MODULE:
		 
		 $path=ROOT."extensions/macros/".$extension_name."/share.php";
		 
		 if(file_exists($path)){
		   
		   include_once($path);
		   
		   return $extension_name();
		
		 }else{
		 
		  self::warningText("Shared service error: $extension_name not found or could not be shared");
		 
		 }
		 break;
	 default:
	    self::warningText("Shared service error: invalid option");
	 
	 }
	
	}
    public static function successText($text,$style=""){
        
       if($text!=""){
        
         return "<div id=\"success_msg\" style=\"$style\" >".$text."</div>";
         
       }
       
    }
    public static function userInfo(){
        
        if(defined("REASON")){
            
    ?>
    
     <div class="title_mes">Alert:</div>
        
    <div class="mess_cont"><?php echo REASON; ?></div>
        
    <?php
    
        }
    }
	public static function adminPageTitle($macro_name,$title){
	?>
    <div id="system_title_bar" style="overflow:hidden;">
<img src="<?php echo "../".system::macro_path()."$macro_name/".$macro_name.".jpg"; ?>" width="40px" height="40px" style="cursor: pointer;"/><div id="main_title"><?php echo $title; ?></div></div>
    <?php
	}
	public function userPageTitle($macro_name,$title,$style="",$ext="jpg"){
	
	$add="../";
	
	if(!defined("NOT_DEFAULT")){
	  
	  $add="";
	
	}
	$umanager=new Manage_user;
	?>
    <div id="system_title_bar" style="overflow:hidden;<?php echo $style ?>">
<img src="<?php echo self::getFolderBackJump().$add.system::macro_path()."$macro_name/".$macro_name.".".$ext; ?>" width="40px" height="40px" style="cursor: pointer;"/><div id="main_title"><?php echo $title; ?></div><?php if($umanager->isLoggedIn(false)){?><div style="float:right;margin-right:5px;"><a href="?logout=1" style="color:#933;font-size:12px;" title="Log Out">Logout</a></div><?php } ?></div>
    <?php
	}
	public static function itemTitle($title){
	
     return "<div id=\"item_title\">$title</div>";
    
	}
	public static function info($inf,$style=""){
	  $mystyle=$style=="" ? "" : "style=\"$style\"";
	  return "<div class=\"mess_cont\" $mystyle >$inf</div>";
	}
    public static function macro_path(){
        return "extensions/macros/";
    }
	public static function getCheckerNumeric($id){
	
	 if(isset($_GET[$id])&&!is_numeric($_GET[$id])){
	 
	   unset($_GET[$id]);
	   
	   return 0;
	 
	 }
	 if(!isset($_GET[$id])){
	   return 0;
	 }
	 return $_GET[$id];
	}
	public static function postChecker($id,$default=0){
	 
	 if(!isset($_POST[$id])){
	   return $default;
	 }
	 return $_POST[$id];
	}
	public static function headerCheckbox($id,$target_prefix,$total){
	
	 return "<input type=\"checkbox\" id=\"$id\" onclick=\"checkUncheck('$id','$target_prefix',".$total.")\" />";
	
	}
	public static function checkbox($id,$head_checkbox,$value){
	
	 return "<input type=\"checkbox\" name=\"$id\" id=\"$id\" onclick=\"resetChecker('$head_checkbox')\"  value=\"$value\" />";
	
	}
	public static function appInnerTitle($title){
	 return "<div id=\"app_titles\">$title</div>";
	}
	public static function categoryTitle($title,$style=""){
	 
     return "<div id=\"app_titles2\" style=\"$style\">$title</div>";
     
	}
	public static function contentTitle($title,$style=""){
	return "<div id=\"app_titles3\" style=\"$style\">$title</div>";
	}
	public static function pluginType($index){

    $arr=array("General","Page Access","System Access","System Messenger");

     if(isset($arr[$index])){
      return $arr[$index];
     }
     return "Undefined";
 
     }
	 public static function moduleType($index){
	  $var="General";
	  
	  if($index==1){
	  
	    $var="Menu";
	  
	  }
	    return $var;
	 }
	public static function statusIcon($type=0,$style=""){
	
	if($type==0){
	
	return "<div id=\"disab\" style=\"$style\"></div>";
	
	}else{
	
	 return "<div id=\"enab\" style=\"$style\"></div>";
	
	}
	
	}
	public static function formatDate($date){
	
	
	 if($date==""){

       return "0000-00-00";

     }

       $arr=explode("/",$date);

       return $arr[2]."-".$arr[1]."-".$arr[0];

	}
	public function getCurrentDate($getString=false){
	
	 if($getString){
	
	 return date('j',time())." - ".date('M',time())." - ".date('Y',time());
	
	}else{
	
	 return date('j',time())."/".date('m',time())."/".date('Y',time());
	
	}
	
	}
	public static function getTimeToDbFormat($prefix){
		 
	$hour=self::postChecker($prefix."_hour");
	
	$min=self::postChecker($prefix."_minutes");
	
	if(self::postChecker($prefix."_ampm")==1)
	$hour=self::postChecker($prefix."_hour")>11 ? 00 : (12+self::postChecker($prefix."_hour"));
	
	return $hour.":".$min;
	
	}
	public static function radioStatus($radioname,$status){
	
	$input_enable=new input;
	
    $input_disable=new input;

    if($status){	
	$input_enable->setTagOptions("checked=\"checked\"");
	}else{
	$input_disable->setTagOptions("checked=\"checked\"");
	}
	
	$input_enable->input("radio","$radioname","1");
	
	$input_disable->input("radio","$radioname","0");
	
	return "<div id=\"mini_label\">Enabled</div>{$input_enable->toString()}<div id=\"mini_label\">Disabled</div>{$input_disable->toString()}";
	
	}
	public static function hardRefresh(){
	
	unset($_SESSION[self::getSessionPrefix().'menus']);
	unset($_SESSION[self::getSessionPrefix().'plugins']);
	unset($_SESSION[self::getMenuLinkSessionName()]);
	
	}
	public static function getSessionPrefix(){
	 $config=new config;
	 return $config->session_Prefix;
	}
	public static function getArrayElementValue($array,$index,$default_value="",$mask_value=""){
	
	  if(isset($array[$index])){
	   if($mask_value=="")
	   return $array[$index];
	   
	   return $mask_value;
	  
	  }
	
	  return $default_value;
	} 
	public static function getElementValuesOf($array,$index_prefix){
	
	  $thisarray=array();
	  
	  $keys=array_keys($array);
	  
	  for($i=0;$i<count($keys);$i++){
	  
	    if(preg_match("/$index_prefix/i",$keys[$i])){
		
		 $thisarray[]=$array[$keys[$i]];
		
		}
	  
	  }
	  
	  return $thisarray;
	  
	} 
public static function generateAjaxParams($target="",$object="",$parameter="",$event="onchange",$type=OPTION_MACRO,$Rand="",$index=0){

    $params=new ajaxParameter;
 
    $params->response_target=$target;
 
    $params->response_object=$object;
 
    $params->response_type=$type;
 
    $params->ajax_parameter=$parameter;
 
    if($Rand==""){
    $params->ajax_id=rand();
    }else{
	
	$params->ajax_id=$Rand;
	define("AJ_RD".$index,$Rand);
	
	}
    $params->ajax_event=$event;
 
    return $params;

}
public static function loadPageInfo(){

GLOBAL $db;
 
 $item=$db->getMenuItem($_GET['mid']);
	 
	  $macros=$db->getMacro($item->item_macroId);
	  
	  
	  if(file_exists(dirname(__FILE__)."/../../extensions/macros/".$macros->macro_name."/custom_meta.php")){
	  
	    include_once(dirname(__FILE__)."/../../extensions/macros/".$macros->macro_name."/custom_meta.php");
		
		meta_run(META);
	  
	  }

 $set=self::getSiteSettings();

 echo "<meta name=\"description\" content=\"{$set->metaDescription}\" />
 <meta name=\"keywords\" content=\"{$set->metaKeywords}\" />
 
 
 {$set->genericCode}";

 
}
public static function saveSiteSettings($data){

 file_put_contents(dirname(__FILE__)."/../../site_settings.txt",serialize($data));

}
public static function getSiteSettings(){

if(file_exists(dirname(__FILE__)."/../../site_settings.txt")){

 $cont=file_get_contents(dirname(__FILE__)."/../../site_settings.txt");
 
 $cont=unserialize($cont);
 
 $cont->metaDescription=stripslashes($cont->metaDescription);
 
 $cont->metaKeywords=stripslashes($cont->metaKeywords);
 
 $cont->genericCode=stripslashes($cont->genericCode);
 
 $cont->siteName=stripslashes($cont->siteName);
 
 return $cont;

}else{

 return new siteSettings;

}

}
	
}
class form_control{

private $validation_script;
 
private $validation_fields;

private $encription="";

private $id;

public function setValidationScript(){

 $this->valudation_script=$sc;

}
public function enableUpload(){
$this->encription="enctype=\"multipart/form-data\"";
}
public function setId($id){
	$this->id=$id;
}
public function __construct($validation_cript=""){

  $this->validation_script=$validation_cript;
   
}
public function formHead($alternateUrl="",$name=""){

return "<form id=\"{$this->id}\" action=\"$alternateUrl\" {$this->encription} method=\"post\" name=\"{$name}\" onsubmit=\"{$this->validation_script}\" style=\"float:left;width:100%;\">";

}

}
class list_control extends objectString{
//list settings
private $script_rowMouseover;
private $script_rowMouseOut;
private $color_rowAlternate;
private $headerFont_bold=false;
private $color_background="none";
private $list_width;
private $list_height;
private $list_id="";
private $column_names=array();
private $column_sizes=array();
private $items=array();
private $title="";
public function setMouseEffectFunctions($moseover="",$mouseout=""){

  $this->script_rowMouseover=$moseover;

  $this->script_rowMouseOut=$mouseout;

}
public function setListId($list_id){
$this->list_id=$list_id; 
}
public function setAlternateColor($alternate_color){
  $this->color_rowAlternate=$alternate_color;
}
public function setHeaderFontBold(){
$this->headerFont_bold=true;
}
public function setSize($width,$height){
$this->list_width=$width;
$this->list_height=$height;
}
public function addItem($item){
  $this->items[]=$item;
}
public function setColumnNames($names){
$this->column_names=$names;
}

public function setColumnSizes($column_sizes){
$this->column_sizes=$column_sizes;
}
public function setBackgroundColour($color){
 $this->color_background=$color; 
}
public function setTitle($title){
 $this->title=$title;
}
public function showList($status=false){
 $this->setMode($status);
 $this->generalTags("<script type=\"text/javascript\">");
 $this->generalTags("function mouseon{$this->list_id}(rowid){") ;
 $this->generalTags("document.getElementById(rowid).style.background=\"{$this->color_rowAlternate}\"; }");
 $this->generalTags(" function mouseout{$this->list_id}(rowid){
 document.getElementById(rowid).style.background=\"none\";   
}
 </script>");
 $this->divTagOpen("","listbox","style=\"width:{$this->list_width};\"");
 
 if($this->title!=""){
 $this->divTagOpen("list_controls","","");
 $this->divTagOpen("list_title","","");
  $this->generalTags($this->title);
 $this->closeTag();
 $this->closeTag();
 }
 //$this->divTagOpen("max_button","","title=\"Restore\"");
 //$this->closeTag();
// $this->divTagOpen("min_button","","title=\"Minimize\"");
 //$this->closeTag();
 
 $this->divTagOpen("title_bar","","style=\"width:$this->list_width;\"");
 $this->divTagOpen("title_bar_inner_l","","");
 $this->divTagOpen("title_bar_inner_r","","");
 for($i=0;$i<count($this->column_names);$i++){
 $this->divTagOpen("","cell","style=\"width:{$this->column_sizes[$i]};".$res=($i==count($this->column_names)-1) ? "border:none;\"":""."\"");
 $this->divTagOpen("inner_cont","","style=\"\"");
 $this->divTagOpen("cell_inner","",$style=($this->headerFont_bold)? "style=\"font-weight:bold;\"":"");
 $this->generalTags( $this->column_names[$i]);
 $this->closeTag(3);
}
$this->closeTag(3);
 $this->divTagOpen("list_content","","style=\"height:{$this->list_height}; background:{$this->color_background};\"");
 for($i=0;$i<count($this->items);$i++){
 $this->divTagOpen("lst{$this->list_id}$i","list_row","");
for($i2=0;$i2<count($this->items[$i]);$i2++){
$this->divTagOpen("","cell","style=\"width:{$this->column_sizes[$i2]};".$no_border=($i2==count($this->items[$i])-1)? "border:none;\"":""."\"");
$this->divTagOpen("cell_inner","","");
$this->divTagOpen("inner_cont","","style=\"\"");
$this->generalTags( $this->items[$i][$i2]);
$this->closeTag(3);
}
$this->closeTag();
}
$this->closeTag(2);
}
public function toString(){
return implode("",$this->object_buffer);
}
}
class open_table extends objectString{
private $list_width;
private $list_height;
private $hFontBold=false;
private $groupHeaders=array();
private $column_names=array();
private $column_width=array();
private $items_array=array();
private $row_array=array();
private $row_id=array();
private $table_id="id";
private $hover_color="";
private $hide_header=false;
private $editable=array();
private $numColumns=array();
private $rowDeletable=false;
private $col1="";
private $col2="";
private $ansCol1="";
private $searchCol="SearchCol=-1";
private $formularCols='var hasFormular=false;';
private $delOption="";
private $rightAlign=array();
private $printBtnId="";
private $excelBtnId="";
private $enablePrintExcel=false;
private $buttons=2;
private $asc=false;
private $cellStyle=array();
private $cellBorder=array();
public function setBorder($arr,$color,$site=0){
    $this->cellBorder['rows']=$arr;
    $this->cellBorder['side']=$site;
    $this->cellBorder['color']=$color;
}
public function setHeaderFontBold(){
 $this->hFontBold=true;	
}
public function enablePrintExport($status=false,$printId=array(),$excelBtn=array(),$buttons=2){
	$this->enablePrintExcel=$status;
	$this->printBtnId=implode('_',$printId);
	$this->excelBtnId=implode('_',$excelBtn);
	$this->buttons=$buttons;
}
public function canDeleteRow($val=false,$delOption=""){
	$this->rowDeletable=$val;
	$this->delOption='_'.$delOption;
}
public function setAsCopy($asc=false){
	$this->asc=$asc;
}
public function setRightAlign($arr=array()){
	$this->rightAlign=$arr;
}
public function setNumberColumns($val){
	$this->numColumns=$val;
}
public function setCalculator($col1,$col2,$col3){
	$this->col1=$col1;
	$this->col2=$col2;
	$this->col3=$col3;
}
public function setColumnFormular($col1,$col2,$ansCol){
	$this->formularCols='	var hasFormular=true;
	var fCol1='.$col1.'; var fCol2='.$col2.'; var fCol3='.$ansCol.';';
}
public function calculateTotalOnly($ansCol){
	$this->formularCols='var hasFormular=false; 
	var fCol3='.$ansCol.';';
}
public function setEditableColumns($val){
  $this->editable=$val;
}
public function setSearchColumn($col){
  $this->searchCol="searchCol=".$col;
}
public function setColumnWidths($width){
 $w_total=0;
 $colWidth=0;
 if($this->rowDeletable){
	for($i=0;$i<count($width);$i++)
	$w_total+=(int) str_replace('%','',$width[$i]);
	 
	$colWidth=100-$w_total-1;
	
 }
 $this->column_width=$width;
 if($this->rowDeletable)
	$this->column_width[]=$colWidth.'%';
	
}
public function setId($id){

$this->table_id=$id;

}
public function specialRow(){
}
public function setHoverColor($hover){

 $this->hover_color=$hover;

}
public function setGroupHeaders($array){

 $this->groupHeaders=$array;

}
public function setSize($width,$height){

 $this->list_width=$width;

 $this->list_height=$height;

}
public function setColumnNames($names){
  $this->column_names=$names;
  if($this->rowDeletable){	
   $this->column_names[]='delCol';
  }
}
public function addItem($item,$rowid=0,$style=array()){
	
	if($this->rowDeletable)
	  $item[]='<div class="delr" id="dl_'.$rowid.$this->delOption.'"></div>';
	
	$this->items_array[]=$item;
	
	if($rowid==0){
		$this->row_id[]='-'.count($this->row_id);
	}else{
		$this->row_id[]='-'.$rowid;
	}
	
	$this->cellStyle[]=$style;
	
}
public function addDataRow($data){
    $this->row_array[]=$data;
}
public function hideHeader($value=true){
	
	$this->hide_header=$value;
	
}
public function showTable($status=false){
 $cs='';//copy sffix
 if($this->asc)
	 $cs="2";
 if(!$this->asc){
 $this->generalTags("<script type=\"text/javascript\">");
 if($this->rowDeletable)
 $this->generalTags('var cRow="";');
 /*$this->generalTags("function mouseon{$this->table_id}(rowid){") ;
 $this->generalTags("document.getElementById(rowid).style.background=\"{$this->hover_color}\"; }function mouseout{$this->table_id}(rowid){
 document.getElementById(rowid).style.background=\"none\";   
}");*/
 $this->generalTags("".$this->formularCols.$this->searchCol."
 </script>");
 }
	//header
	$this->generalTags("<div id=\"table".$cs."_{$this->table_id}\" class='mn_tables' style=\"width:{$this->list_width};\">");
	
	if($this->enablePrintExcel){
	  $this->generalTags('<div class="thePopW"></div>');
	  if($this->buttons==2){
	    $this->generalTags('<div class="tRow"><div class="prbut" id="'.$this->printBtnId.'"></div><div class="excelBtn"></div></div>');
	  }else{
		$this->generalTags('<div class="tRow" id="'.$this->printBtnId.'"><div class="excelBtn" id="'.$this->excelBtnId.'"></div></div>');  
	  }
	}
	
	if($this->rowDeletable)
	$this->generalTags('<div class="xMenu"><div class="xMenuInner" id="abv">Insert Row Above</div>
	<div class="xMenuInner" style="border-bottom:none;" id="blw">Insert Row Below</div>
	</div>');
	
	if(!$this->hide_header){
	$this->generalTags("<div id=\"header".$cs."_row1\">");

    for($i=0;$i<count($this->groupHeaders);$i++){
	
	$border="";
	
	if(count($this->groupHeaders)-1==$i)
	  $border="border-right:none;";
	
	$fontStyle="style=\"width:{$this->column_width[$i]};font-size:14px;$border\"";
	
	if($this->hFontBold)
	$fontStyle="style=\"font-weight:bold;width:{$this->column_width[$i]};font-size:14px;$border\""; 
		
	$this->generalTags("<div class=\"cells".$cs."_top\" $fontStyle >{$this->groupHeaders[$i]}</div>");
		
   }
		
	$this->generalTags("</div>");
	
	$disp="";
		
	if($this->hide_header==true)
	$disp='style="display:none!important;"';
		
	$this->generalTags("<div id=\"header".$cs."_row\" ".$disp.">");
	
	for($i=0;$i<count($this->column_names);$i++){
	
	$border="";
	
	if(count($this->column_names)-1==$i)
	  $border="border-right:none;";
	
	 $align="left";
	
	if(in_array($i,$this->rightAlign))
		$align="right;direction:rtl";
		
	$fontStyle="style=\"text-align:$align;width:{$this->column_width[$i]};$border".$height=$this->column_names[$i]=="" ? "height:16px;":""."\"";
	
	if($this->hFontBold)
	$fontStyle="style=\"font-weight:bold;width:{$this->column_width[$i]};$border".$height=$this->column_names[$i]=="" ? "height:16px;\"":""."\""; 
	
	$ed="_No";
	
	if(in_array($i,$this->editable))
		$ed="_edit";
		
    $id="ch_".$i."_".str_replace('%','',$this->column_width[$i]).$ed;
	
	if(in_array($i,$this->numColumns))
	 $id="num_".$i."_".str_replace('%','',$this->column_width[$i]).$ed;
		
	 $colName=$this->column_names[$i];
	 
	 if($colName=='delCol'){
		$colName=' ';
		$id="delc_".$i."_".str_replace('%','',$this->column_width[$i]).'_No';
	 }
	
	 $this->generalTags('<div class="cells'.$cs.'_top" id='.$id.' '.$fontStyle.' >'.$colName.'</div>');
	
	}
	$this->generalTags("</div>");
	
	}
	
	$alternate="al1";
	
	for($i=0;$i<count($this->items_array);$i++){
		
		$idd=$this->row_id[$i];
		
		$this->generalTags("<div class=\"trow".$cs." ".$alternate."\" id=\"lst{$this->table_id}$idd\" >");
		
		for($b=0;$b<count($this->items_array[$i]);$b++){
			
			$border="";

			if(count($this->cellBorder)){
			    $border="border-right:1px solid ".$this->cellBorder['color'];
            }

			
			$id='id="cl'.$cs.'_'.$i.'_'.$b;
			
			if(in_array($b,$this->editable))
			  $id.="_edit";
			
			$id.='"';
			
			$align="left";
	
	        if(in_array($b,$this->rightAlign))
		     $align="right";

	        if(isset($this->cellBorder['cells'])) {
                if (in_array($b, $this->cellBorder['cells'])) {
                    $this->generalTags("<div class=\"cells" . $cs . "\" " . $id . " style=\"text-align:$align;width:{$this->column_width[$b]};$border;" . System::getArrayElementValue($this->cellStyle[$i], $b) . "\">{$this->items_array[$i][$b]}</div>");
                } else {
                    $this->generalTags("<div class=\"cells" . $cs . "\" " . $id . " style=\"text-align:$align;width:{$this->column_width[$b]};" . System::getArrayElementValue($this->cellStyle[$i], $b) . "\">{$this->items_array[$i][$b]}</div>");
                }
            }else{
                $this->generalTags("<div class=\"cells".$cs."\" ".$id." style=\"text-align:$align;width:{$this->column_width[$b]};".System::getArrayElementValue($this->cellStyle[$i],$b)."\">{$this->items_array[$i][$b]}</div>");

            }

		}
		
		$this->generalTags("</div>");
        
        if(isset($this->row_array[$i]))
		$this->generalTags($this->row_array[$i]);
		
		$alternate=$alternate=="al1" ? "al2":"al1";
		
	}
	$this->generalTags("</div>");
	if($status)
	echo $this->toString();
	
}
}
class macro_layout extends objectString{
public $title;
public $content;
public $tagOptions="";
public $show_title=False;
protected $width="";
public function setContent($content){
  $this->content=$content;
}
public function setTagOptions($tagoptions=""){

 $this->tagOptions=$tagoptions;

}
public function setWidth($width){
$this->width="width:".$width;
}
public function showlayout($run_paste=true){

$this->generalTags("<div id=\"comp_module\" {$this->tagOptions} ><div id=\"comp_module_top\"><div id=\"comp_module_top_l\"><div id=\"comp_module_r\"><div id=\"comp_module_top_r\"><div id=\"comp_bottom\"><div id=\"comp_bottom_l\"><div id=\"comp_bottom_r\"><div id=\"comp_innercont\" style=\"{$this->width};\">");

if($this->show_title){
 $this->generalTags("<div id=\"macro_title\" style=\"width:100%;\">{$this->title}</div>"); 
}

$this->generalTags("<div id=\"content\">{$this->content}</div></div></div></div></div></div></div></div></div></div>");

if(!$run_paste){

return implode("",$this->object_buffer);

}else{

echo implode("",$this->object_buffer);


}

}
public function toString(){

return implode("",$this->object_buffer);

}

}
class tabs_layout extends macro_layout{
    private $activeTab=0;
    private $tabitems=array();
    private $tabContent=array();
public function setActiveTab($tab){
    $this->activeTab =$tab;
 }
 public function addTab($tabname){
  $this->tabsitems[]=$tabname;  
}
public function addTabContent($content){
 $this->tabContent[]=$content;
}
private function tabs(){
    
	$this->generalTags("<div id=\"tabs_menu\" {$this->tagOptions}>");
	
  for($i=0;$i<count($this->tabsitems);$i++){
    if($i==$this->activeTab){ 
    
	$this->generalTags("<div id=\"active_tab\" class=\"inactive_tab\" onclick=\"changeTab('$i','".count($this->tabsitems)."','".$this->activeTab."')\">");
	
	 }else{ 
       $this->generalTags("<div id=\"tab$i\" class=\"inactive_tab\" onclick=\"changeTab('$i','".count($this->tabsitems)."','{$this->activeTab}')\">");
       } 
 $this->generalTags("<div class=\"inner_left\"><div class=\"inner_right\"><div class=\"active_text\">{$this->tabsitems[$i]}</div></div></div></div>");
    
}
    $this->generalTags("</div>");
    
    }
    public function showTabs($status=true){
	if($this->width==""){
	  $this->setWidth("729px");
	}    
      
      $this->generalTags("<div style=\"float:left;padding-left:10px;overflow:hidden;position:relative;z-index:1;\">");
      
      $this->tabs();
      for($i=0;$i<count($this->tabContent);$i++){
          if($this->activeTab==$i){
          $this->content.="<div id=\"active_tbc\" class=\"tabcont\">".$this->tabContent[$i]."</div>";
          }else{
            $this->content.="<div id=\"tbc$i\" class=\"tabcont\">".$this->tabContent[$i]."</div>";
         }
     }
      $this->generalTags("</div>");
	  
      $this->generalTags("<div style=\"margin-top:-4px;float:left;overflow:hidden;z-index:0;position:relative;padding:0px 5px 5px 5px;\">");
     
	 $this->showlayout($status);
      
      $this->generalTags("</div>");
     
    }
}
class name_value{
public $name;
public $value;
public $other;
public $other2;
public $other3;
public function __construct($name="",$value="",$other="",$other2="",$other3=""){

 $this->name=$name;
 
 $this->value=$value;

 $this->other=$other;

 $this->other2=$other2;
 
 $this->other3=$other3;

}

}
class ajaxParameter{
    public $response_target;
    public $ajax_id;
    public $response_function="";
    public $response_type;
    public $response_object;
    public $ajax_parameter;
    public $ajax_event;
	public $ajax_UseGTarget;
}

class input extends objectString{
    private $select_items=array();
    private $input_name;
    private $value;
    private $isAuto=false;
	private $input_class="";
	private $input_id="";
    private $input_type;
	private $innerTitle=array();
	private $selected=array();
    private $enable_ajax=false;
    private $ajax_waitcode;
	private $tagOptions="";
	private $multiple="";
	private $isDatepicker=false;
	private $dateFormat="";
	private $showProgress=false;
	private $target_element;
	private $populate_data=array();
	private $populate_dataKeys=array();
	private $skip_one=false;
	private $arrayName="";
	private $popu_child=false;
	private $poupeffect="";
	private $disabled=array();
public function __construct(){
}
public function makeDatePicker($dateFormat="",$minDate="",$maxdate=""){

  $this->isDatepicker=true;
  
  if($dateFormat!=""){
  
  $mdate="";
  
   if($minDate!=""){
   
    $mdate=",minDate:".$minDate;
   
   }
  
   if($maxdate!=""){
	 $mdate.=",maxDate:".$maxdate;
   }
  
   $this->dateFormat="{dateFormat:'".$dateFormat."'$mdate}";

  }

}
public function skipFirst($val=true){
  $this->skip_one=$val;
}
public function showAjaxProgress(){

$this->showProgress=true;

}
public function setPopUpWithIframe($id,$iframeid,$url){

$this->poupeffect="onclick=\"showWithIframe('{$id}','{$id}_wrapper','{$iframeid}','{$url}')\"";

}
public function setTargetElementData($data=array()){
 $this->populate_data=$data;
}
public function setTargetElementId($element_id=""){
  $this->target_element=$element_id;
  $this->popu_child=true;
}
public function setInnerTitle($title){

  $this->innerTitle[]=$title;
  
}
public function setMultiple(){

    $this->multiple="multiple=\"multiple\"";
	
}

public function setTagOptions($option){
  $this->tagOptions=$option;
}

public function setClass($class){
    $this->input_class=$class;
}

public function setId($id){
    $this->input_id=$id;
}

public function setSelected($value){
    $this->selected[]=$value;
}
public function setDisabled($disabled){
    $this->disabled=$disabled;
}
public function enableAjax($status=false){
    $this->enable_ajax=$status;
}
public function addAjaxWaitcode($waitcode){
    $this->ajax_waitcode=$waitcode;
}
public function selectValueType($isAuto=false){
    $this->isAuto=$isAuto;
}
public function setAmPm(){
$this->addItem(-1,"Select Period");
$this->addItem("0","AM");
$this->addItem("1","PM");
}
public function setMinuteValues(){
	for($i=1;$i<60;$i++)
	if($i<10){
    $this->addItem($i,"0".$i);
	}else{$this->addItem($i,$i);}
	$this->addItem("00","00");
}
public function setHourValues(){

for($i=1;$i<13;$i++)
if($i<10){
    $this->addItem($i,"0".$i);
	}else{$this->addItem($i,$i);}
}
public function ajaxDiv($id,$div_content,$ajaxparam){
    
    $ajx=$this->ajaxEvent($ajaxparam->ajax_event,$ajaxparam->response_type,$ajaxparam->response_object,$ajaxparam,$id,$ajaxparam->ajax_id,2,$ajaxparam->response_function);
    
    $ajax_function=$this->ajaxFunc($ajaxparam->response_target,$ajaxparam->ajax_id,$ajaxparam->response_function);
            
    $this->generalTags($ajax_function);
    
    $this->generalTags("<div id=\"$id\" $ajx {$this->tagOptions} class=\"{$this->input_class}\" {$this->poupeffect} >");
    
    $this->generalTags($div_content);
    
    $this->generalTags("</div>");
    
}
public function input($type,$input_name,$value="",$ajaxparam=NULL){
    
      if($this->enable_ajax){
         
		 
            $ajx=$this->ajaxEvent($ajaxparam->ajax_event,$ajaxparam->response_type,$ajaxparam->response_object,$ajaxparam,$input_name,$ajaxparam->ajax_id,1,$ajaxparam->response_function);
            
            $ajax_function=$this->ajaxFunc($ajaxparam->response_target,$ajaxparam->ajax_id,$ajaxparam->response_function);
            
            $this->generalTags($ajax_function);
                        
		    if($this->isDatepicker){
		 
		      $this->input_class="form_input_picker";
		 
		    }
						
            $this->generalTags("<input type=\"$type\" name=\"$input_name\" id=\"{$this->input_id}\" class=\"{$this->input_class}\" value=\"$value\" {$this->tagOptions} $ajx {$this->poupeffect} />");
         
		 
         }else{
		 
		 if($this->isDatepicker){
		 
		  $this->input_class="form_input_picker";
		 
		 }
                                  
            $this->generalTags("<input type=\"$type\" name=\"$input_name\" id=\"{$this->input_id}\" class=\"{$this->input_class}\" value=\"$value\" {$this->tagOptions} {$this->poupeffect}/>");
         
         }
       
	if($this->isDatepicker){
	
	     
     $this->generalTags("<script language=\"javascript\">");
	
     $this->generalTags("$(document).mouseover(function(){");
     
	 $this->generalTags("$('#{$this->input_id}').mouseover(");
   
     $this->generalTags("function(){");
     
     $this->generalTags("$('#{$this->input_id}').datepick({$this->dateFormat})");
   
     $this->generalTags("} ); } ) ; </script>");
         
    }
      
}
public function select($input_name,$ajaxparam=NULL,$popu_child=false){
        
		$this->arrayName=$input_name;
		
		 if($this->popu_child){
			
			$pop=array();
			 $this->generalTags("<script type=\"text/javascript\">
			 ");
			 $this->generalTags(" function {$this->arrayName}_fn(id1,id2){
				 ");
			  
			 $subst=0;
			 $this->generalTags("var {$this->arrayName}=Array();
			 ");
			 for($i=0;$i<count($this->select_items);$i++){
			  if(($this->skip_one)&&($i==0)){
			   $subst=1;
			   continue;
			  }
			   
              $tags=!$this->isAuto ? $this->select_items[$i]->value :  $i;
			   if(isset($this->populate_data[$i-$subst])){
			
			   $this->generalTags("{$this->arrayName}[".str_replace(" ","_",$tags)."]=new Array();
			   ");
			   
			   for($b=0;$b<count($this->populate_data[$i-$subst]);$b++)			   
               $this->generalTags("{$this->arrayName}[".str_replace(" ","_",$tags)."].push(\"{$this->populate_data[$i-$subst][$b]}\");");
			   
			   //$this->generalTags("[\"".implode("\",\"",$this->populate_data[$i-$subst])."\"]; ");
			   
			   }
			 
			 }
			  
			$this->generalTags(" replaceSelectItems(id1,id2,{$this->arrayName}); 
			}");
			 
			 $this->generalTags("</script>");
			 
			 $this->tagOptions.=" onChange=\"{$this->arrayName}_fn('{$this->input_id}','{$this->target_element}')\"";
			 
			}
		
        if($this->enable_ajax){
         
            $ajx=$this->ajaxEvent($ajaxparam->ajax_event,$ajaxparam->response_type,$ajaxparam->response_object,$ajaxparam,$input_name,$ajaxparam->ajax_id,0,$ajaxparam->response_function);
            
            $ajax_function=$this->ajaxFunc($ajaxparam->response_target,$ajaxparam->ajax_id,$ajaxparam->response_function);
            
            $this->generalTags($ajax_function);
            
            $this->generalTags("<select name=\"$input_name\" ".$this->multiple." id=\"{$this->input_id}\" class=\"{$this->input_class}\" {$this->tagOptions} $ajx {$this->poupeffect} >");
         
         }else{
			
		    $this->generalTags("<select name=\"$input_name\" ".$this->multiple." id=\"{$this->input_id}\" class=\"{$this->input_class}\"  {$this->tagOptions} {$this->poupeffect} >");
         
         }
		
		
        
        for($i=0;$i<count($this->select_items);$i++){
            
          $tags=!$this->isAuto ? $this->select_items[$i]->value :  $i;
         
		  $sltd="";
		  
		  if(in_array($tags,$this->selected)){
		  
		   $sltd="selected=\"selected\"";
		  
		  }
		  
		  $class="";
		  
		  if(in_array($tags,$this->innerTitle)){
		  
		  $class="class=\"innertitle\" disabled=\"disabled\" ";
		    
		  }
		  
		  $disabled="";
		  
		  if(in_array($this->select_items[$i]->value,$this->disabled))
		  $disabled="disabled style=\"font-style:italic;text-align:center;\"";
		  
          $this->generalTags("<option id='opt_".$this->select_items[$i]->value."' value=\"$tags\" $sltd $class $disabled > {$this->select_items[$i]->name}</option>");
          
        }
        
       $this->generalTags("</select>");
       
}
private function ajaxEvent($event,$type,$mod,$parameters,$this_object_id,$id=0,$restype=1,$response_function){
return "$event=\"sendData('../plugins/ajax_handler/','ain=1&typ=$type&nm=$mod&par={$parameters->ajax_parameter}','$this_object_id',$restype,perform$id,{$this->showProgress},'{$parameters->response_target}','{$response_function}')\"";

}
private function ajaxFunc($response_target,$id=0,$response_function="",$useGtarget=false){
   if($response_function==""){
  
  $G="";
  
   if($useGtarget){
   
   $G="var globalTaget$id=\"$response_target\";
   function changeGlobal$id(tar){
    globalTaget$id=tar;
   }
   ";
   
   $fn="document.getElementById(\"globalTaget$id\").innerHTML=xmlhttp.responseText;";
   
   }
   
  
   return "<script>
   
   function perform$id(par){
    
	//alert(par);
	
		if (xmlhttp.readyState==4){
         document.getElementById(target).innerHTML=xmlhttp.responseText;
      }
	  
	 
   } </script>";
   }else{
    return "<script>
   function perform$id(){
   
      if(xmlhttp.readyState<4){
        
		
				
      }
      if (xmlhttp.readyState==4){
         $response_function;
      }
   } </script>";
   }
}
public function addItem($item_value,$item_name){
    $name_val=new name_value;
   $name_val->name=$item_name;
   $name_val->value=$item_value;
   $this->select_items[]=$name_val;
}
public function addItems($items,$simple_array=false){
	
if(!$simple_array){
 for($i=0;$i<count($items);$i++)
  $this->select_items[]=$items[$i];
}else{

 $keys=array_keys($items);
 
 for($i=0;$i<count($keys);$i++) 
  $this->select_items[]=new name_value($items[$keys[$i]],$keys[$i]);
	
}

}
public function resetItems(){
        $this->select_items=array();
        $this->object_buffer=array();
}
public function toString(){
    return implode("",$this->object_buffer);
    }
}
class system_messages{
public $message_id;
public $message_title;
public $message_content;
public $message_actionLink;
public $message_targetPerson;
public $message_sentById=0;
public $message_byName;
public $message_status;
public $message_actionDate;
public function getMyMessages($id,$show_all=false){
GLOBAL $db;

$added=" and status=0";

if($show_all)
$added="";

$results=$db->selectQuery(array("id","message_title","message","action_link","target_person","sent_byid","sent_byname","status","DATE_FORMAT(actiondate,'%d-%b-%Y')"),"system_messages","where target_person=".$id.$added);
 
 $res=array();
 
 while($row=mysqli_fetch_row($results)){

  $this->message_id=$row[0];
  $this->message_title=$row[1];
  $this->message_content=$row[2];
  $this->message_actionLink=$row[3];
  $this->message_targetPerson=$row[4];
  $this->message_setById=$row[5];
  $this->message_byName=$row[6];
  $this->message_status=$row[7];
  $this->message_actionDate=$row[8];
  $res[]=$this;
 }
 
 return $res;
 
}
public function markAsRead(){
GLOBAL $db;
$db->updateQuery(array("status=1"),"system_messages","where id=".System::getCheckerNumeric("@M"));
}
public function postMessage(){
 GLOBAL $db;

 $db->insertQuery(array("message_title","message","action_link","target_person","sent_byid","sent_byname","status","actiondate"),"system_messages",array("'".$this->message_title."'","'".$this->message_content."'","'".$this->message_actionLink."&@M='",$this->message_targetPerson,$this->message_sentById,"'".$this->message_byName."'",0,"CURRENT_DATE()"));

}
}
class imageSession{
	public $image_name;
	public $image_alias;
	public $image_description;
	public $image_path;
	public $image_view;
	public $image_sTime;
}
class pageDetails{
	public $page_minid;
	public $page_maxid;
	public $page_layout;
}
class siteSettings{
	public $siteName;
	public $metaDescription;
	public $metaKeywords;
	public $genericCode;
}
?>