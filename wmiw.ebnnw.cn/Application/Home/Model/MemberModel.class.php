<?php
namespace Home\Model;
use Think\Model;
class MemberModel extends Model {
    
    public function reset_pwd($uid){
        
    }
    public function unfreeze($uid,$gid,$tp){
        // 返还冻结保证金和信用额度
        $gfeez = M('Goods_user')->where(array('g-u'=>'p-u','uid'=>$uid,'gid'=>$gid))->find();
    }

    // 屏蔽卖家或买家列表
    public function black($uid,$selbuy){
        $where = array('uid'=>$uid,'selbuy'=>$selbuy);
        $blacklist = M('blacklist');
        $member = M('member');
        $count = $blacklist->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $page = $pConf['show'];
        $list = $blacklist->limit($pConf['first'].','.$pConf['list'])->where($where)->select();

        foreach ($list as $lk => $lv) {
            $list[$lk]['xinfo'] = $member->where(array('uid'=>$lv['xid']))->field('uid,account,nickname,intr,organization,intro,score,scorebuy')->find();
            if ($lv['selbuy']=='sel') {
                $list[$lk]['xinfo']['leval']=getlevel($list[$lk]['xinfo']['score']);
            }else{
                $list[$lk]['xinfo']['leval']=getlevel($list[$lk]['xinfo']['score'],1);
            }
            
        }
        return array('page'=>$page,'list'=>$list);
    }



       
}

?>
