<?php
function md_adminmenu($id){
 GLOBAL $db;
 
   ?>
    <div id="module_inner">
  <?php 
  $us=$db->getUserSession();
  $menu_items=$db->getMenuItems($id);
  for($i=0; $i<count($menu_items);$i++){
   if(($menu_items[$i]->item_hasrestriction==1) && (!$db->hasAccessToPage($us->user_type,$menu_items[$i]->item_accesslevel))){
   }else{
  ?>
  <div class="menu_row" id="rw_<?php echo $id.$i; ?>" <?php if(!$menu_items[$i]->item_hasChild):?> onmouseover="divcolour('#ddd','rw_<?php echo $id.$i; ?>')" onmouseout="divcolour('none','rw_<?php echo $id.$i; ?>')" <?php endif; ?> <?php if($menu_items[$i]->item_hasChild): ?>style="cursor:pointer;" onmouseover="showThis('child_cont<?php echo $i; ?>','#ddd','rw_<?php echo $id.$i; ?>')" onmouseout="hideThis('child_cont<?php echo $i; ?>','none','rw_<?php echo $id.$i; ?>')" <?php endif ?>><?php if($menu_items[$i]->item_hasChild){ echo $menu_items[$i]->item_title."<div id=\"show_child\">></div>"; }else{?> <a href="<?php echo $menu_items[$i]->item_link; ?>"><?php echo $menu_items[$i]->item_title;?></a><?php } ?>

   <?php 
     if($menu_items[$i]->item_hasChild){
	  ?>
      <div class="menu_child_container" id="child_cont<?php echo $i; ?>">
      <?php 
	    
	    for($i2=0;$i2<count($menu_items[$i]->item_children);$i2++){

		if (($menu_items[$i]->item_children[$i2]->item_hasrestriction==1) && (!$db->hasAccessToPage($us->user_type,$menu_items[$i]->item_children[$i2]->item_accesslevel))){
		
		}else{
		?>
        <div class="menu_row"><a href="<?php echo $menu_items[$i]->item_children[$i2]->item_link; ?>"><?php echo $menu_items[$i]->item_children[$i2]->item_title;?></a></div>
        <?php
		 }
		}
	  ?>
      </div>
      
      <?php 
	 }
	 ?>
     </div>
     <?php
     }
	}
    ?>
   </div>
  <?php
}
?>