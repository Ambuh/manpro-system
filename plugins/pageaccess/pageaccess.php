<?php
function pageaccess(){
GLOBAL $db;
;
 if(isset($_GET["mid"])){
  $items=$db->getMenuItemFromDb($_GET["mid"]);
  define("MID",$_GET["mid"]);
  if(isset($items->item_accesslevel))
  if($items->item_accesslevel==2)
  define("OPEN_PAGE",1);
  
  if($items!=NULL){
 
     if($db->isLogged()){

     if(($items->item_hasrestriction==1)||($items->item_accesslevel==2)){
	 
	 $us=$db->getUserSession();
	  
      return $db->hasAccessToPage($us->user_type,$items->item_accesslevel);
     
	 	 
	  }
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
		

        $_SESSION[System::getSessionPrefix().'user_session']=serialize($temp);

			$items=$db->getMenuItemFromDb($_GET["mid"]);
			

			if($items->item_accesslevel==2){
	   
	    return true; }else{return false;} 

		 
	 }
   }else{  if($db->isLogged()){ if($items->item_accesslevel==2){
	   
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
        $_SESSION[System::getSessionPrefix().'user_session']=serialize($temp);
       define("OPEN_PAGE",1);
	   
	    return true; }else{return false;} }else{
			
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
		

        $_SESSION[System::getSessionPrefix().'user_session']=serialize($temp);

			$items=$db->getMenuItemFromDb($_GET["mid"]);
			
          if(isset($items->item_accesslevel))
			if($items->item_accesslevel==2){
	   
	           return true; 
			   
			   }else{
				   
				   return false;
				   
				} 
			
			}
		 }
   
}else{
 
   $item=$db->getDefaultMenuItem();
   
    if(($item==NULL)&&($db->isLogged())){
	  return false;
	}else{
	  if(!$db->isLogged()){
	     return true;
	  }else{
	     if($item!=NULL){
			 		   
		   define("MID",$item->item_menuid);
		    
			if(!$db->menuIsEnabled($item->item_menuid))
			return false;
			 	 
		 }  
	  }
	}
   
 }
return true;
}
?>