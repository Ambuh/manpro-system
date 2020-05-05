<?php
function md_facebook($id){
$width="200px";
$height="150px";
$url="";
$borders="true";

$settings=System::getModuleSettingsData($id);

if($settings!=false){
  $width=System::getArrayElementValue($settings,"set_width")."px";
  $height=System::getArrayElementValue($settings,"set_height")."px";
  $url=System::getArrayElementValue($settings,"set_url");
  if(System::getArrayElementValue($settings,"set_showborder")==0)
  $borders="false";
}

echo "<div id=\"fb-root\"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = \"//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0\";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>	
";
echo "<div class=\"fb-like-box\" data-href=\"$url\" data-colorscheme=\"light\" data-show-faces=\"true\" data-header=\"true\" data-width=\"$width\" data-height=\"$height\" data-stream=\"false\" data-show-border=\"false\"></div>";

}
?>