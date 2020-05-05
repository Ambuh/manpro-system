<?php
mysqli_connect("localhost","root","root");
mysqli_selectdb("hrm_db");
$newpass=sha1("admin");
mysqli_query("update users set password='".$newpass."'");
?>