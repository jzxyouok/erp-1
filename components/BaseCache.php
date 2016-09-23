<?php

namespace app\components;
use yii;
/**
 * 缓存工具
 * @author liangpingzheng
 * @Date 2014-1126 10:33
 */
class BaseCache{

    /**
     * 取缓存
     * @param $key
     */
    public static function get( $key ) {

        return Yii::$app->cache->get($key);
    }

    /**
     * 设置缓存
     */
    public static function set( $key, $val, $expire = 0) {

        return Yii::$app->cache->set($key, $val, $expire);
    }

    public static function delete($key){
        return Yii::$app->cache->delete($key);
    }

    /**
     * 文件取缓存
     * @param $key
     */
    public static function fget( $key ) {

        return Yii::$app->fcache->get($key);
    }

    /**
     * 设置缓存
     */
    public static function fset( $key, $val, $expire = 0) {
        if($expire == 0){
            return Yii::$app->fcache->set($key, $val);
        }else{
            return Yii::$app->fcache->set($key, $val, $expire);
        }

    }

    public static function fdelete($key){

        return Yii::$app->fcache->delete($key);
    }

}