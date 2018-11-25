<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\Balance;
use App\Admin\Extensions\Tools\Income;
use App\Admin\Extensions\Tools\ShowArtwork;

use App\Admin\Extensions\Tools\UserGender;
use App\Http\Controllers\Controller;
use App\Task;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Box;
use Illuminate\Auth\EloquentUserProvider;

class TaskController extends Controller
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
            ->header('任务活动')
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
            ->header('任务活动')
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
            ->header('任务活动')
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
            ->header('任务活动')
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
        $grid = new Grid(new Task());

//        hasGetMutator




        $grid->name('微信');
        $grid->num('暗号');
        $grid->task('任务人级别');
        $grid->nums('完成任务人数')->editable();;


        $grid->created_at('添加时间');
        $grid->disableExport();
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->filter(function ($filter) {

            $filter->like('name','微信');
            $filter->like('num','暗号');
            $filter->equal('task', '主次任务人')
                ->select(['一级任务人'=>'一级主任务人','二级任务人'=>'二级任务人']);

        });

        $grid->tools(function ($tools) {
            $tools->append(new ShowArtwork());
        });

        $grid->tools(function ($tools) {
            $tools->append(new Income());
        });

        $grid->tools(function ($tools) {
            $tools->append(new Balance());
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
        $show = new Show(Task::findOrFail($id));

        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();

                $tools->disableDelete();
            });;

        $show->panel()
            ->style('danger')
            ->title('基本信息');
        $show->name('微信');
        $show->task('任务人级别');
        $show->num('暗号');
        $show->nums('完成任务人数');




        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Task());

        $form->text('name', '微信');
        $form->radio('task','性别')->options(['一级任务人'=>'一级任务人','二级任务人'=>'二级任务人'])->rules('required');

        $form->number('num','暗号');
        $form->number('nums','完成任务人数');



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
