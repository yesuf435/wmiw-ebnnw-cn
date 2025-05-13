<?php
namespace Home\Model;
use Think\Model\ViewModel;
use Com\Wechat;
use Com\WechatAuth;
class WeixinModel extends ViewModel {
    // $where 推送用户的条件，仅用于member_weixi表的条件查询
    // $news 新闻二维数组 array(array("标题","描述", "链接地址","图片地址"),array("标题","描述", "链接地址","图片地址"))
    // 发送新闻
    public function sendNews($where, $news) {
        $news = array_filter($news);
        $key = array('newsA','newsB','newsC','newsD');
        $count = count($news);
        if($count>4){
            return '最多4条发送图文消息';
            exit();
        }
        $k = 0;
        foreach ($news as $nk => $nv) {
            $$key[$k]=$nv;
            $k += 1;

        }
        $count = M('member_weixin')->where($where)->count();
        $w_member = M('member_weixin')->where($where)->select();

        $accessToken = getJson(U('Home/Common/setAccessToken@'.$_SERVER['SERVER_NAME']));
        $WechatAuth = new WechatAuth(C('Weixin.appid'),C('Weixin.appsecret'),$accessToken);
        $suct = 0;
        $erct = 0;
        if($w_member){
            foreach ($w_member as $mk => $mv) {
                $result = $WechatAuth->sendNews($mv['openid'],$newsA,$newsB,$newsC,$newsD);
                if($result['errcode']==0){
                    $suct += 1;
                }else{
                    $erct += 1;
                }
            }
        }
        if($suct){
            return '微信用户共计'.$count.'位，成功推送图文消息给'.$suct.'位微信用户，'.$suct.'位未接收到！';
        }else{
            return '推送图文消息失败！';
        }
    }
    // 添加编辑图文
    public function addEdit($act,$where){
        $data = I('post.weixin');
        $send = $data['send'];
        unset($data['send']);
        if($act=='edit'){
            $yn = M('weiurl')->save($data);
            $wid = $data['id'];
            $ynmsg = '更新';
        }else{
            $yn = M('weiurl')->add($data);
            $wid = $yn;
            $ynmsg = '添加';
        }
        if($yn){
            // 微信推送新品发布【
            if(C('Weixin.appid')&&C('Weixin.appsecret')){
                // 是否设置推送信息 设置推送则保存或者直接推送
                if($send){
                    $webroot = C('WEB_ROOT');
                    if (__ROOT__=='/') {$root = '';}else{$root = __ROOT__;}
                    $senddata = array($data['name'],$data['comment'],$data['url'],$root.str_replace('./','', C('UPLOADS_PICPATH')).$data['toppic']);
                    // 给全部用户发送图文
                    // 获取微信登陆该站小于48小时的用户
                    $uidarr = M('member_weixin')->where(array('weitime'=>array('gt',time())))->getField('uid',true);
                    $wresult = $this->sendNews(array('uid'=>array('in',$uidarr)),array($senddata),$wid);
                }
            }else{
                return array('status' => 1, 'info' => $ynmsg.'成功！<br/>未配置微信，无法发送！', 'url' => U('Member/weisend'));
                exit;
            }
            return array('status' => 1, 'info' => $ynmsg.'成功！'.$wresult, 'url' => U('Member/weisend'));
        }else{
            return array('status' => 0, 'info' => $ynmsg.'失败！请修改后提交，请重试！','url' =>__SELF__);
        }
    }
    public function accesstoken(){
        if(!S('S_accessToken')){
            $WechatAuth = new WechatAuth(C('Weixin.appid'),C('Weixin.appsecret'));
            $S_accessToken=$WechatAuth->getAccessToken();
            if ($S_accessToken && is_array($S_accessToken)) {
                S('S_accessToken',$S_accessToken['access_token'],7200);//2小时过期
            }
        }
        return S('S_accessToken');
    }
    
}
?>
