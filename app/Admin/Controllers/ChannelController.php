<?php

namespace App\Admin\Controllers;

use App\Channel;
use App\Visiting;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\ExcelExpoter;

class ChannelController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('每日客户来源信息');
            $content->description('');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('每日客户来源信息');
            $content->description('编辑信息');

            $content->body($this->form()->edit($id));
        });
    }


    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('每日客户来源信息');
            $content->description('新增');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Channel::class, function (Grid $grid) {
            $grid->model()->orderBy('date', 'desc');
            $grid->source('客户来源');
            $grid->num('人数');
            $grid->date('日期');
            $grid->note('备注');
            $grid->disableExport();



//            $grid->tools(function ($tools) {
//                $tools->batch(function ($batch) {
//                    $batch->disableDelete();
//                });
//            });
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            $grid->actions(function ($actions) {
                // append一个操作

                $actions->disableDelete();
            });
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('source','客户来源');
            });
            $grid->paginate(15);

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */



    protected function form()
    {

        return Admin::form(Channel::class, function (Form $form) {



                $form->select('source','客户来源')->options(['百度'=>'百度','微博'=>'微博','今日头条'=>'今日头条','360搜索'=>'360搜索','豆瓣'=>'豆瓣','公众号'=>'公众号','朋友圈'=>'朋友圈','抖音'=>'抖音','搜狐'=>'搜狐','美团'=>'美团','大众点评'=>'大众点评','熟人介绍'=>'熟人介绍','学员介绍'=>'学员介绍','其它'=>'其它'])->rules('required');
                $form->number('num','人数')->rules('required');;
                $form->date('date','日期')->rules('required');;
                $form->text('note','备注');


            $form->tools(function (Form\Tools $tools) {

                // 去掉`列表`按钮
                $tools->disableList();

                // 去掉`删除`按钮
                $tools->disableDelete();

                // 去掉`查看`按钮
                $tools->disableView();


            });
            $form->footer(function ($footer) {

                // 去掉`重置`按钮
//            $footer->disableReset();

                // 去掉`提交`按钮
//            $footer->disableSubmit();

                // 去掉`查看`checkbox
                $footer->disableViewCheck();

                // 去掉`继续编辑`checkbox
                $footer->disableEditingCheck();

                // 去掉`继续创建`checkbox
                $footer->disableCreatingCheck();

            });
        });


    }
}
