var xmlhttp;
var current_active=-1;
/*$(document).ready(function(){
    $('.menu_row').click(function(){
        var cid= $(this).attr('id').split('_');
        //alert('#menu_child'+cid[cid.length-1]);
        $('#child_cont'+cid[cid.length-1]).toggle('fast','linear');
    })
    $('.menu_child_container').click(function(){
        $(this).toggle('fast');
    })
});*/
function changeTab(selected_tab,tabs,init_ac){
if(current_active==-1){
    document.getElementById("active_tab").id="tab"+init_ac;
    document.getElementById("active_tbc").id="tbc"+init_ac;
    current_active=selected_tab;
    document.getElementById("tab"+selected_tab).id="active_tab";
    document.getElementById("tbc"+selected_tab).id="active_tbc";
    }else{
    document.getElementById("active_tab").id="tab"+current_active;
    document.getElementById("active_tbc").id="tbc"+current_active;
    current_active=selected_tab;
    document.getElementById("tab"+selected_tab).id="active_tab";
    document.getElementById("tbc"+selected_tab).id="active_tbc";
    }
 //document.getElementById("tab1").id="active_tab";
}
function xmlhttpObject(){
    
}
function sendData(url,parameter,id,typ,callbackfunction,showprogress,target){
    if(window.ActiveXObject){
        xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
    }else{
        xmlhttp=new XMLHttpRequest();
    }
    parameter=parameter+getInputValue(id,typ);
    
    try{
	if(showprogress){
	document.getElementById(target).innerHTML="<i id=\"prog\" style=\"width:300px;\"><strong style=\"width:100%\">Processing...<strong></i>";
	}
	xmlhttpObject();
    xmlhttp.open("POST",url,true);
    xmlhttp.setRequestHeader("Content-Type","Application/x-www-form-urlencoded");
    xmlhttp.onreadystatechange=function(targets){
		if (xmlhttp.readyState==4){
		 document.getElementById(target).innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.send(parameter);
    	
    }catch(e){
        alert(e);
    }
}
function getInputValue(id,typ){

      switch(typ){
		case 0:
		    e=document.getElementById(id);
			return e.options[e.selectedIndex].value;
			
        case 1:
            return document.getElementById(id).value;
        
        case 2:
            return document.getElementById(id).innerHTML;
    }
    return "";
}
function hideThis(id,clr,dv){
	document.getElementById(id).style.display="none";
	divcolour(clr,dv);
}
function showThis(id,clr,dv){
	document.getElementById(id).style.display="block";
	document.getElementById(id).style.overflow="visible";
    divcolour(clr,dv);
}
function divcolour(color,div){
    //document.getElementById(div).style.background=color;
}
function checkUncheck(srcs,pref,counts){
	
for(i=0;i<counts;i++){

  document.getElementById(pref+"_"+i).checked =document.getElementById(srcs).checked;

}

}
function showHideDiv(divid,state){
  document.getElementById(divid).style.display=state;
}
function hideThese(div1,div2,formd,rels){
 document.getElementById(div1).style.display="none";
 document.getElementById(div2).style.display="none";
 if(rels==true){
 document.getElementById("iprop_form").submit();
 }
}
function showThese(div1,div2){
 document.getElementById(div1).style.display="block";
 document.getElementById(div2).style.display="block";
}
function showWithIframe(div1,div2,iframeid,url,formid){
 document.getElementById(div1).style.display="block";
 document.getElementById(div2).style.display="block";
 
 doc=document.getElementById(iframeid);
 
 if( doc.document ) {
            document.test.document.body.innerHTML = "<i>Loading...</i>"; //Chrome, IE
        }else {
            doc.contentDocument.body.innerHTML = "<i>Loading...</i>"; //FireFox
        }
 document.getElementById(iframeid).src=url;
 
}
function hideShow(div1,div2){
 document.getElementById(div1).style.display="none";
 document.getElementById(div2).style.display="block";
}
function resetChecker(id){
	
	document.getElementById(id).checked=false;
	
}
function vField(){
this.fieldId;
this.fieldType;
this.validationType;
this.faliledMessage;
this.successMessage;
this.errorValue;
}
function validateFields(vFarray){
status=true;
try{

for(i=0;i<vFarray.length;i++){
 
    if(vFarray[i].fieldType==0){//text field validation
	 status=validateTextField(vFarray[i]);
	  if(status==false){
	    return status;
	  }
	 }
	
	if(vFarray[i].fieldType==1){//selectBoxValidation
	 status=validateSelect(vFarray[i]);
	 if(!status){
	    return status;
	 }
   }

}
}catch(e){
	alert(e);
}
return status;
}
function validateTextField(vfarray){

  switch(vfarray.validationType){
  
   case 0://check of empty field
    if(document.getElementById(vfarray.fieldId).value==vfarray.errorValue){
		alert(vfarray.failedMessage);
		return false;
	}
   break;
  
   case 1://check if is valid numeric entry
   
   break;
   
   case 2://check for email
   document.getElementById(vfarray.fieldId).value;
   break;
   
   default:
   
   alert("Invalid Option");
  
  }

}
function matchFields(id1,id2,id3,pop_message){
	
	if(document.getElementById(id2).value==""){
	
	   return false;
	   
	}
	
	if(document.getElementById(id1).value==document.getElementById(id2).value){
	
	  document.getElementById(id3).innerHTML="Match";
	  
	  document.getElementById(id3).style.color="green";
	
	  return true;
	  
	}else{
	  
	  if(pop_message){
		 
		 alert("Missmatch");
		 
	  }
	  
	  document.getElementById(id3).innerHTML="Mismatch";
	  
	  document.getElementById(id3).style.color="#ff0000";
		
	  return false;
	}
}
function replaceSelectItems(selId1,selId2,data_array){
	sel1=document.getElementById(selId1);
	sel2=document.getElementById(selId2);
	
	//remove old values 
	try{
	for(i=sel2.length-1;i>-1;i--)
	 sel2.remove(i); 
		 
	 //add items
	for(i=0;i<data_array[sel1.value].length;i++){
	 //alert(data_array[sel1.value][i]);
	 var op=document.createElement("option");
	 var vals =data_array[sel1.value][i].split("_");
	 op.text=vals[0];
	 try{
	 op.value=vals[1];
	 }catch(e){}
	  sel2.add(op);
	}
	}catch(e){
	alert(e);
	}
}
function reposDiv(targetdiv,maxwidth,maxheight){
	try{
    
	//document.getElementById("m_image").style.width=((document.getElementById("m_image").offsetWidth)*1.5)+"px";
	
	var wd=document.getElementById("imageview_wrapper").offsetWidth;
	document.getElementById("imageview").style.marginLeft=(wd/2)-((document.getElementById("imageview").offsetWidth)/2)+"px";
	
	var wh=document.getElementById("imageview_wrapper").offsetHeight;
	document.getElementById("imageview").style.marginTop=((wh/2)-((document.getElementById("imageview").offsetHeight)/2))+"px";
	document.getElementById("arrow_l").style.top=((document.getElementById("imageview").offsetHeight-document.getElementById("arrow_l").offsetHeight)/2)+"px";

document.getElementById("arrow_r").style.top=((document.getElementById("imageview").offsetHeight-document.getElementById("arrow_r").offsetHeight)/2)+"px";

	
	}catch(e){
		alert(e);
	}
}
function swapImage(im1,im2){
	
	try{
	document.getElementById(im1).src=document.getElementById(im2).src;
   }catch(e){
     alert(e);
   }
}
