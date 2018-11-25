<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\Input;

use App\ChinaArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class ApiController extends Controller
{
    public function index()
    {

        echo 111;die;
        $num = Input::get('q');
        if($num == '管理费用'){
            $arr = array('1'=>'房租','2'=>'工资','3'=>'办公','4'=>'人员报销','5'=>'社保','6'=>'培训','7'=>'招聘');
//            return ['房租','工资','办公','人员报销','社保','培训'];
            return $arr;
        }
        if($num == '报销费用'){
            $arr = array('1'=>'网上推广','2'=>'活动推广','3'=>'回扣');
            return $arr;
        }
        if($num == '财务费用'){
            $arr = array('1'=>'转帐手续费','2'=>'税费','3'=>'外挂税务');
            return $arr;
        }
        if($num =='原材料费'){
            $arr = array('1'=>'面包房','2'=>'蛋糕西点','3'=>'水吧');
            return $arr;
        }

        //二级
        if($num == '水吧'){
            $arr = array('1'=>'水果类','2'=>'茶叶类');
            return $arr;
        }
        if($num == '面包房'){
            $arr = array('1'=>'面粉类','2'=>'水果类','3'=>'其它');
            return $arr;
        }

        //三级
        if($num == '水果类' || $num == '茶叶类'){
            $arr = array('1'=>'教学','2'=>'其它');
            return $arr;
        }


    }


    public function city(Request $request)
    {

        $provinceId = $request->get('q');

        return ChinaArea::city()->where('parent_id', $provinceId)->get(['id', DB::raw('name as text')]);
    }

    public function district(Request $request)
    {
        $cityId = $request->get('q');

        return ChinaArea::district()->where('parent_id', $cityId)->get(['id', DB::raw('name as text')]);
    }
}
