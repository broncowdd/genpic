result=first('#result');
function getRandomInt(min, max) {return Math.floor(Math.random() * (max - min + 1)) + min;}
function getRandomColor() {return getRandomInt(0, 255).toString(16)+getRandomInt(0, 255).toString(16)+getRandomInt(0, 255).toString(16);}
/*
on('click','.chars input',function(){
	if (all('.chars input:checked').length){
		addClass('.types .nochars','disabled');
		attr('.types .nochars input','disabled','true');
	}else{
		removeClass('.types .nochars','disabled');
		removeAttr('.types .nochars input','disabled');
	}
});
on('blur','.chars input[type=text]',function(){
	if (this.value){
		addClass('.types .nochars','disabled');
		attr('.types .nochars input','disabled','true');
	}else{
		removeClass('.types .nochars','disabled');
		removeAttr('.types .nochars input','disabled');
	}
});*/
on('click','#generate',function(){
	// make the get command
	command='?';
	command_thumb='?';
	checked='string=';

	each('input[type=text]',function(obj){
		name=obj.name;
		value=obj.value;
		if (value!=''&&value!=-1&&value!='-1'){
			command+=name+'='+value+'&';
			if (name=='w'||name=='h'){
				command_thumb+=name+'=512&';
			}else if(name=="string_chars"){
				checked+=encodeURI(value);
			}else{
				command_thumb+=name+'='+value+'&';
			}
		}else{

			// generate random parameters
			switch(name) {
			    case 'w':
			        command+=name+'=512&';
			        break;
			    case 'h':
			        command+=name+'=512&';
			        break;			    
			    case 'min_size':
			        command+=name+'='+getRandomInt(3,10)+'&';
			        break;
			    case 'max_size':
			        command+=name+'='+getRandomInt(10,50)+'&';
			        break;
			    case 'max_size':
			        command+=name+'='+getRandomInt(10,50)+'&';
			    case 'back':
			        command+=name+'='+getRandomColor()+'&';
			        break;
			    case 'c1':
			        command+=name+'='+getRandomColor()+'&';
			        break;
			    case 'c2':
			        command+=name+'='+getRandomColor()+'&';
			        break;
			} 
		}
	});		

	each('input[type=range]',function(obj){
		name=obj.name;
		value=obj.value;
		if (value){command+=name+"="+value+"&";}
	});		


	name='type';
	typecheck=first('input[name="type"]:checked');
	if (typecheck){
		value=typecheck.value;
		if (value!=-1){
			command+=name+'='+value+'&';
			command_thumb+=name+'='+value+'&';
		}else{			    
			command+=name+'='+getRandomInt(0,10)+'&';
		}
	}else{
		command+=name+'='+getRandomInt(0,10)+'&';
	}
	
	each('.chars input[type=checkbox]:checked',function(obj){		
		value=obj.value;
		checked+=value;
	});
	
	each('.filters input[type=checkbox]:checked',function(obj){		
		value=obj.value;
		name=obj.name;
		command+=name+"="+value+"&";
	});

	checked+='&shape=';
	each('.formes input:checked',function(obj){		
		value=obj.value;
		checked+=value;
	});
	command+=checked;
	command=url+command;
	command_thumb=command+'&w=512&h=512';
	result.src=command_thumb+'refresh='+(1+Math.random())+'&dontsave';
	first('#link').innerHTML='<a class="icon-eye" href="'+command+'"> Afficher l\'image </a>';
});