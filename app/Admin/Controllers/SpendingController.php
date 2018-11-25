<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\Balance;
use App\Admin\Extensions\Tools\Income;
use App\Admin\Extensions\Tools\ShowArtwork;
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
use Illuminate\Support\Facades\DB;

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

        return $content->header('财务出账')
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
                $show->project('财务大类');
                $show->detail('二级分类');
                $show->detail1('三级分类');
                $show->other('明细');
                $show->price('价格');
                $show->name('支出人');
                $show->baoxiao('是否已报销');


                $show->reimburse('报销日期');
                $show->date('消费日期');
                $show->images('照片')->image();
                $show->images1('备注照片')->image();
                $show->note('备注');


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
            $grid->detail('二级分类');
            $grid->detail1('三级分类');
            $grid->other('明细');
            $grid->price('价格');
            $grid->name('支出人');
            $grid->baoxiao('是否报销');
            $grid->date('日期')->sortable();;
            $grid->column('images')->urlWrapper();
//            $grid->images('照片')->image('http://www.meikai.site/upload', 100, 100);

            //$res = $grid->getFilter()->execute();

            $grid->tools(function ($tools) {
                $tools->append(new ShowArtwork());
            });

            $grid->tools(function ($tools) {
                $tools->append(new Income());
            });

            $grid->tools(function ($tools) {
                $tools->append(new Balance());
            });


            $grid->disableExport();
//            $grid->tools(function ($tools) {
//                $tools->batch(function ($batch) {
//                    $batch->disableDelete();
//                });
//            });
            $grid->actions(function ($actions) {
                // append一个操作
                $actions->disableDelete();
            });
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();


//                $filter->equal('project', '财务大类')
//                    ->select(ChinaArea::province()->pluck('name', 'id'));

                $filter->equal('project','财务大类')->select(['管理费用' => '管理费用','销售费用'=>'销售费用','财务费用'=>'财务费用','原材料费用'=>'原材料费用','设备工具费用'=>'设备工具费用']);
//                $filter->equal('detail', '二级分类')->select()
//                    ->load('detail1', '/admin/api/district');

//                $filter->equal('detail1', '三级分类')->select();

                $filter->equal('name', '支出人')
                    ->select(['海鸥'=>'海鸥','老鹰'=>'老鹰','猞猁'=>'猞猁','刺猬'=>'刺猬','海龟'=>'海龟']);

                $filter->equal('baoxiao', '是否报销')
                    ->select(['是'=>'是','否'=>'否','不报销'=>'不报销']);

                $filter->between('date','日期')->date();
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


//            $form->select('address.province_id','财务大类')->select(ChinaArea::province()->pluck('name', 'id'))->load('address.city_id', '/admin/api/city')->rules('required');

            $form->select('project','财务大类')->options(

                ChinaArea::province()->pluck('name', 'id')

            )->load('detail', '/admin/api/city');

            $form->select('detail','二级分类')->options(function ($id) {

                return ChinaArea::options($id);

            })->load('detail1', '/admin/api/district');

            $form->select('detail1','三级分类')->options(function ($id) {

                return ChinaArea::options($id);

            });
            $form->text('other','明细');





                $form->currency('price', '价格')->symbol('￥')->rules('required');

                $form->select('name','支出人')->options(['海鸥'=>'海鸥','老鹰'=>'老鹰','猞猁'=>'猞猁','刺猬'=>'刺猬','海龟'=>'海龟'])->rules('required');
                $form->select('baoxiao','是否已报销')->options(['是'=>'是','否'=>'否','不报销'=>'不报销'])->rules('required');

                $form->date('reimburse','报销日期');
                $form->date('date','消费日期')->rules('required');
                $form->image('images','照片');
                $form->image('images1','备注照片');
//            $form->cropper('content','label');//裁剪
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

            $form->saving(function (Form $form) {

                $project = ChinaArea::where('id',$form->project)->first();
                $form->project = $project->name;

                $detail =  ChinaArea::where('id',$form->detail)->first();
                $form->detail  = $detail->name;

                $detail1 =  ChinaArea::where('id',$form->detail1)->first();
                $form->detail1 = $detail1->name;



            });

        });


    }




}
