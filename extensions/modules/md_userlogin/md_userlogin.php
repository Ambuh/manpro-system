<?php
function md_userlogin(){
	$input=new input;
	$cont=new objectString;
	
	if(!defined("USER_LOGGED")){
	$input->setId('u_email');
	$input->setClass("form_input");
	$input->input("text","u_email",System::getArrayElementValue($_POST,'u_email',''));
	/*$cont->generalTags("<div id=\"module_title\" style=\"margin-top:0px;margin-bottom:0px;margin-bottom:2px;color:#fff;\">User Login</div>");*/

        $cont->generalTags('<div id="userArea">');

        $cont->generalTags("<span class='thePop'></span>");

        $cont->generalTags("<section id='login-section'>");

        $cont->generalTags("<button class='a3-left a3-half a3-padding user-controls' id='logs'>Login</button><button id='rgst' class='a3-left a3-half a3-padding active user-controls'>Register</button><section class='conts'>");

        $cont->generalTags("<form action=\"".str_replace("Sign_Out/","",$_SERVER['REQUEST_URI'])."\" method=\"post\" id='login'>");

        $cont->generalTags("<div style=\"float:left;overflow:hidden;width:98%;margin-left:1%;margin-top:3px;\">".USR_MESSAGE."</div>");
	$pass=new input;
	$pass->setId('r_pass');
	$pass->setClass("form_input");
	$pass->input("password","u_pass");
	/*$cont->generalTags("<div id=\"form_row\" style=\"margin-top:2px;\"></div>");*/
	
	$submit_button=new input;
	//$submit_button->setTagOptions("style=\"margin-left:10px;margin-top:-1px;width:200px;background:#20aa4d;float:right;padding:7px 20px!important;box-shadow:none;\"");
	$submit_button->setClass("form_button_add");
	$submit_button->input("submit","USR_SUBMIT","Log In");
	
	$reset_btn=new input;
	$reset_btn->setId('reset_btn');
	$reset_btn->setClass("form_button");
	//$reset_btn->setTagOptions("style=\"margin-left:20px;float:left;margin-top:0px;\"");
	$reset_btn->input("Reset","resetbtn","Cancel");
	/*$cont->generalTags("<div id=\"form_row\" style=\"margin-bottom:0px;margin-top:0px;\">{$submit_button->toString()}{$reset_btn->toString()}</div>");*/


    
	$cont->generalTags('<section class="input-user"><div id="label" >Email</div>'.$input->toString()."</section>");

    $cont->generalTags('<section class="input-user"><div id="label" >Password</div>'.$pass->toString()."</section>");
		
	$cont->generalTags('<section class="input-user">'.$submit_button->toString()."</section>");
		
	$cont->generalTags('<div class="passRe" style="font-size:12px;margin-top:10px;margin-bottom:0px;float:left;cursor:pointer;"><div class="resPass" style="float:left;width:auto;float:left;text-indent:0px;">Forgot Password?</div></div>');
		

		
   $logid=new input;
	$logid->setId('logid');		
	$logid->input("hidden","logid",time());
	$cont->generalTags($logid->toString());

	$cont->generalTags("</form>");

	$cont->generalTags("<form id='register'>".showRegister()."</form>");


	$cont->generalTags("</div>");



	$cont->generalTags("</section>");

	$cont->generalTags("</section>");

	echo $cont->toString();
	}else{
		if(preg_match("/sch_/i",USER_LOGGED)){
		  $cont->generalTags("<div id=\"module_title\" style=\"margin-top:15px;margin-bottom:0px;margin-bottom:5px;\">User</div>");
		  $vals=explode("_",USER_LOGGED);

		}
	}
}
function showRegister(){

    $cont=new objectString;

    $cont->generalTags('<input type="hidden" id="m_url" value="'.System::ajaxUrl(OPTION_MODULES,'md_logregister').'"/>');

    $cont->generalTags('<div class="regTitle" style="color:#fff;">Get a Free Trial Account</div>');

    $yname=new input;

    $yname->setId('yname');

    $yname->setClass('txtField');

    $yname->input('text','yname');

    $cont->generalTags('<div class="q_row"><div id="label">Your Name</div>'.$yname->toString().'</div>');

    $cname=new input;

    $cname->setId('cname');

    $cname->setClass('txtField');

    $cname->input('text','cname');

    $cont->generalTags('<div class="q_row"><div id="label">Company</div>'.$cname->toString().'</div>');

    $yphone=new input;

    $yphone->setId('yphone');

    $yphone->setClass('txtField');

    $yphone->input('text','yphone');

    $cont->generalTags('<div class="q_row"><div id="label">Phone No.</div>'.$yphone->toString().'</div>');

    $yemail=new input;

    $yemail->setId('yemail');

    $yemail->setClass('txtField');

    $yemail->input('text','yphone');

    $cont->generalTags('<div class="q_row"><div id="label">Your Email</div>'.$yemail->toString().'</div>');

    $cont->generalTags('<div style="width:90%;margin:10px 5%;float:left;padding:10px 0px;border-bottom:1px solid #777;color:#fff;font-size:15px;text-align:center;"></div>');

    $pass=new input;

    $pass->setId('pass');

    $pass->setClass('txtField');

    $pass->input('password','pass');

    $cont->generalTags('<div class="q_row"><div id="label">Password</div>'.$pass->toString().'</div>');

    $rpass=new input;

    $rpass->setId('rpass');

    $rpass->setClass('txtField');

    $rpass->input('password','rpass');

    $cont->generalTags('<div class="q_row"><div id="label" style="margin-top:0px;">Rept. Password</div>'.$rpass->toString().'</div>');

    $regBtn=new input;

    $regBtn->setId('regUsers');

    $regBtn->setClass('form_button_add');

    $regBtn->input('button','sbut','Register');

    $cont->generalTags('<div class="q_row">'.$regBtn->toString().'</div>');

    return $cont->toString();

}
?>