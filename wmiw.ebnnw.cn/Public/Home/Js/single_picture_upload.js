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
                lrz(this.files[i], { width: 2000, quality: 0.5 }).then(function(rst) {
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
                    // rst.formData.append('fileLen', rst.fileLen);
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