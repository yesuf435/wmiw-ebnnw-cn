<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class SellerPledgeModel extends ViewModel {
    Protected $viewFields = array(
        'seller_pledge' => array(
            'id','sellerid','pid','type','pledge',' limsum','time','status',
            '_type' => 'LEFT'
            ),
        'Member' => array(
            'uid','account','nickname','mobile','avatar','organization',
            '_on' => 'seller_pledge.sellerid = Member.uid',
            '_type' => 'LEFT'
            ),
        'Auction' => array(
            'pid','pname','endstatus',
            '_on' => 'seller_pledge.pid = Auction.pid',
            '_type' => 'LEFT'
            ),
        'Goods' => array(
            'pictures',
            '_on' => 'Auction.gid = Goods.id'
            ),
    );
}

?>
