<?php
define("ROOT"," ");

include_once("../../library/objects/toStringConverter.php");

include_once ("../globals/common_controls.php");


include_once ("../objects/database_objects.php");

include_once ("../../app_configs.php");

class database{

    private $con;

    function __construct()
    {

        $count =0;

        $config= new config();

        while(!$this->con=mysqli_connect($config->db_host,$config->db_user,$config->db_password)){

            if($count>2000){

                echo "Could not connect to the database";

                exit;

            }

            $count++;

        }

        mysqli_select_db($this->con,$config->db_name);

    }
    public function selectQuery($fields,$tablename,$whereclause=""){

        $table_fields=implode(",",$fields);

        $mysqli_query="select ".$table_fields." from ".$tablename." ".$whereclause;

        $resource= mysqli_query($this->con,$mysqli_query) or die(mysqli_error($this->con));

     return $resource;

    }
}

class cronJobHandler{

    public $db;

    function __construct()
    {
        $this->db= new database();
    }

    function sendGlobalEmails(){

      }
      function getCompanys(){
           $res=$this->db->selectQuery(array("*"),'companies');

           $data=array();

           while($row=mysqli_fetch_assoc($res)){
               $data[]=$row;
           }
         return $data;
      }
      function sendProjectDailyOverviews(){
          /*
           * get details of all the changes of the day
           * attachaments images |notes | purchases | requsitions | labour | Components |Taks|
           * create two pdf documents one containing an eagles eye preview of the project
           * create a detailed project summary subdividing each section with its report . labour |requsition | purchase |components|
           * create a web document with a brief review of the project's details  and the members of that project
           * */
      }
      function sendProjectMonthlyOverviews(){

      }
      function sendProjectOverViews(){

      }
      function getMyCompanies(){

      }
      function getMyProjects(){
      }
      function getMyPurchases(){

      }
      function getTodaysActivities(){


      }
   private final function emailSimpleLayout($cmpArray,$projects){
      $cont= new objectString();



      return $cont->toString();
   }


  }
$cronManager= new cronJobHandler();


if(isset($_GET['em']))
    $this->sendGlobalEnails();

if(isset($_GET['pr']))
    $this->sendProjectOverviews();


function majorPages(){
    $cont= new objectString();

    $cron = new cronJobHandler();

    $companies =($cron->getCompanys());

    for($i=0;$i<count($companies);$i++){
        $cont->generalTags("Comanany name : ".$companies[$i]['company_prefix']." <br/>");
    }

    $xml = simplexml_load_file('http://www.google.com/ig/api?weather='.urlencode('nairobi'));

    print_r($xml);
    $cont->generalTags("user pages united");

    return $cont->toString();
}

echo majorPages();

?>