$(function() {
    // 主导航鼠标经过[全部分类]的鼠标事件
    var navTime;
    $("#Js-long-title").hover( 
        function(){$("#Js-category").show();},
        function(){navTime = setTimeout(function(){$("#Js-category").hide();},100);}
    );
    $("#Js-category").hover( 
        function(){clearInterval(navTime);$(this).show();},
        function(){$(this).hide();}
    );
    // 主导航鼠标经过[全部分类]下方子类的鼠标事件
    $(".Js_toggle").hover(function() {
        $(".category-content .category-list li.first .menu-in").css("display", "none");
        $(".category-content .category-list li.first").removeClass("hover");
        $(this).addClass("hover");
        $(this).children("div.menu-in").css("display", "block")
    }, function() {
        $(this).removeClass("hover")
        $(this).children("div.menu-in").css("display", "none")
    });
    // 分类页面的事件子类点击时间
    $(".Js_toggle_click").click(function() {      
        $(this).addClass("selected").siblings().removeClass("selected");
        var theight = $(this).children('.menu-item').height();
        var wheight = $(window).height();
        console.log(wheight);
        if (theight>=wheight) {
           $(this).parents('.Js-categoryB').height(theight);
        }else{
            $(this).parents('.Js-categoryB').height(wheight);
        }
    })
    $('.Js-categoryB').height($(window).height());
    // $('.Js-categoryB').height($('.Js-categoryB .first .menu-item').height());


// 用户卡片的显示
$(".ac-show-userbox").hover(
    function(){
        $(".aw-card-tips").hide();
        var selbuy = $(this).attr('selbuy');
        var cardInfoBox = $(this);
        // 获取用户信息
        if($(this).attr('cardtips')==0){
            $.post(getusercard,{'seller':$(this).attr('seller'),'uid':$(this).attr('uid'),'pid':$(this).attr('pid')},function(data){  //ajax提交到后台排序
                var cardinfo = '';
                if (data.status) {
                        // 店铺信息【
                        var selcard = '';
                        selcard+= '<div id="sel" class="aw-mod" ';
                        if (selbuy!='buy') {
                            selcard+= 'style="display:block;';
                        };
                       
                        selcard+= '"><div class="mod-head">';
                        selcard+='<a target="_blank" class="img" href="'+data.sel.url+'"><img src="'+data.sel.head+'"></a>';
                        selcard+='<p class="am-cf"><a target="_blank" class="name pull-left" href="'+data.sel.url+'">'+data.sel.name+'</a><i title="" class=" pull-left"></i></p>';
                        selcard+='<p class="aw-user-center-follow-meta"><span>店铺等级: <img src="'+data.sel.leval+'"></span></p>';
                        selcard+='<p class="am-cf"><a class="am-fr aw-card-shield';
                        if (data.sel.black!=0) {
                            selcard+=' on" black="1';
                        }else{
                            selcard+='" black="0';
                        }
                        selcard+='" xid="'+data.sel.uid+'" pilot="sel" href="javascript:void(0);"><span></span>不看该店拍品</a></p>'
                        selcard+='</div><div class="mod-body">'+data.sel.intr;
                        selcard+='</div>';
                        selcard+='<ul class="mod-footer am-avg-sm-2">';
                        selcard+='<li class="lybox_min am-cf"><a target="_blank" class="ly" href="'+data.sel.auctionurl+'"><i class="am-icon-envelope"></i><p class="txt">给我留言</p></a></li>';
                        if(data.sel.gzuser==0){
                            selcard+='<li class="gzbox_min ac-attention-box am-fr am-cf"><a sellerid='+data.sel.uid+' class="gz_sell gz selatt'+data.sel.uid+'" st="0" href="javascript:void(0);"><i class="am-icon-heart"></i><p class="txt">关注店铺</p></a></li>';
                        }else{
                            selcard+='<li class="gzbox_min ac-attention-box am-fr am-cf"><a sellerid='+data.sel.uid+' class="gz_sell gz selatt'+data.sel.uid+' on" st="1" href="javascript:void(0);"><i class="am-icon-heart"></i><p class="txt">取消关注</p></a></li>';
                        }
                        selcard+='</ul>';
                        selcard+='</div>';
                        // 店铺信息】
                        // 用户信息【
                        var buycard = '';
                        buycard+= '<div id="buy" class="aw-mod" ';
                        if (selbuy=='buy') {
                            buycard+= 'style="display:block;';
                        };           
                        buycard+= '"><div class="mod-head">';
                        buycard+='<a target="_blank" class="img" href="'+data.buy.url+'"><img src="'+data.buy.head+'"></a>';
                        buycard+='<p class="am-cf"><a target="_blank" class="name pull-left" href="'+data.buy.url+'">'+data.buy.name+'</a><i title="" class=" pull-left"></i></p>';
                        buycard+='<p class="aw-user-center-follow-meta"><span>用户等级: <img src="'+data.buy.leval+'"></span></p>';
                        buycard+='<p class="am-cf"><a class="am-fr aw-card-shield';
                        if (data.buy.black!=0) {
                            buycard+=' on" black="1';
                        }else{
                            buycard+='" black="0';
                        }
                        buycard+='" xid="'+data.buy.uid+'" pilot="buy" href="javascript:void(0);"><span></span>不让他看拍品</a></p>';
                        buycard+='</div><div class="mod-body">'+data.buy.intr;
                        buycard+='</div>';
                        // 用户信息】

                    cardinfo+= '<div class="aw-card-tips">';
                    // C2C运营
                    if (isbc!=1) {
                        cardinfo+= '<ul class="aw-card-menu lstit am-cf">';
                        if (selbuy!='buy') {
                            cardinfo+= '<li  class="selected">店铺信息</li><li>用户信息</li>';
                        }else{
                            cardinfo+= '<li>店铺信息</li><li class="selected">用户信息</li>';
                        }
                        cardinfo+= '</ul>';
                        cardinfo+= '<div class="aw-card-con">';
                        cardinfo+=selcard;
                        cardinfo+=buycard;
                    }else{
                    // B2C运营
                        if (selbuy!='buy') {
                            cardinfo +=selcard;
                        }else{
                            cardinfo +=buycard;
                        }
                    }
                    cardinfo+='</div></div>';
                    cardInfoBox.append(cardinfo);
                } else {
                    popup.error(data.msg);
                    setTimeout(function(){
                        popup.close("asyncbox_success");
                    },2000);
                }
            },'json');
            $(this).attr('cardtips',1);
            $(this).children(".aw-card-tips").show();
        }else{
            $(this).children(".aw-card-tips").show();
        }
    },
    function(){
        $(this).children(".aw-card-tips").hide();
    }
);
// 用户标签内选项卡操作
$(document).on('click','.aw-card-menu li',function(){
    $(this).addClass("selected").siblings().removeClass("selected");
    var card_con_index = $(this).index();
    $(this).parents('.aw-card-tips').children('.aw-card-con').find('.aw-mod').eq(card_con_index).show().siblings().hide();
    // $(".aw-card-con>div")
});
// 不让对方看拍品
$(document).on('click','.aw-card-shield',function(){
    if(login == 1){
        var thsck = $(this);
        var pilot = $(this).attr('pilot');
        var black = $(this).attr('black');
        var xid = $(this).attr('xid');
        $.post(blacklistUrl,{'xid':xid,'pilot':pilot,'black':black},function(data){
            if (data.status) {
                if (data.black==1) {
                    thsck.addClass('on');
                    // 用户列表显示文字替换
                    thsck.children('.Js-black-txt').html('取消屏蔽');
                }else{
                    // 用户列表显示文字替换
                    if (pilot=='buy') {
                        thsck.children('.Js-black-txt').html('屏蔽买家');
                    }else{
                        thsck.children('.Js-black-txt').html('屏蔽店铺');
                    }
                    // 用户列表该项删除
                    thsck.parents('.Js-user-listbox').remove();
                    thsck.removeClass('on');
                }
                thsck.attr('black',data.black);
                AMUI.dialog.alert({ title: data.title, content: data.msg});
            } else {
                AMUI.dialog.alert({ title: data.title, content: data.msg});
            }
        },'json');
    }else{
        AMUI.dialog.alert({ title: data.title, content: '您没有登陆！请登录...'});
    }
});
//用户关注拍品
    $('.auctionbox').on('mouseenter','.att',function(){
        if($(this).attr('yn')=='y'){
            $(this).html('取消');
        }
    });
    $('.auctionbox').on('mouseout','.att',function(){
        if($(this).attr('yn')=='y'){
            $(this).html('已关注');
        }
    });
    $('.auctionbox ').on("click",".att",function(){
        if(login == 1){
            var thisObj = $(this);
            var gid = $(this).attr('pid');
            var rela = $(this).attr('rela');
            var yn = $(this).attr('yn');
            $.post(attUrl,{'gid':gid , 'rela':rela, 'yn':yn},function(data){
                if (data.status) {
                    if(yn =='n'){
                        thisObj.addClass('on');
                        thisObj.html('已关注');
                        thisObj.attr('yn','y');
                    }else if(yn =='y'){
                        thisObj.removeClass('on');
                        thisObj.html('关注');
                        thisObj.attr('yn','n');
                    }
                } else {
                    AMUI.dialog.alert({ title: data.title, content: data.msg});
                }
            },'json');  
            
        }else{
            popup.alert('<div class="sayOnelin">您没有登录！请登录</div>');
        }
    });
// 关注店铺操作【
    $('body').on('mouseenter','.gz_sell',function(){
        if($(this).attr('st')=='1'){
            $(this).children('.txt').html('取消关注');
        }
    });
    $('body').on('mouseout','.gz_sell',function(){
        if($(this).attr('st')=='1'){
            $(this).children('.txt').html('已关注');
        }
    });
    if($('.gz_sell').attr('st')==1){
        $('.gz_sell').addClass('on');
    }
    $('body').on('click','.gz_sell',function(){
        var thisbj=$(this);
        var st = $(this).attr('st');
        var attsellerid =  $(this).attr('sellerid');
        var thisall =  $('.gz_sell.selatt'+attsellerid);
        $.post(setAttentionSellerUrl,{'sellerid':attsellerid,'st':st},function(data){
            if (data.status) {
                if(st==0){
                    thisall.children('.txt').html('已关注');
                    thisall.attr('st',1);
                    AMUI.dialog.alert({ title: data.title, content: data.msg});
                    thisall.addClass('on');
                }else{
                    thisall.children('.txt').html('关注店铺');
                    thisall.attr('st',0);
                    AMUI.dialog.alert({ title: data.title, content: data.msg});
                    thisall.removeClass('on');
                }
                
            } else {
                AMUI.dialog.alert({ title: data.title, content: data.msg});
            }
        },'json');
    });

// 关注店铺操作】
});
// 重置验证码
function restVerifyImg(){
    var newcode = $('.verifyImg').attr('src');
    $('.verifyImg').attr('src',newcode+'?rand='+Math.random());
    $('#verify_code').val('');
}



// 判断浏览器类型【
function myBrowser(){
    var userAgent = window.navigator.userAgent; //取得浏览器的userAgent字符串
    var isOpera = userAgent.indexOf("Opera") > -1; //判断是否Opera浏览器
    var isIE = userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1 && !isOpera; //判断是否IE浏览器
    var isFF = userAgent.indexOf("Firefox") > -1; //判断是否Firefox浏览器
    var isSafari = userAgent.indexOf("Safari") > -1; //判断是否Safari浏览器
    var isChrome = userAgent.indexOf("Chrome") >= 0;
    if (isIE) {
        var IE5 = IE55 = IE6 = IE7 = IE8 = false;
        var reIE = new RegExp("MSIE (\\d+\\.\\d+);");
        reIE.test(userAgent);
        var fIEVersion = parseFloat(RegExp["$1"]);
        IE55 = fIEVersion == 5.5;
        IE6 = fIEVersion == 6.0;
        IE7 = fIEVersion == 7.0;
        IE8 = fIEVersion == 8.0;
        if (IE55) {
            return "IE55";
        }
        if (IE6) {
            return "IE6";
        }
        if (IE7) {
            return "IE7";
        }
        if (IE8) {
            return "IE8";
        }
    }//isIE end
    if (isFF) {
        return "FF";
    }
    if (isOpera) {
        return "Opera";
    }
    if (isChrome) {
        return "Chrome";
    }
}//myBrowser() end
// 判断浏览器类型】
//罗马数字转换为汉字【
function convertCurrency(money) {
  //汉字的数字
  var cnNums = new Array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
  //基本单位
  var cnIntRadice = new Array('', '拾', '佰', '仟');
  //对应整数部分扩展单位
  var cnIntUnits = new Array('', '万', '亿', '兆');
  //对应小数部分单位
  var cnDecUnits = new Array('角', '分', '毫', '厘');
  //整数金额时后面跟的字符
  var cnInteger = '整';
  //整型完以后的单位
  var cnIntLast = '元';
  //最大处理的数字
  var maxNum = 999999999999999.9999;
  //金额整数部分
  var integerNum;
  //金额小数部分
  var decimalNum;
  //输出的中文金额字符串
  var chineseStr = '';
  //分离金额后用的数组，预定义
  var parts;
  if (money == '') { return ''; }
  money = parseFloat(money);
  if (money >= maxNum) {
    //超出最大处理数字
    return '';
  }
  if (money == 0) {
    chineseStr = cnNums[0] + cnIntLast + cnInteger;
    return chineseStr;
  }
  //转换为字符串
  money = money.toString();
  if (money.indexOf('.') == -1) {
    integerNum = money;
    decimalNum = '';
  } else {
    parts = money.split('.');
    integerNum = parts[0];
    decimalNum = parts[1].substr(0, 4);
  }
  //获取整型部分转换
  if (parseInt(integerNum, 10) > 0) {
    var zeroCount = 0;
    var IntLen = integerNum.length;
    for (var i = 0; i < IntLen; i++) {
      var n = integerNum.substr(i, 1);
      var p = IntLen - i - 1;
      var q = p / 4;
      var m = p % 4;
      if (n == '0') {
        zeroCount++;
      } else {
        if (zeroCount > 0) {
          chineseStr += cnNums[0];
        }
        //归零
        zeroCount = 0;
        chineseStr += cnNums[parseInt(n)] + cnIntRadice[m];
      }
      if (m == 0 && zeroCount < 4) {
        chineseStr += cnIntUnits[q];
      }
    }
    chineseStr += cnIntLast;
  }
  //小数部分
  if (decimalNum != '') {
    var decLen = decimalNum.length;
    for (var i = 0; i < decLen; i++) {
      var n = decimalNum.substr(i, 1);
      if (n != '0') {
        chineseStr += cnNums[Number(n)] + cnDecUnits[i];
      }
    }
  }
  if (chineseStr == '') {
    chineseStr += cnNums[0] + cnIntLast + cnInteger;
  } else if (decimalNum == '') {
    chineseStr += cnInteger;
  }
  return chineseStr;
}
//罗马数字转换为汉字】











	
	
	
	
	