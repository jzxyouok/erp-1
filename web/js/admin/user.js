/**
 * Created by sandom on 14/03/16.
 */
$(function(){
    dataSource = {};//闭包函數中不用var申明变量,代表全局变量
    dataSource.getOrganizTree = function(id){
        $.post('/common/ajax/getorganiz',{typeId:id}, function(data){
            window.top.art.dialog({
                id:'getorganiz',
                content: data,
                width:'400px',
                title: '组织机构选择',
                fixed: true,
                lock:true,
                drag: false,
                resize: false,
                opacity:0,
                background:'#fff',
            });
        });
    }
});