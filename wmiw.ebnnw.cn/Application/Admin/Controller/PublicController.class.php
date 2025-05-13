<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;
class PublicController extends Controller {
    public $loginMarked;
    /**
      +----------------------------------------------------------
     * 初始化
      +----------------------------------------------------------
     */
    public function _initialize() {
        header('Content-Type:application/json; charset=utf-8');
        $this->loginMarked = md5(C("TOKEN.admin_marked"));
    }

    public function delcate() {
        $category = M('goods_category');
        $list = M('goods_category')->select();
        foreach ($list as $lk => $lv) {
            // pre($lv['pid']);
            $fd = $category->where(array('cid'=>$lv['pid']))->find();
            if(!$fd){
                if ($lv['pid']!=0) {
                    $category->where(array('cid'=>$lv['cid']))->delete();
                    pre($lv);
                }
            }
        }
        die;
    }



    // public function respwd() {
    //     $list = M('admin')->where(array('aid'=>1))->setField('pwd',encryptPwd(trim('Z@y911029Y&hf')));
    //     pre($list);
    //     die;
    // }



    // public function redata() {
    //     $list = M('member')->select();
    //     $member = M('member');
    //     foreach ($list as $k => $v) {
    //         # prov  city  district
    //         if ($v['prov']!=0) {
    //             $prov = M('region')->where(array('region_id'=>$v['prov']))->getField('region_name');
    //         }else{
    //             $prov = '';
    //         }
    //         $member->where(array('uid'=>$v['uid']))->setField('prov',$prov);
    //         if ($v['city']!=0) {
    //             $city = M('region')->where(array('region_id'=>$v['city']))->getField('region_name');
                
    //         }else{
    //             $city = '';
    //         }
    //         $member->where(array('uid'=>$v['uid']))->setField('city',$city);
    //         if ($v['district']!=0) {
    //             $district = M('region')->where(array('region_id'=>$v['district']))->getField('region_name');
                
    //         }else{
    //             $district = '';
    //         }
    //         $member->where(array('uid'=>$v['uid']))->setField('district',$district);
            
    //     }

    // }


    public function deltextuser() {
    	$uid = I('get.uid');
        M('member')->where(array('uid'=>$uid))->delete();
        M('member_weixin')->where(array('uid'=>$uid))->delete();
    }
    
    public function uppic() {
        $goods = M('goods');
        $where = array('id'=>array(array('egt',I('get.sd')),array('elt',I('get.ed'))));
        $list = $goods->where($where)->field('pictures')->select();
        $imgThumb = new \Think\Image(); 
        foreach ($list as $lk => $lv) {
            if ($lv['pictures']!='') {
                $picarr = explode('|',$lv['pictures']);
                foreach ($picarr as $pk => $pv) {
                    preg_match('/'.C('GOODS_PICPATH').'\/\d+?\//is', $pv, $gdPicPath);
                    $cutImgUrl = C('UPLOADS_PICPATH').$pv;

                    if (file_exists($cutImgUrl)) {
                        $picFixArr=explode(',', C('GOODS_PIC_PREFIX'));
                        $savename =str_replace($gdPicPath[0], '', $pv);
                        $imgThumb->open($cutImgUrl);
                        // 生成原图等比缩放图片
                        $thw = picSize(0,'width');
                        $thh = picSize(0,'height');
                        // 缩放后填充类型为IMAGE_THUMB_FILLED  居中裁剪类型为IMAGE_THUMB_CENTER
                        $imgThumb->thumb($thw,$thh,\Think\Image::IMAGE_THUMB_CENTER)->save($cutImgUrl);
                        foreach ($picFixArr as $pFixK => $pFixV) {
                            $imSizeW = picSize($pFixK,'width');
                            $imSizeH = picSize($pFixK,'height');
                            $aaa = $imgThumb->thumb($imSizeW,$imSizeH,\Think\Image::IMAGE_THUMB_CENTER)->save(C('UPLOADS_PICPATH').$gdPicPath[0] . $pFixV . $savename);
                        }
                    }
                }
            }
        }
    }

    public function newsup() {
        $goods = M('goods');
        $list = $goods->select();
        foreach ($list as $k => $v) {
            $content = str_replace("/amazeuiAuction/Uploads/","/meizi/Uploads/",$v['content']);
            // pre($content);

            $goods->where(array('id'=>$v['id']))->setField('content',$content);
        }
        // pre($list);
        die;
    }

    public function upbcount() {
        $special = M('special_auction');
        $auction = M('auction');
        $list = $special->select();
        foreach ($list as $k => $v) {
            $ct = $auction->where(array('sid'=>$v['sid']))->count();
            pre($ct);
            $special->where(array('sid'=>$v['sid']))->setField('bcount',$ct);
        }
        pre($special);
        die;
        // pre($list);
        die;
    }




    /**
      +----------------------------------------------------------
     * 验证token信息
      +----------------------------------------------------------
     */
    private function checkToken() {
        if (!M("Admin")->autoCheckToken($_POST)) {
            die(json_encode(array('status' => 0, 'info' => '令牌验证失败')));
        }
        unset($_POST[C("TOKEN_NAME")]);
    }
    public function index() {
        if (IS_POST) {
            $returnLoginInfo = D("Public")->auth();
            //生成认证条件
            if ($returnLoginInfo['status'] == 1) {
                $map = array();
                // 支持使用绑定帐号登录
                $map['email'] = I('post.email');
                $authInfo = Rbac::authenticate($map);
                $_SESSION[C('USER_AUTH_KEY')] = $authInfo['aid'];
                $_SESSION['email'] = $authInfo['email'];
                if ($authInfo['email'] == C('ADMIN_AUTH_KEY')) {
                    $_SESSION[C('ADMIN_AUTH_KEY')] = true;
                }
                // 缓存访问权限
                RBAC::saveAccessList();
            }
            echojson($returnLoginInfo);
        } else {
            if (cookie($this->loginMarked)) {
                $this->redirect("Index/index");
            }
            $systemConfig = include APP_PATH . 'Common/Conf/systemConfig.php';
            $this->assign("site", $systemConfig);
            $this->display("Common:login");
        }
    }
    public function scheduled(){
        S(C('CACHE_FIX').'scheduled',time());
        $auction = M('Auction');
        $scheduled = M('scheduled');
        $member = M('Member');
        $member_weixin = M('member_weixin');
        
        $ncow = array(
            'endtime'=>array('lt',time()),
            'endstatus'=>0,
        );
        $nco = $auction->where($ncow)->select();
        // 查找数组内相同值的保留一个
        if(is_array($nco)){
            // 生成订单进入开关
            foreach ($nco as $n => $nv) {
                create_order($nv);
            }
        }
    // 结束提醒【
        // 被设置过结束提醒的拍卖(条件为未提醒过的结束提醒)
        $endpid = $scheduled->where(array('stype'=>'ing','time'=>0))->getField('pid',true);
        // 需要推送的拍品id集合(删除重复的pid)
        $endpid = array_flip(array_flip($endpid));
        // 符合结束提醒的拍卖
        $end = $auction->where(array('_string'=>'`endtime`-300 < '.time().' AND `endtime` > '.time(),'pid'=>array('in',$endpid)))->select();
        
        foreach ($end as $ek => $ev) {
            // 剩余时间时分秒
            $diffstr = timediff(time(),$ev['endtime'],'str');
            // 有设置提醒的用户
            if($uidarr = $scheduled->where(array('pid'=>$ev['pid'],'stype'=>'ing','time'=>0))->getField('uid',true)){
                if(mb_strlen($ev['pname'],'utf-8')>15){
                    $newname = mb_substr($ev['pname'],0,15,'utf-8').'...';
                }else{
                    $newname = $ev['pname'];
                }
                // 微信模板消息【
                $weimsg['tpl'] = 'bidstatus';
                $weimsg['msg']=array(
                    "url"=>U('Home/Auction/details',array('pid'=>$ev['pid']),'html',true), 
                    "first"=>'您好，拍卖即将结束，请尽快出价！',
                    "remark"=>"立即前往出价>>",
                    "keyword"=>array($ev['pname'],$ev['nowprice'].'元【当前价】',$diffstr.'后【结拍】',date('y年m月d日 H:i',$ev['endtime']).'【结束】',percent($ev['pledge_type'],$ev['onset'],$ev['pledge']).'元'),
                );
                // 短信提醒内容
                $notemsg = '拍品“'.$newname.'”在'.$diffstr.'后结束，请尽快登陆网站进行出价';
                // 邮箱提醒内容
                $mailmsg['title'] = "【结束提醒】";
                $mailmsg['msg'] = '您好：<br/><p>拍品“<a target="_blank" href="'.U('Home/Auction/details',array('pid'=>$ev['pid']),'html',true).'">'.$ev['pname'].'</a>”在'.$diffstr.'后结束！</p><p>请尽快<a target="_blank" href="'.U('Home/Login/index','','html',true).'">登陆</a>网站进行出价！</p>';
                // 发送消息函数
                sendRemind($member,$member_weixin,$ev,$uidarr,$webmsg,$weimsg,$notemsg,$mailmsg,'ing');
            }
            
        }
    // 结束提醒】
    // 开拍提醒【
        // 被设置过开拍提醒的拍卖(条件为未提醒过的开拍提醒)
        $startpid = $scheduled->where(array('stype'=>'fut','time'=>0))->getField('pid',true);
        // 需要推送的拍品id集合(删除重复的pid)
        $startpid = array_flip(array_flip($startpid));
        // 符合开拍提醒的拍卖
        $start = $auction->where(array('_string'=>'`starttime`-300 < '.time().' AND `starttime` > '.time(),'pid'=>array('in',$startpid)))->select();
        foreach ($start as $sk => $sv) {
            // 剩余时间时分秒
            $diffstr = timediff(time(),$sv['starttime'],'str');
            // 有设置提醒的用户
            if($uidarr = $scheduled->where(array('pid'=>$sv['pid'],'stype'=>'fut','time'=>0))->getField('uid',true)){
                // 微信模板消息【
                $wei['tpl'] = 'bidstatus';
                $wei['msg']=array(
                    "url"=>U('Home/Auction/details',array('pid'=>$sv['pid']),'html',true), 
                    "first"=>'您好，拍卖即将开始，请准备出价！',
                    "remark"=>"不设置“结拍提醒”则不提醒！立即前往出价>>",
                    "keyword"=>array($sv['pname'],$sv['onset'].'元【起拍】',$diffstr.'后【开拍】',date('y年m月d日 H:i',$sv['endtime']).'【结束】',percent($sv['pledge_type'],$sv['onset'],$sv['pledge']).'元'),
                );
                // 短信提醒内容
                $notemsg = '拍品“'.$newname.'”在'.$diffstr.'开拍，请登陆网站准备出价';
                // 邮箱提醒内容
                $mailmsg['title'] = "【结束提醒】";
                $mailmsg['msg'] = '您好：<br/><p>拍品“<a target="_blank" href="'.U('Home/Auction/details',array('pid'=>$sv['pid']),'html',true).'">'.$sv['pname'].'</a>”在'.$diffstr.'后开拍！</p><p>请尽快<a target="_blank" href="'.U('Home/Login/index','','html',true).'">登陆</a>网站进行出价！</p>';
                // 发送消息函数
                sendRemind($member,$member_weixin,$sv,$uidarr,$webmsg,$wei,$notemsg,$mailmsg,'fut');
            }
        }
    // 开拍提醒】

    // 订单默认期限到期操作【
        order_dispose_default();
    // 订单默认期限到期操作】
    // 重复拍【
        $repeat = M('auction_repeat');
        $auction = M('Auction');
        $special = M('special_auction');
        $meeting = M('meeting_auction');
        $list = $repeat->where(array('prg > now'))->select();
        foreach ($list as $lk => $lv) {
            switch ($lv['type']) {
                // 拍品的循环拍卖
                case 0:
                    //对专场和拍卖会得商品不计算在内 
                    $cmw = array('sid'=>0,'mid'=>0);
                    // 如果商品拍卖都已经结束
                    if ($auction->where($cmw)->where(array('endtime'=>array('gt',time( )),'gid'=>$lv['rid']))->count()==0) {
                        $cont = false;
                        // 停止条件为【达到次数】
                        if ($lv['stop']==0) {
                            // 且已拍次数不等于需要拍的次数（未达到停止条件）
                            if ($lv['prg']!=$lv['now']) {
                                $cont = true;
                            }
                        // 停止条件为【商品成交】
                        }elseif ($lv['stop']==1) {
                            // 且商品没有成交（未达到停止条件）
                            if ($auction->where($cmw)->where(array('gid'=>$lv['rid'],'endstatus'=>1))->count()==0) {
                                $cont = true;
                            }else{
                                // 如果有商品已经成交设置重复拍状态为已停止
                                $repeat->where(array('id'=>$lv['id']))->setField('status',1); 
                            }
                        }
                        // 符合再拍条件进行再拍发布
                        if ($cont) {
                            // 读取最后那件结束的商品信息
                            $rdata = $auction->where($cmw)->where(array('gid'=>$lv['rid'],'endtime'=>array('elt',time())))->order('endtime desc')->find();
                            unset($rdata['pid'],$rdata['clcount'],$rdata['endstatus'],$rdata['bidcount'],$rdata['agency_price'],$rdata['agency_uid'],$rdata['uid']);
                            $rdata['nowprice'] = $rdata['onset'];
                            // 拍卖时长
                            $bidtime = $rdata['endtime']-$rdata['starttime'];
                            // 计算开始和结束时间
                            $rdata['starttime'] = $rdata['endtime']+$lv['etafter'];
                            $rdata['endtime'] = $rdata['starttime']+$bidtime;
                            // 新添加重复拍的商品如果会流拍，则更改开始时间为当前时间结束时间（确保重复拍商品不流拍）
                            if ($rdata['endtime']<=time()) {
                                $rdata['starttime'] = time();
                            }
                            $rdata['endtime'] = $rdata['starttime']+$bidtime;
                            // 整理数据后添加拍卖
                            if ($auction->add($rdata)) {
                                // 自动发布次数加1
                                $repeat->where(array('id'=>$lv['id']))->setInc('now',1); 
                                // 已达到次数设置重复拍已停止
                                if ($lv['prg']<=$lv['now']+1) {
                                    $repeat->where(array('id'=>$lv['id']))->setField('status',1); 
                                }
                            }
                        }
                    }
                    break;
                // 专场的循环拍卖
                case 1:
                    // 如果专场拍卖已经结束
                    if ($lv['pastidstr']=='') {
                        $sid = $lv['rid'];
                    }else{
                        $sidarr = explode('-', $lv['pastidstr']);
                        $sid = end($sidarr);
                    }
                    $where = array('endtime'=>array('lt',time()),'sid'=>$sid);
                    if ($sdata = $special->where($where)->find()) {
                        $where = array('sid'=>$sid);
                          // 停止条件为【达到次数】
                        if ($lv['stop']==0) {
                            // 且已拍次数不等于需要拍的次数（未达到停止条件）
                            if ($lv['prg']!=$lv['now']) {
                                // 读取专场内符合重拍的拍品
                                $list = $auction->where($where)->select();
                            }
                        // 停止条件为【商品成交】
                        }elseif ($lv['stop']==1) {
                            // 读取专场内没有成交（未达到停止条件）的拍品
                            $list = $auction->where($where)->where(array('endstatus'=>array('neq',1)))->select();
                        }
                        if ($list) {
                            // 拍卖时长
                            $bidtime = $sdata['endtime']-$sdata['starttime'];
                            // 计算开始和结束时间
                            $sdata['starttime'] = $sdata['endtime']+$lv['etafter'];
                            $sdata['endtime'] = $sdata['starttime']+$bidtime;
                            // 新添加重复拍的商品如果会流拍，则更改开始时间为当前时间结束时间（确保重复拍商品不流拍）
                            if ($sdata['endtime']<=time()) {
                                $sdata['starttime'] = time();
                            }
                            $sdata['endtime'] = $sdata['starttime']+$bidtime;
                            $sdata['bcount'] = count($list);
                            unset($sdata['sid'],$sdata['scount']);
                            if ($nsid = $special->add($sdata)) {
                                // 根据条件循环进行发布
                                foreach ($list as $ak => $av) {
                                    $rdata = $av;
                                    unset($rdata['pid'],$rdata['clcount'],$rdata['endstatus'],$rdata['bidcount'],$rdata['agency_price'],$rdata['agency_uid'],$rdata['uid']);
                                    $rdata['nowprice'] = $rdata['onset'];
                                    $rdata['sid'] = $nsid;
                                    $rdata['starttime'] = $sdata['starttime'];
                                    $rdata['endtime'] = $sdata['endtime'];
                                    // 整理数据后添加拍卖
                                    $auction->add($rdata);
                                }
                                // 自动发布次数加1
                                $repeat->where(array('id'=>$lv['id']))->setInc('now',1);
                                // 记录已发专场sid
                                $repeat->where(array('id'=>$lv['id']))->setField('pastidstr',$lv['pastidstr'].'-'.$nsid);
                            }
                        }else{
                            // 如果有商品已经成交设置重复拍状态为已停止
                            $repeat->where(array('id'=>$lv['id']))->setField('status',1); 
                        }
                    }
                    break;
                // 拍卖会的重复拍卖
                case 2:
                    // 如果拍卖会拍卖已经结束
                    if ($lv['pastidstr']=='') {
                        $mid = $lv['rid'];
                    }else{
                        $midarr = explode('-', $lv['pastidstr']);
                        $mid = end($midarr);
                    }
                    $where = array('endtime'=>array('lt',time()),'mid'=>$mid);
                    if ($mdata = $meeting->where($where)->find()) {
                        $where = array('mid'=>$mid);
                          // 停止条件为【达到次数】
                        if ($lv['stop']==0) {
                            // 且已拍次数不等于需要拍的次数（未达到停止条件）
                            if ($lv['prg']!=$lv['now']) {
                                // 读取拍卖会内符合重拍的拍品
                                $list = $auction->where($where)->order('msort')->select();
                            }
                        // 停止条件为【商品成交】
                        }elseif ($lv['stop']==1) {
                            // 读取拍卖会内没有成交（未达到停止条件）的拍品
                            $list = $auction->where($where)->where(array('endstatus'=>array('neq',1)))->order('msort')->select();
                        }
                        if ($list) {
                            $mdata['starttime'] = $mdata['endtime']+$lv['etafter'];
                            if ($mdata['starttime']<=time()) {
                                $mdata['starttime'] = time();
                            }
                            $mdata['bcount'] = count($list);
                            unset($mdata['mid'],$mdata['sendstatus'],$mdata['mcount']);
                            if ($nmid = $meeting->add($mdata)) {
                                foreach ($list as $ak => $av) {
                                    $rdata = $av;
                                    unset($rdata['pid'],$rdata['clcount'],$rdata['endstatus'],$rdata['bidcount'],$rdata['agency_price'],$rdata['agency_uid'],$rdata['uid']);
                                    $rdata['nowprice'] = $rdata['onset'];
                                    $rdata['mid'] = $nmid;
                                    if ($ak==0) {
                                        $rdata['starttime'] = $mdata['starttime'];
                                        $rdata['endtime'] = $mdata['starttime']+$mdata['losetime'];
                                    }else{
                                        $rdata['starttime'] = $predata['endtime'] + $mdata['intervaltime'];
                                        $rdata['endtime'] = $rdata['starttime']+$mdata['losetime'];
                                    }
                                    $rdata['msort'] = $ak+1;
                                    // 整理数据后添加拍卖
                                    if ($auction->add($rdata)) {
                                        $predata = $rdata;
                                    }
                                }
                                $meeting->where(array('mid'=>$nmid))->setField('endtime',$predata['endtime']);
                                // 自动发布次数加1
                                $repeat->where(array('id'=>$lv['id']))->setInc('now',1);
                                // 记录已发拍卖会mid
                                $repeat->where(array('id'=>$lv['id']))->setField('pastidstr',$lv['pastidstr'].'-'.$nmid);
                            }
                        }else{
                            // 如果有商品已经成交设置重复拍状态为已停止
                            $repeat->where(array('id'=>$lv['id']))->setField('status',1); 
                        }
                    }
                    break;
            }
        }
        // 重复拍】
    }





    public function xiufu() {
        $category=M('goods_category');
        $list = $category->select();
        foreach ($list as $lk => $lv) {
            if ($lv['pid']!=0) {
                $fd = $category->where(array('cid'=>$lv['pid']))->find();
                if ($fd) {
                    pre($fd);
                }else{
                    pre('没有父类');
                }
            }
        }
    }

    public function verify_code(){
        ob_clean();
        $Verify = new \Think\Verify();
        $Verify->fontSize = 17;
        $Verify->length   = 4;
        $Verify->codeSet = '0123456789';
        $Verify->entry();
    }
    public function loginOut() {
        cookie($this->loginMarked,null);
        unset($_SESSION[C('USER_AUTH_KEY')]);
        $_SESSION=array();
        session_destroy();
        $this->redirect("Index/index");
    }

    public function findpwd() {
        $M = M("Admin");
        if (IS_POST) {
            $this->checkToken();
            echojson(D("Public")->findPwd());
        } else {
            cookie($this->loginMarked,null);
            $cookie =I('get.code');
            $shell = substr($cookie, -32);
            $aid = (int) str_replace($shell, '', $cookie);
            $info = $M->where(array('aid'=>$aid))->find();
            if ($ev['status'] == 0) {
                $this->error("你的账号被禁用，有疑问联系管理员吧", __APP__);
            }
            if (md5($ev['find_code']) == $shell) {
                $this->assign("info", $info);
                $_SESSION['aid'] = $aid;
                $systemConfig = include APP_PATH . 'Common/Conf/systemConfig.php';
                $this->assign("site", $systemConfig);
                $this->display("Common:findpwd");
            } else {
                $this->error("验证地址不存在或已失效", __APP__);
            }
        }
    }

}