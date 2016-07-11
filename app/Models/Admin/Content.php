<?php namespace App\Models\Admin;

use App\Models\Admin\Base;

/**
 * 文章表模型
 *
 * @author jiang
 */
class Content extends Base
{
    /**
     * 文章数据表名
     *
     * @var string
     */
    protected $table = 'article_main';

    /**
     * 可以被集体附值的表的字段
     *
     * @var string
     */
    protected $fillable = array('id', 'user_id', 'title', 'summary', 'head_pic', 'little_head_pic', 'write_time', 'is_delete', 'status');

    /**
     * 文章未删除的标识
     */
    CONST IS_DELETE_NO = 1;

    /**
     * 文章删除的标识
     */
    CONST IS_DELETE_YES = 0;
    
    /**
     * 取得未删除的信息
     *
     * @return array
     */
    public function AllContents()
    {
        $currentQuery = $this->select(array('article_main.*','users.name'))->leftJoin('users', 'article_main.user_id', '=', 'users.id')
                            ->orderBy('article_main.id', 'desc')->where('article_main.is_delete', self::IS_DELETE_NO)->paginate(15);
        return $currentQuery;
    }

    /**
     * 增加文章
     * 
     * @param array $data 所需要插入的信息
     */
    public function addContent(array $data)
    {
        return $this->create($data);
    }

    /**
     * 修改文章
     * 
     * @param array $data 所需要插入的信息
     */
    public function editContent(array $data, $id)
    {
        return $this->where('id', '=', intval($id))->update($data);
    }

    /**
     * 取得指定ID信息
     * 
     * @param intval $id 文章的ID
     * @return array
     */
    public function getOneById($id)
    {
        return $this->where('id', '=', intval($id))->first();
    }

    /**
     * 批量软删除
     */
    public function solfDeleteContent(array $data, array $ids)
    {
        return $this->whereIn('id', $ids)->update($data);
    }

    /**
     * 取得一篇文章主表和副表的信息
     *
     * @param int $articleId 文章的ID
     * @return array
     */
    public function getContentDetailByArticleId($articleId)
    {
        $articleId = (int) $articleId;
        $currentQuery = $this->select(array('article_main.*','article_detail.content'))
                ->leftJoin('article_detail', 'article_main.id', '=', 'article_detail.article_id')
                ->where('article_main.id', $articleId)->first();
        $info = $currentQuery->toArray();
        return $info;
    }

    /**
     * 取得文章所属的标签
     * 
     * @param int $articleId 文章的ID
     * @return  array 文章的分类
     */
    public function getArticleClassify($articleId)
    {
        $articleId = (int) $articleId;
        $currentQuery = $this->from('article_classify_relation')->select(array('article_classify_relation.classify_id','article_classify.name'))
                ->leftJoin('article_classify', 'article_classify_relation.classify_id', '=', 'article_classify.id')
                ->where('article_classify_relation.article_id', $articleId)->get();
        $classify = $currentQuery->toArray();
        return $classify;
    }

    /**
     * 取得文章所属的标签
     * 
     * @param int 文章的ID
     * @return  array 文章的标签
     */
    public function getArticleTag($articleId)
    {
        $articleId = (int) $articleId;
        $currentQuery = $this->from('article_tag_relation')->select(array('article_tag_relation.tag_id', 'article_tags.name'))
              ->leftJoin('article_tags', 'article_tag_relation.tag_id', '=', 'article_tags.id')
              ->where('article_tag_relation.article_id', $articleId)->get();
        $tags = $currentQuery->toArray();
        return $tags;
    }

}
