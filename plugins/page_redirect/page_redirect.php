<?php
function page_redirect(){
GLOBAL $db;

$user=$db->getUserSession();

if(($user->parent_id!=PARENT)&(PARENT==0)){

$company=System::shared("companyinterface");

$comp=$company->getCompanyById($user->parent_id);

$docbase=str_replace($_SERVER['DOCUMENT_ROOT'],"",str_replace("\\","/",ROOT));

header("Location: http://".$_SERVER['HTTP_HOST']."/".$docbase.$comp->company_username);
//echo "hello man";

}

}
?>