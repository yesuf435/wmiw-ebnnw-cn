<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class MemberPledgeBillModel extends ViewModel {
    Protected $viewFields = array(
        'Member_pledge_bill' => array(
             '*',
            '_type' => 'LEFT'
            ),
        'Member' => array(
            'uid','account','nickname','mobile','avatar',
            '_on' => 'Member_pledge_bill.uid = Member.uid'
            )
    );
}

?>
