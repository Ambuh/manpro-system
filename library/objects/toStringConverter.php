<?php
class objectString{
    private $flush_mode=false;
    public $object_buffer=array();
	
public function divTagOpen($id,$class,$other_attributes){
    $this->object_buffer[]="<div id=\"$id\" class=\"$class\" ". $other_attributes.">";
    if($this->flush_mode){
     echo "<div id=\"$id\" class=\"$class\" ".$other_attributes.">";
   }
}
public function setMode($status){
    $this->flush_mode=$status;
}
public function generalTags($tags){
$this->object_buffer[]=$tags;
  if($this->flush_mode){
      echo $tags;
  }
}
public function closeTag($counts=1){
   for($i=0;$i<$counts;$i++){
   $this->object_buffer[]="</div>";
   if($this->flush_mode){
    echo "</div>";
   }
 }
}
public function toString(){
 $st=implode($this->object_buffer);
 return $st;
}
}
?>