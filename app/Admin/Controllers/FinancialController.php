<?php

namespace App\Admin\Controllers;

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

class FinancialController extends Controller
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


            $content->header('财务进账');
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

            $content->header('财务进账');
            $content->description('编辑信息');

            $content->body($this->form()->edit($id));
        });
    }
    public function show($id, Content $content)
    {

        return $content->header('Post')
            ->description('详情')
            ->body(Admin::show(Financial::findOrFail($id), function (Show $show) {

                $show->panel()
                    ->tools(function ($tools) {
                        $tools->disableEdit();

                        $tools->disableDelete();
                    });;

                $show->panel()
                    ->style('danger')
                    ->title('基本信息');
                $show->product('进账产品');
                $show->price('价格');
                $show->source('来源');

                $show->date('日期');
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

            $content->header('财务进账');


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
        return Admin::grid(Financial::class, function (Grid $grid) {
            $grid->product('进账产品');
            $grid->price('价格');
            $grid->source('来源');
            $grid->column('images')->urlWrapper();
            $grid->note('备注');
            $grid->date('日期');
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

        return Admin::form(Financial::class, function (Form $form) {

            $form->row(function ($row) use ($form) {
                $row->width(2)->text('product', '产品')->rules('required');
                $row->width(2)->currency('price', '价格')->symbol('￥')->rules('required');
                $row->width(2)->text('source','来源');
                $row->width(2)->text('note','备注');
                $row->width(4)->date('date','日期')->rules('required');
                $row->width(8)->image('images','照片');
            }, $form);
            $form->tools(function (Form\Tools $tools) {
                // 去掉`删除`按钮
                $tools->disableDelete();

            });
        });


    }
}
