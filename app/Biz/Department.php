<?php
namespace App\Biz;

use App\Lib\Pagination;
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
     * @var string $createdTime 院系信息创建时间
    */
    public $createdTime;

    /**
     * @var string $updatedTime 院系修改时间
    */
    public $updatedTime;

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

    /**
     * 本方法用于列表展示院系信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $currentPage 当前页数
     * @param int $itemPerPage 每页显示信息条数
     * @return array $result 本数组共3项内容:
     * array<User> $result['departments']:院系信息集合
     * App\Lib\Pagination $result['pagination']:分页器对象
     * int $result['code']:错误码
     */
    public function list($currentPage, $itemPerPage) {
        $result = [
            'departments' => [],
            'pagination' => null,
            'code' => 0
        ];

        $pagination = new Pagination($currentPage, $itemPerPage);
        $offset = $pagination->calcOffset();

        $model = new \App\Http\Models\Department();
        $departmentCollection = $model->findNormalDepartments($offset, $itemPerPage);
        for ($i = 0; $i <= count($departmentCollection) - 1; $i++) {
            $departmentOrm = $departmentCollection[$i];
            $department = new Department();
            $department->fill($departmentOrm);
            $result['departments'][$i] = $department;
        }

        $totalDepartmentNum = $model->countNormalDepartments();
        $pagination->calcTotalPage($totalDepartmentNum);
        $result['pagination'] = $pagination;
        return $result;
    }

    /**
     * 本方法用于根据User表的ORM填充Biz层的User对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\Department $model 院系信息ORM
     * @return void
     */

    public function fill($model) {
        $this->id = $model->id;
        $this->name = $model->name;
        $this->principalName = $model->principal_name;
        $this->principalMobile = $model->principal_mobile;
        $this->sort = $model->sort;
        $this->createdTime = explode('.', $model->created_time)[0];
        $this->updatedTime = explode('.', $model->updated_time)[0];
        // TODO: majorCollection
    }

    /**
     * 本方法用于更新院系信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 院系id
     * @param string $name 更新后的院系名称
     * @param string $principalName 更新后的院系负责人姓名
     * @param string $principalMobile 更新后的院系负责人手机号
     * @return int $code 表示更新结果的错误码 成功则返回0
     */
    public function update($id, $name, $principalName, $principalMobile) {
        $code = 0;
        $model = new \App\Http\Models\Department();
        $departmentOrm = $model->findById($id);
        if ($departmentOrm == null) {
            $code = Resp::DEPARTMENT_NOT_EXIST;
            return $code;
        }

        if ($departmentOrm->status == \App\Http\Models\Department::STATUS['delete']) {
            $code = Resp::DEPARTMENT_HAS_BEEN_DELETE;
            return $code;
        }

        $result = $model->updateDepartment($departmentOrm, $name, $principalName, $principalMobile);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }

        $this->fill($departmentOrm);
        return $code;
    }

    /**
     * 本方法用于删除用户
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 待删除院系的院系id
     * @return int $code 错误码 若操作无错误则返回0
     */
    public function delete($id) {
        $code = 0;
        $model = new \App\Http\Models\Department();
        $departmentOrm = $model->findById($id);
        if ($departmentOrm == null) {
            $code = Resp::DEPARTMENT_NOT_EXIST;
            return $code;
        }

        if ($departmentOrm->status == \App\Http\Models\Department::STATUS['delete']) {
            $code = Resp::DEPARTMENT_HAS_BEEN_DELETE;
            return $code;
        }

        $result = $model->updateStatus($departmentOrm, \App\Http\Models\Department::STATUS['delete']);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }
}
