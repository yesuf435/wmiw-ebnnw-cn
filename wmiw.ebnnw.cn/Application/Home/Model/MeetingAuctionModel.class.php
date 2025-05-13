<?php
namespace Home\Model;
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
    public function listMeeting($where,$od,$size = 20, $countyn=0) {
        $auction = M('auction');
        $count = $this->where($where)->count();
        $pConf = page($count,C('PAGE_SIZE'));
        $list = $this->limit($pConf['first'].','.$pConf['list'])->order($od)->where($where)->select();
        foreach ($list as $k => $v) {
            // 拍品状态
            $ntime = time();
            if($v['starttime']<=$ntime&&$v['endtime']>=$ntime){
                $list[$k]['saytyp'] = array('ch'=>'在拍拍卖会','get'=>'biding');
                if ($countyn) {
                    // 统计拍卖会内拍品出价次数
                    $list[$k]['bidcount'] = $auction->where(array('mid'=>$v['mid']))->sum('bidcount');
                }
            }elseif ($v['endtime']<$ntime) {
                $list[$k]['saytyp'] = array('ch'=>'结束拍卖会','get'=>'bidend');
                if ($countyn) {
                    // 统计拍卖会内拍品成交数量
                    $list[$k]['endcount'] = $auction->where(array('mid'=>$v['mid'],'endstatus'=>1))->count();
                    // 统计拍卖会内拍品成交额
                    $list[$k]['countprc'] = $list[$k]['endcount']?$auction->where(array('mid'=>$v['mid'],'endstatus'=>1))->sum('nowprice'):0;
                }
            }elseif ($v['starttime']>$ntime) {
                $list[$k]['saytyp'] = array('ch'=>'待拍拍卖会','get'=>'future');
                if ($countyn) {
                    // 统计拍卖会内拍品想拍数量
                    $list[$k]['clcount'] = $auction->where(array('mid'=>$v['mid']))->sum('clcount');
                }
            }
        }
        return array('list'=>$list,'page'=>$pConf['show'],'count'=>$count);
    }
}

?>
