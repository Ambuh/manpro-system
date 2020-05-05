<?php
class database{
public $dbprefix;
private $session_prefix;
private $table_prefix="";
private $con;
private $config;
public function __construct(){
   GLOBAL $config;
   $this->config=$config;
   $this->openConnection();
   $this->session_prefix=$this->config->session_Prefix;
}
public function setTablePrefix($prefix=""){
   $this->table_prefix=$prefix;
}
public function openConnection(){
 
$count =0;

$this->dbprefix=$this->config->db_prefix;

while(!$this->con=mysqli_connect($this->config->db_host,$this->config->db_user,$this->config->db_password)){
 
if($count>2000){
 
 echo "Could not connect to the database";
 
 exit;
 
}

$count++;

}
 
mysqli_select_db($this->con,$this->config->db_name);
 
}
public function generalQuery($query=""){
    $res=mysqli_query($this->con,$query);
    echo mysqli_error($this->con);
    return $res;
}
public function selectQuery($fields,$tablename,$whereclause="",$prefix=-1,$echo_cs=true){

//$this->openConnection();

$tablePref=$this->table_prefix;
	
if($prefix!=-1)
  $tablePref=$prefix;
	
$table_fields=implode(",",$fields);

	$mysqli_query="select ".$table_fields." from ".$tablePref.$tablename." ".$whereclause;
	
	
	
$resource= mysqli_query($this->con,$mysqli_query) or die(mysqli_error($this->con));

//mysqli_close($this->con);

return $resource;

}
public function selectQueryCustom($fields,$tablename,$whereclause=""){

//$this->openConnection();
	
$table_fields=implode(",",$fields);

$resource= mysqli_query($this->con,"select ".$table_fields." from ".$tablename." ".$whereclause) or die(mysqli_error($this->con));

//mysqli_close($this->con);

return $resource;

}
	
public function selectQueryJoin($fields,$tables_array,$whereclause="",$prefix=-1){

//$this->openConnection();
	
$tablePref=$this->table_prefix;
	
if($prefix!=-1)
  $tablePref=$prefix;

$table_fields=implode(",",$fields);
 
  $arr=array();

  foreach($tables_array as $tb)
     $arr[]=$tablePref.$tb;

$tables=implode(",",$arr);

$resource= mysqli_query($this->con,"select ".$table_fields." from ".$tables." ".$whereclause) or die(mysqli_error($this->con));

    return $resource;

}
public function updateQuery($fields_values,$tablename,$whereclause="",$prefix=-1){
//$this->openConnection();

$table_fields=implode(",",$fields_values);

$tablePref=$this->table_prefix;
	
if($prefix!=-1)
  $tablePref=$prefix;
//echo "update ".$this->dbprefix.$tablename." set ".$table_fields." ".$whereclause;
	
 $resource= mysqli_query($this->con,"update ".$tablePref.$tablename." set ".$table_fields." ".$whereclause) or die(mysqli_error($this->con));

	return $resource;
//mysqli_close();
}
public function deleteQuery($tablename,$whereclause="where id =0 ",$prefix=-1){

//$this->openConnection();

//$table_fields=implode(",",$fields_values);

$tablePref=$this->table_prefix;
	
if($prefix!=-1)
  $tablePref=$prefix;

$resource= mysqli_query($this->con,"delete from ".$tablePref.$tablename." ".$whereclause) or die(mysqli_error($this->con));

//mysqli_close($this->con);
  
}
public function selectUnionQuery($forTable1,$forTable2,$table1,$table2,$whereclause=''){
	  
	  $table_fields1=implode(",",$forTable1);
	  
	  $table_fields2=implode(",",$forTable2);
	  
	  $mysqli_query="select ".$table_fields1." from ".$table1." union all select ".$table_fields2." from ".$table2."  where ".$whereclause;
	  
	  $resource=mysqli_query($this->con,$mysqli_query) or die (mysqli_error($this->con));
	  
	  return $resource;
 }
	
public function getChildItemsArray($items,$level=1){
$array=array();
 for($i=0;$i<count($items);$i++){
   
   $items[$i]->item_title=$this->getLevel($level).$items[$i]->item_title;
   
   $array[]=$items[$i];
    
	if($items[$i]->item_hasChild){
	
	  $ar=$this->getChildItemsArray($items[$i]->item_children,$level+1);
	 
	 for($b=0;$b<count($ar);$b++){
	 
	  $array[]=$ar[$b];
	  
	  }
	}
	
 }
 return $array;
}
private function getLevel($lev){

$d="";

for($i=0;$i<$lev;$i++){
  $d.=" - ";
}

return $d;

}

public function insertQuery($fields,$tablename,$values,$debugString=false,$prefix=-1){
//$this->openConnection();

$table_fields=implode(",",$fields);

$field_values=implode(",",$values);
	
$tablePref=$this->table_prefix;
	
if($prefix!=-1)
  $tablePref=$prefix;

if($debugString){
 echo "insert into ".$tablePref.$tablename."($table_fields)values(".$field_values.") ";
}
	
$resource= mysqli_query($this->con,"insert into ".$tablePref.$tablename."($table_fields)values(".$field_values.") ") or die(mysqli_error($this->con));

echo mysqli_error($this->con);
	
return mysqli_insert_id($this->con);
//mysqli_close($this->con);
}
public function insertQueryCustom($query){
	
$resource= mysqli_query($this->con,$query) or die(mysqli_error($this->con));

echo mysqli_error($this->con);
	
return mysqli_insert_id($this->con);
//mysqli_close($this->con);
}
public function alterQuery($tablename,$command){

$this->openConnection();

$resource= mysqli_query("alter table ".$this->table_prefix.$tablename." ".$command) or die(mysqli_error());

//mysqli_close($this->con);
}

public function getTemplates($whereclause=""){
 
 $temps= array();
 
 $resource=$this->selectQuery(array("id","template_name","selected","author"),"templates",$whereclause,'');
 
 while($row=mysqli_fetch_row($resource)){
 
  $temp=new template;
  
  $temp->template_id=$row[0];
  
  $temp->template_name=$row[1];
  
  $temp->template_status=$row[2];
  
  $temp->template_author=$row[3];
  
  $temps[]=$temp;
 
 }
 return $temps;
}
public function getModules($whereclause){
  $temps = array();
  $resource=$this->selectQuery(array("id","module_title","modulename","status","position","show_title","ismenu","status_mode","for_super","module_suffix","menu_assign","accesslevel"),"modules",$whereclause,'');
  
 while($row=mysqli_fetch_row($resource)){
  
   $temp=new module;
  
   $temp->module_id=$row[0];
  
   $temp->module_title=$row[1];
  
   $temp->module_name=$row[2];
  
   $temp->module_status=$row[3];
  
   $temp->module_position=$row[4];
  
   $temp->module_show_title=$row[5];
  
   $temp->module_is_menu=$row[6];
  
   $temp->module_status_mode=$row[7];
  
   $temp->module_for_super=$row[8];
   
   $temp->module_suffix=$row[9];
   
   $temp->module_menuassign=$row[10];
   
   $temp->module_accesslevel=$row[11];
   
   $temps[]=$temp;
 }
 return $temps;
}
public function getmacro($byid,$is_default=0,$frs=0){
 
$resource;

if($is_default==0){
 
$resource=$this->selectQuery(array("id","macroname","status","for_super","mode_status","macro_title"),"macros"," where id=".$byid." and for_super=".$frs." and status=1",'');

}else{
 
 $resource=$this->selectQuery(array("id","macroname","status","for_super","mode_status","macro_title"),"macros"," where for_super=".$frs." and is_default=".$is_default." and status=1",'');

}

$temp=NULL;

while($row=mysqli_fetch_row($resource)){

 $temp=new macro;
 
 $temp->macro_id=$row[0];
 
 $temp->macro_name=$row[1];
 
 $temp->macro_status=$row[2];
 
 $temp->macro_for_super=$row[3];
 
 $temp->macro_mod_status=$row[4];
 
 $temp->macro_title=$row[5];
 
}

return $temp;
}
public function getmacros($addwhere=" where show_to_admin=1"){

 $resource=$this->selectQuery(array("id","macroname","status","for_super","mode_status","macro_title","macro_category","description","restricted"),"macros","".$addwhere,'');

 $macros=array();

 while($row=mysqli_fetch_row($resource)){
 
  $temp=new macro;
  
  $temp->macro_id=$row[0];
 
  $temp->macro_name=$row[1];
 
  $temp->macro_status=$row[2];
 
  $temp->macro_for_super=$row[3];
 
  $temp->macro_mod_status=$row[4];
 
  $temp->macro_title=$row[5];
  
  $temp->macro_category=$row[6];
  
  $temp->macro_description=$row[7];
  
  $temp->macro_isRestricted=$row[8];
  
  $macros[]=$temp;
  
 }

 return $macros;

}
public function getPlugin($id){

$plugins=$this->getPlugins(" where id=".$id);
 
 if($plugins!=NULL){
 
  return $plugins[0];
  
 }
 
 return $plugins;
 
}
public function menuIsEnabled($id){
$status=false;


$resource=$this->selectQuery(array("id"),"modules","where status=1 and id=$id",'');

while(mysqli_fetch_row($resource)){
  $status=true;
}

return $status;

}
public function getPlugins($whereclause){

$temp =array();

$resource=$this->selectQuery(array("id","plugin_name","status","plugin_type"),"plugins",$whereclause);

while($row=mysqli_fetch_row($resource)){

$tmp=new plugin;

$tmp->plugin_id=$row[0];

$tmp->plugin_name=$row[1];

$tmp->plugin_status=$row[2];

$tmp->plugin_type=$row[3]; 

$temp[]=$tmp;

} 

if(count($temp)==0): $temp=NULL; endif;

return $temp;
 
}
public function getPluginByType($plugin_array,$type,$collection=false){
  
  $plugins=NULL;
  
  $temp=array();
  
  for($i=0;$i<count($plugin_array);$i++){
  
    if($plugin_array[$i]->plugin_type==$type){
	
	if(!$collection){
	 
	  return $plugin_array[$i];
	 
	 }else{
	 
	  $temp[]=$plugin_array[$i];
	 
	 }
	 
	}
  }
  
  if(count($temp)>0){
  return $temp;
  }
  
  return NULL;
}

public function getMenuItem($id){

$resource=$this->selectQuery(array("id","menu_id","item_title","modules","status","link","macro_id"),"menu_items"," where id=".$id,'');

$item=NULL;

while($row=mysqli_fetch_row($resource)){
 $item=new menu_items;
 $item->item_id=$row[0];
 $item->item_menuid=$row[1];
 $item->item_title=$row[2];
 $item->item_modules=$row[3];
 $item->item_status=$row[4];
 $item->item_link=$row[5];
 $item->item_macroId=$row[6];
}

return $item;

}

public function getMenuLinkBymacroId($array_of_id,$mid=0){

$resource=$this->selectQuery(array("link","macro_id"),"menu_items","where menu_id=".$mid." and macro_id=".implode(" or menu_id=".$mid."  and macro_id=",$array_of_id));

$rw=array();

while($row=mysqli_fetch_row($resource)){
  $rw[$row[1]]=$row[0];
}
return $rw;

}
public function hasAccessToPage($userlevel,$pageaccess){

   if(($userlevel>=$pageaccess)||($pageaccess==2)){
          return true;
	 
   }
  
return false;

}
public function addAccessToPage($level,$menu_item){
  if($menu_item->item_accesslevel!=""){
  
    $array=unserialize($menu_item->item_accesslevel);
	
	if(!in_array($level,$array)){
	 
	 $array[]=$level;
	 
	 $this->updateQuery(array("accesslevel=".serialize($array),"hasrestrictions=1"),"menu_items"," where id=".$menu_item->item_id,'');
	 
	}
  
  }else{
    
	$alevel=serialize(array($level));
	
	$this->updateQuery(array("accesslevel=".$alevel,"hasrestrictions=1"),"menu_items"," where id=".$menu_item->item_id,'');
	  
  }
}
public function removeAccessToPage($level,$menu_item){

if($menu_item->item_accesslevel!=""){

$new_array=array();

$arr=unserialize($menu_item->item_accesslevel);

for($i=0;$i<count($arr);$i++){
 
 if($arr[$i]!=$level){

   $new_array[]=$arr[$i];

 }
 
}

$restrict=1;

if(count($menu_item)==0){

 $restrict=0;

}

$this->updateQuery(array("accesslevel=".serialize($new_array),"hasrestrictions=".$restrict),"menu_items"," where id=".$menu_item->item_id,'');


}else{

$this->updateQuery(array("accesslevel=".serialize(array()),"hasrestrictions=0"),"menu_items"," where id=".$menu_item->item_id,'');

}

}	
public function addMenuToSession($mymenu){

 if(isset($_SESSION[$this->session_prefix.'menus']))
 $menus = unserialize($_SESSION[$this->session_prefix.'menus']);
 
 $menus[]=$mymenu;
 
 $_SESSION[$this->session_prefix.'menus']=serialize($menus);
 
}
public function removeMenuFromSession($menu_id){

if(defined("OPEN_PAGE"))
return;

if($this->menuIsInSession($menu_id)){

 $menus=unserialize($_SESSION[$this->session_prefix.'menus']);
 
 $temp=array();
 
 for($i=0;$i<count($menus);$i++){
if(!isset($menus[$i]))
 continue; 
   if($menus[$i][0]->item_menuid!=$menu_id){
   
      $temp[$i]=$menus[$i];
   
   }
 
 }
 
 $_SESSION[$this->session_prefix.'menus']=serialize($temp);
 
}

}
public function getUserSession(){

 if(isset($_SESSION[$this->session_prefix.'user_session'])){
 
 return unserialize($_SESSION[$this->session_prefix.'user_session']);
 
 }else{
	  $temp=new User_Session;
        $temp->username="Guest";
        $temp->id="0";
        $temp->parent_account=PARENT;
        $temp->user_type=-1;
        $temp->user_status=1;
        $temp->parent_id=PARENT;
        $temp->firstname="Guest";
        $temp->secondname="Guest";
        $temp->lastname="Guest";
        $temp->cellphone="";
        $temp->gender=-1;
        $temp->profile_image="";
        $temp->email_address="";

        return $temp;
 }
 
}
public function updateUserSession($session_details){

$_SESSION[$this->session_prefix.'user_session']=serialize($session_details);

}
public function updatePassword($password,$id){

 $this->updateQuery(array("password='".$this->hashPassword($password)."'","reset_token=".time()),"users","where id=".$id);

}
public function hashPassword($password){

 return substr(sha1($password),0,15).":".substr(md5($password),3);

}

public function menuIsInSession($menu_id,$type=false){

if(!isset($_SESSION[$this->session_prefix.'menus'])){
  $_SESSION[$this->session_prefix.'menus']=serialize(array());
  return false;
}else{
  
  $menus=unserialize($_SESSION[$this->session_prefix.'menus']);
  
  for($i=0;$i<count($menus);$i++){
  if(!isset($menus[$i]))
  continue;
  if(count($menus[$i])>0){
   
   
   if($menus[$i][0]->item_menuid==$menu_id){
   
    if($menus[$i][0]->item_isall==$type){
      
	   return true;
	  
    }
   
   }
   
  }
  
  }

}

//$this->removeMenuFromSession($menu_id);

return false;
}
public function getSessionMenuItems($menu_id){
 
 $menus=unserialize($_SESSION[$this->session_prefix.'menus']);
 
 
 for($i=0;$i<count($menus);$i++){
 if(!isset($menus[$i]))
 continue;
 if(count($menus[$i])>0){
     
   if($menus[$i][0]->item_menuid==$menu_id){
	  
	 return $menus[$i];
	 
   }
  }
 }
 return NULL;
}
public function getMenuItemFromDb($id,$any=false){

$myLinks=array();

if(!defined("ADMIN_ROOT")){
	if(isset($_SESSION[System::getMenuLinkSessionName()])){
	   $myLinks=unserialize($_SESSION[System::getMenuLinkSessionName()]);
	}
}


$us=new Manage_User;

$mitem=NULL;

$status="and status=1";

if($any==true){
  $status="";
}


$option=" and for_super=0";

if(defined("ADMIN_ROOT")){
 if(!$any){
   $option=" and for_super=1";
 }
}
$resource= $this->selectQuery(array("id","menu_id","item_title","modules","status","link","macro_id","accesslevel","hasrestrictions","parent_id","for_super","user_default","alias","display_options"),"menu_items","where id=$id $status $option ",'');


while($row=mysqli_fetch_row($resource)){
	
 $item=new menu_items;
 $item->item_id=$row[0];
 $item->item_menuid=$row[1];
 $item->item_title=$row[2];
 $item->item_modules=$row[3];
 $item->item_status=$row[4];
 $item->item_link=$row[5];
 $item->item_rawLink=$row[5];
 $item->item_alias=$row[12];
 $item->item_macroId=$row[6];
 $item->item_parentid=$row[9];
 $item->item_displayOption=$row[13];
// $item->item_children= $this->getMenuItems($menu_id,$all,$level+1,$item->item_id);
 if(count($item->item_children)>0){
  $item->item_hasChild=true;
 }
 $item->item_accesslevel=$row[7];
 $item->item_hasrestriction=$row[8];
 $item->item_forsuper=$row[10];
 $item->item_isdefault=$row[11];
 
 if(!defined("ADMIN_ROOT")){
  
  if(!isset($myLinks[$item->item_alias])){ 
 
   $myLinks[$item->item_alias]=$item->item_rawLink;
 
   }
 }
 
 if(($item->item_accesslevel!=2)){
if($us->isLoggedIn()||(($item->item_accesslevel==2)&&(isset($user->user_type)))) {

 $user=$this->getUserSession();



 if(($user->user_type>=$item->item_accesslevel)){

  $mitem=$item;
 
 }else{ $mitem=NULL;  }

if($any==true){$mitem=$item;}



}
}else{
	if(($item->item_accesslevel==2)){
	$mitem=$item;
	
	}
	
}
}
$_SESSION[System::getMenuLinkSessionName()]=serialize($myLinks);
 return $mitem;



}

public function getMenuItemById($id,$fromdb=false,$all=false){

  $return_val=NULL;
  
  if(isset($_SESSION[$this->session_prefix.'menus'])&&(!$fromdb)){
   
    $menus=unserialize($_SESSION[$this->session_prefix.'menus']);
	
	
	for($i=0;$i<count($menus);$i++){
	
	if(count($menus[$i])>0){
	
	 $items=$this->getMenuItems($menus[$i][0]->item_menuid,$all);
	 
	  for($i2=0;$i2<count($items);$i2++){
	 
	     if($items[$i2]->item_id==$id){
		  
		  return $items[$i2];
		
		}else{
		 
		 if($items[$i2]->item_hasChild){
		   		   $return_val=$this->lookInChild($items[$i2]->item_children,$id);
				   if($return_val!=NULL){
				    break;
				   }
				   
				 
		  }
		}
	 }
	 }
	}
  
  }else{
  
     $menus=$this->getMenuItems(0,$all,0,0,true);
	 
	 for($i=0;$i<count($menus);$i++){
	   
	   if($menus[$i]->item_id==$id){
	    
	    return $menus[$i];
	   
	   }else{
	   
	     if($menus[$i]->item_hasChild){
		 
		  		   $return_val =$this->lookInChild($menus[$i]->item_children,$id);
				   if($return_val!=NULL){
				     break;
				   }
		  }
	   
	   }
	   
	 }
  
  }
  
  //if(($return_val!=NULL)&&($fromdb==false)){
  
    // $return_val=$this->getMenuItemById($id,true);
	
  // }
  
  return $return_val;
}
private function lookInChild($child,$id){

$return_val=NULL;

for($i=0;$i<count($child);$i++){
  
  if($child[$i]->item_id==$id){
    
	 return $child[$i];
	
  }else{
    
	if($child[$i]->item_hasChild){
	
	 $return_val=$this->lookInChild($child[$i]->item_children,$id);
	 
	 if($return_val!=NULL){
	 break;
	 }
	 
	}
	
  }
  
}

return $return_val;

}
public function getDefaultMenuItem(){
	
$myL=array();

$us=new Manage_User;

$mitem=NULL;

$option=" and for_super=0";

if(defined("ADMIN_ROOT")){

$option=" and for_super=1";

}
$resource= $this->selectQuery(array("id","menu_id","item_title","modules","status","link","macro_id","accesslevel","hasrestrictions","parent_id","for_super","user_default","alias","display_options"),"menu_items","where user_default=1 and status=1 $option ",'');
 
while($row=mysqli_fetch_row($resource)){
 $item=new menu_items;
 $item->item_id=$row[0];
 $item->item_menuid=$row[1];
 $item->item_title=$row[2];
 $item->item_modules=$row[3];
 $item->item_status=$row[4];
 $item->item_link=$row[5];
 $item->item_macroId=$row[6];
 $item->item_parentid=$row[9];
// $item->item_children= $this->getMenuItems($menu_id,$all,$level+1,$item->item_id);
  $item->item_children=array();
 if(count($item->item_children)>0){
  $item->item_hasChild=true;
 }
 $item->item_accesslevel=$row[7];
 $item->item_hasrestriction=$row[8];
 $item->item_forsuper=$row[10];
 $item->item_isdefault=$row[11]; 
 $item->item_alias=$row[12];
 $item->item_displayOption=$row[13];
 
 $item->item_isall=false;

if($us->isLoggedIn()) {

 $user=$this->getUserSession();

 if($user->user_type>=$item->item_accesslevel){
 
 $mitem=$item;
 
 }else{ $mitem=NULL; }

}else{
  if(!defined("ADMIN_ROOT"))
  $mitem=$item;
  //echo "hello";
}

}
//$_SESSION[System::getMenuLinkSessionName()]=serialize($myLinks);

 return $mitem;

}
public function isLogged(){

$us=new Manage_User;

return $us->isLoggedIn();

}
public function getMenuItems($menu_id,$all=false,$level=0,$parent_id=0,$all_menus=false){

$myL=array();


if(!defined("OPEN_PAGE")&(defined("ADMIN_ROOT")))
if($this->menuIsInSession($menu_id,$all)){
  return $this->getSessionMenuItems($menu_id);
 
}

$cn=new config;

$this->removeMenuFromSession($menu_id);
$acc="";

if(defined("OPEN_PAGE"))
$acc=" and accesslevel=2";

$where=" where status=1 $acc and menu_id=".$menu_id." and parent_id=".$parent_id." order by item_position asc";

$items=array();

if ($all){

$where=" where menu_id=".$menu_id." and parent_id=".$parent_id;

}

if($all_menus){
$where=" where status=1";
}

$resource= $this->selectQuery(array("id","menu_id","item_title","modules","status","link","macro_id","accesslevel","hasrestrictions","parent_id","for_super","user_default","alias","display_options"),"menu_items",$where,'');

while($row=mysqli_fetch_row($resource)){
 $item=new menu_items;
 $item->item_id=$row[0];
 $item->item_menuid=$row[1];
 $item->item_title=$row[2];
 $item->item_modules=$row[3];
 $item->item_status=$row[4];
 $item->item_link=$row[5];
 $item->item_macroId=$row[6];
 $item->item_parentid=$row[9];
 $item->item_children= $this->getMenuItems($menu_id,$all,$level+1,$item->item_id);
 if(count($item->item_children)>0){
  $item->item_hasChild=true;
 }
 $item->item_accesslevel=$row[7];
 $item->item_hasrestriction=$row[8];
 $item->item_forsuper=$row[10];
 $item->item_isdefault=$row[11]; 
 $item->item_isall=$all;
 $item->item_alias=$row[12];
 $item->item_diaplayOption=$row[13];
  
 if(!defined("ADMIN_ROOT")){
	if(isset($_SESSION[System::getMenuLinkSessionName()])){
	
	   $myL=unserialize($_SESSION[System::getMenuLinkSessionName()]);
	   
	}
 }
 
 if(!defined("ADMIN_ROOT")){
  
  if(!isset($myL["$item->item_alias"])){ 
 
   $myL[$item->item_alias]=$item->item_link;
   
   }
  
 }
 
 $_SESSION[System::getMenuLinkSessionName()]=serialize($myL);
 
 if(($cn->clean_url==1) && (!defined("ADMIN_ROOT")))
 if($item->item_isdefault==1){
     $item->item_link="http://".preg_replace("/\/\//i","/",$_SERVER['HTTP_HOST'].ROOT_DIR);
 }else{
	 $use_external =false
;	 if($row[5]!="")
      if($row[5][0]!="?")
	  $use_external=true;
	
	 	 
	 if(!$use_external){
	 
	 $item->item_link="http://".preg_replace("/\/\//i","/",$_SERVER['HTTP_HOST'].ROOT_DIR.$row[12]."/");
	 
	 }else{
	 
	 $item->item_link=$row[5];
	 
	 }
 }
 
 if($item->item_accesslevel==2){
 
 $user=$this->getUserSession();
 
 if(defined("OPEN_PAGE")|($user->user_type==9)|($user->id==0))
  $items[]=$item;
  
 }else{
	 
  $user=$this->getUserSession();
 
 if(($user->user_type>=$item->item_accesslevel)){

 $items[]=$item;
 
 }
 }
 
}

if($all_menus){
 $level=1;
}

if($level==0){
 if(defined("ADMIN_ROOT")){	
   $this->addMenuToSession($items);
 }
}

return $items;
}
public function generateUserToken($uid=0){

$res=$this->selectQuery(array("password"),"users","where id=".$uid);

while($row=mysqli_fetch_row($res)){
	$res=$this->updateQuery(array("reset_token='".$row[0].time()."'","token_time=".time()),"users","where id=".$uid,'');
	return $row[0].time();
}

return 0;

}
public function getUserDetails($whereclause=""){

$user_details=array();

$resource=$this->selectQuery(array("id","parent_id","firstname","middlename","lastname","user_status","username","user_type","cellphone","email","gender","image_url","reset_token","token_time"),"users",$whereclause,'');


while($row=mysqli_fetch_row($resource)){
  $temp=new User_Session;
  $temp->username=$row[6];
  $temp->id=$row[0];
  $temp->parent_account=$row[1];
  $temp->user_type=$row[7];
  $temp->user_status=$row[5];
  $temp->parent_id=$row[1];
  $temp->firstname=$row[2];
  $temp->secondname=$row[3];
  $temp->lastname=$row[4];
  $temp->cellphone=$row[8];
  $temp->gender=$row[10];
  $temp->profile_image=$row[11];
  $temp->email_address=$row[9];
  $temp->hasToken=false;
  $temp->tokenTime=$row[13];
  $temp->token=$row[12];
  $user_details[]=$temp;
  
}

return $user_details;


}
public function createUser($object,$pass){

 $this->insertQuery(array("firstname","middlename","lastname","user_status","username","user_type","cellphone","email","password","gender"),"users",array("'{$object->firstname}'","'{$object->secondname}'","'{$object->lastname}'","0","'{$object->username}'","{$object->user_type}","'{$object->cellphone}'","'{$object->email_address}'","'".$this->hashPassword($pass)."'","{$object->gender}"));

}

}

?>