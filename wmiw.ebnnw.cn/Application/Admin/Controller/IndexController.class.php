<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index() {
        // 取款请求
        $take = M('member_pledge_take');
        $this->takeAll = $take->count();
        $this->takeUn = $take->where('status=0')->count();
        // 过期拍卖订单
        $lbid = array(
            'type'=>array('in',array('0','1')),
            'deftime1'=>array('lt',time()),
            'status'=>array('in',array('0','4')),
            );
        // ----全部过期

        // 最近一个月账号统计【
        $firstday = date('Y-m-01', strtotime(date("Y-m-d")));
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        $yue['time'] = array(array('egt',strtotime($firstday)),array('elt',strtotime($lastday)), 'and');
        // 卖家佣金
        $goods_order = M('goods_order');
        $walletsum['broker'] = $goods_order->where($yue)->where(array('status'=>array('in',array(3,10))))->sum('broker');
        $walletsum['broker_predict'] = $goods_order->where($yue)->where(array('status'=>array('in',array(0,1,2))))->sum('broker');
        // 买家手续费
        $walletsum['broker_buy'] = $goods_order->where($yue)->where(array('status'=>array('in',array(3,10))))->sum('broker_buy');
        $walletsum['broker_buy_predict'] = $goods_order->where($yue)->where(array('status'=>array('in',array(0,1,2))))->sum('broker_buy');
        // 网站总收入
        $walletsum['total'] = $walletsum['broker']+$walletsum['broker_buy'];
        $walletsum['total_predict'] = $walletsum['broker_predict']+$walletsum['broker_buy_predict'];
        $this->walletsum=$walletsum;
        // 最近一个月账号统计【

        // 在线充值图标显示统计【
        $result=array();
        $datestr = '';
        $moneystr = '';
        $pledge_bill = M('member_pledge_bill');
        for($i=1;$i<=7;$i++){
            $stday = date('Y-m-d', strtotime("-".$i." day"));
            if ($i==1) {
                $edday = date('Y-m-d');
            }else{
                $edday = date('Y-m-d', strtotime("-".($i-1)." day"));
            }
            // pre(array('stday'=>$stday,'edday'=>$edday));
            $pw = array(
                'time'=>array(array('egt',strtotime($stday)),array('elt',strtotime($edday)), 'and'),
                'changetype'=>'pay_deposit'
                );
            $datestr .= "'".substr($stday, -5)."',";
            $income = $pledge_bill->where($pw)->sum('income');
            $moneystr .= empty($income)?'0,':$income.',';
        }
        $this->datestr = rtrim($datestr, ",");
        $this->moneystr = rtrim($moneystr, ",");
        // 在线充值图标显示统计】

        $losebidAll = M('goods_order')->where($lbid)->count();
        // ----未处理过期
        $lbid['status']=0;
        $losebidUn = M('goods_order')->where($lbid)->count();
        $this->losebidAll=$losebidAll;
        $this->losebidUn=$losebidUn;

        //服务器信息
        if (function_exists('gd_info')) {
            $gd = gd_info();
            $gd = $gd['GD Version'];
        } else {
            $gd = "不支持";
        }
        $info = array(
            '操作系统' => PHP_OS,
            '主机名IP端口' => $_SERVER['SERVER_NAME'] . ' (' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . ')',
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'MYSQL版本' => function_exists("mysql_close") ? mysql_get_client_info() : '不支持',
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . "秒",
            '剩余空间' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            '服务器时间' => date("Y年n月j日 H:i:s")
        );
        $this->assign('server_info', $info);
        // 计划任务状态
        if (S(C('CACHE_FIX').'scheduled')) {$scheduled = '已配置，每分钟执行一次，上次执行时间【'.date('Y-m-dH:i',S(C('CACHE_FIX').'scheduled')).'】';}else{$scheduled = '未配置';}
        $this->scheduled = $scheduled;

        // 文章5条【
        $redata = D("News")->listNews(null,null,5);
        $this->nlist = $redata['list'];
        // 文章5条】

        // 意见反馈5条【
        $codata = D('Consultation')->listConsultation(null,'time desc',5);
        $this->clist=$codata['list'];
        // 意见反馈5条】

        


        $this->display();
    }
    // 资金统计
    public function statistics(){
        $member = M('member');
        $where = array();
        $uwhere = array();
        $wstar = '';
        $bktime = '';
        if(I('get.')){
            if(I('get.start_time')!=''){
                $wstar .= "time >= ".strtotime(I('get.start_time'))." and ";
                $bktime .= "time3 >= ".strtotime(I('get.start_time'))." and ";
            }
            if(I('get.end_time')!=''){
                $wstar .= "time <= ".strtotime(I('get.end_time'));
                $bktime .= "time3 <= ".strtotime(I('get.end_time'));
            }
            if($wstar!=''){
                $where['_string'] = $wstar;
                $bkwhere['_string'] = $bktime;
            }
            if(I('get.account')!=''){
                $user = $member->where(array('account'=>I('get.account')))->field('uid,nickname,account')->find();
                if ($user) {
                    $where['uid'] = $user['uid'];
                    $uwhere['uid'] = $user['uid'];
                    $bkwhere['uid'] = $user['uid'];
                }else{
                    $this->error('用户不存在！',U('Index/statistics'));
                }
            }
            $keys= I('get.');
            $this->keys = $keys;
        }
        $wallet_bill = M('member_pledge_bill');
        $limsum_bill = M('member_limsum_bill');
        $walletlist = $member->where($uwhere)->field('uid,wallet_pledge,wallet_pledge_freeze,wallet_limsum,wallet_limsum_freeze')->select();
        $walletsum = array(
            'wallet_pledge'=>array_sum(array_reduce($walletlist, create_function('$v,$w', '$v[$w["uid"]]=$w["wallet_pledge"];return $v;'))),
            'wallet_pledge_freeze'=>array_sum(array_reduce($walletlist, create_function('$v,$w', '$v[$w["uid"]]=$w["wallet_pledge_freeze"];return $v;'))),
            'wallet_limsum'=>array_sum(array_reduce($walletlist, create_function('$v,$w', '$v[$w["uid"]]=$w["wallet_limsum"];return $v;'))),
            'wallet_limsum_freeze'=>array_sum(array_reduce($walletlist, create_function('$v,$w', '$v[$w["uid"]]=$w["wallet_limsum_freeze"];return $v;'))),
            );
        // 建站到开始时间冻结金额 freeze_where():返回冻结的查询条件
        $pledge_where_freeze = $wallet_bill->where($where)->where(freeze_where())->sum('expend');
        $limsum_where_freeze = $limsum_bill->where($where)->where(freeze_where())->sum('expend');

        // 建站到开始时间解冻金额 unfreeze_where():返回解冻的查询条件
        $pledge_where_unfreeze = $wallet_bill->where($where)->where(unfreeze_where())->sum('income');
        $limsum_where_unfreeze = $limsum_bill->where($where)->where(unfreeze_where())->sum('income');

        // 建站到开始时间扣除的金额 reduce_where():返回扣除的查询条件
        $pledge_where_reduce = $wallet_bill->where($where)->where(reduce_where())->sum('expend');
        $limsum_where_reduce = $limsum_bill->where($where)->where(reduce_where())->sum('expend');

        // 建站到开始时间增加的金额 increase_where():返回增加的查询条件
        $pledge_where_increase = $wallet_bill->where($where)->where(increase_where())->sum('income');
        $limsum_where_increase = $limsum_bill->where($where)->where(increase_where())->sum('income');

        // 建站到开始时间扣除冻结的金额 increase_freeze_where():返回扣除冻结的查询条件
        $pledge_where_increase_freeze = $wallet_bill->where($where)->where(increase_freeze_where())->sum('expend');
        $limsum_where_increase_freeze = $limsum_bill->where($where)->where(increase_freeze_where())->sum('expend');

        // pre(freeze_where());
        // pre(unfreeze_where());
        // pre(reduce_where());
        // pre(increase_where());
        // pre(increase_freeze_where());
        // die;
        // 查询条件余额
        $walletsum['wallet_pledge_where'] = ($pledge_where_increase-$pledge_where_reduce)-$pledge_where_increase_freeze;
        // 冻结余额
        $walletsum['wallet_pledge_where_freeze'] = $pledge_where_freeze-$pledge_where_unfreeze-$pledge_where_increase_freeze;
        // 查询条件内可用余额
        $walletsum['wallet_pledge_where_usable'] = $walletsum['wallet_pledge_where']-$walletsum['wallet_pledge_where_freeze'];

        // 查询条件信誉
        $walletsum['wallet_limsum_where'] = ($limsum_where_increase-$limsum_where_reduce)-$limsum_where_increase_freeze;
        // 冻结信誉
        $walletsum['wallet_limsum_where_freeze'] = $limsum_where_freeze-$limsum_where_unfreeze-$limsum_where_increase_freeze;
        // 查询条件内可用信誉
        $walletsum['wallet_limsum_where_usable'] = $walletsum['wallet_limsum_where']-$walletsum['wallet_limsum_where_freeze'];





        
        // 开始到结束时间


        // $walletsum['wallet_all_pledge_freeze'] = $wallet_bill->where(array(''))
        // // 账户增加条件
        // $iw = array('changetype'=>array('in',array('admin_deposit','pay_deposit','share_add','profit')));
        // $walletsum['wallet_all_pledge_increase']
        // $walletsum['wallet_all_pledge_usable']

// 余额
        // 可用余额
        $walletsum['wallet_pledge_usable'] = $walletsum['wallet_pledge']-$walletsum['wallet_pledge_freeze'];
        // 可用信誉
        $walletsum['wallet_limsum_usable'] = $walletsum['wallet_limsum']-$walletsum['wallet_limsum_freeze'];

        // 拍卖冻结
        $walletsum['wallet_pledge_bid_expend'] = $wallet_bill->where($where)->where(array('changetype'=>'bid_freeze'))->sum('expend');
        // 发布拍卖冻结
        $walletsum['wallet_pledge_add_expend'] = $wallet_bill->where($where)->where(array('changetype'=>'add_freeze'))->sum('expend');
        // 管理员冻结
        $walletsum['wallet_pledge_admin_freeze'] = $wallet_bill->where($where)->where(array('changetype'=>'admin_freeze'))->sum('expend');
        // 提现冻结
        $walletsum['wallet_pledge_take_freeze'] = $wallet_bill->where($where)->where(array('changetype'=>'extract_freeze'))->sum('expend');

        // 拍卖解冻
        $walletsum['wallet_pledge_bid_income'] = $wallet_bill->where($where)->where(array('changetype'=>'bid_unfreeze'))->sum('income');
        // 交易成功解冻
        $walletsum['wallet_pledge_add_income'] = $wallet_bill->where($where)->where(array('changetype'=>'add_unfreeze'))->sum('income');
        // 管理员解冻
        $walletsum['wallet_pledge_admin_unfreeze'] = $wallet_bill->where($where)->where(array('changetype'=>'admin_unfreeze'))->sum('income');

        // 卖家未按时发货扣除
        $walletsum['wallet_pledge_seller_break_deliver'] = $wallet_bill->where($where)->where(array('changetype'=>'seller_break_deliver'))->sum('expend');
        // 订单过期扣除
        $walletsum['wallet_pledge_buy_break_nopay'] = $wallet_bill->where($where)->where(array('changetype'=>'buy_break_nopay'))->sum('expend');
        
        // 保证金抵货款
        $walletsum['wallet_pledge_paypledge_expend'] = $wallet_bill->where($where)->where(array('changetype'=>'pay_pledge'))->sum('expend');
        // 支付扣除
        $walletsum['wallet_pledge_pay_expend'] = $wallet_bill->where($where)->where(array('changetype'=>'pay_deduct'))->sum('expend');
        // 管理员扣除余额
        $walletsum['wallet_pledge_admin_expend'] = $wallet_bill->where($where)->where(array('changetype'=>'admin_deduct'))->sum('expend');
        // 提现扣除
        $walletsum['wallet_pledge_take'] = $wallet_bill->where($where)->where(array('changetype'=>'extract'))->sum('expend');

        // 卖家未按时发货收入
        $walletsum['wallet_pledge_buy_break_deliver'] = $wallet_bill->where($where)->where(array('changetype'=>'buy_break_deliver'))->sum('income');
        // 订单过期收入
        $walletsum['wallet_pledge_seller_break_nopay'] = $wallet_bill->where($where)->where(array('changetype'=>'seller_break_nopay'))->sum('income');

        // 在线充值
        $walletsum['wallet_pledge_inlin_income'] = $wallet_bill->where($where)->where(array('changetype'=>'pay_deposit'))->sum('income');
        // 充值卡充值
        $walletsum['wallet_pledge_card_income'] = $wallet_bill->where($where)->where(array('changetype'=>'card_deposit'))->sum('income');
        // 管理员充值余额
        $walletsum['wallet_pledge_admin_income'] = $wallet_bill->where($where)->where(array('changetype'=>'admin_deposit'))->sum('income');
        // 分享奖励
        // $walletsum['wallet_pledge_share_income'] = $wallet_bill->where($where)->where(array('changetype'=>'share_add'))->sum('income');
        // 交易收入
        $walletsum['wallet_pledge_profit_income'] = $wallet_bill->where($where)->where(array('changetype'=>'profit'))->sum('income');
        
// 信誉额度
        // 拍卖冻结
        $walletsum['wallet_limsum_bid_expend'] = $limsum_bill->where($where)->where(array('changetype'=>'bid_freeze'))->sum('expend');
        // 发布拍卖冻结
        $walletsum['wallet_limsum_add_expend'] = $limsum_bill->where($where)->where(array('changetype'=>'add_freeze'))->sum('expend');
        // 管理员冻结
        $walletsum['wallet_limsum_admin_freeze'] = $limsum_bill->where($where)->where(array('changetype'=>'admin_freeze'))->sum('expend');
        // 提现冻结
        $walletsum['wallet_limsum_take_freeze'] = $limsum_bill->where($where)->where(array('changetype'=>'extract_freeze'))->sum('expend');

        // 拍卖解冻
        $walletsum['wallet_limsum_bid_income'] = $limsum_bill->where($where)->where(array('changetype'=>'bid_unfreeze'))->sum('income');
        // 交易成功解冻
        $walletsum['wallet_limsum_add_income'] = $limsum_bill->where($where)->where(array('changetype'=>'add_unfreeze'))->sum('income');


        // 卖家未按时发货收入
        $walletsum['wallet_limsum_buy_break_deliver'] = $limsum_bill->where($where)->where(array('changetype'=>'buy_break_deliver'))->sum('income');
        // 订单过期收入
        $walletsum['wallet_limsum_seller_break_nopay'] = $limsum_bill->where($where)->where(array('changetype'=>'seller_break_nopay'))->sum('income');

        // 卖家未按时发货扣除
        $walletsum['wallet_limsum_seller_break_deliver'] = $limsum_bill->where($where)->where(array('changetype'=>'seller_break_deliver'))->sum('expend');
        // 订单过期扣除
        $walletsum['wallet_limsum_buy_break_nopay'] = $limsum_bill->where($where)->where(array('changetype'=>'buy_break_nopay'))->sum('expend');

        // 管理员解冻
        $walletsum['wallet_limsum_admin_unfreeze'] = $limsum_bill->where($where)->where(array('changetype'=>'admin_unfreeze'))->sum('income');

        // 管理员扣除余额
        $walletsum['wallet_limsum_admin_expend'] = $limsum_bill->where($where)->where(array('changetype'=>'admin_deduct'))->sum('expend');
        // 充值卡充值
        $walletsum['wallet_limsum_card_income'] = $limsum_bill->where($where)->where(array('changetype'=>'card_deposit'))->sum('income');
        // 管理员充值余额
        $walletsum['wallet_limsum_admin_income'] = $limsum_bill->where($where)->where(array('changetype'=>'admin_deposit'))->sum('income');
        // 分享奖励
        $walletsum['wallet_limsum_share_income'] = $limsum_bill->where($where)->where(array('changetype'=>'share_add'))->sum('income');
        // 推广建立
        $walletsum['wallet_limsum_reward_income'] = $limsum_bill->where($where)->where(array('changetype'=>'reward_add'))->sum('income');
// 卖家佣金
        $goods_order = M('goods_order');
        $walletsum['broker_where'] = $goods_order->where($bkwhere)->where(array('status'=>array('in',array(3,10))))->sum('broker');
        $walletsum['broker_where_predict'] = $goods_order->where($bkwhere)->where(array('status'=>array('in',array(0,1,2))))->sum('broker');

        $walletsum['broker'] = $goods_order->where(array('status'=>array('in',array(3,10))))->sum('broker');
        $walletsum['broker_predict'] = $goods_order->where(array('status'=>array('in',array(0,1,2))))->sum('broker');
// 买家手续费
        $walletsum['broker_buy_where'] = $goods_order->where($bkwhere)->where(array('status'=>array('in',array(3,10))))->sum('broker_buy');
        $walletsum['broker_buy_where_predict'] = $goods_order->where($bkwhere)->where(array('status'=>array('in',array(0,1,2))))->sum('broker_buy');

        $walletsum['broker_buy'] = $goods_order->where(array('status'=>array('in',array(3,10))))->sum('broker_buy');
        $walletsum['broker_buy_predict'] = $goods_order->where(array('status'=>array('in',array(0,1,2))))->sum('broker_buy');

        $this->walletsum=$walletsum;
        $this->display();
    }
    //取款申请
    public function take(){
        $take = M('member_pledge_take');
        $member= M('member');
        $count = $take->count();
        $pConf = page($count,20);
        $list = $take->limit($pConf['first'].','.$pConf['list'])->order('time desc')->select();
        foreach ($list as $tk => $tv) {
            $list[$tk]['user'] = $member->where('uid='.$tv['uid'])->field('account,uid,nickname')->find();
        }
        $this->sw=I('get.sw');
        $this->page = $pConf['show']; 
        $this->list = $list;
        $this->display();
    }
    public function search(){
	 $search = I('get.');
	 if($search['account']!=''){
		$uidarr = M('member')->where(array('account'=>array('LIKE', '%' . $search['account'] . '%')))->getField('uid',true);
		$where['uid'] = array('in',$uidarr);
	 }
	 if($search['status']!=''){
		$where['status'] = $search['status'];
	 }
	 $take = M('member_pledge_take');
	 $member= M('member');
	 $count = $take->where($where)->count();
	 $pConf = page($count,20);
	 $list = $take->where($where)->limit($pConf['first'].','.$pConf['list'])->order('time desc')->select();
	 foreach ($list as $tk => $tv) {
			$list[$tk]['user'] = $member->where('uid='.$tv['uid'])->field('account,uid,nickname')->find();
	}
	$this->page = $pConf['show']; 
	$this->list = $list;
	$this->display('take');
  }
    // 处理提现申请
    public function rtake() {
        $take = M('member_pledge_take');
        $member= M('member');
        if (IS_POST) {
            $data = I('post.data');
            $info = $take->where(array('tid'=>$data['tid']))->find();
            if ($info['status']!=0) {
                echojson(array('status' => 0, 'info' => '请勿重复操作！','url'=>U('Index/take')));
            }
            $data['dtime'] = time();
            if($take->save($data)){
                // 扣除冻结的保证金
                $where = array('uid'=>$info['uid']);
                $wallet = $member->where($where)->field('wallet_pledge,wallet_pledge_freeze')->find();
                $usable = sprintf("%.2f",$wallet['wallet_pledge']-$wallet['wallet_pledge_freeze']);
                // 已转账
                if($data['status']==1&&$wallet['wallet_pledge_freeze']>=$info['money']){
                    if($member->where($where)->setDec('wallet_pledge_freeze',$info['money'])&&$member->where($where)->setDec('wallet_pledge',$info['money'])){
                        // 扣除冻结
                        $pledge_data = array(
                            'order_no'=>createNo('rtk'),
                            'uid'=>$info['uid'],
                            'changetype'=>'extract',
                            'time'=>time(),
                            'annotation'=>$data['cause'],
                            'expend' => $info['money'],
                            'usable'=>$usable,
                            'balance'=>sprintf("%.2f",$wallet['wallet_pledge']-$info['money'])
                            );
                        if(M('member_pledge_bill')->add($pledge_data)){
                            // 给用户发消息
                            sendSms($pledge_data['uid'],'系统发送','管理员已同意您的'.$pledge_data['expend'].'元保证金提现申请！即将为您转账请注意查收！备注：'.$pledge_data['expend']);
                            echojson(array('status' => 1, 'info' => '已处理申请为已提现','url'=>U('Index/take')));
                        } //写入用户账户记录
                    }
                // 驳回
                }elseif ($data['status']==2) {
                    if($member->where($where)->setDec('wallet_pledge_freeze',$info['money'])){
                        // 解冻余额
                        $pledge_data = array(
                            'order_no'=>createNo('suf'),
                            'uid'=>$info['uid'],
                            'changetype'=>'admin_unfreeze',
                            'time'=>time(),
                            'annotation'=>$data['cause'],
                            'income'=>$info['money'],
                            'usable'=>sprintf("%.2f",$usable+$info['money']),
                            'balance'=>$wallet['wallet_pledge']
                            );
                        if(M('member_pledge_bill')->add($pledge_data)){
                            // 给用户发消息
                            sendSms($pledge_data['uid'],'系统发送','网站驳回了您'.$pledge_data['income'].'元提现申请！解冻保证金'.$pledge_data['income'].'元！备注：'.$pledge_data['remark']);
                            echojson(array('status' => 1, 'info' => '已处理申请为驳回申请'.$pledge_data['income'],'url'=>U('Index/take')));
                        } //写入用户账户记录
                    }
                }
            }
        }else{
            $info = $take->where('tid='.I('get.tid'))->find();
            $info['user'] = $member->where('uid='.$info['uid'])->field('account,uid,nickname')->find();
            $this->info=$info;
            $this->display('');
        }
    }

    public function myinfo() {
        if (IS_POST) {
            $data = I('post.data');
            $valid = valid_pass($data['pwd']);
            if (!$valid['status']) {echojson($valid); exit();}
            // if ($this->cAid==1) {
            //   echojson(array('status' => 0, 'info' => '演示站禁止修改超级管理员密码', 'url' => __ACTION__));
            //   die;
            // }
            echojson(D("Index")->my_info($this->loginMarked,$this->cAid));
        } else {
            $this->display();
        }
    }
    // 缓存管理
    public function cache() {
        $caches = array(
            "ConfigCache" => array("name" => "网站配置缓存", "path" => WEB_CACHE_PATH . "common~runtime.php"),
            "HomeCache" => array("name" => "网站前台缓存文件", "path" => WEB_CACHE_PATH . "Cache/Home/"),
            "AdminCache" => array("name" => "网站后台缓存文件", "path" => WEB_CACHE_PATH . "Cache/Admin/"),
            "HomeData" => array("name" => "网站前台数据库字段缓存文件", "path" => WEB_CACHE_PATH . "Data/Home/"),
            "AdminData" => array("name" => "网站后台数据库字段缓存文件", "path" => WEB_CACHE_PATH . "Data/Admin/"),
            "HomeLog" => array("name" => "网站日志缓存文件", "path" => WEB_CACHE_PATH . "Logs/"),
            "HomeTemp" => array("name" => "网站临时缓存文件", "path" => WEB_CACHE_PATH . "Temp/"),
            "MinFiles" => array("name" => "JS\CSS压缩缓存文件", "path" => WEB_CACHE_PATH . "MinFiles/")
        );
        if (IS_POST) {
            foreach ($_POST['cache'] as $path) {
                if (isset($caches[$path]))
                    delDirAndFile($caches[$path]['path']);
            }
           $this->checkToken();
            echojson(array("status"=>1,"info"=>"缓存文件已清除",'url'=>U('Index/index')));
        } else {
            $this->assign("caches", $caches);
            $this->display();
        }
    }
    // 意见反馈
    public function consultation(){
        $rdata = D('Consultation')->listConsultation(null,'time desc',5);
        $this->list=$rdata['list'];
        $this->page=$rdata['page'];
        $this->display();
    }
    // 回复意见反馈
    public function consultation_edit(){
        if (IS_POST) {
            $data = I('post.data');
            $data['aid']=$this->cAid;
            $data['rtime']=time();
            if(M('consultation')->save($data)){
                echojson(array('status' => 1, 'info' => '已回复！','url'=>U('Index/consultation')));
            }else{
                echojson(array('status' => 0, 'info' => '回复失败，请从新回复！','url'=>U('Index/consultation')));
            }
        }else{
            $info = D('Consultation')->where(array('id'=>I('get.id')))->find();
            if ($info) {
                $this->info=$info;
                $this->display();
            }else{
                $this->error('不存在的意见反馈，或已被删除！','Index/consultation');
            }
        }
    }
    // 删除意见反馈
    public function consultation_del(){
        if (M("consultation")->where("id=" . (int) $_GET['id'])->delete()) {
            $this->success("成功删除");
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }
    }
}