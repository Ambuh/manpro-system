
/*
 *  jquery-maskmoney - v3.0.2
 *  jQuery plugin to mask data entry in the input text in the form of money (currency)
 *  https://github.com/plentz/jquery-maskmoney
 *
 *  Made by Diego Plentz
 *  Under MIT License (https://raw.github.com/plentz/jquery-maskmoney/master/LICENSE)
 */
!function($){"use strict";$.browser||($.browser={},$.browser.mozilla=/mozilla/.test(navigator.userAgent.toLowerCase())&&!/webkit/.test(navigator.userAgent.toLowerCase()),$.browser.webkit=/webkit/.test(navigator.userAgent.toLowerCase()),$.browser.opera=/opera/.test(navigator.userAgent.toLowerCase()),$.browser.msie=/msie/.test(navigator.userAgent.toLowerCase()));var a={destroy:function(){return $(this).unbind(".maskMoney"),$.browser.msie&&(this.onpaste=null),this},mask:function(a){return this.each(function(){var b,c=$(this);return"number"==typeof a&&(c.trigger("mask"),b=$(c.val().split(/\D/)).last()[0].length,a=a.toFixed(b),c.val(a)),c.trigger("mask")})},unmasked:function(){return this.map(function(){var a,b=$(this).val()||"0",c=-1!==b.indexOf("-");return $(b.split(/\D/).reverse()).each(function(b,c){return c?(a=c,!1):void 0}),b=b.replace(/\D/g,""),b=b.replace(new RegExp(a+"$"),"."+a),c&&(b="-"+b),parseFloat(b)})},init:function(a){return a=$.extend({prefix:"",suffix:"",affixesStay:!0,thousands:",",decimal:".",precision:2,allowZero:!1,allowNegative:!1},a),this.each(function(){function b(){var a,b,c,d,e,f=s.get(0),g=0,h=0;return"number"==typeof f.selectionStart&&"number"==typeof f.selectionEnd?(g=f.selectionStart,h=f.selectionEnd):(b=document.selection.createRange(),b&&b.parentElement()===f&&(d=f.value.length,a=f.value.replace(/\r\n/g,"\n"),c=f.createTextRange(),c.moveToBookmark(b.getBookmark()),e=f.createTextRange(),e.collapse(!1),c.compareEndPoints("StartToEnd",e)>-1?g=h=d:(g=-c.moveStart("character",-d),g+=a.slice(0,g).split("\n").length-1,c.compareEndPoints("EndToEnd",e)>-1?h=d:(h=-c.moveEnd("character",-d),h+=a.slice(0,h).split("\n").length-1)))),{start:g,end:h}}function c(){var a=!(s.val().length>=s.attr("maxlength")&&s.attr("maxlength")>=0),c=b(),d=c.start,e=c.end,f=c.start!==c.end&&s.val().substring(d,e).match(/\d/)?!0:!1,g="0"===s.val().substring(0,1);return a||f||g}function d(a){s.each(function(b,c){if(c.setSelectionRange)c.focus(),c.setSelectionRange(a,a);else if(c.createTextRange){var d=c.createTextRange();d.collapse(!0),d.moveEnd("character",a),d.moveStart("character",a),d.select()}})}function e(b){var c="";return b.indexOf("-")>-1&&(b=b.replace("-",""),c="-"),c+a.prefix+b+a.suffix}function f(b){var c,d,f,g=b.indexOf("-")>-1&&a.allowNegative?"-":"",h=b.replace(/[^0-9]/g,""),i=h.slice(0,h.length-a.precision);return i=i.replace(/^0*/g,""),i=i.replace(/\B(?=(\d{3})+(?!\d))/g,a.thousands),""===i&&(i="0"),c=g+i,a.precision>0&&(d=h.slice(h.length-a.precision),f=new Array(a.precision+1-d.length).join(0),c+=a.decimal+f+d),e(c)}function g(a){var b,c=s.val().length;s.val(f(s.val())),b=s.val().length,a-=c-b,d(a)}function h(){var a=s.val();s.val(f(a))}function i(){var b=s.val();return a.allowNegative?""!==b&&"-"===b.charAt(0)?b.replace("-",""):"-"+b:b}function j(a){a.preventDefault?a.preventDefault():a.returnValue=!1}function k(a){a=a||window.event;var d,e,f,h,k,l=a.which||a.charCode||a.keyCode;return void 0===l?!1:48>l||l>57?45===l?(s.val(i()),!1):43===l?(s.val(s.val().replace("-","")),!1):13===l||9===l?!0:!$.browser.mozilla||37!==l&&39!==l||0!==a.charCode?(j(a),!0):!0:c()?(j(a),d=String.fromCharCode(l),e=b(),f=e.start,h=e.end,k=s.val(),s.val(k.substring(0,f)+d+k.substring(h,k.length)),g(f+1),!1):!1}function l(c){c=c||window.event;var d,e,f,h,i,k=c.which||c.charCode||c.keyCode;return void 0===k?!1:(d=b(),e=d.start,f=d.end,8===k||46===k||63272===k?(j(c),h=s.val(),e===f&&(8===k?""===a.suffix?e-=1:(i=h.split("").reverse().join("").search(/\d/),e=h.length-i-1,f=e+1):f+=1),s.val(h.substring(0,e)+h.substring(f,h.length)),g(e),!1):9===k?!0:!0)}function m(){r=s.val(),h();var a,b=s.get(0);b.createTextRange&&(a=b.createTextRange(),a.collapse(!1),a.select())}function n(){setTimeout(function(){h()},0)}function o(){var b=parseFloat("0")/Math.pow(10,a.precision);return b.toFixed(a.precision).replace(new RegExp("\\.","g"),a.decimal)}function p(b){if($.browser.msie&&k(b),""===s.val()||s.val()===e(o()))a.allowZero?a.affixesStay?s.val(e(o())):s.val(o()):s.val("");else if(!a.affixesStay){var c=s.val().replace(a.prefix,"").replace(a.suffix,"");s.val(c)}s.val()!==r&&s.change()}function q(){var a,b=s.get(0);b.setSelectionRange?(a=s.val().length,b.setSelectionRange(a,a)):s.val(s.val())}var r,s=$(this);a=$.extend(a,s.data()),s.unbind(".maskMoney"),s.bind("keypress.maskMoney",k),s.bind("keydown.maskMoney",l),s.bind("blur.maskMoney",p),s.bind("focus.maskMoney",m),s.bind("click.maskMoney",q),s.bind("cut.maskMoney",n),s.bind("paste.maskMoney",n),s.bind("mask.maskMoney",h)})}};$.fn.maskMoney=function(b){return a[b]?a[b].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof b&&b?($.error("Method "+b+" does not exist on jQuery.maskMoney"),void 0):a.init.apply(this,arguments)}}(window.jQuery||window.Zepto);
var xcel_col="";
var xhr;
var disableAnim=false;
var blockpopclose=false;
var selected_column="";
$(document).ready(function(){  
            
    $('#profile_div').click(function(){
        $('.acc_dets').toggle('fast');
    });

    $('#inner_content').click(function(){
        hideProfile();
    })
    
    $('#unit_box').click(function(){
        hideProfile();
    })

    $('#cat_select').change(function(){
        loadCategory();
    })
    $('#mn_message').keyup(function(){
        letterCount("");
    });
    $('#mn_message2').keyup(function(){
        letterCount("2");
    });
    
     $('#mn_message2').mouseover(function(){
        if(xcel_col!=""){
         $('#t_infomessage').css("margin-top","-160px");
         $('#t_infomessage').css("margin-left","60px");
         $('#t_infomessage').html("Click to include column data in your message");
         $('#t_infomessage').fadeIn('fast');
        }
    });
    
    $('#mn_message2').mouseout(function(){
        $('#t_infomessage').fadeOut('fast');
    });
    
    $(".pop_close").click(function(){closePop()});
    
    $('#wrapper').click(function(){closePop()});
    
    $('#add_group').click(function(){quickAddGroupPop()});
    
    $('#add_contact').click(function(){quickAddContactPop()});
    
    $('#p_mode').click(function(){swToExcel();});
    
    $('#p_mode2').click(function(){swToDefault();});
    
    $('#mn_message2').click(function(){
        $('#t_infomessage').fadeOut('fast');
        insertColToMes();
    })

    $('#qadd_c').click(function(){
        qsaveNewContacts();
    });
    
    $('#n_group').click(function(){
        qsaveNewGroup();
    });
        
    $('#xslfrm').ajaxForm({beforeSubmit:function(){
        $('#excel_content').html("<div id=\"form_row\"><i>Please wait.Loading content...</i></div>");
    },success:function(data){

        var ob=jParser(data);
        if(ob.status="Success"){
          var sheets=ob.sheetlist.split(',');
           
          if(ob.Refresh==1){
                 
            for(i=0;i<sheets.length;i++)
            $('#ws_select').append("<option value=\""+i+"\">"+sheets[i]+"</option>");
          
          }
          
          $('#excel_content').html(ob.vals);
          patchList();
        }
    },uploadProgress : function(ev,pos,total,percent){
        $('#excel_content').html("<div id=\"form_row\"><i>Please wait.Loading content..."+percent+"%</i></div>");
    }
    });
    
    $('#ws_select').change(function(){
        $('#xslfrm').submit();
    });
    
    $('#xslfcontact').ajaxForm({beforeSubmit : function(){ $('#contact_prev').html('<div id=\"form_row\"><i>Loading. Please wait...</i></div>') }, success : function(data){
       showContactsPrev(data);
       }
    });
    
    $('#snd_msg').click(function(){
        showSending();
    });
    
    $('#ins').click(function(){
        insertName('#mn_message')
    });
    
    $('#del_btn').click(function(){
        messageBox('Warning',false,'Are you sure you want to delete this group?','DeleteGroup',true);
    });
    
    $("#acc_set").click(function(){ loadSettingsPanel(); });
    
    $("#chsid").click(function(){
        getSenderId();
    });
    $('#clse').click(function(){
       $('.sid_box').fadeOut('slow'); 
    });
    
    $('#buysms').click(function(){
        buysms();
    });
    
    $('#xsnd_btn').click(function(){
        
     if(excelChecker()){
        
        messageBox("Warning",false,'Are you sure you want to send?');
        
        $('#wrapper2').fadeIn('fast',function(){$('.send_status_box').fadeIn('fast',function(){
        $('.send_status_content_failed').css('display','none');
        $('.send_status_content2').css('display','none');
        $('.send_status_content').css('display','block');
        $('.send_status_content').html("<i>Please wait.Sending Messages...</i>");
       disableAnim=false;
       animateSend('.send_status_content');
         sendMessageFromExcel();
       });});
    
     }
        
    });
    
    numbersOnly("#mo_number");
    numbersOnly("#qnewcphone");
    
});
function hideProfile(){
    if($('.acc_dets').css('display')!='none'){
        $('.acc_dets').toggle('fast');
    }
}
function numbersOnly(classorid){
	$(classorid).keypress(function (e){
  var charCode = (e.which) ? e.which : e.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)&&(charCode!=44)&&((charCode!=43))) {
    return false;
    
  }
});
}
function limitCharacters(classorid,thelimit){
    
    $(classorid).keypress(function(e){
        if(blockpopclose)
        return false;
        
        var charCode=(e.which) ? e.which : e.keyCode;
       if((($(classorid).val().length+1)>thelimit)&(charCode!=8)&(charCode!=46)&(charCode!=37)&(charCode!=39))
        return false;
    });
    
}
function sendMessageFromExcel(){
    
      //alert($('#contact_sec').val());
      
     var selected_rows="";
     
     
     var checkboxes= $('.c_box').toArray();
     
     
     for(i=0;i<checkboxes.length;i++)
     if(checkboxes[i].checked){
        if(selected_rows==""){
            selected_rows=checkboxes[i].value;
        }else{
            selected_rows=selected_rows+'_'+checkboxes[i].value;
        }
     }
     
     $.post($('#main_url').val()+"&sm=28",{contact : $('#contact_sec').html(),filter : $('.filter_bar').html(),
     message : $('#mn_message2').val(),selectedrows: selected_rows},function(data){
        
       var ob=jParser(data);
            if(ob.status=='Success'){
                 
                 messageBox("Success",false,ob.vals);
                 
                 $('#mn_message2').val("");
                 $('#excel_content').html("");
                 $('#ws_select').html("");
                 $('.span_file').html("Click Here To Load File.");
                 getSMSUnits();
                 
                }else{
                messageBox('Failed',false,ob.vals);
                    
            }
    });
   
     
}
function saveTemplate(){
    if(excelChecker()){
        
    }
}
function excelChecker(){
    if($('#excel_content').html().trim()==""){
        messageBox('Warning',false,'Excel/CSV file not loaded.');
        return false;
    }
     if($('#mn_message2').val()==""){
        messageBox('Warning',false,'Message content is empty');
        return false;
     }
     
     check_box=$('.c_box').toArray();
     
     var has_selected=false;
     
     for(i=0;i<check_box.length;i++){
        if(check_box[i].checked){
            has_selected=true;
            break;
        }
     }
     
     if(!has_selected){
        messageBox('Warning',false,'Please select records from list.');
        return false;
     } 
     if($('#contact_sec').html().trim()==""){
        messageBox('Warning',false,'Contact column not selected');
        return false;
     }
     return true;
}
function getSMSUnits(){
    $.post($('#main_url').val()+'&sm=26',{},function(data){
        //alert(data);
        var ob=jParser(data);
        
        
        
        if(ob.status=='Success'){
            $('#sm_units').html(ob.vals);
            $('#txtUn').html(ob.vals);
        }
        
    });
}
function sendMessageFromPanel(){
   
    var m=$('.c_chk').toArray();
    var checkedValues="";
        for(i=0;i<m.length;i++)
        if(m[i].checked){
            if(checkedValues==""){
                checkedValues=m[i].value;
            }else{

                checkedValues=checkedValues+"_"+m[i].value;
            }
        }
        
    
       if($('#cat_select').val()==0){
        
         $.post($('#main_url').val()+"&sm=27",{message:$('#mn_message').val(),recipients:$('#mo_number').val()},function(data){
           
           var ob=jParser(data);
            
            if(ob.status=='Success'){
                 
                 messageBox("Success",false,ob.vals);
                 $('#mn_message').val("");
                 getSMSUnits();
                 
                }else{
                messageBox('Failed',false,ob.vals);
                    
            }
            
         })
        
        }else{
            
        $.post($('#main_url').val()+"&sm=7",{message:$('#mn_message').val(),recipients:checkedValues},function(data){
           
            var ob=jParser(data);
            if(ob.status=='Success'){
                 
                 messageBox("Success",false,ob.vals);
                 
                 getSMSUnits();
                 
                }else{
                messageBox('Failed',false,ob.vals);
                    
            }
            //closeSending();
        });
        
       }
}
function loadCategory(){
    if($('#cat_select').val()!=0){
        $('.phone_no').hide('fast');
        $('#list_container').html("");
        $('#list_container').html("<i>Loading...</i>");
        $('#cat_title').fadeIn('fast');
        var cattitle=$('#cat_select').val().split('_');
        $('#cat_title').html(cattitle[0]);
        loadContacts();
        if(for_contacts){
            $('#del_btn').fadeIn("fast");
        }
    }else{
        $('.phone_no').show('fast');
        if(for_contacts){
            $('#del_btn').fadeOut("fast");
            $('#list_container').html("<div id=\"info_cont\">Select group to load contacts</div>");
            }else{
                $('#cat_title').fadeOut('fast',function(){$('#list_container').html("<div id=\"info_cont\">Group not selected</div>");});
        } 
    }
}
function letterCount(suffix){
    if($('#mn_message'+suffix).val()!=""){
     $("#t_c"+suffix).html($('#mn_message'+suffix).val().length);
     
    }else{
      $("#t_c"+suffix).html('0');
    }
}
function closePop(){
    if(!blockpopclose){
        $('.pop_box').fadeOut('fast',function(){$('#wrapper').fadeOut('fast');});
    }else{
        messageBox("Warning",true,'Please wait.',null,null,false);
    }
}
function openPop(popname,callbackfunc){
    $('#wrapper').fadeIn('fast',function(){
        $("#"+popname).fadeIn('fast',function(){
            
            if(callbackfunc!=undefined)
            callbackfunc();
            
            });
    });
}
function quickAddGroupPop(){
    
    $('#g_cont').html("<div id=\"form_row\"><i>Please wait.Loading...</i></div>");
    
    $.post($('#main_url').val()+"&sm=25",{},function(data){
        var ob=jParser(data);
        if(ob.status=="Success")
        $('#g_cont').html(ob.vals);
    });
    
    openPop('pop_group');
}
function quickAddContactPop(){
    if($('#cat_select').val()==0){
        messageBox('Warning',false,'Select contact group first');
        return;
    }
    $('#recent_div').html("<div id=\"form_row\"><i id=\"iload\">Loading....</i></div>");
    
    openPop('pop_contact',function(){
        
        $("#multi_md").unbind();
         
        $("#multi_md").click(function(){
            switchToMultiMode();
        });
    
        disableAnim=false;
        
        animateSend('#iload',false);
       
        $.post($('#main_url').val()+"&sm=22",{ categ : $('#cat_select').val()},function(data){
        
        disableAnim=true;
         
        var ob=jParser(data);
         
        if(ob.status=="Success"){
          
          $('#recent_div').html(ob.vals);
        
        }
        
    });
        
    });

}
function switchToMultiMode(){
    $("#multi_md").fadeOut('fast',function(){
        $("#single_md").fadeIn('fast',function(){
            $("#sc_wrap").fadeOut('fast',function(){
                
                $("#mc_wrap").fadeIn('fast',function(){
                $("#single_md").unbind();
                $("#single_md").click(function(){
                    switchToSingleMode();
                });
                });
            });
        });        
    });
    
}
function switchToSingleMode(){
    $("#single_md").fadeOut('fast',function(){
        $("#multi_md").fadeIn('fast',function(){
            $("#mc_wrap").fadeOut('fast', function(){ $("#sc_wrap").fadeIn('fast');});
            $("#contact_prev").html("");
            $("#up_name").html("");
            $("#up_contact").html("");
            $('#sheet_selector').html("");
        });
    });
}
function swToExcel(){
    $('#p_mode').fadeOut('fast',function(){
        $('#p_mode2').fadeIn('fast',function(){$('#m_default').fadeOut('fast');$('#m_excel').fadeIn('fast');});
        $('#add_group').fadeOut('fast');
        $('#add_contact').fadeOut('fast');
        $('#n_mode').fadeOut('fast')
        $('#ws_select').html("");
        $('.span_file').html('Click Here To Load File.');
        });
}
function swToDefault(){
    if($('#mn_message2').val()!="")
     if(!confirm("You are about to exit excel.Message will be discarded.")){
         return;
     }
      
    
    $('#p_mode2').fadeOut('fast',function(){
        $('#p_mode').fadeIn('fast',function(){$('#m_default').fadeIn('fast');$('#m_excel').fadeOut('fast');$('#span').html('Click Here To Load Excel File.');});
        $('#add_group').fadeIn('fast');
        $('#add_contact').fadeIn('fast');
        $('#n_mode').fadeIn('fast')
        $('#excel_content').html("");
        $('#mn_message2').val("");
        letterCount("2")
        });
}
function ldExcel(u_path){
    var fname_parts =$(".excel_loader").val().split('.');
    var f_ext=fname_parts[fname_parts.length-1];
    if((f_ext!='xls')&(f_ext!='xlsx')&(f_ext!='csv')){
        $('#excel_content').html("<div id=\"warning_msg\">Invalid file format</div>");
        return;
    }
    $('#excel_content').html("<div id=\"form_row\"><strong><i>Please wait while loading content from your excel file....</i></strong></div>");
    var xlfiles=$(".excel_loader").val().split("\\");
    $('.span_file').html(xlfiles[xlfiles.length-1]);
    
    $('#ws_select').html("");
    
    $('#xslfrm').submit();
}
function patchList(){
    $('.excel_col').click(function(){
       if(xcel_col==""){
           xcel_col= $(this).attr('id');
          hMessageBox("#mn_message2",'0px 0px 2px #bbb');
          hMessageBox("#contact_sec",'0px 0px 0px #bbb');
          hMessageBox(".filter_bar",'0px 0px 0px #bbb');
       }else{
           xcel_col= $(this).attr('id');
       }
    });
    
    $('#contact_sec').click(function(){
       makeAsContactCol();
    });
    
    $('#selfil').change(function(){
        manageFieldInput(this,"#filter_value",'is less than,is greater than');
    });
    
    $('#contact_sec').mouseover(function(){
       if(xcel_col!=""){
         $('#t_infocon').html('Click to assign '+ xcel_col.split('_')[1]+' as contact column.');
         $('#t_infocon').css('margin-left','120px');
         $('#t_infocon').css('margin-top','30px');
         $('#t_infocon').fadeIn('fast');
       }
    });
    
    $('.filter_bar').mouseover(function(){
        if(xcel_col!=""){
        $('#t_infofilter').html('Click to add filter on '+ xcel_col.split('_')[1]+'.');
        $('#t_infofilter').css('margin-left','240px');
        $('#t_infofilter').css('margin-top','30px');
         $('#t_infofilter').fadeIn('fast');
         }
    });
    
    $('.filter_bar').mouseout(function(){
       
       setTimeout(function(){$('#t_infofilter').fadeOut('slow');},1000);
        
    });
    
    $('.filter_bar').click(function(){
        if(xcel_col!=""){
            $('#col_field').html(xcel_col.split('_')[1]);
            $('.where_wrap').fadeIn('fast');
            $('#cld_'+xcel_col.split("_")[1]).attr("checked",false);
            xcel_col="";
        }
    });
    
    $('#adfilter').click(function(){
        if($("#filter_value").val()==""){
            messageBox("Warning",false,'Enter filter value');
            $("#filter_value").css("border","2px solid #f00");
            return;
        }
        if($('.filter_bar').html()==""){
            $('.filter_bar').html($('#col_field').html()+' '+$('#selfil').val()+" '"+$('#filter_value').val()+"'");
        }else{
            $('.filter_bar').html($('.filter_bar').html()+', '+$('#col_field').html()+' '+$('#selfil').val()+" '"+$('#filter_value').val()+"'");
        }
        
        $('.rmv_f').fadeIn('fast');
        
        $('#filter_value').val("");
        
        
        $('.where_wrap').fadeOut('fast');
        
    });
    
    $('.rmv_f').click(function(){
    
      var splits=$('.filter_bar').html().split(',');
      var newfilters="";
      
      for(i=0;i<splits.length-1;i++)
      if(newfilters==""){
        newfilters=splits[i];
      }else{
        newfilters=newfilters+','+splits[i];
      }
     
     $('.filter_bar').html(newfilters);
     
     if(newfilters.trim()=="")
     $('.rmv_f').fadeOut('fast');
     
    });
    
     $("#filter_value").keypress(function(e){
        $(this).css("border","none");
        });
    
    $('.cls_wrap').click(function(){
        $('.where_wrap').fadeOut('fast');
    });
    
    $('#contact_sec').mouseout(function(){
        $('#t_infocon').fadeOut('fast');
    });
    
    $('.check_m').change(function(){
        if($('.check_m').prop('checked')){
            $('.c_box').prop('checked',true);
        }else{
            $('.c_box').prop('checked',false);
        }
    });
    
}
function manageFieldInput(caller,targetfield,optionValues){
    
    $(targetfield).unbind();
    
    var vals =optionValues.split(',');
    
    if($.inArray($(caller).val(),vals)>-1){
    
    $(targetfield).val("");
    
    $(targetfield).keypress(function(e){
        $("#filter_value").css("border","none");
        var charCode=(e.which) ? e.which : e.keyCode;
        if(!((charCode>47)&(charCode<58))&(charCode!=8)&(charCode!=46)){
            return false;
        }
    });
    }else{
        $(targetfield).keypress(function(e){
        $("#filter_value").css("border","none");
        });
    }
}
function makeAsContactCol(){
    
    if(xcel_col!=""){
     $('#contact_sec').html(xcel_col.split('_')[1]);
     $("#"+xcel_col).attr('checked',false);
     xcel_col="";
    }
    
}
function insertColToMes(){

    
    if(xcel_col!=""){      
      
      $("#"+xcel_col).attr('checked',false);
      
      var vl=xcel_col.split('_');
      
      
      
      var smsdata=$("#mn_message2").val();
      
      var pos=$("#mn_message2").prop('selectionStart');
      
      var firstPart=smsdata.substr(0,pos);
       
      var secondPart=smsdata.substr(pos,smsdata.length);
      
      $("#mn_message2").val(firstPart + "{"+vl[vl.length-1]+"}" + secondPart);
      xcel_col="";
      $("#mn_message2").setCursorPosition(pos);
    
    }
    
}
function loadContacts(){

    $.post($('#main_url').val()+"&sm=2&",{categ : getSelectedGroup()},function(data){
    var ob=jParser(data);
     $("#list_container").html(ob.vals);
        patchClist();
    });
}
function patchClist(){
    
    $('#c_search').unbind()
    
    $('.c_mchk').unbind();
    
    $('.c_chk').unbind();
    
    $('.edit_btn').unbind();
    
    $('.form_button_listcn').unbind();
    
    $('.form_button_save').unbind();
    
    $('#del_contact').unbind();
    
    $('#c_search').keyup(function(){
      loadCSearch();   
    });
    $('.c_mchk').click(function(){
      if(!$('.c_mchk').prop('checked')){
      $('.c_chk').prop('checked',false);
      }else{
       $('.c_chk').prop('checked',true); 
      }
    });
    $('.c_chk').click(function(){
        if(!$(this).prop('checked')){
            $('.c_mchk').prop('checked',false);
        }
    });
    $('.edit_btn').click(function(){
        activateEdit(this);
    });
    $('.form_button_listcn').click(function(){
        cancelEdit(this);
    })
    $('.form_button_save').click(function(){
        updateContact(this);
    })
    $('#del_contact').click(function(){
        
     var ids="";
     
     var n_ids=$(".c_chk").toArray();
     
     for(i=0;i<n_ids.length;i++){
        if(n_ids[i].checked){
            if(ids==""){
                ids=n_ids[i].value;
            }else{
                ids=ids+','+n_ids[i].value;
            }
        }
     }
        if(ids!=""){
          deleteContact(ids);
        }else{
            messageBox("Warning",false,"Contact not selected");
        }
    });
}
function activateEdit(el){
    
    var edit_field= $(el).attr('id').split('_');
    
    $('#cn_'+edit_field[edit_field.length-1]).html("<input type=\"hidden\" id=\"hn_"+edit_field[edit_field.length-1]+"\" value=\""+$('#cn_'+edit_field[edit_field.length-1]).html()+"\" /><input type=\"text\" class=\"form_input\" value=\""+ $('#cn_'+edit_field[edit_field.length-1]).html()+"\" id=\"ed_"+edit_field[edit_field.length-1]+"\" />");
    
     $('#pn_'+edit_field[edit_field.length-1]).html("<input type=\"hidden\" id=\"hp_"+edit_field[edit_field.length-1]+"\" value=\""+$('#pn_'+edit_field[edit_field.length-1]).html()+"\"/><input type=\"text\" class=\"form_input\" id=\"p_"+edit_field[edit_field.length-1]+"\" value=\""+ $('#pn_'+edit_field[edit_field.length-1]).html()+"\"/>");
     
     numbersOnly("#p_"+edit_field[edit_field.length-1]);
     
     $(el).fadeOut('fast',function(){
        $('#btn_wrp'+edit_field[edit_field.length-1]).fadeIn('fast');
     });
     

}
function cancelEdit(el){
    
    var h_fields=$(el).attr('id').split('_');
    
    $('#cn_'+h_fields[h_fields.length-1]).html($('#hn_'+h_fields[h_fields.length-1]).val());
    
    $('#pn_'+h_fields[h_fields.length-1]).html($('#hp_'+h_fields[h_fields.length-1]).val());
    
    $('#btn_wrp'+h_fields[h_fields.length-1]).fadeOut('fast',function(){
        $("#bn_"+h_fields[h_fields.length-1]).fadeIn('fast');
     });
    
}
function updateContact(el){
    var u_field=$(el).attr('id').split('_');
    
    if($('#ed_'+u_field[u_field.length-1]).val().trim()==""){
        messageBox("Warning",false,"Please enter contact name!");
        return;
    }
    
    if($('#p_'+u_field[u_field.length-1]).val().trim()==""){
        messageBox("Warning",false,"Please enter phone number!");
        return;
    }
    
    $("#ac_div"+u_field[u_field.length-1]).html("<i>Updating...</i>");


    disableAnim=false;
    
    animateSend("#ac_div"+u_field[u_field.length-1],false);
    
    $.post($('#main_url').val()+"&sm=9",{contactName : $('#ed_'+u_field[u_field.length-1]).val(),contactPhone : $('#p_'+u_field[u_field.length-1]).val(),cid : u_field[u_field.length-1],gid : getSelectedGroup()},function(data){
       
            
       var ob=jParser(data);
       
       if(ob.status="Success"){
       
       disableAnim=true;
       
       $('#cn_'+u_field[u_field.length-1]).html($('#ed_'+u_field[u_field.length-1]).val());
    
       $('#pn_'+u_field[u_field.length-1]).html($('#p_'+u_field[u_field.length-1]).val());
       
       $("#ac_div"+u_field[u_field.length-1]).html(ob.vals);
       
       patchClist();
       
       }
        
    });
    
    
}
function deleteContact(){
    messageBox("Warning",false,"Are you sure you want to delete?","DeleteContact",true);
}
function loadCSearch(){
    if(xhr!=null)
    xhr.abort();
    
    $('#c_list').html("<i style=\"width:100%;text-align:center;margin-top:5px;font-weight:bold;\">Loading...</i>");
    xhr=$.post($('#main_url').val()+"&sm=3",{s_search : $('#c_search').val(),categ : getSelectedGroup()},function(data){
     ob=jParser(data);
        $('#c_list').html(ob.vals);
        
        patchClist();
    });
}
function qsaveNewContacts(){
    
    if($('#qnewcname').val().trim()==""){
     alert("Enter Name");
     return;
    }
    
    if($('#qnewcphone').val().trim()==""){
    alert("Enter phone number");
    return;
    }
    $('#recent_div').html("<i>Processing...</i>");
    
    $.post($('#main_url').val()+"&sm=4",{qname:$('#qnewcname').val(),qphone:$('#qnewcphone').val(),categ:getSelectedGroup()},function(data){
        var ob=jParser(data);
        $('#qnewcname').val("");
        $('#qnewcphone').val("");
        $('#recent_div').html(ob.datas);
        loadContacts();        
    });

}
function qsaveNewGroup(){
   
   if($('#qnewgroup').val().trim()==""){
    alert("Enter group name");
    return;
   }
   $('#g_cont').html("<i>Processing...</i>");
   $.post($('#main_url').val()+"&sm=5",{qnewgroup:$('#qnewgroup').val()},function(data){
      ob=jParser(data);
      $('#g_cont').html(ob.vals);
      $('#qnewgroup').val("");
      appendNewOption();
   });

}
function appendNewOption(){
    $.post($('#main_url').val()+"&sm=6",{cate:getSelectedGroup()},function(data){
        ob=jParser(data);
        var thevals=ob.vals.split('/');
        $('#cat_select').append("<option value=\""+thevals[1]+"\">"+thevals[0]+"</option>");
    })
}
function getSelectedGroup(){
    $cattitle=$('#cat_select').val().split("_");
    return $cattitle[$cattitle.length-1]
}
function animateSend(divname,hidedisp){
  if(hidedisp==undefined){
    hidedisp=true;
  }
   var t= $(divname).animate({opacity:'0.1'},'slow',function(){
        if(!disableAnim){
            disableAnim=false;
            reverseAnim(divname,hidedisp);
        }else{
            if(!hidedisp){
                $(divname).css('opacity','1');
            }else{
                $(divname).css('display','none');
            }
        }
        
    });
}
function reverseAnim(divname,hidedisp){
    $(divname).animate({opacity:'1'},'slow',function(){
        animateSend(divname,hidedisp);
    });
}
function showSending(){
    
    if($('#cat_select').val()==0){
    
     if($('#mo_number').val().trim()==""){
        messageBox('Warning',false,"Phone number missing!");
        return;
     }
     
     if($('#mn_message').val().trim()==""){
        messageBox('Warning',false,"Message content is empty!");
        return false;
     }
    
    if(!confirm("Are you sure you want to send?"))
    return false;
        
        $('#wrapper2').fadeIn('fast',function(){$('.send_status_box').fadeIn('fast',function(){
        $('.send_status_content_failed').css('display','none');
        $('.send_status_content2').css('display','none');
        $('.send_status_content').css('display','block');
        $('.send_status_content').html("<i>Please wait.Sending Messages...</i>");
       disableAnim=false;
       animateSend('.send_status_content');
       sendMessageFromPanel();
         
       });});
    
     
         
     }else{
        
     var m=$('.c_chk').toArray();
     var has_selected=false
     for($i=0;$i<m.length;$i++){
        if(m[$i].checked){
        //alert('hello');
        has_selected=true;
        break;
        }
     }
     if(!has_selected){
        messageBox('Warning',false,"Select contact(s) from list!");
        return;
     }
    
    
    if($('#mn_message').val().trim()==""){
        messageBox('Warning',false,"Message content is empty!");
        return false;
     }
     
     if(!confirm("Are you sure you want to send?"))
    return false;
    
    $('#wrapper2').fadeIn('fast',function(){$('.send_status_box').fadeIn('fast',function(){
        $('.send_status_content_failed').css('display','none');
        $('.send_status_content2').css('display','none');
        $('.send_status_content').css('display','block');
        $('.send_status_content').html("<i>Please wait.Sending Messages...</i>");
       disableAnim=false;
       animateSend('.send_status_content');
       sendMessageFromPanel(); 
    });});
    }
}
function closeSending(closeNow,callback){
    if(!closeNow){
    setTimeout(function(){$('.send_status_box').fadeOut('fast',function(){$('#wrapper2').fadeOut('fast',function(){
        $('.send_status_content_failed').css('display','none');
        $('.send_status_content2').css('display','none');
        $('.send_status_content_warning').css('display','none');
        $('.tmp_cont').css('display','none')
        if(callback!=undefined)
        callback();
    });})},2000);
    }else{
        $('.send_status_box').fadeOut('fast',function(){$('#wrapper2').fadeOut('fast',function(){
        $('.tmp_cont').css('display','none')
        $('.send_status_content_failed').css('display','none');
        $('.send_status_content2').css('display','none');
        $('.send_status_content_warning').css('display','none');
        if(callback!=undefined)
        callback();
    });});
    }
}
function messageBox(tOption,autoClose,dataText,actype,yesno,withbg,hideOk,evsource){
    
    if(hideOk==undefined)
     hideOk=false;
    
    if(yesno==undefined) 
     yesno=false;
    
    if(actype==undefined)
     actype="okbtn";
    
    if(withbg==undefined)
    withbg=true;
    
    var okbtn="";
    var cancelbtn=""
    var style="style=\"margin-left:40%;\"";    
    var op1="Ok";
    var op2="Cancel";
    
    if(yesno){
        op1="Yes";
        op2="No";
    }
    
    
    if(actype!="okbtn"){
     cancelbtn="<div class=\"form_button_add\" id=\"msg_cancel\">"+op2+"</div>";
     style="";
    }
    
    if(!autoClose && !hideOk)
       okbtn="<div id=\"form_row\"><div class=\"form_button\" id=\"msg_ok\" "+style+">"+op1+"</div>"+cancelbtn+"</div>"; 
           
    $('.tmp_cont').css('display','none');
    $('.send_status_content').css('display','none');
    $('.send_status_content').html("");
    $('.send_status_content2').css('display','none');
    $('.send_status_content2').html("");
    $('.send_status_content_failed').css('display','none');
    $('.send_status_content_failed').html("");
    $('.send_status_content_warning').css('display','none');
    $('.send_status_content_warning').html("");
    $('.send_status_content_processing').css('display','none');
    $('.send_status_content_processing').html("");
    
    if(withbg){
    $('#wrapper2').fadeIn('fast',function(){showBox(tOption,autoClose,dataText,actype,okbtn,evsource)});
    }else{
        showBox(tOption,autoClose,dataText,actype,okbtn,evsource);
    }
    
}
function showBox(tOption,autoClose,dataText,actype,okbtn,evsource){
    $('.send_status_box').fadeIn('fast',function(){
        switch(tOption){
           
           case 'Processing':
           $('.send_status_content_processing').css('display','block');
           $('.send_status_content_processing').html("<i>"+dataText+"</i>"+okbtn);
           if(!autoClose){
           okAction(actype,evsource);
           }else{
             setTimeout(function(){$('.send_status_box').fadeOut('fast')},1000);
           }
           break; 
           
           case 'Status':
           $('.send_status_content').css('display','block');
           $('.send_status_content').html("<i>"+dataText+"</i>"+okbtn);
           if(!autoClose){
           okAction(actype,evsource);
           }else{
             setTimeout(function(){$('.send_status_box').fadeOut('fast')},1000);
           }
           break;  
            
           case 'Success':
           $('.send_status_content2').css('display','block');
           $('.send_status_content2').html("<i>"+dataText+"</i>"+okbtn);
           if(!autoClose){
           okAction(actype,evsource);
           }else{
             setTimeout(function(){$('.send_status_box').fadeOut('fast')},1000);
           }
           break; 
           
           case 'Warning':
           $('.send_status_content_warning').css('display','block');
           $('.send_status_content_warning').html("<i>"+dataText+"</i>"+okbtn);
           if(!autoClose){
           okAction(actype,evsource);
           }else{
             setTimeout(function(){$('.send_status_box').fadeOut('fast')},1000);
           }
           break;
           
          case 'Failed':
           $('.send_status_content_failed').css('display','block');
           $('.send_status_content_failed').html("<i>"+dataText+"</i>"+okbtn);
           if(!autoClose){
           okAction(actype,evsource);
           }else{
            setTimeout(function(){$('.send_status_box').fadeOut('fast')},1000);
           }
           break;
        }
    });
}
function patchOk(cb){
    $('#msg_ok').click(function(){
        closeSending(true,cb);
    });
    $('#msg_cancel').click(function(){
        closeSending(true);
    });
}
function insertName(c_div){
    var pos=$(c_div).prop('selectionStart');
    var smsdata=$(c_div).val();
    var firstPart=smsdata.substr(0,pos);
      
      var secondPart=smsdata.substr(pos,smsdata.length);
      
      $(c_div).val(firstPart + "{name}" + secondPart);
      
      $(c_div).setCursorPosition(pos);
      
      xcel_col="";
    
}
function okAction(buttonAction,evsource){
    
    switch(buttonAction){
        case 'DeleteGroup':
        patchOk(function(){deleteGroup()});
        break;
        
        case 'DeleteContact':
        patchOk(function(){deleteContacts()});
        break;
        
        case 'deleteTemplate':
        patchOk(function(){deleteMessageTemplate(evsource)});
        
        default:
        patchOk();
    }
    
}
function deleteGroup(){
      var m=$('.c_chk').toArray();
      if(m.length>0)
      $('#wrapper').fadeIn("fast");
      disableAnim=false;
      animateSend('.send_status_content_processing');
      messageBox("Processing",false,"Deleting group.Please wait...");
      
      $.post($('#main_url').val()+"&sm=8",{gid: getSelectedGroup()},function(data){
        
        var ob=jParser(data);
        
        if(ob.status=="Success"){
             disableAnim=true
             $('.send_status_content_processing').css('display','none');
             messageBox("Success",false,ob.vals);
             $("#cat_select option[value='"+$('#cat_select').val()+"']").remove();
             loadCategory();
        }
        
        if(ob.status=="Failed"){
            messageBox("Failed",false,ob.vals);
        }
                
      });
     
      //$('#cat_select').prop('selectedIndex',0);
      
}
function loadSettingsPanel(){
   openPop('acc_settings',function(){getSettingsParameters()});
}
function getSettingsParameters(){
    
    $('#acc_settings').html("<div id=\"form_row\"><i id=\"i_load\">Please wait.Loading...</i><div>");
    
    disableAnim=false;
    
    animateSend('#i_load',false)
    
    try{
    
    $.post($('#main_url').val()+"&sm=10",{acset:''},function(data){
       
        disableAnim=true;
        
        ob=jParser(data);
        
        $('#acc_settings').html(ob.vals);
        
        $('#editprof').click(function(){ editProfile()});
        
        $("#edit_close").click(function(){closePop()});
        
        $('#chng_pass').click(function(){
            changePass();
        });
        
    });
    }catch(e){
        alert(e);
    }
}
function editProfile(){
    
    if($('#editprof').attr('class')=="form_button_add"){
      
      disableAnim=true;
      
      $('#edit_status').html(" ");
      
      var ptxt=$('#phone_text').html();
      $('#phone_text').html("<input type=\"hidden\" id=\"ptxt\" value=\""+ptxt+"\" /><input type=\"text\" class=\"form_input\" id=\"txtPhone\" value=\""+ptxt+"\"/>");
      numbersOnly('#txtPhone');
      //var etxt=$('#email_text').html();
      //$('#email_text').html("<input type=\"hidden\" id=\"etxt\" value=\""+etxt+"\" /><input class=\"form_input\" id=\"txtEmail\" value=\""+etxt+"\"/>");
      
      $('#editprof').attr('class','form_button');
      $('#editprof').val('Save Changes');
      
    }else{
        disableAnim=false;
      $('#edit_status').html("Updating profile...");
      animateSend('#edit_status',false);
      if($('#txtPhone').val()!=""){
      $.post($('#main_url').val()+"&sm=11",{phone : $('#txtPhone').val()},function(data){
        var ob=jParser(data);
        if(ob.status=="Success"){
         disableAnim=true;
        $('#edit_status').html(ob.vals);
         $('#pcn').html($('#txtPhone').val());
         $('#phone_text').html($('#txtPhone').val());
         $('#editprof').attr('class','form_button_add');
         $('#editprof').val('Edit Profile');  
        }else{
          $('#edit_status').html(ob.vals);
        }
      });
      //$('#phone_text').html($('#ptxt').val());
     // $('#email_text').html($('#etxt').val());
      
      }else{
        disableAnim=true;
        $('#edit_status').html(" ");
        $('#phone_text').html($('#ptxt').val());
        $('#editprof').attr('class','form_button_add');
        $('#editprof').val('Edit Profile');
      }  
    }
}
function changePass(){
    if($('#opass').val()==""){
     $('#edit_status2').html("<div id=\"warning_msg\">Enter current password</div>");
     return;
    }
    if($('#newpass').val()==""){
     $('#edit_status2').html("<div id=\"warning_msg\">Enter new password</div>");
     return;
    }
    if($('#newpass').val()!=$('#rpass').val()){
     $('#edit_status2').html("<div id=\"warning_msg\">Password mismatch</div>");
     return;
    }
    
    $.post($('#main_url').val()+"&sm=12",{oldpass : $('#opass').val(),newpass : $('#newpass').val(),reppass : $('#rpass').val()},
    function(data){
        
        var ob=jParser(data);
        if(ob.status=="Success"){
          try{
           alert('Password changed successfully.');
           location.reload();
           
           }catch(e){
            alert(e);
           }
        }else{
          $('#edit_status2').html(ob.vals);
        }
    });
    
}
function getSenderId(){
    
    openPop('chsid_box',function(){ getSIPanel()});
    
}
function getSIPanel(){
    
    $('#chsid_box').html("<div id=\"form_row\"><i>Loading.Please wait...</i></div>");
    
    $.post($('#main_url').val()+'&sm=13',{},function(data){
        //alert(data);
        var ob=jParser(data);
        if(ob.status=="Success"){
            $('#chsid_box').html(ob.vals);
            patchSIPanel();
        }
       $("#sidcpop").click(function(){ closePop()}); 
       
       $("#ex_sidbtn").click(function(){ miniProcessPay(); }); 
       
       $("#rsnd").keyup(function(){ hideMiniProcessPay();});
       
    });
    
}
function patchSIPanel(){
    limitCharacters("#rsnd",11);
    $('.form_button_hire').click(function(){ hireSenderId(this); });
     $('.radsid').click(function(){
        changeDefaultSenderId(this);
    });
}
function miniProcessPay(){
    if($('#rsnd').val().trim()==""){
      $('#rsnd').css('box-shadow','inset 0px 0px 5px #f00');
      $('#rsnd').css('-moz-box-shadow','inset 0px 0px 5px #f00');
      $('#rsnd').css('-webkit-box-shadow','inset 0px 0px 5px #f00');
      messageBox('Warning',true,'Please enter a valid sender id.',null,null,false);
      return;
    }
    
    $('#button_wrap').fadeOut('fast',function(){
        $('#mini_paywindow').fadeIn('fast',function(){
            $('#mini_paywindow').html("<i style=\"padding:0px;margin-top:0px;\" id=\"lp\">Loading...</i>");
            animateSend("#lp",false);
            
            $(this).animate({display:'block',background:'#000000'},'fast',function(){
                loadPayOption();
            });
            });
    });
}
function hideMiniProcessPay(){
    
    if(blockpopclose)
    return;
    
    $('#rsnd').css('box-shadow','inset 0px 0px 5px #bbb');
    $('#rsnd').css('-moz-box-shadow','inset 0px 0px 5px #bbb');
    $('#rsnd').css('-webkit-box-shadow','inset 0px 0px 5px #bbb');
    
    $('#mini_paywindow').animate({display:'none',height: '35px',background:'#000000'},'fast','linear',function(){
        $('#mini_paywindow').fadeOut('fast',function(){ $('#button_wrap').fadeIn('fast'); });
        $('#mini_paywindow').html("");
        $('#paywrap').html("<div id=\"mini_paywindow\"></div>");
    });
    
}
function loadPayOption(){
    $.post($('#main_url').val()+'&sm=14',{pay_op :1,sndid: $('#rsnd').val()},function(data){
        //alert(data);
        var ob=jParser(data);
        if(ob.status="Success"){
            disableAnim=true;
            
            $('#mini_paywindow').html(ob.vals);
            $('#panel_left').animate({scrollTop:($('#mini_paywindow').position().top-$('#panel_left').position().top)},'fast');
            $('#clsp').click(function(){
                hideMiniProcessPay();
                $('#panel_left').animate({scrollTop:'0px'},'fast');
            })
            $('#tcode').css('text-transform','uppercase');
            $('#tcode').keypress(function(e){ 
                if(blockpopclose)
                return false;   
             });
            $('#p_pay').click(function(){
                pProcess();
            })
        }
    });
}
function jParser(theData){
   var ob=$.parseJSON(theData);
   if(ob.status=='Logout'){
     location.reload();
   }else{
    return ob;
   }
}
function pProcess(){
    if($('#tcode').val().trim()==""){
      messageBox('Warning',true,'Please enter transaction code.',null,null,false);
      return;
    }
    if($('#p_pay').val()=="Processing...")
    return false;
    
    $('#progress_div').html("");
    $('#p_pay').val('Processing...');
    blockpopclose=true;
    disableAnim=false;
    animateSend('#p_pay',false);
    
    $.post($('#main_url').val()+'&sm=15',{trans_code:$('#tcode').val(),rsnd : $('#rsnd').val()},function(data){
       // alert(data);
        var ob=jParser(data);
        if(ob.status=='Failed'){
         $('#progress_div').html(ob.vals);
         $('#p_pay').val('Process Payment');
         blockpopclose=false;
         disableAnim=true;
        }
        if(ob.status=='Success'){
            //messageBox('Success',false,ob.other,null,null,false);
            $('#progress_div').html(ob.vals);
            $('#rsnd').val("");
            
            disableAnim=true;
            $('#mini_paywindow').css('height','auto');
            setTimeout(function(){
                $('.closebar').fadeOut('fast');
                $('#mini_paywindow').animate({height:'34px'},'slow','linear');
                blockpopclose=false;
                
                setTimeout(function(){ $('#mini_paywindow').fadeOut('slow',function(){
                    hideMiniProcessPay();
                });},10000);
                
            },2000)
            
        }
    });
}
function changeDefaultSenderId(thisobj){
    
    var sid=$(thisobj).val();
    
    $('#progress_div3').html("<i>Updating...</i>");
    
    disableAnim=false;
    
    animateSend('#progress_div3',false);
    
    $.post($('#main_url').val()+"&sm=19",{sndid : sid},function(data){
        var ob=jParser(data);
        if(ob.status="Success"){
          disableAnim=true;
          $('#progress_div3').html("");
          $('#txtsnd').html($(thisobj).attr('id').split('_')[1]);
          $('#tsid').html($(thisobj).attr('id').split('_')[1]);

        }
    });
    
}
function buysms(){
    
    $('.buyUn').html("");
    
    $('.buyUn').toggle('fast',function(){
        $('.buyUn').html("<div id=\"form_row\" style=\"text-align:center;margin:50px 0px;\"><i id=\"load_i\">Loading....</i></div>");
        disableAnim=false;
        animateSend("#load_i");
        $.post($('#main_url').val()+"&sm=20",{},function(data){
           var ob=jParser(data);
           disableAnim=true;
           $('.buyUn').html(ob.vals);
           $('#min_cls').click(function(){$('.buyUn').toggle('fast')});
           $('#trans_code').css('text-transform','uppercase');
           $('#p_trans').click(function(){
             process_buy();
           });
        });
    });
}
function process_buy(){
    
    if($('#trans_code').val().trim()==""){
        messageBox("Warning",false,"Enter transaction code.");
        return;
    }
    
    $('#results_bar').html("");
    
    disableAnim=false;
    animateSend('#p_trans',false);
    
    $('#p_trans').val("Processing...");
    
    $.post($('#main_url').val()+"&sm=21",{trans_code : $('#trans_code').val()},function(data){
        //alert(data);
        var ob=jParser(data);
        if(ob.status=="Failed"){
            $('#results_bar').html(ob.vals);
            $('#p_trans').val("Credit Account");
        }
        if(ob.status=="Success"){
            $('.buyUn').html(ob.vals);
            $('#min_cls').click(function(){$('.buyUn').toggle('fast')});
            $('#sm_units').html(ob.unt);
            $('#txtUn').html(ob.unt);
        }
        disableAnim=true;
    });
}
function showContactsPrev(data){
    
    //alert(data);
    
    var ob=jParser(data);
    
    if(ob.status=="Success"){
        $('#contact_prev').html(ob.vals);
        sheets=ob.Sheets.split(',');
        
        if(ob.Refresh)
        for(i=0;i<sheets.length;i++)
        $('#sheet_selector').append("<option value=\""+i+"\">"+sheets[i]+"</option>");
        
        patchContactList();
    }
    
}
function loadCPreview(){
    $('#sheet_selector').html("")
    $("#up_name").html("");
    $("#up_contact").html("");
    $('#xslfcontact').submit();
}
function patchContactList(){
    
    $('.check_mup').click(function(){
        if(!$('.check_mup').prop('checked')){
            $('.c_boxup').prop('checked',false);
        }else{
          $('.c_boxup').prop('checked',$('.check_mup').prop('checked'));
        }
    });
    
    $('.c_boxup').click(function(){
       if($('.check_mup').prop('checked')){
        $('.check_mup').prop('checked',false)
       } 
    });
    
    $('.excel_colup').click(function(){
        selected_column= $(this).attr('id');
        highlight('#up_name');
        highlight('#up_contact');
    });
    
}
function patchStatics(){
    
    $('#up_name').mouseover(function(){
        if(selected_column!=""){
        $('#t_infoname').html("Click to use column "+ selected_column.split('_')[1] +" as contact name.");
        $('#t_infoname').fadeIn('fast');
        }
    });
    
     $('#up_name').mouseout(function(){
        $('#t_infoname').fadeOut('fast');
    });
    
    $('#up_name').click(function(){
        $('#up_name').html(selected_column.split('_')[1]);
        selected_column="";
        $('.excel_colup').attr('checked',false);
    });
    
    $('#up_contact').mouseover(function(){
        if(selected_column!=""){
        $('#t_infophone').html("Click to use column "+ selected_column.split('_')[1] +" as phone number.");
        $('#t_infophone').fadeIn('fast');
        }
    });
    
     $('#up_contact').mouseout(function(){
        $('#t_infophone').fadeOut('fast');
    });
    
     $('#up_contact').click(function(){
        $('#up_contact').html(selected_column.split('_')[1]);
        selected_column="";
        $('.excel_colup').attr('checked',false);
    });
    
}
function highlight(divname){
     $(divname).css('box-shadow','0px 0px 3px #099129');
     radiate(divname);
}
function radiate(divname){
    if(selected_column==""){
        $(divname).css('box-shadow','0px 0px 0px #099129');
        $(divname).animate({opacity : '1.0'},'fast');
        return;
    }
    $(divname).animate({opacity : '0.2'},'fast',function(){deradiate(divname)});
}
function hMessageBox(divname,shadowprop){
     $(divname).css('box-shadow','0px 0px 3px #099129');
     radiate2(divname,shadowprop);
}
function radiate2(divname,shadowprop){
    if(xcel_col==""){
        $(divname).css('box-shadow',shadowprop);
        $(divname).animate({opacity : '1.0'},'fast');
        return;
    }

    $(divname).animate({opacity : '0.2'},'fast',function(){deradiate2(divname,shadowprop)});
}
function deradiate2(divname,shadowprop){
    $(divname).animate({opacity : '1.0'},'fast',function(){
       radiate2(divname,shadowprop); 
    });
}
function deradiate(divname){
    $(divname).animate({opacity : '1.0'},'fast',function(){
       radiate(divname); 
    });
}
function saveMultiContacts(){
    
    if($('#contact_prev').html().trim()==""){
     messageBox("Warning",true,'Load contacts first',null,null,false);
     return;
     }
    if($('#up_name').html().trim()==""){
     $('#up_name').css('box-shadow','0px 0px 3px #f00');
     messageBox("Warning",true,'Select column for name field.',null,null,false);
     return;
    }
    if($('#up_contact').html().trim()==""){
     $('#up_contact').css('box-shadow','0px 0px 3px #f00');
     messageBox("Warning",true,'Select column for contact field.',null,null,false);
     return;
    }
    
    var has_selected=false;
    
    cbox=$('.c_boxup').toArray();
    
    for(i=0;i<cbox.length;i++)
      if(cbox[i].checked){
        has_selected=true;
        break;
      }
    if(!has_selected){
      messageBox("Warning",false,'Select contacts you want uploaded.',null,null,false);
      return;
    }
    var selected_values="";
    
    for(i=0;i<cbox.length;i++)
      if(cbox[i].checked){
        if(selected_values==""){
          selected_values=""+(i+1);
        }else{
          selected_values=selected_values + ","+(i+1);
        } 
     }
    
    $.post($('#main_url').val()+"&sm=24",{SelectedContacts : selected_values, NameField : $('#up_name').html(),
    ContactField : $('#up_contact').html(),ContactGroup : $('#cat_select').val().split('_')[1]},function(data){
        
        $('#contact_prev').html(data);
        
        var ob=jParser(data);
        
        if(ob.status=="Success"){
            $('#contact_prev').html("");
            $('#up_name').html("");
            $('#sheet_selector').html("");
            $('#up_contact').html("");
            loadContacts();
        }
        
    });
}