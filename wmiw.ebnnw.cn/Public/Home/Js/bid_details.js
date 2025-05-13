
// 初始化代理提示
var loseralert = 0;
var endDowntimer;
$(function(){
    // 上一个加一下拍品【
    $('.pm-item-arrow').on("mouseover mouseout",function(event){
        var exeobj =$(this).children('.item-imgCon');
        if(event.type == "mouseover"){
           exeobj.show();
        }else if(event.type == "mouseout"){
            setTimeout(function(){
                if (exeobj.attr('data-ex')=='hide') {
                    exeobj.hide();
                };
            },500);
        }
    });
    $('.item-imgCon').on("mouseover mouseout",function(event){
        if(event.type == "mouseover"){
           $(this).show();
           $(this).attr('data-ex','show');
        }else if(event.type == "mouseout"){
            $(this).attr('data-ex','hide');
            setTimeout(function(){

                $(this).hide();
            },500);
        }
    });
    // 上一个加一下拍品】

    if(endStatus==0){
        // 初始化拍卖记录状态
        $(".Js-record-list .r-list:eq(0)").addClass('lingxian').find('.bidlistico').html('领先');
        $(".Js-allrecord.Js-record-list .r-list:eq(0)").addClass('lingxian').find('.bidlistico').html('领先');
    }else if(endStatus==1){
        $(".Js-record-list .r-list:eq(0)").addClass('chengjiao').find('.bidlistico').html('成交');
        $(".Js-allrecord.Js-record-list .r-list:eq(0)").addClass('chengjiao').find('.bidlistico').html('成交');
    }
    // 加减步长【
    $('.Js-change_step').click(function(){
        var changObj = $(this).parents('.Js-change_box').find('input');
        var originalPric = Number(changObj.val());
        var thisHow = $(this).attr('how');
        var thisAct = $(this).attr('act');
        // 出价框减步长值
        var lval  = (originalPric-Number(steplin)).toFixed(2)*100/100;
        // 当前价加步长值
        var rval = (Number(nowPrice)+Number(steplin)).toFixed(2)*100/100;
        var changval = originalPric;
        if(thisAct=='minus'){
            if (thisHow == 'manual') {
                if ((Number(nowUid) != 0 && lval>=rval) || (Number(nowUid) == 0 && lval>=Number(nowPrice))) {
                    changval = lval;
                }else{
                    AMUI.dialog.alert({ title: '', content: '出价不能低于当前价！'}); 
                };
            };
            if (thisHow == 'robot') {
                if (setagency==3) {
                    if (lval>=Number(myagprice)) {
                        changval = lval;
                    }else{
                        AMUI.dialog.alert({ title: '', content: '代理出价不能低于'+myagprice}); 
                    };
                }else{
                    AMUI.dialog.alert({ title: '', content: '代理出价正在运行中，如需更改代理价请先停止代理出价！'});
                }
                
            };
        }else if($(this).attr('act')=='add'){
            if (thisHow == 'robot' && setagency!=3) {
                AMUI.dialog.alert({ title: '', content: '代理出价正在运行中！<br/>如需更改代理价请先停止代理出价。'});
            }else{
                changval = (originalPric+Number(steplin)).toFixed(2)*100/100
            }
        }
        changObj.val(changval);
    });
    // 加减步长】
    // 详情页关注拍品操作【
    $('.attTixingBox').on('mouseenter','.Js-follow',function(){
        if($(this).attr('st')=='1'){
            $(this).children('p').html('取消关注');
        }
    });
    $('.attTixingBox').on('mouseout','.Js-follow',function(){
        if($(this).attr('st')=='1'){
            $(this).children('p').html('已关注');
        }
    });
    if($('.Js-follow').attr('st')==1){
        $('.Js-follow').addClass('on');
    }
    $('.Js-follow').click(function(){
        if(checkCodemap()){
            var thisbj=$(this);
            var st = $(this).attr('st');
            $.post(setAttentionUrl,{'pid':acpid,'uid':ws_my_uid,'st':st},function(data){
                if (data.status) {
                    if(st==0){
                        thisbj.children('p').html('已关注');
                        thisbj.attr('st',1);
                        AMUI.dialog.alert({ title: '', content: data.msg}); 
                        thisbj.addClass('on');
                    }else{
                        thisbj.children('p').html('关注');
                        thisbj.attr('st',0);
                        AMUI.dialog.alert({ title: '', content: data.msg}); 
                        thisbj.removeClass('on');
                    }
                    
                } else {
                    AMUI.dialog.alert({ title: '', content: data.msg}); 
                }
            },'json');
        }
        
    });
    // 详情页关注拍品操作】
    // 详情页拍品提醒操作【
    $('.attTixingBox').on('mouseenter','.Js-remind',function(){
        if($(this).attr('st')=='1'){
            $(this).children('p').html('取消提醒');
        }
    });
    $('.attTixingBox').on('mouseout','.Js-remind',function(){
        if($(this).attr('st')=='1'){
            $(this).children('p').html('已设提醒');
        }
    });
    if($('.Js-remind').attr('st')==1){
        $('.Js-remind').addClass('on');
    }
    $('.attTixingBox').on('click','.Js-remind',function(){
        if(checkCodemap()){
            changtx();
        }
    });
    $('.attTixingBox').on('click','#setremind',function(){
        if(login==0){
            AMUI.dialog.alert({ title: '', content: '请先登陆后在设置提醒！'}); 
        }else{
            if(checkCodemap()){
                asyncbox.open({
                    id : "open_0",
                    title : '设置提醒',
                    args : {pid:acpid},
                    modal : true,
                    buttons : asyncbox.btn.OKCANCEL,
                    url : setTixingUrl,
                    callback : handler 
                })
            }
            
        }
    });
    // 是否提示关注公众号
    function checkCodemap(){
        // 是微信端打开
        if(iswei==1){
            // 已经开启关注提醒
            if(mapstate!=1&&codemap==1){
                $('#codeMapModal').modal();
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }
    function changtx(){
        var thisbj=$('.Js-remind');
        var st = $('.Js-remind').attr('st');
        var stype = $('.Js-remind').attr('stype');
        $.post(setScheduledUrl,{'pid':acpid,'uid':ws_my_uid,'st':st,'stype':stype},function(data){
            if (data.status) {
                if(st==0){
                    thisbj.children('p').html('已设提醒');
                    thisbj.attr('st',1);
                    AMUI.dialog.alert({ title: '', content: data.info});
                    thisbj.addClass('on');
                }else{
                    thisbj.children('p').html('设置提醒');
                    thisbj.attr('st',0);
                    AMUI.dialog.alert({ title: '', content: data.info});
                    thisbj.removeClass('on');
                }
                
            } else {
                AMUI.dialog.alert({ title: '', content: data.info});
            }
        },'json');
    }
    function handler(buttonResult,contentWindow,returnValue){
        if(buttonResult=='ok'){
            if(returnValue){
                $.post(setTixingUrl,returnValue,function(data){
                    if (data.status) {
                        $('.Js-remind').attr("id",'tx');
                        changtx();
                    } else {
                        AMUI.dialog.alert({ title: '', content: data.info});
                    }
                },'json');
            }else{
                AMUI.dialog.alert({ title: '', content: '请选择提醒方式！'});
            }
        }
        
        
    }
    // 详情页拍品提醒操作】
    // 手动出价
    $('.Js-manual-post_but').click(function(){
        postbid(acpid,ws_my_uid,'sd',$('.Js-bidprice').val());
    });

    // 一口价成交
    $('.Js-ykj-post_but').click(function(){
        $('.Js-bidprice').val(succPrice);
        postbid(acpid,ws_my_uid,'sd',succPrice);
    });


    // 手动和代理窗口切换
    $('.Js-robot-tab-btn').click(function(){
        $(this).parents('.Js-manual-tab').css('display','none');
        $('.Js-robot-tab').css('display','block');
    });
    // 代理和手动窗口切换
    $('.Js-manual-tab-btn').click(function(){
        $(this).parents('.Js-robot-tab').css('display','none');
        $('.Js-manual-tab').css('display','block');
    });
    // 启动和关闭代理出价
    $('.Js-robot-post_but').click(function(){
        var thisbegin = $(this);
        // 未设置代理
        if(setagency==3){
            $.post(bidUrl,{'pid':acpid,'uid':ws_my_uid,'bidType':'zd','bidPric':$('.Js-robotprice').val()},function(data){
                if (data.status) {
                    // 有权限进行设置出价
                    if(data.status==1){
                        // 设置代理成功
                        if(data.thisS.agency_succ){
                            if(data.thisS.agency_succ.uid == ws_my_uid){
                                AMUI.dialog.alert({ title: '', content: data.msg});
                                agency_butstyl(0);
                            }
                        }
                        // 失败代理检查
                        agency_loser(data.thisS.agency_loser);
                        if(data.thisS.recordList){
                            ws.send(JSON.stringify({"type":"bid","thisS":data.thisS}));
                            // 异步发送价格被超越提醒【
                            $.post(send_remind,{'pid':acpid});
                            // 异步发送价格被超越提醒【
                        }
                        // 变更受影响的其他拍品时间
                        if(mid&&data.thisS.drive){
                            ws.send(JSON.stringify({"type":"drive","pre":ws_rom_pre,"data":data.thisS.drive}));
                        }
                    }else if(data.status==3){
                        AMUI.dialog.confirm({ title: '缴纳保证金', content: data.msg, 
                            onConfirm: function() {
                                $.post(bidUrl,{'pid':acpid,'uid':ws_my_uid,'bidType':'zd','bidPric':$('.Js-robotprice').val(),'agr':1},function(data){
                                    if(data.status==1){
                                        // 设置代理成功
                                        if(data.thisS.agency_succ){
                                            if(data.thisS.agency_succ.uid == ws_my_uid){
                                                AMUI.dialog.alert({ title: '', content: data.msg});
                                                agency_butstyl(0);
                                            }
                                        }
                                        if(data.thisS.recordList){
                                            ws.send(JSON.stringify({"type":"bid","thisS":data.thisS}));
                                            // 异步发送价格被超越提醒【
                                            $.post(send_remind,{'pid':acpid});
                                            // 异步发送价格被超越提醒【
                                            // 异步发送价格被超越提醒【
                                            $.post(freeze_remind,{'pid':acpid,'uid':ws_my_uid});
                                            // 异步发送价格被超越提醒【
                                        }
                                        // 变更受影响的其他拍品时间
                                        if(mid&&data.thisS.drive){
                                            ws.send(JSON.stringify({"type":"drive","pre":ws_rom_pre,"data":data.thisS.drive}));
                                        }
                                    }else if(data.status==2){
                                        $('.Js-robotprice').removeAttr("disabled");
                                        AMUI.dialog.alert({ title: '', content: data.msg});
                                    }
                                },'json');
                            }, 
                            onCancel: function() {
                                $('.Js-robotprice').removeAttr("disabled");
                            }
                        });
                    }else if(data.status==4){
                        // 实名认证和手机认证
                        $('.Js-robotprice').removeAttr("disabled");
                        AMUI.dialog.confirm({ title: data.title, content: data.msg, 
                            onConfirm: function() {
                                if (data.skipurl) {window.location.href = data.skipurl+'?rand='+Math.random();};
                            }, 
                            onCancel: function() {}
                        });
                    }else{
                        // 状态2：出价小于阶梯价，5：保证金不足
                        $('.Js-robotprice').removeAttr("disabled");
                        AMUI.dialog.alert({ title: '', content: data.msg});
                        alert(data.url);

                        if (data.url) {
                            setTimeout(function(){
                                window.location.href=window.location.href+"?id="+10000*Math.random();
                            },1000);
                        }
                    }
                } else {
                    AMUI.dialog.alert({ title: '', content: data.msg});
                }
            },'json'); 

        // 已设置代理取消代理出价
        }else{
            // 取消代理出价
            $.post(cancelAgency,{'pid':acpid,'uid':ws_my_uid},function(data){
                if (data.status) {
                    AMUI.dialog.alert({ title: '', content:'已取消代理出价！'});
                    agency_butstyl(3);
                } else {
                    AMUI.dialog.alert({ title: '', content:'取消代理出价失败！'});
                }
            },'json');
        }

        
    });
    // 内容选项卡
    $('#extcon_menu').on('click','li',function(){
          $(this).addClass("on").siblings().removeClass("on");
          var div_index = $(this).index();

          $("#extcon_content>div").eq(div_index).show().siblings().hide();

    });
    // 测试
    $('#test').on('click','a',function(){
        clearInterval(endDowntimer);
    });

});

// 建立链接完成执行的操作
    function loadsucc(){
        // 代理结束通知【
        if(setagency==2||setagency==1){
            if(setagency==2){
                var agencymsg = '您设置的代理价'+myagprice+'元被其他用户超越。<br/>代理已取消！';
            }else if(setagency==1){
                var agencymsg = '已达到您设置的代理价'+myagprice+'元。<br/>代理已取消！';
            }
            AMUI.dialog.confirm({ title: '代理结束', content: agencymsg, 
                onConfirm: function() {
                    $.post(iknowurl,{'pid':acpid,'uid':ws_my_uid},function(data){
                        if (data.status) {
                            AMUI.dialog.alert({ title: '', content: '已取消代理出价！'}); 
                            agency_butstyl(3);
                        } else {
                            AMUI.dialog.alert({ title: '', content: '取消代理出价失败！'}); 
                        }
                    },'json');
                }, 
                onCancel: function() {}
            });
        }
        // 代理结束通知】


        // 如果是拍卖会提示是否进入正在拍卖的拍品
        if(acpid!=mtnowpid && mtstatus=='ing'){
            AMUI.dialog.confirm({ title: '跳转提示', content: '拍卖会<strong>《'+mname+'》</strong>正在进行中！<br>是否进入进入在拍拍品？', 
                onConfirm: function() {
                    window.location.href = mtnowUrl+'?rand='+Math.random();
                    return false;
                }, 
                onCancel: function() {}
             }); 
        }else if(mtstatus=='end'){
            AMUI.dialog.confirm({ title: '跳转提示', content: '该拍品所属<strong>《'+mname+'》</strong>拍卖会已结束！<br>是否查看拍卖结论书？', 
                onConfirm: function() {
                    window.location.href = conclusion+'?rand='+Math.random();
                    return false
                }, 
                onCancel: function() {}
             }); 
        }
    }



// web_socket【
    // ajax出价
    function postbid(postpid,postuid,posttype,postprice){
        $('.Js-bidprice').attr("disabled", 'disabled');
        $.post(bidUrl,{'pid':postpid,'uid':postuid,'bidType':posttype,'bidPric':postprice},function(data){
            if (data.status) {
                if(data.status==1){
                    ws.send(JSON.stringify({"type":"bid","thisS":data.thisS}));
                    // 异步发送价格被超越提醒【
                    $.post(send_remind,{'pid':postpid});
                    // 异步发送价格被超越提醒【
                    // 变更受影响的其他拍品时间
                    if(mid&&data.thisS.drive){
                        ws.send(JSON.stringify({"type":"drive","pre":ws_rom_pre,"data":data.thisS.drive}));
                    }
                }else if(data.status==3){
                    AMUI.dialog.confirm({ title: '温馨提示', content: data.msg, 
                        onConfirm: function() {
                            $.post(bidUrl,{'pid':acpid,'uid':postuid,'bidType':'sd','bidPric':$('.Js-bidprice').val(),'agr':1},function(data){
                                if (data.status) {
                                    if(data.status==1){
                                        ws.send(JSON.stringify({"type":"bid","thisS":data.thisS}));
                                        // 异步发送价格被超越提醒【
                                        $.post(send_remind,{'pid':postpid});
                                        // 异步发送价格被超越提醒【
                                        // 异步发送价格被超越提醒【
                                        $.post(freeze_remind,{'pid':acpid,'uid':postuid});
                                        // 异步发送价格被超越提醒【
                                        // 变更受影响的其他拍品时间
                                        if(mid&&data.thisS.drive){
                                            ws.send(JSON.stringify({"type":"drive","pre":ws_rom_pre,"data":data.thisS.drive}));
                                        }
                                    }else if(data.status==2){
                                        $('.Js-bidprice').removeAttr("disabled");
                                        AMUI.dialog.alert({ title: '', content:data.msg});
                                    }else if(data.status==3){
                                        popup.alert(agencymsg,'拍品结束',function(action){
                                            if(action == 'ok'||action == 'close'){
                                                
                                                window.location.href=window.location.href+"?id="+10000*Math.random();
                                            }
                                        });
                                    }
                                }
                            },'json');
                        }, 
                        onCancel: function() {
                            $('.Js-bidprice').removeAttr("disabled");
                        }
                     }); 
                }else if(data.status==4){
                    // 实名认证和手机认证
                    $('.Js-bidprice').removeAttr("disabled");
                    AMUI.dialog.confirm({ title: data.title, content: data.msg, 
                        onConfirm: function() {
                            if (data.skipurl) {window.location.href = data.skipurl+'?rand='+Math.random();};
                        }, 
                        onCancel: function() {}
                    });
                }else{
                    // 状态2：出价小于阶梯价，5：保证金不足
                    $('.Js-robotprice').removeAttr("disabled");
                    AMUI.dialog.alert({ title: '', content: data.msg});
                }
            } else {
                AMUI.dialog.alert({ title: '', content: data.msg});
            }
        },'json'); 
    }

    // 连接服务端
    function connect() {
        // 创建websocket
        ws = new WebSocket("ws://"+document.domain+":7272");
        // 当socket连接打开时，输入用户名
        ws.onopen = onopen;

        // 当有消息时根据消息类型显示不同信息
        ws.onmessage = onmessage; 
        ws.onclose = function() {
            console.log("连接关闭，定时重连");
            ws_login = 0;
            connect();
        };
        ws.onerror = function() {
            ws_login = 0;
            $loading.modal('close');
            if (linkerr_alert == 0) {
                AMUI.dialog.alert({ title: '建立链接失败', content: '请刷新页面重试！', onConfirm: function() { window.location.href=window.location.href; } }); 
                linkerr_alert = 1;
            };
            console.log("出现错误");
        };
    }

    // 连接建立时发送登录信息
    function onopen(){
        // 登录
        if (ws_login == 0) {
            var login_data = '{"type":"login","client_name":"'+ws_my_name+'","client_avatar":"'+ws_my_avatar+'","room_id":"'+ws_rom_pre+nstatus+acpid+'"}';
            console.log("websocket握手成功，发送登录数据:"+login_data);
            ws.send(login_data);
        };
        
    }
    // 服务端发来消息时
    function onmessage(e){
        // console.log(e.data);
        var data = eval("("+e.data+")");
        switch(data['type']){
            // 服务端ping客户端
            case 'ping':
                ws.send('{"type":"pong","domain":'+domain+'}');
                break;
            // 登录 更新用户列表
            case 'login':
                //{"type":"login","client_id":xxx,"client_name":"xxx","client_list":"[...]","time":"xxx"}
                ws_login = 1;
                // 关闭链接等待
                $loading.modal('close');
                // 关闭链接失败初模态窗口验证
                $('.am-modal-alert').remove();
                $('.am-dimmer').hide();
                linkerr_alert =0;
                // 建立链接后执行的操作
                loadsucc();
                // 发送登陆信息
                say(data['client_id'], data['client_name'], data['client_avatar'],  data['client_name']+' 进入拍场', data['time']);
                if(data['client_list']){
                    client_list = data['client_list'];
                }
                else{
                    client_list[data['client_id']] = data['client_name']; 
                }
                // 更新用户列表
                flush_client_list();
                console.log(data['client_name']+"登录成功");
                break;
            // 发言
            case 'say':
                //{"type":"say","from_client_id":xxx,"to_client_id":"all/client_id","content":"xxx","time":"xxx"}
                say(data['from_client_id'], data['from_client_name'],data['from_client_avatar'], data['content'], data['time']);
                break;
            // 出价
            case 'bid':
                bidChange(data.thisS);
                break;
            // 时间变更
            case 'drive':
                if(data.change.action=='cancel'||data.change.action=='uptime'){
                    if(nstatus=='ing'){
                        clearInterval(endDowntimer);
                        calibrationEndtime(data.change.endtime,data.change.nowtime);
                    }else if(nstatus=='fut'){
                        clearInterval(startDowntimer);
                        calibrationStarttime(data.change.starttime,data.change.nowtime);
                    }
                }else if(data.change.action=='delete'){
                    auctionDeleted();
                }
                break;
            // 用户退出 更新用户列表
            case 'logout':
                //{"type":"logout","client_id":xxx,"time":"xxx"}
                say(data['from_client_id'], data['from_client_name'],data['from_client_avatar'], data['from_client_name']+' 退出了', data['time']);
                delete client_list[data['from_client_id']];
                flush_client_list();
        }
    }

    // 提交对话
    function onSubmit() {
      var input = document.getElementById("textarea");
      var to_client_id = $("#client_list option:selected").attr("value");
      var to_client_name = $("#client_list option:selected").text();
      ws.send('{"type":"say","to_client_id":"'+to_client_id+'","to_client_name":"'+to_client_name+'","content":"'+input.value+'"}');
      input.value = "";
      input.focus();
    }
// 刷新用户列表框
    function flush_client_list(){
        var userlist_window = $("#userlist ul");
        var client_list_slelect = $("#client_list");
        userlist_window.empty();
        client_list_slelect.empty();
        client_list_slelect.append('<option value="all" id="cli_all">所有人</option>');
        for(var p in client_list){
            userlist_window.append('<li class="am-text-truncate" id="'+p+'">'+client_list[p]+'</li>');
            client_list_slelect.append('<option value="'+p+'">'+client_list[p]+'</option>');
        }
        $("#client_list").val(select_client_id);
    }
    // 发言
    function say(from_client_id, from_client_name,from_client_avatar, content, time){
        $("#dialog").append('<div class="speech_item"><div class="item_head am-cf"><img src="'+from_client_avatar+'" class="user_icon" /> <div class="head_con">'+from_client_name+' <span class="tm">'+time+'</span></div></div><p class="triangle-isosceles"><img src="'+ltnr+'" class="ltnr" />'+content+'</p> </div>');
        var speed=200;//滑动的速度
        $('.caption_box').animate({ scrollTop: $('#dialog').height()}, speed);
    }
    $(function(){
        select_client_id = 'all';
        $("#client_list").change(function(){
             select_client_id = $("#client_list option:selected").attr("value");
        });
    });
// web_socket】

// 更新页面信息
function bidChange(data){
    // 竞拍出价
    if(bidtype == 0){
        bidCount=data.bidcount;
        steplin = data.stepsize;
        nowPrice = data.nowprice;
        nowUid = data.uid;
        $('.Js-nowprice-box').html('<span><em>¥</em><b class="sys_item_price">'+data.nowprice+'</b></span>');
        $('.r-none').remove();
        $('.Js-bidprice').removeAttr("disabled");
        $('.Js-bidprice').val(data.bidprice);
        $('.Js-stped').html(data.stped);
        $('.Js-steps').html(data.stepsize);
        $('.Js-bidcount').html(data.bidcount);
        $('.nobody').remove();
        $('.r-list.lingxian').removeClass('lingxian').find('.bidlistico').html('出局');
        var bid_item = '';
        $.each(data.recordList,function(drk,drv){
            bid_item +='<div title="'+drv.time+'" uid="'+drv.uid+'" class="r-list"><div class="r-avatar"><img src="'+drv.avatar+'"></div><div class="r-info"><div class="r-top am-cf"><div class="r-nickname"><a href="javascript:void(0);">'+drv.nickname+'</a></div><div class="r-status am-cf">';
            if(drv.type=='代理'){
                bid_item += '<div title="代理出价" class="agency_ico"></div>';
            }                    
            bid_item +='<div class="bidlistico">出局</div></div></div><div class="r-bottom am-cf"><div class="r-price">￥'+drv.bided+'</div><div class="r-time">'+drv.time+'</div></div></div></div>';
            // 我的出价
            if(drv.uid==ws_my_uid){
                AMUI.dialog.alert({ title: '', content:'出价成功'});
                my_item ='<div title="'+drv.time+'" uid="'+drv.uid+'" class="r-list"><div class="r-info"><div class="r-top am-cf"><div class="r-add">加价：￥'+drv.money+'</div><div class="r-status am-cf">';
                if(drv.type=='代理'){
                    my_item += '<div title="代理出价" class="agency_ico"></div>';
                } 
                my_item +='<div class="bidlistico"></div></div></div><div class="r-bottom am-cf"><div class="r-price">￥'+drv.bided+'</div><div class="r-time">'+drv.time+'</div></div></div></div>';
                // 更新我的出价列表
                $('.Js-myrecord-bidlimet').prepend(my_item);
                if($('.Js-myrecord-bidlimet .r-list').size()>4){
                    $('.Js-myrecord-bidlimet .r-list:eq(4)').nextAll().remove();
                }
                
                // 我的出价次数加1
                $('.Js-mycount').html(parseInt($('.Js-mycount').html())+1);
            }
            $('#Js-lead img').attr('src',drv.avatar);
            $('#Js-lead .ld-add').html('+￥'+drv.money);
            $('#Js-lead .ld-name').html(drv.nickname);
        });
// 更新出价记录列表
        $('.Js-record-list').prepend(bid_item);
        if($('.Js-bidlimet .r-list').size()>4){
            $('.Js-bidlimet .r-list:eq(4)').nextAll().remove();
        }
        
        // 设置领先样式
        
        $('.Js-record-list .r-list:eq(0)').addClass('lingxian').find('.bidlistico').html('领先');
        $('.Js-allrecord.Js-record-list .r-list:eq(0)').addClass('lingxian').find('.bidlistico').html('领先');
        if (nowUid==ws_my_uid) {
            $('.Js-myrecord-bidlimet .r-list:eq(0)').addClass('lingxian').find('.bidlistico').html('领先');
        }else{
            $('.Js-myrecord-bidlimet .r-list:eq(0)').removeClass('lingxian').find('.bidlistico').html('出局');
        }
        // 判断该用户代理是否失效【
        agency_loser(data.agency_loser);
        // 判断该用户代理是否失效】
        // 更新结束时间
        clearInterval(endDowntimer);
        calibrationEndtime(data.endtime,data.nowtime);
    // 竞标出价
    }else if(bidtype == 1){
        bidCount=data.bidcount;
        $('.Js-bidcount').html(data.bidcount);
        $('.nobody').remove();
        var bid_item = '';
        $.each(data.recordList,function(drk,drv){
            bid_item +='<div title="'+drv.time+'" uid="'+drv.uid+'" class="r-list"><div class="r-avatar"><img src="'+drv.avatar+'"></div><div class="r-info"><div class="r-top am-cf"><div class="r-nickname"><a href="javascript:void(0);">***</a></div><div class="r-status am-cf">';
            bid_item +='</div></div><div class="r-bottom am-cf"><div class="r-price">***</div><div class="r-time">'+drv.time+'</div></div></div></div>';
            // 我的出价
            if(data.uid==ws_my_uid){
                my_item ='<div title="'+drv.time+'" uid="'+drv.uid+'" class="r-list"><div class="r-info"><div class="r-top am-cf"><div class="r-add">加价：￥'+drv.money+'</div><div class="r-status am-cf">';
                my_item +='<div class="bidlistico"></div></div></div><div class="r-bottom am-cf"><div class="r-price">￥'+drv.bided+'</div><div class="r-time">'+drv.time+'</div></div></div></div>';
                $('.Js-myrecord-bidlimet').prepend(my_item);
                 if($('.Js-myrecord-bidlimet .r-list').size()>4){
                    $('.Js-myrecord-bidlimet .r-list:eq(4)').nextAll().remove();
                }
                // 我的出价次数加1
                $('.Js-mycount').html(parseInt($('.Js-mycount').html())+1);
            }
        });
        // 全部部分出价
        $('.Js-record-list').prepend(bid_item);
        if($('.Js-bidlimet .r-list').size()>4){
            $('.Js-bidlimet .r-list:eq(4)').nextAll().remove();
        }
        
    }
}


// 结束倒计时
function endDown(etime,ntime,boxobj,day_elem,hour_elem,minute_elem,second_elem,msec_elem){
    var now_time = new Date(ntime*1000);
    var end_time = new Date(etime*1000);
    var native_time = new Date().getTime(); //本地时间
    var now_cha = now_time - native_time; //服务器和本地时间差
    var native_end_time = end_time - now_cha; //本地结束时间
    var sys_second = 0;
    endDowntimer = setInterval(function(){
        // 检查本地时间是否更改
        if(Math.abs(native_time - new Date().getTime())>5000){
            clearInterval(endDowntimer);
            $.post(ajaxGetTime, {'pid':acpid},function(data){
                calibrationEndtime(data.endtime,data.nowtime);
            });
        }
        sys_second = (native_end_time - new Date().getTime())/100; //本地结束剩余时间
        if (sys_second > 0) {
            sys_second -= 1;
            var day = Math.floor((sys_second / 36000) / 24);
            var hour = Math.floor((sys_second / 36000) % 24);
            var minute = Math.floor((sys_second / 600) % 60);
            var second = Math.floor((sys_second/10) % 60);
            var msec = Math.floor(sys_second % 10); //毫秒
            day_elem && $(day_elem).text(day);//计算天
            $(hour_elem).text(hour<10?"0"+hour:hour);//计算小时
            $(minute_elem).text(minute<10?"0"+minute:minute);//计算分
            $(second_elem).text(second<10?"0"+second:second);// 计算秒
            $(msec_elem).text(msec);// 计算秒的1/10
            native_time = new Date().getTime();
        } else { 
            clearInterval(endDowntimer);
            // 本地时间结束提交服务器验证是否结束
            $.post(ajaxCheckSucc, {'pid':acpid},function(data){
                if(data.status==0){
                    calibrationEndtime(data.end_time,data.now_time);
                }else{
                    clearInterval(endDowntimer);
                    if(data.status==1){
                        $('#bidTimeStatus').remove();
                        $(boxobj).parents('.onBidTbox').html('<div class="into">拍卖已结束...</div>');
                        var user = data.nickname;
                        if(data.uid==ws_my_uid){user ='您';}
                        var msg = '恭喜'+user+'以'+data.money+'元，拍到《'+data.pname+'》';
                    }else if (data.status==2){
                        var msg = '《'+data.pname+'》未达到保留价，流拍！'
                    }else if (data.status==3){
                        $('#bidTimeStatus').remove();
                        $(boxobj).html('<div class="into">拍品已撤拍...</div>');
                        var msg = '《'+data.pname+'》管理员撤拍！<br/>如果您缴纳过保证金，现在已退还到您的账户。请注意查收'
                    }
                    // 判断当前拍品归属执行操作
                    // 拍卖会操作
                    if(mid!=0){
                        // 显示结束信息
                        if(mtnextPid!=''){
                            var msgtz = '<br/>即将开始下一件拍品！'; 
                        }else{
                            var msgtz = '<br/>正在生成结论书！'; 
                        }
                        AMUI.dialog.alert({ title: '', content:msg+msgtz});
                        setTimeout(function(){
                            
                            if(mtnextPid!=''){
                                window.location.href = mtnextUrl+'?rand='+Math.random();
                            }else{
                                window.location.href = conclusion+'?rand='+Math.random();
                            }
                        },2000);
                        // 如果下一件拍品存在则跳转到链接地址 否则生成结论书
                        
                    // 普通拍品操作
                    }else{
                        AMUI.dialog.alert({ title: '结束提示', content: msg, onConfirm: function() { 
                                window.location.href=window.location.href+"?id="+10000*Math.random();
                        } }); 
                    } 
                }
            });
        }
    }, 100);
}
// 开始时间倒计时
function startDown(stime,ntime,boxobj,day_elem,hour_elem,minute_elem,second_elem,msec_elem){
    var now_time = new Date(ntime*1000);
    var end_time = new Date(stime*1000);
    var native_time = new Date().getTime(); //本地时间
    var now_cha = now_time - native_time; //服务器和本地时间差
    var native_end_time = end_time - now_cha; //本地结束时间
    var sys_second = 0;
    startDowntimer = setInterval(function(){
        if(Math.abs(native_time - new Date().getTime())>5000){
            clearInterval(startDowntimer);
            $.post(ajaxGetTime, {'pid':acpid},function(data){
                calibrationStarttime(data.starttime,data.nowtime);
            });
        }
        sys_second = (native_end_time - new Date().getTime())/100; //本地结束剩余时间
        if (sys_second > 0) {
            sys_second -= 1;
            var day = Math.floor((sys_second / 36000) / 24);
            var hour = Math.floor((sys_second / 36000) % 24);
            var minute = Math.floor((sys_second / 600) % 60);
            var second = Math.floor((sys_second/10) % 60);
            var msec = Math.floor(sys_second % 10); //毫秒
            day_elem && $(day_elem).text(day);//计算天
            $(hour_elem).text(hour<10?"0"+hour:hour);//计算小时
            $(minute_elem).text(minute<10?"0"+minute:minute);//计算分
            $(second_elem).text(second<10?"0"+second:second);// 计算秒
            $(msec_elem).text(msec);// 计算秒的1/10
            native_time = new Date().getTime();
        } else { 
            $('.noStartBidTbox .th').html('拍卖已开始');
            $(boxobj).html('<div class="into">正在进入拍卖...</div>');
            
            clearInterval(startDowntimer);
            window.location.href=window.location.href+"?id="+10000*Math.random();;
        }
    }, 100);
}
// 拍卖被删除操作
function auctionDeleted(etime,ntime){
    if(mid!=0){
        // 显示结束信息
        if(mtnextPid!=''){
            var msgtz = '<br/>即将开始下一件拍品！'; 
        }else{
            var msgtz = '<br/>正在生成结论书！'; 
        }
        AMUI.dialog.alert({ title: '', content: '该拍品已被管理员删除！'+msgtz, onConfirm: function() { 
            
            if(mtnextPid!=''){
                window.location.href = mtnextUrl+'?rand='+Math.random();
            }else{
                window.location.href = conclusion+'?rand='+Math.random();
            }
        } });
        // 如果下一件拍品存在则跳转到链接地址 否则生成结论书
    // 普通拍品操作
    }else{
        AMUI.dialog.alert({ title: '', content: '该拍品已被管理员删除！'+msgtz, onConfirm: function() { 
            
            window.location.href=window.location.href+"?id="+10000*Math.random();;
        } });
    }
}
// 校准结束时间
function calibrationEndtime(etime,ntime){
    endDown(etime,ntime,".onBidtime",".onBidtime .day",".onBidtime .hour",".onBidtime .minute",".onBidtime .second",".onBidtime .msec");
}

// 校准开始时间
function calibrationStarttime(stime,ntime){
    startDown(stime,ntime,".noStartTime",".noStartTime .day",".noStartTime .hour",".noStartTime .minute",".noStartTime .second",".noStartTime .msec");
}
// 代理失败检查提醒
function agency_loser(loserlist){
    if(loserlist){
        $.each(loserlist,function(loserk,loserv){
            if(loserv.uid==ws_my_uid){
                AMUI.dialog.confirm({ title: '代理出价结束', content: loserv.msg, 
                    onConfirm: function() {
                        $.post(iknowurl,{'pid':acpid,'uid':ws_my_uid},function(data){
                            if (data.status) {
                                popup.alert('已取消代理出价！');
                                agency_butstyl(3);
                            } else {
                                popup.alert('取消代理出价失败！');
                            }
                        },'json');
                    }, 
                    onCancel: function() {}
                });
            }
        });
    }
}
// 代理出价按钮样式改变 sta 0：执行中无状态；1：达到目标价；2：被超越；3已停止
function agency_butstyl(sta){
    if (sta==0) {
        $('.Js-robot-post_but strong').html('停止代理出价');
        $('.Js-robot-post_but').removeClass('startBtn');
        $('.Js-robot-post_but').addClass('stopBtn');
        $('.Js-robotprice').attr("disabled", 'disabled'); 
    }else{
        $('.Js-robot-post_but strong').html('启动代理出价');
        $('.Js-robot-post_but').removeClass('stopBtn');
        $('.Js-robot-post_but').addClass('startBtn');
        $('.Js-robotprice').removeAttr("disabled");
    }
    setagency=sta;
}
