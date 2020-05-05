  var menu=0;
  var screen_handler=0;
$(document).ready(function(){
 "use strict";
  var reg_data;
  var log_data;
	
	$(".adDiv").addClass("am_sit");
	
	var width=$(window).width();
	
 		menu_mouse();
	
	    screen_handler=1;
	
	$(".am_upgrade").click(function(){
		
		revolt();
		
	});
	
	 $("#hem_control").click(function(){
		 log_data=$(".m_login").html();
		 reg_data=$(".rDiv").html();
		  var funct=function(){
			   $(".regUsers").click(function(){
						 asRegisterNewUser(); 
					  });
			$("#wrapper").unbind();
			//$("")
			$(".resPass").click(function(){
				asistResetPassword();
			}); 
			  $(".w3-fit #hem_control").html("");
			  $(".w3-lg-2").css("height","50%");
			  $(".register").click(function(){
				  var regist=function(){
					  $(".regUsers").click(function(){
						 asRegisterNewUser(); 
					  });
					  $(".regTitle").css("color","black");$(".w3-lg-2").css("height","119%");
					  $(".w3-fit #hem_control").html("");
					  $(".login").click(function(){
						  $(".w3-lg-2").css("height","50%")
								  var login=function(){
                                     $(".w3-fit #hem_control").html("");
								  };
								  var obj={cont:log_data,func:login};
										  shownWrapper(obj);
			           });
				  };
				  var obj={cont:reg_data,func:regist};
				  shownWrapper(obj);
			  });
			  $(".login").click(function(){
				  $(".w3-lg-2").css("height","50%")
				$(".w3-fit #hem_control").html("");
				  var login=function(){
					  
					  $(".register").click(function(){$(".regTitle").css("color","black");$(".w3-lg-2").css("height","110%");
							  var regist=function(){
								   $(".regUsers").click(function(){
						 asRegisterNewUser(); 
					  });
								  $(".login").click(function(){
											  var login=function(){

											  };
											  var obj={cont:log_data,func:login};
													  shownWrapper(obj);
								   });
							  };
								  var obj={cont:reg_data,func:regist};
								  shownWrapper(obj);
			           });
				  };
				  var obj={cont:log_data,func:login};
				  shownWrapper(obj);
			  });
		  };
		  var content="<div class='w3-control w3-fit' ><div class='w3-header'><span class='left login'>Login</span><span class='right register' style='color:#F1690D'>Register</span></div>"+$(".m_login").html()+"</div>";
		  var obj={cont:content,func:funct};
		  showWrapper(obj);
		 // popWindow(".thePop",display_data);

	  });

});
function getPhoneAttr(){
	"use strict";
	var w = window.innerWidth|| document.documentElement.clientWidth || document.body.clientWidth;
	return w;
}
function display_data(){
	"use strict";
	var data=$(".m_login").html();
	$('.thePop').html("<div id='tt_upgrade'><div class='respHeader' ><div class='respItem' id='login'>Login</div><div class='respItem' id='register'>Regsiter</div></div>"+data+"</div>");
	//alert(data);
	 $(" #register").click(function(){
		 $("#tt_upgrade").html("<div class='reg '>"+$(".rDiv").html()+"</div>");

	  });
	return data;
}
function regUsers(){
	"use strict";
	$("#regUsers").click(function(){
        $("#regUsers").css("background-color","yellow").fadeToggle("slow");
	});
}
function menu_clicks(){
	"use strict";
  $(".menu_wrapp").fadeOut("slow",function(){
	 $(".quince_content").fadeIn("fast");
  });
}
function revolt(){
	"use strict";
	$(".menu_wrapp").fadeToggle("slow",function(){
		$(".quince_content").fadeToggle("fast");
	});
}
function menu_mouse(){
	"use strict";
	$(".menu_wrapp").mouseover(function(){
		menu=1;
	});
	$(".menu_wrapp").mouseout(function(){

	});
}
function runAssist(){
	"use strict";
	
  	req_Confirm_btn();runMenus();innerCompany();updatedUiFunctions();rawInnerFunction();
	var elem=0;

	$(".btn-project").unbind().click(function () {
        updatedProjectLevelFunctions(this);
	});

	$(".aQuince_select").unbind().change(function(){
		bindedTaskFunction(this);
	});

	$(".in-inputs").unbind().keyup(function(){
		updatedMaterialTemplateFunction(this);
	});
	$(".task").unbind().click(function(){
		updatedTaskCreation(this);
	});
     $(".tab-set").unbind().click(function(){
         materialManagement(this);
	});
     
     $(".tasks").unbind().click(function(){
     	 bindedTaskFunction(this);
	 });
     
    $(".mat_select").unbind().change(function () {
		materialUpdateManagment(this);
	});
    $(".opt_select").unbind().click(function () {
		materialUpdateManagment(this);
	})
	$("#mRec select[name='proj']").unbind().change(function(){
		updatedMaterialTemplateFunction(this);
	});

	$(".up_select").unbind().change(function(){
        updatedSelections(this);
	});
	$(".tab-equip,.equip-control").unbind().click(function(){
		updatedEquipmentFunction(this);
	});

	$(" .xpand3").unbind().click(function(){
       $("#"+$($(this).parent()).parent().attr("id") +" .a3-hidden").fadeToggle("slow");
	});

    $(".edit").unbind().click(function(){
		var grent=$(this).parent().attr("id");
        var pself=$(this).attr("id");
        $(" #"+$(this).parent().attr("id")+" #"+$(this).attr("id")).html("<input type='text' placeholder=' ' class='ltxt' id='"+$(this).attr("id")+"'>");
         $(".ltxt").focus();
		 /*$(".ltxt").mouseout(function(){
			 let  c_val=$(this).val().trim();
			 if(c_val !==""){
			      $(" #"+grent+" #"+pself).html(c_val);
			 }else{
			     $(" #"+grent+" #"+pself).html(" Click here");
			 }
				 
            
         });*/
		$(".ltxt").blur(function(){
            var c_val=$(this).val().trim();
			if(c_val !=="")
				 $(" #"+grent+" #"+pself).html(c_val);
			 else
				 $(" #"+grent+" #"+pself).html(" Click here");
            
         });
	});
	
	$(".mater").unbind().click(function(){
		updatedMaterialTemplateFunction(this);
	});
	
	$(".a3-radio").unbind().click(function(){
		updatedRadioFunctions(this);
	});
	$(".schSHw").unbind().click(function(){
		updatedScheduleOfWorks(this);
	});
	$(".a3-tab").unbind().click(function(){
		updatedTabletWorsSpace(this);
	});
	$(".checks").unbind().click(function(){
		if($(this).attr("id")=="create"){
			$("#update").prop("checked",false)
		}else{
			$("#create").prop("checked", false)
		}
		//$(".checks").attr("checked","false");
	})
    $(".am_btn").unbind().on("click",function(){  createTableElement().then(function(){
		$("#cat_oth .a3-hidden").fadeIn("fast");
	}) });
	
	$(".delete").unbind();
	$(".delete").click(function(){
		deleteCompanyRequest();
	});
}

function runCheck(btn){

	var prent=	$(btn).parent().attr("id");

	  var gprent=$(".dCont #"+prent).parent().attr("id");

		 var items= $(".dCont #"+gprent+" .cells ").toArray();

		  var cid=$(items[2]).attr("id");

			 if($(".dCont #"+gprent+" #"+cid+" div").attr("id").split("_")[0] ==="edm"){

				    if($("#"+$(items[1]).attr("id")+" input").val() !==""){

									$(".dCont #"+gprent+" #"+cid+" div").removeClass("edm").addClass("ch_a").css("float","right");

				       }else{

							 $(".dCont #"+gprent+" #"+cid+" div").removeClass("ch_a").addClass("edm");

				 }

			 }



}
function req_Confirm_btn(){
	"use strict";
	
	           $("#s_id_3").unbind();
	             $("#s_id_3").click(function(){
					if($(".qs_wrap select").length==1){
						if($(".qs_wrap select").val() <0){
							var obj={cont:"Please select a site",func:function(){
								
								$(".qs_wrap ").css("border-color","red");
								setTimeout(function(){
									$(".qs_wrap ").css("border-color","#bbb");
								},7000);
								$(".qs_wrap").mouseover(function(){
									$(".qs_wrap ").css("border-color","#bbb");
								});
							}};
							promtUser(obj);
						}else{
						
							$(".innerTitle").attr("data-prj",$("select.quince_select").val());
							let dt=getTableData();
	                        
							
							table_get(dt.dt,dt.hdItem);
							
						}
					}else{
						
						$(".innerTitle").attr("data-prj",$("select.quince_select").val());
						let dt=getTableData();
						
							
						 table_get(dt.dt,dt.hdItem);
					}
                         
                       
	  });
}
function getScreenWidth(){
	 "use strict";
	 return $(window).width();
 }
function wrap_close(){
	"use strict";

	 var wrap=document.getElementById("wrapper");
  $("#cancel").click(function(){
	$("#wrapper").fadeOut("slow");
  });
  $("#submit").click(function(){
      table_submision();

  });

}
function table_get($arr,$headrArr=null){
    
	
	if($arr.length >0){
		
		$(".dlMp").html("<div class='a3-left a3-padding-left a3-margin-left a3-text-green'>List of materials being requisitioned </div><div class='a3-right '><span class='a3-padding a3-margin-left a3-margin-right a3-blue a3-round a3-left a3-hover-green mats' id='conf' onclick=table_submision(this)>Confirm</span><span class='a3-padding a3-margin-right a3-red a3-round a3-left mats men_tab2' id='inMen_7'>Cancel</span></div>");
		activateMenuItem();
		
		$("#s_id_3,.tbl_ins").fadeOut();
		
		$("#Reflist div").remove();

		$("#Reflist").append("<div class='mn_tables' id='table_id'><div id='header_row1'></div><div id='header_row'></div></div>")

		$("#header_row").append($headrArr);
		
		
		$arr.forEach( (element,key)=>{
			let arr=[];
			
			$headrArr.forEach( (cell,num)=>{

				let nm= "<div class='cells' id='cl_"+key+"_0' style='width:"+$(cell).width()+"px'>"+(key+1)+"</div>";

				(num !=0) ? nm="<div class='cells' id='cl_"+key+"_"+num+"_edit' style='width:"+$(cell).width()+"px'>"+element[num-1]+"</div>" :null ;
				
				arr.push(nm);
			});

			arr.push("<div class='cells'><i class='fas fa-trash del'></i></div>");
		   
			
			$("#table_id").append("<div class='trow al"+(p=(key%2 =="") ? 2:1 )+"' id='listid_"+(key+1)+"'>"+arr.join(" ")+"</div>");
            loadInnerFunc();delfunction().then(tableCheck());
		});
		
		
		
	}
}

function edits_removal(){
	"use strict";

	$(" .cells .ed").click(function(){
		var selector=$(this).parent().attr("id").split("_")[1];
		 var cells =$(".m_tables #lstid-"+selector+" .cells").toArray();
		  var c_data= $(cells[1]).html();

		  $(cells[1]).html("<input type='text'  placeholder='"+c_data+"' class='lassist' id='lstid-"+selector+"' />");
		  $(".m_tables .lassist").mouseout(function(){
			  var $id=$(this).attr("id");

			   var up_data= $(".m_tables #"+$id +" .cells .lassist").val();
				 var u_items= $(".m_tables #"+$id +" .cells ").toArray();
				  if(up_data !==""){
					$(u_items[1]).html(up_data);
				  }else{
                    $(u_items[1]).html(c_data);
				  }
				 });

		});
		$(" .cells .del").click(function(){
			var current=this;
			var selector=$(this).parent().attr("id").split("_")[1];
			$(".m_tables #lstid-"+selector).animate({width:0,height:0},1000,function(){
				  $(this).remove();
				  var p_items=$(".dCont .trows").toArray();
				  var h_items=$(current).attr("id");
				  var rows=$(".m_tables .trow").toArray();
                   for(var i=0;i<rows.length;i++){
					   $(".dCont ."+$(rows[i].attr("class")+" #"+h_items));
					   //work with that vatiable to get the parnt in the dcont then remove the values that are in the elements and change the img url
				   }
				  if(rows.length==0){
					  $("#wrapper").fadeOut("fast");
				  }
			});

		});
}

function customRowFunction(){
    "use strict";
   		$('.cells').unbind();
		$('.cells').click(function(){
		activateCells(this);
	    });

	    $('.cells,.xMenu').contextmenu(function(){
		return false;
	})

    $('.cells').mousedown(function(ev){
		cRow=$(this).parent().attr('id');
		if(ev.which===3){
			try{
			leftVal = ev.pageX - ($('.xMenu').width()) + "px";
            topVal = ev.pageY + "px";
            $('.xMenu').css({ left: leftVal, top: topVal });
			$('.xMenu').show('fast',function(){$('.xMenuInner').fadeIn('fast');});
			}catch(e){alert(e);}
		}
	});

   activateDeleteFunction();

}
function am_submit_btn(){
    "use strict";
    $('.cells').click(function(){
        var grent=$(this).parent().attr("id");
        var pself=$(this).attr("id");
        $(".tble_am #"+$(this).parent().attr("id")+" #"+$(this).attr("id")).html("<input type='text' placeholder=' ' class='ltxt' id='"+$(this).attr("id")+"'>");
         $(".ltxt").focus();
		 $(".ltxt").mouseout(function(){
            var c_val=$(this).val();
            $(".tble_am #"+grent+" #"+pself).html(c_val);
         });
		$(".ltxt").blur(function(){
            var c_val=$(this).val();
            $(".tble_am #"+grent+" #"+pself).html(c_val);
         });
    });
}
function table_submision(btn){
  "use strict";
    let dt=[]; dt=items_get();
    
    var today = new Date();
	
    let currDate=(String(today.getMonth() + 1).padStart(2, '0'))+'/' +(String(today.getDate()).padStart(2, '0'))+'/'+(today.getFullYear());
    
    let params={dat:JSON.stringify(dt),const:2,st:$("select[name=selectSite]").val(),sId:$(".innerTitle").attr("data-prj"),tDate:currDate,comp:$("select[name=selectComponent]").val()};
    
	loadingSpinner("#quince_inner_content","<div class='response a3-left'></div><span class='a3-left a3-full'><div style='padding: 15% !important;' class='a3-padding a3-left a3-margin a3-full a3-center a3-text-orange'><i class='fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom'></i></div></span>");

	if(dt.length !==0){
       $.post($('#main_url').val()+'&sq=77',params,function(data){
				if(getScreenWidth() < 670){revolt(); }else{
					var ob=jParser(data);
		
                    if(ob.Status=="Success"){
                        
						    $(".response").html(ob.Message);
                        
							$("#quince_inner_content span").html(ob.Content);
						    
                        runAssist();loadInnerFunc();
					}
				}
		});
	}
	
}
function showWrapper(obj){
	"use strict";

	$("#wrapper").fadeIn("slow",function(){

		$("#wrapper ").html("<div class='cont'></div>");

		var $cont=$("#wrapper .cont");

		$cont.append("<div class='w3-lg-2'>"+obj.cont);

		$cont.append("<div class='w3-closer'><div class='w3-sel close'>X</div></div>");
        obj.func();
		closeWrapper($cont);
		showDisclaimer();
		wrapperControl(executeWrapper);
	});
}
function closeWrapper(obj){
	"use strict";

	$(".close").unbind();

    $(".close").click(function(){

		$("#"+$(obj).parent().attr("id")).fadeOut("fast");

	 });

}
function showDisclaimer(){
	"use strict";
	$("#wrapper a").click(function(){
		$(".disc").fadeToggle("slow");
	});
}
function  wrapperControl(func){
	"use strict";

	$(".btn").click(function(){
		func(this);
	});
}
function executeWrapper(btn){
	"use strict";
	var params={cons:5};
	switch($(btn).attr("id")){

		case 'mn_1':

		  params={const:5,rIp:1};

			break;

		case 'nm_2':

			params={const:5,rIp:2};

			break;

      case 'nm_3':
      /*test confirmed on passing function as variables then using them in cases when we are applying wrappers and functions that we need to make universal for reuse purposes
  var funci=function(){
    alert("is_working");
  } */
      params={const:6,func:funci,rip:1};

      break;
	}
	$.post($('#main_url').val()+'&sq=77',params,function(data){

	});
}
function runMenus(){
	"use strict";
	
	var width=$(window).width();
	
	if(width < 670){
		
		if($('.menu_wrapp').css("display") !="none"){
	
			$('.menu_wrapp').fadeOut(500,function(){
			   $(".quince_content").fadeIn("fast");
		  });
		}
 }
	
}
function asistResetPassword(){
	"use strict";
	
	$(".cont .w3-lg-2").html('<i style="text-align:center;font-size:20px;float:left;margin-top:120px;width:100%;">Loading....</i>');
			  $.post($('#m_url').val()+'&sq=5',{},function(data){
				
				var ob=jParser(data);
				
				if(ob.Status=="Success"){
					var dect=function(){
						$('#resBnn').click(function(){
						     asrequestReset(this);
					     });
					};
					var obj={cont:ob.Content,func:dect};
					shownWrapper(obj);
					
					
				}
				
			});
					 
	
		   
		   
}
function shownWrapper(obj){
	"use strict";
	var content="<div class='w3-control w3-fit' ><div class='w3-header'><span class='left login'>Login</span><span class='right register' style='color:#F1690D'>Register</span></div>"+obj.cont+"</div>";
	$("#wrapper .cont .w3-lg-2").html(content);
	obj.func();
	
}
function asrequestReset(btn){
	if($(btn).val()=='Processing...')
		return;
	
	$('#cemail').css('border','1px solid #bbb');
	
	 if($('#cemail').val()==""){
		$('#cemail').css('border','1px solid #f00'); 
		 return;
	 }
	
	 $(btn).val('Processing...');
	
	$.post($('#m_url').val()+'&sq=6',{email : $('#cemail').val()},function(data){
	    
		var ob=jParser(data);
		
		if(ob.Status=="Success"){
		    $('.cont .w3-lg-2').html(ob.Content);
			$('#resBnn2').click(function(){
				$('#rcode').css('border','1px solid #bbb');
				if($('#rcode').val()==""){
					$('#rcode').css('border','1px solid #f00');
					return;
				}
				
				$(this).val('Processing...');
				
				var theEmail=$('.resetEmail').html();
				
				$.post($('#m_url').val()+'&sq=7',{resetcode : $('#rcode').val(),rEmail : $('.resetEmail').html()},function(data){
					
					var ob = jParser(data);
		
				    if(ob.Status=='Success'){
					   $('.cont .w3-lg-2').html(ob.Content);
					   $('#resBnn3').click(function(){
						  
						   var status=true;
						   $('#pass1').css('border','1px solid #bbb');
						   $('#pass2').css('border','1px solid #bbb');
						   
						   if($('#pass1').val()==""){
						      $('#pass1').css('border','1px solid #f00');
							  status=false;
						   }
						   if($('#pass2').val()==""){
						      $('#pass2').css('border','1px solid #f00');
							   status=false;
						   }
						   
						   if($('#pass1').val()!=$('#pass2').val()){
							  alerts('Password Mismatch!');
							   status=false;
							}
						   
						   $(this).val('Processing...');
						   
						   if(!status)
							   return status;
						   
						   $.post($('#m_url').val()+'&sq=8',{pass1 : $('#pass1').val(),pass2 : $('#pass2').val(),rEmail : theEmail },function(data){
							   var ob=jParser(data);
							   if(ob.Status=="Success"){
								   $('.cont .w3-lg-2').html(ob.Content);
							   }
						   });
						   
					   });
					}else{
					   showMessage(ob.Content);	
					}
					
				});
				
			});
		}else{
			showMessage(ob.Content);
			$(btn).val('Request password');
		}
		
	});
	
}
function asRegisterNewUser(){
	'use strict';
	
	var vals=$('.rDiv .txtField').toArray();
	
	var status=true;
	
	for(var i=0;i<vals.length;i++){
		if($(vals[i]).val()==""){
			$(vals[i]).css('border','1px solid #f00');
			status=false;
		}else{
			$(vals[i]).css('border','1px solid #bbb');
		}
	}
	
	if(!status){
		alert('Complete all fields!');
		return;
	}
	
	$(btn).val('Processing...');
	$('.rDiv .txtField').attr('disabled',true);
	$('.rDiv .txtField').css('opacity','0.5');
	$('.rDiv #label').css('opacity','0.5');
	
	$.post($('#m_url').val()+'&sq=1',{YName : $('#yname').val(),YEmail : $('#yemail').val(),YCompany : $('#cname').val(),YPhone:$('#yphone').val(),YPass:$('#pass').val()},function(data){
		
		$(btn).val('Register');
	    $('.rDiv .txtField').attr('disabled',false);
	    $('.rDiv .txtField').css('opacity','1');
	    $('.rDiv #label').css('opacity','1');
		
		var ob=jParser(data);
		
		var register=function(){
			if(ob.Status==='Success'){
			   $('.w3-lg-2').html(ob.Content);
			   
				$.post($('#m_url').val()+'&sq=2',{YName : $('#yname').val(),YEmail : $('#yemail').val(),YCompany : $('#cname').val(),YPhone:$('#yphone').val(),YPass:$('#pass').val()},function(dat){
				   var cob=jParser(dat);
				   if(cob.Status==='Success'){
				     $('.w3-lg-2').html(cob.Content);
					 
					 $.post($('#m_url').val()+'&sq=3',{FName : $('#yname').val().split(' ')[0],SName : $('#yname').val().split(' ')[1],YEmail : $('#yemail').val(),companyId : cob.TopMenu,YPhone:$('#yphone').val(),YCompany : $('#cname').val(),YPass:$('#pass').val(),USR_SUBMIT:1,u_pass:$('#pass').val(),u_email:$('#yemail').val()},function(dat2){
						 
						 var cob2=jParser(dat2);
						 
						  if(cob2.Status==='Success'){
				           $('.w3-lg-2').html(cob2.Content);
							  
							$.post($('#m_url').val()+'&sq=4',{YName : $('#yname').val(),YEmail : $('#yemail').val(),YCompany : $('#cname').val(),YPhone:$('#yphone').val(),YPass:$('#pass').val()},function(dat3){
								jParser(dat3);
							}); 
						  }
						 
					 });
					   
				   }
			   });
				
			}
		};
		
		var pg={cont:"<div class='w3-lg-2'><div class=''></div></div>",func:register};
		shownWrapper(pg);
		
		
	});
	
}
function runTablesUpdates(){
	"use strict";
	
	 $.post($('#main_url').val()+'&sq=77',{const:8},function(){
		  
		   //timer delay function();
	  });
	
}
function items_mobile_vr(){
	"use strict";
	var tables=$("#quince_inner_content .q_row").toArray();
	 $(tables[2]).fadeOut("slow",function(){
		 $("#s_id_3").fadeOut("fast");
		 $(".tbl_ins").fadeOut("fast");
	 });
}
function promtUser(obj){
	"use strict";
	
	$("#wrapper").html(" ").fadeIn("fast");
	$("#wrapper").html("<div class='alert-wrap'>");
	
	$("#wrapper .alert-wrap").html("<div class='data-containet'><span>"+obj.cont+"</span> <div class='btn btn-cept'>Ok</div> </div>");
	$(".btn-cept").click(function(){
		$("#wrapper").fadeOut();
	});
	obj.func();
	
}
function updatedScheduleOfWorks(btn){
	"use strict";
	
	
	
	let props;
	
	if($(btn).attr("id") !==undefined){
	    
	    if($(".newSch").attr("id") !==undefined){
	        props={Vname:$(btn).attr("id").split("_")[1],prj:$(".newSch").attr("id").split("_")[1],isPhone:getPhoneAttr()};
	    }else{
	        props={Vname:$(btn).attr("id").split("_")[1],prj:0,isPhone:getPhoneAttr()};
	    }
	}
		
		
	$('#quince_inner_content').html('<i style="width:100%;float:left;text-align:center;">Loading...</i>');
	
	$.post($('#main_url').val()+'&sq=81',props,function(data){
		
		var ob=jParser(data);
		 
		if(ob.Status==="Success"){
			$('#quince_inner_content').html(ob.Content);
			$('.menu_loader').html(ob.TopMenu);runAssist();
			loadMiniFunctions();
						rawInnerFunction();
						activateDeleteFunction(true);
			loadInnerFunc();loadAfterAnimate(); 
		}
		
	});
}
function updatedTaskCreation(btn){
	"use strict";
	let id,props,content;
	let run=0;let fun=function () {

	};

	if($(btn).prop("id") !==undefined)
		id=$(btn).attr("id");
	
	switch(id.split("_")[0]){
		case "tskIns":
			run=1;
			
			props={cs:1, sch:$(btn).attr('id').split("_")[1],st:$("#st_date").val(),en:$("#en_date").val(),tt:$("#tt_val").val(),sr:$("#as_user").val(),pj:$(".a3-tab").attr("id").split("_")[1]};content=".taskHolder";
			
			content="#quince_inner_content";

			break;
		case "del":
			run=0;
			confirmAction("This action cannot be reversed ",function(){
				$.post($('#main_url').val()+'&sq=82',{cs:3,id:id.split("_")[1]},function(data){var ob=jParser(data);if(ob.Status==="Success"){
                    $("#quince_inner_content").html(ob.Content);
                    runAssist();rawInnerFunction();popFunctions();loadInnerFunc();loadAfterAnimate(); 
		         }});
			});
			break;
		case "exp":
			content="#quince_inner_content";

			run=1;

			$(".menu_loader").fadeOut("fast");

			props={cs:7,sch:$(btn).attr("id").split("_")[1]};

			break;
		case "upd":
			content="#wrapper";
		    $("#wrapper").fadeIn();
			run=1;
			props={cs:2,id:id.split("_")[1]};
			fun=function(){
				$(".quince_select").onchange(function(){
					console.log($(this).val());
				});
			}
			break;
		case "upT":
			content="#quince_inner_content";
			run=1;
            
            let tk_name=$("textarea").attr("placeholder");
            if($("textarea").val().trim() !="")
                tk_name=$("textarea").val();
                
            
            
			props={cs:4,nm:tk_name,st:$("#tk_sDate").val(),en:$("#tk_eDate").val(),as:$("#ass").val(),id:id.split("_")[1]};
			
			fun=function(){
				 $("#wrapper").animate({"marginLeft":"-300px","width":"0px","height":"0px"},function(){
			            $("#wrapper").attr("style","");
		           })
			}
			
			break;
		case "can":
			 $("#wrapper").animate({"marginLeft":"-300px","width":"0px","height":"0px"},function(){
			            $("#wrapper").attr("style","");
		      })
			break;
		case "dl":
			confirmAction("Deleting material estimate from Database <br/>this action is irreversible.",function(){
				 //$(".matHolder").html("loading....");
				$.post($('#main_url').val()+'&sq=82',{cs:5,id:$(btn).attr("id").split("_")[1],cm:$(".hans").attr("id").split("_")[1]},function(data){var ob=jParser(data);if(ob.Status==="Success"){
                // $(".matHolder").html(ob.Content);
					$($(btn).parent()).parent().remove()
			             runAssist();fun();rawInnerFunction();popFunctions();loadInnerFunc();loadAfterAnimate(); 
		        }});
			});
			return;
		case "wk":
			
			let cells=$("#"+$($(btn).parent()).parent().attr("id")+" .cells").toArray();
			
			let val=$(cells[2]).html();
			
			$(cells[2]).html("<input type='text' class='task' id='ltxt_"+$(btn).attr("id").split("_")[1]+"' style='padding:0px' value="+parseInt(val)+">");$(".cells input").focus();
			
			$(".task").focusout(function(){
				updatedTaskCreation(this);
			});
			return;
		case "ltxt":
			props={cs:6,cm:$(".hans").attr("id").split("_")[1],id:$(btn).attr('id').split("_")[1],vl:$(btn).val(),ds:$("#"+$(btn).parent().attr("id").split("_")[0]+"_"+$(btn).parent().attr("id").split("_")[1]+"_1").html()};
			run=1;
			content=".matHolder";
			
			break;
		case "upImg":
			console.log("is working");
			$("#file").trigger('click');
			break;
		case "shr":

			content="#wrapper";
			run=1;
			let id=$(btn).parent().attr('id').split("_");

		    props={cs:13,desc:$("#"+($(btn).parent().parent().attr("id"))+" #"+id[0]+"_"+id[1]+"_1").html(),id:$(btn).attr("id").split("_")[1]};

		    fun=cont=>{
				$("#wrapper").fadeIn(function(){

				});
		    	$("#wrapper ").html("<div class='a3-container a3-white a3-padding a3-round' style='width:75%;margin:5% 15%'>"+cont);
		    	  $(".edit").click(function () {

		    	  	let data=this

		    	  	$(data).html("<input  value='"+$(data).html()+"'  class='mathand a3-left a3-full' type='text' >");

					  $("#"+$(data).attr("id")+" input").unbind().focusout(function(){

					  	$(data).html($(this).val());
						  $("#cl_"+$(data).attr("id").split("_")[1]+"_3").html("Issueing.........");
						  $.post($('#main_url').val()+'&sq=82',{cs:14,desc:$("#desc").val(),val:$(this).val(),cmp:$(data).attr("id").split("_")[1]},function(info){
						  	var ob=jParser(info);

						  	if(ob.Status =="Success"){
						       $("#cl_"+$(data).attr("id").split("_")[1]+"_3").html("Complete");
						     }
						  })

                         $(data).click(function () {
                         	  updatedTaskCreation(this);
						 })
					  });

		    	  	$(data).unbind();

		    	  	$("#"+$(data).attr("id")+" input").focus();
				  });
		    	  $(".a3-red").unbind().click( ()=>{
		    	  	 $("#wrapper").fadeOut("fast");
				    });
                }

			break;
	}
	if(run !=0){
		$(content).html("<div class='response a3-left'></div><span class='a3-left a3-full'><div style='padding: 15% !important;' class='a3-padding a3-left a3-margin a3-full a3-center a3-text-orange'><i class='fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom'></i></div></span>");

		$.post($('#main_url').val()+'&sq=82',props,function(data){var ob=jParser(data);if(ob.Status =="Success"){

			$(content).html(ob.Content);
			runAssist();fun(ob.Content);rawInnerFunction();popFunctions();loadInnerFunc();loadAfterAnimate();
		}});}
	
	
	
}

function updatedTabletWorsSpace(btn){
	"use strict";
	let id,run=0,content,props,port=83;
	let func=function(){
		return null;
	}
	if($(btn).attr("id") !==undefined)
		  id=$(btn).prop("id");
	
	switch(id.split("_")[0]){
		case "cWk":
			run=1;
			content="#quince_inner_content";
			props={jm:1,id:$(btn).attr("id").split("_")[1]};
			
			break;
		case "tCmp":
				$('.menu_loader').html("");
			loadScheduleOfWorks(btn)
			break;
		case "app":
			$(".a3-toggle").fadeOut("fast");
			run=1;
			content="#quince_inner_content";
			props={jm:2,id:$(btn).attr("id").split("_")[1]};
			break;
		case "issBtn":
			const tableData=getTableData();
			
			port=88;
				
            $(btn).html("saving...");
           
            if($(btn).attr("id").split("_")[1]==1){
                props={type:1,cs:2,data:tableData.js,str:$("select[name=storeHandler]").val(),pr:$(".proj").val(),cm:$("select[name=compHandler]").val()};
            }else{
                props={type:1,cs:6,data:tableData.js,str:$("select[name=storeHandler]").val(),pr:$(".proj").val(),cm:$("select[name=compHandler]").val()};
            }
			run=1;

            $("select[name=compHandler]").val() =='-2' ? alert("Select a component") : null ;


            func=function (ob) {
                if(ob.name){

                	$(".results_wrap").fadeIn('fast',function () {
                       $(this).html(ob.value);
                       $("#ReqList").html(ob.other);
					});

					$(btn).html("Issue Items");

					setTimeout(function () {
						$(".results_wrap").fadeOut('fast').html(" ");
					},1000);

				}
			}

			break;
		case "uMp":case "vMp":case "cMp":
			content="#cont-container";
			run=1;
			props={jm:$(btn).attr("id").split("_")[0]};
			break;
			
		
	}
	
	if(run !==0){
		$(content).html('<i style="float:left;width:100%;color:blue;text-align:center">Loading....</i>');
		
		$.post($('#main_url').val()+'&sq='+port,props,function(data){
		
		var ob=jParser(data);

    	if(ob.Status==="Success"){
			$(content).html(ob.Content);

			func(ob.Content);$(".cel").unbind().click(function(){updatedTaskCreation(this);});
			runAssist();rawInnerFunction();runAssist();loadInnerFunc();loadAfterAnimate(); 
		}
		
	   });
	}
}
function updatedRadioFunctions(btn){
	"use strict";
	$(btn).addClass("selected");
	
	let opt=$(".quince_select option").toArray();
	
	let run=0;
	
	switch($(btn).attr("id")){
		case "store_2":
			$("#store_1").attr("checked",false).removeClass("selected");
			
			for(let i=0;i<opt.length;i++){
				if($(opt[i]).val()==-3){
					$(opt[i]).fadeOut();
				}if($(opt[i]).val()==-2){
					$(opt[i]).fadeIn();
				}
				
			}
				
			
			break;
		case "store_1":
			$("#store_2").attr("checked",false).removeClass("selected");
			
			for(let i=0;i<opt.length;i++){
				if($(opt[i]).val()==-2){
					$(opt[i]).fadeOut();
				}if($(opt[i]).val()==-1){
					$(opt[i]).fadeIn();
				}
			}
			
			break;
	}
}
var click=0;
function updatedMaterialTemplateFunction(btn){
	"use strict";
	if($(btn).prop("id") !==undefined){
		let props, port,UserDef,run=0;
		let clicks=0,content;
		
		let fun=null;
		
	
	switch($(btn).attr("id").split("_")[0]){
		case "crt":
			createTableElement().then(data=>{
               $("#cat_oth .a3-hidden").fadeIn("fast");
			});
             
			break;
		case "app":
			
			let nm=getTableData();
					
			props={def:3,dat:nm.js,id:$(btn).attr("id").split("_")[1],pId:$(btn).attr("id").split("_")[1]};
			run=1;
			content="#quince_inner_content";	
			
			break;
		case "pr":case "label":
			$(".listWrap").html(" ");
			$("#cont").append("<section id='req' class='a3-left'>")
			run=1;
			props={def:4,cdef:$(btn).val()};
			content="#req";
			break;
		case "qty":case "cost":case "tot":
			content=" ";
			
			props={def:5,cdef:1,val:$(btn).val(),bt:$(btn).attr("id").split("_")[0],id:$("#control").val()};
			
			run=1;
			
			  $("#tot_").val(parseInt($("#qty_").val())*parseInt($("#cost_").val()));
			break;
	}
	if(run !==0){
		$(content).html('<i style="float:left;width:100%;color:blue;text-align:center">Loading....</i>');
		
		$.post($('#main_url').val()+'&sq=84',props,function(data){
		
		var ob=jParser(data);
		 
		if(ob.Status==="Success"){
			
			$(content).html(ob.Content);
			runAssist();rawInnerFunction();runAssist();loadInnerFunc();loadAfterAnimate(); 
		}
		
	   });
	}
	}
}
function addItem(btn){
	"use strict";
	let header=$("#Reflist #header_row .cells_top").toArray();
	
	let rl=$("#cont-hold #"+$($(btn).parent()).parent().attr("id")+" .cells").toArray();
	
	let list=[];
	
	for(let i=0;i<header.length;i++){
		
		switch(i){
			case 0:
				list.push("<div class='cells' style='width:"+$(header[i]).width()+"px' id='cl_"+($("#Reflist .trow").length)+"_0'>"+$(rl[i]).html()+"</div>");
				break;
			case 1:
				list.push("<div class='cells' style='width:"+$(header[i]).width()+"px' id='cl_"+($("#Reflist").length)+"_1'>"+$(rl[2]).html()+"</div>");
				break;
			case 2:
				list.push("<div class='cells' style='width:"+$(header[i]).width()+"px' id='cl_"+($("#Reflist").length)+"_2'>"+$(rl[1]).html()+"</div>");
				break;
			case 3:
				list.push("<div class='cells edit' id='cl_"+($("#Reflist .trow").length)+"_3' style='width:"+$(header[i]).width()+"px'>type value</div>");
				editCells();
				break;
			case 4:
				list.push("<div class='cells' style='width:"+$(header[i]).width()+"px' id='cl_"+($("#Reflist").length)+"_"+i+"'><i class='fas fa-trash-alt a3-hover-text-red tab-equip' id='del_"+$(rl[3]).html()+"'></i></div>");
				   
				break;
		}
		
	}
	$("#Reflist label").remove();
		$("#Reflist #table_id").append("<div class='trow al"+($("#Reflist .trow").length)+"' id='lstid-"+($("#Reflist").length)+"'>"+ list.join(" ")+"</div>");editCells();$(".tab-equip").click(function(){			      $($(this).parent()).parent().remove();   });
	
	
}
function editCells(){
	$(".edit").unbind().click(function(){
		var grent=$(this).parent().attr("id");
        var pself=$(this).attr("id");
        $("#Reflist #"+$(this).parent().attr("id")+" #"+$(this).attr("id")).html("<input type='text' placeholder=' ' class='ltxt' id='"+$(this).attr("id")+"'>");
         $(".ltxt").focus();
		 $(".ltxt").mouseout(function(){
            var c_val=$(this).val();
            $("#Reflist #"+grent+" #"+pself).html(c_val);
         });
		$(".ltxt").blur(function(){
            var c_val=$(this).val();
            $("#mn_tb #"+grent+" #"+pself).html(c_val);
         });
	});
}
const updatedEquipmentFunction=(btn)=>{
  let params;let run=0;
	let func;
	let content=".a3-response";
	switch($(btn).attr("id").split("_")[0]){
		case "dn":
			confirmAction('This action is not reversable. Are you sure you want to proceed',function(){
				
					$.post($('#main_url').val()+'&sq=85',{id:$(btn).attr("id").split("_")[1],cs:1},function(data){
						
						var ob=jParser(data);
						if(ob.Status==="Success"){
							
							if(ob.Content.name){
								$("#respond").css("backgroundColor", "green").animate({width:"100%","backGround":"green","color":"white"},function(){
									$("#respond").html(ob.Content.value);
									
								})
							}else{
								$("#respond").css("backgroundColor", "red").animate({width:"100%",height:"50px","backGround":"red","color":"white"},function(){
									$("#respond").html(ob.Content.value);
								})
							}
							setTimeout(function(){
							$("#quince_inner_content").html("loading....");
							$("#wrapper").fadeOut(function(){
								$("#quince_inner_content").html(ob.TopMenu);runAssist();rawInnerFunction();runAssist();loadInnerFunc();loadAfterAnimate();});	
							},1500);
							
							//$("#wrapper").html(ob.Content);$(".w3-sel").click(function(){$('#wrapper').fadeOut("fast");	});		
						}
					});
					
				
				
			});
			return ;
		case "upd":case "edi":
			params={sr:$(".quince_select ").val(),vl:$("#eq_val").val(),id:$(btn).attr("id").split("_")[1],cs:2};
			popWindow('#wrapper',function(){
				
				$.post($('#main_url').val()+'&sq=85',params,function(data){
		                  var ob=jParser(data);

					
						if(ob.Status==="Success"){
							
							if(ob.Content.name){
								$("#respond").css("backgroundColor", "green").animate({width:"100%","backGround":"green","color":"white"},function(){
									$("#respond").html(ob.Content.value);
									
								})
							}else{
								$("#respond").css("backgroundColor", "red").animate({width:"100%",height:"50px","backGround":"red","color":"white"},function(){
									$("#respond").html(ob.Content.value);
								})
							}
							setTimeout(function(){
							$("#quince_inner_content").html("loading....");
								
							$("#wrapper").fadeOut(function(){
								$("#quince_inner_content").html(ob.TopMenu);runAssist();rawInnerFunction();runAssist();loadInnerFunc();loadAfterAnimate();});	
							},1500);
							
							//$("#wrapper").html(ob.Content);$(".w3-sel").click(function(){$('#wrapper').fadeOut("fast");	});		
						}
				});
				
			});
			break;
		case "app":
			$("#wrapper").fadeOut();
			break;
		case "dis":
			run=2;
			func=function(ob){
				$(".equip-tab-pop").fadeOut("slow",function(){
					$(" #wrapper").fadeOut("fast",function(){
						$("#quince_inner_content").html(ob);runAssist();
					});
				});
			}
			params={cs:4,id:$(btn).attr("id").split("_")[1]};
			break;

	}
	if(run !==0){$.post($('#main_url').val()+'&sq=85',params,function(data){  response(data,content,func); }); }
}
const tableRefresh=()=>{
	"use strict";
	let rows=$(" #table_id .trow ").toArray();let tableData=[];let hdArr=$("#table_id #header_row .cells_top").toArray();
	
	for(let i=0;i<rows.length;i++){
		let cells=$("#table_id  #"+$(rows[i]).attr("id")+" .cells").toArray();	let cellData=[];
		
		   cellData.push("<div class='cells' id='cl_"+i+"_0' style='width:"+$(hdArr[0]).width()+"px' >"+(i+1)+"</div>");
		
		for(let p=1;p<cells.length;p++)
		   cellData.push("<div class='cells' id='cl_"+i+"_"+p+"' style='width:"+$(hdArr[p]).width()+"px'>"+$("#table_id   #"+$(rows[i]).attr("id")+" #"+$(cells[p]).attr("id")).html()+"</div>");
		
		tableData.push(cellData);
		
	}
	
	$("#table_id .trow").remove();
	
	for(let i=0;i<tableData.length;i++){
		
		$("#table_id ").append(" <div class='trow al"+i+"' id='lstid-"+i+"'>"+tableData[i].join(" ")+"</div>");runAssist();
		
	}
	
}
const response=(data,$loc,$scFunc)=>{
	  var ob=jParser(data);

	if(ob.Status==="Success"){
		$($loc).html("value added succesfully").animate({backgroundColor:"green",height:"30px",width:"98%"},function(){
			$scFunc(ob.Content);
		});
		
	}else{

	}
}
const updatedStockFunction=()=>{
	"use strict";
	
	$(".quince_select").unbind().change(function(){
	    $("#invL").html(" loading....");
		$.post($('#main_url').val()+'&sq=86',{st:$(this).val(),pj:$("#siteSel").val()},function(data){
			var ob=jParser(data);
            
            if(ob.Status==="Success"){
                
                $("#invL").html(ob.Content);
            
				rawInnerFunction();updatedStockFunction();
				
            }
		});
		
	});
	
}
const getTableData=()=>{
	let  mnTbl=$(".mn_tables").toArray();
	
	let data=[];let hdArr=[];

	(mnTbl.length >1 ) ?  hdArr=$("#"+$(mnTbl[0]).attr("id")+" .cells_top" ).toArray() : hdArr=$(".mn_tables .cells_top" ).toArray() ;
	
	mnTbl.forEach( (tb,key)=>{

		let tbData=$("#"+$(tb).attr("id")+" .trow" ).toArray();

		tbData.forEach(element => {
			let cells=$("#"+$(tb).attr("id")+ "  #"+ $(element).attr("id")+ " .cells").toArray();

           
			(!isNaN($(cells[2]).html())) ? data.push([$(cells[1]).html(),$(cells[2]).html(),$(cells[3]).html(),$(cells[4]).html(),$(cells[5]).html()]) : null;
			
			
		});
	});
	
	
	return {dt :data ,tbl:mnTbl,js:JSON.stringify(data),hdItem:hdArr}
  
}
async function createTableElement(arr){
	
	run=0;
	
	let table='.mn_table';

	($('.mn_tables').length == 1) ? table='.mn_tables' : table='#table_oth' ; 

	let $headrArr=$("#"+$(table).attr("id")+" #header_row .cells_top").toArray();

	let columns=[];
	
	let len=$("#"+$(table).attr("id")+"  .trow").length;
	
	$headrArr.forEach( (element,key)=>{
		let cl=null;
		
		(key ==0) ? cl= "<div class='cells edit' id='cl_"+key+"_"+(len+1)+"_' style='width:"+$(element).css("width")+"'>"+(len+1)+"</div>" : cl= "<div class='cells edit' id='cl_"+(len+1)+"_"+key+"_edit' style='width:"+$(element).css("width")+"'>Click here</div>" 
		columns.push(cl);
	});
  
	columns.push("<div class='cells' style='display:none' id='nElem'>"+(len+1)+"</div> ");

	($('.mn_tables').length == 1) ? $('.mn_tables').append("<div class='trow al' id='listid_"+len+"'>"+columns.join(" ")+"</div>") : $('#table_oth').append("<div class='trow al' id='listid_"+len+"'>"+columns.join(" ")+"</div>") ; 
    rawInnerFunction();
}

async function insertTableElement(){
	
}
async function manageSelections(btn){

	if( $(btn).attr("id") !=undefined ){
		
		switch($(btn).attr("id").split("_")[0]){
			case "pr":case "sSite":
				
				($(btn).val() == '-1') ? $("select[name='selectSource']  option").show() : $("select[name='selectSource']  #mrc_"+$(btn).val()).hide();

                
				break;
			 case "src-site":
				 ($(btn).val() =="-2" ) ? $("select[name='selectSite'] option").show() : $(" select[name='selectSite'] #opt_"+$(btn).val()).hide();
				 
				 break;
		 }
	}
	

}
const  updatedSelections=btn=>{
   switch ($(btn).attr("name")) {
	   case "eqSite":
		   
		   break;
   
	   default:
		   break;
   }

}
async function delfunction(){
  $(".del").click(function(){
        $($(this).parent()).parent().remove();
  });
}
async function tableCheck(){
	console.log("is working");

	let row=$(".mn_tables .trow").toArray();

	if($(".mn_tables .trow").length==0){
        alert("There is zero data in the table");
	}
}
const tableRedo=arr=>{
	run=0;
	
	let table='.mn_table';

	($('.mn_tables').length == 1) ? table='.mn_tables' : table='#table_oth' ; 

	let $headrArr=$("#"+$(table).attr("id")+" #header_row .cells_top").toArray();

	
	
	$(table+" .trow").remove();

	
	
	for(let i=0;i<arr.length;i++){
		
		let columns=[];
		
		columns.push("<div class='cells' style='width:"+($($headrArr[0]).css("width"))+"' id='cl_"+i+"_0'>"+(i+1)+"</div>");
		
		columns.push("<div class='cells ' id='cl_"+i+"_1' style='width:"+($($headrArr[1]).css("width"))+"'>"+arr[i][0]+"</div>");
		
		columns.push("<div class='cells  ' id='cl_"+i+"_2_edit' style='width:"+($($headrArr[2]).css("width"))+";text-align:right;padding-right:4px'>Click here</div>");
		
		columns.push("<div class='cells ' id='cl_"+i+"_3' style='width:"+($($headrArr[3]).css("width"))+";text-align:center'>"+arr[i][2]+"</div>");
		
		columns.push("<div class='cells ' id='cl_"+i+"_4' style='width:"+($($headrArr[0]).css("width"))+"'><div id='dl_"+i+"_' class='delr'></div></div>");
		
		$(table).append("<div class='trow al"+i+"' id='listid-"+i+"'>"+columns.join(" ")+"</div>");
		rawInnerFunction();activateDeleteFunction(true);
	}
	
}
const createDate=date=>{
		
		const monthNames = ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July','Aug', 'Sept', 'Oct','Nov', 'Dec'];
		
		let dateArr=date.split("-");
		
		let month=null;
		
		monthNames.forEach( (element,key)=>{
		    (element==dateArr[1]) ? month=key+1 :null;
		});
		
		
		return dateArr[2]+"/"+month+"/"+dateArr[0];
		
	}
const popFunctions=_=>{
	var modal=document.getElementById("wrapper");
	
	window.onclick = function(event) {
      if (event.target == modal) {
           $("#wrapper").animate({"marginLeft":"-300px","width":"0px","height":"0px"},function(){
			   $("#wrapper").attr("style","");
		   })
      }
    }
}
const companyFunction=btn=>{
  
	let run=0;let params,container,fun=function(){
		
	};
	
	if($(btn).attr("id") ==undefined)
		return ;
	
	switch($(btn).attr("id").split("_")[0]){
		case "cSt":
			container="";
			params={cs:$("#stName").val(),bt:$(btn).attr("id"),site:$(".quince_select").val()  }
			fun=function(bt){
				$(".container").html(bt);
				$("#stName").val(" ");
				$(".quince_select").prop("selectedIndex",-1);
				$(".a3-toggle").fadeOut("slow");
			}
			run=1;
			break;
	}

	if(run !=0){
		$.post($('#main_url').val()+'&sq=87',params,function(data){
			var ob=jParser(data);
            
            if(ob.Status==="Success"){
                
                $(container).html(ob.Content);
				fun(ob.Content);
            
				rawInnerFunction();updatedStockFunction();
				
            }
		});
		
	}
}
const innerCompany=_=>{
	
	$(".comp").click(function(){
			$(".a3-toggle").fadeToggle("slow")
	});
	
	$(".comp2").click(function(){
		companyFunction(this);
	});
	$(".storeF").unbind().click(function(){
		companyStoreFunction(this);
	});
	
	$(".saveList").click(function(){
		updatedSaveList(this);
	});
	$(".compBtn").click(function(){
		let btn=this;
		 	popWindow('#wrapper',function(){
				updatedCompanyProcessor(btn);
			       
			});
	});
}

const companyStoreFunction=btn=>{
	let cells=$("#"+$($(btn).parent()).parent().attr("id")+" .cells").toArray();
    $(cells[4]).html("loading.....");
    $.post($('#main_url').val()+'&sq=88',{id:$(btn).attr("id").split("_")[1],cs:3,pj:$("select[name=siteHandler]").val(),st:$("select[name=vPl]").val()},function(data){
        var ob=jParser(data);
            
        if(ob.Status==="Success"){
             $(cells[3]).html(ob.Content);
             $(cells[4]).html("Awaiting Response...");
             $(".quince_select").unbind().change(function(){
                 $(cells[4]).html("Assigning User..");
                 $.post($('#main_url').val()+'&sq=88',{id:$(this).val(),btn:$(this).attr("id"),cs:4},function(data){
                        var ob=jParser(data);
                     if(ob.Status==="Success"){
                         $(cells[3]).html(ob.Content);
                         $(cells[4]).html("<i class='fas fa-user a3-hover-text-blue a3-margin-left storeF'></i><i class='fas fa-trash a3-margin-left storeF'>");
                         innerCompany();
                     }
                        
                 });
             });
        }
       
    });
	
	
}
const updatedSaveList=btn=>{
	if($(btn).prop("id")==undefined)
		return
	
	let prop,run=0;
	
	let string=$(btn).html();
	
	let dataTable=getTableData();
	
	switch($(btn).attr("id").split("_")[0]){
		case "sid":
			
			if($(btn).html() !=="saving..."){
				
				run=1;
				
				$(btn).html("saving...");
				
				prop={req:$(btn).attr("id").split("_")[2],pr:$("select[name=proj]").val(),st:$("select[name=store]").val(),data:dataTable.js,cs:1};
			}
			break;
        case "rac":
            if($(btn).html !=="receiving..."){
                
                $(btn).html('receiving...');
                
                prop={cs:5,data:dataTable.js};
                
                run=1;
                
            }
            
            break;
        case "req":
            if($(btn).html !=="issueing..."){
                
                $(btn).html('issueing...');
                
                prop={cs:6,data:dataTable.js,req:$(btn).attr("id").split("_")[1],pr:$("select[name=siteHandler]").val(),st:$("select[name=vPl]").val()};
                
                run=1;
               
            }
            break;
	}
	if(run !==0){
      
		$.post($('#main_url').val()+'&sq=88',prop,function(data){
			var ob=jParser(data);
		   
			if(ob.Status==="Success"){
                
                
                if(ob.Content.name==true){
                   $(btn).html(string);
                    
                    $(".innerTitle").append("<div class='a3-left resp'>");
                    
                    $(".innerTitle .resp").html(ob.Content.value).animate({width:"100%","height":"70px"},function(){
                        setTimeout(function(){
                            $(".innerTitle .resp").fadeOut(function(){
                                $("#quince_inner_content").html(ob.TopMenu);
                                runAssist();rawInnerFunction();updatedStockFunction();
                            });
                        },2000)
                    });
                }else{
                    $(".innerTitle .resp").html(ob.Content.value).animate({width:"100%","height":"70px"},function(){
                        setTimeout(function(){
                           // $(".innerTitle .resp").fadeOut();
                        },2000)
                    });
                }
                
               
                
               // $("quince_inner_content").html(ob.Content);
			   
		   }
		
		});
	}
}, items_get=_=>{
   "use strict";
	
   var $it_arr=[];
	
   let rows=$("#Reflist #table_id .trow").toArray();
	
	for(let i=0;i<rows.length;i++){
		let cels=$("#Reflist #table_id #"+$(rows[i]).attr("id")+" .cells").toArray();
		
		let temp=[];
		
		if(!isNaN($("#Reflist #table_id #"+$(rows[i]).attr("id")+"  #"+$(cels[2]).attr("id")).html()) &( $("#Reflist #table_id #"+$(rows[i]).attr("id")+"  #"+$(cels[3]).attr("id")).html() !==" click here  " )  ){
			 temp=[$("#Reflist #table_id #"+$(rows[i]).attr("id")+"  #"+$(cels[1]).attr("id")).html(),parseInt($("#Reflist #table_id #"+$(rows[i]).attr("id")+"  #"+$(cels[2]).attr("id")).html()),$("#Reflist #table_id #"+$(rows[i]).attr("id")+"  #"+$(cels[3]).attr("id")).html()];
			
			$it_arr.push(temp);
	  }
	
	}
	
	return $it_arr;
  }, loadingSpinner = (btn, response) => {
	  $(btn).html(response);

  }, updatedUiFunctions = _ => {
	  "use strict";
	  $(".a3-tabs").unbind().click(function () {
		  updat
	  });

  }, updatedEquipmentFunctions = _ => {
	  $(".auth2").unbind().click(function () {
		  let auth = this;
		  $("#wrapper").fadeIn(function () {
			  $.post($('#main_url').val() + '&sq=88', {cs: 7, bt: $(auth).attr("id").split("_")[1]}, function (data) {
				  var ob = jParser(data);

				  if (ob.Status === "Success") {
					  $("#wrapper").html(ob.Content);
					  updatedEquipmentFunctions();
				  }


			  });
		  });
		  popFunctions();
	  });
	  $(".eq-btn").unbind().click(function () {
		  updatedEquipmentFunction(this);
	  });
	  $(".quince_select").change(function () {
		  if ($(this).val() != '-2') {

			  if ($("#fl-gauge input").val() == undefined) {
				  $("#fl-gauge").html("<label class='a3-left a3-padding a3-margin-top'>Fuel Used</label><input class='a3-border a3-round a3-margin a3-padding' style='width: 20%;' type='text' id='fuel'>");
			  }
		  } else {
			  $("#fl-gauge").html("");
		  }
	  });
  }, updatedCompanyProcessor = btn => {
	  let content, params;


	  switch ($(btn).attr("id")) {
		  case "lauch":
			  params = {cs: 1};
			  content = "#wrapper";
			  break;
		  case "create":
			  params = {
				  cs: 2,
				  YCompany: $("#cmName").val(),
				  FName: $("#FName").val(),
				  YEmail: $("#YEmail").val(),
				  YPass: $("#YPass").val()
			  };
			  content = "#wrapper #container";
			  $(content).html("<div id='loader'>loading ...............</div>");
			  break;
	  }

	  $.post($('#main_url').val() + '&sq=89', params, function (data) {
		  var ob = jParser(data);

		  if (ob.Status === "Success") {
			  $(content).html(ob.Content);
			  popFunctions();
			  innerCompany();
		  }
	  });
  }, cellFunction = btn => {
	 
	  if ($('#control').val() != undefined) {
		  let id = $(btn).parent().prop("id");
		  
		  let parent=$($($(btn).parent()).parent()).parent().attr("id");
		  
		  let description = $("#"+parent+" #" + id.split("_")[0] + "_" + id.split("_")[1] + "_1").html();

		  let projectId = $("#control").val();
		  
		  let params={cs:0,pj: $("#control").val(),tb:parent.split("_")[1],ds:description,cel:id,up:$(btn).val()};


		  $.post($('#main_url').val() + '&sq=89',params, function (data) {
			  var ob = jParser(data);


			  if (ob.Status === "Success") {
				  let mat=JSON.parse(ob.Content);

				  /*$("#"+parent+" #"+id).html(mat[0].mat_qty+""+mat[0].mat_unitType);

				 // $("#"+parent+" #" + id.split("_")[0] + "_" + id.split("_")[1] + "_3_edit").html(mat[0].mat_estCost.toFixed(2));

				 // $("#"+parent+" #" + id.split("_")[0] + "_" + id.split("_")[1] + "_3").html(mat[0].mat_estCost.toFixed(2));

				  $("#"+parent+" #" + id.split("_")[0] + "_" + id.split("_")[1] + "_4").html((mat[0].mat_qty*mat[0].mat_estCost)+".00");
				 */
			  }
		  });

	  }

  }, materialManagement = btn => {
	  if ($(btn).attr("id") == undefined) {

		  $(".a3-toggle").fadeIn("fast");

		  return null;
	  }

	  $.post($('#main_url').val() + '&sq=90', {cs :1,id:$(btn).attr("id")}, function (data) {
		  var ob = jParser(data);

		  if (ob.Status === "Success") {
		  	$(".table_area").html(ob.Content);
		  	runAssist();
		  }
	  });

  },materialUpdateManagment=btn=>{

  	switch($(btn).attr("id").split("_")[0]){
		case 'del':
			return  confirmAction("This action is irreversible",function(){
                $.post($('#main_url').val() + '&sq=90', {cs :4,nm:$(btn).attr("id").split("_")[1]}, function (data) {
                    var ob = jParser(data);
                    if (ob.Status === "Success") {
                        $($(btn).parent()).parent().fadeOut().remove();

                    } });
			});
		case 'ins':
			return confirmAction("Do you wish To continue?",function () {
                $.post($('#main_url').val() + '&sq=90', {cs :2,nm:$("#catName").val()}, function (data) {
                    var ob = jParser(data);
                    if (ob.Status === "Success") {
                       $(".a3-toggle").fadeOut('slow',function () {
                           $("fieldset").append(ob.Content);$("#catName").val(" ");
                       });
                    }
                });
			});
        case "crt":
            return  $(".m3_toggle").fadeIn('fast');
        case "sel":
            $.post($('#main_url').val() + '&sq=90', {cs :3,nm:$(btn).val(),id:$(btn).attr("id").split("_")[1]}, function (data) {
                var ob = jParser(data);
                if (ob.Status === "Success") {
                 $($($(btn).parent()).parent()).parent().fadeOut('fast').remove()
                }
            });
            return null;
	}

  },menuClicks=btn=>{	  
	 
	  $(".menu_item").removeClass("menu_active");
	  
	  $(btn).addClass("menu_active");
  },bindedTaskFunction=btn=>{
    $(".tasks").removeClass('a3-light-blue');

	$(btn).addClass('a3-light-blue');

	let props;
	let content;
	let run;
	let fun;

	switch($(btn).attr("id").split("_")[0]){
		case "upImg":
		    $("#file").trigger('click');

            $('#fileImage').attr('action',$('#main_url').val()+'&sq=12&si=plain_1&fid=copI');

            $(":file").change(function(){

            	$("#wrapper").fadeIn().html(" <div class='a3-white a3-padding a3-margin ' id='chids'></div>");
            	$('#fileImage').ajaxSubmit({

					url:$('#main_url').val()+'&sq=12&si=plain_1&fid='+$(btn).attr("id").split("_")[1],
					type:'post',
					error:function (data) {
                       alert(data);
					},
					success:data=>{
						var ob=jParser(data);

						if(ob.Status=='Success'){

							let cont= ob.Content;

							$("#chids").html("<img src='"+cont.other+"'><div class='a3-green a3-padding' style='height:50px'>"+cont.value+"<button class='a3-right a3-padding a3-round'>Close </button></div>");

							$("button").unbind().click(function(){
								$("#wrapper").fadeOut('fast');
							});
							setTimeout(function(){
								$("#wrapper").fadeOut('fast');
							},2500)
						}
					},uploadProgress:function(ev,pos,total,per){
						$("#chids").html("<p>file Uploading >>>  <div class='a3-left a3-blue a3-padding a3-round' style='width:"+per+"%'>"+per+"% </div></p>");

                   	}
				});
			});

               run=0;
			break;
		case "mat":
			props={cs:8,sch:$(btn).attr("data-dad"),id:$(btn).attr("id").split("_")[1]};

			run=1;

            content="#mat_container";

            fun=data=>{
            	$("#mat_container").html(data.value);

			}

			break;
		case "estMat":
			run=0;

			let materials=getTableData();

			if(materials.dt.length !=0){
				props={ dt:JSON.stringify(materials.dt),cs:9,sch:$(btn).attr('id').split("_")[1],dad:$("button").attr("data-dad")};

				console.log(props);
				run=1;

				fun=data=>{
					$("#mat_container").html("<div class='response'></div><div class='content'></div>");

					$("#mat_container .response").html(data.value);

					$("#mat_container .content").html(data.other);

					setTimeout(function(){
						$(".response").animate({'width':'0px','height':'0px'},1000,function(){
							$(this).fadeOut('fast');
						});
					},1500);
				}
			}else{
				alert("please enter vales for estimation");
			}
			break;
		case 'note':
					run=0;
                 $(".note_area").animate({'width':'230px'},1000);

			break;
		case 'not':
			run=1;
			props={cs:10,sch:$(btn).attr('id').split("_")[1],cont:$('textarea').val()};

			fun=data=>{
				if(data.name==true){
				   $(".note_area").animate({width:'0px'},1000,function(){
				   	  $("textarea").val(" ");
				   });
				}

			}
			break;
		case 'gimage':
			props={cs:11,sch:$(btn).attr('id')};

			content="#mat_container";

			fun=data=>{

				$("#mat_container").html(data);
			}
			break;
		case 'gnote':
			props={cs:11,sch:$(btn).attr('id')};

			content="#mat_container";

			fun=data=>{
				$("#mat_container").html(data);
			}
			break;
		case "gmat":
			props={cs:11,sch:$(btn).attr('id')};

			content="#mat_container";

			fun=data=>{
				$("#mat_container").html(data);
			}
			break;
		case "pen":
			 getLocation();

			setTimeout(function(){ console.log(mainLocation) },2000);

			run=0;
			break;
        case "bck":
            props={cs:12 ,sch:$(btn).attr('id').split('_')[1]};
            content="#quince_inner_content";

			$(".menu_loader").fadeIn("fast");
             fun=data=>{
                 $("#quince_inner_content").html(data);
             }
             run =1;
            break;
		case "isMat":
			props={cs:15,val:$(btn).val()};
			run=1;
			content="#ReqList";
			fun=data=>{
				$("#ReqList").html(data);
				runAssist();rawInnerFunction();
				loadInnerFunc();
				loadAfterAnimate()
			}
			break;
	}

	if(run !==0) {
		$(content).html("<div class='response a3-left'></div><span class='a3-left a3-full'><div style='padding: 15% !important;' class='a3-padding a3-left a3-margin a3-full a3-center a3-text-orange'><i class='fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom'></i></div></span>");

		$.post($('#main_url').val() + '&sq=82', props, function (data) {
			var ob = jParser(data);
			if (ob.Status === "Success") {

				fun(ob.Content);
				runAssist();rawInnerFunction();
				loadInnerFunc();
				loadAfterAnimate();
			}

		});
	}
}
const close=_=>{
	$("#wrapper").fadeOut('fast');
},loginRegisterToggle=btn=>{
	let cBtn=$("button.active").attr("id");

	if(cBtn==$(btn).attr("id")) {
		if(cBtn=='logs'){
               console.log(" add margin");
			$(".conts").animate({'marginLeft':0},1500);
			$("#rgst").addClass('active');
			$("#logs").removeClass('active');
		}else{
			$(".conts").animate({'marginLeft':"-420px"},1500);
			$("#rgst").removeClass('active');
			$("#logs").addClass('active');

		}
	}
},updatedProjectLevelFunctions=btn=>{

	if($(btn).attr("id")=='' | $(btn).attr("id")== undefined)
		return;

	let content,func=_=>{},props,run=0;

	switch ($(btn).attr("id").split("_")[0]) {
		case "gan":
			content="#quince_inner_content";
			props={ cs:0,id:$(btn).attr("id").split("_")[1]};
			run=1;
			break;
		case "mem":
			content=".pr_cont";
			props={cs:1,id:$(btn).attr("id").split("_")[1]};
			run=1;

			break;
		case "cat":
			props={cs:2,id:$(btn).attr("id").split("_")[1],sel:$(".quince_select[name=projSelect]").val()};
			run=1;

			$(".slidePopUp").animate({"top":"90%"},1000).html(" Adding members");;
			func=cont=>{

				$(".a3-table-container").html(cont);

					slidePopUp("Succesfully Added user");
			}
			break;
		case "projM":
			let username,title,phone,email1,email2;

			let inputs=$("fieldset section input").toArray();

			for(let i=0;i<inputs.length-1;i++){
				if($(inputs[i]).val().trim()==""){
				   run=0;
				}else{
					run=1;
				}
			}

			username=$("input[name=username]").val();
			title=$("input[name=title]").val();
			phone=$("input[name=phone]").val();
			email1=$("input[name=email1]").val();

			email2=$("input[name=email2]").val().trim()=='' ?  $("input[name=email2]").val() : " ";

			props={usr:username,phn:phone,email:email1,temail:email2,tit:title,cs:3,id:$(btn).attr("id").split("_")[1]};
			$(".slidePopUp").animate({"top":"90%"},1000).html(" Adding members");

			func=cont=>{

				$(".a3-table-container").html(cont);

				slidePopUp("Succesfully Added user");

				$("input").clear();
			}

			break;
		case "email":
			props={id:$(btn).attr("id").split("_")[1],cs:4};
			run=true;

			$(".slidePopUp").animate({"top":"90%"},1000);

			$(".slidePopUp").html(" processing the email");

			func=cont=>{
				slidePopUp(cont);
			}
			break;
	}
	$(content).html("loading.....");
	if(run !==0) {


		$.post($('#main_url').val() + '&sq=91', props, function (data) {
              var ob=jParser(data);

              if(ob.Status=='Success'){
              	 $(content).html(ob.Content);
              	 func(ob.Content);
                  runAssist();
			  }
		})
	}

},slidePopUp=cont=>{


     let data=cont;

     if(data.name=true){
		 $(".slidePopUp").html(cont);

     	 setTimeout(_=>{
			 $(".slidePopUp").animate({"top":"190%"},2000);
		 },2000)
	 }else{
		 $(".slidePopUp").html("Error Contact developer ").css({"background":"red"});
	 }
      //console.log(cont);
}