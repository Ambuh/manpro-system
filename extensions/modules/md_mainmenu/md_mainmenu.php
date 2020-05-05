<?php
function md_mainmenu($id){
 GLOBAL $db;
   ?>
    <div id="module_inner">
  <?php 
  $us=$db->getUserSession();
  $menu_items=$db->getMenuItems($id);
  
  for($i=0; $i<count($menu_items);$i++){
	
   $str="";
   
   $active="";
	 
   if($i==0)
   $str="border-left:none!important;";
	 
   if($i==(count($menu_items)-1))
	 $str="border-right:none!important;";
	 
   if(($menu_items[$i]->item_hasrestriction==1) && (!$db->hasAccessToPage($us->user_type,$menu_items[$i]->item_accesslevel))){
	//echo $menu_items[$i]->item_accesslevel;  
   }else{ 
   if($menu_items[$i]->item_id==System::getCheckerNumeric('mid')){
    $active="_active";
   }
  ?>
   <div class="menu_row<?php echo $active; ?>" id="rw_<?php echo $id."_".$i; ?>"  style=" <?php echo $str; ?>" <?php if($menu_items[$i]->item_hasChild): ?>style="<?php echo $str; ?>cursor:pointer;"  <?php endif ?>><?php if($menu_items[$i]->item_hasChild){ echo "<div id=\"innerc\">".$menu_items[$i]->item_title."</div><div id=\"show_child\">+</div>"; }else{?> <a href="<?php echo $menu_items[$i]->item_link; ?>"><div class="m_icon" id="m_icon<?php echo $i; ?>"></div><?php echo $menu_items[$i]->item_title;?></a><?php } ?></div>

   <?php 
     if($menu_items[$i]->item_hasChild){
	  ?>
      <div class="menu_child_container" id="child_cont<?php echo $i; ?>">
      <?php 
	    
	    for($i2=0;$i2<count($menu_items[$i]->item_children);$i2++){
         
		$active="";
		
		if (($menu_items[$i]->item_children[$i2]->item_hasrestriction==1) && (!$db->hasAccessToPage($us->user_type,$menu_items[$i]->item_children[$i2]->item_accesslevel))&&($menu_items[$i]->item_accesslevel!=2)){

		
		}else{
			if($menu_items[$i]->item_id==System::getCheckerNumeric('mid'))
             $active=" class=\"active\" ";
		?>
        <div class="menu_row" id="rw_<?php echo $id.$i.$i2; ?>" <?php echo $active ?> style=" <?php echo $str; ?>" <?php if($menu_items[$i]->item_children[$i2]->item_hasChild): ?>style="cursor:pointer;overflow:visible;" onmouseover="showThis('child_cont<?php echo $i.$i2; ?>','#ddd','rw_<?php echo $id.$i.$i2; ?>')" onmouseout="hideThis('child_cont<?php echo $i.$i2; ?>','none','rw_<?php echo $id.$i.$i2; ?>')" <?php endif ?> ><?php if($menu_items[$i]->item_children[$i2]->item_hasChild){ echo $menu_items[$i]->item_children[$i2]->item_title."<div id=\"show_child\">></div>"; }else{?><a href="<?php echo $menu_items[$i]->item_children[$i2]->item_link; ?>"><?php echo $menu_items[$i]->item_children[$i2]->item_title;?></a><?php } ?>
        
		<?php
		
		if($menu_items[$i]->item_children[$i2]->item_hasChild){
		?>
        <div class="menu_child_container2" id="child_cont<?php echo $i.$i2; ?>" >
        
         <?php for($b=0;$b<count($menu_items[$i]->item_children[$i2]->item_children);$b++){ ?>
         
         <div class="menu_row" id="rw_<?php echo "ii".$id.$i.$i2; ?>"><a href="<?php echo $menu_items[$i]->item_children[$i2]->item_children[$b]->item_link;?>"><?php echo $menu_items[$i]->item_children[$i2]->item_children[$b]->item_title; ?></a></div>
        
        <?php } ?>
        
                
        </div>
        
        <?php
		
		}
		echo "</div>";
		 }
		}
	  ?>
      </div>
      
      <?php 
	 }
	 ?>
     
     <?php
     }
	}
    ?>
  
   </div>
  <?php
}
?>