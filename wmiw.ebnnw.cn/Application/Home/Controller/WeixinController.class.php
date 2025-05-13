<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;

class WeixinController extends Controller{
    /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */
    public function index($id = ''){
        //调试
        try{
            $appid = C('Weixin.appid'); //AppID(应用ID)
            $token = C('Weixin.token'); //微信后台填写的TOKEN
            $crypt = C('Weixin.encodingaeskey'); //消息加密KEY（EncodingAESKey）
            /* 加载微信SDK */
            $wechat = new Wechat($token, $appid, $crypt);
            /* 获取请求信息 */
            $data = $wechat->request();
            if($data && is_array($data)){
                // 设置已关注和微信公众账号的交流时间
                $member_weixin = M('member_weixin');
                $updata = array('weitime'=>time()+172800,'subscribe'=>1);
                if (!$member_weixin->where(array('openid'=>$data['FromUserName']))->getField('subscribe_time')) {
                    $updata['subscribe_time'] = time();
                }
                $member_weixin->where(array('openid'=>$data['FromUserName']))->setField($updata);
                /**
                 * 你可以在这里分析数据，决定要返回给用户什么样的信息
                 * 接受到的信息类型有10种，分别使用下面10个常量标识
                 * Wechat::MSG_TYPE_TEXT       //文本消息
                 * Wechat::MSG_TYPE_IMAGE      //图片消息
                 * Wechat::MSG_TYPE_VOICE      //音频消息
                 * Wechat::MSG_TYPE_VIDEO      //视频消息
                 * Wechat::MSG_TYPE_SHORTVIDEO //视频消息
                 * Wechat::MSG_TYPE_MUSIC      //音乐消息
                 * Wechat::MSG_TYPE_NEWS       //图文消息（推送过来的应该不存在这种类型，但是可以给用户回复该类型消息）
                 * Wechat::MSG_TYPE_LOCATION   //位置消息
                 * Wechat::MSG_TYPE_LINK       //连接消息
                 * Wechat::MSG_TYPE_EVENT      //事件消息
                 *
                 * 事件消息又分为下面五种
                 * Wechat::MSG_EVENT_SUBSCRIBE    //订阅
                 * Wechat::MSG_EVENT_UNSUBSCRIBE  //取消订阅
                 * Wechat::MSG_EVENT_SCAN         //二维码扫描
                 * Wechat::MSG_EVENT_LOCATION     //报告位置
                 * Wechat::MSG_EVENT_CLICK        //菜单点击
                 */

                //记录微信推送过来的数据
                file_put_contents('./data.json', $data);

                /* 响应当前请求(自动回复) */
                //$wechat->response($content, $type);

                /**
                 * 响应当前请求还有以下方法可以使用
                 * 具体参数格式说明请参考文档
                 * 
                 * $wechat->replyText($text); //回复文本消息
                 * $wechat->replyImage($media_id); //回复图片消息
                 * $wechat->replyVoice($media_id); //回复音频消息
                 * $wechat->replyVideo($media_id, $title, $discription); //回复视频消息
                 * $wechat->replyMusic($title, $discription, $musicurl, $hqmusicurl, $thumb_media_id); //回复音乐消息
                 * $wechat->replyNews($news, $news1, $news2, $news3); //回复多条图文消息
                 * $wechat->replyNewsOnce($title, $discription, $url, $picurl); //回复单条图文消息
                 * 
                 */
                
                //执行operate
                $this->operate($wechat, $data);
            }
        } catch(\Exception $e){
            file_put_contents('./error.json', $e->getMessage());
        }
        
    }
    /**
     * operate
     * @param  Object $wechat Wechat对象
     * @param  array  $data   接受到微信推送的消息
     */
    private function operate($wechat, $data){
        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
                switch ($data['Event']) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        $updata = array('subscribe_time'=>time(),'subscribe'=>1);
                        M('member_weixin')->where(array('openid'=>$data['FromUserName']))->setField($updata);
                        $replyFollow = C('Weixin.reply_follow');
                        if (!$replyFollow) {
                            $replyFollow = '欢迎您关注'.C('SITE_INFO.name').'公众平台！';
                        }
                        $wechat->replyText($replyFollow);
                        break;

                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        $updata = array('subscribe_time'=>0,'subscribe'=>0);
                        M('member_weixin')->where(array('openid'=>$data['FromUserName']))->setField($updata);
                        break;
                    default:
                        $wechat->replyText("欢迎访问".C('SITE_INFO.name')."公众平台！");
                        break;
                }
                break;

            case Wechat::MSG_TYPE_TEXT:
                // 执行回复的内容
                $where = array('keywords'=>$data['Content']);
                $info = M('weiurl')->where($where)->find();
                S('weiC',array('name'=>'weiC','time'=>date('Y-m-d H:i:s',time()),'data'=>$data,'info'=>$info));
                switch ($info['msgtype']) {
                    case 'news':
                        //回复单条图文消息
                        $wechat->replyNewsOnce($info['name'],$info['comment'],$info['url'],getImgUrl($info['toppic'])); 
                        break;
                    case 'text':
                        $wechat->replyText($info['comment']);
                        break;
                    case 'image':
                        $upinfo['file'] = C('UPLOADS_PICPATH').$info['toppic'];
                        $media_id = $this->upload('image',$upinfo);
                        $wechat->replyImage($media_id);
                        break;
                    case 'voice':
                        $upinfo['file'] = C('UPLOADS_PICPATH').$info['enclosure'];
                        $media_id = $this->upload('voice',$upinfo);
                        $wechat->replyVoice($media_id);
                        
                        break;
                    case 'video':
                        $upinfo['file'] = C('UPLOADS_PICPATH').$info['enclosure'];
                        $upinfo['name'] = $info['name'];
                        $upinfo['comment'] = $info['comment'];
                        $media_id = $this->upload('video',$upinfo);
                        S('weiD',array('name'=>'weiD','time'=>date('Y-m-d H:i:s',time()),'data'=>$media_id));
                        S('weiE',array('name'=>'weiE','time'=>date('Y-m-d H:i:s',time()),'data'=>$upinfo));
                        $rt = $wechat->replyVideo($media_id, $upinfo['name'],$upinfo['comment']);
                        S('weiF',array('name'=>'weiF','time'=>date('Y-m-d H:i:s',time()),'data'=>$rt));
                        break;
                    case 'thumb':
                        $upinfo['file'] = C('UPLOADS_PICPATH').$info['toppic'];
                        $media_id = $this->upload('thumb',$upinfo);
                        $wechat->replyMusic($info['name'],$info['comment'],$info['url'],$info['urlh'], $media_id); //回复音乐消息
                        break;
                    default:
                        $replyDefaule = C('Weixin.reply_defaule');
                        if (!$replyDefaule) {
                            $replyDefaule = '没有对应的消息！';
                        }
                        $wechat->replyText($replyDefaule);
                        break;
                }
                
                
                
            }
    }

    /**
     * 资源文件上传方法
     * @param  string $type 上传的资源类型
     * @return string       媒体资源ID
     */
    private function upload($type,$upinfo){
        $appid     = C('Weixin.appid');
        $appsecret = C('Weixin.appsecret');
        $token = session("token");
        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();

            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }
        S('weiA',array('name'=>'weiA','time'=>date('Y-m-d H:i:s',time()),'data'=>$upinfo));
        switch ($type) {
            case 'image':
                $media    = $auth->materialAddMaterial($upinfo['file'], $type);
                break;

            case 'voice':
                $media    = $auth->materialAddMaterial($upinfo['file'], $type);
                break;

            case 'video':
                $discription = array('title' => $upinfo['name'], 'introduction' => $upinfo['comment']);
                $media       = $auth->materialAddMaterial($upinfo['file'], $type, $discription);
                break;

            case 'thumb':
                $media    = $auth->materialAddMaterial($upinfo['file'], $type);
                break;
            
            default:
                return '';
        }
        
        S('weiB',array('name'=>'weiB','time'=>date('Y-m-d H:i:s',time()),'data'=>$media));

        if($media["errcode"] == 42001){ //access_token expired
            session("token", null);
            $this->upload($type,$updata);
        }

        return $media['media_id'];
    }

    public function wechatrequest(){
        pre(S('weiA'));
        pre(S('weiB'));
        pre(S('weiC'));
        pre(S('weiD'));
        pre(S('weiE'));
    }
    public function accesstoken(){
        $appid = C('Weixin.appid'); //AppID(应用ID)        
        $secret = C('Weixin.appsecret'); //微信后台填写的TOKEN        
        if(S('S_accessToken')){
            $WechatAuth = new WechatAuth($appid,$secret,S('S_accessToken'));
        }else{
            $WechatAuth = new WechatAuth($appid,$secret);
            $S_accessToken=$WechatAuth->getAccessToken();
            if ($S_accessToken && is_array($S_accessToken)) {
                S('S_accessToken',$S_accessToken['access_token'],7200);//2小时过期
            }
        }
        $accessToken = S('S_accessToken');
        echo $accessToken;    
        die;
    }
}
