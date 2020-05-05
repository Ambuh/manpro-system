<?php

function SalesUpdater(){

 $user_manager=System::shared("usermanager");

 $usr=$user_manager->getUsers(" where username='".$_POST['user']."' and status=1 and user_type=3");
 //parse_str(base64_decode($_POST),$_POST);

 for($i=0;$i<count($usr);$i++){
    
    //echo $usr[0]->user_password."--";
    
    if(sha1($usr[0]->user_password.System::getArrayElementValue($_POST,'Sales'))!=System::getArrayElementValue($_POST,'token')){
        echo "Invalid password";
        return;
    }
    
    define("USER_LOGGED","usid_".$usr[0]->user_id."_".$usr[0]->user_name."_".$usr[0]->user_type."_".$usr[0]->user_branch."_".$usr[0]->user_company);
    $_SESSION[System::getSessionPrefix()."USER_LOGGED"]="usid_".$usr[0]->user_id."_".$usr[0]->user_name."_".$usr[0]->user_type."_".$usr[0]->user_branch."_".$usr[0]->user_company;
    
    $current_user=explode("_",$_SESSION[System::getSessionPrefix().'USER_LOGGED']);
   
    $lan=System::shared("lantern");
    
    //echo $_POST['branch'];
    
    $branch=$lan->getBranches(" and branch_name='".$_POST['branch']."'",$current_user[5]);
    
    if(count($branch)==0){
     echo "Invalid branch";
     return;
    }
    
    if(!isset($_POST['miniOrder'])){
    
    System::getPostedItems("wtr");
    
    for($c=0;$c<count($branch);$c++){
         
         $lan->saveAutoIncome("Sales",$_POST['Sales'],$branch[$c]->value,$current_user[5],System::getArrayElementValue($_POST,'PostDate',''));
         
         $waiter_data=System::getPostedItems("wtr");
    
       for($x=0;$x<count($waiter_data);$x++){
          $dets=explode("_",$waiter_data[$x]->name);
          $lan->saveWaiterRevenue($dets[2],$dets[1],$waiter_data[$x]->value,$branch[$c]->value,$current_user[5],System::getArrayElementValue($_POST,'PostDate',''));
       }
       
       $food_data=System::getPostedItems("fty");
       
       
       for($x=0;$x<count($food_data);$x++){
          $dets=explode("_",$food_data[$x]->name);
          $lan->saveFoodRevenue($dets[2],$dets[1],$food_data[$x]->value,$dets[3],$branch[$c]->value,$current_user[5],System::getArrayElementValue($_POST,'PostDate',''));
       }

       $req=$lan->getRetrieveRequest("where branch_id=".$branch[$c]->value." and r_date<>''");
    
       for($x=0;$x<count($req);$x++)
       echo "#Retrieve:".$req[$x]->value;
       
       $lan->resetRetreive($branch[$c]->value);
       
         break;
     }
    
    }else{
        for($c=0;$c<count($branch);$c++){
            
            echo $lan->getMiniOrdersAsJSON(0,$current_user[5],$branch[$c]->value);
        
        }
    }
   
 }
 
 if(count($usr)==0){
  echo "User disabled or does not exist.";
  return;
 }

}
?>