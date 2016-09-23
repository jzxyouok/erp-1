var pc_hash = 'AHXV';
function confirmurl(url, message) {
    url = url + '&pc_hash=' + pc_hash;
    artConfirm(message, function(){
        $.post(url,function(){
            window.location.reload();
        });
    });

}

function redirect(url) {
    location.href = url;
}

/**
 * 確信彈出框
 * @param content
 * @param okVal
 * @param cancelVal
 * @param isFresh
 * @constructor
 */
function artConfirm(content, callback, okVal, cancelVal, isFresh){
    if(!okVal) okVal='确认';
    if(!cancelVal) cancelVal='取消';
    window.top.art.dialog({
        title: '友情提示',
        content: content ? content : '确定操作吗' + '？',
        ok: function(){
            this.close();
            callback && callback();
            return true;
        },
        okVal:okVal,
        cancelVal: cancelVal,
        cancel: function(){
            if(isFresh) window.location.reload();
            return true;
        },
        width:'282px',
        fixed: true,
        lock:true,
        drag: false,
        resize: false,
        opacity:0,
        background:'#fff'
    });
}

/**
 * 統一頁面中的彈窗消息
 * @param content
 * @param title
 * @param okVal
 */
function artAlert(content, title ,okVal){
    if(!title) title = '提示';
    if(!okVal) okVal = '确定';
    window.top.art.dialog({
        okVal:okVal,
        content: content,
        ok: true,
        width:'282px',
        title: title,
        fixed: true,
        lock:true,
        drag: false,
        resize: false,
        opacity:0,
        background:'#fff'
    });
}

/**
 * 全选checkbox,注意：标识checkbox id固定为为check_box
 * @param string name 列表check名称,如 uid[]
 */
function selectall(name) {
    //alert($("#check_box").prop("checked"));
    if ($("#check_box").prop("checked") == false) {
        $("input[name='" + name + "']").each(function () {
            this.checked = false;
        });
    } else {
        $("input[name='" + name + "']").each(function () {
            this.checked = true;
        });
    }
}
function openwinx(url, name, w, h) {
    if (!w)
        w = screen.width - 4;
    if (!h)
        h = screen.height - 95;
    url = url + '&pc_hash=' + pc_hash;
    window.open(url, name, "top=100,left=400,width=" + w + ",height=" + h + ",toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no");
}
//弹出对话框
function omnipotent(id, linkurl, title, w, h,close_type) {
    var art = window.top.art;
    if (!w)
        w = 700;
    if (!h)
        h = 500;
    art.dialog({id: id, iframe: linkurl, title: title, width: w, height: h, lock: true},
    function () {
        if (close_type == 1) {
            art.dialog({id: id}).close()
        } else {
            var d = art.dialog({id: id}).data.iframe;
            var form = d.document.getElementById('dosubmit');
            form.click();
        }
        return false;
    },
            function () {
                art.dialog({id: id}).close();
            });
    void(0);
}

