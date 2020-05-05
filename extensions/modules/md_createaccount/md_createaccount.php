<?php
function md_createaccount(){

$cont=new input;

$client_name=new input;
$client_name->setClass("form_input");
$client_name->input("text","client_name");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\">Your Name</div>{$client_name->toString()}</div>");

$client_email=new input;
$client_email->setClass("form_input");
$client_email->input("input","client_email");

$cont->generalTags("<div id=\"form_row\"><div id=\"label\">Email</div>{$client_email->toString()}</div>");

$client_phone=new input;
$client_phone->setClass("form_input");
$client_phone->input("text","client_phone");
$cont->generalTags("<div id=\"form_row\"><div id=\"label\">Phone No</div>{$client_phone->toString()}</div>");

$submit_but=new input;
$submit_but->setClass("form_button_add");
$submit_but->input("submit","register","Register");
$cont->generalTags("<div id=\"form_row\">{$submit_but->toString()}</div>");

echo $cont->toString();
}
?>