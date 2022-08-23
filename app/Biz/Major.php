<?php
namespace App\Biz;

use App\Lib\Resp;

class Major {
    /**
     * @var int $id 专业id
    */
    public $id;

    /**
     * @var Department $department 专业所属院系
    */
    public $department;

    /**
     * @var string $name 专业名称
    */
    public $name;

    /**
     * @var string $status 专业信息状态
    */
    public $status;

    /**
     * @var int $sort 排序字段 展示用
    */
    public $sort;

    /**
     * 本方法用于创建专业
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $departmentId 院系id
     * @param string $name 专业名称
     * @return int $code 操作状态码 0表示成功 操作失败则返回对应失败原因的状态码
     */
    public function create($departmentId, $name) {
        $code = 0;
        $this->department = new Department();
        $code = $this->department->exist($departmentId);
        if ($code != 0) {
            return $code;
        }

        $this->name = $name;
        $model = new \App\Http\Models\Major();
        $result = $model->create($this);

        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }

        return $code;
    }
}
