$(document).ready(function(){
    $('#n_mode').click(function(){
       templateSave(); 
    });
    $('#timepick').timepicker();
    $('#interval').change(function(){
        intervalSelect(this);
    });
    $('#timepick').keypress(function(e){
        return false;
    });
    $('.form_button').click(function(){
        $(this).css('box-shadow','0px 0px 0px #444;');
    });
    
    $('#save_sched').click(function(){
        saveSchedule();
    });
    
    $('#load_mess').click(function(){
        
       loadOutboxMessages();
        
    });
    
    $('#sel_stat').change(function(){
        loadOutboxMessages();
    });
    
    $('#test_schedule').click(function(){
        $.post($('#main_url').val()+"&sm=35",{},function(data){
            alert(data);
        });
    });
    
    patchTempDelete();
    
    patchScheduleList();
    
});
function loadOutboxMessages(){
     
     $('#s_cont').html("<div id=\"form_row\"><i id=\"load_id\">Please wait.Loading...</i></div>");
        animateSend('#load_id',false);
        
        $.post($('#main_url').val()+"&sm=32",{messageDate : $('#date_box2').val(),selstat : $('#sel_stat').val()},function(data){
            
            disableAnim=true;
            
            $('#s_cont').html(data);
            
     });
}
function saveSchedule(){
    
    //$('#sched_list').html("<div id=\"form_row\"><i id=\"l_status\" style=\"width:100%;text-indent:10px;float:left;\">Loading.Please wait...</i></div>");
    
    isChecked=true;
    
    $('.sl_t').css("border","none");
    $('.sl_g').css("border","none");
    $('#timepick').css("border","none");
    $('#date_box').css("border","none");
    
    if($('#sel_temp').val()==0){
        $('.sl_t').css("border","1px solid #f00");
        isChecked=false;
    }
    
    if($('#sel_grp').val()==0){
        $('.sl_g').css("border","1px solid #f00");
        isChecked=false;
    }
    
    if($('#timepick').val()==0){
        
        $('#timepick').css("border","1px solid #f00");
        isChecked=false;
    }
    
    if($('#interval').val()==4){
        if($('#date_box').val()==""){
         $('#date_box').css("border","1px solid #f00");
         isChecked=false;
        }
    }
    
    //alert($('#sel_temp').val());

    if(!isChecked){
      messageBox("Warning",false,"Complete required fields");
      return false;
    }
    disableAnim=false;
    
    
    messageBox("Processing",false,"Please Wait.Saving schedule",null,null,null,true);
    
    animateSend('.send_status_content_processing',true);
    
    $.post($('#main_url').val()+"&sm=30",{templates : $('#sel_temp').val(),
    group : $('#sel_grp').val().split('_')[1],interval : $('#interval').val(),days : $('#dayselect').val(),
    times : $('#timepick').val(),dates : $('#date_box').val()},function(data){
        
        var ob=jParser(data);
        disableAnim=true;
        if(ob.status=="Success"){
           $('#sched_list').html(ob.vals);
           patchScheduleList();
           messageBox('Success',false,"Schedule saved successfully");
        }else{
           $('#sched_list').html(ob.vals);
           patchScheduleList();
           messageBox('Failed',false,"Failed to save schedule");
        }
    });
    
    
}
function patchScheduleList(){
    $('.del_sch').click(function(){
        
        if(!confirm("Are you sure you want to delete this schedule?"))
        return false;
        
        var tsid=$(this).attr('id')
        
         tsid=tsid.split('_')[1];
        
        $.post($('#main_url').val()+"&sm=31",{sid : tsid},function(data){
             
             var ob=jParser(data);
             
             if(ob.status=="Success"){
                $('#sched_list').html(ob.vals);
                patchScheduleList();
                messageBox('Success',false,"Schedule deleted successfully");
             }
             
        });
        
    });
}
function patchTempDelete(){
    $('.del_temp').click(function(){
        messageBox("Warning",false,"Are you sure you want to delete this template.","deleteTemplate",true,true,false,this);
    });
}
function intervalSelect(thediv){
    //alert($(thediv).val());
    switch($(thediv).val()){
        case '4':
        $('.day_row').hide('fast',function(){ $('.date_row').show('fast'); });
        break;
        
        case '2': 
        daysoftheweek="Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday";
        populateSelect("#dayselect",daysoftheweek);
        $('.date_row').hide('fast',function(){ $('.day_row').show('fast');});
        break;
        
        case '3':
        daysofthemonth="";
        for(i=0;i<31;i++)if(daysofthemonth==""){daysofthemonth="Day "+(i+1);}else{daysofthemonth=daysofthemonth+',Day '+(i+1);}
        populateSelect("#dayselect",daysofthemonth);
        $('.date_row').hide('fast',function(){ $('.day_row').show('fast');});
        break;
        
        default:
        $('.date_row').hide('fast',function(){$('.day_row').hide('fast');});
    }
}
function populateSelect(inputid,values){
       
       $(inputid).html("");
       
       vals=values.split(',');
       
       for(i=0;i<vals.length;i++)
        $(inputid).append("<option value=\""+(i+1)+"\">"+vals[i]+"</option>");
        
}
function templateSave(){
    if($('#mn_message').val()==""){
        messageBox("Warning",false,'Message content is empty.');
        return false;
    }
    $('.tmp_cont').html("<div id=\"min_inner2\">New message template.<div id=\"cls_nt\">X</div></div>"+templateContent());
    $('.tmp_cont').css('display','block');
    $('#wrapper2').fadeIn('fast',function(){$('.send_status_box').fadeIn('fast',function(){
        $('#cls_nt').click(function(){
        closeSending(true);
      });
      $('#sv_temp').click(function(){
        processTemp();
      });
    })});
}
function templateContent(){
   return "<div id=\"form_row\"><div id=\"label\">Template Title</div><input class=\"form_input\" id=\"t_title\"/><input type=\"button\" class=\"form_button\" id=\"sv_temp\" value=\"Save Template\"/></div>";
}
function processTemp(){
    if($('#t_title').val()==""){
        $('#t_title').css('border','1px solid #f00');
        return false;
    }

    disableAnim=false;
    
    $('#sv_temp').val('Processing...');
    
    animateSend('#sv_temp');
    
    $.post($('#main_url').val()+"&sm=29",{message : $('#mn_message').val(),t_title : $('#t_title').val()},function(data){
        
        var ob=jParser(data);
        
        if(ob.status=="Success"){
            disableAnim=true;
            messageBox("Success",false,'Template saved successfully.');
        }
        
    });
    
}
function hireSenderId(btn){
    
    $('#h_id').html("<div id=\"i_id\" style=\"width:100%;text-align:center;font-size:16px;\"><i>Processing...</i></div>");
    animateSend('#i_id',false);
    hideMiniProcessPay();
   
    $.post($('#main_url').val()+'&sm=16',{sndid : '',sid : $(btn).attr('id') },function(data){
        disableAnim=true;
        var ob=jParser(data);
        $('#h_id').html('<div id=\"mini_paywindow2\" style=\"padding:10px 0px;\">'+ob.vals+'</div>');
        $('#mini_paywindow2').fadeIn('fast',function(){$('#panel_left').animate({scrollTop: $('#h_id').offset().top+"px"}); } );
        $('#clsp').click(function(){loadAvails()});
        patchAvails();
    });
    
}
function loadAvails(){
    $('#h_id').html("<div id=\"i_id\" style=\"width:100%;text-align:center;font-size:16px;\"><i>Processing...</i></div>");
    disableAnim=false;
    animateSend('#i_id',false);
    
    $.post($('#main_url').val()+"&sm=17",{},function(data){
        
        var ob=jParser(data);
        disableAnim=true;
        $('#h_id').html(ob.vals);
        $('.form_button_hire').click(function(){ hireSenderId(this); });
        $('#panel_left').animate({scrollTop: $('#h_id').position().top+"px"});
       
    });
    
}
function patchAvails(){
    
    $('#tcode2').css('text-transform','uppercase');
    
    $('#p_pay2').click(function(){
      
      if($('#tcode2').val().trim()==""){
        
        messageBox('Warning',true,"Please enter transaction code!",null,null,false);
        
        return;
        
      }
      
      disableAnim=false; 
      animateSend('#p_pay2',false);
        
      $('#p_pay2').val("Processing...");
      $('#progress_div2').html("");
      
      $('#panel_left').animate({scrollTop : $('#mini_paywindow2').offset().top},'fast');
        
       $.post($('#main_url').val()+"&sm=18",{trans_code: $('#tcode2').val(),sid : $('#ssnd_id').val()},function(data){
        
       //alert(data);
        
       var ob=jParser(data);
        
       if(ob.status=="Success"){
           
           disableAnim=true;
            
            $('#progress_div2').html(ob.vals);
            
        }
        if(ob.status=="Failed"){
            
            disableAnim=true;
            
            $('#progress_div2').html(ob.vals);
            
        }
        
        $('#p_pay2').val("Process Payment");
        
       });
        
    });
    
}
function deleteMessageTemplate(evsource){
   
   messageBox("Processing",false,"Please wait.Processing...",null,null,null,true);
   
   disableAnim=false;
   animateSend('.send_status_content_processing',true);
   
   $.post($('#main_url').val()+"&sm=33",{templateID : $(evsource).attr('id')},function(data){
    
     disableAnim=true;
     
     var ob=jParser(data);
     
     if(ob.status=="Success"){
         
         $("#temp_listcont").html(ob.vals);
         messageBox("Success",false,"Template deleted successfully");
         patchTempDelete();
            
     }else{
        messageBox("Failed",false,ob.other);
     }
     
    
   });
   
   
   //$(evsource).attr("id")
}
function systemSendMessages(){
    $.post($("#main_url").val()+"&sm=34",{},function(data){
        alert(data);
    });
}