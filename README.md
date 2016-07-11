# laravel5 后台基础系统

这是一个基于laravel 5.1框架的后台基础系统，**不定期更新**，如果你有好的idea，欢迎email我：mylampblog@163.com

请大家在分支中下载稳定版本，不要拉master的代码。

**适用对象：**

* phper
* 正在使用laravel5.1或正在学习它的朋友
* 相信php是世界上最好的语言（梗）

一、功能说明
--------------------------

主要包括：

* 登录验证
* 用户（组）管理
* 用户（组）权限
* 功能管理（同是也是后台菜单来源）
* 系统日志
* 文件上传
* 工作流
* 目前还附加了简单的blog功能

可以快速基于此系统进行laravel5的快速开发，免去每次都写一次后台基础的痛苦

**1、用户组的层级关系**

用户（组）的管理有等级的层级关系，底等级的或评级的用户是无法修改高等级的用户（组）信息。

**2、权限给予**

对用户进行权限给予的时候只能给予自己所拥有的权限。

**3、关于工作流**

工作流暴露给外面的接口主要有：

* App\Services\Admin\Workflow\Check::checkAcl();
* App\Services\Admin\Workflow\Check::getComfirmStatus();

在使用前请先在**工作流管理**中进行设置

使用demo

	$check = new \App\Services\Admin\Workflow\Check();
	//检测有没有权限
	//W_sdfg代表的是工作流管理中的调用代码
	//第二个参数代表得是当前的审核的值
	//一般来说或有一个字段来保存这个值，进行判断的时候拿出来，传到第二个参数即可
	$is = $check->checkAcl('W_sdfg', [0]);
	//如果有权限，会返回true，反之false
	var_dump($is);
	//下一步所要设置的信息
	//参数和上面的一个意思，只不过第二个参数不是array
	$next = $check->getComfirmStatus('W_sdfg', 1);
	//返回
	// <code>
    //     $result = ['is_final' => false, 'status' => 2];
    // </code

    //is_final代表是不是到了最终一步了，status是这一步要设置的值，如果是最后一步，值为99
	var_dump($next);

**4、日志**

只需在你想使用日志记录的时候，手动调用

	$this->setActionLog(['groupInfos' => $groupInfos]);

这里可以传入一个参数。

具体的实现则在app/services/admin/actionlog/中，里面的命名规则为

	$logNamespace = '\\App\\Services\\Admin\\ActionLog\\';
    $manager = $logNamespace.$module.'\\'.$class.'\\'.$action;

 里面的实现必须继承

 	App\Services\Admin\AbstractActionLog

 **5、博客**

 博客只实现了发布以评论。

 **6、关于权限缓存**

 目前登录后权限会缓存在session中，所以重新给权限后需要重新登录。

二、如何安装
--------------------------

1、建立数据库名为：mrblog

2、把包目录下的mrblog.sql导到库mrblog中。

3、复制doc/.env文件到根目录下，修改数据库连接为你的信息。

4、默认使用的域名为admin.opcache.net和www.opcache.net，如果你需要修改，那么修改文件

    config/sys.php

中的值

    sys_images_domain
    sys_admin_domain
    sys_blog_domain
    sys_blog_nopre_domain


5、配置你的域名并指向目录public

6、默认后台密码，其实是md5，你可以改你想要的。

帐号：admin
密码：11


三、额外的
--------------------------------

如果你使用blog的搜索功能的话，你需要安装php扩展sphinx，同时安装sphinx服务

* sphinx 主要用于博客的搜索
* 自行安装与配置sphinx（如果你使用blog的搜索功能的话），这是建议的配置 doc/sphinx.conf

在mrblog.sql文件里面也有相关文章，安装后再内容管理中可以查看。

四、需要的环境要求
---------------------------------
至少满足laravel 5.1框架的要求。

五、更新日志
------------------------------------

**release/v0.1.0.20150707**

* 登录验证
* 用户（组）管理
* 用户（组）权限
* 功能管理（同是也是后台菜单来源）
* 系统日志
* 文件上传
* 工作流
* 目前还附加了简单的blog功能

六、一点预览
------------------------------------

![enter image description here](http://static.oschina.net/uploads/space/2015/0707/125515_Kdi6_1777357.png)

![enter image description here](http://static.oschina.net/uploads/space/2015/0707/125516_rtVg_1777357.png)

![enter image description here](http://static.oschina.net/uploads/space/2015/0707/125516_7Kqi_1777357.png)

![enter image description here](http://static.oschina.net/uploads/space/2015/0707/125516_Eboi_1777357.png)

![enter image description here](http://static.oschina.net/uploads/space/2015/0707/125516_HeWC_1777357.png)

![enter image description here](http://static.oschina.net/uploads/space/2015/0707/125517_c5sd_1777357.png)

![enter image description here](http://static.oschina.net/uploads/space/2015/0707/125517_D1Ra_1777357.png)