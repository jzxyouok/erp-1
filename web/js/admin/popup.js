function view(id, name, url, width, height) {
    window.top.art.dialog({title:name, id:'edit', iframe:url ,width:width+'px',height:height+'px'},function(){window.top.art.dialog({id:'edit'}).close()});
}
function edit(id, name, url, width, height) {
    window.top.art.dialog({title:name, id:'edit', iframe:url ,width:width+'px',height:height+'px'},function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;},function(){window.top.art.dialog({id:'edit'}).close()});
}
//成功或失败弹窗
function alertpop(msg, width, height){
    window.top.art.dialog({content: msg, lock: true, width: width, height: height}, function () {this.close();})
}