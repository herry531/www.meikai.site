<?php

namespace App\Admin\Extensions\Tools;

use App\Spending;
use App\Task;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class ShowArtwork extends AbstractTool
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
        $res = Spending::get(['price']);

//        $countUser = count($test);


        $data = [];
        foreach($res as $key => $value){
            $data[] = $value['price'];
        }

        $total = array_sum($data);

        return $total;

    }

    public function render()
    {

        $data  = $this->script();


        return view('admin.tools.button', compact('data'));
    }
}
