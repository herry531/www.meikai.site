<?php

namespace App\Admin\Controllers;

use App\Movie;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class MovieController extends Controller
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
        $grid = new Grid(new Movie);

//        $grid->id('序号')->sortable();;
        $grid->column('姓名')->display(function ($name) {
            if($this->status =='已经来访'){
                return "<span class='label label-success'>$this->name</span>";

            }elseif($this->status=='需要跟进'){
                return "<span class='label label-danger'>$this->name</span>";
            }elseif($this->status=='三次沟通都无意向') {
                return "<span class='label label-warning'>$this->name</span>";
            }elseif($this->status=='未沟通'){
                return "<span >$this->name</span>";
            }else{
                return "<span >$this->name</span>";
            }
        });

        $grid->gender('性别');
        $grid->city('地区');
        $grid->age('年龄');
        $grid->phone('电话');
        $grid->purpose('学习目的');

        $grid->source('来源渠道');
        $grid->course('意向课程');

        $grid->one_data('沟通时间')->sortable();
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
        $show = new Show(Movie::findOrFail($id));

        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();

                $tools->disableDelete();
            });;

        $show->panel()
            ->style('danger')
            ->title('基本信息');
        $show->name('姓名');
        $show->age('年龄');

        $show->gender('性别');

        $show->phone('电话');
        $show->weixin_name('微信名称');
        $show->weixin_num('微信号');
        $show->source('渠道');
        $show->city('地区');
        $show->purpose('学习目的');
        $show->course('意向课程');
        $show->care('在意问题点');
        $show->text('具体详细信息');
        $show->one_data('初次沟通时间');
        $show->tow_data('二次沟通时间');
        $show->three_data('三次沟通时间');


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Movie);

        $form->text('name', '姓名');
        $form->radio('age', '年龄')->options(['20岁以下'=>'20岁以下','20-30岁'=>'20-30岁','30-40岁'=>'30-40岁','40岁+'=>'40岁+']);
        $form->radio('gender','性别')->options(['男'=>'男','女'=>'女','未知'=>'未知'])->rules('required');

        $form->radio('status','跟进程度')->options(['三次沟通都无意向'=>'三次沟通都无意向','需要跟进'=>'需要跟进','已经来访'=>'已经来访','未沟通'=>'未沟通'])->rules('required');
        $form->mobile('phone', '电话');
        $form->text('weixin_name','微信名称');
        $form->text('weixin_num','微信号');
        $form->select('source','报名来源')->options(['百度'=>'百度','微博'=>'微博','今日头条'=>'今日头条','360搜索'=>'360搜索','豆瓣'=>'豆瓣','公众号'=>'公众号','朋友圈'=>'朋友圈','抖音'=>'抖音','搜狐'=>'搜狐','美团'=>'美团','大众点评'=>'大众点评','熟人介绍'=>'熟人介绍','学员介绍'=>'学员介绍','其它'=>'其它'])->rules('required');
        $form->text('city','所在地区');
        $form->text('purpose','学习目的');
        $form->text('course','意向课程');

        $form->text('care','在意问题点');
        $form->text('text','具体详细信息');

        $form->date('one_data','初次沟通时间');
        $form->date('tow_data','二次沟通时间');
        $form->date('three_data','三次沟通时间');

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
