<?php
function staticoptions(){
	return new staticoptions;
}
class staticoptions{
	private $db;
	public function __construct(){
	GLOBAL $db;
	$this->db=$db;
	}
	public function staticBodyOptions($adding =false){
	$options=array("Any Body Type","Saloon","Hatchback","4 Wheel Drive","Station Wagon","Pick-Ups","Motorbike",
		"Convertible","Bus,Matatu & Van","Truck","Machinery & Tractor","Trailer","Other");
	$res=array();
	  
	  if($adding)
	  $options[0]="Please Select";
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$options[$i]);
	  
	  return $res;
	  
	}
	public function staticCondition($adding =false){
	$options=array("Any Condition","Used Overseas Only","Used Locally","Import");
	$res=array();
	  
	  if($adding)
	  $options[0]="Please Select";
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$options[$i]);
	  
	  return $res;
	  
	}
	public function staticDriveType($adding =false){
	$options=array("Any Drive Type","2 Wheel Drive","4 Wheel Drive");
	$res=array();
	  
	  if($adding)
	  $options[0]="Please Select";
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$i);
	  
	  return $res;
	  
	}
	public function staticDoors($adding =false){
	$options=array("Please Select","2","3","4");
	$res=array();
	  
	  if($adding)
	  $options[0]="Please Select";
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$i);
	  
	  return $res;
	  
	}
	public function staticTransmission($adding =false){
	$options=array("Any Transmission","Manual","Automatic","Other");
	$res=array();
	  
	  if($adding)
	  $options[0]="Please Select";
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$options[$i]);
	  
	  return $res;
	  
	}
	public function staticColour($adding =false){
	$options=array("Any Colour","White","Silver","Green","Dark Green","Blue","Dark Blue","Brown","Black","Yellow","Red",
	"Yellow","Red","Maroon","Purple","Pink","Orange","Grey","Dark Grey","Gold","Beige","Other");
	$res=array();
	  
	  if($adding)
	  $options[0]="Please Select";
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$options[$i]);
	  
	  return $res;
	  
	}
	public function staticFuelType($adding =false){
	$options=array("Any Fuel Type","Petrol","Diesel","Hybrid","Other");
	$res=array();
	  
	  if($adding)
	  $options[0]="Please Select";
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$options[$i]);
	  
	  return $res;
	  
	}
	public function countryCodes(){
	$options=array("Kenya(+254)","Tanzania(+255)","Uganda(+256)","Ethiopia(+251)","Japan(+81)","United Arabs Emirates(+971)","United Kindom(+44)");
	$vals=array("+254","+255","+256","+251","+81","+971","+44");
		
	$res=array();
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$vals[$i]);
	  
	  return $res;
	
	}
	public function driveSetup($adding =false){
	$options=array("Any Drive Setup","Left Hand Drive","Right Hand Drive","Other");
	$res=array();
	  
	  if($adding)
	  $options[0]="Please Select";
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$options[$i]);
	  
	  return $res;
	  
	}
	public function minPrice(){
	$options=array("Min Price","100,000","500,000","700,000","1,000,000","2,000,000","5,000,000","Max Price");
	$res=array();
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$options[$i]);
	  
	  return $res;
	  
	}
	public function maxPrice(){
	$options=array("Max Price","5,000,000","2,000,000","1,000,000","700,000","500,000","100,000","Min Price");
	$res=array();
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$options[$i]);
	  
	  return $res;
	  
	}
	public function make($adding =false,$whereclause="",$raw=false){
	
	$options=array();
	if(!$raw)
	if($whereclause=="")
	$options=array("Any Make");
	
	$res=array();
	
	  if($whereclause=="")
	  if(!$raw)
	  if($adding)
	  $options[0]="Please Select";
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$i);
	  
	  $wh=$whereclause;
	  
	  if(preg_match("/where/i",$wh)){
	  $wh.=" and is_porpular=1";
	  
	  }else{
	  $wh="where is_porpular=1";
	  }
	  
	  if(!$raw)
	  $res[]=new name_value("---Porpular---",-2);
	  
	  $resource =$this->db->selectQuery(array("*"),"car_make",$wh." order by priority asc");
	  $ids=array();
	  while($row=mysqli_fetch_row($resource)){
		$res[]=new name_value($row[1],$row[0],$row[2],$row[4]);
		$ids[]=$row[0];  
	  }
	  $whereclause2=$whereclause;
	  
	  if(count($ids)>0)
	  if(preg_match("/where/i",$whereclause2)){
	  $whereclause2.=" and  id<>".implode(" and id<>",$ids);
	  
	  }else{
	  $whereclause2="where id<>".implode(" and id<>",$ids);
	  }
	  $resource =$this->db->selectQuery(array("*"),"car_make",$whereclause2." order by id asc");
	  
	  if(!$raw)
	  $res[]=new name_value("---Alphabetically---",-1);
	  
	  while($row=mysqli_fetch_row($resource)){
		$res[]=new name_value($row[1],$row[0],$row[2],$row[4]);  
	  }
	  
	  return $res;
	  
	}
	public function model($adding =false,$whereclause="",$showraw=false){
	  $res=array();
	  
	  $options=array();
	  
	  if(!$showraw){
	  $options=array("Any Model");
	 
	  if($adding)
	  $options[0]="Please Select";
	  }
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$i);
	  
	  $resource=$this->db->selectQuery(array("*"),"models",$whereclause." order by model_desc asc");
	  
	  while($row=mysqli_fetch_row($resource)){
	  if(!$showraw){
	     $res[]=new name_value($row[2]."_".$row[0],$row[1]);
	  }else{
	     $res[]=new name_value($row[2],$row[0]);
	  }
	  }
	  
	  return $res;

	}
	public function duty(){
	  
	  $options=array("Please Select","Paid","Not Paid");
	  $res=array();
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$options[$i]);
	  
	  return $res;

	}
	public function years($asc=true,$adding=false){
	  $min_year=1970;
		
	  $options=array();
	  
	  $opt=array();
	  
	  $st_date=date("Y",time());
		
		$opt[]="Min Year";
		
		$dif=$st_date-1970;
		for($i=1;$i<$dif;$i++)
		$opt[]=1970 + $i;
		
		
		$opt[]="Max Year";
		
	    $options=$opt;
	  
	  if(!$asc){
	    
		array_pop($opt);
		
		array_shift($opt);
		
		$options=array();
		
		if(!$adding){
			
		$options[]="Max Year";
		
		}else{
		 $opt[]=date("Y",time());
		 $opt[]="Please Select";
		}
		
		for($i=count($opt)-1;$i>-1;$i--)
		$options[]=$opt[$i];
		
		if(!$adding)
		$options[]="Min Year";
		
	  }
	  
	  $res=array();
	  
	  for($i=0;$i<count($options);$i++)	
	  $res[]=new name_value($options[$i],$options[$i]);
	  
	  return $res;
		
	}
	public function locations($adding =false){

 	$options=array("Any location");
	
	if($adding)
	  $options[0]="Please Select";
	
	$resource =$this->db->selectQuery(array("*"),"locations"," order by id asc");
	
	$res=array();
	
	$val=-1;
	
	if($adding)
	$val="Please Select";
	
	$res[]=new name_value($options[0],$val);
	
	while($row=mysqli_fetch_array($resource)){
	  
	  $res[]=new name_value($row["location_name"],$row["location_name"]);
	
	}
	  
	return $res;
	 

	}
	
}
?>