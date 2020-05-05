<?php 
function calendarmarker(){

 return new cMarker;

}

class cMarker{

private $db;

public $buffered_vals=array();

public function __construct(){

 GLOBAL $db;
 
 $this->db=$db;

}
public function getMarkedDates($whereclause=""){

$whereclause.=" order by i_date asc";

$resource=$this->db->selectQuery(array("id","date_format(i_date,'%D-%b-%Y')","company_id","description"),"important_dates"," where company_id=".PARENT." ".$whereclause);

$arrays=array();

 while($row=mysqli_fetch_row($resource)){

   $cm=new cMark;
   
   $cm->importantDate_id=$row[0];
   
   $cm->importantDate_date=$row[1];
   
   $cm->importantDate_companyId=$row[2];
   
   $cm->importantDate_description=$row[3];

   $arrays[]=$cm;
   
 }

return $arrays;

}
public function markDate($cMark){


$marked=$this->getMarkedDates(" and i_date='".System::formatDate($cMark->importantDate_date)."'");

if(count($marked)>0){

$this->saved_vals[]=$cMark->importantDate_date;

$this->saved_vals[]=$cMark->importantDate_description;

return System::getWarningText("Date already marked","margin-left:3px;");

}

if($cMark->importantDate_date==""){

$this->buffered_vals[]=$cMark->importantDate_date;

$this->buffered_vals[]=$cMark->importantDate_description;

return System::getWarningText("Enter Date");

}

if($cMark->importantDate_description==""){

$this->buffered_vals[]=$cMark->importantDate_date;

$this->buffered_vals[]=$cMark->importantDate_description;

return System::getWarningText("Enter Description");

}

$this->db->insertQuery(array("i_date","company_id","description"),"important_dates",array("'".System::formatDate($cMark->importantDate_date)."'",PARENT,"'".$cMark->importantDate_description."'"));


return System::successText("Date added!","margin-right:3px;");

}
public function removeMarkedDate($id){

$this->db->deleteQuery("important_dates","where id=$id");

return System::successText("Date deleted!");

}

}

class cMark{

public $importantDate_id;

public $importantDate_date;

public $importantDate_companyId;

public $importantDate_description;

}

?>