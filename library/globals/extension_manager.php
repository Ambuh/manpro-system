<?php
class extension_handler{
    private $user;
    public function __construct(){
	 
	 GLOBAL $db;
	 
	 $this->user=$db->getUserSession();
	 
	 
	}
	
    public function loadModule($position,$frsp=0,$return=false){
        GLOBAL $db;
		$item_id=0;
		
        $mods=$db->getModules("where status=1 and position='".$position."' and for_super=".$frsp);
		
        for($i=0;$i<count($mods);$i++){
		
		    if(!defined("ADMIN_ROOT")){
			
	if((($mods[$i]->module_menuassign=="")|($mods[$i]->module_menuassign==NULL)||($this->user->user_type<$mods[$i]->module_accesslevel))&&($mods[$i]->module_accesslevel!=2)){
			
				 continue;
				 				 
			  }else{
			  if(isset($_GET['mid']))
			    if(!in_array($_GET['mid'],unserialize($mods[$i]->module_menuassign))){
				   
				   continue;
				   				   
				}
				
				}
		            
			}  
			 $returnData="";
			 
			  include_once(ROOT."extensions/modules/".$mods[$i]->module_name."/".$mods[$i]->module_name.".php");
              $funcname=$mods[$i]->module_name;
              if(!$return){
			  echo "<div id=\"mod\" class=\"mod{$mods[$i]->module_suffix}\">";
			  }else{
				  $returnData.="<div id=\"mod\" class=\"mod{$mods[$i]->module_suffix}\">";
			  }
             if($mods[$i]->module_show_title):
               if(!$return){
               echo "<div id=\"module_title\">{$mods[$i]->module_title}</div>";
			   }else{
				   $returnData.="<div id=\"module_title\">{$mods[$i]->module_title}</div>";
			   }
             endif;
             
			 if(!$return){
              
			      $funcname($mods[$i]->module_id);
              
			      echo "</div>";
			  
			  }else{
				  $returnData.=$funcname($mods[$i]->module_id,true);
				  $returnData.="</div>";
			  }
			  
			  if($return)
			  return $returnData;
             
        }
        
    }
    public function isAvailable($position,$frsp=0){
    GLOBAL $db;
		
	$mods=$db->getModules("where status=1 and position='".$position."' and for_super=".$frsp);
	
	for($i=0;$i<count($mods);$i++){
		
		if((unserialize($mods[$i]->module_menuassign)!=null)&&(in_array($_GET['mid'],unserialize($mods[$i]->module_menuassign)))){
		
		  return true;
		
		}
		
	}
	
	return false;	
		
   }
   public function loadmacro(){
       GLOBAL $db;
       $user=new Manage_User;
       $macro_type=0;
       if(defined("ADMIN_ROOT")):
         $macro_type=1; 
       endif;

    
       if(isset($_GET['opt'])){
	     
		 if(!is_numeric($_GET['opt'])){
		   $_GET['opt']=0;
		 }
		 
        $macro=$db->getmacro($_GET['opt'],0,$macro_type);
		
		
		
		if($macro==NULL):
		
		$macro=$db->getmacro(0,1,$macro_type);
		
		endif;
		
        if($macro!=NULL){
          $macro_path="";
		  
          if($user->isLoggedIn()){
		   
            include_once(ROOT."extensions/macros/{$macro->macro_name}/{$macro->macro_name}.php");
            
          }else{
            if(defined("ADMIN_ROOT")){  
            $macro->macro_name="sp_login";
            
            $macro->macro_id=0;
            
            include_once(ROOT."extensions/macros/{$macro->macro_name}/{$macro->macro_name}.php");
			}else{
			 
			}
          }
          
		  include_once(ROOT."extensions/macros/{$macro->macro_name}/{$macro->macro_name}.php");
		  
          $func=$macro->macro_name;
          
          $func($macro->macro_id) or die("Invalid macro");
          
        }else{
		
		  echo System::getWarningText("No macro assigned to this page or the existing one is disabled");
		 
		}
       }else{
        
        $macro=$db->getmacro(0,1,$macro_type);
        
        if($macro!=NULL){
        
        if($user->isLoggedIn()){
            
        include_once(ROOT."extensions/macros/{$macro->macro_name}/{$macro->macro_name}.php");
        
        }else{
			
			if(defined("ADMIN_ROOT")){
            $macro->macro_name="sp_login";
            
            $macro->macro_id=0;
		   
            
            include_once(ROOT."extensions/macros/{$macro->macro_name}/{$macro->macro_name}.php");
			}else{
			 echo "try me";
			}
        }
        GLOBAL $db;
        $func=$macro->macro_name;
        
        $func($macro->macro_id) or die("Invalid macro");
        
        }else{
		
		if($user->isLoggedIn()){
      
		 if($macro!=NULL){
          
		  include_once(ROOT."extensions/macros/{$macro->macro_name}/{$macro->macro_name}.php");
          
		  }
        
		}else{
			if(defined("ADMIN_ROOT")){
            $macro->macro_name="sp_login";
            
            $macro->macro_id=0;
			
            include_once(ROOT."extensions/macros/{$macro->macro_name}/{$macro->macro_name}.php");
			}else{
				$macro->macro_name="macro_userpanel";
            
                $macro->macro_id=0;
				
				include_once(ROOT."extensions/macros/{$macro->macro_name}/{$macro->macro_name}.php");
			}
        }
		
		GLOBAL $db;
		 if($macro!=NULL){
		 
           $func=$macro->macro_name;
        
           $func($macro->macro_id) or die("Invalid macro");
		   
          }
		
		}
       }
       
    }
}
?>