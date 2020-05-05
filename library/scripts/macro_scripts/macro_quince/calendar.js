var today=new Date();
var currentMonth=today.getMonth();
var currentYear=today.getFullYear();
var months=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"];
var tasks=[];var tegs=[];var cased=0;

function previous(){
	var year=$("#tt").attr("data-yr");
	var month=$("#tt").attr("data-mn");
	if(month ==0){
		year-2;
		month=11;
		console.log("year");
	}else{
		month--;
	}
	showcalendar(month,year);
}
function next(){
	var year=$("#tt").attr("data-yr");
	var month=$("#tt").attr("data-mn");
	if(month ==11){
		year++;
		month=0;
	}else{
		month++;
	}
	showcalendar(month,year);
	
}
function showcalendar(month,year){
	
	
	if(cased ==0){
	for(let i=0;i<tegs.length;i++){
		tasks.push({id:i,tsk:tegs[i][1],dt:new Date(tegs[i][2]),tim:tegs[i][3]});
		cased++;
	}
	}
	
	
	var firstdDay=new Date(year,month).getDay();
	
	$("#tt").attr("data-yr",year).attr("data-mn",month);
	
	var daysInMonth=32-new Date(year,month,32).getDate();
	
	
	$("#calendar").html(" ").addClass("w3-border");
	
	let date=1;
	
	$("#monthyear").html(months[month]+" "+year);
	
	for(let i=0;i<5;i++){
		$("#calendar").append("<tr id='row_"+i+"'></tr>");
		for(let j=0;j<7;j++){
			if(i==0 && j < firstdDay){
				$("#calendar #row_"+i).append("<td class=' w3-border' style='width:12%'></td>");
				
			}else if(date <= daysInMonth){
				if(date==today.getDate() && month== today.getMonth()){
						$("#calendar #row_"+i).append("<td class='w3-hover-yellow w3-center w3-border w3-blue w3-border' style='width:12%'><div class='time'>"+date+"</div></td>");
					 timeFunction();
				}else{
					
					$("#calendar #row_"+i).append("<td class='w3-hover-yellow w3-center w3-border' style='width:12%'>"+checkTsk(month,date,year)+"</td>");
					timeFunction();
				}
				
				date++;
			}	
			
		}
	}
}
function weekly(date ,month,year){
	"use strict";
	$("#mime table").html(" ");
	var days=["Monday","tuesday","wednesday","thursday","friday","saturday","sunday"];
	$("#monthyear").html("Week 1");
	
	for(let i=0;i<days.length;i++){
		$("#mime table").append("<th class='w3-uppercase w3-border'>"+days[i]+"</th>");
	}
	$("#mime table").append("<tr>");
	
	if(date ==undefined)
		date=today.getDay();
	if(month==undefined)
		month=today.getMonth();
	if(year==undefined)
		year=today.getFullYear();
	
	
	for(let i=0;i<days.length;i++){
		if((date-1)==i){
			
			$("#mime table tr").append("<td class='w3-border w3-blue'>"+checkTsk(month,today.getDate(),year)+"</td>");
		}else{
			$("#mime table tr").append("<td class='w3-border'>"+checkTsk(month,(today.getDate()+i),year)+"</td>");
		}
		
	}
	
}
function daily(date,month){
	
	$("#mime table").html(" ");
	$("#mime table ").append("<tr class='w3-border'><th style='width:20%;float:left' class='w3-border'>Time</th><th style='width:80%;float:left' class='w3-border'>Activity</th></tr>");
	 var timelines=[];
	var months=["January","Feburuary","March","April","May","June","July","August","September","October","November","December"];
	if(month ==undefined)
		month=today.getMonth();
	if(date== undefined)
		date=today.getDate();
	
	$("#monthyear").html(date+" "+months[month]+" "+today.getFullYear());
	
	for(let i=0;i<23;i++){
		let tml=i+":00-"+(i+1)+":00";
		
		timelines.push({hr:i,tm:tml,act:" "});
	}
		
	for(let i=0;i<timelines.length;i++){
		
	if(today.getHours()==timelines[i].hr){
		$("#mime table ").append("<tr id='row_"+i+"' class='w3-blue w3-border' ></tr>");
		$('#mime table #row_'+i).append("<td  style='width:20%;float:left' class='w3-border-right'>"+timelines[i].tm+"</td><td style='width:80%;float:left'></td>");
	}else{
		$("#mime table ").append("<tr id='row_"+i+"' class='w3-border' s></tr>");
		$('#mime table #row_'+i).append("<td  style='width:20%;float:left' class='w3-border-right'>"+timelines[i].tm+"</td><td  style='width:80%;float:left'></td>");
	}
		
	}
}
function monthly(){
	
	var dt=new Date();
	
	$("#mime table").html(" ");
	var days=["Sun","Mon","tue","wed","thur","fri","sat"];
	$("#monthyear").html("Weekly ");
	
	for(let i=0;i<days.length;i++){
		$("#mime table").append("<th class='w3-uppercase w3-border w3-center'>"+days[i].toUpperCase()+"</th>");
	}
	
	
	$("#mime table").append("<tbody id='calendar'></div>");
	showcalendar(dt.getMonth(),dt.getFullYear());
}
function checkTsk(month,date,year){   
	let caser="<div class='time'> "+date+"</div>";
	
	
	
	for(i=0;i<tasks.length;i++){
		if(tasks[i].dt.getDate()==date && tasks[i].dt.getMonth() ==month  && tasks[i].dt.getFullYear() ==year){
			if(tasks[i].tim==null)
			caser="<div class='w3-left w3-full time'>"+date+"</div><div class='w3-left w3-full w3-green w3-center'>"+tasks[i].tsk+"<div class='w3-right'></div></div>";
			else
				caser="<div class='w3-left w3-full time'>"+date+"</div><div class='w3-left w3-full w3-green w3-center'>"+tasks[i].tsk+"<div class='w3-right'>"+tasks[i].tim+"</div></div>";
		}
		
	}
	
	return caser;
}
function timeFunction(){
	"use strict";
	
	$("#calendar td div.time").unbind().click(function(){
		daily($(this).html(),$("#tt").attr("data-cm"),$("#tt").attr("data-yr"));
	});
}
function listDisplay(){
	var months=["January","Feburuary","March","April","May","June","July","August","September","October","November","December"];
	$("#mime").html("<table class='w3-table' ><th>Task Name</th><th> Date </th></table>");
	for(let i=0;i<tasks.length;i++){
		$("#mime table ").append("<tr id='row_"+i+"'></tr>");
		$("#mime #row_"+i).html("<td>"+tasks[i].tsk+"</td><td>"+tasks[i].dt.getDate()+"/"+months[tasks[i].dt.getMonth()]+" /"+tasks[i].dt.getFullYear()+"</td>");
		
	}
}
function getMaxDate(){
	if(limDate !=undefined){
		return limDate;
	}
}
/*
 public function calendarFunction(){
		  $cont=new  objectString();
		  
		  $cont->generalTags("<input type='hidden' id='tt' data-cm='0' data-cd='".date("d")."' data-cs='0'>");
		  
		 // $cont->generalTags("<div class='a3-left a3-responsive' style='width:80%'>");
		  
		  $cont->generalTags("<div class='a3-right a3-responsive' style='width:50%'>
  		  <div class='a3-left a3-full header-controls'>
  		    <div class='a3-left a3-padding' style='width: 20%;'>
  		    	<div class='a3-left sm-padding a3-btn fa fa-arrow-left' onClick='previous()'></div>
  		     <div class='a3-left sm-padding a3-btn fa fa-arrow-right' onClick='next()'></div>
  		  
  		  	<div class='a3-left sm-padding a3-btn' onClick='getCurrentTable()'>Today</div>
  		  	</div>
            <div class='a3-left a3-center sm-padding a3-fn a3-fnt-21' id='monthyear' style='width: 50%;'>January 24 2018</div>
  		  	<div class='a3-right' style='width: 20%;'>
  		  		<div class='a3-left sm-padding' style='cursor:pointer;margin:3px;' onclick=monthly()>Month</div>
  		  		<div class='a3-left sm-padding' style='cursor:pointer;margin:3px;' onclick=weekly()>Week</div>
  		  		<div class='a3-left sm-padding' style='cursor:pointer;margin:3px;' onclick=daily()>Day</div>
  		  		<div class='a3-left sm-padding' style='cursor:pointer;margin:3px;' onclick=listDisplay()>List</div>
  		  	</div>
  		  </div>
  		  <div class='a3-left a3-full' id='mime'>
  		  	<table class='a3-table a3-border'>
  		  		
  		  			<tr class='a3-ful '>
  		  				<th class='a3-center a3-capitalise a3-border'>Sun</th>
  		  				<th class='a3-center a3-capitalise a3-border'>mon</th>
  		  				<th class='a3-center a3-capitalise a3-border'>tue</th>
  		  				<th class='a3-center a3-capitalise a3-border'>wed</th>
  		  				<th class='a3-center a3-capitalise a3-border'>thur</th>
  		  				<th class='a3-center a3-capitalise a3-border'>fri</th>
  		  				<th class='a3-center a3-capitalise a3-border'>Sat</th>
  		  			</tr>
  		  		
  		  		<tbody id='calendar'></tbody>
  		  	</table>
  		  </div>
  		</div>");
		  
		  return $cont->toString();
		  
      }
      
      public function userLayout(){
		  $cont=new objectString();
		  
		  $arr=array();
		  
		  $data=array();//$data=$this->gbs->getdbResource("where extdata2=".$this->ud->user_id." and type=8");
		  
		  foreach($data as $tm){
			  $arr[]=array($tm->id,$tm->name,$tm->extData1,$tm->extData3);
		  }
		  
		  $cont->generalTags("");
		  
		  $cont->generalTags("<script>$(document).ready(function(){
		  tegs=".json_encode($arr).";showcalendar(currentMonth,currentYear);});</script>");
		  
		  $cont->generalTags($this->calendarFunction());
		  
		  $cont->generalTags("<div class='a3-padding a3-border a3-hover-green a3-left a3-margin-left a3-margin-right a3-round in-bt' id='cEv_1'>Create Event</div>");
		  
		  
		  
		  
		  
		  return $cont->toString();
		 
        }

*/