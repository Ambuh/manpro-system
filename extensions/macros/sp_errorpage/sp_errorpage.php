<?php 
function sp_errorpage($message,$home_link=""){ 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if(defined('NOT_DEFAULT')){ ?>
<link rel="stylesheet" href="../system/layout_css.css" type="text/css" >
<?php }else{?>
<link rel="stylesheet" href="system/layout_css.css" type="text/css" >
<?php } ?>
<title>No access</title>
</head>

<body>

<div id="error_content">
 <div id="cont">
  <?php echo $message; ?>
 </div>
 
 <?php if($home_link!=""):?>
 <div id="cont2"><a href="<?php echo $home_link; ?>">Home</a></div>
 <?php endif; ?>
</div>

</body>

</html>

<?php } ?>