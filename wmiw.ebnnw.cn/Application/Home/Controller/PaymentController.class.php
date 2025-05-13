<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class PaymentController extends CommonController {
    public function index(){
    	$sub_data = I('post.data');
    	$uid = $this->cUid;
    	// 避免计算出错
    	$sub_data["money"] = sprintf("%.2f",$sub_data["money"]);
    	$data = array();
		// $data["channel"] 渠道类型 	
		$data["channel"] = $sub_data["channel"];
		// $data["timestamp"] 签名生成时间 	时间戳，毫秒数
		$data["timestamp"] = time() * 1000;
		//选填 订单失效时间bill_timeout
		//必须为非零正整数，单位为秒，建议最短失效时间间隔必须大于360秒
		//京东(JD*)不支持该参数。
		//$data["bill_timeout"] = 360;
		/**
		 * notify_url 选填，该参数是为接收支付之后返回信息的,仅适用于线上支付方式, 等同于在beecloud平台配置webhook，
		 * 如果两者都设置了，则优先使用notify_url。配置时请结合自己的项目谨慎配置，具体请
		 * 参考demo/webhook.php
		 */
		// $data['notify_url'] = 'http://beecloud.cn';
    	switch ($sub_data["payType"]) {
    		case 'recharge':
    		// 充值
    			// 订单标题
    			if ($sub_data['purpose']=='pledge') {
    				$bill_no = createNo('czy');
	    			// 生成本地支付订单保存【
	    			$adata = array(
	                    'bill_no' => $bill_no, 
	                    'order_no' => $bill_no, 
	                    'purpose'=>'pledge',
	                    'useid'=>0,
	                    'uid'=>$uid,
	                    'money' => $sub_data["money"],
	                    'yuemn'=>0,
	                    'pledge'=>0,
	                    'paytype'=>$sub_data["channel"],
	                    'status' => 0,
	                    'create_time' => time(),
	                    'update_time' => time(),
	                    'title'=>'充值余额',
	                    'return_url'=> U('Home/Member/wallet','','',true),
	                    'show_url' => U('Home/Member/payment','','',true)
	                );

	                $check = M("Payorder")->add($adata);
	                // 生成本地支付订单保存】
    			}
    			break;
    		case 'auction':
    			$member = M('Member');
    			// 计算发货过期时间
    			if(C('Order.losetime2')==0||C('Order.losetime2')==''){
	                $deftime2 = 0;
	            }else{
	                $losetime2=C('Order.losetime2');
	                $deftime2 = time()+(60*60*24*$losetime2);
	            }
    		// 支付拍卖订单
    			if(!$sub_data['adid']){
	                $this->error('请设置您的收货地址后进行支付！',U('Member/deliver_address'),5);
	            }
	            $ptp = I('post.ptp');
	            $goods_order = M('goods_order');
	            $auction = M('auction');
	            // 读取订单信息
	            $order_info = $goods_order->where(array('order_no'=>$sub_data['order_no']))->find();
	            if($order_info['status']!=0){
	                $this->error('订单已失效或不存在的订单号！',U("Home/Member/mysucc",array('st'=>1)));
	            }
	            // 订单总金额
	            $total = 0;
	            // 设置发货地址
	            $region = M('region');
	            $address = M('deliver_address')->where(array('adid'=>$sub_data['adid'],'uid'=>$this->cUid))->field('prov,city,district,address,truename,mobile')->find();
	            if($address){
	                $goods_order->where(array('order_no'=>$sub_data['order_no']))->setField('address',serialize($address));
	            }else{
	                $this->error('地址被删除，或不可用的地址！',U('Member/deliver_address'),5);
	            }

	            // 写入卖家留言
	            $goods_order->where(array('order_no'=>$sub_data['order_no']))->setField('buyer_msg',$sub_data['buyer_msg']);
	            // 设置发货地址】
	            /*需要支付金额（商品价格、运费、和佣金）*/
	            $total = $order_info['price']+$order_info['freight']+$order_info['broker_buy'];
	            // 读取拍品订单
	            $bidinfo = $auction->where(array('pid'=>$order_info['gid']))->find();
	            // 获取支付拍品保证金金额
	            $regp = getpledge($bidinfo);
	            $pledge = $regp['money']['pledge'];
	            // 没有支付方式使用保证金抵货款
	            if ($sub_data["channel"]=='') {
	            	// 保证金抵货款
	            	if($sub_data['bpy']){
		                // 保证金足以支付订单
		                if (bccomp($pledge,$total,2)!=-1) {
		                	// 保证金抵货款操作
		            		$ppdata = D('Payment')->pledgepaySend($bidinfo,$total,$sub_data['order_no'],$order_info['uid']);
		                    if ($ppdata['status']) {
			                    if($goods_order->where(array('order_no'=>$sub_data['order_no']))->setField(array('status'=>1,'time1'=>time(),'deftime2'=>$deftime2))){
				                	// 解冻买家保证金和信用额度
				                	payBidUnfreeze($sub_data['order_no']);
				                	// 发送订单状态提醒
					    			sendOrderRemind($sub_data['order_no']);
				                	$this->success('支付成功',U('Home/Member/mysucc',array('st'=>1)));
				                }else{
				                	$this->error('支付失败，请联系管理员或刷新页面重试！',$adata["show_url"]);
				                }
			                }else{
			                    $this->error($ppdata['info'],$adata["show_url"]);
			                }
		                }
		            }
	            }elseif($sub_data["channel"]=='yue'){
	            	// 保证金抵货款【
	            	if($sub_data['bpy']&&$pledge>0){
	            		// 保证金抵货款操作
	            		$ppdata = D('Payment')->pledgepaySend($bidinfo,$pledge,$sub_data['order_no'],$order_info['uid']);
	                    if ($ppdata['status']) {
		                    $total = $total-$pledge;
		                }else{
		                    $this->error($ppdata['info'],$adata["show_url"]);
		                }
                    }
                    // 保证金抵货款】
	                // 余额支付【
                    $yedata = D('Payment')->yuepaySend($bidinfo,$total,$sub_data['order_no'],$order_info['uid']);
                    if ($yedata['status']==0) {
	                    $this->error($yedata['info'],$adata["show_url"]);
	                }
	                // 更改订单状态为已支付
	                if($goods_order->where(array('order_no'=>$sub_data['order_no']))->setField(array('status'=>1,'time1'=>time(),'deftime2'=>$deftime2))){
	                	// 解冻保证金和信用额度
	                	payBidUnfreeze($sub_data['order_no']);
	                	// 发送订单状态提醒
		    			sendOrderRemind($sub_data['order_no']);
	                	$this->success('支付成功',U('Home/Member/mysucc',array('st'=>1)));
	                }else{
	                	$this->error('支付失败，请联系管理员或刷新页面重试！',$adata["show_url"]);
	                }
	                // 余额支付】
	    			break;
    			}else{
    			 	// 计算出在线支付的钱数：如果勾选保证金抵货款且保证金大于0，在线支付金额为订单总额减去保证金
	            	if($sub_data['bpy']&&$pledge>0){
		                $total = sprintf("%.2f",$total-$pledge);
                    }
    			 	// 第三方支付进行支付
					$bill_no = createNo('zfp');
	    			// 生成本地支付订单保存【
	    			$adata = array(
	                    'bill_no' => $bill_no, 
	                    'order_no' => $sub_data['order_no'], 
	                    'purpose'=>'auction',
	                    'useid'=>$bidinfo['pid'],
	                    'uid'=>$uid,
	                    'money' => $total,
	                    'yuemn'=>0,
	                    'pledge'=>$pledge,
	                    'paytype'=>$sub_data["channel"],
	                    'status' => 0,
	                    'create_time' => time(),
	                    'update_time' => time(),
	                    'title'=>'支付拍品订单',
	                    'return_url'=>U('Member/mysucc',array('st'=>1),'',true),
	                    'show_url' => U('Member/payment_order',array('order_no'=>$sub_data['order_no'],'',true))
	                    
	                );
	                $check = M("Payorder")->add($adata);
	                // 生成本地支付订单保存】
	            }
    		}
    	if ($check) {
    	// 生成支付订单成功【
		    $this->redirect('Home/Payment/online', array('bill' => $adata['bill_no']), 0, '页面跳转中...');
	    // 生成支付订单成功【
    	}else{
    	// // 生成支付订单失败【
    		$this->error('生成支付订单失败！请联系管理员解决，或刷新页面重试',$adata["show_url"]);
    	// // 生成支付订单失败【
    	}
    }
    public function online(){
    	Vendor('beecloud.loader');
		try {
		    /* registerApp fun need four params:
		     * @param(first) $app_id beecloud平台的APP ID
		     * @param(second) $app_secret  beecloud平台的APP SECRET
		     * @param(third) $master_secret  beecloud平台的MASTER SECRET
		     * @param(fouth) $test_secret  beecloud平台的TEST SECRET, for sandbox
		     */
		    // $api->registerApp(C('payment.app_id'), C('payment.appSecret'), C('payment.masterSecret'), C('payment.testSecret'));
		    //Test Model,只提供下单和支付订单查询的Sandbox模式,不写setSandbox函数或者false即live模式,true即test模式
		    // $api->setSandbox(false);

		    \beecloud\rest\api::registerApp(C('payment.app_id'), C('payment.appSecret'), C('payment.masterSecret'), C('payment.testSecret'));
		    \beecloud\rest\api::setSandbox(false);
		}catch(Exception $e){
		    die($e->getMessage());
		}
		$adata = M('payorder')->where(array('bill_no'=>I('get.bill')))->find();
		// $data["bill_no"] 商户订单号
		$data["bill_no"] = $adata['bill_no'];
		// $data["total_fee"] 订单总金额 	必须是正整数，单位为分
		$data["total_fee"] =  (Integer)($adata['money']*100);
		// $data["timestamp"] 签名生成时间 	时间戳，毫秒数
		$data["timestamp"] = time() * 1000;
		// $data["return_url"] String 同步返回页面 	渠道类型:ALI_WEB 或 ALI_QRCODE 或 UN_WEB或JD_WAP或JD_WEB时为必填 支付渠道处理完请求后,当前页面自动跳转到商户网站里指定页面的http路径不包含?及&，必须为http://或者https://开头
		$data["return_url"] = $adata['return_url'];
		// $data["show_url"] String 	商品展示地址以http://开头
		$data["show_url"] = $adata['show_url'];
		if ($adata['purpose']=='pledge') {
			$data["title"] = '充值余额';
			//optional 附加数据 	用户自定义的参数，将会在webhook通知中原样返回，该字段主要用于商户携带订单的自定义数据 	{"key1":"value1","key2":"value2",...}
			$data["optional"] = (object)array('order_no'=>$adata["bill_no"]);
		}
		if ($adata['purpose']=='auction') {
			$data["title"] = '支付拍品订单';
			//optional 附加数据 	用户自定义的参数，将会在webhook通知中原样返回，该字段主要用于商户携带订单的自定义数据 	{"key1":"value1","key2":"value2",...}
			$data["optional"] = json_decode(json_encode(array('order_no'=>$adata['order_no'])));
		}

		if (I('get.channel')) {
			$type = I('get.channel');
		}else{
			$type = $adata["paytype"];
		}
		switch($type){
		    case 'ALI_WEB' :
		        // 支付宝及时到账
		        $data["channel"] = "ALI_WEB";
		        break;
		    case 'ALI_WAP' :
		        // 支付宝移动网页";
		        $data["channel"] = "ALI_WAP";
		        //非必填参数,boolean型,是否使用APP支付,true使用,否则不使用
		        //$data["use_app"] = true;
		        break;
		    case 'ALI_QRCODE' :
		        // 支付宝扫码支付
		        $data["channel"] = "ALI_QRCODE";
		        //qr_pay_mode必填 二维码类型含义
		        //0： 订单码-简约前置模式, 对应 iframe 宽度不能小于 600px, 高度不能小于 300px
		        //1： 订单码-前置模式, 对应 iframe 宽度不能小于 300px, 高度不能小于 600px
		        //3： 订单码-迷你前置模式, 对应 iframe 宽度不能小于 75px, 高度不能小于 75px
		        $data["qr_pay_mode"] = "0";
		        break;
		    case 'ALI_OFFLINE_QRCODE' :
		        $data["channel"] = "ALI_OFFLINE_QRCODE";
		        // 支付宝线下扫码";
		        $this->_alioffline($data);
		        // require_once 'ali.offline.qrcode/index.php';
		        exit();
		        break;
		    case 'BD_WEB' :
		        $data["channel"] = "BD_WEB";
		        // 百度网页支付
		        break;
		    case 'BD_WAP' :
		        $data["channel"] = "BD_WAP";
		        // 百度移动网页
		        break;
		    case 'JD_B2B' :
		        $data["channel"] = "JD_B2B";
		        /*
		         * bank_code(int 类型) for channel JD_B2B
		        9102    中国工商银行      9107    招商银行
		        9103    中国农业银行      9108    光大银行
		        9104    交通银行          9109    中国银行
		        9105    中国建设银行		9110 	 平安银行
		        */
		        $data["bank_code"] = 9102;
		        // 京东B2B
		        break;
		    case 'JD_WEB' :
		        $data["channel"] = "JD_WEB";
		        // 京东网页
		        break;
		    case 'JD_WAP' :
		        $data["channel"] = "JD_WAP";
		        // 京东移动网页
		        break;
		    case 'UN_WEB' :
		        $data["channel"] = "UN_WEB";
		        // 银联网页
		        break;
		    case 'UN_WAP' : //由于银联做了适配,需在移动端打开,PC端仍显示网页支付
		        $data["channel"] = "UN_WAP";
		        // 银联移动网页
		        break;
		    case 'WX_NATIVE':
		        $data["channel"] = "WX_NATIVE";
		        $this->_native($data);
		        // 微信扫码";
		        exit();
		        break;
		    case 'WX_JSAPI':
		        $data["channel"] = "WX_JSAPI";
		        // 微信H5网页";
		        $this->_jsapi($data);
		        exit();
		        break;
		    case 'YEE_WEB' :
		        $data["channel"] = "YEE_WEB";
		        // 易宝网页";
		        break;
		    case 'YEE_WAP' :
		        $data["channel"] = "YEE_WAP";
		        $data["identity_id"] = "lengthlessthan50useruniqueid";
		        // 易宝移动网页";
		        break;
		    case 'YEE_NOBANKCARD':
		        //total_fee(订单金额)必须和充值卡面额相同，否则会造成金额丢失(渠道方决定)
		        $data["total_fee"] = $data["total_fee"];
		        $data["channel"] = "YEE_NOBANKCARD";
		        //点卡卡号，每种卡的要求不一样
		        $data["cardno"] = "15078120125091678";
		        //点卡密码，简称卡密
		        $data["cardpwd"] = "121684730734269992";
		        /*
		         * frqid 点卡类型编码
		         * 骏网一卡通(JUNNET),盛大卡(SNDACARD),神州行(SZX),征途卡(ZHENGTU),Q币卡(QQCARD),联通卡(UNICOM),
		         * 久游卡(JIUYOU),易充卡(YICHONGCARD),网易卡(NETEASE),完美卡(WANMEI),搜狐卡(SOHU),电信卡(TELECOM),
		         * 纵游一卡通(ZONGYOU),天下一卡通(TIANXIA),天宏一卡通(TIANHONG),32 一卡通(THIRTYTWOCARD)
		         */
		        $data["frqid"] = "SZX";
		        // 易宝点卡支付";
		        break;
		    case 'KUAIQIAN_WEB' :
		        $data["channel"] = "KUAIQIAN_WEB";
		        // 快钱移动网页";
		        break;
		    case 'KUAIQIAN_WAP' :
		        $data["channel"] = "KUAIQIAN_WEB";
		        // 快钱移动网页";
		        break;
		    case 'PAYPAL_PAYPAL' :
		        $data["channel"] = "PAYPAL_PAYPAL";
		        /*
		         * currency参数的对照表, 请参考:
		         * https://github.com/beecloud/beecloud-rest-api/tree/master/international
		         */
		        $data["currency"] = "USD";
		        // Paypal网页";
		        break;
		    case 'PAYPAL_CREDITCARD' :
		        $data["channel"] = "PAYPAL_CREDITCARD";
		        /*
		         * currency参数的对照表, 请参考:
		         * https://github.com/beecloud/beecloud-rest-api/tree/master/international
		         */
		        $data["currency"] = "USD";

		        $card_info = array(
		            'card_number' => '',
		            'expire_month' => 1,  //int month
		            'expire_year' => 2016, //int year
		            'cvv' => 0,           //string
		            'first_name' => '', //string
		            'last_name' => '',  //string
		            'card_type' => 'visa' //string
		        );
		        $data["credit_card_info"] = (object)$card_info;
		        // Paypal信用卡";
		        break;
		    case 'PAYPAL_SAVED_CREDITCARD' :
		        $data["channel"] = "PAYPAL_SAVED_CREDITCARD";
		        /*
		         * currency参数的对照表, 请参考:
		         * https://github.com/beecloud/beecloud-rest-api/tree/master/international
		         */
		        $data["currency"] = "USD";
		        $data["credit_card_id"] = '';
		        // Paypal快捷";
		        break;
		    case 'BC_GATEWAY' :
		        $data["channel"] = "BC_GATEWAY";
		        /*
		         * bank(string 类型) for channel BC_GATEWAY
		         * CMB	  招商银行    ICBC	工商银行   CCB   建设银行(暂不支持)
		         * BOC	  中国银行    ABC    农业银行   BOCM	交通银行
		         * SPDB   浦发银行    GDB	广发银行   CITIC	中信银行
		         * CEB	  光大银行    CIB	兴业银行   SDB	平安银行
		         * CMBC   民生银行    NBCB   宁波银行   BEA   东亚银行
		         * NJCB   南京银行    SRCB   上海农商行 BOB   北京银行
		        */
		        $data["bank"] = "BOC";
		        // BC网关支付";
		        break;
		    case 'BC_EXPRESS' :
		        $data["channel"] = "BC_EXPRESS";
		        //渠道类型BC_EXPRESS, total_fee(int 类型) 单位分, 最小金额100分
		        $data["total_fee"] = $data["total_fee"];
		        //银行卡卡号, 选填
		        //$data["card_no"] = '622269192199384xxxx';
		        // BC快捷支付";
		        break;
		    case 'BC_NATIVE' :
		        $data["channel"] = "BC_NATIVE";
		        // BC微信扫码";
		        $this->_native($data);
		        exit();
		        break;
			case 'BC_WX_WAP' :
				$data["channel"] = "BC_WX_WAP";
				$ip=get_client_ip();
			    $data["analysis"] = array("ip"=>$ip);
				// BC微信移动网页支付";
				break;
		    case 'BC_WX_SCAN' :
		        $data["channel"] = "BC_WX_SCAN";
		        // BC微信刷卡";
		        $data["auth_code"] = "13022657110xxxxxxxx";
		        break;
		    case 'BC_WX_JSAPI':
		        $data["channel"] = "BC_WX_JSAPI";
		        // 微信H5网页";
		        $this->_jsapi($data);
		        exit();
		        break;
		    case 'BC_ALI_QRCODE' :
		        $data["channel"] = "BC_ALI_QRCODE";
		        // BC支付宝线下扫码";
		        $this->_alioffline($data);
		        // require_once 'ali.offline.qrcode/index.php';
		        exit();
		        break;
		    case 'BC_ALI_SCAN' :
		        $data["channel"] = "BC_ALI_SCAN";
		        // BC支付宝刷卡";
		        $data["auth_code"] = "28886955594xxxxxxxx";
		        break;
		    default :
		        exit("No this type.");
		        break;
		}
		try {
		    if(in_array($type, array('BC_ALI_SCAN', 'BC_WX_SCAN'))){
		        $result =  \beecloud\rest\api::offline_bill($data);
		    }else{
		        $result =  \beecloud\rest\api::bill($data);
		    }
		    if ($result->result_code != 0) {
		    	 $this->error($result->errMsg.'<br/>请联系管理员处理！');
		    }
		    if(isset($result->url)){
		        header("Location:$result->url");
			}else if(isset($result->html)) {
		        echo $result->html;
		    }else if(isset($result->credit_card_id)){
		        echo '信用卡id(PAYPAL_CREDITCARD): '.$result->credit_card_id;
		    }else if(isset($result->id)){
		        echo $type.'支付成功: '.$result->id;
		    }
		} catch (Exception $e) {
		    echo $e->getMessage();
		}
	// 在线支付】
    }



    Private function _alioffline ($data){
    	try {
		    switch ($data['channel']){
		        case 'BC_ALI_QRCODE':
		            $result = \beecloud\rest\api::bill($data);
		            break;
		        case 'ALI_OFFLINE_QRCODE':
		            $result =  \beecloud\rest\api::offline_bill($data);
		            break;
		        default:
		            exit('channel must be BC_ALI_QRCODE  or ALI_OFFLINE_QRCODE');
		            break;
		    }
		    if ($result->result_code != 0) {
		        pre($result);
		        exit();
		    }
		    $code_url = $result->code_url;
		} catch (Exception $e) {
		    echo $e->getMessage();
		    exit();
		}
		$this->code_url=$code_url;
		$adata = M('payorder')->where(array('bill_no'=>$data["bill_no"]))->find();
		// 拍品支付显示支付信息
		if($adata['purpose']=='auction'){
			$pinfo = D('Auction')->where(array('pid'=>$adata['useid']))->field('pname,pictures')->find();
			$pinfo['pictures'] = C('WEB_ROOT'). str_replace('./', '', C('UPLOADS_PICPATH')).getPicUrl($pinfo['pictures'],2,0);
			$oinfo =  M('goods_order')->where(array('order_no'=>$adata['order_no']))->find();
			$oinfo['total'] = $oinfo['price']+$oinfo['freight'];
			$this->pinfo = $pinfo;
			$this->oinfo = $oinfo;
		}
		$payment=C('payment.list');
        $this->chname=$payment[$data['channel']]['chname'];
		$this->code_url=$code_url;
		$this->adata=$adata;
		$this->display('wx_native');
    }
    
    Private function _native ($data){
    	try {
	        $result = \beecloud\rest\api::bill($data);
	        if ($result->result_code != 0) {
	        	pre($result);
	            exit();
	        }
	        $code_url = $result->code_url;
	    } catch (Exception $e) {
	        die($e->getMessage());
	    }
	    $adata = M('payorder')->where(array('bill_no'=>$data["bill_no"]))->find();
		// 拍品支付显示支付信息
		if($adata['purpose']=='auction'){
			$pinfo = D('Auction')->where(array('pid'=>$adata['useid']))->field('pname,pictures')->find();
			$pinfo['pictures'] = C('WEB_ROOT'). str_replace('./', '', C('UPLOADS_PICPATH')).getPicUrl($pinfo['pictures'],2,0);
			$oinfo =  M('goods_order')->where(array('order_no'=>$adata['order_no']))->find();
			$oinfo['total'] = $oinfo['price']+$oinfo['freight']+$oinfo['broker_buy'];
			$this->pinfo = $pinfo;
			$this->oinfo = $oinfo;
		}
		$payment=C('payment.list');
		$this->chname=$payment[$data['channel']]['chname'];
		$this->code_url=$code_url;
		$this->adata=$adata;

		$this->display('wx_native');
    }
    public function jsapi(){
    	pre('asdfads');
    	pre(I('post.'));
    	pre(I('get.'));
    	die;
    }
    // http://192.168.1.238/Payment/jsapi

    Private function _jsapi ($data) {
    	// 微信H5网页";
        /**
		 * 微信用户的openid获取请参考官方demo sdk和文档
		 * https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=11_1
		 * 微信获取openid php代码, 运行环境是微信内置浏览器访问时
		 *
		 * 注意:
		 *      请修改lib/WxPayPubHelper/WxPay.pub.config.php配置文件中的参数:
		 *      1.APPID, APPSECRET请修改为商户自己的微信参数(MCHID, KEY在beecloud平台创建的应用中配置);
		 *      2.JS_API_CALL_URL针对当前的demo,应该是http(s)://<your domain>/<your path>/pay.bill.php?type=WX_JSAPI,
		 *        可根据具体情况进行配置调整;
		 *      3.请检查方法createOauthUrlForCode是否对回调链接地址(redirect_uri)进行urlencode处理,如果没有请自行添加
		 *      3.特别要强调的是JS_API_CALL_URL的访问域名必须与微信公众平台配置的授权回调页面域名一致.
		 */
		Vendor('beecloud.WxPay.WxPayPubHelper');
		$jsApi = new \JsApi_pub();
		//网页授权获取用户openid
		//通过code获得openid
		if (!isset($_GET['code'])){
		    //触发微信返回code码
		    $url = $jsApi->createOauthUrlForCode(U('Home/Payment/online',array('channel'=>$data['channel'],'bill'=>$data["bill_no"]),'',true));
		    Header("Location: ".$url);
		    exit();
		} else {
		    //获取code码，以获取openid
		    $code = $_GET['code'];
		    $jsApi->setCode($code);
		    $openid = $jsApi->getOpenId();
		}
		$data["openid"] = $openid;
		try {
	        $result = \beecloud\rest\api::bill($data);
	        if ($result->result_code != 0) {
	            pre($result);
	            exit();
	        }
	        $jsApiParam = array(
	            "appId" => $result->app_id,
	            "timeStamp" => $result->timestamp,
	            "nonceStr" => $result->nonce_str,
	            "package" => $result->package,
	            "signType" => $result->sign_type,
	            "paySign" => $result->pay_sign
	        );
	    } catch (Exception $e) {
	        die($e->getMessage());
	    }
	    $adata = M('payorder')->where(array('bill_no'=>$data["bill_no"]))->find();
		// 拍品支付显示支付信息
		if($adata['purpose']=='auction'){
			$pinfo = D('Auction')->where(array('pid'=>$adata['useid']))->field('pname,pictures')->find();
			$pinfo['pictures'] = C('WEB_ROOT'). str_replace('./', '', C('UPLOADS_PICPATH')).getPicUrl($pinfo['pictures'],2,0);
			$oinfo =  M('goods_order')->where(array('order_no'=>$adata['order_no']))->find();
			$oinfo['total'] = $oinfo['price']+$oinfo['freight']+$oinfo['broker_buy'];
			$this->pinfo = $pinfo;
			$this->oinfo = $oinfo;
		}
		$this->jsApiParam =json_encode($jsApiParam);
		$this->adata=$adata;
		$this->display('wx_jsapi');
    }




 // public function test(){
 // 	$jssdk = new JSSDK(C('Weixin.appid'),C('Weixin.appsecret'));
 // 	// $jssdk->debug = true;
 // 	$signPackage = $jssdk->GetSignPackage();
 // 	$mvv['url'] = 'https://wxht.xakaiku.cn/connect/oauth2/authorize?appid='.C('Weixin.appid').'&redirect_uri='.urlencode(substr(C('WEB_ROOT'), 0, -1).U('Home/Wlogin/index')).'&response_type=code&scope=snsapi_base&state='.$mvv['mark'].'#wechat_redirect';

 // 	pre($signPackage);
	// die; 	
 	
 // 	$this->signPackage=$signPackage;
 // 	$this->display();
 // }



    public function showcach(){
    	pre('asdfasdf');
    	// pre(S('weixintest'));
    	pre(S('webhookmsg'));
    	pre('x');
    	pre(S('testmsgx'));
    	pre('y');
    	pre(S('testmsgy'));
    	pre('z');
    	pre(S('testmsgz'));
    	pre('a');
    	pre(S('testmsga'));
    	pre('b');
    	pre(S('testmsgb'));
    	pre('c');
    	pre(S('testmsgc'));
    	pre('d');
    	pre(S('testmsgd'));
    	pre('f');
    	pre(S('testmsgf'));
    	pre('f');
    	pre(S('testmsgf'));
    	// pre(S('testmsgb'));
    	// pre(S('testmsgc'));
    }

    public function webhook(){
    	/**
		 * http类型为 Application/json, 非XMLHttpRequest的application/x-www-form-urlencoded, $_POST方式是不能获取到的
		 */
		$appId = C('payment.app_id');
		$appSecret = C('payment.appSecret');
		$jsonStr = file_get_contents("php://input");
		$msg = json_decode($jsonStr);
		// webhook字段文档: /doc/php.php#webhook
		S('webhookmsg',$msg);

		// 验证签名
		$sign = md5($appId . $appSecret . $msg->timestamp);
		if ($sign != $msg->sign) {
		    // 签名不正确
		    exit();
		}
		$bill_no = $msg->transaction_id;
		$transaction_fee = $msg->transaction_fee;
		
		$thisOrder = M('payorder')->where(array('bill_no'=>$bill_no))->find();
		if(intval($thisOrder['money']*100) != intval($transaction_fee)){
			// 支付订单金额不匹配
			exit();
		}
		// 此处需要验证购买的产品与订单金额是否匹配:
		// 验证购买的产品与订单金额是否匹配的目的在于防止黑客反编译了iOS或者Android app的代码，
		// 将本来比如100元的订单金额改成了1分钱，开发者应该识别这种情况，避免误以为用户已经足额支付。
		// Webhook传入的消息里面应该以某种形式包含此次购买的商品信息，比如title或者optional里面的某个参数说明此次购买的产品是一部iPhone手机，
		// 开发者需要在客户服务端去查询自己内部的数据库看看iPhone的金额是否与该Webhook的订单金额一致，仅有一致的情况下，才继续走正常的业务逻辑。
		// 如果发现不一致的情况，排除程序bug外，需要去查明原因，防止不法分子对你的app进行二次打包，对你的客户的利益构成潜在威胁。
		// 如果发现这样的情况，请及时与我们联系，我们会与客户一起与这些不法分子做斗争。而且即使有这样极端的情况发生，
		// 只要按照前述要求做了购买的产品与订单金额的匹配性验证，在你的后端服务器不被入侵的前提下，你就不会有任何经济损失。

		if($msg->transactionType == "PAY") {
		    //付款信息
		    //支付状态是否变为支付成功
		    $result = $msg->tradeSuccess;
		    $member = M('member');
		    if($result){
		    	$status = M('payorder')->where(array('bill_no'=>$thisOrder['bill_no']))->getField('status');
		    	if ($status!=1) {
		    		// 设置支付订单为已支付
		    		M('payorder')->where(array('bill_no'=>$thisOrder['bill_no']))->setField('status',1);
		    		S(C('CACHE_FIX').$thisOrder['bill_no'],1);
		    		if($thisOrder['purpose']=='pledge'){
			    		$payorder = M('payorder');
			    			// 账户余额增加
				    		$payment = C('payment.list');
				    		$wallet = $member->where(array('uid'=>$thisOrder['uid']))->field('wallet_pledge,wallet_pledge_freeze')->find();
                        	$usable = sprintf("%.2f",$wallet['wallet_pledge']-$wallet['wallet_pledge_freeze']);
				    		$data = array(
				    			'order_no'=>$thisOrder['order_no'],
				    			'uid'=>$thisOrder['uid'],
				    			'changetype'=>'pay_deposit',
				    			'time'=>time(),
				    			'annotation'=>'【'.$payment[$thisOrder['paytype']]['chname'].'】在线充值'.$thisOrder['money'].'元，充值成功！',
				    			'income'=>$thisOrder['money'],
				    			'usable'=>sprintf("%.2f",$usable+$thisOrder['money']),
                                'balance'=>sprintf("%.2f",$wallet['wallet_pledge']+$thisOrder['money'])
				    			);

				    		// 记录账户记录
				    		$stay = M('member_pledge_bill')->add($data);
				    		
				    		// 增加账户余额
				    		$staz = $member->where(array('uid'=>$thisOrder['uid']))->setInc('wallet_pledge',$thisOrder['money']);
				    	// 提醒通知充值成功【
				    		// 微信提醒内容
			                $wei_pledge['tpl'] = 'walletchange';
			                $wei_pledge['msg']=array(
			                    "url"=>U('Member/wallet','','html',true), 
			                    "first"=>"您好，".$data['annotation'],
			                    "remark"=>'查看账户记录>>',
			                    "keyword"=>array('账户余额','在线充值','充值订单:'.$thisOrder['order_no'],'+'.$thisOrder['money'],$usable)
			                );
			                // 账户类型，操作类型、操作内容、变动额度、账户余额
			                // 站内信提醒内容
			                $web_pledge = array(
			                    'title'=>'在线充值',
			                    'content'=>$data['annotation']
			                    );
			                // 短信提醒内容
			                $note_pledge = $data['annotation'].'您可以登陆平台查看账户记录。';
			                // 邮箱提醒内容
			                $mail_pledge['title'] = '在线充值成功';
			                $mail_pledge['msg'] = '您好：<br/><p>'.$data['annotation'].'</p><p>您可以<a target="_blank" href="'.U('Home/Member/wallet','','html',true).'">查看账户记录</a></p>';

			                sendRemind($member,M('Member_weixin'),array(),array($thisOrder['uid']),$web_pledge,$wei_pledge,$note_pledge,$mail_pledge,'buy');
			            // 提醒通知充值成功【
			    	}
			    	if($thisOrder['purpose']=='auction'){
			    		$goods_order = M('goods_order');
		    			// 计算发货过期时间
		    			if(C('Order.losetime2')==0||C('Order.losetime2')==''){
			                $deftime2 = 0;
			            }else{
			                $losetime2=C('Order.losetime2');
			                $deftime2 = time()+(60*60*24*$losetime2);
			            }
			            // 设置已支付
			    		$goods_order->where(array('order_no'=>$thisOrder['order_no']))->setField(array('status'=>1,'time1'=>time(),'deftime2'=>$deftime2));
			    		// 读取订单信息
			    		$order_info = $goods_order->where(array('order_no'=>$thisOrder['order_no']))->find();
			    		// 读取商品信息
			    		$bidinfo = M('auction')->where(array('pid'=>$order_info['gid']))->find();
			    		// 当设置有余额支付时候操作
			    		if($thisOrder['yuemn']>0){
			    			// 扣除余额
		                    $yedata = D('Payment')->yuepaySend($bidinfo,$thisOrder['yuemn'],$thisOrder['order_no'],$thisOrder['uid']);
		                    if ($yedata['status']==0) {
		                    	echo $yedata['info'];
			                }
			    		}
			    		// 当设置有保证金支付时候操作
			    		if($thisOrder['pledge']>0){
							// 保证金抵货款操作
		            		$ppdata = D('Payment')->pledgepaySend($bidinfo,$thisOrder['pledge'],$thisOrder['order_no'],$thisOrder['uid']);
		                    if ($ppdata['status']==0) {
			                    echo $ppdata['info'];
			                }
			    		}
			    		// 解冻保证金和信用额度
			    		$b = payBidUnfreeze($thisOrder['order_no']);
			    		sendOrderRemind($thisOrder['order_no']);
			    	}
		    	}
		    }
		    //messageDetail 参考文档
		    // switch ($msg->channelType) {
		    //     case "WX":
		    //         /**
		    //          * 处理业务
		    //          */
		    //         break;
		    //     case "ALI":
		    //         break;
		    //     case "UN":
		    //         break;
		    // }
		}
		//打印所有字段
		// pre($msg);

		//处理消息成功,不需要持续通知此消息返回success
		echo 'success';
    }
    // 支付同步返回页面
    public function return_url(){
    	/**
		 * 支付宝 return_url 获取支付状态
		 */
		$aliTradeSuccess = ($_GET["trade_status"] == "TRADE_SUCCESS" || $_GET["trade_status"] == "TRADE_FINISH") ? true : false ;

		/**
		 * 银联 return_url 获取支付状态
		 */

		$unTradeSuccess = ($_POST["respCode"] == "00" && $_POST["respMsg"] == "success") ? true : false;
    }

// 查询微信扫码订单
    public function native_query(){
    	if(S(C('CACHE_FIX').$_POST["billNo"])){
    		echo json_encode(array('status'=>1));
    	}else{
    		echo json_encode(array('status'=>0));
    	}
    }
}