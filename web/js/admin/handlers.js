function fileDialogStart() {
    var stats = swfu1.getStats();
    stats.successful_uploads += initImageCount;
    stats.successful_uploads -= removeCount;
    swfu1.setStats(stats);
    initImageCount = 0;  // 娓�0锛屼綘鎳傚緱
    removeCount = 0; // 娓�0


}
function fileDialogInit() {
    var stats = swfu1.getStats();
    stats.successful_uploads += initImageCount;
    stats.successful_uploads -= removeCount;
    swfu1.setStats(stats);
    initImageCount = 0;  // 娓�0锛屼綘鎳傚緱
    removeCount = 0; // 娓�0
}

function fileQueueError(file, errorCode, message) {
    try {
        var imageName = "error.gif";
        var errorName = "";
        if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
            errorName = "涓€娆℃€т笂浼犱簡澶鐨勬枃浠�";
        }
        if (errorName !== "") {
            alert(errorName);
            return;
        }

        switch (errorCode) {
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                imageName = "zerobyte.gif";
                break;
            case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                imageName = "toobig.gif";
                break;
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
            case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
            default:
                alert(message);
                break;
        }

        addImage(base_url+"/"+"images/swfupload/" + imageName,this);

    } catch (ex) {
        this.debug(ex);
    }

}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
    try {
        if (numFilesQueued > 0) {
            this.startUpload();
        }
    } catch (ex) {
        this.debug(ex);
    }
}

function uploadProgress(file, bytesLoaded) {

    try {
        var percent = Math.ceil((bytesLoaded / file.size) * 100);

        var progress = new FileProgress(file,  this);
        progress.setProgress(percent);
        if (percent === 100) {
            progress.setStatus("鏂囦欢淇濆瓨涓�......");
            progress.toggleCancel(false, this);
        } else {
            progress.setStatus("姝ｅ湪涓婁紶......");
            progress.toggleCancel(true, this);
        }
    } catch (ex) {
        this.debug(ex);
    }
}

function uploadSuccess(file, serverData) {
    //alert(serverData);
    try {
        var progress = new FileProgress(file,  this);
        if (serverData.substring(0, 3) === "JS:") {
            eval(serverData.substring(3));
            progress.setStatus("鏂囦欢淇濆瓨鎴愬姛锛�");
            progress.toggleCancel(false);
        } else {
            addImage(base_url+"/"+"images/swfupload/error.gif",this);
            progress.setStatus("鍑洪敊浜�.");
            progress.toggleCancel(false);
        }
    } catch (ex) {
        this.debug(ex);
    }
}

function uploadComplete(file) {
    try {
        /*  I want the next upload to continue automatically so I'll call startUpload here */
        if (this.getStats().files_queued > 0) {
            this.startUpload();
        } else {
            var progress = new FileProgress(file,  this);
            progress.setComplete();
            progress.setStatus("鏂囦欢涓婁紶鎴愬姛锛�");
            progress.toggleCancel(false);
        }
    } catch (ex) {
        this.debug(ex);
    }
}

function uploadError(file, errorCode, message) {
    var imageName =  "error.gif";
    var progress;
    try {
        switch (errorCode) {
            case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
                try {
                    progress = new FileProgress(file,  this);
                    progress.setCancelled();
                    progress.setStatus("鍙栨秷");
                    progress.toggleCancel(false);
                }
                catch (ex1) {
                    this.debug(ex1);
                }
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
                try {
                    progress = new FileProgress(file,  this);
                    progress.setCancelled();
                    progress.setStatus("鍋滄");
                    progress.toggleCancel(true);
                }
                catch (ex2) {
                    this.debug(ex2);
                }
            case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                imageName = "uploadlimit.gif";
                break;
            default:
                alert(message);
                break;
        }

        addImage(base_url+"/"+"images/swfupload/" + imageName,this);

    } catch (ex3) {
        this.debug(ex3);
    }

}


function addImage(src,obj) {
    var newImg = document.createElement("img");
    newImg.style.margin = "5px";
    var parentNode = document.getElementById(obj.customSettings.thumbnails);
    if(obj.customSettings.thread_id == 1){
        while (parentNode.firstChild) {
            var oldNode = parentNode.removeChild(parentNode.firstChild);
            oldNode = null;
        }
    }
    parentNode.appendChild(newImg);
    if (newImg.filters) {
        try {
            newImg.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 0;
        } catch (e) {
            // If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
            newImg.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + 0 + ')';
        }
    } else {
        newImg.style.opacity = 0;
    }

    newImg.onload = function () {
        fadeIn(newImg, 0);
    };
    newImg.src = src;
}


function fadeIn(element, opacity) {
    var reduceOpacityBy = 5;
    var rate = 30;	// 15 fps


    if (opacity < 100) {
        opacity += reduceOpacityBy;
        if (opacity > 100) {
            opacity = 100;
        }

        if (element.filters) {
            try {
                element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
            } catch (e) {
                // If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
                element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
            }
        } else {
            element.style.opacity = opacity / 100;
        }
    }

    if (opacity < 100) {
        setTimeout(function () {
            fadeIn(element, opacity);
        }, rate);
    }
}



/* ******************************************
 *	FileProgress Object
 *	Control object for displaying file info
 * ****************************************** */

function FileProgress(file, obj) {

    this.fileProgressID = "divFileProgress"+obj.customSettings.thread_id;

    this.fileProgressWrapper = document.getElementById(this.fileProgressID);
    if (!this.fileProgressWrapper) {
        this.fileProgressWrapper = document.createElement("div");
        this.fileProgressWrapper.className = "progressWrapper";
        this.fileProgressWrapper.id = this.fileProgressID;

        this.fileProgressElement = document.createElement("div");
        this.fileProgressElement.className = "progressContainer";

        var progressCancel = document.createElement("a");
        progressCancel.className = "progressCancel";
        progressCancel.href = "#";
        progressCancel.style.visibility = "hidden";
        progressCancel.appendChild(document.createTextNode(" "));

        var progressText = document.createElement("div");
        progressText.className = "progressName";
        progressText.appendChild(document.createTextNode(file.name));

        var progressBar = document.createElement("div");
        progressBar.className = "progressBarInProgress";

        var progressStatus = document.createElement("div");
        progressStatus.className = "progressBarStatus";
        progressStatus.innerHTML = "&nbsp;";

        this.fileProgressElement.appendChild(progressCancel);
        this.fileProgressElement.appendChild(progressText);
        this.fileProgressElement.appendChild(progressStatus);
        this.fileProgressElement.appendChild(progressBar);

        this.fileProgressWrapper.appendChild(this.fileProgressElement);

        document.getElementById(obj.customSettings.upload_target).appendChild(this.fileProgressWrapper);
        fadeIn(this.fileProgressWrapper, 0);

    } else {
        this.fileProgressElement = this.fileProgressWrapper.firstChild;
        this.fileProgressElement.childNodes[1].firstChild.nodeValue = file.name;
    }

    this.height = this.fileProgressWrapper.offsetHeight;

}
FileProgress.prototype.setProgress = function (percentage) {
    this.fileProgressElement.className = "progressContainer green";
    this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
    this.fileProgressElement.childNodes[3].style.width = percentage + "%";
};
FileProgress.prototype.setComplete = function () {
    this.fileProgressElement.className = "progressContainer blue";
    this.fileProgressElement.childNodes[3].className = "progressBarComplete";
    this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setError = function () {
    this.fileProgressElement.className = "progressContainer red";
    this.fileProgressElement.childNodes[3].className = "progressBarError";
    this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setCancelled = function () {
    this.fileProgressElement.className = "progressContainer";
    this.fileProgressElement.childNodes[3].className = "progressBarError";
    this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setStatus = function (status) {
    this.fileProgressElement.childNodes[2].innerHTML = status;
};

FileProgress.prototype.toggleCancel = function (show, swfuploadInstance) {
    this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
    if (swfuploadInstance) {
        var fileID = this.fileProgressID;
        this.fileProgressElement.childNodes[0].onclick = function () {
            swfuploadInstance.cancelUpload(fileID);
            return false;
        };
    }
};

/*鏂板鍔犵殑鍒犻櫎鍔熻兘*/
function del_image(obj,name,url){
    obj.parentNode.removeChild(obj);
    var stats = swfu1.getStats();
    stats.successful_uploads--;
    AjaxSubmit("/swfupload/delfile","filename="+name)
    swfu1.setStats(stats);
    var status = document.getElementById("id_status");
    if(parseInt(swfu1.settings['file_upload_limit']-stats.successful_uploads) <= 0)
    {
        status.innerHTML = " 鎮ㄥ凡缁忎笂浼犱簡 <span class='bold red'>" + stats.successful_uploads + "</span>寮犲浘鐗囷紝杩橀渶涓婁紶璇峰垹闄ゅ凡涓婁紶鍥剧墖";
    }
    else
    {
        status.innerHTML = "鎮ㄥ凡缁忎笂浼犱簡 <span class='bold red'>" + stats.successful_uploads + "</span> 寮犲浘鐗囷紝杩樺彲浠ュ啀涓婁紶 <span class='bold red'>"+ parseInt(swfu1.settings['file_upload_limit']-stats.successful_uploads) +"</span>寮狅紝 姣忓紶涓嶈秴杩�10MB銆�";
    }
}

function swfupload(name,src_s,src_b) {

    var url = "/upload/"+name;

    var fileProgressElement = document.createElement("div");
    fileProgressElement.className = "imgbox";

    var fileItemBorder = document.createElement("div");
    fileItemBorder.className = "item_border";

    var fileItem = document.createElement("div");
    fileItem.className = "item";

    var fileImage = document.createElement("img");
    fileImage.style.height = "60px";
    fileImage.style.width = "80px";
    fileImage.src = url;


    var progressBar = document.createElement("input");
    progressBar.name = "image[]";
    progressBar.type = "hidden";
    progressBar.value = name;

    var newA = document.createElement("a");
    newA.className = "delete";
    newA.title = "鍒犻櫎";
    newA.href = "javascript:void(0);";
    newA.onclick=function (){
        del_image(fileProgressElement,name,url);
    };

    fileProgressElement.appendChild(fileItemBorder);
    fileItemBorder.appendChild(fileItem);
    fileItem.appendChild(fileImage);
    fileProgressElement.appendChild(newA);
    newA.appendChild(progressBar);
    document.getElementById("uploadimages").appendChild(fileProgressElement);

    var status = document.getElementById("id_status");
    var stats = swfu1.getStats();
    swfu1.setStats(stats);
    if(parseInt(swfu1.settings['file_upload_limit']-stats.successful_uploads) <= 0)
    {
        status.innerHTML = " 鎮ㄥ凡缁忎笂浼犱簡 <span class='bold red'>" + stats.successful_uploads + "</span>寮犲浘鐗囷紝杩橀渶涓婁紶璇峰垹闄ゅ凡涓婁紶鍥剧墖";
    }
    else
    {
        status.innerHTML = "鎮ㄥ凡缁忎笂浼犱簡 <span class='bold red'>" + stats.successful_uploads + "</span> 寮犲浘鐗囷紝杩樺彲浠ュ啀涓婁紶 <span class='bold red'>"+ parseInt(swfu1.settings['file_upload_limit']-stats.successful_uploads) +"</span>寮狅紝 姣忓紶涓嶈秴杩�10MB銆�";
    }
}

function CreateXMLHttpRequest()
{
    var xmlHttpRequest = null;//杩欓噷鏄ぇ瀹堕兘甯哥敤鐨処E锛宖irefox涓彇寰梄MLHttpRequest瀵硅薄鐨勬柟娉�
    try
    {
        xmlHttpRequest = new XMLHttpRequest();
    }
    catch(e)
    {
        try
        {
            xmlHttpRequest=new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch(e)
        {
            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    return xmlHttpRequest;
}

function AjaxSubmit(url,data,changeFunction,fail)//url鎸囧畾璺宠浆椤�,data鏄post鐨勬暟鎹€俢hangeFunction绫讳技浜庡嚱鏁版寚閽�
{
    var xmlHttpResquest = CreateXMLHttpRequest();

    url=url+"?"+data;
    xmlHttpResquest.open("post",url,true);
    xmlHttpResquest.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=utf-8"); //POST鎻愪氦鏃讹紝蹇呴』.
    xmlHttpResquest.send(null);

    xmlHttpResquest.onreadystatechange = function()
    {
        if (xmlHttpResquest.readyState == 4)
        {
            try
            {
                if(xmlHttpResquest.status == 200)
                {
                    //changeFunction(xmlHttpResquest.responseText);//杩欓噷鍙互璋冪敤鎯宠鐨勫嚱鏁�
                    //alert(xmlHttpResquest.responseText);
                }
                else
                {
                    alert("鍒犻櫎澶辫触");
                }
            }
            catch(e)
            {
                alert("鍒犻櫎澶辫触");
            }
        }
    }
}