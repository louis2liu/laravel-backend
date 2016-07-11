<?php namespace App\Http\Controllers\Admin\Blog;

use Request, Lang;
use App\Models\Admin\Content as ContentModel;
use App\Models\Admin\Category as CategoryModel;
use App\Services\Admin\Content\Process as ContentActionProcess;
use App\Libraries\Js;
use App\Http\Controllers\Admin\Controller;

/**
 * 登录相关
 *
 * @author jiang <mylampblog@163.com>
 */
class ContentController extends Controller
{
    /**
     * 显示首页
     */
    public function index()
    {
        $list = (new ContentModel())->AllContents();
        $page = $list->setPath('')->appends(Request::all())->render();
        return view('admin.content.index', compact('list', 'page'));
    }

    /**
     * 增加文章
     *
     * @access public
     */
    public function add()
    {
        if(Request::method() == 'POST') return $this->saveDatasToDatabase();
        $classifyInfo = (new CategoryModel())->activeCategory();
        $formUrl = R('common', 'blog.content.add');
        return view('admin.content.add', compact('formUrl', 'classifyInfo'));
    }
    
    /**
     * 增加文章入库处理
     *
     * @access private
     */
    private function saveDatasToDatabase()
    {
        $data = (array) Request::input('data');
        $data['tags'] = explode(';', $data['tags']);
        $param = new \App\Services\Admin\Content\Param\ContentSave();
        $param->setAttributes($data);
        $manager = new ContentActionProcess();
        if($manager->addContent($param) !== false) return Js::locate(R('common', 'blog.content.index'), 'parent');
        return Js::error($manager->getErrorMessage());
    }

    /**
     * 删除文章
     *
     * @access public
     */
    public function delete()
    {
        if( ! $id = Request::input('id')) return responseJson(Lang::get('common.action_error'));
        if( ! is_array($id)) $id = array($id);
        $manager = new ContentActionProcess();
        if($manager->detele($id)) return responseJson(Lang::get('common.action_success'), true);
        return responseJson($manager->getErrorMessage());
    }

    /**
     * 编辑文章
     *
     * @access public
     */
    public function edit()
    {
        if(Request::method() == 'POST') return $this->updateDatasToDatabase();
        $id = Request::input('id');
        if( ! $id or ! is_numeric($id)) return Js::error(Lang::get('common.illegal_operation'));
        $info = (new ContentModel())->getContentDetailByArticleId($id);
        if(empty($info)) return Js::error(Lang::get('content.not_found'));
        $classifyInfo = (new CategoryModel())->activeCategory();
        $info = $this->joinArticleClassify($info);
        $info = $this->joinArticleTags($info);
        $formUrl = R('common', 'blog.content.edit');
        return view('admin.content.add', compact('info', 'formUrl', 'id', 'classifyInfo'));
    }

    /**
     * 取回当前文章的所属分类
     * 
     * @param  array $articleInfo 当前文章的信息
     * @return array              整合后的当前文章信息
     */
    private function joinArticleClassify($articleInfo)
    {
        $classifyInfo = (new ContentModel())->getArticleClassify($articleInfo['id']);
        $classifyIds = [];
        foreach ($classifyInfo as $key => $value)
        {
            $classifyIds[] = $value['classify_id'];
        }
        $articleInfo['classifyInfo'] = $classifyIds;
        return $articleInfo;
    }

    /**
     * 取回当前文章的所属标签
     * 
     * @param  array $articleInfo 当前文章的信息
     * @return array              整合后的当前文章信息
     */
    private function joinArticleTags($articleInfo)
    {
        $tagsInfo = (new ContentModel())->getArticleTag($articleInfo['id']);
        $tagsIds = [];
        foreach ($tagsInfo as $key => $value)
        {
            $tagsIds[] = $value['name'];
        }
        $articleInfo['tagsInfo'] = $tagsIds;
        return $articleInfo;
    }
    
    /**
     * 编辑文章入库处理
     *
     * @access private
     */
    private function updateDatasToDatabase()
    {
        $data = (array) Request::input('data');
        $id = intval(Request::input('id'));
        $data['tags'] = explode(';', $data['tags']);
        $param = new \App\Services\Admin\Content\Param\ContentSave();
        $param->setAttributes($data);
        $manager = new ContentActionProcess();
        if($manager->editContent($param, $id) !== false) return Js::locate(R('common', 'blog.content.index'), 'parent');
        return Js::error($manager->getErrorMessage());
    }

}