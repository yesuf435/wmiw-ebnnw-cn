<?php
namespace Admin\Model;
use Think\Model;
class WebinfoModel extends Model {

    public function set_site_info() {
        
    }
    /**
     * 首页导航
     * @return [type] [description]
     */
    public function navigation() {
        if (IS_POST) {
            $act = $_POST[act];
            $data = $_POST['data'];
            $data['name'] = addslashes($data['name']);
            $M = M("Navigation");
            if ($act == "add") { //添加导航
                unset($data[lid]);
                if ($M->where($data)->count() == 0) {
                    return ($M->add($data)) ? array('status' => 1, 'info' => '导航 ' . $data['name'] . ' 已经成功添加到系统中', 'url' => U('Webinfo/navigation', array('time' => time()))) : array('status' => 0, 'info' => '导航 ' . $data['name'] . ' 添加失败');
                } else {
                    return array('status' => 0, 'info' => '系统中已经存在导航' . $data['name']);
                }
            } else if ($act == "edit") { //修改导航
                if (empty($data['name'])) {
                    unset($data['name']);
                }
                if ($data['pid'] == $data['lid']) {
                    unset($data['pid']);
                }
                return ($M->save($data)) ? array('status' => 1, 'info' => '导航 ' . $data['name'] . ' 已经成功更新', 'url' => U('Webinfo/navigation', array('time' => time()))) : array('status' => 0, 'info' => '导航 ' . $data['name'] . ' 更新失败');
            } else if ($act == "del") { //删除导航
                unset($data['pid'], $data['name']);
                return ($M->where($data)->delete()) ? array('status' => 1, 'info' => '导航 ' . $data['name'] . ' 已经成功删除', 'url' => U('Webinfo/navigation', array('time' => time()))) : array('status' => 0, 'info' => '导航 ' . $data['name'] . ' 删除失败');
            }
        } else {
            $cat = new \Org\Util\Category();
            return $cat->getList(M('Navigation'),'lid',0,NULL,'sort desc');
        }
    }
}

?>
