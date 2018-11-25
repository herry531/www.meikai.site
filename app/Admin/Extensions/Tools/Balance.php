<?php

namespace App\Admin\Extensions\Tools;

use App\Financial;
use App\Spending;
use App\Task;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class Balance extends AbstractTool
{
    protected  $countUser;
    protected  $total;
//    function __construct($countUser,$total)
//    {
//        $this->countUser = $countUser;
//        $this->total = $total;
//
//
//    }

    public function script()
    {
        $res = Financial::get(['price']);//收入
        $re = Spending::get(['price']);//支出

//        $countUser = count($test);


        $data = [];
        $dat = [];
        foreach($res as $key => $value){
            $data[] = $value['price'];
        }
        foreach($re as $key => $value){
            $dat[] = $value['price'];
        }



        $total = array_sum($data);

        $tota = array_sum($dat);

        $num  = $total - $tota;

        return $num;

    }

    public function render()
    {

        $data  = $this->script();


        return view('admin.tools.balance', compact('data'));
    }
}
