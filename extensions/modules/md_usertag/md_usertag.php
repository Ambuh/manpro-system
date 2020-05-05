<?php

function md_usertag(){
	
	$pd=System::shared('proman_lib');
	
	echo '<div class="uWrap"><div class="u_row"><div class="sUser"></div><div class="txtDiv">'.$pd->ud->user_name.'</div><div class="uOpts">'.menDet().'</div></div></div><div class="nDiv"><div class="posDiv"><b>Position:</b><i>'.System::getArrayElementValue($pd->getDynamicPositions(true),$pd->ud->user_userType,'Administrator').'</i></div><div class="mNDiv" title="Click to view alerts"><div class="at_icon" id="mAll">5</div></div><div class="numb">0</div></div>';
	
	/*echo '<div style="width:100%;float:left;color:#444;text-align:left;font-size:14px;margin-top:5px;"><div id="label" style="width:80px;margin-left:5px;"><b>Position:</b></div><div class="txtDiv">'.$pd->getUserType($pd->ud->user_userType).'</div></div>';*/
	
}
function menDet(){
	$cont=new objectString;
	$cont->generalTags('<div class="mnRow" id="mc_14">Change Password<a style="display:none">Change Password</a></div>');
	$cont->generalTags('<div class="mnRow" id="mc_15">My Settings<a style="display:none">My Settings</a></div>');
	$cont->generalTags('<div class="mnRow" id="mc_6">Sign Out</div>');
	return $cont->toString();
}
?>