<?php
/**
 * Created by PhpStorm.
 * User: smile
 * Date: 16-9-20
 * Time: 下午7:04
 */

namespace app\components;


use yii\base\Component;
use yii\helpers\Url;
class Tools extends Component
{
    /**
     * @param $menuList
     * @param int $parentId
     * @return array
     * 获取菜单树形结构
     */
    public static function getMenuTree($menuList,$parentId = 0)
    {
        $tree = [];
        if(!empty($menuList)){
            foreach($menuList as $key => $menu){
                if($menu['parentId'] == $parentId){
                    $tree[$menu['id']] = $menu;
                    $tree[$menu['id']]['child'] = self::getMenuTree($menuList, $menu['id']);
                }
            }
        }
        return $tree;
    }

    /**
     * 通过模块,控制器和方法来创建url
     * @param string $m 模块
     * @param string $a 控制器
     * @param string $c 方法
     * @return string
     */
    public static function createUrl($m = '', $a = '', $c = ''){
        $url = '/';
        $m && $url .= $m.'/';
        $a && $url .= $a.'/';
        $c && $url .= $c;
        if($url != '/')
            return Url::toRoute($url);
    }

    /**
     * 字符串加密、解密函数
     * @param	string	$txt		字符串
     * @param	string	$operation	ENCODE为加密，DECODE为解密，可选参数，默认为ENCODE，
     * @param	string	$key		密钥：数字、字母、下划线
     * @param	string	$expiry		过期时间
     * @return	string
     */
    static function sysAuth($string, $operation = 'ENCODE', $key = '', $expiry = 0) {
        $ckey_length = 4;
        $key = md5($key != '' ? $key : DEFAULT_KEY);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(strtr(substr($string, $ckey_length), '-_', '+/')) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . rtrim(strtr(base64_encode($result), '+/', '-_'), '=');
        }
    }

    /**
     * 获取菜单深度
     * @param $id
     * @param $array
     * @param $i
     */
    public static function get_level($id,$array=array(),$i=0) {
        foreach($array as $n=>$value){
            if($value['id'] == $id)
            {
                if($value['parentid']== '0') return $i;
                $i++;
                return Tools::get_level($value['parentid'],$array,$i);
            }
        }
    }
}