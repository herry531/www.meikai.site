<?php

namespace App\Admin\Controllers;

use App\ChinaArea;
use App\Financial;
use App\Movie;

use App\Spending;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\ExcelExpoter;
use Encore\Admin\Show;

class SpendingController extends Controller
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


            $content->header('财务出账');
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

            $content->header('财务出账');
            $content->description('编辑信息');

            $content->body($this->form()->edit($id));
        });
    }
    public function show($id, Content $content)
    {

        return $content->header('Post')
            ->description('详情')
            ->body(Admin::show(Spending::findOrFail($id), function (Show $show) {

                $show->panel()
                    ->tools(function ($tools) {
                        $tools->disableEdit();

                        $tools->disableDelete();
                    });;

//                $show->panel()
//                    ->style('danger')
//                    ->title('基本信息');
                $show->project('项目');
                $show->detail('项目明细');
                $show->detail1('项目明细一');
                $show->detail2('项目明细二');
                $show->price('价格');
                $show->name('支出人');
                $show->baoxiao('报销');

                $show->baoxiao('报销');
                $show->date('日期');
                $show->images('照片')->urlWrapper();


            }));
    }


    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('财务出账');


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
        return Admin::grid(Spending::class, function (Grid $grid) {
            $grid->project('支出项目');
            $grid->detail('项目明细');
            $grid->price('价格');
            $grid->name('支出人');
            $grid->baoxiao('是否报销');

            $grid->column('images')->urlWrapper();
            $grid->note('备注');
            $grid->date('日期');
            $grid->disableExport();
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            $grid->actions(function ($actions) {
                // append一个操作

                $actions->disableDelete();
            });
        });
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */

    protected function form()
    {

        return Admin::form(Spending::class, function (Form $form) {


            $form->select('project','支出选项')->options(['管理费用'=>'管理费用','报销费用'=>'销售费用','财务费用'=>'财务费用','原材料费'=>'原材料费','设备费用'=>'设备费用'])->load('detail', '/admin/api/city')->rules('required');

            $form->select('detail','项目明细')->load('detail1', '/admin/api/city')->rules('required');

            $form->select('detail1','项目明细一')->load('detail2', '/admin/api/city');
            $form->select('detail2','项目明细二');

                $form->currency('price', '价格')->symbol('￥')->rules('required');

                $form->select('name','支出人')->options(['海鸥'=>'海鸥','老鹰'=>'老鹰','猞猁'=>'猞猁','刺猬'=>'刺猬','海龟'=>'海龟'])->rules('required');
                $form->select('baoxiao','是否报销')->options(['是'=>'是','否'=>'否','不报销'=>'不报销'])->rules('required');


                $form->date('date','日期')->rules('required');
                $form->image('images','照片');
                $form->text('note','备注');

            $form->tools(function (Form\Tools $tools) {
                // 去掉`删除`按钮
                $tools->disableDelete();

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
