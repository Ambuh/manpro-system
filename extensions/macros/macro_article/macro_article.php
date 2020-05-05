<?php

include("helper_article.php");

function macro_article(){

$hlp=new articleHelper;

$layout=new macro_layout;

$layout->setWidth("100%","200px");

$layout->content=$hlp->showArticle();

$layout->showLayout();

return true;
}
?>