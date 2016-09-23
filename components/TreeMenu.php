<?php
namespace app\components;
class TreeMenu {

    public $ret = '';
    /*
     * descript:生成数形结构图
     * date:2015-12-14 09:12
     * author:yuexl
     * 构造函数的参数格式
     * $data =  @param array 2维数组，例如：
     * array(
     *      1 => array('id'=>'1','parentid'=>0,'name'=>'一级栏目一'),
     *      2 => array('id'=>'2','parentid'=>0,'name'=>'一级栏目二'),
     *      3 => array('id'=>'3','parentid'=>1,'name'=>'二级栏目一'),
     *      4 => array('id'=>'4','parentid'=>1,'name'=>'二级栏目二'),
     *      5 => array('id'=>'5','parentid'=>2,'name'=>'二级栏目三'),
     *      6 => array('id'=>'6','parentid'=>3,'name'=>'三级栏目一'),
     *      7 => array('id'=>'7','parentid'=>3,'name'=>'三级栏目二')
     *      )
     * method param get_tree;
     *  * @param $myid 表示获得这个ID下的所有子级
     * @param $str 生成树形结构基本代码, 例如: "<option value=\$id \$select>\$spacer\$name</option>"
     * @param $sid 被选中的ID, 比如在做树形下拉框的时候需要用到
     * @param $adds
     * @param $str_group
     * @return unknown_type
     * */
    public static function getTree($data,$myid=0,$sid =0,$adds='',$str_group ='',$str = "<option value=\$id \$selected>\$spacer\$name</option>"){
        $r='';
        $tree = new Tree;
        if(empty($adds)){
            //$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
            $tree->icon = array('&nbsp;&nbsp;&nbsp; ','&nbsp;&nbsp;&nbsp; ','&nbsp;&nbsp;&nbsp; ');
        }else{
            $tree->icon = $adds;
        }
        
        $tree->nbsp = "&nbsp;&nbsp;&nbsp;";
        $tree->init($data);
        $r = $tree->get_tree($myid, $str, $sid, $adds = '', $str_group = '');
        return $r;
    }
    /**
     * 得到树型结构
     * @param int ID，表示获得这个ID下的所有子级
     * @param string 生成树型结构的基本代码，例如："<option value=\$id \$selected>\$spacer\$name</option>"
     * @param int 被选中的ID，比如在做树型下拉框的时候需要用到
     * @return string
     */
    public static function get_tree($myid, $str, $sid = 0, $adds = '', $str_group = ''){
        $number=1;
        $tree = new Tree;
        $child = $tree->get_child($myid);
        if(is_array($child)){
            $total = count($child);
            foreach($child as $id=>$value){
                $j=$k='';
                if($number==$total){
                    $j .= $tree->icon[2];
                }else{
                    $j .= $tree->icon[1];
                    $k = $adds ? $tree->icon[0] : '';
                }
                $spacer = $adds ? $adds.$j : '';
                $selected = $id==$sid ? 'selected' : '';
                @extract($value);
                //$parentid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
                //$this->ret .= $nstr;
                $nbsp = $tree->nbsp;
                self::get_tree($id, $str, $sid, $adds.$k.$nbsp,$str_group);
                $number++;
            }
        }
        return $tree->ret;
    }
}



?>