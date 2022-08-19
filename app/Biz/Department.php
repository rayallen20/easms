<?php
namespace App\Biz;

use App\Lib\Resp;

class Department {
    /**
     * @var int $id 院系id
    */
    public $id;

    /**
     * @var string $name 院系名称
    */
    public $name;

    /**
     * @var array<Major> $majorCollection 院系下属专业集合
    */
    public $majorCollection;

    /**
     * @var string $principalName 负责人姓名
    */
    public $principalName;

    /**
     * @var string $principalMobile 负责人联系方式
    */
    public $principalMobile;

    /**
     * @var string $status 院系信息状态
    */
    public $status;

    /**
     * @var int $sort 院系顺序 展示用
    */
    public $sort;

    /**
     * 本方法用于创建院系
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $name 院系名称
     * @param string $principalName 院系负责人姓名
     * @param string $principalMobile 院系负责人手机号
     * @return int $code 操作状态码 0表示成功 操作失败则返回对应失败原因的状态码
    */
    public function create($name, $principalName, $principalMobile) {
        $code = 0;
        $this->name = $name;
        $this->principalName = $principalName;
        $this->principalMobile = $principalMobile;
        $model = new \App\Http\Models\Department();
        $result = $model->create($this);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }
}
