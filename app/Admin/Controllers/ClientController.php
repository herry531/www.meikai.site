<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ChinaArea;
use App\Models\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class ClientController extends Controller
{
    use HasResourceActions;

    /**
     * @var string
     */
    protected $title = 'Users';

    /**
     * Display a listing of the resource.
     *
     * @return Content
     */
    protected function index(Content $content)
    {
        return $content
            ->header($this->title)
            ->description('Index')
            ->body($this->grid());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header($this->title)
            ->description('Detail')
            ->body($this->detail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header($this->title)
            ->description('Edit')
            ->body($this->form()->edit($id));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header($this->title)
            ->description('Create')
            ->body($this->form());
    }

    public function grid()
    {
        $grid = new Grid(new User());

        $grid->model()->with('profile')->orderBy('id', 'DESC');

        $grid->paginate(20);

        $grid->id('ID')->sortable();

        $grid->name()->editable();

        $grid->column('expand')->expand(function () {

            if (empty($this->profile)) {
                return '';
            }

            $profile = array_only($this->profile->toArray(), ['homepage', 'gender', 'birthday', 'address', 'last_login_at', 'last_login_ip', 'lat', 'lng']);

            return new Table([], $profile);

        }, 'Profile');
//
//        $grid->column('position')->openMap(function () {
//
//            return [$this->profile['lat'], $this->profile['lng']];
//
//        }, 'Position');

        $grid->column('profile.homepage')->urlWrapper();

        $grid->email()->prependIcon('envelope');

        //$grid->profile()->mobile()->prependIcon('phone');

        //$grid->column('profile.age')->progressBar(['success', 'striped'], 'xs')->sortable();

        $grid->profile()->age()->sortable();
        $grid->profile()->gender()->using(['f' => '女', 'm' => '男']);

        $grid->created_at();

        $grid->updated_at();

        $grid->filter(function (Grid\Filter $filter) {

            $filter->disableIdFilter();

            $filter->like('name');

            $filter->equal('address.province_id', 'Province')
                ->select(ChinaArea::province()->pluck('name', 'id'))
                ->load('address.city_id', '/demo/api/china/city');

            $filter->equal('address.city_id', 'City')->select()
                ->load('address.district_id', '/demo/api/china/district');

            $filter->equal('address.district_id', 'District')->select();

            $filter->scope('male')->whereHas('profile', function ($query) {
                $query->where('gender', 'm');
            });
            $filter->scope('female')->whereHas('profile', function ($query) {
                $query->where('gender', 'f');
            });

            $filter->scope('all_gender', 'Male & Female')->whereHas('profile', function ($query) {
                $query->whereIn('gender', ['f', 'm']);
            });

            $filter->scope('children')->whereHas('profile', function ($query) {
                $query->where('age', '<', 18);
            });

            $filter->scope('children')->whereHas('profile', function ($query) {
                $query->where('age', '<', 18);
            });

            $filter->scope('elderly')->whereHas('profile', function ($query) {
                $query->where('age', '>', 60);
            });
        });

        $grid->actions(function (Grid\Displayers\Actions $actions) {

            if ($actions->getKey() % 2 == 0) {
                $actions->disableDelete();
                $actions->append('<a href=""><i class="fa fa-paper-plane"></i></a>');
            } else {
                $actions->disableEdit();
                $actions->prepend('<a href=""><i class="fa fa-paper-plane"></i></a>');
            }
        });

        return $grid;
    }

    public function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->name();
        $show->avatar()->file();
        $show->username();
        $show->email();

        $show->divider();

        $show->created_at();
        $show->updated_at();

        $show->profile(function ($profile) {

            $profile->homepage()->link();
            $profile->mobile();
            $profile->avatar();
            $profile->document();
            $profile->gender();
            $profile->birthday();
            $profile->address();
            $profile->color();
            $profile->age();
            $profile->last_login_at();
            $profile->last_login_ip();

            $profile->created_at();
            $profile->updated_at();

        });

        $show->sns(function ($sns) {

            $sns->qq();
            $sns->wechat();
            $sns->weibo();
            $sns->github();
            $sns->google();
            $sns->facebook();
            $sns->twitter();

            $sns->created_at();
            $sns->updated_at();

        });

        $show->address(function ($address) {

            $address->province()->name('省份');
            $address->city()->name('城市');
            $address->district()->name('地区');
            $address->address();


            $address->created_at();
            $address->updated_at();

        });

        $show->friends(function ($friend) {

            $friend->resource('users');

            $friend->name();
            $friend->email();

        });

        return $show;
    }

    public function form()
    {





        return Admin::form(User::class, function (Form $form) {

            $form->row(function ($row) use ($form) {
                $row->width(4)->text('name', '姓名');
                $row->width(4)->select('age', '年龄')->options(['20岁以下'=>'20岁以下','20-30岁'=>'20-30岁','30-40岁'=>'30-40岁','40岁+'=>'40岁+']);
                $row->width(4)->select('gender','性别')->options(['男'=>'男','女'=>'女','未知'=>'未知'])->rules('required');

                $row->width(4)->mobile('phone', '电话');
                $row->width(4)->text('weixin_name','微信名称');
                $row->width(4)->text('weixin_num','微信号');
                $row->width(4)->select('source','报名来源')->options(['百度'=>'百度','微博'=>'微博','今日头条'=>'今日头条','360搜索'=>'360搜索','豆瓣'=>'豆瓣','公众号'=>'公众号','朋友圈'=>'朋友圈','抖音'=>'抖音','搜狐'=>'搜狐','美团'=>'美团','大众点评'=>'大众点评','熟人介绍'=>'熟人介绍','学员介绍'=>'学员介绍','其它'=>'其它'])->rules('required');
                $row->width(4)->text('city','所在地区');
                $row->width(4)->text('purpose','学习目的');
                $row->width(4)->text('course','意向课程');

                $row->width(4)->text('care','在意问题点');
                $row->width(4)->text('text','具体详细信息');

                $row->width(4)->date('one_data','初次沟通时间');
                $row->width(4)->date('tow_data','二次沟通时间');
                $row->width(4)->date('three_data','三次沟通时间');


            }, $form);
            $form->tools(function (Form\Tools $tools) {
                // 去掉`删除`按钮
                $tools->disableDelete();

            });
//            $form->footer(function ($footer) {
//
//                // 去掉`重置`按钮
//                $footer->disableReset();
//
//                // 去掉`提交`按钮
//                $footer->disableSubmit();
//
//                // 去掉`查看`checkbox
//                $footer->disableViewCheck();
//
//                // 去掉`继续编辑`checkbox
//                $footer->disableEditingCheck();
//
//                // 去掉`继续创建`checkbox
//                $footer->disableCreatingCheck();
//
//            });




        });
    }
}
