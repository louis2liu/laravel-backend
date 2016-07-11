<?php namespace App\Services\Admin\Position;

use Lang;
use App\Models\Admin\Position as PositionModel;
use App\Services\Admin\Position\Validate\Position as PositionValidate;
use App\Services\Admin\BaseProcess;

/**
 * 推荐位处理
 *
 * @author jiang <mylampblog@163.com>
 */
class Process extends BaseProcess
{
    /**
     * 推荐位模型
     * 
     * @var object
     */
    private $positionModel;

    /**
     * 推荐位表单验证对象
     * 
     * @var object
     */
    private $positionValidate;

    /**
     * 初始化
     *
     * @access public
     */
    public function __construct()
    {
        if( ! $this->positionModel) $this->positionModel = new PositionModel();
        if( ! $this->positionValidate) $this->positionValidate = new PositionValidate();
    }

    /**
     * 增加新的推荐位
     *
     * @param string $data
     * @access public
     * @return boolean true|false
     */
    public function addPosition(\App\Services\Admin\Position\Param\PositionSave $data)
    {
        if( ! $this->positionValidate->add($data)) return $this->setErrorMsg($this->positionValidate->getErrorMessage());
        $data = $data->toArray();
        $data['is_delete'] = PositionModel::IS_DELETE_NO;
        if($this->positionModel->addPosition($data) !== false) return true;
        return $this->setErrorMsg(Lang::get('common.action_error'));
    }

    /**
     * 删除推荐位
     * 
     * @param array $ids
     * @access public
     * @return boolean true|false
     */
    public function detele($ids)
    {
        if( ! is_array($ids)) return false;
        $data['is_delete'] = PositionModel::IS_DELETE_YES;
        if($this->positionModel->deletePositions($data, $ids) !== false) return true;
        return $this->setErrorMsg(Lang::get('common.action_error'));
    }

    /**
     * 编辑推荐位
     *
     * @param string $data
     * @access public
     * @return boolean true|false
     */
    public function editPosition(\App\Services\Admin\Position\Param\PositionSave $data)
    {
        if( ! isset($data['id'])) return $this->setErrorMsg(Lang::get('common.action_error'));
        $id = intval($data['id']); unset($data['id']);
        if( ! $this->positionValidate->edit($data)) return $this->setErrorMsg($this->positionValidate->getErrorMessage());
        if($this->positionModel->editPosition($data->toArray(), $id) !== false) return true;
        return $this->setErrorMsg(Lang::get('common.action_error'));
    }

}