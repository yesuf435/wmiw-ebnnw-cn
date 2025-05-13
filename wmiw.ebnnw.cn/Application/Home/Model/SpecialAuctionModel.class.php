<?php
namespace Home\Model;
use Think\Model\ViewModel;
class SpecialAuctionModel extends ViewModel {
    Protected $viewFields = array(
        'Special_auction' => array(
            '*',
            '_type' => 'LEFT'
            ),
        'Member' => array(
            'account','organization','intro','score',
            '_on' => 'Special_auction.sellerid = Member.uid',
            '_type' => 'LEFT'
            )
    );
    /**
     * [listSpecial description]
     * @param  integer $where [条件]
     * @param  integer $od [排序]
     * @param  [type]  $size [分页数量]
     * @param  [type]  $countyn [是否统计]
     * @return [type]        [专场列表列表]
     */
    public function listSpecial($where,$od,$size = 20, $countyn=0) {
        $auction = M('auction');
        $count = $this->where($where)->count();
        $pConf = page($count,$size);
        $list = $this->limit($pConf['first'].','.$pConf['list'])->order($od)->where($where)->select();
        foreach ($list as $k => $v) {
            // 拍品状态
            $ntime = time();
            if($v['starttime']<=$ntime&&$v['endtime']>=$ntime){
                $list[$k]['saytyp'] = array('ch'=>'在拍专场','get'=>'biding');
                if ($countyn) {
                    // 统计专场内拍品出价次数
                    $list[$k]['bidcount'] = $auction->where(array('sid'=>$v['sid']))->sum('bidcount');
                }
            }elseif ($v['endtime']<$ntime) {
                $list[$k]['saytyp'] = array('ch'=>'结束专场','get'=>'bidend');
                if ($countyn) {
                    // 统计专场内拍品成交数量
                    $list[$k]['endcount'] = $auction->where(array('sid'=>$v['sid'],'endstatus'=>1))->count();
                    // 统计专场内拍品成交额
                    $list[$k]['countprc'] = $list[$k]['endcount']?$auction->where(array('sid'=>$v['sid'],'endstatus'=>1))->sum('nowprice'):0;
                }
            }elseif ($v['starttime']>$ntime) {
                $list[$k]['saytyp'] = array('ch'=>'待拍专场','get'=>'future');
                if ($countyn) {
                    // 统计专场内拍品想拍数量
                    $list[$k]['clcount'] = $auction->where(array('sid'=>$v['sid']))->sum('clcount');
                }
            }
        }
        return array('list'=>$list,'page'=>$pConf['show'],'count'=>$count);
    }
}

?>
