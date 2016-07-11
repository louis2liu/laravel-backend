<?php namespace App\Models\Admin;

use App\Models\Admin\Base;

/**
 * 文章标签关系表模型
 *
 * @author jiang
 */
class Tags extends Base
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'article_tags';

    /**
     * 可以被集体附值的表的字段
     *
     * @var string
     */
    protected $fillable = array('id', 'name', 'sort', 'is_active', 'is_delete', 'time');

    /**
     * 文章标签库的is_active状态
     */
    CONST TAGS_LIB_ACTIVE_YES = 1;

    /**
     * 文章标签库的is_delete状态
     */
    CONST TAGS_LIB_IS_DELETE_NO = 1;

    /**
     * 取得指定信息
     * 
     * @param string $tagName
     * @return array
     */
    public function getOneByName($tagName)
    {
        return $this->where('name', $tagName)->first();
    }

    /**
     * 批量插入新的数据
     */
    public function addTagsIfNotExistsByName($tagName)
    {
        if($info = $this->where('name', $tagName)->first())
        {
            return $info;
        }
        $isertData = ['name' => $tagName, 'is_active' => self::TAGS_LIB_ACTIVE_YES, 'is_delete' => self::TAGS_LIB_IS_DELETE_NO, 'time' => time()];
        return $this->create($isertData);
    }

}
