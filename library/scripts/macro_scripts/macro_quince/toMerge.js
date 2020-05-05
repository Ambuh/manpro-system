/*  updated legal data 


const updatedSaveListData=btn=>{
  
}

const activateCustomNotifcations=_=>{
	if (!("Notification" in window)) {
    
		console.log("This browser does not support desktop notification");
     
	}else{
	  
		if($(".sUser").val() !==undefined){
			Notification.requestPermission();
			customNotifications().activate();
		}
			
    
	}
}
var isActive=0;var notiArray=[{title:"Requisition",message:"james Chege"},
	    {title:"Project",message:"ambrose Mwangi"}];var notiCount=0;
const customNotifications=_=>{
	
	const arry=[
		{name:"james Chege",isShown:0},
	    {name:"ambrose Mwangi",isShown:0}
	];
	
	return {
		hasPermission:function(){
			return true
		},requestPermission:function(){
			if(Notification.permission !== "granted"){
				Notification.requestPermission().then(function(permission){
					if(permission==='granted'){
						let nt=new Notification("Hi there");
						return true
					}else{
						return false
					}
				});
			}
		},
		activate:function(){
			const arr=this.response();
			const st=this.stop();
			
			if(this.hasPermission()){
				 this.alert();                
			}else{
				this.requestPermission();
			}
		},
		stop:function(){
		
	    },
		response:function(){
			
		},
		alert:function(){
			let arr=[...notiArray];
			
			
			
			setInterval(function(){
			
				if(arr.length> notiCount){
				
					customNotifications().notice(arr[notiCount]);
			    
					notiCount+=1;
			
				}
			},2000);
				
			
			
		},
		handler:function(){
			
		},notice(obj){
			
				
				
				//let nt=new Notification(obj.title,{body:obj.message});
				//chrome.notifications.create(obj.title, {message:obj.message}, function(){})
			    //let ny=new myObject("username");
			
		
		},updateNotification:function(id){
			
		}
	}
}

          Uber notications endend 
-----------------notifications add---------------
*/

function createScript(path){
	"use strict";
	
	$(".added").remove();
	
	if(path ==''){
		return;
	}
		
	
	let  head = document.getElementsByTagName('head')[0];
	
	let scripts=JSON.parse(path);
	 
	
	if(scripts.dir != undefined){
		
		let dir=scripts.dir;
		
		if(scripts.script !=undefined){
			for(let i=0;i<scripts.script.length;i++){
				var script = document.createElement('script');
				script.type = 'text/javascript';
				script.onload = function() {
                   testSchedule();
				};
				path.split("extensions")[1];

				script.setAttribute("class", "added");
				
				let src =`extensions${dir.split("extensions")[1]}scripts/${scripts.script[i].trim()}`;
				
				if(src.endsWith('.js')){
					
						script.src=(src.split("\\")).join("/");
						head.appendChild(script);
					    
				}
			}
		}
		if(scripts.styles !=undefined){
			for(let i=0;i<scripts.styles.length;i++){
				var style=document.createElement('link');
				
				style.type='text/css';
				style.rel  = 'stylesheet';
				style.media = 'all';
				let src =`extensions${dir.split("extensions")[1]}styles/${scripts.styles[i].trim()}`;
				
				//style.href=((`extensions${dir.split("extensions")[1]}styles/${scripts.style[i].trim()}`).split("\\")).join("/");
				
				style.setAttribute('class','added');
				
				if(src.endsWith(".css")){
					style.href=(src.split("\\")).join("/");
					head.appendChild(style);
				}
			}
		}
	}
	
    
}