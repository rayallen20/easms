<?php
namespace App\Biz;

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
}
