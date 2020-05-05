  var menu=0;
$(document).ready(function(){
	"use strict";
	var width=$(window).width();
   if(width <480){
	  // alert("is_smaller");
		menu_mouse();
	
	$(".am_upgrade").click(function(){
		revolt();
	});
	$(".menu_item").click(function(){
		menu_clicks();
	   });
	  $("#hem_control").click(function(){
		  var data=$(".rDiv").html();
	
		  popWindow(".thePop",display_data);
		 
	  });
	 
	}
	
});
function display_data(){
	"use strict";
	var data=$(".m_login").html();
	$('.thePop').html("<div id='tt_upgrade'><div class='respHeader' ><div class='respItem' id='login'>Login</div><div class='respItem' id='register'>Regsiter</div></div>"+data+"</div>");
	//alert(data);
	 $(" #register").click(function(){
		 $("#tt_upgrade").html("<div class='reg'>"+$(".rDiv").html()+"</div>"); 
		//var amos= $(".reg div").toArray();
		 //$(amos[5]).fadeOut("slow");
	  });
	return data;
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