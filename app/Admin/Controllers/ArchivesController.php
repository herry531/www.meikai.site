<?php

namespace App\Admin\Controllers;

use App\Archives;
use App\Movie;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ArchivesController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */


    public function index(Content $content)
    {
        return $content
            ->header('客户信息')
            ->description('概览')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('客户信息')
            ->description('详情')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('客户信息')
            ->description('修改')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('客户信息')
            ->description('添加')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Archives());


        $grid->model()->orderBy('id', 'desc');
        $grid->name('姓名')->editable();
        $grid->gender('性别');
        $grid->phone('电话');
        $grid->course('学习课程');
        $grid->price1('已交学费');

        $grid->price2('未交学费');
        $grid->date1('计划学习时间');
        $grid->city('所在地区');
        $grid->column('images')->urlWrapper();
//        $grid->tools(function ($tools) {
//            $tools->batch(function ($batch) {
//                $batch->disableDelete();
//            });
//        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->actions(function ($actions) {
            // append一个操作

            $actions->disableDelete();
        });

        $grid->note('备注');


        $grid->filter(function ($filter) {

            $filter->like('name','姓名');
            $filter->like('phone','电话');
            $filter->like('weixin','微信号');

        });
        $grid->filter(function($filter){


        });

        $grid->paginate(15);
        $grid->disableExport();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Archives::findOrFail($id));

        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();

                $tools->disableDelete();
            });;

        $show->panel()
            ->style('danger')
            ->title('基本信息');
        $show->name('学员姓名');
        $show->phone('电话');
        $show->city('所在地区');
        $show->weixin('微信号');
        $show->course('学习课程');
        $show->price('总学费');
        $show->price1('已交学费');
        $show->price1('未交学费');
        $show->date('报名时间');
        $show->date1('预计学习时间');
        $show->date2('实际上课时间');
        $show->gender('性别');
        $show->shop('是否开店');
        $show->weixin('学员微信');
        $show->source('报名来源');
        $show->age('年龄');
        $show->purpose('学习目的');
        $show->age('年龄');
        $show->images('学员照片')->image();
        $show->images1('合同照片')->image();

        $show->content('毕业后跟进信息');
        $show->note('备注');



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Archives());

        $form->text('name', '学员姓名')->rules('required');
        $form->currency('price', '总学费')->rules('required')->symbol('￥');
        $form->currency('price1', '已交学费')->rules('required')->symbol('￥');
        $form->currency('price2', '未交学费')->rules('required')->symbol('￥');
        $form->date('date', '报名时间')->rules('required');
        $form->date('date1', '预计学习时间')->format('YYYY-MM');
        $form->date('date2', '实际上课时间');
        $form->radio('gender','学员性别')->options(['男'=>'男','女'=>'女'])->rules('required');
        $form->radio('shop','是否开店')->options(['是'=>'是','否'=>'否'])->rules('required');
        $form->radio('age', '年龄')->options(['20岁以下'=>'20岁以下','20-30岁'=>'20-30岁','30-40岁'=>'30-40岁','40岁+'=>'40岁+']);
        $form->radio('purpose','学习目的')->options(['创业'=>'创业','就业'=>'就业','私房'=>'私房','兴趣爱好'=>'兴趣爱好','其它'=>'其它'])->rules('required');
        $form->image('images','学员照片');
        $form->image('images1','合同照片');
        $form->mobile('phone', '学员电话')->rules('required');
        $form->text('weixin','学员微信')->rules('required');
        $form->select('source','报名来源')->options(['百度'=>'百度','微博'=>'微博','今日头条'=>'今日头条','360搜索'=>'360搜索','豆瓣'=>'豆瓣','公众号'=>'公众号','朋友圈'=>'朋友圈','抖音'=>'抖音','搜狐'=>'搜狐','美团'=>'美团','熟人介绍'=>'熟人介绍','学员介绍'=>'学员介绍','其它'=>'其它'])->rules('required');
        $form->text('city','所在地区')->rules('required');
        $form->text('course','学习课程')->rules('required');
        $form->text('content','毕业后跟进信息');
        $form->text('note','备注');





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
        $form->tools(function (Form\Tools $tools) {

            // 去掉`列表`按钮
//            $tools->disableList();

            // 去掉`删除`按钮
            $tools->disableDelete();

            // 去掉`查看`按钮
//            $tools->disableView();

            // 添加一个按钮, 参数可以是字符串, 或者实现了Renderable或Htmlable接口的对象实例
//            $tools->add('<a class="btn btn-sm btn-danger"><i class="fa fa-trash"></i>&nbsp;&nbsp;delete</a>');
        });

        return $form;
    }
}
