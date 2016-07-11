<?php namespace App\Models\Admin;

use App\Models\Admin\Base;

/**
 * 文章标签关系表模型
 *
 * @author jiang
 */
class ClassifyRelation extends Base
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'article_classify_relation';

    /**
     * 可以被集体附值的表的字段
     *
     * @var string
     */
    protected $fillable = array('id', 'article_id', 'classify_id', 'time');

    /**
     * 批量删除
     */
    public function deleteClassifyRelation(array $ids)
    {
        return $this->whereIn('article_id', $ids)->delete();
    }

    /**
     * 增加数据
     */
    public function addClassifyArticleRelation($articleId, $classifyId)
    {
        $isertData = ['article_id' => $articleId, 'classify_id' => $classifyId, 'time' => time()];
        return $this->create($isertData);
    }

}
