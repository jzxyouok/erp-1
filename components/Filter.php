<?php
/**
 * 对用户输入的数据进行过滤
 */
namespace app\components;
class Filter extends \yii\base\Component
{
    const FILTER_STRING = 'string';
    const FILTER_INT = 'int';
    const FILTER_FLOAT = 'float';

    /**
     * 过滤用户输入的数据
     * @param $val
     * @param string $type
     * @return float|int|mixed|string
     */
    public static function filterVal($val, $type = 'string'){
        $filter = '';
        $val = trim($val);
        switch($type){
            case self::FILTER_STRING:
                $filter = self::filterString($val);
                break;
            case self::FILTER_INT:
                $filter = intval($val);
                break;
            case self::FILTER_FLOAT:
                $filter = floatval($val);
                break;
            default:
                $filter = self::filterString($val);
                break;
        }

        return $filter;
    }

    /**
     * 过滤字符串
     * @param $val
     * @return mixed
     */
    public static function filterString($val){

         $val = filter_var($val, FILTER_SANITIZE_STRIPPED);//过滤器去除或编码不需要的字符

         return filter_var($val, FILTER_SANITIZE_MAGIC_QUOTES);//应用 addslashes()
    }
}