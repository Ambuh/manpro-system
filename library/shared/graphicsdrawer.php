<?php
function graphicsdrawer(){

return new Graphics;

}
class Graphics extends objectString{
private $paneWidth;
private $paneHeight;
private $max;
private $plots;
private $intervals;
private $axis="#444";
private $colorCode=array();
public function setPaneWidth($width){
	$this->paneWidth=$width;
}
public function setPaneHeight($height){
    $this->paneHeight=$height;
}
public function setAxisColor($colorCode){
	
	$this->axis=$colorCode;

}
public function setColorCodes($codes_array){

$this->colorCode=$codes_array;

}
public function setPlotValues($array){
$this->plots=$array;
}
public function setXPoints($max,$intevals){
 $this->max=$max;
 $this->intervals=$intevals;
}
public function plotKeys($columns=2){
$this->generalTags("<div style=\"float:left;width:{$this->paneWidth}px;margin-top:20px;\">");

$col_limit=count($this->plots)/$columns;

$width=($this->paneWidth/$columns)."px";

$limit=0;

$this->generalTags(System::categoryTitle("Keys","background:none;border:none;margin-bottom:5px;"));

$this->generalTags("<div id=\"gcol\" style=\"float:left;width;$width;overflow:hidden;\">");

for($i=0;$i<count($this->plots);$i++){
	$margin_left="";
	if($limit!=0)
	 $margin_left="margin-left:4px;";
	
	$this->generalTags("<div style=\"float:left;width:15px;height:15px;$margin_left margin-right:2px;background:".System::getArrayElementValue($this->colorCode,$i).";\"></div><div style=\"float:left;\">{$this->plots[$i]->name}({$this->plots[$i]->value})</div>");
	
		if(($limit==$col_limit-1)&&($i!=count($this->plots)-1)){
		$limit=0;
		$this->generalTags("</div><div id=\"gcol\" style=\"float:left;\">");
		}
	$limit++;
	
}
$this->generalTags("</div>");
$this->generalTags("</div>");


}
public function plotGraph($title=""){

$this->generalTags(System::categoryTitle("$title","border:none;background:none;text-align:center;margin-bottom:5px;"));

$wdth=$this->paneWidth-100;

$font_color=array("#333","#000");

$this->generalTags("<div style=\"width:100px;float:left;height:{$this->paneHeight}px;\">");

$interval=$this->max/$this->intervals;

$heigth=0;

if($this->max!=0)
$heigth=($interval/$this->max) * 100;

for($i=0;$i<$this->intervals;$i++){

$value=$this->max-($interval*$i);

$this->generalTags("<div id=\"plot_row\" style=\"height:{$heigth}%;\"><div id=\"plot_label\">".(int)$value."</div><div id=\"plot_mark\"></div></div>");

}

$this->generalTags("</div>");

$this->generalTags("<div style=\"width:{$wdth}px;float:left;height:{$this->paneHeight}px;border-left:1px solid $this->axis;border-bottom:1px solid $this->axis;margin-top:12px;\">");

$barwidth=(($this->paneWidth-100)/count($this->plots))-(count($this->plots) * 2);

for($i=0;$i<count($this->plots);$i++){

$barHeight=0;

if($this->max!=0)
$barHeight=($this->plots[$i]->value)/$this->max * 100;

$rem_height=100-$barHeight;

$color="";

if(in_array(System::getArrayElementValue($this->colorCode,$i),$font_color))
$color="color:#fff;";

$this->generalTags("<div id=\"graph_bar\" style=\"width:{$barwidth}px\"><div id=\"graph_top\" style=\"height:{$rem_height}%\"></div><div id=\"graph_self\" style=\"background:".System::getArrayElementValue($this->colorCode,$i).";height:{$barHeight}%;text-align:center;$color\">{$this->plots[$i]->value}</div></div>");

}

$this->generalTags("</div>");
}
public function showGraphics(){

return $this->toString();

}

}

?>