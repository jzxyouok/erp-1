<?php

/**
 * 功能描述:信息提示
 */
namespace app\components\library;

class ShowMessage
{

    static function info($msg, $url_forward = 'goback', $ms = 2500, $dialog = '', $returnjs = '')
    {
        if ($url_forward == 'goback' || $url_forward == '') {
            $redirect = '<a href="javascript:history.back();" >[返回]</a>';
        } elseif ($url_forward == "close") {
            $redirect = '<input type="button" name="close" value="关闭" onClick="window.close();">';
        } elseif ($url_forward) {
            $redirect = '<a href="' . $url_forward . '">如果您的浏览器没有跳转，请点击这里</a><script language="javascript">setTimeout("redirect(\'' . $url_forward . '\');",' . $ms . ');</script>';
        }
        $returnjs_info = $diglogs = '';
        if ($dialog) {
            $diglogs = '<script style="text/javascript">window.top.right.location.reload();</script>';
            $diglogs .= '<script style="text/javascript">window.top.art.dialog({id:"' . $dialog . '"}).close();</script>';
        }
        
        if ($returnjs) {
            $returnjs_info = '<script style="text/javascript">' . $returnjs . '</script>';
        }
        
        $showmsg = '<!DOCTYPE html>
       <html>
       <head>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <meta http-equiv="X-UA-Compatible" content="IE=7" />
       <title></title>
       <style type="text/css">
       <!--
       *{ padding:0; margin:0; font-size:12px}
       a:link,a:visited{text-decoration:none;color:#0068a6}
       a:hover,a:active{color:#ff6600;text-decoration: underline}
       .showMsg{border: 1px solid #1e64c8; zoom:1; width:450px; height:172px;position:absolute;top:44%;left:50%;margin:-87px 0 0 -225px}
       .showMsg h5{background-image: url(/images/admin/msg_img/msg.png);background-repeat: no-repeat; color:#fff; padding-left:35px; height:25px; line-height:26px;*line-height:28px; overflow:hidden; font-size:14px; text-align:left}
       .showMsg .content{ padding:46px 12px 10px 45px; font-size:14px; height:64px; text-align:left}
       .showMsg .bottom{ background:#e4ecf7; margin: 0 1px 1px 1px;line-height:26px; *line-height:30px; height:26px; text-align:center}
       .showMsg .ok,.showMsg .guery{background: url(/images/admin/msg_img/msg_bg.png) no-repeat 0px -560px;}
       .showMsg .guery{background-position: left -460px;}
       -->
       </style>
       <script type="text/javaScript" src="/js/admin/jquery.min.js"></script>
       <script type="text/javaScript" src="/js/admin/admin_common.js"></script>
       </head>
       <body>
       <div class="showMsg" style="text-align:center">
           <h5>信息提示</h5>
           <div class="content guery" style="display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline;max-width:330px">' . $msg . '</div>
           <div class="bottom">
           ' . $redirect . $diglogs . $returnjs_info . '
           </div>
       </div>
       <script style="text/javascript">
           function close_dialog() {
               window.top.right.location.reload();window.top.art.dialog({id:"' . $dialog . '"}).close();
           }
       </script>
       </body>
       </html>';
        exit($showmsg);
    }

    static function show_msg($show = '', $url = '', $dialog = '', $flag = true)
    {
        $refresh = $diglogs = '';
        if ($dialog) {
            $diglogs = '<script style="text/javascript">window.top.right.location.reload();</script>';
            $diglogs .= '<script style="text/javascript">window.top.art.dialog({id:"' . $dialog . '"}).close();</script>';
        }
        if ($flag)
            $refresh = '<meta http-equiv="refresh" content="3;url=' . $url . '">';
        $msg = '<!DOCTYPE html>
				<html>
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				' . $refresh . '
				<title>信息提示</title>
				<style type="text/css">
				body,td,th{
					font-size: 12px;
				}
				body {
					margin-left: 0px;
					margin-top: 100px;
					margin-right: 0px;
					margin-bottom: 0px;
					line-height:200%;
					background-color:#EFEFEF;
				}
				a:link {font-size: 10pt;color: #000000;text-decoration: none;font-family: ""宋体"";}
				a:visited{font-size: 10pt;color: #000000;text-decoration: none;font-family: ""宋体"";}
				a:hover {color: red;font-family: ""宋体"";text-decoration: underline;}
				table{border:1px solid #D1DDAA;background-color:#FFF;}
				th{ background-color:#D1DDAA; font-size:14px;}
				td{padding:5px 10px 10px 10px;}
				</style>
				</head>
				<body>
				<table width="450" border="0" align="center" cellpadding="0" cellspacing="0">
				  <tr>
					<th height="34">提示信息</th>
				  </tr>
				  <tr align="center">
					<td height="131"><p>' . $show . '<br />
					  3秒后自动返回指定页面！<br />
					如果浏览器无法跳转，<a href="' . $url . '">请点击此处</a>。</p>' . $diglogs . '</td>
				  </tr>
				</table>
				</body>
				</html>
				';
        // echo $msg;
        exit($msg);
    }
    
    // end show_msg
    
    /**
     * 提示信息
     */
    static function msg($action = 'success', $content = '', $redirect = 'javascript:history.back(-1);', $timeout = 4)
    {
        switch ($action) {
            case 'success':
                $titler = '操作完成';
                $class = 'message_success';
                $images = 'message_success.png';
                break;
            case 'error':
                $titler = '操作未完成';
                $class = 'message_error';
                $images = 'message_error.png';
                break;
            case 'errorBack':
                $titler = '操作未完成';
                $class = 'message_error';
                $images = 'message_error.png';
                break;
            case 'redirect':
                header("Location:$redirect");
                break;
            case 'script':
                if (empty($redirect)) {
                    exit('<script language="javascript">alert("' . $content . '");window.history.back(-1)</script>');
                } else {
                    exit('<script language="javascript">alert("' . $content . '");window.location=" ' . $redirect . '   "</script>');
                }
                break;
        }
        
        // 信息头部
        $header = '<!DOCTYPE html">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>操作提示</title>
<style type="text/css">
body{font:12px/1.7 "\5b8b\4f53",Tahoma;}
html,body,div,p,a,h3{margin:0;padding:0;}
.tips_wrap{ background:#F7FBFE;border:1px solid #DEEDF6;width:780px;padding:50px;margin:50px auto 0;}
.tips_inner{zoom:1;}
.tips_inner:after{visibility:hidden;display:block;font-size:0;content:" ";clear:both;height:0;}
.tips_inner .tips_img{width:80px;float:left;}
.tips_info{float:left;line-height:35px;width:650px}
.tips_info h3{font-weight:bold;color:#1A90C1;font-size:16px;}
.tips_info p{font-size:14px;color:#999;}
.tips_info p.message_error{font-weight:bold;color:#F00;font-size:16px; line-height:22px}
.tips_info p.message_success{font-weight:bold;color:#1a90c1;font-size:16px; line-height:22px}
.tips_info p.return{font-size:12px}
.tips_info .time{color:#f00; font-size:14px; font-weight:bold}
.tips_info p a{color:#1A90C1;text-decoration:none;}
</style>
</head>

<body>';
        // 信息底部
        $footer = '</body></html>';
        
        $body = '<script type="text/javascript">
        function delayURL(url) {
        var delay = document.getElementById("time").innerHTML;
        //alert(delay);
        if(delay > 0){
        delay--;
        document.getElementById("time").innerHTML = delay;
    } else {
    window.location.href = url;
    }
    setTimeout("delayURL(\'" + url + "\')", 1000);
    }
    </script><div class="tips_wrap">
    <div class="tips_inner">
        <div class="tips_img">
            <img src="' . Yii::app()->baseUrl . '/images/admin/admin_img/' . $images . '"/>
        </div>
        <div class="tips_info">

            <p class="' . $class . '">' . $content . '</p>
            <p class="return">系统自动跳转在  <span class="time" id="time">' . $timeout . ' </span>  秒后，如果不想等待，<a href="' . $redirect . '">点击这里跳转</a></p>
        </div>
    </div>
</div><script type="text/javascript">
    delayURL("' . $redirect . '");
    </script>';
        
        exit($header . $body . $footer);
    }
}
