<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
use App\Admin\Extensions\Column\UrlWrapper;
use Encore\Admin\Grid\Column;
use App\Admin\Extensions\Column\ExpandRow;



Encore\Admin\Form::forget(['map', 'editor']);

Admin::js('/vendor/clipboard/dist/clipboard.min.js');

Column::extend('urlWrapper', UrlWrapper::class);

Column::extend('expand', ExpandRow::class);


Column::extend('prependIcon', function ($value, $icon) {

    return "<span style='color: #999;'><i class='fa fa-$icon'></i>  $value</span>";

});
