<?php
namespace Admin\Widget;
use Think\Controller;
class OncooWidget extends Controller {
    //省市区
    public function region($province,$city,$area,$layer) {
        if($province==0){
            $rid = array(0,0,0);
        }else{
            $rid = array($province,$city,$area);
        }
        if(!empty($layer)){
            $layer  =$layer>3 || $layer<1 ? 3 : $layer;
        }else{
            $layer=3;
        }
        $name =array('province','city','area');
        $option = array('省、直辖市','选择城市','选择区、县');
        $tier = 1;
        $region = M('region');
        $rMap = $region->field(array('region_id','region_name'))->where(array('parent_id'=>1))->select();
        // 地区层
        $this->tier=$tier;
        $this->name=$name;
        $this->layer=$layer;
        $this->option=$option;
        $this->rid=$rid;
        $this->rMap=$rMap;
        $this->display(T('Common/region'));
    }
}