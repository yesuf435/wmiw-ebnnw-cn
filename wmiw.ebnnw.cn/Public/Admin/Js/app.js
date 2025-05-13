$(function() {
    // 读取body data-type 判断是哪个页面然后执行相应页面方法，方法在下面。
    var dataType = $('body').attr('data-type');
    console.log(dataType);
    for (key in pageData) {
        if (key == dataType) {
            pageData[key]();
        }
    }
    //     // 判断用户是否已有自己选择的模板风格
    //    if(storageLoad('SelcetColor')){
    //      $('body').attr('class',storageLoad('SelcetColor').Color)
    //    }else{
    //        storageSave(saveSelectColor);
    //        $('body').attr('class','theme-black')
    //    }

    autoLeftNav();
    $(window).resize(function() {
        autoLeftNav();
        console.log($(window).width())
    });

    //    if(storageLoad('SelcetColor')){

    //     }else{
    //       storageSave(saveSelectColor);
    //     }

    var chn=function(cid,op){
        if(op=="show"){
            $("tr[pid='"+cid+"']").each(function(){
                $(this).removeAttr("status").show();
                chn($(this).attr("id"),"show");
            });
        }else{
            $("tr[pid='"+cid+"']").each(function(){
                $(this).attr("status",1).hide();
                chn($(this).attr("id"),"hide");
            });
        }
    }
    $(".tree").click(function(){
        if($(this).attr("status")!=1){
            chn($(this).parent().attr("id"),"hide");
            $(this).attr("status",1);
        }else{
            chn($(this).parent().attr("id"),"show");
            $(this).removeAttr("status");
        }
    });

    $('.ajax_order a').on("click",function(){
        var odType = $(this).attr('class');
        var odShow = $(this).parents('.ajax_order').find('.input');
        var odVal = odShow.html();
        var odid = odShow.attr('odid');
        $.post(odUrl,{'odType':odType,'odid':odid},function(data){      //ajax后台
            if (data.status) {
                if(odType == 'rising'){
                    odShow.html(parseInt(odVal) + 1);
                }else if(odType == 'drop'){
                    odShow.html(parseInt(odVal) - 1);
                }
                
            } else {
                alert(data.msg);
            }
        },'json');        
    });
})
// 单张图片上传操作【 
$(document).on('change','.upInput',function(){
    var uplode_url = $(this).attr('link');
    var parentsObj =$(this).parents('.uploadePic-box');
    var pname = $(this).attr('pname');
    if (this.files.length) {
        if (this.files.length>1) {
            AMUI.dialog.alert({ title: '', content: "最多上传1张图片！"});
        }else{
            for (var i = 0; i < this.files.length; i++) {
                lrz(this.files[i], { width: 3000, quality: 0.99 }).then(function(rst) {
                    console.log(rst.base64);
                    parentsObj.find('.img-box').remove();
                    var load = 0;
                    var newli = '<li class="img-box"><div class="upImg-list-box up-progress"><div class="am-progress am-progress-striped am-progress-xs"><div class="am-progress-bar" style="width: 0%"></div></div></div></li>';
                    parentsObj.find('.upImg-lighthouse').before(newli);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', uplode_url);
                    xhr.onload = function () {
                        var data = JSON.parse(xhr.response);
                        if (xhr.status === 200) {
                            var newimg ='<div class="upImg-list-box am-gallery-item"><a class="showurl" href="'+data.showurl+'"><img src="'+data.showurl+'"><input type="hidden" name="'+pname+'" value="'+data.savepath+data.savename+'" /></a></div>';
                            parentsObj.find('.img-box').html(newimg);
                            // 上传成功
                        } else {
                            alert(data.msg);
                            parentsObj.find('.img-box').remove();
                            this.value = null;
                            // 处理其他情况
                        }
                    };
                    xhr.onerror = function () {
                        AMUI.dialog.alert({ title: '', content: '未知错误:' + JSON.stringify(err, null, 2)});
                        parentsObj.find('.img-box').remove();
                        this.value = null;
                    };
                    if (xhr.upload) {
                        try {
                            xhr.upload.addEventListener('progress', function (e) {
                                if (!e.lengthComputable) return false;
                                // 上传进度
                                load = parseInt(((e.loaded / e.total) || 0) * 100);
                                parentsObj.find('.img-box .am-progress-bar').css({"width": load + "%"});
                            });
                        } catch (err) {
                            console.error('进度展示出错了,似乎不支持此特性?', err);
                        }
                    }
                    // 添加参数
                    if(upAppendData){
                        rst.formData.append('data', upAppendData);
                    }
                    // 触发上传
                    xhr.send(rst.formData);
                    return rst;
                }).catch(function (err) {
                    // 万一出错了，这里可以捕捉到错误信息
                    // 而且以上的then都不会执行
                    alert(err);
                }).always(function () {
                    // 不管是成功失败，这里都会执行
                });
            }
        }
    }
});
// 单张图片上传操作】

// 页面数据
var pageData = {
    // ===============================================
    // 图表页
    // ===============================================
    'chart': function chartData() {
        // ==========================
        // 百度图表A http://echarts.baidu.com/
        // ==========================

        var echartsC = echarts.init(document.getElementById('tpl-echarts-C'));


        optionC = {
            tooltip: {
                trigger: 'axis'
            },

            legend: {
                data: ['蒸发量', '降水量', '平均温度']
            },
            xAxis: [{
                type: 'category',
                data: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
            }],
            yAxis: [{
                    type: 'value',
                    name: '水量',
                    min: 0,
                    max: 250,
                    interval: 50,
                    axisLabel: {
                        formatter: '{value} ml'
                    }
                },
                {
                    type: 'value',
                    name: '温度',
                    min: 0,
                    max: 25,
                    interval: 5,
                    axisLabel: {
                        formatter: '{value} °C'
                    }
                }
            ],
            series: [{
                    name: '蒸发量',
                    type: 'bar',
                    data: [2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6, 162.2, 32.6, 20.0, 6.4, 3.3]
                },
                {
                    name: '降水量',
                    type: 'bar',
                    data: [2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3]
                },
                {
                    name: '平均温度',
                    type: 'line',
                    yAxisIndex: 1,
                    data: [2.0, 2.2, 3.3, 4.5, 6.3, 10.2, 20.3, 23.4, 23.0, 16.5, 12.0, 6.2]
                }
            ]
        };

        echartsC.setOption(optionC);

        var echartsB = echarts.init(document.getElementById('tpl-echarts-B'));
        optionB = {
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                x: 'center',
                data: ['某软件', '某主食手机', '某水果手机', '降水量', '蒸发量']
            },
            radar: [{
                    indicator: [
                        { text: '品牌', max: 100 },
                        { text: '内容', max: 100 },
                        { text: '可用性', max: 100 },
                        { text: '功能', max: 100 }
                    ],
                    center: ['25%', '40%'],
                    radius: 80
                },
                {
                    indicator: [
                        { text: '外观', max: 100 },
                        { text: '拍照', max: 100 },
                        { text: '系统', max: 100 },
                        { text: '性能', max: 100 },
                        { text: '屏幕', max: 100 }
                    ],
                    radius: 80,
                    center: ['50%', '60%'],
                },
                {
                    indicator: (function() {
                        var res = [];
                        for (var i = 1; i <= 12; i++) {
                            res.push({ text: i + '月', max: 100 });
                        }
                        return res;
                    })(),
                    center: ['75%', '40%'],
                    radius: 80
                }
            ],
            series: [{
                    type: 'radar',
                    tooltip: {
                        trigger: 'item'
                    },
                    itemStyle: { normal: { areaStyle: { type: 'default' } } },
                    data: [{
                        value: [60, 73, 85, 40],
                        name: '某软件'
                    }]
                },
                {
                    type: 'radar',
                    radarIndex: 1,
                    data: [{
                            value: [85, 90, 90, 95, 95],
                            name: '某主食手机'
                        },
                        {
                            value: [95, 80, 95, 90, 93],
                            name: '某水果手机'
                        }
                    ]
                },
                {
                    type: 'radar',
                    radarIndex: 2,
                    itemStyle: { normal: { areaStyle: { type: 'default' } } },
                    data: [{
                            name: '降水量',
                            value: [2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 75.6, 82.2, 48.7, 18.8, 6.0, 2.3],
                        },
                        {
                            name: '蒸发量',
                            value: [2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 35.6, 62.2, 32.6, 20.0, 6.4, 3.3]
                        }
                    ]
                }
            ]
        };
        echartsB.setOption(optionB);
        var echartsA = echarts.init(document.getElementById('tpl-echarts-A'));
        option = {

            tooltip: {
                trigger: 'axis',
            },
            legend: {
                data: ['邮件', '媒体', '资源']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: [{
                type: 'category',
                boundaryGap: true,
                data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
            }],

            yAxis: [{
                type: 'value'
            }],
            series: [{
                    name: '邮件',
                    type: 'line',
                    stack: '总量',
                    areaStyle: { normal: {} },
                    data: [120, 132, 101, 134, 90, 230, 210],
                    itemStyle: {
                        normal: {
                            color: '#59aea2'
                        },
                        emphasis: {

                        }
                    }
                },
                {
                    name: '媒体',
                    type: 'line',
                    stack: '总量',
                    areaStyle: { normal: {} },
                    data: [220, 182, 191, 234, 290, 330, 310],
                    itemStyle: {
                        normal: {
                            color: '#e7505a'
                        }
                    }
                },
                {
                    name: '资源',
                    type: 'line',
                    stack: '总量',
                    areaStyle: { normal: {} },
                    data: [150, 232, 201, 154, 190, 330, 410],
                    itemStyle: {
                        normal: {
                            color: '#32c5d2'
                        }
                    }
                }
            ]
        };
        echartsA.setOption(option);
    }
}


// 风格切换

$('.tpl-skiner-toggle').on('click', function() {
    $('.tpl-skiner').toggleClass('active');
})

$('.tpl-skiner-content-bar').find('span').on('click', function() {
    $('body').attr('class', $(this).attr('data-color'))
    saveSelectColor.Color = $(this).attr('data-color');
    // 保存选择项
    storageSave(saveSelectColor);

})




// 侧边菜单开关


function autoLeftNav() {



    $('.tpl-header-switch-button').on('click', function() {
        if ($('.left-sidebar').is('.active')) {
            if ($(window).width() > 1024) {
                $('.tpl-content-wrapper').removeClass('active');
            }
            $('.left-sidebar').removeClass('active');
        } else {

            $('.left-sidebar').addClass('active');
            if ($(window).width() > 1024) {
                $('.tpl-content-wrapper').addClass('active');
            }
        }
    })

    if ($(window).width() < 1024) {
        $('.left-sidebar').addClass('active');
    } else {
        $('.left-sidebar').removeClass('active');
    }
}


// 侧边菜单
$('.sidebar-nav-sub-title').on('click', function() {
    $(this).siblings('.sidebar-nav-sub').slideToggle(80)
        .end()
        .find('.sidebar-nav-sub-ico').toggleClass('sidebar-nav-sub-ico-rotate');
})

function clickCheckbox(){
        $(".chooseAll").click(function(){
            var status=$(this).prop('checked');
            $("table input[type='checkbox']").prop("checked",status);
            $(".chooseAll").prop("checked",status);
            $(".unsetAll").prop("checked",false);
        });
        $(".unsetAll").click(function(){
            var status=$(this).prop('checked');
            $("table input[type='checkbox']").each(function(){
                $(this).prop("checked",! $(this).prop("checked"));
            });
            $(".unsetAll").prop("checked",status);
            $(".chooseAll").prop("checked",false);
        });
    }
// 重置验证码
function restVerifyImg(){
    var newcode = $('.verifyImg').attr('src');
    $('.verifyImg').attr('src',newcode+'?rand='+Math.random());
    $('#verify_code').val('');
}

