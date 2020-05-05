<?php

class file_handler{

public function createFile($file_path,$content){

 file_put_contents($file_path,$content)or(System::warningText("Could not create file"));

}

public function deleteFolder($path){
 
 $state=true;
 
  if((!is_dir($path))||(!is_writable($path))){
     return false;
  }

  $content=scandir($path);
  
  $filtered_content=$this->filterFolderContent($content);
  
  if(count($filtered_content)>0){
 
     for($i=0;$i<count($filtered_content);$i++){
	 
	   if(is_file($path."/".$filtered_content[$i])){
	   
	      unlink($path."/".$filtered_content[$i]);
	      
	   }else{
	   
	    $state=$this->deleteFolder($path."/".$filtered_content[$i]);
	   
	   }
	 
	 }
  
  }
  
  if($state){
    rmdir($path);
	}//echo "hello2";
  return $state;
}
private function filterFolderContent($array_content){
 $filtered=array();
 
 for($i=0;$i<count($array_content);$i++){
 
   if(($array_content[$i]!=".")&&($array_content[$i]!="..")&&($array_content[$i]!="...")){
   
    $filtered[]=$array_content[$i];
   
   }
 
 }
 
 return $filtered;

}
public function installExtension($path_toistFile=""){
 GLOBAL $db;
  
 $tree=$this->getInstallInfo($path_toistFile);
 
 switch($tree->install_extensionType){
 
 case "module":
 
 $files=$tree->install_files;
 
 //module exists?
 $exists=false;
 $res=$db->selectQuery(array("id"),"modules","where modulename='{$tree->install_extensionName}'");
 while($rw=mysqli_fetch_row($res)){
  $exists=true;
 }
 
 if(!$exists){
 
   for($i=0;$i<count($files);$i++){
 
   $this->makeDirTree(ROOT."extensions/modules/",$files[$i]->filepath);
 
    file_put_contents(ROOT."extensions/modules/{$files[$i]->filepath}",$files[$i]->content);
 
  }
 
 $db->insertQuery(array("module_title","modulename","for_super","isMenu"),"modules",array("'".$tree->install_extensionName."'","'".$tree->install_extensionName."'",$tree->install_forsuper,$tree->install_intype));
 
 return System::successText("Module '".$tree->install_extensionName."' installed successfully.");
 
 }else{
 
 return System::getWarningText("Module '".$tree->install_extensionName."' Failed to install.");
 
 }
 
 break;
 
 case "macro":
 
 $files=$tree->install_files;
 
  $exists=false;
 $res=$db->selectQuery(array("id"),"macros","where macroname='{$tree->install_extensionName}'");
 while($rw=mysqli_fetch_row($res)){
  $exists=true;
 }
 
 if(!$exists){
 
   for($i=0;$i<count($files);$i++){
 
   $this->makeDirTree(ROOT."extensions/macros/",$files[$i]->filepath);
 
    file_put_contents(ROOT."extensions/macros/{$files[$i]->filepath}",$files[$i]->content);
 
  }
 
 $db->insertQuery(array("macro_title","macroname","for_super","macro_category","description"),"macros",array("'".$tree->install_extensionName."'","'".$tree->install_extensionName."'",$tree->install_forsuper,$tree->install_intype,"'".$tree->install_extensionDescription."'"));
 
 $macro_id=0;
 
 $res=$db->selectQuery(array("max(id)"),"macros");
 
 while($row=mysqli_fetch_row($res)){
 
   $macro_id=$row[0];
 
 }
 
 if($tree->install_forsuper==1){
 
   $mid=0;
   
   $rs=$db->selectQuery(array("max(id)"),"menu_items");

   while($rw=mysqli_fetch_row($rs)){
   
     $mid=$rw[0];
   
   }
   
 $db->insertQuery(array("menu_id","item_title","status","macro_id","link","parent_id"),"menu_items",array("2","'".$tree->install_extensionTitle."'",1,$macro_id,"'?mid=".($mid+1)."'","4"));
   
    
 }
 
 return System::successText("Macro '".$tree->install_extensionName."' installed successfully.");
 
 }else{
 
 return System::getWarningText("Macro '".$tree->install_extensionName."' failed to install.");
 
 }
 
 break;
 
 case "plugin":
 
  $files=$tree->install_files;
 
  $exists=false;
 $res=$db->selectQuery(array("id"),"plugins","where plugin_name='{$tree->install_extensionName}'");
 while($rw=mysqli_fetch_row($res)){
  $exists=true;
 }
 
 if(!$exists){
 
   for($i=0;$i<count($files);$i++){
 
   $this->makeDirTree(ROOT."plugins/",$files[$i]->filepath);
 
    file_put_contents(ROOT."plugins/{$files[$i]->filepath}",$files[$i]->content);
 
  }
 
 $db->insertQuery(array("plugin_name","plugin_type"),"plugins",array("'".$tree->install_extensionName."'",$tree->install_intype));
 
 return System::successText("Plugin '".$tree->install_extensionName."' installed successfully.");
 
 }else{
 
 return System::getWarningText("Plugin '".$tree->install_extensionName."' failed to install.");
 
 }
 
 break;
 
 default:
 return System::getWarningText("Failed:Invalid file format");
 
 }
 

}
private function makeDirTree($path,$extension_path){

$dir=explode("/",$extension_path);
 
 array_pop($dir);
 
 $inner="";
 
 for($i=0;$i<count($dir);$i++){
 
 if($i==0){ 
 $inner.=$dir[$i];
 }else{ $inner.="/".$dir[$i]; }
 
  if(!is_dir($path.$inner)){
  
    mkdir($path.$inner);
  
  }
  
 }
 
}
private function getInstallInfo($path_toistFile){

$inst=new installerTree;

$content=file_get_contents($path_toistFile);

$inst->install_developer=$this->getTagContent("devinfo",$content);

$inst->install_extensionName=$this->getTagContent("extname",$content);

$inst->install_extensionType=$this->getTagContent("type",$content);

$inst->install_version=$this->getTagContent("vers",$content);

$inst->install_forsuper=$this->getTagContent("forsup",$content);

$inst->install_intype=$this->getTagContent("intype",$content);

$inst->install_extensionTitle=$this->getTagContent("extTitle",$content);

$inst->install_extensionDescription=$this->getTagContent("desc",$content);

$inst->install_files=$this->getFilesToInstall($this->getTagContent("files",$content));

return $inst;

}
private function getFilesToInstall($content){

$files=array();

$contents=$this->getMultipleTagsContents("ifn",$content);

for($i=0;$i<count($contents);$i++){

  $fle=new file_tree;
  
  $fle->filepath=$this->getTagContent("path",$contents[$i]);
  
  $fle->content=$this->getTagContent("content",$contents[$i]);
  
  $files[]=$fle;

}
return $files;
}
public function createInstallationFile($path,$given_name,$type="plugin",$devInfo="Wekesa",$for_super=0,$intype=1,$version=1,$title="title",$desc=""){
 $content="<extension>
  
  <devinfo>$devInfo</devinfo>
  
   <type>$type</type>
   
   <intype>$intype</intype>
   
   <forsup>$for_super</forsup>
   
   <extTitle>$title</extTitle>
   
   <desc>$desc</desc>
   
   <vers>$version<vers>
   
   <extname>$given_name</extname>
   
   <files>";
  
  $array=$this->getFiles($path,$given_name);
  
  for($i=0;$i<count($array);$i++){
   $content.=" 
	<ifn>
	 
	 <path>".$array[$i]->filepath."</path>
	 
	 <content>".$array[$i]->content."</content>
	
	</ifn>";
	
	}
   
  $content.= "</files>
  
  </extension>";
  
  file_put_contents($path."/$given_name".".inst",$content);
 
}
public function getFiles($path,$root_folder){
    
  $files=array();
  
  $arr=scandir($path);
  
  $filtered=$this->filterFolderContent($arr);
  
  
  for($i=0;$i<count($filtered);$i++){
   
    if(is_file($path."/".$filtered[$i])){
	
	  $files[]=$this->getFileTag($path,$filtered[$i],$root_folder);
	
	}else{
	
	    $rr=$this->getFiles($path."/".$filtered[$i],$root_folder."/".$filtered[$i]);
		
	 	$files=array_merge($files,$rr);
	
	}
  
  }
  
  return $files;

}

private function getFileTag($path,$filename,$root_folder){

  $tree=new file_tree;
  
  $tree->filepath=$root_folder."/".$filename;
  
  $tree->content=file_get_contents($path."/".$filename);
  
  
  return $tree;

}

public function getTagContent($tag,$content){

 $starting=strpos($content,"<".$tag.">")+strlen($tag)+2;

 $length=strpos($content,"</".$tag.">")-$starting;
  
 return substr($content,$starting,$length);

}
public function getMultipleTagsContents($tag,$content){
$array=array();

$cont=$content;


 while(preg_match("/<$tag>/i",$cont)){
 
  $temp_cont=$this->getTagContent($tag,$cont);
 
  $array[]=$temp_cont;
  
  $cont=substr($cont,strpos($cont,"</$tag>")+strlen($tag)+3);
 
 }
 return $array;
}

}
class file_tree{

public $filepath;

public $content;

}
class installerTree{
public $install_forsuper;
public $install_version;
public $install_developer;
public $install_intype;
public $install_extensionName;
public $install_extensionTitle;
public $install_extensionDescription;
public $install_extensionType;
public $install_files;
}

?>