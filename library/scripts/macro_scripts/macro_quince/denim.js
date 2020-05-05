function refined(btn){
	"use strict";
	var port="80";
	
	var context;
	
	var context_menu;
	
	var params;
	
	var extras;
	if($(btn).is("[id]")){
		switch($(btn).attr("id").split("_")[0]){
			case "dCm":
				params={tls:1,id:$(btn).attr("id").split("_")[1]};
				
				extras=function(){
					let id=$(btn).attr("id").split("_")[1];
						$('.thePop').fadeOut("slow",function(){
							$("#wrapper").fadeOut();
							$($("#ew_"+id+" ").parent()).parent().fadeOut();
						});			
					};
				
				
				break;
		}
		$.post($('#main_url').val()+'&sq='+port,params,function(data){
			
			var ob=jParser(data);
			
			if(ob.Status==="Success"){
				
				$('.thePop').html(ob.Content);
				extras();
			}
		});
	}
}
function mainUtilities(){
	"use strict";
	$(".pj-info").click(function(){
		loadProject(this);
	});
	$(".quince_select option").click(function(){
		var curr=this;
		
		$(curr).parent().attr("id","pr_"+$(curr).val());
		
	});
}
if (document.addEventListener) { // IE >= 9; other browsers
        document.addEventListener('contextmenu', function(e) {
           
        }, false);
    } else { // IE < 9
        document.attachEvent('oncontextmenu', function(e) {
            alert("You've tried to open context menu");
            window.event.returnValue = false;
        });
    }

function loadProject(btn){
	"use strict";
	
	if($(btn).is("[id]")){
		$.post($('#main_url').val()+'&sq=81',{pid:$(btn).attr("id").split("_")[1]},function(data){
			
			var ob=jParser(data);
			
			if(ob.Status==="Success"){
				 $('#quince_inner_content').html(ob.Content);
                   $('.menu_loader').html(ob.TopMenu);
				   runAssist();mainUtilities();
				   loadInnerFunc();
                   loadAfterAnimate();  
				   blink('.ch_dw');
			}
	});
	}
	
}
