<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class MeetingAuctionModel extends ViewModel {
    Protected $viewFields = array(
        'Meeting_auction' => array(
            '*',
            '_type' => 'LEFT'
            ),
        'Member' => array(
            'account','organization','intro','score',
            '_on' => 'Meeting_auction.sellerid = Member.uid',
            '_type' => 'LEFT'
            )
    );
    /**
     * [listMeeting description]
     * @param  integer $firstRow [分页起始]
     * @param  integer $listRows [分页结束]
     * @param  [type]  $where    [筛选条件]
     * @return [type]            [拍品列表]
     */
    public function listMeeting($where,$od) {
        $count = $this->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $list = $this->limit($pConf['first'].','.$pConf['list'])->order($od)->where($where)->select();
        $ntime = time();
        $repeat=M('auction_repeat');
        foreach ($list as $k => $v) {
            if($v['starttime']<=$ntime&&$v['endtime']>=$ntime){
                $list[$k]['st'] = '在拍';
                $list[$k]['sten'] = 'biding';
            }elseif ($v['endtime']<$ntime) {
                $list[$k]['st'] = '结束';
                $list[$k]['sten'] = 'bidend';

            }elseif ($v['starttime']>$ntime) {
                $list[$k]['st'] = '待拍';
                $list[$k]['sten'] = 'future';
            }
            // 是否设置重复拍
            $list[$k]['repeat'] = $repeat->where(array('type'=>2,'rid'=>$v['mid']))->getField('id');
        }
        return array('list'=>$list,'page'=>$pConf['show'],'count'=>$count);
    }
}

?>
