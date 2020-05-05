var is_native=0;
var aud=new Audio('alerts/alert1.mp3');
var a_num=0;
var tout=300;
var cRow=null;

$(document).ready(function(){
	'use strict';
    
//	activateCustomNotifcations();
	
	$('.acTab').unbind();
    $('.menu_item,.mnRow').click(function(){
	   menuClicks(this);
       loadMainContent(this);        
    });

    $(".user-controls").click(function(){
    	loginRegisterToggle(this);
	});

	loadAlerts();
	
    $('#wrapper').click(function(){
       var theWrap=this;
	   $('.thePop').fadeOut('500',function(){ $(theWrap).fadeOut('500'); }); 
    });
	
    loadInnerFunc();
	
	$('.tggle_m').click(function(){
		var $screen=$(window).width();
		if($screen >670){
		if($('.menu_wrapp').width()===0){
			$('.retR').fadeOut('fast',function(){$('.quince_content').animate({width:"81.8%"},500,function(){
			$('.menu_wrapp').animate({width:"18%"},500);
			});
		  });
		}else{
			$('.menu_wrapp').animate({width:"0%"},500,function(){
			$('.quince_content').animate({width:"99.8%"},500,function(){
				$('.retR').fadeIn('slow');
			});
		  });
		}
		}else{
			if($('.menu_wrapp').width()===0){
			$('.retR').fadeOut('fast',function(){$('.quince_content').animate({width:"0%"},500,function(){
			$('.menu_wrapp').animate({width:"98%"},500);
			});
		  });
		}else{
			$('.menu_wrapp').animate({width:"0%"},500,function(){
			$('.quince_content').animate({width:"98%"},500,function(){
				$('.retR').fadeIn('slow');
			});
		  });
		}
		}
		
	});$('.uWrap .txtDiv').click(function(){$('.uOpts').toggle('fast');});
	
	$('.quince_content,.menu_item').mouseover(function(){
		$('.uOpts').hide('fast');
	});
	
	$('.mNDiv').click(function(){
		showMyAlerts();
	});
	
	numbersOnly('#yphone');
	
	$('#regUsers').click(function(){
		registerNewUser(this);
	});
	
	$('.resPass').click(function(){
		resetPassword();
	});
	
});
//------------------------------------------------------------RESET PASSWORD------------------------------------------------
function resetPassword(){
	popWindow('.thePop',function(){
			$('.thePop').animate({left:(($(window).width()-$('.thePop').width())/2)+'px'},'fast');
		    $('.thePop').html('<i style="text-align:center;font-size:20px;float:left;margin-top:120px;width:100%;">Loading....</i>');
		   
		    $.post($('#m_url').val()+'&sq=5',{},function(data){
				
				var ob=jParser(data);
				
				if(ob.Status=="Success"){
					$('.thePop').html(ob.Content);
					$('#resBnn').click(function(){
						requestReset(this);
					});
				}
				
			});
		
		});
}
function requestReset(btn){
	
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
		    $('.thePop').html(ob.Content);
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
					   $('.thePop').html(ob.Content);
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
								   $('.thePop').html(ob.Content);
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
//----------------------------------------------------------END RESET PASSORD-----------------------------------------------
//-----------------------------------------------------------REGISTER NEW USER-----------------------------------------------
function registerNewUser(btn){
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
		
		popWindow('.thePop',function(){
			//showWait('.thePop','Creating '+$('#cname').val()+'...');
			$('.thePop').animate({left:(($(window).width()-$('.thePop').width())/2)+'px'},'fast');
			
			//-----------------------------------------------------------------------------------
			
			if(ob.Status==='Success'){
			   $('.thePop').html(ob.Content);
			   
				$.post($('#m_url').val()+'&sq=2',{YName : $('#yname').val(),YEmail : $('#yemail').val(),YCompany : $('#cname').val(),YPhone:$('#yphone').val(),YPass:$('#pass').val()},function(dat){
				   var cob=jParser(dat);
				   if(cob.Status==='Success'){
				     $('.thePop').html(cob.Content);
					 
					 $.post($('#m_url').val()+'&sq=3',{FName : $('#yname').val().split(' ')[0],SName : $('#yname').val().split(' ')[1],YEmail : $('#yemail').val(),companyId : cob.TopMenu,YPhone:$('#yphone').val(),YCompany : $('#cname').val(),YPass:$('#pass').val(),USR_SUBMIT:1,u_pass:$('#pass').val(),u_email:$('#yemail').val()},function(dat2){
						 
						 var cob2=jParser(dat2);
						 
						  if(cob2.Status==='Success'){
				           $('.thePop').html(cob2.Content);
							  
							$.post($('#m_url').val()+'&sq=4',{YName : $('#yname').val(),YEmail : $('#yemail').val(),YCompany : $('#cname').val(),YPhone:$('#yphone').val(),YPass:$('#pass').val()},function(dat3){
								jParser(dat3);
							}); 
						  }
						 
					 });
					   
				   }
			   });
				
			}
			//-----------------------------------------------------------------------------------
			
		});
		
	});
	
}
//----------------------------------------------------------END REGISTER NEW USER--------------------------------------------
function showWait(tDiv,title){
	$(tDiv).html('<div class="lspin"></div><i style="width:100%;float:left;font-size:25px;text-align:center;">'+title+'</i>');
}
function loadMainContent(theBut){
    
	'use strict';
	
    if(($(theBut).attr('id')==="m_6")||($(theBut).attr('id')==="mc_6")){
               confirmAction('Are you sure you want to log out?',function(){
				$.post($('#main_url').val()+'&sq=1',{vName : $(theBut).attr('id')},function(data){
					location.reload();
					
				});
				  

			   });
               return;
    }
	
	if($(theBut).attr('class')==="mnRow"){
		$('.uOpts').toggle('fast');
	}
    var the_title=$('#utab_'+$(theBut).attr("id").split('_')[1]).html();
    $('#quince_title').animate({marginLeft: "-200px"},null,null,function(){
        
            $('#quince_inner_content').html('<div id="form_row" style="width:100%;float:left;text-align:center;font-size:14px;"><i>Loading...</i><div>');
		    
		    if($(theBut).attr("id").split('_')[0]!=='tb'){
                $('#quince_title').html($("#"+$(theBut).attr("id").split('_')[0]+'_'+$(theBut).attr("id").split('_')[1]+" a").html());
				$('title').html($("#"+$(theBut).attr("id").split('_')[0]+'_'+$(theBut).attr("id").split('_')[1]+" a").html());
			}else{
				$('#quince_title').html(the_title);
				//$('#quince_title').html($('#m_'+$(theBut).attr("id").split('_')[1]+" a").html());
				$('title').html(the_title);
			}
            $('#sec_image').attr('class',"s_i"+$(theBut).attr("id").split("_")[1]);
            
            $('#quince_title').animate({marginLeft: "0px"});
        
		    //alert('https://app.duatech.co.ke/'+$('#main_url').val()+'&sq=1');
            $.post($('#main_url').val()+'&sq=1',{vName : $(theBut).attr('id'),isPhone:getPhoneAttr()},function(data){
                
				
                var ob=jParser(data);
                
                if(ob.Status==="Success"){
                   $('#quince_inner_content').html(ob.Content);
                   $('.menu_loader').html(ob.TopMenu);
					
                   createScript(ob.DivSufix);
				   runAssist();
				   loadInnerFunc();
                   loadAfterAnimate();  
				   blink('.ch_dw');
                }
                
            });
            //loadRawAlerts();
        });
        
}
function loadAlerts(){
	'use strict';
	if($('#main_url').val()===undefined){
		return;
	}
	loadRawAlerts();
	setInterval(function(){
		  loadRawAlerts();
  },30000);
}
function loadRawAlerts(data){
	'use strict';
	$.post($('#main_url').val()+'&sq=25',{},function(data){
	var ob=jParser(data);
		if(ob.Status==="Success"){
			if(a_num<jParser(ob.Content).length){
                try{
                    aud.play();
                }catch(e){
                    
                }
				
				a_num=jParser(ob.Content).length;
			}else{
				a_num=jParser(ob.Content).length;
			}
			var arrOb=jParser(ob.Content);
				$('.at_icon').css('display','none');
			    $('.at_picon').css('display','none');
             
				for(var i=0;i<arrOb.length;i++){
					
					if((parseInt(arrOb[i].split('_')[1])>0)|((parseInt(arrOb[i].split('_')[2])>0)&(parseInt(arrOb[i].split('_')[1])>0))){
					 
					  if($('#al_'+arrOb[i].split('_')[0]).html()!=undefined){
						 var tot=0;
						 for(var c=0;c<arrOb.length;c++){if(arrOb[i].split('_')[0]===arrOb[c].split('_')[0])tot+=parseFloat(arrOb[c].split('_')[1]);}
					     $('#al_'+arrOb[i].split('_')[0]).css('display','block');
					     $('#al_'+arrOb[i].split('_')[0]).html(tot);
					   }
					
					   if($('#pIc_'+arrOb[i].split('_')[2]).html()!=undefined){
					     $('#pIc_'+arrOb[i].split('_')[2]).css('display','block');
					     $('#pIc_'+arrOb[i].split('_')[2]).html(arrOb[i].split('_')[1]);
					   }
						
					   if($('#tm_'+arrOb[i].split('_')[2]).html()!=undefined){
					     $('#tm_'+arrOb[i].split('_')[2]).css('display','block');
					     $('#tm_'+arrOb[i].split('_')[2]).html(arrOb[i].split('_')[1]);
					   }
					
					   if($('#cal_'+arrOb[i].split('_')[0]).html()!=undefined){
						 var tot=0;
						 for(var c=0;c<arrOb.length;c++){if(arrOb[i].split('_')[0]===arrOb[c].split('_')[0])tot+=parseFloat(arrOb[c].split('_')[1]);}
						 $('#cal_'+arrOb[i].split('_')[0]).css('display','block');
					     $('#cal_'+arrOb[i].split('_')[0]).html(tot);	
					   }
					}else{
						$('#al_'+arrOb[i].split('_')[0]).css('display','none');
						$('#cal_'+arrOb[i].split('_')[0]).css('display','none');
						$('#tm_'+arrOb[i].split('_')[2]).css('display','none');
					}
				
				}
			if(ob.TopMenu>0){
				$('#mAll').css('display','block');
				$('#mAll').html(ob.TopMenu);
				$('.mNDiv').css('opacity','1');
			}else{
				$('#mAll').css('display','none');
				$('.mNDiv').css('opacity','0.3');
			}
		}
		
		//loadAlerts();
	});
}

function animateCentral(vnk,opt){
    try{
        
     $(vnk).animate({height : "40px"},null,null,function(){
        
        $(vnk).html('<div id="form_row" style="width:100%;float:left;text-align:center;font-size:14px;"><i>Loading...</i><div>');
        
        $.post($('#main_url').val()+'&sq=2',{vOpt : opt},function(data){
            var ob=jParser(data);
            if(ob.Status=="Success"){
                $(vnk).html(ob.Content);
                $(vnk).animate({height : $(vnk+" div").height()+"px"});
                if(opt==undefined)
                $('.menu_loader').html(ob.TopMenu);
                loadAfterAnime();
                activateMenuItem();
            }
        });
        
     });  
      
     }catch(e){
     
        alert(e);
     
     } 
}
function loadAfterAnimate(){
    $('#sMode').change(function(){
        if($('#sMode').val()==2){
            $('#attach_file').show('fast');
            $('#ref_row').hide('fast',function(){
                $(".quinceInner").animate({height : $(".quinceInner div").height()+"px"});
            });
        }else{
            $('#ref_row').show('fast');
            $('#attach_file').hide('fast',function(){
                $(".quinceInner").animate({height : $(".quinceInner div").height()+"px"});
            });
        }
    });
}
function loadInnerContent(target,opts){
    
	'use strict';
	
    $(target).html('<i style="width:100%;float:left;text-align:center;margin-top:20px;">Loading...</i>');
    
    $.post($('#main_url').val()+'&sq=4',{vOption : opts},function(data){
        
        var ob=jParser(data);
        if(ob.Status==="Success"){
            $(target).html(ob.Content);
			rawInnerFunction();
        }
        
    });
}
function loadInnerContent2(target,opts){
    
	'use strict';
	
    $(target).html('<i style="width:100%;float:left;text-align:center;margin-top:20px;">Loading...</i>');
    
    $.post($('#main_url').val()+'&sq=4',opts,function(data){
        
        var ob=jParser(data);
        if(ob.Status==="Success"){
            $(target).html(ob.Content);
			rawInnerFunction();
        }
        
    });
}
//-------------------------------------------------------------------------BUTTON INNER FUNCTION-------------------------------------------------
function loadInnerFunc(){
    
	'use strict';
	
    rawInnerFunction();
    
    activateMenuItem();
    
}

function rawInnerFunction(){
    'use strict';
    $('#sco').change(function(){
        animateCentral('.quinceInner',$(this).val());
    });
    
	$('#invest_select').unbind();
    $('#invest_select').change(function(){
         loadInnerContent2('#listWrap',{vOption : $(this).val(),sOp : $('#sOp .quince_select').val()});
        //animateCentral('#listWrap',$(this).val());
    });
	
	$('#sOp .quince_select').unbind();
    $('#sOp .quince_select').change(function(){
         loadInnerContent2('#listWrap',{vOption : $('#invest_select').val(),sOp : $('#sOp .quince_select').val()});
        //animateCentral('#listWrap',$(this).val());
    });
    
	$('.acTab').click(function(){
		if($(this).attr("id")=="tb_104"){
			runTablesUpdates();
		}else{
			 loadMainContent(this);        
		}
      
    });
	$('.quince_date').datepicker({dateFormat : 'dd/mm/yy'})
	
	 
	
    //$('.labour_date').unbind();
	
	//$('.trDate').unbind();
	
	$('#vRow .quince_date,#vRow #sf_qs_site').change(function(){
		loadLabourReport(this);
	});
	
	
	$('.labour_date,.trDate').datepicker({dateFormat : 'dd/mm/yy',maxDate : 0});

	$('.labour_date').change(function(){
		if($('.labour_date').val().trim()!==""){
		 loadLabour(this);
		}
	});
    
	$('.inv_select').unbind();
	$('.inv_select,.trDate').change(function(){
		loadInventory(this);
	});
	$('.prNs').unbind().click(function(){
		loadScheduleOfWorks(this);
	 });
	
	$('.pur_select').unbind();
	$('.pur_select').change(function(){
		loadPurchases(this);
	});
	
	$('.l_select').unbind();
	$('.l_select,#lSites .quince_select').change(function(){
		if($('.labour_date').val().trim()!==""){
		   loadLabour($('.labour_date'));
		}
	});
	
    $('#findM').keyup(function(){
        
        $('#membContent').html('<div id="form_row" style="width:100%;float:left;text-align:center;font-size:14px;"><i>Loading...</i></div>');
        
        $.post($('#main_url').val()+'&sq=3',{sSource : $(this).attr('id')},function(data){
            
            var ob=jParser(data);
            
            if(ob.Status==="Success"){
                
                $('#membContent').html(ob.Content);
            
            }
            
        });
        
    });
    
    $('.mainDashTab').click(function(){
        loadMainTabOption(this);
    });
    
	closeDetFunction();
	
	$('.xpand').click(function(){
		var theBt=this;
		//$('.dRw').hide('slow',function(){
		  setTimeout(function(){$('#dRw_'+$(theBt).attr('id').split('_')[1]).show('slow',function(){
			  $('#scont_'+$(theBt).attr('id').split('_')[1]).html('<i style="float:left;width:100%;">Loading...</i>');
			  $.post($('#main_url').val()+'&sq=14',{srch : $(theBt).attr('id').split('_')[2],rid : $(theBt).attr('id').split('_')[3],zrate : $(theBt).attr('id').split('_')[4],rqty : $(theBt).attr('id').split('_')[5],bt:$(theBt).attr('id')},function(data){
				  var ob=jParser(data);
				  if(ob.Status=='Success'){
					   $('#scont_'+$(theBt).attr('id').split('_')[1]).html(ob.Content);
				  }
			  });
		  })},500);
		//});
	});
	
	loadEquipFunctions();
	
	$('.u_more').click(function(){
		var theBtn=this;
		innerListPanel(this,22,{userId : $(theBtn).attr('id').split('_')[1]},function(){
			$('.dCont .form_button').click(function(){
			    processUserSettings(this);
			});
			$('.savenSet').click(function(){
		      updateNotification(this);		
	        });
		});
	});
    
	$('.xpand2').click(function(){
		var theBt=this;
		//$('.dRw').hide('slow',function(){
		  setTimeout(function(){$('#dRw_'+$(theBt).attr('id').split('_')[1]).show('slow',function(){
			  $('#scont_'+$(theBt).attr('id').split('_')[1]).html('<i style="float:left;width:100%;">Loading...</i>');
			  $.post($('#main_url').val()+'&sq=15',{srch : $(theBt).attr('id').split('_')[2],rid : $(theBt).attr('id').split('_')[3],zrate : $(theBt).attr('id').split('_')[4],rqty : $(theBt).attr('id').split('_')[5],lid : $(theBt).attr('id').split('_')[1]},function(data){
				  var ob=jParser(data);
				  if(ob.Status==='Success'){
					   try{
					 					  
					   $('#scont_'+$(theBt).attr('id').split('_')[1]).html(ob.Content);
					   loadMiniFunctions();
					   }catch(e){
						   loadMiniFunctions();
					   }
				  }
			  });
		  });},500);
		//});
	});
	$('.shwDet').unbind();
	$('.shwDet').click(function(){
		showItemDetails(this);
	});
	
    $('#accFrom,#accTo').blur(function(){ 
             
       searchDateFunction(this,['#accFrom','#accTo'],'#payHistCont');
       
    });
    
    $('#rFrom,#rTo').blur(function(){
        searchDateFunction(this,['#rFrom','#rTo'],'#qReport');
    });
	
	
	$('.cells').unbind().click(function(){
		cRow=$(this).parent().attr('id');
		activateCells(this);
	});
	
	;
	
	$('.xMenuInner').click(function(){
		if($(this).attr('id')=="abv"){
		  addRowFunction('Before');
		}else{
		  addRowFunction('After');	
		}
	});
	
	$('.cells,.xMenu').contextmenu(function(){
		return false;
	})
	
    $('.cells').mousedown(function(ev){
		cRow=$(this).parent().attr('id');
		if(ev.which===3){
			try{
			var leftVal = ev.pageX - ($('.xMenu').width()) + "px";
            var topVal = ev.pageY + "px";
            $('.xMenu').css({ left: leftVal, top: topVal });
			$('.xMenu').show('fast',function(){$('.xMenuInner').fadeIn('fast');});
			}catch(e){alert(e);}
		}
	});
	
	$('.xMenu').click(function(){
		$('.xMenuInner').fadeOut('fast',function(){$('.xMenu').hide('fast')});
	});
	
	$('.addBtn').unbind().click(function(){
		createTableElement(true);
	});
	$('.excSet').click(function(){
		expandRetractDiv('.excSetWrap');
	});
	
	$('#fReq .quince_select').change(function(){
		loadFRequestPanel(this);
	});
	//---------------------------------------SEARCH FUNCTIONS--------------------------------------
	
	$('.sIcon').click(function(){
		$('.sc').show('fast',function(){
		});
	});
    
	$('.cls').click(function(){
		$('.sc').hide('fast');
	});
	
	loadPrintingFunctions();
	
	$('#wrapper').click(function(){
       var theWrap=this;
	   $('.thePopW').fadeOut('500',function(){ $(theWrap).fadeOut('500'); }); 
    });
	
	//--------------------------------------END  SEARCH FUNCTIONS------------------------------------
	//------------------------------------------SAVE F LIST------------------------------------------
	loadSaveListFunctions();
	//------------------------------------------END SAVE F LIST---------------------------------------
	
    $('.iprint').click(function(){
		var theBut=this;
		popWindow('.thePop',function(){
			try{
			 $('.thePop').html('<iframe class="nframe" id="nframe_1" href=""></iframe>');	
			 $('#nframe_1').attr('src','?tp='+$(theBut).attr('id').split('_')[1]+'&ptyp='+$(theBut).attr('id').split('_')[0]);
				
			}catch(e){
				alert(e);
			}
		});
	});
	//---------------------------------------------Mini Search-------------------------------------------
	loadMinSearch();
	
	//-----------------------------------------------End Mini Search--------------------------------------
	
	loadFormFunctions();
	
	$('.u_more').click(function(){
		
		
	});
	
	$('.auth').click(function(){
		authorizeReq(this);
	});
	$(".editAuth ,.delAuth").unbind().click(function(){
         updatedEquipmentFunction(this);
	});
	
	$('.delMp').click(function(){
	    deleteMPayment(this);
	});
	
	$('#req_filters .quince_select').unbind();
	
	$('#req_filters .quince_select').change(function(){
		loadReqList(this);
	});
	
	$('.prN').click(function(){
		loadPrReq(this);
	});
	
    
	
	
	$('.bBut').click(function(){
		loadPrReq(this);
	});
	
	$('.mpSite .quince_select').unbind().change(function(){
		let bt=this;
		manageSelections(bt).then(function(){
			loadDelReqs(bt);
		})
		
	});
	
	$('.resBtn').unbind();
	$('.resBtn').click(function(){
		if($('.labour_date').val()===""){	return;}
		
		var btn=this;
		confirmAction('Are you sure you want to request deletion for this entry?',function(){
			resetEntries(btn);
		});
	});
	
	$('.appR,.decL').click(function(){
		approveDecline(this);
	});
	
	$('.excelImp').click(function(){
		loadExRatesImport();
	});
	
	//numbersOnly('.descField');
	
	//numbersOnly('.rateField');
	
	$('.loadRate').click(function(){
		loadItemRatesToList();
	});
	
	$('.clM2').click(function(){
		$('.impRateWrap').hide('slow');
	});
	
	loadPurchaseFunctions();
	
	$('#equip .quince_select').change(function(){
		loadEquipList();
	});
	
	$('#opetty .quince_select').change(function(){
	   loadPettyReceived();	
	});
	
	$('#pFromDate,#pToDate').change(function(){
		
		setTimeout(function(){
		
		if(($('#pFromDate').val()!=="")&&($('#pToDate').val()!=="")){	
			loadPettyReceived();
		}
			
		},500);
		
	});
	
	$('.addUser').click(function(){
		$('.nUser').show('slow',function(){
			$('.addUser').hide('slow');
		});
	});
	
	$('#vfy .quince_select').change(function(){
		loadVerifyPanel(this);
	});
	
	$('#update_prof').click(function(){
		updateProfileDetails(this);
	});
	
	$('.savenSet').click(function(){
		updateNotification(this);		
	});
	
	$('#updateEs').click(function(){
		saveEmailSettings(this);
	});
	
	$('#sndEmail').click(function(){
		sendTestEmail(this);
	});
	
	$('#testSms').click(function(){
		sendTestSms(this);
	});
	
	$('.edpro').click(function(){
		editProject(this);
	});
	
	$('.edwk').click(function(){
		editWorkSch(this);
	});
	
	$('.app_button').click(function(){
		approveOR(this);
	});
	
	$('#oProc .quince_select').unbind();

	$('#oProc .quince_select').change(function(){
		loadORequest(this);
	});
	
	//$('.fbdFrm,.tbdTo').unbind();
	$('.fbdFrm,.tbdTo').blur(function(){
		loadIncomeRecords(this);
	});
	
	$('.pettyFrm,.pettyTo').blur(function(){
		 loadPettyCashRecords();
	});
	
	$('#pSite .quince_select').change(function(){
		loadPettyCashRecords();
	});
	
	$('#inSite .quince_select').change(function(){
		loadIncomeRecords(this);
	});
	
	$('#inOpt .quince_select').unbind();
	
	$('#inOpt .quince_select').change(function(){
		loadIncomeView(this);
	});
	
	$('#inPetty .quince_select').unbind();
	
	$('#inPetty .quince_select').change(function(){
		loadPettyCashView(this);
	});
	
	$('.acheck').change(function(){
		changeUserAlertStatus(this);
	});
	
	$('.addPos').click(function(){
		addUserPosition(this);
	});
	
	$('#addAl').unbind().click(function(){
		addApprovalLevel(this);
	});
	
	$('#txtRFind').keyup(function(){
		searchRItem();
	});
	
	$('.addCoo').unbind();
	$('.addCoo').click(function(){
		$('.newSch').toggle('fast');
	});
	
	$('.lvOpts').unbind();
	$('.lvOpts').click(function(){
				 
		 $('#lvOpp_'+$(this).attr('id').split('_')[1]).toggle('fast');
		
	});
	
	$('.lImage').unbind().click(function(){
		loadComponentImage(this);
	});
	
	dynamicLevels();
	
	numbersOnly('.unitQty');
	
	addFileFunction();
	
	attachViewFmFunctions();
	
	loadScannedMP();
}
function loadComponentImage(btn){
	
	popWindow('.thePopW',function(){
		
		$('.thePopW').html('loading...');
		
		$.post($('#main_url').val()+'&sq=75',{compId:$(btn).attr('id').split('-')[1]},function(data){
			
			var ob=jParser(data);
			
			if(ob.Status="Success"){
				$('.thePopW').html(ob.Content);
				loadFormFunctions();
			}
			
		});
		
	});
	
}
function loadScannedMP(){
			  $('.mpReq').click(function(){
			  popWindow('.thePopW',function(){
				  $('.thePopW').html('<div class="qs_row" style="text-align:center;margin-top:200px;text-style:italic ;">Loading...</div>');
				  
				  $.post($('#main_url').val()+'&sq=47',{siteId : $('#sSite').val()},function(data){
					   var ob=jParser(data);
					   if(ob.Status=="Success"){
						  $('.thePopW').html(ob.Content);
						   $('.shwDet').click(function(){
		                     showItemDetails(this);
							 closeDetFunction();
	                       });
						   $('.attDiv').click(function(){
				                 $('.ldiv').attr('id','adiv_'+$(this).attr('id').split('_')[1]);
							     $('.ldiv').fadeIn('fast',function(){
								 $('.thePopW').fadeOut('fast',function(){
									 $('#wrapper').fadeOut('fast');
									 $('.kindNote').fadeOut('fast',function(){
										 $('.saveFList').fadeIn('fast');
										 $('.hideFirst').fadeIn('fast');
									 });
								 });
								 });
						   });
						   $('.ldiv').click(function(){
							   var bn=this;
							   popWindow('.thePopW',function(){
								   $('.thePopW').html('<div class="qs_row" style="text-align:center;margin-top:200px;text-style:italic ;">Loading...</div>');
								   $.post($('#main_url').val()+'&sq=48',{rId : $(bn).attr('id').split('_')[1]},function(data){
									   var ob=jParser(data);
									   if(ob.Status=="Success"){
										   $('.thePopW').html(ob.Content);
									   }
								   });
							   });
						   });
					   }
				  });
				  
			  });
		  });
}


function dynamicLevels(){
	'use strict';
	
	$('.dyLev').unbind();
	
	$('.dyLev').click(function(){
		
		$('#'+$(this).attr('id').replace('chckdl','prdl')).html('Updating...');
		
		var bt='#'+$(this).attr('id').replace('chckdl','prdl');
		
		var st=0;
		
		if($(this).prop('checked'))
			st=1;
		
		$.post($('#main_url').val()+'&sq=74',{app : $(bt).attr('id'),stat : st},function(data){
			
			$(bt).html('');
		});
	});
	
}
function searchRItem(){
	
	'use strict';
	
	$('#sPfind').html("Loading...");
	
	 $.post($('#main_url').val()+'&sq=67',{searchF : $('#txtRFind').val()},function(data){
		 
		 var ob=jParser(data);
		 if(ob.Status==="Success"){
			 $('#sPfind').html(ob.Content);
		 }
		 
	 });
	
}
function changeUserAlertStatus(btn){
	'use strict';
	$("#ap_"+$(btn).attr('id').split('_')[1]).fadeIn('fast',function(){
	  $("#ap_"+$(btn).attr('id').split('_')[1]).html('Updating...');
	  var chkd=0;
	  if($(btn).prop('checked')){
		  chkd=1;
	  }
	  $.post($('#main_url').val()+'&sq=64',{alertType:$(btn).attr('id').split('_')[1],stat : chkd},function(data){
		var ob=jParser(data);
		if(ob.Status==="Success"){
			$("#ap_"+$(btn).attr('id').split('_')[1]).html(ob.Content);
		}
	  });
	});
}
function loadIncomeView(btn){
	'use strict';
	$('.inco').html('<div class="q_row">Loading...</div>');
	$.post($('#main_url').val()+'&sq=60',{theBtn : $(btn).val()},function(data){
		var ob=jParser(data);
		if(ob.Status==="Success"){
			$('.inco').html(ob.Content);
			rawInnerFunction();
		}
	});
	
}
function loadPettyCashView(btn){
	'use strict';
	$('#pCo').html('<div class="q_row">Loading...</div>');
	$.post($('#main_url').val()+'&sq=62',{theBtn : $(btn).val()},function(data){
		var ob=jParser(data);
		if(ob.Status==="Success"){
			$('#pCo').html(ob.Content);
			rawInnerFunction();
		}
	});
}
function addApprovalLevel(btn){
	'use strict';
	
	if($('#txtNa').val()==""){
		alert('Enter title');
		return;
	}
	
	if($('#levelA').val()==-1){
		alert('Select Level');
		return;
	}
	
	$(btn).val('Processing...');
	
	$.post($('#main_url').val()+'&sq=69',{title : $('#txtNa').val(),tLevel : $('.quince_select').val()},function(data){
		
		var ob=jParser(data);
		if(ob.Status=="Success"){
		  $('#aLev').html(ob.Content);
		  $(btn).val('Add Level');
		  $('#txtNa').val("");
		  activateDeleteFunction();
		  $('.shwDet').click(function(){
		    showItemDetails(this);
	      });
		}else{
			showMessage(ob.Content);
		}
	});
	
}
function addUserPosition(btn){
	'use strict';
	confirmAction('Are you sure you want to add this position?',function(){
		$(btn).val('Processing...');
		$.post($('#main_url').val()+'&sq=66',{pos_title : $('#txtPos').val()},function(data){
			var ob=jParser(data);
			if(ob.Status==="Success"){
			  $('#mPosition').html(ob.Content);
			  $(btn).val('+Add Position');
			  activateDeleteFunction();
			  loadMiniFunctions();
			  $('.shwDet').unbind();
			  $('.shwDet').click(function(){
		        showItemDetails(this);
				closeDetFunction();
	          });
			  //addRowFunction();
			  $('#txtPos').val("");
			}
		});
	});
}
function loadIncomeRecords(btn){
	'use strict';
	$('#vincome_list').html('<i>Loading....</i>');
	setTimeout(function(){
		$.post($('#main_url').val()+'&sq=61',{fromDate : $('#theDate').val(),toDate : $('#theDate2').val(),siteId : $('#inSite .quince_select').val() },function(data){
		var ob=jParser(data);
		if(ob.Status==="Success"){
		  $('#vincome_list').html(ob.Content);	
		  loadPrintingFunctions();
		}
	    });	
	},500);
	
}
function loadPettyCashRecords(){
	'use strict';
	$('.listPetty').html('<i>Loading....</i>');
	setTimeout(function(){
	$.post($('#main_url').val()+'&sq=63',{pFromDate : $('.pettyFrm').val(),pToDate : $('.pettyTo').val(),theSite : $('#pSite .quince_select').val()},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status==="Success"){
			$('.listPetty').html(ob.Content);
			loadPrintingFunctions();
		}
		
	});},500);	
}
function loadORequest(btn){
	
	'use strict';
	
	$('.oReq').html('<div class="q_row">Loading....</div>');
	
	$.post($('#main_url').val()+'&sq=59',{utype : $(btn).val()},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status==="Success"){
			$('.oReq').html(ob.Content);
			$('.vReq').click(function(){
				loadMenuContent(this);
			});
		}
		
	});
	
}
function approveOR(btn){
	'use strict';
	
	$(btn).html('<i>Loading...</i>');
	
	$.post($('#main_url').val()+'&sq=58',{pid : $(btn).attr('id')},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status==="Success"){
			$(btn).fadeOut('slow');
			showMessage(ob.Content);
		}
		
	});
	
}
function loadMinSearch(){
	
	'use strict';
	
	$('.schUField').click(function(){
		processSearch(this);
	});
	
	$('.scField').keyup(function(){
		
		loadSContent($('#'+$(this).attr('id').replace('cf_','')),$(this).val());
		//$('#'+$(this).attr('id').replace('pr_','')).val();
	});
	
}
function showItemDetails(btn){
	'use strict';
		//$('.dRw').hide('slow',function(){
		  setTimeout(function(){$('#dRw_'+$(btn).attr('id').split('_')[1]).show('slow',function(){
			  $('#scont_'+$(btn).attr('id').split('_')[1]).html('<i style="float:left;width:100%;">Loading...</i>');
			  $.post($('#main_url').val()+'&sq=45',{srch : $(btn).attr('id'),rid : $(btn).attr('id').split('_')[3],zrate : $(btn).attr('id').split('_')[4],rqty : $(btn).attr('id').split('_')[5],lid : $(btn).attr('id').split('_')[1]},function(data){
				  var ob=jParser(data);
				  if(ob.Status==='Success'){
					   try{				  
					   $('#scont_'+$(btn).attr('id').split('_')[1]).html(ob.Content);
					   loadMiniFunctions();
						rawInnerFunction();
						activateDeleteFunction(true);
					   }catch(e){
						   loadMiniFunctions();
					   }
				  }
			  });
		  });},500);
		//});
}
function loadVerifyPanel(btn){
	
	'use strict';
	
	$('.csite').html('<i style="width:100%;text-align:center;float:left;">Loading...</i>');
	
	$.post($('#main_url').val()+'&sq=53',{siteId : $(btn).val()},function(data){
		var ob=jParser(data);
		if(ob.Status==="Success"){
			$('.csite').html(ob.TopMenu);
			$('#verList').html(ob.Content);
			loadVerFunctions()
			//loadMinSearch();
		}
		
	});
	
	
}
function loadVerFunctions(){
	'use strict';
	$('#vMp .quince_select').change(function(){
		$('.listWrap').html('<i style="width:100%;float:left;text-align:center;">Loading...</i>');
		$.post($('#main_url').val()+'&sq=54',{mpId : $(this).val()},function(data){
			
			var ob=jParser(data);
			
			if(ob.Status==="Success"){
				$('.listWrap').html(ob.Content);
				rawInnerFunction();
			}
			
		});
	});
}
function editProject(btn){
	popWindow('.thePop',function(){
		$('.thePop').html('<i style="margin-top:20%;float:left;width:100%;text-align:center;font-size:22px;">loading...</i>');
		
		$.post($('#main_url').val()+'&sq=56',{pid : $(btn).attr('id').split('_')[1]},function(data){
			
			var ob=jParser(data);
			if(ob.Status==="Success"){
				$('.thePop').html(ob.Content);
				$('.quince_date').datepicker({dateFormat: 'dd/mm/yy'});
				$('#updatePro').click(function(){
					var btn=this;
					var stat=true;
					$('#ptitle').css('border','1px solid #bbb');
					$('#sdate').css('border','1px solid #bbb');
					$('#edate').css('border','1px solid #bbb');
					$('#elocation').css('border','1px solid #bbb');
					
					if($('#ptitle').val().trim()===""){
						$('#ptitle').css('border','1px solid #f00');
						stat=false;
					}
					if($('#sdate').val().trim()===""){
						$('#sdate').css('border','1px solid #f00');
						stat=false;
					}
					if($('#edate').val().trim()===""){
						$('#edate').css('border','1px solid #f00');
						stat=false;
					}
					if($('#elocation').val().trim()===""){
						$('#elocation').css('border','1px solid #f00');
						stat=false;
					}
					
					if(!stat){
						showMessage('<div id="warning_msg">Enter field marked in red.</div>','.mText');
						return;
					}
					
					$(this).val('Updating.....');
					
					$.post($('#main_url').val()+'&sq=55',{prid:$('#proId').val(),pTitle:$('#ptitle').val().trim(),desc : $('#pDesc').val(),sDate :$('#sdate').val(),eDate : $('#edate').val(),location: $('#elocation').val().trim()},function(data){
						
						var ob=jParser(data);
						
						if(ob.Status==="Success"){
							showMessage(ob.Content,'.mText');
							$(btn).val('Update Project');
							$.post($('#main_url').val()+'&sq=57',{},function(data){
								
								var ob=jParser(data);
								
								if(ob.Status==="Success"){
									$('#refCont').html(ob.Content);
									$('.vReq').click(function(){
										loadMenuContent(this);
									});
									$('.edpro').click(function(){
										editProject(this);
									});

								}
								
							});
						}
						
					});
					
				});
			}
			
		});
		
	});
}
function editWorkSch(btn){
	'use strict';
	popWindow('.thePop',function(){
		$('.thePop').html('<i style="margin-top:20%;float:left;width:100%;text-align:center;font-size:22px;">loading...</i>');
		
		$.post($('#main_url').val()+'&sq=71',{wkshId : $(btn).attr('id').split('_')[1]},function(data){
			var ob=jParser(data);
			if(ob.Status==="Success"){
				$('.thePop').html(ob.Content);
				rawInnerFunction();
				$(".sub-btn").click(function(){
					refined(this);
				});
				$('.updateComp').click(function(){
					updateWkComponent(this);
				});
			}
		});
		
	});
	
}
function updateWkComponent(btn){
	'use strict';
	
	var oldVal=$(btn).val();
	
	$(btn).val('Processing....');
	
	$.post($('#main_url').val()+'&sq=73',{title : $('#wkTitle').val(),fromDate : $('#wkFrmDate').val(),toDate : $('#wkToDate').val(),tid : $(btn).attr('id').split('_')[1],pid : $(btn).attr('id').split('_')[2]},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status==="Success"){
			showMessage(ob.Message);
			$('.lstC').html(ob.Content);
			$(btn).val(oldVal);
			rawInnerFunction();
		}
		
	});
	
}
function saveEmailSettings(btn){
	
	confirmAction("Are you sure you want to save email settings?",function(){
		
		$(btn).html('Saving....');
		
		$.post($('#main_url').val()+'&sq=43',{port : $('#portNo').val(),host : $('#smtphost').val(),email : $('#emailAdd').val(),password : $('#pass').val()},function(data){
			
			$(btn).html('Update Details');
			
			var ob=jParser(data);
			
			if(ob.Status=="Success"){
			  showMessage(ob.Content);	
			}
			
		});
		
	});
	
}
function sendTestEmail(btn){
	'use strict';
	$(btn).html('<i>Sending...</i>');
	
	$.post($('#main_url').val()+'&sq=44',function(data){
		
	   var ob=jParser(data);
		if(ob.Status==="Success"){
		   showMessage(ob.Content);	
		   $(btn).html('<i>Test Email</i>');
		}
	});
	
}
function sendTestSms(btn){
	'use strict';
	$(btn).html('<i>Sending...</i>');
	
	$.post($('#main_url').val()+'&sq=52',function(data){
	   var ob=jParser(data);
		if(ob.Status==="Success"){
			alert(ob.Content);
		   //showMessage(ob.Content);	
		   $(btn).html('Send Test Message');
		}
	});
}
function updateNotification(btn){
	
	'use strict';
	
	    var smsOp=0;
		
		var emailOp=0;
	
		if($('.emailN_'+$(btn).attr('id').split('_')[1]).is(':checked')){
			emailOp=1;
		}
		
		if($('.smsN_'+$(btn).attr('id').split('_')[1]).is(':checked')){
			 smsOp=1;
		}
		$(btn).html('Processing...');
		$.post($('#main_url').val()+'&sq=42',{sms : smsOp,email : emailOp,userId : $(btn).attr('id').split('_')[1]},function(data){
			var ob=jParser(data);
			if(ob.Status==="Success"){
			   $(btn).html('Update Details');
			   showMessage(ob.Content,'#us_'+$(btn).attr('id').split('_')[1]);
			 }
		});
}
function updateProfileDetails(btn){
	'use strict';
	confirmAction('Are you sure you want to update details? Change of email address will affect your login credentials.',function(){		
		$(btn).html('Processing...');
		$.post($('#main_url').val()+'&sq=41',{email : $('#uEmail').val(),phone : $('#uPhone').val()},function(data){
		  var ob=jParser(data);
		  if(ob.Status=="Success"){
			showMessage(ob.Content);
			$(btn).html('Update Details');
			if(ob.TopMenu=="Refresh")
			 location.reload();
		  }else{
			showMessage(ob.Content);
			$(btn).html('Update Details');  
		  }
		});
	});
}
function loadSaveListFunctions(){
	'use strict';
	$('.saveFList,.saveFAList').unbind();
	$('.saveFList,.saveFAList').click(function(){
		let btn=this;
		saveListData(btn,'6',true).then(function(){
			updatedSaveListData(btn);
		});
	});
	
	$('.saveData').click(function(){
		saveFormData(this);
	});
	$('.vDiv').click(function(){
		var theBut=this;
		$('.dRow').hide(function(){
			setTimeout(function(){
				$("#pd_"+$(theBut).attr('id').split('_')[1]).show('slow');
			},500);
		});
	});
}
function loadLabourUploader(){
	
	"use strict";
	$('.lForm').html('<i style="width:100%;float:left;text-align:center;margin-top:50px;">Loading...</i>');
	
	$.post($('#main_url').val()+'&sq=38',{},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status==="Success"){
		  	$('.lForm').html(ob.Content);
			loadFormFunctions();
			addFileFunction();
			loadSaveListFunctions();
			//rawInnerFunction();
		}
		
	});
	
}
function loadPettyReceived(){
	"use strict";
	$('#purWrap').html('<i style="width:100%;float:left;text-align:center;margin-top:50px;">Loading...</i>');
	
	$.post($('#main_url').val()+'&sq=36',{site : $('#opetty .quince_select').val(),fromDate: $('#pFromDate').val(),toDate : $('#pToDate').val()},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status==="Success"){
		  	$('#purWrap').html(ob.Content);
			loadPrintingFunctions();
		}
		
	});
	
}
function closeDetFunction(){
	'use strict';
	$('.clM').click(function(){
		$('#dRw_'+$(this).attr('id').split('_')[1]).hide('slow',function(){
			$('#scont_'+$(this).attr('id').split('_')[1]).html("");
		});
	});
}
function loadPrintingFunctions(){
	'use strict';
	$('.prbut,.excelBtn').click(function(){
		var btn=this;
		popWindow('.thePopW',function(){
			loadPreview(btn);
		});
	});
}
function loadEquipFunctions(){
	$('.mEQ').click(function(){
		var theBt=this;
		innerListPanel(this,16,{eid : $(theBt).attr('id').split('_')[1]},function(){
			$('.dCont .form_button').click(function(){
						   assignELocation(this);
			 });
		});
		
	});
}
function loadEquipList(){
	
	$('#equipWrap').html('<i style="width:100%;float:left;margin-top:50px;text-align:center;">Loading...</i>');
	
	$.post($('#main_url').val()+'&sq=37',{siteId : $('.quince_select').val()},function(data){
		var ob=jParser(data);
		if(ob.Status=="Success"){
			$('#equipWrap').html(ob.Content);
			loadEquipFunctions();
			loadPrintingFunctions();
			closeDetFunction();
			activateDeleteFunction();
		}
	});
}
function loadItemRatesToList(){
	'use strict';
	var arr={'A':0,'B':1,'C':2,'D':3,'E':4,'F':5,'G':6,'H':7,'I':8,'J':9,'K':10,'L':11,'M':12,'N':13,'O':14,'P':15,'Q':16,'R':17,'S':18,'T':19,'U':20,'V':21,'W':22,'X':23,'Y':24,'Z':25};
	var rows=$('.trow').toArray();
	
	for(var i=0;i<rows.length;i++){
	  var rowCells= $('#'+$(rows[i]).attr('id')+' .cells').toArray();
	   	   var rowsI=$('.trow2').toArray();
		   for(var x=0;x<rowsI.length;x++){
			   try{
			   var iCells=$('#'+$(rowsI[x]).attr('id')+' .cells2').toArray();
    
				   
			  if($(rowCells[1]).html().toLocaleLowerCase()===$(iCells[arr[$('.descField').val().toUpperCase()]]).html().toLocaleLowerCase()){
				// try{
	            if($(rowCells[1]).html().trim()!==""){  $(rowCells[4]).html(numeral($(iCells[arr[$('.rateField').val().toUpperCase()]]).html().replace(',','')).format('0,0.00'));//$(iCells[$('.rateField').val()]).html();
			     $(rowCells[fCol3]).html(numeral(parseFloat($(rowCells[fCol1]).html().replace(',',''))*parseFloat($(rowCells[fCol2]).html().replace(',',''))).format('0,0.00'));
				
				 $(rowCells[6]).html($(iCells[arr[$('.remarkField').val().toUpperCase()]]).html());
													 
				}
				 //}catch(e){alert(e);}
			  }
			 }catch(e){
				 
			 }
	   
	        }
    }
	calculateTotal();
	$('.impRateWrap').hide('slow');
}
function loadExRatesImport(){
	$('.impRateWrap').show('slow');
}
function loadFormFunctions(){
	$('#nFile').change(function(){
		
		$('.fLabel').html($(this).val());
		$('#nFileform').attr('action',$('#main_url').val()+'&sq=12&si='+$('.fLabel').attr('id')+'&fid='+$(this).attr('name').split('_')[1]);
		
		$('.fUp').fadeIn('slow');
		
		//$('#nFileform').submit();
		
		//$('.prevForm').html('<iframe src="'+$('#nFile').val()+'"></iframe>');
		
	});
	
	$('.fUp').click(function(){
		var bnt=this;
		$('#nfup').css('border','1px solid #ddd');
		
		if($('#nfup').val()!=undefined){
			if($('#nfup').val()==""){
			  $('#nfup').css('border','1px solid #f00');
				alert('<div style="color:#f00;">Enter file description!</div>')
				return false;
			}
		}
		
		confirmAction('Are you sure you want to submit?',function(){
			$(bnt).fadeOut('slow');
			$('#nFileform').submit();	
		},true);
	
		//
	});
	
    $('#nFileform').ajaxForm({beforeSubmit:function(){
		
	},uploadProgress:function(ev,pos,total,per){
				
		$('.lstatus').html('<div class="a3-left a3-full"><div style="float:left;width:30px">Uploading....</div><div class="a3-blue a3-left" style="width:'+per+'%;height:20px;border-radius:4px;">'+per+'% </div></div>');
		
	},success : function(data){
		var jp=jParser(data);
		if(jp.Status=="Success"){
		  $('.lstatus').html('Upload Completed');
		  if(jp.TopMenu=="Prepend"){
		     $('#tFrame').prepend(jp.Content);
			 //$('.fLabel').val('Add Another File');
		  }else{
			 if(jp.TopMenu==="Done"){
				$('.fLabel').fadeOut('slow');
				showMessage('<div id="success_msg">'+jp.Message+'</div>');
			 }
			 $('#tFrame').html(jp.Content); 
		  }
		  if(jp.Message!==""){
		  $('.fLabel').html(jp.Message);
		  }
		  if($('#nfup').val()!==undefined){
			 $('#nfup').val('');
		  }
		}
	}});
	
	$('#excelForm').ajaxForm({beforeSubmit:function(){},uploadProgress :function(ev,pos,total,per){
		$('#pBarI').css('width',per+'%');
	},complete : function(data){
	  setTimeout(function(){
		  expandRetractDiv('.excSetLoadWrap');
	  },800);
	},success : function(data){
		$('#ReqList').html(data);
		$('.cells').unbind();
		$('.cells').click(function(){
		activateCells(this);
	    });
	    activateDeleteFunction();
		//addRowFunction();
		//alert(data);		
	}});
	
	$('#excelFile').change(function(){
		if($(this).val()!=""){
			expandRetractDiv('.excSetLoadWrap');
			var theId=$('.saveFList').attr('id');
			var buttons=$('.saveFList').toArray();
			
			if(buttons.length>1){
				theId=$(buttons[buttons.length-1]).attr('id');
			}
			
			if(theId==undefined){
			 theId=$('.saveFAList').attr('id')
			}
			
			$('#excelForm').attr('action',$('#main_url').val()+'&sq=5&si='+theId);
			$('#excelForm').submit();
		}
			
	});
	
	
}
function confirmAction(mess,okayCb,cancelBtn=null){

	$('.conBox').html('<div class="conA"></div><div class="butRow"><div class="canBtn">Cancel</div><div class="okBtn">OK</div></div>');
	
	$('.conA').html(mess);
	
	$('.conBox').css('top',(($(window).height()-$('.conBox').height())/2)+'px');
	
	$('.fadeDiv').fadeIn('fast',function(){
		$('.conBox').fadeIn('slow',function(){
			$('.okBtn').unbind();
			$('.okBtn').click(function(){
			  if(okayCb!==undefined)
				okayCb();
				closeConfirm();
			});
			
			$('.canBtn').unbind();
			$('.canBtn').click(function(){
			    $(".conBox").fadeOut("slow",function(){
			        $(".fadeDiv").fadeOut("fast",function(){
						console.log(cancelBtn);
			            if(cancelBtn !=undefined){
			               cancelBtn();
				           closeConfirm();
			            }
			        });
			        
				        
			    });
			  
			});
			
		});
	});
}
/*function confirmAction(mess,okayCb,cancelBtn,hasMatch){

	$('.conBox').html('<div class="conA"></div><div class="butRow"><div class="canBtn">Cancel</div><div class="okBtn">OK</div></div>');
	
	$('.conA').html(mess);
	
	$('.conBox').css('top',(($(window).height()-$('.conBox').height())/2)+'px');
	
	$('.fadeDiv').fadeIn('fast',function(){
		$('.conBox').fadeIn('slow',function(){
			$('.okBtn').unbind();
			$('.okBtn').click(function(){
			  if(okayCb!==undefined)
				okayCb();
				closeConfirm();
			});
			
			$('.canBtn').unbind();
			$('.canBtn').click(function(){
			  if(cancelBtn!==undefined)
				 //if(cancelBtn){
				  // cancelBtn();
				   closeConfirm();
				 //}
				 
			});
			
		});
	});
}*/
function alerts(mess,color){
	
	$('.conBox2').html('<div class="conA2"></div><div class="butRow"><div class="okOnly">OK</div></div>');
	
	$('.conA2').html(mess);
	
	$('.conBox2').css('top',(($(window).height()-$('.conBox2').height())/2)+'px');
	
	$('.fadeDiv2').fadeIn('fast',function(){
		$('.conBox2').fadeIn('slow',function(){
			$('.okOnly').unbind();
			$('.okOnly').click(function(){
				closeConfirm2();
			});
			
		});
	});
	
}
function showMyAlerts(){
	
	$('.conBox3').html('<div class="msgTitle">Alerts <div class="closeBxx" title="Close">X</div></div><div class="mesCont"></div>');
	
	$('.conBox3').css('top',(($(window).height()-$('.conBox3').height())/2)+'px');
	
	$('.fadeDiv3').fadeIn('fast',function(){
		$('.conBox3').fadeIn('slow',function(){
			loadMyAlerts();
			$('.closeBxx').unbind();
			$('.closeBxx').click(function(){
				closeConfirm3();
			});
			
		});
	});
}
function loadMyAlerts(){
	$('.mesCont').html('<i style="width:100%;text-align:center;font-size:20px;float:left;margin-top:50px;">Loading Messages...</i>');
	
	$.post($('#main_url').val()+'&sq=40',{},function(data){
		var ob=jParser(data);
		if(ob.Status=="Success"){
			$('.mesCont').html(ob.Content);
		}
	});
	
}
function closeConfirm(){
	$('.conBox').fadeOut('fast',function(){
		$('.fadeDiv').fadeOut('slow');
	});
}
function closeConfirm2(){
	$('.conBox2').fadeOut('fast',function(){
		$('.fadeDiv2').fadeOut('slow');
	});
}
function closeConfirm3(){
	$('.conBox3').fadeOut('fast',function(){
		$('.fadeDiv3').fadeOut('slow');
	});
}
function loadPrintFunctions(){
	$('.prbut,.excelBtn').click(function(){
		var btn=this;
		popWindow('.thePopW',function(){
			loadPreview(btn);
		});
	});
}
function loadPurchaseFunctions(){
	$('#orec .quince_select').change(function(){
		loadReceivedReItems(this);
	});
	
	$('#opur .quince_select').change(function(){
		loadPurchasedReItems(this);
	});
	
	$('#recFrom,#recTo').blur(function(){
	
		setTimeout(function(){
			
			if(($('#recFrom').val()=="")|($('#recTo').val()==""))
			return;
			
			loadReceivedReItems($('#orec .quince_select'));
			
		},500);
		
	});
	
	$('#recFromp,#recTop').blur(function(){
	
		setTimeout(function(){
			
			if(($('#recFromp').val()=="")|($('#recTop').val()==""))
			return;
			
			loadPurchasedReItems($('#opur .quince_select'));
			
		},500);
		
	});
	
}
function loadReceivedReItems(btn){
	
	$('#orecDiv').html('<i style="margin-top:10px;float:left;font-size:20px;text-align:center;width:100%;">Loading...</i>');
	
	$.post($('#main_url').val()+'&sq=34',{theOp : $(btn).val(),frmDate : $('#recFrom').val(),toDate : $('#recTo').val()},function(data){
		
		var ob=jParser(data);
		if(ob.Status=="Success"){
			$('#orecDiv').html(ob.Content);
			loadPrintFunctions();
		}
		
	});
	
}
function loadPurchasedReItems(btn){
	
	$('#orecDiv').html('<i style="margin-top:10px;float:left;font-size:20px;text-align:center;width:100%;">Loading...</i>');
	
	$.post($('#main_url').val()+'&sq=35',{theOp : $(btn).val(),frmDate : $('#recFromp').val(),toDate : $('#recTop').val()},function(data){
		
		var ob=jParser(data);
		if(ob.Status=="Success"){
			$('#orecDiv').html(ob.Content);
			loadPurchaseFunctions();
			loadPrintingFunctions();
		}
		
	});
	
}
function loadFRequestPanel(btn){
	
	$('.fmu_wrap').html('<i style="margin-top:10px;float:left;font-size:20px;text-align:center;width:100%;">Loading...</i>');
	
	$.post($('#main_url').val()+'&sq=32',{theOp : $(btn).val()},function(data){
		
		var ob=jParser(data);
		if(ob.Status=="Success"){
			$('.fmu_wrap').html(ob.Content);
			loadFormFunctions();
			$('.saveFList,.saveFAList').unbind();
	        $('.saveFList,.saveFAList').click(function(){
		      saveListData(this,'6',true);
	        });
		}
		
	});
	
}
function loadPreview(btn){
	var opt="";
	if($(btn).attr('class')=="excelBtn"){
	 $('.thePopW').html('<i style="margin-top:20%;float:left;font-size:20px;text-align:center;width:100%;">Prepairing Excel File...</i>');
	 opt='excel';
	}else{
	  $('.thePopW').html('<i style="margin-top:20%;float:left;font-size:20px;text-align:center;width:100%;">Loading Preview...</i>');
	  opt="print";
	}
	var subData=getListData(0);
	var mStyles=getListData(1);
	var mainData=getListData();
	
	$.post($('#main_url').val()+'&sq=30',{listData : mainData,colN : getColumnNames(),colStyle : getColumnStyles(),sData : subData,mstyl : mStyles,prevOp : opt,btnId : $(btn).attr('id')},function(data){
	  
		var ob=jParser(data);
		
		if(ob.Status=="Success"){
			$('.thePopW').html(ob.Content);
		}
		
	});
	
}
function loadMiniFunctions(){
	
	"use strict";
	$('.delEn').unbind();	
	$('.delEn').click(function(){
		var btn=this;
		confirmAction('Are you sure you want to remove item?',function(){
			deleteOrRequest(btn);
		});
	});
	
	$('.delFmReq').unbind();
	$('.delFmReq').click(function(){
		var btn=this;
		confirmAction('Are you sure you want to delete this request?',function(){
		   $(btn).html('<i>Deleting....</i>');
		   $.post($('#main_url').val()+'&sq=46',{delId : $(btn).attr('id').split('_')[1]},function(data){
			   var ob=jParser(data);
			   if(ob.Status="Success"){
			     $($($($(btn).parent()).parent()).parent()).hide('slow',function(){
			         $(this).remove();
			         $('#lstid-'+$(btn).attr('id').split('_')[2]).remove();			 
		          });
			    }
		       });
		 });
		
	});
	
	$('.pchk').unbind();
	$('.pchk').change(function(){
		
		if($(this).prop('checked')){
		    addRemovePriviledge(this,1);
		}else{
			addRemovePriviledge(this,0);
		}
		
	});
	
	$('.pchk2').unbind();
	$('.pchk2').change(function(){
		
		if($(this).prop('checked')){
		    addRemovePriviledge(this,1);
		}else{
			addRemovePriviledge(this,0);
		}
		
	});
	
}
function addRemovePriviledge(btn,st){
	
	$('#'+$(btn).attr('id')+'_nm').html('<i>Updating...</i>');
	
	var theOp='68';
	
	if($(btn).attr('class')=="pchk2")
		theOp='70';

	
	$.post($('#main_url').val()+'&sq='+theOp,{pStatus: st,pos : $(btn).attr('id').split('_')[0],pType : $(btn).attr('id').split('_')[1]},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status=="Success"){
			$('#'+$(btn).attr('id')+'_nm').html('');
		}
		
	});
	
}
function deleteOrRequest(btn){
	
	if($(btn).html()=="Reset Entry"){}else{return;}
		
	
	$(btn).html("Processing...");
	
	$.post($('#main_url').val()+'&sq=31',{pid : $(btn).attr('id')},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status=="Success"){
		
		
		 $('#qty_'+$(btn).attr('id').replace('_','-').split('#')[3]).html(ob.Message);	
		
			if(ob.Content=="Deleted"){
			  $('#lst'+$(btn).attr('id').replace('_','-').split('#')[0]).hide('slow',function(){
		      $('#lst'+$(btn).attr('id').replace('_','-').split('#')[0]).remove();
	          });
		  
		  }else{ $(btn).html("Request Sent"); $(btn).css('background','#99999a'); }
		
		}
	});
	
}
function approveDecline(btn){
	
	var mess="";
	
	if($(btn).attr('class')=="appR")
		mess='Are you sure you want to approve this request?';
	
	if($(btn).attr('class')=="decL")
		mess='Are you sure you want to decline this request?';
 
	
   confirmAction(mess,function(){
	
	   $(btn).html('Wait...');
	
	$.post($('#main_url').val()+'&sq=29',{req: $(btn).attr('id')},function(data){
		var ob=jParser(data);
		if(ob.Status=="Success"){
		  showMessage(ob.Content);
			$("#lstid-"+$(btn).attr('id').split('_')[1]).hide('slow',function(){
			$("#lstid-"+$(btn).attr('id').split('_')[1]).remove();
			   
		    });
			
		}
	});
	   
   });
	
}
function resetEntries(btn){
	
	if($('.quince_select').val()==-1){
		alert("Site Not Selected");
	}
	
	if($('.labour_date').val()==""){
		alert("Entry date not indicated");
	}
	
	$(btn).html('Processing...');
	
	$.post($('#main_url').val()+'&sq=28',{theSite:$('.quince_select').val(),theDate : $('.labour_date').val(),lType : $('.l_select').val()},function(data){
		var ob=jParser(data);
		if((ob.Status=="Success")|(ob.Status=="Failed")){
			$(btn).html('Reset Entry');
			showMessage(ob.Content);
		}
	});
}
function loadDelReqs(btn){
	$('.dlMp').html('<i style="float:left;margin-left:60px;">Loading...</i>');
	$.post($('#main_url').val()+'&sq=27',{siteId : $(btn).val()},function(data){
		var ob=jParser(data);
		if(ob.Status=="Success"){
		  $('.dlMp').html(ob.Content);
		    if($(".compSel").val()== undefined){
               	 $(".mpSite").append("<div class='next'>"+ob.TopMenu+" </div>");
			   }else{
               	 $(".mpSite .next").html(ob.TopMenu);
			   }


			$('.hideFirst').fadeOut('fast',function(){
				$('.saveFList').fadeOut('fast');
				$('.kindNote').fadeIn('fast');
		  });
		  $('.mpReq').click(function(){
			  popWindow('.thePopW',function(){
				  $('.thePopW').html('<div class="qs_row" style="text-align:center;margin-top:200px;text-style:italic ;">Loading...</div>');
				  
				  $.post($('#main_url').val()+'&sq=47',{siteId : $('#sSite').val()},function(data){
					   var ob=jParser(data);
					   if(ob.Status=="Success"){
						  $('.thePopW').html(ob.Content);
						   $('.shwDet').unbind();
						   $('.shwDet').click(function(){
		                     showItemDetails(this);
							 closeDetFunction();
	                       });
						   $('.attDiv').click(function(){
				                 $('.ldiv').attr('id','adiv_'+$(this).attr('id').split('_')[1]);
							     $('.ldiv').fadeIn('fast',function(){
								 $('.thePopW').fadeOut('fast',function(){
									 $('#wrapper').fadeOut('fast');
									 $('.kindNote').fadeOut('fast',function(){
										 $('.saveFList').fadeIn('fast');
										 $('.hideFirst').fadeIn('fast');
									 });
								 });
								 });
						   });
						   $('.ldiv').click(function(){
							   var bn=this;
							   popWindow('.thePopW',function(){
								   $('.thePopW').html('<div class="qs_row" style="text-align:center;margin-top:200px;text-style:italic ;">Loading...</div>');
								   $.post($('#main_url').val()+'&sq=48',{rId : $(bn).attr('id').split('_')[1]},function(data){
									   var ob=jParser(data);
									   if(ob.Status=="Success"){
										   $('.thePopW').html(ob.Content);
									   }
								   });
							   });
						   });
					   }
				  });
				  
			  });
		  });
		}
	});
}
function attachViewFmFunctions(){
	
	$('.noAFile').click(function(){
		   var bn=this;
			popWindow('.thePopW',function(){
				   $('.thePopW').html('<div class="qs_row" style="text-align:center;margin-top:200px;text-style:italic ;">Loading...</div>');
					   $.post($('#main_url').val()+'&sq=47',{siteId : $(bn).attr('id').split('_')[1]},function(data){
						   var ob=jParser(data);
						   if(ob.Status=="Success"){
							   $('.thePopW').html(ob.Content);
							   $('.shwDet').unbind();
							   $('.shwDet').click(function(){
		                        showItemDetails(this);
							    closeDetFunction();
	                         });
						     $('.attDiv').click(function(){
							     var bn=this;
				                 $('.ldiv').attr('id','adiv_'+$(this).attr('id').split('_')[1]);
								 $('.ldiv').html('<i>Attaching</i>');
							     $('.ldiv').fadeIn('fast',function(){
								 $('.thePopW').fadeOut('fast',function(){
									 $('#wrapper').fadeOut('fast',function(){
										 $.post($('#main_url').val()+'&sq=49',{siteId : $('.noAFile').attr('id').split('_')[1],fmrid : $(bn).attr('id').split('_')[1],reqId : $('.noAFile').attr('id').split('_')[2]},function(data){
											 var ob=jParser(data);
											 if(ob.Status=="Success"){
												 $('.ldiv').html('View Attached');
											 }
										 });
									 });
								  });
								  });
						        });
							   
							   }
			     		   });
			});
		});
	    
	    $('.ldiv').click(function(){
				var bn=this;
				  popWindow('.thePopW',function(){
				  $('.thePopW').html('<div class="qs_row" style="text-align:center;margin-top:200px;text-style:italic ;">Loading...</div>');
				   $.post($('#main_url').val()+'&sq=48',{rId : $(bn).attr('id').split('_')[1]},function(data){
					   var ob=jParser(data);
						   if(ob.Status=="Success"){
							   $('.thePopW').html(ob.Content);
						   }
					 });
				 });
			});
}
function loadPrReq(btn){
	
	$('#quince_inner_content').html('<div class="qs_row">Loading...</div>');
	
	$.post($('#main_url').val()+'&sq=26',{pid : $(btn).attr('id').split('_')[1]},function(data){
		var ob =jParser(data);
		if(ob.Status=="Success"){
			$('#quince_inner_content').html(ob.Content);
			activateMenuItem();
			$('#req_filters .quince_select').change(function(){
		     loadReqList(this);
	        });
			blink('.ch_dw');
		}
	});
}
function blink(divId,typ){
	
	 if(typ==1){
		$(divId).animate({opacity:"1"},350,function(){
	      
       });
		
	 }else{
       $(divId).animate({opacity:"0.2"},350,function(){
	      
       });
		
	 }
	
	//alert("hello");
}
function loadReqList(btn){

	
	$('#rListWrap').html('<div class="qs_row">Loading...</div>');

	
	var param={};
	
	switch($(btn).attr('id')){
		case 'lstP':
			param={pid : $(btn).val(),typ : $('#lstT').val()};
			break;
			
		case 'lstT':
			param={pid : $('#lstP').val(),typ : $(btn).val()};
			break;
	}
	
	$.post($('#main_url').val()+'&sq=24',param,function(data){
		var ob =jParser(data);
		if(ob.Status=="Success"){
			$('#rListWrap').html(ob.Content);
			activateMenuItem();
			blink('.ch_dw');
		}
	});
	
}
function processUserSettings(btn){
	
	var oldVal=$(btn).val();
	
	if($(btn).val()=='Processing...')
		return;
	
	$(btn).val('Processing...');
	
	var param;
	
	switch($(btn).attr('id').split('_')[0]){
		case 'upsp':
			param={pos : $('#pos_'+$(btn).attr('id').split('_')[1]).val(),stat : $('#status_'+$(btn).attr('id').split('_')[1]).val(), but : $(btn).attr('id'),email:$('#uem_'+$(btn).attr('id').split('_')[1]).val()}
		break;
			
		case 'sSite':
			param={site : $('#selS_'+$(btn).attr('id').split('_')[1]).val(),but : $(btn).attr('id')}
	}
	
	$.post($('#main_url').val()+'&sq=23',param,function(data){
		
		var ob=jParser(data);
		if(ob.Status=="Success"){
		   showMessage(ob.Message,'#us_'+$(btn).attr('id').split('_')[1]);
		   $(btn).val(oldVal);	
		}
	});
	
	
	
}
function deleteMPayment(btn){
	
	confirmAction('Are you sure you want to discard this requisition?',function(){
	   
		$(btn).val('Processing...');
	
	$.post($('#main_url').val()+'&sq=21',{rid : $(btn).attr('id').split('_')[1],pid : $(btn).attr('id').split('_')[2]},function(data){
		
	    var ob=jParser(data);
		if(ob.Status==="Success"){
		   $('#quince_inner_content').html('<div class="q_row" style="text-align:center;color:#117a09;font-size:22px">Requisition Deleted Successfully <div class="aDelTab"></div></div>');
		}
	});
		
	});
	
}
function innerListPanel(theBt,$opt,params,callback){
		$('.dRw').hide('slow',function(){
		  setTimeout(function(){$('#dRw_'+$(theBt).attr('id').split('_')[1]).show('slow',function(){
			  $('#scont_'+$(theBt).attr('id').split('_')[1]).html('<i style="float:left;width:100%;">Loading...</i>');
			  $.post($('#main_url').val()+'&sq='+$opt,params,function(data){
				  var ob=jParser(data);
				  if(ob.Status=='Success'){
					   $('#scont_'+$(theBt).attr('id').split('_')[1]).html(ob.Content);
					   callback();
				  }
			  });
		  })},500);
		});
	
}
function authorizeReq(btn){
	
	$("#quince_inner_content").html('Processing...');
	
	$.post($('#main_url').val()+'&sq=20',{rid : $(btn).attr('id')},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status=="Success"){
			$("#quince_inner_content").html(ob.Content);
			updatedEquipmentFunctions();
		}
		
	});
}
function assignELocation(btn){
	
	var oldVal=$(btn).val();
	
	$(btn).val('Processing....');
	
	$.post($('#main_url').val()+'&sq=19',{equip : $(btn).attr('id').split('_')[1],site:$('#e_'+$(btn).attr('id').split('_')[1]).val(),butId : $(btn).attr('id')},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status="Success"){
			$(btn).val(oldVal);
			showMessage(ob.Content);
		}
		
	});
	
}
function loadLabour(thBtn){
	
	$('.lForm').html('Loading...');
	
	$.post($('#main_url').val()+'&sq=10',{theDate : $(thBtn).val(),siteId : $('.quince_select').val(),lType : $('.l_select').val()},function(data){
		
		var ob=jParser(data);
            
            if(ob.Status=="Success"){
                
                $('.lForm').html(ob.Content);
            
				loadFormFunctions();
			    addFileFunction();
			    loadSaveListFunctions();
				//rawInnerFunction();
				
				numbersOnly('.lField');
				
            }
	});
}
function loadLabourReport(theBtn){
	alert($(theBtn).attr('id'));
}
function loadInventory(thBtn){
    var activate=0;
	var site=0;
	var tDiv="";	
	var mp=0;
    var rid=0;
	var theBtnValue=$(thBtn).val();
    var store=0;
	
	if(($(thBtn).attr('id')=="siteSel")|($(thBtn).attr('id')=="invType")){
	
		$('#invL').html('Loading...');
	  site=$('#siteSel').val();
	  tDiv='#invL';
		
	  if( ($("#invType").val()=="3") &( $("#appStore").val() ==undefined) ){
          $("#form_row").append("<div class='qs_wrap' style='margin-left:30px' id='appStore'>");
          
	  }else{
         $("#appStore").remove();
	  }
	}else{
         $('.listWrap').html('Loading...');
	       tDiv='.listWrap';
	       mp=$('#mat_pay').val();
	       site=$('#siteSel').val();
	       rid=$("select[name=consign]").val();
    }
    if($(thBtn).attr("name")=="vStock"){
           $('.listWrap').html('Loading...');
	       tDiv='#invL';
	       mp=$('#mat_pay').val();
	       site=$('#siteSel').val();
	       rid=$("select[name=consign]").val();
           store=$(thBtn).val();
           activate=0;
    }
    if($(thBtn).attr("name")=="sReq"){
        activate=1;
        tDiv="#invL";fLabel
        rid=$(thBtn).val();
    }
    
    let params={siteId : site,qOption : $(thBtn).attr('id'),invT : $('#invType').val(),map : mp,reqId : rid,btnVal: theBtnValue,st:store};
    if($(thBtn).attr("name")=='siteHandler'){
           $('.listWrap').html('Loading...');
           params= {siteId : $(thBtn).val(),qOption : $(thBtn).attr('id'),invT : 6,btnVal: theBtnValue,st:store}
	       tDiv='#invL';
	       activate=0;
    }
    
    $.post($('#main_url').val()+'&sq=11',params,function(data){
		
		var ob=jParser(data);
        
            if(ob.Status==="Success"){
               
                $(tDiv).html(ob.Content);
                
                if(activate==0)
                    $("#appStore").html(ob.TopMenu);
            
				innerCompany();rawInnerFunction();updatedStockFunction();
				
            }
	});
}
function loadPurchases(btn){

	switch($(btn).attr('id')){
		case 'selMp':case 'selMpR':
			$('#pWin').html('<i style="width:100%;text-align:center;float:left;font-size:14px">loading....</i>');
			break;
			
		case 'selSite':case 'selSiteR':case "selSt":
			$('#pWin').html('');
			$('#sMp').html(' Loading....');
			break;
	}
	
	$.post($('#main_url').val()+'&sq=17',{sId : $(btn).val(), opId : $(btn).attr('id')},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status=="Success"){
		   
		switch($(btn).attr('id')){
			case 'selSite':case 'selSiteR':case "selSt":
			 $('#sMp').html(ob.Message);
			 $('.pur_select').unbind();	
			 $('.pur_select').change(function(){
				 loadPurchases(this);
			 });
			break;	
				
			case 'selMp':case 'selMpR':
				$('#pWin').html(ob.Message);
				rawInnerFunction();
				break;
		}   
			
		}
	});
	
} 
function processSearch(btn){
	
	var oldWrap =$('.sWrap').toArray();
	
	for(i=0;i<oldWrap.length;i++){
		if('wr_'+$(btn).attr('id')!=$(oldWrap[i]).attr('id')){
		 $(oldWrap[i]).hide('1000');
		}
	}
	
	$('#wr_'+$(btn).attr('id')).toggle('1000',function(){
		if($('#wr_'+$(btn).attr('id')).is(':visible')){
		   loadSContent(btn,$('#sf_'+$(btn).attr('id')).val());	
		}
	});
}
function loadSContent(btn,scont){
	try{
	
	 $('#in_'+$(btn).attr('id')).html('<i style="float:left;margin-top:30px;width:100%;text-align:center;">Loading...</i>');
	 $('#cf_'+$(btn).attr('id')).focus();
	 
	 $.post($('#main_url').val()+'&sq=9',{stype : $(btn).attr('id'),srch : scont},function(data){
		var ob=jParser(data);
		if(ob.Status=="Success"){
		   $('#in_'+$(btn).attr('id')).html(ob.Content);
		   selItemFunction(btn);
		}
	 });
		
	}catch(e){
	  alert(e);
	}
}
function selItemFunction(btn){
	$('.selC').click(function(){
		$('#'+$(btn).attr('id')).html($('#dv_'+$(this).attr('id').split('_')[1]).html());
		$('#vh_'+$(btn).attr('id')).val($(this).attr('id').split('_')[1]);
		$('#wr_'+$(btn).attr('id')).hide('1000');
		//alert('#sf_'+$('.scField').attr('id').split('_')[1]);
		//$('#cf_'+$('.scField').attr('id').split('_')[1]).val($(btn).attr('id').split('_')[1]);
	});
}
function popWindow(ppop,calBack){
	$('#wrapper').fadeIn('500',function(){
		$(ppop).fadeIn('500',function(){
			calBack();
			
		});
		$(".w3-sel").click(function(){
				$('#wrapper').fadeOut("fast");
		});
	});
}
function expandRetractDiv(theDiv){
	if($(theDiv).width()==0){
	  $(theDiv).fadeIn('fast',function(){
	   $(theDiv).animate({width : $(theDiv+' div').width()+'px'},500);	
	  });
	}else{
	 
	    $(theDiv).animate({width :'0px'},'slow',function(){
			 $(theDiv).fadeOut('slow');
		});	
	}
	
}
function addRowFunction(forRow){
	var arr=$('.trow').toArray();
		var cols=$('.cells_top').toArray();
		var cells="";
		for(i=0;i<cols.length;i++){
		var vl="";
		if((i==0)&(forRow==undefined))
			vl=arr.length+1;
			
		if($(cols[i]).attr('id').split('_')[0]=='delc')
			vl='<div class="delr" id="dle_'+arr.length+'"></div>';
		if(forRow==undefined){	
		  cells=cells+'<div class="cells" id="cl_'+arr.length+'_'+i+'_'+$(cols[i]).attr('id').split('_')[3]+'" style="width:'+$(cols[i]).attr('id').split('_')[2]+'%;text-align:'+$(cols[i]).css('text-align')+';">'+vl+'</div>';
		}else{
		   cells=cells+'<div class="cells" id="cl_'+arr.length+'_'+i+'_'+$(cols[i]).attr('id').split('_')[3]+'" style="width:'+$(cols[i]).attr('id').split('_')[2]+'%;text-align:'+$(cols[i]).css('text-align')+';">'+vl+'</div>';
		}
		}
	    if(forRow==undefined){
		 if(arr.length>0){
		   $('#table_id').append('<div id="lstid-'+arr.length+'e" class="trow">'+cells+'</div>');
		   $('#table_id').append('<div id="drow-'+arr.length+'e" class="drw"></div>');
		 }else{
		   $('#table_id').append('<div id="lstid-'+arr.length+'e" class="trow">'+cells+'</div>');
		   $('#table_id').append('<div id="drow-'+arr.length+'e" class="drw"></div>');
		 }
		}else{
			if(forRow=="Before"){
			  $('<div id="lstid-'+arr.length+'e" class="trow">'+cells+'</div>').insertBefore('#'+cRow);
			  $('<div id="drow-'+arr.length+'e" class="drw"></div>').insertBefore('#'+cRow);
			}else{
			  $('<div id="lstid-'+arr.length+'e" class="trow">'+cells+'</div>').insertAfter('#'+cRow);
			  $('<div id="drow-'+arr.length+'e" class="drw"></div>').insertAfter('#'+cRow);
			}
		}
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
function activateDeleteFunction(findParent){
	
	$('.delr').unbind();
	$('.delr').click(function(){
		var theBtn=this;
		if($(this).attr('id').split('_')[0]=='dl'){
		  
		   confirmAction('Are you sure you want to remove item?',function(){
			   	
			$(theBtn).attr('class','wait');
			
			$.post($('#main_url').val()+'&sq=13',{iType:$(theBtn).attr('id')},function(data){
				var ob=jParser(data);
				if(ob.Status=="Success"){
					
					$('#drow-'+$(theBtn).attr('id').split('_')[1]).hide('slow',function(){
			           $(theBtn).remove();
						try{
						calculateTotal();
						}catch(e){}
		             });
		            var rowOb=$('#lstid-'+$(theBtn).attr('id').split('_')[1]);
					if(findParent!==undefined){
						rowOb=$(theBtn).parent().parent();
					}
		            $(rowOb).hide('slow',function(){
			         try{ 
						 $(theBtn).remove();
	   					  calculateTotal();
						}catch(e){}
		             });
				}
			});
		   });
		
		}else{
		 
		var theBtn=this;
			
		confirmAction('Are you sure you want remove this item?',function(){
			try{
			//$('#drow-'+$(theBtn).attr('id').split('_')[1]+'e').hide('slow',function(){
			//$($($(theBtn).parent()).parent()).remove();
		 //});
		//'#lstid-'+$(theBtn).attr('id').split('_')[1]+'e'
		 $($($(theBtn).parent()).parent()).hide('slow',function(){
			$($($(theBtn).parent()).parent()).remove();
			 try{
			  calculateTotal();
			 }catch(e){}
		 });
		 try{
		  calculateTotal();
		 }catch(e){}
		}catch(e){
			
		}
			
		});
			
		}
		
	});
	
}
function activateCells(btn){
	
	if($(btn).attr('id').split('_')[3]=="edit"){


		let p_id=$($(btn).parent()).parent().attr("id");
		
		  if($('#'+$(btn).attr('id')+' input').attr('id')==$(btn).attr('id')+'_f'){
			  return;
		  }
		  var cols=$('.cells_top').toArray();
		
		  
		  if($("#allow_edit").val() !=undefined){
			   $(btn).html('<input type="text" id="'+$(btn).attr('id')+'_f" class="ltxt" value=""/>');
		  }else{
			   $(btn).html('<input type="text" id="'+$(btn).attr('id')+'_f" class="ltxt" value="'+$(btn).html()+'"/>');
		  }
			 
		
		  if($(cols[$(btn).attr('id').split('_')[2]]).attr('id').split('_')[0]=='num')
	   		  numbersOnly('.ltxt');
		  $('.ltxt').unbind().focusout(function(){

		      cellFunction(this);
			  formularCell(this);
		  });
		  $('.drw').hide('fast');
		  $('.drw').html('');
		try{
		  
		  if($(btn).attr('id').split('_')[2]==searchCol){
			 $('.ltxt').unbind().focusout(function(){
				 searchFunction(btn,this);
			 });
		  
		  }
		  
		}catch(e){ }
		  $('.ltxt').focus();
		  $('.ltxt').blur(function(){
			 
			  $(btn).html($(this).val());
			  
			  calculateTotal();
			  
		  });
		}
	
}
function formularCell(btn){
	
	if($(btn).attr('id').split('_')[3]=="edit"){
		
		if(hasFormular){
		  if($(btn).attr('id').split('_')[2]==fCol2){
			  if($('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol1).html()!=undefined){
				var colVal=$('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol3);
				  if($(colVal).html()==undefined)
					 colVal=$('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol3+'_No');
			    $(colVal).html(numeral($(btn).val()*parseFloat($('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol1).html().replace(',',''))).format('0,0.00'));
			  }else{
				  var colVal=$('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol3);
				  if($(colVal).html()==undefined)
					 colVal=$('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol3+'_No');
				  $(colVal).html(numeral($(btn).val()*parseFloat($('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol1+'_edit').html().replace(',',''))).format('0,0.00'));
			  }
		   }else{
			  if($(btn).attr('id').split('_')[2]==fCol1){ 
			  if($('#cl_'+$(btn).attr('id').split('_')[2]+'_'+fCol2).html()!=undefined){
				var colVal=$('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol3);
				  if($(colVal).html()==undefined)
					 colVal=$('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol3+'_No');
			    $(colVal).html(numeral($(btn).val()*parseFloat($('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol2).html().replace(',',''))).format('0,0.00'));
			  }else{
				  var colVal=$('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol3);
				  if($(colVal).html()==undefined)
					 colVal=$('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol3+'_No');

				  $(colVal).html(numeral($(btn).val()*parseFloat($('#cl_'+$(btn).attr('id').split('_')[1]+'_'+fCol2+'_edit').html().replace(',',''))).format('0,0.00'));
			  }
			 }
		   }
			
		}
		
		calculateTotal(btn);
		
	}
}
function calculateTotal(btn){
	
	var rws=$('.trow').toArray();
		  var ttals=0;
	      var stat=false;
	try{
		
		  for(i=0;i<rws.length;i++){
			 // console.log('#cl_'+(i+1)+'_'+fCol3);
			   if($('#cl_'+i+'_'+fCol3).html()==undefined){
				if($('#cl_'+i+'_'+fCol3+'_No').html()!=undefined){ 
					
				   if($('#cl_'+i+'_'+fCol3+'_No').html()!='')
				   ttals+=parseFloat($('#cl_'+i+'_'+fCol3+'_No').html().replace(',',''));
				   stat=true;
				}else{
					ttals+=parseFloat($('#cl_'+i+'_'+fCol3+'_edit').html().replace(',',''));
					stat=true;
				}
			   }else{
				
				if($('#cl_'+i+'_'+fCol3).html().trim()!=''){  
			     ttals+=parseFloat($('#cl_'+i+'_'+fCol3).html().replace(/,/g,''));
				 stat=true;
				}
				 
			   }
		   }
	}catch(e){}
	       if(stat)
		   if($('.Rtotal').html()!=undefined){
		     $('.Rtotal').html('TOTAL '+numeral(ttals).format('0,0.00'));
		   }
}
function searchFunction(btn,sdata){
	
	$('#drow'+$(btn).attr('id').split('_')[1]).show('fast');
	
	$('#drow'+$(btn).attr('id').split('_')[1]).html('');
	
	$.post($('#main_url').val()+'&sq=18',{theText : $('.ltxt').val(),tsite : $('#sSite').val()},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status=="Success"){
			if(ob.Content==""){
				$('#drow'+$(btn).attr('id').split('_')[1]).hide('fast');
			}
			$('#drow'+$(btn).attr('id').split('_')[1]).html(ob.Content);
			
			$('.searchRW').click(function(){
				var tarr=$('#'+$(this).attr('id')+' div').toArray();
				for(var x=0;x<tarr.length;x++){
					
					if($('#cl_'+$(btn).attr('id').split('_')[1]+'_'+$(tarr[x]).attr('id').split('_')[1]+'_edit').attr('id')==undefined){
						$('#cl_'+$(btn).attr('id').split('_')[1]+'_'+$(tarr[x]).attr('id').split('_')[1]).html($(tarr[x]).html())
					}else{
						if($(tarr[x]).attr('id').split('_')[0]=='ed'){
							activateCells($('#cl_'+$(btn).attr('id').split('_')[1]+'_'+$(tarr[x]).attr('id').split('_')[1]+'_edit'));
						}else{
						  $('#cl_'+$(btn).attr('id').split('_')[1]+'_'+$(tarr[x]).attr('id').split('_')[1]+'_edit').html($(tarr[x]).html());
						}
						
					}
				}
				$('#drow'+$(btn).attr('id').split('_')[1]).hide('fast');
			});
		}
		
	});
	
}
function loadMainTabOption(theBut){
    
    switch($(theBut).attr('id')){
        case 'dsh_1':
        loadMainContent($('#m_2'));
        break;
        
        case 'dsh_2':
        loadMainContent($('#m_3'));
        break;
        
        case 'dsh_3':
        loadMainContent($('#m_4'));
        break;
    }
    
}
function saveFormData(theBtn){
	
	if($(theBtn).val()=='Processing...')
	return;
	mess="Are you sure you want to submit?";
	var param={};
	var btn_text=$(theBtn).val();
	switch($(theBtn).attr('id')){
		//save user	
		case 's_user':
		var status=true;
		$('#fName').css('border','1px solid #bbb');
		$('#sName').css('border','1px solid #bbb');
		$('#email').css('border','1px solid #bbb');
		$('#upos').css('border','1px solid #bbb');
		$('#pass').css('border','1px solid #bbb');
		$('#rpass').css('border','1px solid #bbb');
		
	    if($('#fName').val().trim()==""){
			$('#fName').css('border','1px solid #f00');
			status=false;
		}
		if($('#sName').val().trim()==""){
			$('#sName').css('border','1px solid #f00');
			status=false;
		}
		if($('#email').val().trim()==""){
			$('#email').css('border','1px solid #f00');
			status=false;
		}
		if($('#position').val()==-1){
			$('#upos').css('border','1px solid #f00');
			status=false;
		}
		if($('#pass').val().trim()==""){
			$('#pass').css('border','1px solid #f00');
			status=false;
		}
		if($('#rpass').val().trim()==""){
			$('#rpass').css('border','1px solid #f00');
			status=false;
		}
		if(!status){
			alert('Complete all fields!');
			return;
		}
			
		if($('#pass').val()!=$('#rpass').val()){
		   alert('Password mismatch!');	
		   return;
		}
		param={sop:1,firstName : $('#fName').val(),lastName : $('#sName').val(),position :  $('#position').val(),email : $('#email').val(),password : $('#pass').val()};
			
		mess="Are you sure you want to create this user?"
			
		break;
			
		case 's_proj':
		$('#proTitle').css('border','1px solid #bbb');
		$('#proManager').css('border','1px solid #bbb');
		$('#qs_clerk').css('border','1px solid #bbb');
		$('#proStart').css('border','1px solid #bbb');
		$('#proEnd').css('border','1px solid #bbb');
		$('#proLocation').css('border','1px solid #bbb');
		
		var status=true;
			
		if($('#proTitle').val().trim()==""){
			$('#proTitle').css('border','1px solid #f00');
			status=false;
		}
			
		if($('#proBudget').val().trim()==-1){
			$('#proBudget').css('border','1px solid #f00');
			status=false;
		}
		
		/*if($('#vh_qs_clerk').val().trim()==-1){
			$('#qs_clerk').css('border','1px solid #f00');
			status=false;
		}*/
			
		if($('#proStart').val().trim()==""){
			$('#proStart').css('border','1px solid #f00');
			status=false;
		}
			
		if($('#proEnd').val().trim()==""){
			$('#proEnd').css('border','1px solid #f00');
			status=false;
		}
			
		if($('#proLocation').val().trim()==""){
			$('#proLocation').css('border','1px solid #f00');
			status=false;
		}
			
		if(!status){
			alert('Complete all fields!');
			return;
		}
		
		param={sop:2,title : $('#proTitle').val(),manager : $('#vh_proManager').val(),bud:$("#proBudget").val(),clerk : $('#vh_qs_clerk').val(),sDate : $('#proStart').val(),eDate : $('#proEnd').val(),loc:$('#proLocation').val(),pDesc : $('#txtDesc').val()}
			
		break;
			
		case 's_site':
		status=true;
		$('#siteName').css('border','1px solid #bbb');
		$('#qs_project').css('border','1px solid #bbb');
		$('#qs_clerk').css('border','1px solid #bbb');
		$('#siteLocation').css('border','1px solid #bbb');
	    
		if($('#siteName').val().trim()==""){
			$('#siteName').css('border','1px solid #f00');
			status=false;
		}
		
		if($('#project').val().trim()==-1){
			$('#qs_project').css('border','1px solid #f00');
			status=false;
		}
			
		if($('#vh_qs_clerk').val().trim()==-1){
			$('#qs_clerk').css('border','1px solid #f00');
			status=false;
		}
			
		if($('#siteLocation').val().trim()==""){
			$('#siteLocation').css('border','1px solid #f00');
			status=false;
		}
			
		if(!status){
			alert('Complete all fields!');
			return;
		}
		
		//alert($('#vh_qs_clerk').val());
			
		param={sop : 3,siteName : $('#siteName').val(),project : $('#project').val(),clerk :$('#vh_qs_clerk').val(),location : $('#siteLocation').val()}	
		break;
			
		case 'cPassword':
	    status=true;
		$('#oPass').css('border','1px solid #bbb');
		$('#nPass').css('border','1px solid #bbb');
		$('#rPass').css('border','1px solid #bbb');
		if($('#oPass').val().trim()==""){
			$('#oPass').css('border','1px solid #f00');
			status=false;
		}
		
		if($('#nPass').val().trim()==""){
			$('#nPass').css('border','1px solid #f00');
			status=false;
		}
		
		if($('#rPass').val().trim()==""){
			$('#rPass').css('border','1px solid #f00');
			status=false;
		}
		
		if(!status){
			alert('Complete all fields!');
			return;
		}
			
		if($('#rPass').val().trim()!=$('#nPass').val().trim()){
			alert("Password Mismatch");
			return;
		}
			
			
		param={sop : 4,oPass : $('#oPass').val(),newPass : $('#nPass').val()}	
		break;
			
		case 's_company':
		status=true;
		$('#cname').css('border','1px solid #bbb');
		$('#cprefix').css('border','1px solid #bbb');
		
		if($('#cname').val()==""){
		   $('#cname').css('border','1px solid #f00');
		   status=false;
	    }
		if($('#cprefix').val()==""){
		   $('#cprefix').css('border','1px solid #f00');
		   status=false;
	    }
		
		if(!status){
			alert('Complete all fields!');
			return;
		}
			
		param={sop : 5,companyName : $('#cname').val(),prefix : $('#cprefix').val()};
		break;
		
		case 'sod':
		var status=true;
		var c_id=($(theBtn).attr('class').split(' ')[1]).split('_')[1];
		 $('#fName_'+c_id).css('border','1px solid #bbb');
		 $('#sName_'+c_id).css('border','1px solid #bbb');
		 $('#txtEmail_'+c_id).css('border','1px solid #bbb');
		 $('#txtPass_'+c_id).css('border','1px solid #bbb');
		 $('#txtRPass_'+c_id).css('border','1px solid #bbb');	
		
		if($('#fName_'+c_id).val()==""){
		   $('#fName_'+c_id).css('border','1px solid #f00');
		   status=false;
	    }
			
		if($('#sName_'+c_id).val()==""){
		   $('#sName_'+c_id).css('border','1px solid #f00');
		   status=false;
	    }
			
		if($('#txtEmail_'+c_id).val()==""){
		   $('#txtEmail_'+c_id).css('border','1px solid #f00');
		   status=false;
	    }
			
		if($('#txtPass_'+c_id).val()==""){
		   $('#txtPass_'+c_id).css('border','1px solid #f00');
		   status=false;
	    }
			
		if($('#txtRPass_'+c_id).val()==""){
		   $('#txtRPass_'+c_id).css('border','1px solid #f00');
		   status=false;
	    }
			
	   if($('#txtRPass_'+c_id).val()!=$('#txtPass_'+c_id).val()){
		   alert('Complete all fields!');
		   return;
	   }
		
		if(!status){
			alert('Complete all fields!');
			return;
		}
				
	    param={sop : 6,companyId : c_id,firstName : $('#fName_'+c_id).val(),secondName : $('#sName_'+c_id).val(),email:$('#txtEmail_'+c_id).val(),pass : $('#txtPass_'+c_id).val(),rpass : $('#txtRPass_'+c_id).val()};
			
		break;
			
		case 'wrkSch':
		var status=true;
		$('#fromDate').css('border','1px solid #bbb');	
		$('#toDate').css('border','1px solid #bbb');
		$('#txtCompTitle').css('border','1px solid #bbb');
			
		if($('#fromDate').val()==""){
			$('#fromDate').css('border','1px solid #f00');
			status=false;
		}
			
		if($('#toDate').val()==""){
			$('#toDate').css('border','1px solid #f00');
			status=false;
		}
			
		if($('#txtCompTitle').val()==""){
			$('#txtCompTitle').css('border','1px solid #f00');
			status=false;
		}
			
		if(!status){
		   alert('Complete all fields!');
		   return;
		}
			
		param={sop : 7,title : $('#txtCompTitle').val(),fromDate : $('#fromDate').val(),toDate : $('#toDate').val(),pid : $('.newSch').attr('id').split('_')[1]};
		break;
			
		case 'addCItem':
		var status=true;
		var id=$(theBtn).attr('class').split(' ')[1].split('_')[1];
		$('#desc_'+id).css('border','1px solid #bbb');
		$('#iqty_'+id).css('border','1px solid #bbb');
		$('#txtUnit_'+id).css('border','1px solid #bbb');
			
		if($('#desc_'+id).val()==""){
			$('#desc_'+id).css('border','1px solid #f00');
			status=false;
		}
		
		if($('#iqty_'+id).val()==""){
			$('#iqty_'+id).css('border','1px solid #f00');
			status=false;
		}
		
		if($('#txtUnit_'+id).val()==""){
			$('#txtUnit_'+id).css('border','1px solid #f00');
			status=false;
		}
		
		if(!status){
		   alert('Complete all fields!');
		   return;
		}
			
		param={sop : 8,cid : id ,description : $('#desc_'+id).val(),qty : $('#iqty_'+id).val(),unit : $('#txtUnit_'+id).val()};
		break;
			
	}
	
	confirmAction(mess,function(){
		
	  $(theBtn).val('Processing...');	
	  
	   $.post($('#main_url').val()+'&sq=8',param,function(data){
		
		var ob=jParser(data);
		if(ob.Status=="Success"){
			
		   $('.jaxR'+ob.DivSufix).html(ob.Content);
		   $('.txtField').val('');
		   if(ob.DivName!=""){
		    showMessage(ob.Message,ob.DivName);
		   }else{
			showMessage(ob.Message);   
		   }
		   activateDeleteFunction(true);
		   rawInnerFunction();runAssist();
		}else{
		   if(ob.DivName!=""){
		    showMessage(ob.Message,ob.DivName);
		   }else{
			showMessage(ob.Message);   
		   }
		}

		$(theBtn).val(btn_text);	
		
	    });
		
	},function(){});

	
}
function showMessage(dets,divId){
	if(divId==undefined){
	  $('.results_wrap').html(dets);
	  $('.results_wrap').show('slow',function(){
		setTimeout(function(){
			$('.results_wrap').hide('slow');
		},3000);
	   });
	}else{
	  $(divId).html(dets);
	  $(divId).show('slow',function(){
		setTimeout(function(){
			$(divId).hide('slow');
		},3000);
	   });
	}
}
function getColumnStyles(){
	var topCells=$('.cells_top').toArray();
	var cells=Array();
	for(x=0;x<topCells.length;x++){
		cells.push($(topCells[x]).attr('style'));
	}
	
	return JSON.stringify(cells);
}
function getColumnNames(){
	var topCells=$('.cells_top').toArray();
	var cells=Array();
	for(x=0;x<topCells.length;x++){
		cells.push($(topCells[x]).html());
	}
	
	return JSON.stringify(cells);
	
}
function getListData(opData){
	
	var tid=0;
	var dRows=$('#table_id .trow').toArray();
	var dataRow=Array();
	var mDataRow=Array();
	var extField=Array();
	var minStyles=Array();
	var mainSub=new Array();
	var tld="";
	var lType=0;
	var sid=0;
	var repl=-1;
	var hasMinStyle=false;
	var createNewRow=false;
	var isNewRow = false;
	var subIndex=-1;
	mainSub[0]=new Array();
	for(i=0;i<dRows.length;i++){
	   //alert('#'+$(dRows[i]).attr('id')+' .cell');
	   var par=$(dRows[i]).parent();
	   var rcel=$('#'+$(dRows[i]).attr('id')+' .cells').toArray();
	   var cellData=Array();
	   var sa=0;
	   extField=Array();
		
		var innerHas=hasMinStyle;
		
	   for(c=sa;c<rcel.length;c++){
		   if($('#'+$(rcel[c]).attr('id')+' div').attr('class')!='delr'){
		     cellData.push($(rcel[c]).html());
			 if($(par).attr('id')!='table_id'){
			   if(!innerHas)
			    minStyles.push($(rcel[c]).attr('style'));
			    hasMinStyle=true;
			 }
		   }
	   }
	   
	   cellData.push($(dRows[i]).attr('id').split('-')[1]);
	   
	  if($(par).attr('id')=='table_id'){
		//if(createNewRow){
			//mDataRow=new Array();
			subIndex++;
			//createNewRow=false;
			mainSub[subIndex]=new Array();
		//}
		
	    dataRow.push(cellData);
	    //mDataRow.push(Array());
		mainSub[subIndex].push(Array());
	  }else{
		createNewRow=true;
		//mainSub[subIndex]=new Array();
	    //mDataRow.push(cellData);  
		mainSub[subIndex].push(cellData);  
	  }
		
	}
	if(opData!=undefined){
		
	  if(opData==0)
	  return JSON.stringify(mainSub);
	
	  if(opData==1)
	  return JSON.stringify(minStyles);
	
	}else{
	 return JSON.stringify(dataRow);
	}
}
async function saveListData(btn,op,clearAfter){
	let func;
	var clearA=false;
	if(clearAfter!=undefined){
		clearA=true;
	}
	if(($(btn).val()==undefined)|($(btn).val()=='')){
	  if($(btn).html()=="Saving...")
		return;
	}else{
	  if($(btn).val()=="Saving...")
		return;	
	}
	if($(btn).attr("id").split("_")[0]=="app"){
		$("#cont-hold #table_id").remove();
		func=function(ob){
			$("#quince_inner_content").html(" ");
		}
	}let store=-2;
	if($(btn).attr("id").split("_")[0]=="s"){
		if(($("#store_2").prop("checked") !==true) &($("#store_1").prop("checked") !==true)){
			alert("select store");$("fieldset").addClass("error-border");
			return;
		}else{
			if($("#store_2").prop("checked") ===true){
				store="-2";
			}else{
				store="-3";
			}
			
		}
	}
	
	confirmAction('Are you sure you want to process?',function(){
				
	var response="List is Empty";	
	var listLabel=$(btn).html();
	var tid=0;
	var dRows=$('.trow').toArray();
	var dataRow=Array();
	var extField=Array();
	var tld="";
	var lType=0;
	var sid=0;
	var repl=-1;
	var fmp=0;
	if(($(btn).val()==undefined)|($(btn).val()=="")){
		listLabel=$(btn).html();
	}else{
		listLabel=$(btn).val();
	}
	try{
		fmp=$('.ldiv').attr('id').split('_')[1];
	}catch(e){
		
	}
	  for(i=0;i<dRows.length;i++){
	   //alert('#'+$(dRows[i]).attr('id')+' .cell');
	   var rcel=$('#'+$(dRows[i]).attr('id')+' .cells').toArray();
	   var cellData=Array();
	   var sa=1;
	   extField=Array();
		
		
	   if($(btn).attr('class')=="saveFAList"){
		   
		   sa=0;
		   
		   var lfield=$('.lField').toArray();
		   
		   var stat=true;
		   
		   if(lfield.length>0){
			   for(var x=0;x<lfield.length;x++)
				   if($(lfield[x]).val()==""){
					  $(lfield[x]).css('border','1px solid #f00');
					  stat=false;
				   }else{
					   $(lfield[x]).css('border','1px solid #bbb');
					   extField.push($(lfield[x]).attr('id')+':'+$(lfield[x]).val());
				   }
		   }
		   
		   tid=$('.quince_select').val();
		   
		   
		   
		   if(!stat){
			   alert(cDiv('Sorry.Wages and Overtime Fields Must Have Values!'));
			   return;
		   }
		   tld=$('.labour_date').val();
		   
		   lType=$('.l_select').val();
		 
		   
	   }
	   
	   if($('.dlMp .quince_select').val()!=undefined){
		
	     repl=$('.dlMp .quince_select').val();
	   
	   }
	  
		    for(c=sa;c<rcel.length;c++){
		       if($('#'+$(rcel[c]).attr('id')+' div').attr('class')!='delr'){
		           cellData.push($(rcel[c]).html());
		        }
	         }
	   
	            cellData.push($(dRows[i]).attr('id').split('-')[1]);
	   
	       dataRow.push(cellData);
	       }
		
	
		
	
	
	if(dataRow.length==0){
	     if($(btn).attr("id").split("_")[0]=="sid"){
	         response="Start by adding quantities and units to issue";
	     } alert(cDiv(response));
	 
	  return;
	}
	
	if($('#sSite').val()!=undefined){
		if($('#sSite').val()==-1){
			alert(cDiv('Please select site'));
			return;
		}else{
			sid=$('#sSite').val();
		}
	}else{
		
		if(is_native !=0){
			sid=$(".quince_select").val();
		}else{
			sid=$(".quince_select").val();
		}
		
	}	
	
	if(($(btn).val()==undefined)|($(btn).val()=="")){
	  $(btn).html('Saving...');
	}else{
	  $(btn).val('Saving...');
	}
	 let params={listData : JSON.stringify(dataRow),sType: $(btn).attr('id').split('_')[1],upid : $('#uid').val(),extF : JSON.stringify(extField),uid : tid,theDate : tld,lType : lType,siteId : sid ,oid : $(btn).attr('id').split('_')[2],tDate : $('#theDate').val(),replc: repl,frid : fmp,str:$(".selected").attr("data-val"),sRc:$("select[name='selectSource']").val()};
			
			
	 $.post($('#main_url').val()+'&sq='+op,params,function(data){
		
		var ob=jParser(data)
		
		if(ob.Status=="Success"){
		if($(btn).attr('class')!="saveFAList"){
		    showMessage(ob.Message);
		}
			
		if($(btn).attr("id").split("_")[0]=="app"){
			$("#quince_inner_content").html(ob.Content);runAssist();
		}
		if($(btn).val()==undefined){
	      $(btn).html(listLabel);
	    }else{
	      $(btn).val(listLabel);
	    }
		if(clearA){
		  var arr=$('.trow').toArray();
			
			if(ob.Content=="Refresh"){
			$('.saveFList').fadeOut('fast');
			for(z=0;z<arr.length;z++){
			 $('#'+$(arr[z]).attr('id')).remove();
				
			 }
			
		    }
			
			if(ob.Content=="Replace"){
				$('.saveFList').fadeOut('fast');
				$('#'+ob.DivName).html(ob.Message);
				
				addFileFunction();
			}
			
			if(ob.Content=="Retain"){
				if($(btn).val()!=undefined){
	               $(btn).html(listLabel);
	             }else{
	               $(btn).val(listLabel);
	             }
				$('.'+ob.DivName).html(ob.Message);
			}
			
		 }else{
			 $('.saveFList').fadeOut('fast');
		 }
		
		}else{
			showMessage(ob.Message);
			if($(btn).val()!=undefined){
	            $(btn).html(listLabel);
	         }else{
	            $(btn).val(listLabel);
	         }
		}
		calculateTotal();
	});
	

	});
}
function cDiv(itm){
	return '<div class="dv_caution" style="color:#f00;width:100%;">'+itm+'</div>';
}
function addFileFunction(){
	$('.addRF').click(function(){
	     loadLabourUploader();
	  });
	
	$('#ldd .quince_select').change(function(){
		
		$('.qreq').html('Loading....');
		
		$.post($('#main_url').val()+'&sq=39',{theDate : $('.labour_date').val(),siteId : $('.quince_select').val(),lType : $('.l_select').val(), copy:$('#ldd .quince_select').val() },function(data){
		
		var ob=jParser(data);
            
            if(ob.Status=="Success"){
                
                $('.qreq').html(ob.Content);
            
				rawInnerFunction();
				
				numbersOnly('.qreq');
				
            }
	});
		
	});
	
}
function numbersOnly(classorid){
	$(classorid).keypress(function (e){
  var charCode = (e.which) ? e.which : e.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)&& charCode !=46) {
    return false;
  }
});
}
function searchDateFunction(theBut,butIds,target){
    
    setTimeout(function(){
       
        if(($(butIds[0]).val()=="")|($(butIds[1]).val()=="")) 
        return;
        
        $(target).html('<div id="form_row" style="width:100%;float:left;text-align:center;font-size:14px;"><i>Loading...</i></div>');
       
               
        $.post($('#main_url').val()+'&sq=3',{sSource : $(theBut).attr('id')},function(data){
       
          var ob=jParser(data);
          
          if(ob.Status=="Success"){
          
            $(target).html(ob.Content);
          
          }
        
         });
        
       },200);
    
}
function activateMenuItem(){
    
	$('.men_tab2').unbind()
	
    $('.men_tab2,.vReq').click(function(){
        
      loadMenuContent(this);
        
    });
	
	$('.exMp').click(function(){
		splitMp(this);
	});
    
}
function loadScheduleOfWorks(btn){
	
	'use strict';
	
	$('#quince_inner_content').html('<i style="width:100%;float:left;text-align:center;">Loading...</i>');
	
	$.post($('#main_url').val()+'&sq=72',{pid : $(btn).attr('id').split('_')[1],isPhone:getPhoneAttr()},function(data){
		
		var ob=jParser(data);
		
		if(ob.Status==="Success"){
			$('#quince_inner_content').html(ob.Content);
			rawInnerFunction();
			runAssist();
				   loadInnerFunc();
                   loadAfterAnimate(); 
		}
		
	});
	
}
function loadMenuContent(btn){
	
	   if($(btn).attr('id')==undefined)
        return;
        
        $('#quince_inner_content').html('<div id="form_row" style="width:100%;float:left;text-align:center;font-size:14px;"><i>Loading...</i></div>');
        
         $.post($('#main_url').val()+'&sq=1',{vName : $(btn).attr('id'),isPhone:getPhoneAttr()},function(data){
           
           var ob=jParser(data);
           
           if(ob.Status=="Success"){
             
             $('#quince_inner_content').html(ob.Content);
			 runAssist(btn);mainUtilities();
             activateMenuItem();
             loadAfterAnimate();  
             rawInnerFunction();
             activateDeleteFunction();
			 blink('.ch_dw');
			 
           }
            
        });

	loadRawAlerts();
}
//---------------------------------------------------------------------END BUTTON INNER FUNCTIONS------------------------------------------
function splitMp(btn){
	try{
	popWindow('.thePop',function(){
		$('.thePop').html('<i style="margin-top:20%;float:left;font-size:20px;text-align:center;width:100%;">Loading...</i>');		
		
		$.post($('#main_url').val()+'&sq=50',{rid : $(btn).attr('id').split('_')[1]},function(data){
		   var ob=jParser(data);
			if(ob.Status=="Success"){
			    $('.thePop').html(ob.Content);
				$('.yesbtn').click(function(){
					$('.thePop').fadeOut('fast',function(){
						$('#wrapper').fadeOut('fast');
					});
					confirmAction('This action is not reversable. Are you sure you want to proceed',function(){
						$('#rListWrap').html('Loading....');
						 $.post($('#main_url').val()+'&sq=51',{reqId : $(btn).attr('id').split('_')[1]},function(data){
							 var ob=jParser(data);
							 if(ob.Status=="Success"){
							    loadReqList($('#lstT'));
							  }
						 });
						 
					});
				});
				
				$('.declbtn').click(function(){
					$('.thePop').fadeOut('fast',function(){
						$('#wrapper').fadeOut('fast');
					});
				});
				
			}
	    });
		
	});
	}catch(e){
		alert(e);
	}
	
}
function jParser(data){
    
    try{
		var ob=$.parseJSON(data);
		if(ob.Content=="LOGGED_OUT"){
			window.location.reload();
		}
        return $.parseJSON(data)
    }catch(e){
        alert(e);
        alert(data);
    }
    
}
