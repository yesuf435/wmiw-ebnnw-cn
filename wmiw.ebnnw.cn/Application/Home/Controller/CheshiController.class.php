<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class CheshiController extends CommonController {
    public function index(){
      $post_data = array();  
      $post_data['optEmail'] = "oncoo_net@163.com";  
      $post_data['payAmount'] = "250";  
      $post_data['title'] = "免签支付";
      $post_data['memo'] = "阿萨德飞洒地方撒旦";  
      $post_data['submit'] = "submit";  
      $url='https://shenghuo.alipay.com/send/payment/fill.htm';  
      $o="";  
      foreach ($post_data as $k=>$v)  
      {  
          $o.= "$k=".urlencode($v)."&";  
      }  


      $content=iconv("UTF-8","GB2312//IGNORE",$content); 
      $sendurl=$ntc['url'];
      $sdata=$ntc['uid']['field']."=".$ntc['uid']['value']."&".$ntc['pwd']['field']."=".$ntc['pwd']['value']."&".$ntc['mob']['field']."=".$mobile."&".$ntc['con']['field']."=".$content;
      $status = explode('/',file_get_contents($sendurl.$sdata));



      $this->display();
    }

}