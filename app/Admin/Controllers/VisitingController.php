<?php

namespace App\Admin\Controllers;

use App\Visiting;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\ExcelExpoter;

use App\Models\Post;

use Encore\Admin\Show;

class VisitingController extends Controller
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

            $content->header('来访客户信息');
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

            $content->header('来访客户信息');
            $content->description('编辑信息');

            $content->body($this->form()->edit($id));
        });
    }

    public function show($id, Content $content)
    {
        return $content->header('Post')
            ->description('详情')
            ->body(Admin::show(Visiting::findOrFail($id), function (Show $show) {
                $show->panel()
                    ->tools(function ($tools) {
                        $tools->disableEdit();
                        $tools->disableList();
                        $tools->disableDelete();
                    });;
                $show->panel()
                    ->style('danger')
                    ->title('基本信息');
                $show->name('姓名');
                $show->age('年龄');
                $show->gender('性别');
                $show->basis('基础');
                $show->gender('性别');
                $show->phone('电话');
                $show->weixin_num('微信号');
                $show->source('来源渠道');
                $show->city('所在地区');
                $show->purpose('学习目的');
                $show->course('意向课程');
                $show->care('在意问题点');
                $show->details('具体详细信息');
                $show->advice('对我们的建议');
                $show->visit_time('到访日期');
                $show->plan_time('计划学习时间');

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

            $content->header('来访客户信息');
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
        return Admin::grid(Visiting::class, function (Grid $grid) {


            $grid->column('姓名')->display(function ($name) {
                if($this->status =='3'){
                    return "<span class='label label-danger'>$this->name</span>";

                }elseif($this->status=='2'){
                    return "<span class='label label-warning'>$this->name</span>";
                }elseif($this->status=='1') {
                    return "<span class='label label-default'>$this->name</span>";
                }elseif($this->status == '4'){
                    return "<span class='label label-success'>$this->name</span>";
                }else{
                    return "<span >$this->name</span>";
                }
            });
            $grid->model()->orderBy('created_at', 'desc');
                $grid->model()->orderBy('status', 'desc');
                $grid->model()->orderBy('rate', 'desc');
                //
                $grid->rate('星级')->display(function ($rate) {
                    $html = "<i class='fa fa-star' style='color:#ff8913'></i>";

                    if ($rate < 1) {
                        return '';
                    }

                    return join('&nbsp;', array_fill(0, min(5, $rate), $html));
                });

                $grid->disableExport();
                $grid->age('年龄');
                $grid->gender('性别');
                $grid->phone('电话');
                $grid->weixin_num('微信号');
                $grid->source('来源渠道');
                $grid->course('意向课程');
                $grid->care('在意问题点');
                $grid->visit_time('到访日期');

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
                    $filter->like('name','姓名');
                    $filter->like('phone','电话');
                    $filter->like('weixin_num','微信号');
                    $filter->like('source','来源渠道');
                    $filter->like('visitors','是否到访');
                    $filter->date('visit_time','到访时间');
                });
                $grid->paginate(15);

            $excel = new ExcelExpoter();
            $excel->setAttr(['姓名','性别','年龄','电话','微信号','基础','来源渠道','学习目的','意向课程','职业','在意问题点','详细信息','对我们的建议','到访日期','计划学习时间'], ['name','gender','age','weixin_num','source','purpose','course','professional','care','details','advice','visit_time','plan_time']);
            $grid->exporter($excel);

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */


    protected function form()
    {

        return Admin::form(Visiting::class, function (Form $form) {


                $form->text('name', '姓名');
                $form->select('age', '年龄')->options(['20岁以下'=>'20岁以下','20-30岁'=>'20-30岁','30-40岁'=>'30-40岁','40岁+'=>'40岁+']);
                $form->select('gender','性别')->options(['男'=>'男','女'=>'女'])->rules('required');
                $form->select('basis','基础')->options(['零基础'=>'零基础','有基础'=>'有基础'])->rules('required');
                $form->number('rate','重要星级');

                $form->mobile('phone', '电话');
                $form->text('weixin_num','微信号');
                $form->select('source','报名来源')->options(['百度'=>'百度','微博'=>'微博','今日头条'=>'今日头条','360搜索'=>'360搜索','豆瓣'=>'豆瓣','公众号'=>'公众号','朋友圈'=>'朋友圈','抖音'=>'抖音','搜狐'=>'搜狐','美团'=>'美团','大众点评'=>'大众点评','熟人介绍'=>'熟人介绍','学员介绍'=>'学员介绍','其它'=>'其它'])->rules('required');
                $form->text('city','所在地区');
                $form->text('purpose','学习目的');
                $form->text('course','意向课程');

                $form->text('care','在意问题点');
                $form->text('advice','对我们的建议');
                $form->date('visit_time','到访日期');
                $form->select('status','重要程度')->options(['1'=>'无意向','2'=>'跟进','3'=>'重要客户','4'=>'成交客户']);

                $form->select('plan_time', '计划学习时间')->options(['1个月内'=>'1个月内','3个月内'=>'3个月内','半年内'=>'半年内','1年内'=>'1年内','其它'=>'其它']);;
                $form->text('details','具体详细信息');


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
