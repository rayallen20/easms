<?php
namespace App\Biz;

use App\Lib\Resp;

class Nation {
    /**
     * @var int $id 民族id
     */
    public $id;

    /**
     * @var int $code 民族编码
     */
    public $code;

    /**
     * @var string $name 民族名称
     */
    public $name;

    /**
     * @var int $sort 排序字段
     */
    public $sort;

    /**
     * 本方法用于列表展示民族信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return array<Nation> $nations
     */
    public function list() {
        $nations = [];
        $model = new \App\Http\Models\Nation();
        $nationOrms = $model->findNormals();
        for ($i = 0; $i <= count($nationOrms) - 1; $i++) {
            $nationOrm = $nationOrms[$i];
            $nation = new Nation();
            $nation->fill($nationOrm);
            $nations[$i] = $nation;
        }
        return $nations;
    }

    /**
     * 本方法用于根据nation表的ORM填充Biz层的Nation对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\Nation $model 政治面貌信息ORM
     * @return void
     */
    public function fill($model) {
        $this->id = $model->id;
        $this->code = $model->code;
        $this->name = $model->name;
        $this->sort = $model->sort;
    }

    /**
     * 本方法用于根据id字段值确认民族是否存在
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 待确认民族的院系id
     * @return int $code 存在返回0 否则返回表示民族信息不存在的错误码
     */
    public function exist($id) {
        $code = 0;
        $model = new \App\Http\Models\Nation();
        $nationOrm = $model->findById($id);
        if ($nationOrm == null) {
            $code = Resp::NATIONALITY_NOT_EXIST;
            return $code;
        }

        if ($nationOrm->status == \App\Http\Models\Nation::STATUS['delete']) {
            $code = Resp::NATIONALITY_HAS_BEEN_DELETE;
            return $code;
        }

        $this->fill($nationOrm);
        return $code;
    }
}
