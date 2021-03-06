<?php

namespace App\Services;

use Route;

/**
 * 系统路由
 * ---------------------
 * 注：大部分的路由及控制器所执行的动作来说，你需要返回完整的 Illuminate\Http\Response 实例或是一个视图
 *
 * @author jiang <mylampblog@163.com>
 */
class Routes
{
    private $adminDomain = 'admin.opcache.net';

    private $wwwDomain = 'www.opcache.net';

    private $noPreDomain = 'opcache.net';

    /**
     * 初始化，取得配置
     */
    public function __construct()
    {
        $this->adminDomain = config('sys.sys_admin_domain');
        $this->wwwDomain = config('sys.sys_blog_domain');
        $this->noPreDomain = config('sys.sys_blog_nopre_domain');
    }

    /**
     * 后台的通用路由
     * --------------------------------------
     * 特别要注意的
     * 覆盖通用的路由的例子，一定要带上别名，且別名的值为module.class.action，即我们使用别名传入了当前请求所属的module,controller和action.
     * Route::get('index-index.html', ['as' => 'module.class.action', 'uses' => 'Admin\IndexController@index']);
     */
    public function admin()
    {
        Route::group(['domain' => $this->adminDomain], function()
        {
            Route::group(['middleware' => ['csrf']], function()
            {
                Route::get('/', 'Admin\Foundation\LoginController@index');
                Route::controller('login', 'Admin\Foundation\LoginController', ['getOut' => 'foundation.login.out']);
            });

            Route::group(['middleware' => ['auth', 'acl', 'alog']], function()
            {
                //通用的路由，如果要覆盖，写在些行之上
                Route::any('{module}-{class}-{action}.html', ['as' => 'common', function($module, $class, $action)
                {
                    $class = 'App\\Http\\Controllers\\Admin\\'.ucfirst(strtolower($module)).'\\'.ucfirst(strtolower($class)).'Controller';
                    if(class_exists($class))
                    {
                        $classObject = new $class();
                        if(method_exists($classObject, $action)) return call_user_func(array($classObject, $action));
                    }
                    return abort(404);
                }])->where(['module' => '[0-9a-z]+', 'class' => '[0-9a-z]+', 'action' => '[0-9a-z]+']);
            });
        });
        return $this;
    }

    /**
     * 博客通用路由
     * -------------------------
     * 这里必须要返回一个Illuminate\Http\Response 实例而非一个视图，原因是因为csrf中需要影响的必须为一个response
     */
    public function www()
    {
        $homeDoaminArray = ['home' => $this->wwwDomain, 'home_empty_prefix' => $this->noPreDomain];
        foreach($homeDoaminArray as $key => $value)
        {
            Route::group(['domain' => $value, 'middleware' => ['csrf']], function() use ($key)
            {
                Route::get('/', 'Home\IndexController@index');
                Route::any('{class}/{action}.html', ['as' => $key, function($class, $action)
                {
                    $class = 'App\\Http\\Controllers\\Home\\'.ucfirst(strtolower($class)).'Controller';
                    if(class_exists($class))
                    {
                        $classObject = new $class();
                        if(method_exists($classObject, $action))
                        {
                            $return = call_user_func(array($classObject, $action));
                            if( ! $return instanceof \Illuminate\Http\Response) return response($return);
                            return $return;
                        }
                    }
                    return abort(404);
                }])->where(['class' => '[0-9a-z]+', 'action' => '[0-9a-z]+']);
            });
        }
        return $this;
    }

}
