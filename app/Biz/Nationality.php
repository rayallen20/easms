<?php
namespace App\Biz;

use App\Lib\Resp;

class Nationality {
    /**
     * @var int $id 国籍id
     */
    public $id;

    /**
     * @var int $code 国籍编码
     */
    public $code;

    /**
     * @var string $name 国籍名称
     */
    public $name;

    /**
     * @var int $sort 排序字段
     */
    public $sort;

    /**
     * 本方法用于列表展示国籍信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return array<Nationality> $nationalities
     */
    public function list() {
        $nationalities = [];
        $model = new \App\Http\Models\Nationality();
        $nationalityOrms = $model->findNormals();
        for ($i = 0; $i <= count($nationalityOrms) - 1; $i++) {
            $nationalityOrm = $nationalityOrms[$i];
            $nationality = new Nationality();
            $nationality->fill($nationalityOrm);
            $nationalities[$i] = $nationality;
        }
        return $nationalities;
    }

    /**
     * 本方法用于根据nationality表的ORM填充Biz层的Nationality对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\Nationality $model 国籍信息ORM
     * @return void
     */
    public function fill($model) {
        $this->id = $model->id;
        $this->code = $model->code;
        $this->name = $model->name;
        $this->sort = $model->sort;
    }

    /**
     * 本方法用于根据id字段值确认国籍是否存在
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 待确认国籍的院系id
     * @return int $code 存在返回0 否则返回表示国籍信息不存在的错误码
     */
    public function exist($id) {
        $code = 0;
        $model = new \App\Http\Models\Nationality();
        $nationalityOrm = $model->findById($id);
        if ($nationalityOrm == null) {
            $code = Resp::NATIONALITY_NOT_EXIST;
            return $code;
        }

        if ($nationalityOrm->status == \App\Http\Models\Nationality::STATUS['delete']) {
            $code = Resp::NATIONALITY_HAS_BEEN_DELETE;
            return $code;
        }
        $this->fill($nationalityOrm);
        return $code;
    }
}
