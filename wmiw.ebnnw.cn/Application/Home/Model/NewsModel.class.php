<?php
namespace Home\Model;
use Think\Model\ViewModel;
class NewsModel extends ViewModel {
    Protected $viewFields = array(
        'News' => array(
            '*',
            '_type' => 'LEFT'
            ),
        'Category' => array(
            'pid','name','sort',
            '_on' => 'News.cid = Category.cid',
            '_type' => 'LEFT'
            )
    );
}
?>
