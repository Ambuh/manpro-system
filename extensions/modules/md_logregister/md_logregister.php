<?php
function md_logregister(){
  
	//echo getItemLists();
  
}
function getItemLists(){
    $cont= new objectString;
    
	$cont->generalTags('<div class="landScreen"><div class="introText">Construction Management System<div class="tagLine">Made in Kenya For Africa</div></div><div class="rDiv">'.showRegister().'</div><div style="width:50%;float:left;margin-left:1%;"><div class="trDiv"><div class="aWT"><div class="trophy"></div><div style="width:100%;text-align:left;font-size:16px;color:#20aa4d;">CONSTRUCTION INDUSTRY AWARDS 2018</div><br/>
	Technological innovation of the year</div></div></div></div>');
	
    $cont->generalTags('<div class="pDiv">');
    
	$cont->generalTags('<div class="qTitle">Monitor & track it all...</div>');
	
	$cont->generalTags('<div class="slt_2"><div class="sCnt"></div><div style="width:100%;float:left;text-align:center;margin-top:20px;">Theft</div></div>');

	$cont->generalTags('<div class="slt_4"><div class="lCnt"></div><div style="width:100%;float:left;text-align:center;margin-top:20px;">Ghost Workers</div></div>');
		
	$cont->generalTags('<div class="slt_1"><div class="fCnt"></div><div style="width:100%;float:left;text-align:center;margin-top:20px;">Work Done</div></div>');
	
	$cont->generalTags('<div class="slt_3"><div class="tCnt"></div><div style="width:100%;float:left;text-align:center;margin-top:20px;">Income & Expenses</div></div>');
	
	$cont->generalTags('<div style="width:90%;float:left;margin:70px 4%;border-bottom:1px solid #F1690D;"></div>');	

	$cont->generalTags(showSlider());
	
    $cont->generalTags('</div>');
	
	$cont->generalTags('<div class="regDiv">');

	$cont->generalTags('</div>');
	
    return $cont->toString();
}
function showSlider(){
	
	$cont=new objectString;
	
	$cont->generalTags('<div class="lSlider">');

	$cont->generalTags('<div class="thePop"></div>');
	
	//<div style="float:left;overflow:visible;"><div class="leftArrow"></div></div>
	
		//For theft control
	$cont->generalTags('<div class="clSlide">
	   		
		<div class="fDetails"><div class="dImage"><img class="mImage" src="'.System::getFolderBackJump().'images/Theft_Control.jpg" /></div>
		 
		 <div class="dDetails">
		 
		 <div class="dTitle">Theft Control</div>
		 
		 <div class="dText"><ul><li>Material requisition done through the system </li>
		 <li>Independent recording of materials purchased & received </li>
		 <li>Track material flow from store to site</li>
		 <li>Real time reports of materials quantities for each project</li>
		 <li>Monitor and track fuel usage</li></ul>
</div>
		 
		 </div>
		
		</div>
		</div>');
		
		//Labour Management
	$cont->generalTags('<div class="clSlide">
	   		
		<div class="fDetails"><div class="dImage"><img class="mImage" src="'.System::getFolderBackJump().'images/Labor_mgt.jpg" /></div>
		 
		 <div class="dDetails">
		 
		 <div class="dTitle">Labour Management</div>
		 
		 <div class="dText"><ul><li>Track workers on site through registers or biometrics </li>
		 <li>Compare work done to number of workers on site </li>
		 <li>Wage summary for each/ all projects</li>
		 <li>Automated payments of wages to workers Mpesa accounts </li></ul>
</div>
		 
		 </div>
		
		</div>
		</div>');
		
		
		

	//For income & expenses
	$cont->generalTags('<div class="clSlide">
	   		
		<div class="fDetails"><div class="dImage"><img class="mImage" src="'.System::getFolderBackJump().'images/Cost_control.jpg" /></div>
		 
		 <div class="dDetails">
		 
		 <div class="dTitle">Cost Monitoring</div>
		 
		 <div class="dText"><ul><li>Track income and expenses for each/ all projects </li>
		 <li>Automated price comparison of materials </li>
		 <li>Daily report of workers overtime per site</li>
		 <li>Track profit/loss for each / all projects anytime</li></ul>
</div>
		 
		 </div>
		
		</div>
		</div>');
	
		//For Project Monitoring & Control
	$cont->generalTags('<div class="clSlide">
	   		
		<div class="fDetails"><div class="dImage"><img class="mImage" src="'.System::getFolderBackJump().'images/Project_Control.jpg" /></div>
		 
		 <div class="dDetails">
		 
		 <div class="dTitle">Project Monitoring & Control</div>
		 
		 <div class="dText"><ul><li>Track multiple projects from anywhere, anytime</li>
		 <li>Break down project into components & track work done </li>
		 <li>Compare material used to BOQ estimates</li>
		 <li>Archive project records for future reference</li>
		 <li>Track tools and equipment on each project/ site</li></ul>
</div>
		 
		 </div>
		
		</div>
		</div>');
		
	//For Partnership
    $cont->generalTags('<div class="partnership"><div class="partnershipText">In Partnership With:</div><img 	height="80px" src="'.System::getFolderBackJump().'images/KFMB_Logo.png" /></div>');
		
	
	//<div class="" style="float:right;overflow:visible;"><div class="rightArrow"></div></div>
	
	$cont->generalTags('</div>');
	
	//$cont->generalTags('<div class="q_row"><div class="tutVideo"><iframe class="rVideo" id="video" width="520" height="415" src="//www.youtube.com/embed/9B7te184ZpQ?rel=0" frameborder="0" allowfullscreen></iframe></div></div>');
	
	return $cont->toString();
	
}

?>