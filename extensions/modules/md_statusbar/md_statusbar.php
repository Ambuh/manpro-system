<?php
function md_statusBar(){
GLOBAL $db;

$messages=new system_messages;

$User=$db->getUserSession();

$messages->markAsRead();

$mess=$messages->getMyMessages($User->id);

?>
<div id="user_title"><div style="float:left; height:15px; width:275px;"></div><strong style="margin-left:0px;">User: </strong><?php echo $User->firstname." ".$User->lastname; ?><strong style="margin-left:10px">Username: </strong><?php echo $User->username; ?></div><div id="user_title"><strong style="margin-left:10px;">Notifications:</strong></div><?php echo count($mess)==0 ? "<div style=\"float:left;margin:3px;\">0</div>": MsgBox($mess);?> <div id="user_title" style="float:right; margin-right:10px;"><strong style="margin-left:10px;">Date:</strong><?php echo System::getCurrentdate(true);?></div>
<?php 
}
function MsgBox($mess){

$mes="";

for($i=0;$i<count($mess);$i++)
$mes.="<div id=\"irow\" style=\"font-weight:normal;font-size:12px;margin-bottom:0px;overflow:hidden;\"><a href=\"{$mess[$i]->message_actionLink}{$mess[$i]->message_id}\">{$mess[$i]->message_title}</a></div>";

return  "<div id=\"pend\" class=\"point\" style=\"float:left;margin:2px 0px 0px 2px;\" onclick=\"showHideDiv('mesbox','block')\">(".count($mess).")<div class=\"mesbox\" id=\"mesbox\" ><div id=\"box-top\">Notifications <div id=\"closebn\" onmousedown=\"showHideDiv('mesbox','none')\" >Close X</div></div>$mes</div>";

}

?>