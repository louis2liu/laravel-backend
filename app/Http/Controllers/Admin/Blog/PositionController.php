<?php namespace App\Http\Controllers\Admin\Blog;

use Request, Lang;
use App\Models\Admin\Position as PositionModel;
use App\Services\Admin\Position\Process as PositionActionProcess;
use App\Libraries\Js;
use App\Http\Controllers\Admin\Controller;

/**
 * 文章推荐位相关
 *
 * @author jiang <mylampblog@163.com>
 */
class PositionController extends Controller
{
    /**
     * 显示推荐位列表
     */
    public function index()
    {
    	$list = (new PositionModel())->unDeletePosition();
    	$page = $list->setPath('')->appends(Request::all())->render();
        return view('admin.content.position', compact('list', 'page'));
    }

    /**
     * 增加推荐位分类
     */
    public function add()
    {
    	if(Request::method() == 'POST') return $this->saveDatasToDatabase();
        $formUrl = R('common', 'blog.position.add');
        return view('admin.content.positionadd', compact('formUrl'));
    }

    /**
     * 增加推荐位入库处理
     *
     * @access private
     */
    private function saveDatasToDatabase()
    {
        $data = (array) Request::input('data');
        $param = new \App\Services\Admin\Position\Param\PositionSave();
        $param->setAttributes($data);
        $manager = new PositionActionProcess();
        if($manager->addPosition($param) !== false) return Js::locate(R('common', 'blog.position.index'), 'parent');
        return Js::error($manager->getErrorMessage());
    }

    /**
     * 编辑文章推荐位
     */
    public function edit()
    {
    	if(Request::method() == 'POST') return $this->updateDatasToDatabase();
        $id = Request::input('id');
        if( ! $id or ! is_numeric($id)) return Js::error(Lang::get('common.illegal_operation'));
        $info = (new PositionModel())->getOneById($id);
        if(empty($info)) return Js::error(Lang::get('position.not_found'));
        $formUrl = R('common', 'blog.position.edit');
        return view('admin.content.positionadd', compact('info', 'formUrl', 'id'));
    }

    /**
     * 编辑推荐位入库处理
     *
     * @access private
     */
    private function updateDatasToDatabase()
    {
        $data = Request::input('data');
        if( ! $data or ! is_array($data)) return Js::error(Lang::get('common.illegal_operation'));
        $param = new \App\Services\Admin\Position\Param\PositionSave();
        $param->setAttributes($data);
        $manager = new PositionActionProcess();
        if($manager->editPosition($param)) return Js::locate(R('common', 'blog.position.index'), 'parent');
        return Js::error($manager->getErrorMessage());
    }

    /**
     * 删除文章推荐位
     *
     * @access public
     */
    public function delete()
    {
        if( ! $id = Request::input('id')) return responseJson(Lang::get('common.action_error'));
        if( ! is_array($id)) $id = array($id);
        $manager = new PositionActionProcess();
        if($manager->detele($id)) return responseJson(Lang::get('common.action_success'), true);
        return responseJson($manager->getErrorMessage());
    }

}