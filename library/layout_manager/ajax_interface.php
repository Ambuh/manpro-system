<?php
class ajaxRun{
    public function main(){
		$typ=0;
		if(isset($_POST['typ']))
		$typ=$_POST['typ'];
		
		if(isset($_GET['typ']))
		$typ=$_GET['typ'];
		
		
		   $nm="";
		   $par="";
		   
		   if(isset($_POST['nm']))
		   $nm=$_POST['nm'];
		
		   if(isset($_GET['nm']))
		   $nm=$_GET['nm'];
		   
		   if(isset($_GET['par']))
		   $par=$_GET['nm'];
		   
		   if(isset($_POST['par']))
		   $par=$_POST['par'];
		
		
        switch($typ){
         
         case OPTION_MODULES:
		    if(file_exists(ROOT."extensions/modules/".$nm."/ajax_asset.php")){
            include_once(ROOT."extensions/modules/".$nm."/ajax_asset.php");
            $module_ajax=new ajax_run;
            $module_ajax->main($par);
			}else{
			  echo "Not Available";
			}
            break;
        
         case OPTION_LIB_ETC:
		     if(file_exists(ROOT."lib_etc/".$nm.".php")){
              include_once(ROOT."lib_etc/".$nm.".php");
              $lib=new lib_run;
              $lib->main($par);
			  }else{ 
			    echo "Not Available";
			  }
              break;
            
         case OPTION_MACRO:
		 
		      if(file_exists(ROOT."extensions/macros/".$nm."/ajax_asset.php")){
               include_once(ROOT."extensions/macros/".$nm."/ajax_asset.php");
               $com=new ajax_run;
               $com->main($par);
			  }else{
			   echo "Not Available";
			  }
              break;
            
         case OPTION_PLUGIN:
		      if(file_exists(ROOT."plugins/".$nm."/ajax_asset.php")){
              include_once(ROOT."plugins/".$nm."/ajax_asset.php");
              $plugin=new ajax_run;
              $plugin->main($par);
			  }else{
			    echo "Not Available";
			  }
              break;
        }
        
    }
}
?>