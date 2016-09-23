/*根据项目id获取模块*/
function GetMenu(val,myobj){
	document.getElementById(myobj).disabled ="";
	var Url=document.getElementById("getmenuurl").value;
	$.ajax({
        'type':'POST',
        'url':Url,
        'dataType':"json",
        'data':{siteid:val},
        'success':function(data){
            if(data.errorno == 0){
            	var obj=document.getElementById(myobj);
            	obj.innerHTML='<option value="0">根菜单</option>'+data.msg;
            }else{               
                return false;
            }
        }
    });		
}