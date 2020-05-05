var dataContainer=[];
var idConatainer=[];

var dataObj=function dataManupilator(id,data){ "use strict";
	
	return {
		check:function(id){	
			for(let i=0;i<idConatainer.length;i++){
				if(idConatainer[i]==id){return 1;}}
		},update:function(id){ 
			if(this.check(id)===1){
				
			}
			else{this.insert(id);}
		},insert:function(id){
		  let obj={id:id,cont:data,isActive:1};
			dataContainer.push(obj);	
		}
	};
};
var dataFunc=function(id){"use strict";
	
};