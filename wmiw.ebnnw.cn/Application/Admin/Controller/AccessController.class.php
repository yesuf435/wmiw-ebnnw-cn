<?php
namespace Admin\Controller;
use Think\Controller;
class AccessController extends CommonController {
    /**
      +----------------------------------------------------------
     * 管理员列表
      +----------------------------------------------------------
     */
    public function index() {
        $this->assign("list", D("Access")->adminList());
        $this->display();
    }

    public function nodeList() {
        $this->assign("list", D("Access")->nodeList());
        $this->display('nodeList');
    }

    public function roleList() {
        $this->assign("list", D("Access")->roleList());
        $this->display('roleList');
    }

    public function addRole() {
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Access")->addRole());
        } else {
            $this->assign("info", $this->getRole());
            $this->display("editRole");
        }
    }

    public function editRole() {
        if (IS_POST) {
            $this->checkToken();
        
            echojson(D("Access")->editRole());
        } else {
            $M = M("Role");
            $info = $M->where("id=" . (int) $_GET['id'])->find();
            if (empty($info['id'])) {
                $this->error("不存在该角色", U('Access/roleList'));
            }
            $this->assign("info", $this->getRole($info));
            $this->display('editRole');
        }
    }

    public function opNodeStatus() {
    
        echojson(D("Access")->opStatus("Node"));
    }

    public function opRoleStatus() {
    
        echojson(D("Access")->opStatus("Role"));
    }

    public function opSort() {
        $this->checkToken();
        $M = M("Node");
        $datas['id'] = (int) I("post.id");
        $datas['sort'] = (int) I("post.sort");
        if ($M->save($datas)) {
            echojson(array('status' => 1, 'info' => "处理成功"));
        } else {
            echojson(array('status' => 0, 'info' => "处理失败"));
        }
    }

    public function editNode() {
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Access")->editNode());
        } else {
            $M = M("Node");
            $info = $M->where("id=" . (int) $_GET['id'])->find();
            if (empty($info['id'])) {
                $this->error("不存在该节点", U('Access/nodeList'));
            }
            $this->assign("info", $this->getPid($info));
            $this->display();
        }
    }
    public function order_node(){
        $getInfo = I('post.');
        $M = M("Node");
        $where=array('id'=>$getInfo['odid']);
        if($getInfo['odType'] == 'rising'){
            if($M->where($where)->setInc('sort')){
                echojson(array('status'=>'1','msg'=>'排序写入数据库成功'));
            }
        }elseif($getInfo['odType'] == 'drop'){
            if($M->where($where)->setDec('sort')){
                echojson(array('status'=>'1','msg'=>'排序写入数据库成功'));
            }
        }
    }

    public function addNode() {
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Access")->addNode());
        } else {
            $this->assign("info", $this->getPid(array('level' => 1)));
            $this->display("editNode");
        }
    }

    /**
      +----------------------------------------------------------
     * 添加管理员
      +----------------------------------------------------------
     */
    public function addAdmin() {
        if (IS_POST) {
            // 验证密码规则
            $data = I('post.data');
            $valid = valid_pass($data['pwd']);
            if (!$valid['status']) {echojson($valid); exit();}
            $this->checkToken();
            echojson(D("Access")->addAdmin());
        } else {
            $info = $this->getRoleListOption(array('role_id' => 0));
            $this->info=$info;
            $this->display('addAdmin');
        }
    }

    public function changeRole() {
    
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Access")->changeRole());
        } else {
            $M = M("Node");
            $info = M("Role")->where("id=" . (int) $_GET['id'])->find();
            if (empty($info['id'])) {
                $this->error("不存在该用户组", U('Access/roleList'));
            }
            $access = M("Access")->field("CONCAT(`node_id`,':',`level`,':',`pid`) as val")->where("`role_id`=" . $info['id'])->select();
            $info['access'] = count($access) > 0 ? json_encode($access) : json_encode(array());
            $this->assign("info", $info);
            $datas = $M->where("level=1")->order('sort desc')->select();
            foreach ($datas as $k => $v) {
                $map['level'] = 2;
                $map['pid'] = $v['id'];
                $datas[$k]['data'] = $M->where($map)->select();
                foreach ($datas[$k]['data'] as $k1 => $v1) {
                    $map['level'] = 3;
                    $map['pid'] = $v1['id'];
                    $datas[$k]['data'][$k1]['data'] = $M->where($map)->select();
                }
            }
            $this->assign("nodeList", $datas);
            $this->display('changeRole');
        }
    }

    /**
      +----------------------------------------------------------
     * 添加管理员
      +----------------------------------------------------------
     */
    public function editAdmin() {
        if (IS_POST) {
            // 验证密码规则
            $data = I('post.data');
            $valid = valid_pass($data['pwd']);
            if (!$valid['status']) {echojson($valid); exit();}
            
            $this->checkToken();
            echojson(D("Access")->editAdmin());
        } else {
            $M = M("Admin");
            $aid = (int) $_GET['aid'];
            $pre = C("DB_PREFIX");
            $info = $M->where("`aid`=" . $aid)->join($pre . "role_user ON " . $pre . "admin.aid = " . $pre . "role_user.user_id")->find();
            if (empty($info['aid'])) {
                $this->error("不存在该管理员ID", U('Access/index'));
            }
            if ($info['email'] == C('ADMIN_AUTH_KEY')) {
                $this->error("超级管理员信息不允许操作", U("Access/index"));
                exit;
            }
            $this->assign("info", $this->getRoleListOption($info));
            $this->display("addAdmin");
        }
    }

    private function getRole($info = array()) {
        $cat = new \Org\Util\Category();
        $list = $cat->getList(M('Role'),'id');               //获取分类结构
        foreach ($list as $k => $v) {
            $disabled = $v['id'] == $info['id'] ? ' disabled="disabled"' : "";
            $selected = $v['id'] == $info['pid'] ? ' selected="selected"' : "";
            $info['pidOption'].='<option value="' . $v['id'] . '"' . $selected . $disabled . '>' . $v['fullname'] . '</option>';
        }
        return $info;
    }

    private function getRoleListOption($info = array()) {
        $cat = new \Org\Util\Category();
        $list = $cat->getList(M('Role'),'id'); 
        $info['roleOption'] = "";
        foreach ($list as $v) {
            $disabled = $v['id'] == 1 ? ' disabled="disabled"' : "";
            $selected = $v['id'] == $info['role_id'] ? ' selected="selected"' : "";
            $info['roleOption'].='<option value="' . $v['id'] . '"' . $selected . $disabled . '>' . $v['fullname'] . '</option>';
        }
        return $info;
    }

    private function getPid($info) {
        $arr = array("请选择", "项目", "模块", "操作");
        for ($i = 1; $i < 4; $i++) {
            $selected = $info['level'] == $i ? " selected='selected'" : "";
            $info['levelOption'].='<option value="' . $i . '" ' . $selected . '>' . $arr[$i] . '</option>';
        }
        $level = $info['level'] - 1;
        $cat = new \Org\Util\Category();
        $list = $cat->getList(M('Node'),'id'); 
        $option = $level == 0 ? '<option value="0" level="-1">根节点</option>' : '<option value="0" disabled="disabled">根节点</option>';
        foreach ($list as $k => $v) {
            $disabled = $v['level'] == $level ? "" : ' disabled="disabled"';
            $selected = $v['id'] != $info['pid'] ? "" : ' selected="selected"';
            $option.='<option value="' . $v['id'] . '"' . $disabled . $selected . '  level="' . $v['level'] . '">' . $v['fullname'] . '</option>';
        }
        $info['pidOption'] = $option;
        return $info;
    }

}