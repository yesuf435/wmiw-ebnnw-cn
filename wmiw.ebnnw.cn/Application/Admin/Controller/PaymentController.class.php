<?php
namespace Admin\Controller;
use Think\Controller;
class PaymentController extends CommonController {
    /**
     * 支付订单列表
     * @return [type] [description]
     */
    public function index() {
        $M = M("payorder");
        $count = $M->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $this->page = $pConf['show']; //分配分页
        $this->order = D("Payment")->payList($pConf['first'], $pConf['list']);
        C('TOKEN_ON',false);
        $this->display();
    }
    //删除友情链接
    public function del() {
        $where=array('bill_no'=>I('get.bill_no'));
        if (M("payorder")->where($where)->delete()) {
            $this->success("成功删除");
            //echojson(array("status"=>1,"info"=>""));
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }
    }
    // 支付订单搜索
    public function search(){
        $search = I('get.');
        $this->keys =$search;
        $where = array();
        if($search['status']!='') $where['status'] = $search['status'];
        if(!empty($search['start_time']) && !empty($search['end_time'])){
            $where['update_time'] = array(array('egt',strtotime($search['start_time'])),array('elt',strtotime($search['end_time'])), 'and');
        }elseif(!empty($search['start_time'])){
            $where['update_time'] = array('elt',strtotime($search['end_time']));
        }elseif(!empty($search['end_time'])){
            $where['update_time'] = array('egt',strtotime($search['start_time']));
        }
        $M = M("payorder");
        $count = $M->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $this->page = $pConf['show']; //分配分页
        $this->order = D("Payment")->payList($pConf['first'], $pConf['list'],$where);
        C('TOKEN_ON',false);
        $this->display('index');
    }
    /**
     * 支付接口配置
     * @return [type] [description]
     */
    public function pay_gallery(){
        if (IS_POST) {
            $setPay['payment'] = I('post.payment');
            $setPay['payment']['app_id'] = str_replace(' ','',$setPay['payment']['app_id']);
            $setPay['payment']['appSecret'] = str_replace(' ','',$setPay['payment']['appSecret']);
            $this->checkToken();

            if (set_config("payment", $setPay, APP_PATH . "Common/Conf/")) {
                delDirAndFile(WEB_CACHE_PATH . "Cache/Admin/");
                echojson(array('status' => 1, 'info' => $str . '已更新', 'url' =>U('Payment/pay_gallery')));
            } else {
                echojson(array('status' => 0, 'info' => $str . '失败，请检查', 'url' =>U('Payment/pay_gallery')));
            }
        } else {
            $payment = include APP_PATH . 'Common/Conf/payment.php'; //读取支付配置
            $this->webhookurl = U('/Payment/webhook','','',true);
            $this->payment=$payment['payment'];
            $this->weixinurl = str_replace('onreplac', '', U('Home/Payment/online',array('channel'=>'WX_JSAPI','bill'=>'onreplac'),'',true));
            $this->display();
        }
    }
    // 充值卡管理
    public function rechargeable(){
        $rechargeable = M('rechargeable');
        $count = $rechargeable->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $this->page = $pConf['show']; //分配分页
        $this->list = D("Rechargeable")->listRechargeable($pConf['first'], $pConf['list']);
        C('TOKEN_ON',false);
        $this->display();
    }
    // 搜索充值卡
    public function search_rechargeable(){
        $search = I('get.');
        $this->keys =$search;
        $where = array();
        if($search['status']!='') $where['status'] = $search['status'];
        if(!empty($search['start_time']) && !empty($search['end_time'])){
            $where['time'] = array(array('egt',strtotime($search['start_time'])),array('elt',strtotime($search['end_time'])), 'and');
        }elseif(!empty($search['start_time'])){
            $where['time'] = array('elt',strtotime($search['end_time']));
        }elseif(!empty($search['end_time'])){
            $where['time'] = array('egt',strtotime($search['start_time']));
        }
        $rechargeable = M('rechargeable');
        $count = $rechargeable->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $this->page = $pConf['show']; //分配分页
        $this->list = D("Rechargeable")->listRechargeable($pConf['first'], $pConf['list'],$where);
        C('TOKEN_ON',false);
        $this->display('rechargeable');
    }
    // 充值卡导出excel
    public function export_rechargeable(){
        $search = I('get.');
        $where = array();
        $name = '充值卡';
        if($search['status']!='') $where['status'] = $search['status'];

        if(!empty($search['start_time']) && !empty($search['end_time'])){
            $where['time'] = array(array('egt',strtotime($search['start_time'])),array('elt',strtotime($search['end_time'])), 'and');
            $timech = $search['start_time'].'-'.$search['end_time'];
        }elseif(!empty($search['start_time'])){
            $where['time'] = array('elt',strtotime($search['end_time']));
            $timech = '建站时间-'.$search['end_time'];
        }elseif(!empty($search['end_time'])){
            $where['time'] = array('egt',strtotime($search['start_time']));
            $timech = $search['start_time'].'-'.date('Y-m-d H:i',time());
        }
        $field = array('cardno','pwd','pledge','limsum','pasttime');
        $data = M('rechargeable')->where($where)->field($field)->select();
        foreach ($data as $dk => $dv) {
            $data[$dk]['cardno'] = ' '.(string)$dv['cardno'];
            $data[$dk]['pasttime'] = date('Y年m月d日 H时i分',$dv['pasttime']);
        }
        if ($dv['status']==0) {
            $name.= '[未使用]';
        }elseif ($dv['status']==1) {
            $name.= '[已使用]';
        }else{
            $name.= '[已过期]';
        }
        $name.=$timech;
        $title = array('卡号','密码','金额','信誉额度','过期时间');
        exportexcel($data,$title,$name);
        
    }
    // 添加充值卡
    public function add_rechargeable(){
        $rechargeable = M('rechargeable');
        if (IS_POST) {
            $this->checkToken();
            $info = I('post.info');
            // 充值卡数量判断
            if (empty($info['amount'])) {
                $info['amount'] = 1;
            }
            if(!preg_match("/^[1-9][0-9]*$/",$info['amount'])&&!(strlen($info['amount'])<4)){
                echojson(array('status' => 0, 'info' => '充值卡数量请输入小于9999的正整数！', 'url' => __SELF__));
                exit();
            }
            if(!$info['prefix']){
                echojson(array('status' => 0, 'info' => '充值卡前缀错误！', 'url' => __SELF__));
                exit();
            }
            if(strtotime($info['pasttime'])<time()){
                echojson(array('status' => 0, 'info' => '有效期必须大于当前时间！', 'url' => __SELF__));
                exit();
            }
            $data = array();
            $aid = $this->cAid;
            $suc = 0;
            $err = 0;
            for ($i=0; $i < $info['amount']; $i++) { 
                $data = array(
                    'cardno'=>$info['prefix'].time().sprintf("%04d", $i),
                    'pwd'=>randCode(6, 1),
                    'aid'=>$aid,
                    'pledge'=>$info['pledge'],
                    'limsum'=>$info['limsum'],
                    'time'=>time(),
                    'pasttime'=>strtotime($info['pasttime'])
                    );
                if ($rechargeable->add($data)) {
                    $suc+=1;
                }else{
                    $err+=1;
                }
            }
            if ($suc>0) {
                $msg = '成功生成'.$suc.'张充值卡！';
                if($err>0){
                    $msg.='生成失败'.$err.'张';
                }
                echojson(array('status' => 1, 'info' => $msg, 'url' => U('Payment/rechargeable')));

            }else{
                echojson(array('status' => 0, 'info' => '添加失败，请刷新页面重试！', 'url' => __SELF__));
            }
        }else{
            $this->display();
        }
    }
    // 删除充值卡
    public function del_rechargeable(){
        $where=array('cardno'=>I('get.cardno'));
        if (M("rechargeable")->where($where)->delete()) {
            $this->success("成功删除");
        } else {
            $this->error("删除失败，可能是不存在该ID的记录");
        }

    }


}