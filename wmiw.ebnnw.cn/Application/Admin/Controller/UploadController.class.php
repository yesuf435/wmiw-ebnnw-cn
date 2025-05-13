<?php
namespace Admin\Controller;
use Think\Controller;
class UploadController extends Controller {
    //用户头像上传
    Public function upUserPic () {
        if (!IS_POST) E('页面不存在');
        $upload = $this->_upUserPic();
        echojson($upload);
    }

    //商品图片上传
    Public function upGoodsPic () {
        if (!IS_POST) E('页面不存在');
        $upload = $this->_upload();
        echojson($upload);
    }
    //广告图片上传
    Public function upAdvFile () {
        $data = I('post.data');
        $size = explode('|',$data);
        if (!IS_POST) E('页面不存在');
        $upload = $this->_upUntreatedPic (C('ADV_PICPATH') . '/',$size[0],$size[1]);
        echojson($upload);
    }
    //分类图标上传
    Public function upCateIco () {
        if (!IS_POST) E('页面不存在');
        $upload = $this-> _upUntreatedPic (C('CATE_PICPATH') . '/',C('CATE_ICO_WIDTH'),C('CATE_ICO_HEIGHT'));
        echojson($upload);
    }
    //友链图标上传
    Public function upLinkIco () {
        if (!IS_POST) E('页面不存在');
        $upload = $this-> _upUntreatedPic (C('LINK_PICPATH') . '/',C('LINK_ICO_WIDTH'),C('LINK_ICO_HEIGHT'));
        echojson($upload);
    }
    //文章图标上传
    Public function upNewsIco () {
        if (!IS_POST) E('页面不存在');
        $upload = $this-> _upUntreatedPic (C('NEWS_PICPATH') . '/',C('NEWS_ICO_WIDTH'),C('NEWS_ICO_HEIGHT'),1);
        echojson($upload);
    }
    //专场列表图片上传
    Public function upSpecialIco () {
        if (!IS_POST) E('页面不存在');
        $upload = $this-> _upUntreatedPic (C('SPECIAL_PICPATH') . '/',C('SPECIAL_ICO_WIDTH'),C('SPECIAL_ICO_HEIGHT'),0,3);
        echojson($upload);
    }
    //专场BANNER图片上传
    Public function upSpecialBnner () {
        if (!IS_POST) E('页面不存在');
        $upload = $this-> _upUntreatedPic (C('SPECIAL_PICPATH') . '/',C('SPECIAL_BANNER_WIDTH'),C('SPECIAL_BANNER_HEIGHT'),0,3);
        echojson($upload);
    }
    //拍卖会列表图片上传
    Public function upMeetingIco () {
        if (!IS_POST) E('页面不存在');
        $upload = $this-> _upUntreatedPic (C('MEETING_PICPATH') . '/',C('MEETING_ICO_WIDTH'),C('MEETING_ICO_HEIGHT'),0,3);
        echojson($upload);
    }
    //拍卖会BANNER图片上传
    Public function upMeetingBnner () {
        if (!IS_POST) E('页面不存在');
        $upload = $this-> _upUntreatedPic (C('MEETING_PICPATH') . '/',C('MEETING_BANNER_WIDTH'),C('MEETING_BANNER_HEIGHT'),0,3);
        echojson($upload);
    }
    //微信头条图片上传
    Public function upWeiTopPic () {
        if (!IS_POST) E('页面不存在');
        $upload = $this-> _upUntreatedPic (C('WEI_PICPATH') . '/',C('WEI_TOP_WIDTH'),C('WEI_TOP_HEIGHT'));
        echojson($upload);
    }
    //微信列表图片上传
    Public function upWeiListPic () {
        if (!IS_POST) E('页面不存在');
        $upload = $this->_upUntreatedPic (C('WEI_PICPATH') . '/',C('WEI_LIST_WIDTH'),C('WEI_LIST_HEIGHT'));
        echojson($upload);
    }
    //后台分享链接默认图片
    Public function upSharePic () {
        if (!IS_POST) E('页面不存在');
        $upload = $this-> _upUntreatedPic (C('WEI_PICPATH') . '/',C('WEI_LIST_WIDTH'),C('WEI_LIST_HEIGHT'));
        echo json_encode($upload);
    }
    //后台分享链接默认图片
    Public function upCodemapPic () {
        if (!IS_POST) E('页面不存在');
        $upload = $this-> _upUntreatedPic (C('WEI_PICPATH') . '/',430,430);
        echo json_encode($upload);
    }
    // 身份证上传
    Public function upIdcardPic () {
        if (!IS_POST) E('页面不存在');
        $upload = $this->_upUntreatedPic(C('IDCARD_PICPATH') . '/',C('IDCARD_MAX_WIDTH'),C('IDCARD_MAX_HEIGHT'));
        echo json_encode($upload);
    }
    //文件上传
    Public function upFile () {
        if (!IS_POST) E('页面不存在');
        $config = array(
            'maxSize' => 52428800,//上传的文件大小限制 (0-不做限制)
            'rootPath' => C('UPLOADS_PICPATH'),//保存根路径
            'savePath' => C('FILE_PICPATH') . '/',//商品图片保存路径
            'saveName' => array('uniqid',''),//保存文件名
            'autoSub' => true,//自动子目录保存文件
            'subName' => array('date','Ymd'),//子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        );
        
        $upload = new \Think\Upload($config);// 实例化上传类
        $info   =   $upload->upload();
        $info = $info['file'];
        if(!$info) {// 上传错误提示错误信息        
            $upload = array('status' => 0, 'msg' => $upload->getError());
        }else{// 上传成功 获取上传文件信息
            $upload = array(//返回数据
            'status' => 1,
            'path' => $info['savepath'] . $info['savename'],
            'msg' => '上传成功'
            ); 
        }
        echojson($upload);
    }
    /**
    * 用户头像上传处理
    * @return [Array]         [图片上传信息]
    */
    Private function _upUserPic () {
        $config = array(
            'maxSize' => 3145728,//上传的文件大小限制 (0-不做限制)
            'rootPath' => C('UPLOADS_PICPATH'),//保存根路径
            'savePath' => C('USER_PICPATH') . '/',//商品图片保存路径
            'saveName' => array('uniqid',''),//保存文件名
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),//允许上传的文件后缀
            'autoSub' => false,//自动子目录保存文件
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info   =   $upload->upload();
        $info = $info['file'];
        if(!$info) {// 上传错误提示错误信息        
            return array('status' => 0, 'msg' => $upload->getError());
        }else{// 上传成功 获取上传文件信息
            $uploadImg = $info['savepath'] . $info['savename'];//上传的图片和路径
            $thumbImgUrl = C('UPLOADS_PICPATH').$info['savepath'] . $info['savename'];
            //生成缩略图
            $imgThumb = new \Think\Image(); 
            $imgThumb->open($thumbImgUrl);
            $picFixArr=explode(',', C('USER_PIC_PREFIX'));
            foreach ($picFixArr as $pFixK => $pFixV) {
                $imSizeW = picSize($pFixK,'width','user');
                $imSizeH = picSize($pFixK,'height','user');
                $imgThumb->thumb($imSizeW,$imSizeH,\Think\Image::IMAGE_THUMB_CENTER)->save(C('UPLOADS_PICPATH').$info['savepath'] . $pFixV . $info['savename']);
                $path[]=C('UPLOADS_PICPATH').$info['savepath'] . $pFixV . $info['savename'];

            }
            return array(//返回数据
                'status' => 1,
                'savepath'=>$info['savepath'],
                'savename'=>$info['savename'],
                'path' => $uploadImg,
                'showurl'=>getImgUrl($uploadImg),
                'thumb'=>getImgUrl($thumbImg),
                'msg' => '上传成功'
            );  
        }
    }
    /**
     * 商品图片上传处理
     * @return [Array]         [图片上传信息]
     */
    /**
     * 商品图片上传处理
     * @return [Array]         [图片上传信息]
     */
    Private function _upload () {
        $config = array(
            'maxSize' => 3145728,//上传的文件大小限制 (0-不做限制)
            'rootPath' => C('UPLOADS_PICPATH'),//保存根路径
            'savePath' => C('GOODS_PICPATH') . '/',//商品图片保存路径
            'saveName' => array('uniqid',''),//保存文件名
            'exts' => array('jpg', 'gif', 'jpeg','aae'),//允许上传的文件后缀
            'autoSub' => true,//自动子目录保存文件
            'subName' => array('date','Ymd'),//子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        );

        $upload = new \Think\Upload($config);// 实例化上传类
        $info   =   $upload->upload();
        $info = $info['file'];

        if(!$info) {// 上传错误提示错误信息        
            return array('status' => 0, 'msg' => $upload->getError());
        }else{// 上传成功 获取上传文件信息
            $uploadImg = $info['savepath'] . $info['savename'];//上传的图片和路径
            $cutImgUrl = C('UPLOADS_PICPATH').$info['savepath'] . $info['savename'];
            return $this->_goodsDispose($info['savepath'],$info['savename'],$cutImgUrl);
        }
    }
    // 商品图片处理操作
    Public function _goodsDispose ($savepath,$savename,$cutImgUrl){
        //生成缩略图
            $uploadImg = $savepath.$savename;
            $imgThumb = new \Think\Image(); 
            $imgThumb->open($cutImgUrl);
            $picFixArr=explode(',', C('GOODS_PIC_PREFIX'));
            // 生成原图等比缩放图片
            $thw = picSize(0,'width');
            $thh = picSize(0,'height');
            // 缩放后填充类型为IMAGE_THUMB_FILLED  居中裁剪类型为IMAGE_THUMB_CENTER
            $imgThumb->thumb($thw,$thh,\Think\Image::IMAGE_THUMB_CENTER)->save($cutImgUrl);
            foreach ($picFixArr as $pFixK => $pFixV) {
                $imSizeW = picSize($pFixK,'width');
                $imSizeH = picSize($pFixK,'height');
                $imgThumb->thumb($imSizeW,$imSizeH,\Think\Image::IMAGE_THUMB_CENTER)->save(C('UPLOADS_PICPATH').$savepath . $pFixV . $savename);
            }
            //保存到数据库
            $goodsId = I('post.goodsId');//获取到的商品id
            if($goodsId){ //如果是编辑商品会传过来商品id
                //读取该商品图片字段，并组合新上传的图片
                $goods = M('Goods');
                $gdPic = $goods->where(array('id'=>$goodsId))->getField('pictures');
                $newPicStr = trim($gdPic.'|'.$uploadImg,'|');

                //保存上传的图片到数据库
                $data = array(
                    'id' => $goodsId,
                    'pictures' => $newPicStr
                    );
                if($goods->save($data)){
                    return array(//返回数据
                        'status' => 1,
                        'path' => $uploadImg,
                        'showimg' => getImgUrl(picRep($uploadImg,1)),
                        'showurl'=>getImgUrl($uploadImg),
                        'msg' => '上传成功并保存到了数据库！'
                        );
                }
            }else{ //否则 是发布商品只上传不保存到数据库
                return array(//返回数据
                    'status' => 1,
                    'savepath'=>$savepath,
                    'savename'=>$savename,
                    'path' => $uploadImg,
                    'showurl'=>getImgUrl(picRep($uploadImg,1)),
                    'thumb'=>getImgUrl(picRep($uploadImg,3)),
                    'msg' => '上传成功'
                    );  
            }
    }
    /**
    * 广告图片上传处理
    * @return [Array]         [图片上传信息]
    */
    Private function _uploadAdv () {
        if(I('post.up_type') == '1'){
            $fileExts = array('jpg', 'gif', 'jpeg');// 设置附件上传类型
        }else{
            $fileExts = array('swf');
        }
        $config = array(
            'maxSize' => 3145728,//上传的文件大小限制 (0-不做限制)
            'rootPath' => './'.C('UPLOADS_PICPATH'),//保存根路径
            'savePath' => C('ADV_PICPATH') . '/',//广告图片保存路径
            'saveName' => array('uniqid',''),//保存文件名
            'allowExts' => $fileExts,//允许上传的文件后缀
            'autoSub' => true,//自动子目录保存文件
            'subName' => array('date','Ymd'),//子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        );
        $data = I('post.');
        $upload = new \Think\Upload($config);// 实例化上传类
        $info   =   $upload->upload();
        $info = $info['file'];
        if(!$info) {// 上传错误提示错误信息        
            return array('status' => 0, 'msg' => $upload->getError());
        }else{// 上传成功 获取上传文件信息
            $uploadFile = $info['savepath'] . $info['savename'];//上传的图片和路径
            $cutImgUrl = './'.C('UPLOADS_PICPATH').$info['savepath'] . $info['savename'];
            //如果大于配置文件配置最大宽度高度进行缩略图处理
            if($data['0']['up_type'] == '1'){
                //缩略图生成
                $imgAdv = new \Think\Image(); 
                $imgAdv->open($cutImgUrl);
                $advWidth = $imgAdv->width(); // 返回图片的宽度
                $advHeight = $imgAdv->height(); // 返回图片的高度
                if($advWidth > C('ADV_MAX_WIDTH') || $advHeight > C('ADV_MAX_HEIGHT')){
                    $imgAdv->thumb(C('ADV_MAX_WIDTH'),C('ADV_MAX_HEIGHT'),\Think\Image::IMAGE_THUMB_CENTER)->save($cutImgUrl);
                }
            }
            //保存到数据库
            $advId = $data['0']['advId'];//获取到的广告id
            if($advId){ //如果是编辑广告会传过来广告id
                $Advertising = M('Advertising');
                $deladvFile = $Advertising->where(array('id'=>$advId))->getField('code');
                @unlink('./'.C('UPLOADS_PICPATH') . $deladvFile);//删除之前广告文件
                //保存上传的广告图片到数据库
                $data = array(
                    'id' => $advId,
                    'code' => $uploadFile
                    );
                if($Advertising->save($data)){
                    return array(//返回数据
                        'status' => 1,
                        'path' => $uploadFile,
                        'msg' => '上传成功并保存到了数据库！'
                        );
                }
            }else{ //否则 是发布商品只上传不保存到数据库
                return array(//返回数据
                    'status' => 1,
                    'path' => $uploadFile,
                    'msg' => '上传成功！'
                    );  
            }
        }
    }
    



    /**
     * 不做处理的图片上传
     * @return [Array]         [图片上传信息]
     */
    Private function _upUntreatedPic ($savePath,$width,$height,$thumb,$type=1) {
        $config = array(
            'maxSize' => 3145728,//上传的文件大小限制 (0-不做限制)
            'rootPath' => C('UPLOADS_PICPATH'),//保存根路径
            'savePath' => $savePath,//商品图片保存路径
            'saveName' => array('uniqid',''),//保存文件名
            'exts' => array('jpg', 'gif', 'jpeg','png'),//允许上传的文件后缀
            'autoSub' => true,//自动子目录保存文件
            'subName' => array('date','Ymd'),//子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        );

        $upload = new \Think\Upload($config);// 实例化上传类
        $info   =   $upload->upload();
        $info = $info['file'];
        if(!$info) {// 上传错误提示错误信息        
            return array('status' => 0, 'msg' => $upload->getError());
        }else{// 上传成功 获取上传文件信息
            $uploadImg = $info['savepath'] . $info['savename'];//上传的图片和路径
            //生成缩略图
            $imgThumb = new \Think\Image(); 
            $imgThumb->open(C('UPLOADS_PICPATH').$uploadImg);
            // IMAGE_THUMB_SCALE     =   1 ; 等比例缩放类型
            // IMAGE_THUMB_FILLED    =   2 ; 缩放后填充类型
            // IMAGE_THUMB_CENTER    =   3 ; 居中裁剪类型
            // IMAGE_THUMB_NORTHWEST =   4 ; 左上角裁剪类型
            // IMAGE_THUMB_SOUTHEAST =   5 ; 右下角裁剪类型
            // IMAGE_THUMB_FIXED     =   6 ; 固定尺寸缩放类型
            switch ($type) {
                case 1:
                    $imgThumb->thumb($width,$height,\Think\Image::IMAGE_THUMB_SCALE)->save(C('UPLOADS_PICPATH').$uploadImg);
                    break;
                case 2:
                    $imgThumb->thumb($width,$height,\Think\Image::IMAGE_THUMB_FILLED)->save(C('UPLOADS_PICPATH').$uploadImg);
                    break;
                case 3:
                    $imgThumb->thumb($width,$height,\Think\Image::IMAGE_THUMB_CENTER)->save(C('UPLOADS_PICPATH').$uploadImg);
                    break;
                case 4:
                    $imgThumb->thumb($width,$height,\Think\Image::IMAGE_THUMB_NORTHWEST)->save(C('UPLOADS_PICPATH').$uploadImg);
                    break;
                case 5:
                    $imgThumb->thumb($width,$height,\Think\Image::IMAGE_THUMB_SOUTHEAST)->save(C('UPLOADS_PICPATH').$uploadImg);
                    break;
                case 6:
                    $imgThumb->thumb($width,$height,\Think\Image::IMAGE_THUMB_FIXED)->save(C('UPLOADS_PICPATH').$uploadImg);
                    break;
            }
            if ($thumb) {
                $thumbImg = $info['savepath'] . 'thumb'.$info['savename'];
                $imgThumb->thumb('100','100',\Think\Image::IMAGE_THUMB_CENTER)->save(C('UPLOADS_PICPATH').$thumbImg);
            }
            return array(//返回数据
            'status' => 1,
            'savepath'=>$info['savepath'],
            'savename'=>$info['savename'],
            'path' => $uploadImg,
            'showurl'=>getImgUrl($uploadImg),
            'thumb'=>getImgUrl($thumbImg),
            'msg' => '上传成功'
            );
        }
    }
    //editor上传
    Public function editorUpload () {
        if (!IS_POST) E('页面不存在');
        $upload = $this->_editorUpload();
        echojson($upload);
    }
    /**
    * uEditor图片上传处理
    * @return [Array]         [图片上传信息]
    */
    Private function _editorUpload () {
        $config = array(
            'maxSize' => 3145728,//上传的文件大小限制 (0-不做限制)
            'rootPath' => './'.C('UPLOADS_PICPATH'),//保存根路径
            'savePath' => C('ARTICLE_PICPATH') . '/',//商品图片保存路径
            'saveName' => array('uniqid',''),//保存文件名
            'exts' => array('jpg', 'gif', 'jpeg'),//允许上传的文件后缀
            'autoSub' => true,//自动子目录保存文件
            'subName' => array('date','Ymd'),//子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        );
        
        $upload = new \Think\Upload($config);// 实例化上传类
        $info   =   $upload->upload();
        $info = $info['upfile'];
        if(!$info) {// 上传错误提示错误信息        
            return array('status' => 0, 'msg' => $upload->getError());
        }else{// 上传成功 获取上传文件信息
            $uploadImg = $info['savepath'] . $info['savename'];//上传的图片和路径
            return array(
                'url'      =>$info['savepath'] . $info['savename'],   //保存后的文件路径
                'title'    => htmlspecialchars($_POST['pictitle'], ENT_QUOTES),   //文件描述，对图片来说在前端会添加到title属性上
                'original' => $info['name'],   //原始文件名
                'state'    =>'SUCCESS'  //上传状态，成功时返回SUCCESS,其他任何值将原样返回至图片上传框中
            );
        }
    }
}