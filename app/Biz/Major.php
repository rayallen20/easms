<?php
namespace App\Biz;

use App\Lib\Pagination;
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
     * @var string $createdTime 专业创建时间
     */
    public $createdTime;

    /**
     * @var string $updatedTime 专业修改时间
     */
    public $updatedTime;

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

    /**
     * 本方法用于列表展示指定院系下的专业信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $departmentId 院系id
     * @param int $currentPage 当前页数
     * @param int $itemPerPage 每页显示信息条数
     * @return array $result 本数组共3项内容:
     * array<Major> $result['majors']:院系信息
     * App\Lib\Pagination $result['pagination']:分页器对象
     * int $result['code']:错误码
     */
    public function list($departmentId, $currentPage, $itemPerPage) {
        $result = [
            'majors' => [],
            'pagination' => null,
            'code' => 0
        ];

        $this->department = new Department();
        $code = $this->department->exist($departmentId);
        if ($code != 0) {
            $result['code'] = $code;
            return $result;
        }

        $pagination = new Pagination($currentPage, $itemPerPage);
        $offset = $pagination->calcOffset();

        $model = new \App\Http\Models\Major();
        $majorCollection = $model->findNormalMajors($departmentId, $offset, $itemPerPage);
        for ($i =0; $i <= count($majorCollection) - 1; $i++) {
            $majorOrm = $majorCollection[$i];
            $major = new Major();
            $major->fill($majorOrm);
            $result['majors'][$i] = $major;
        }

        $totalMajorNum = $model->countNormalMajors($departmentId);
        $pagination->calcTotalPage($totalMajorNum);
        $result['pagination'] = $pagination;
        return $result;
    }

    /**
     * 本方法用于根据Major表的ORM填充Biz层的Major对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\Major $model 院系信息ORM
     * @return void
     */
    public function fill($model) {
        $this->id = $model->id;
        $this->department = $model->department;
        $this->name = $model->name;
        $this->sort = $model->sort;
        $this->createdTime = explode('.', $model->created_time)[0];
        $this->updatedTime = explode('.', $model->updated_time)[0];
    }
}
