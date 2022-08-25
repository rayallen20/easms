<?php
namespace App\Biz;

use App\Lib\Resp;

class ExamArea {
    /**
     * @var int $id 考区id
     */
    public $id;

    /**
     * @var int $code 考区编码
     */
    public $code;

    /**
     * @var string $name 考区名称
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
     * @return array<ExamArea> $examAreas
     */
    public function list() {
        $examAreas = [];
        $model = new \App\Http\Models\ExamArea();
        $examAreaOrms = $model->findNormals();
        for ($i = 0; $i <= count($examAreaOrms) - 1; $i++) {
            $examAreaOrm = $examAreaOrms[$i];
            $examArea = new ExamArea();
            $examArea->fill($examAreaOrm);
            $examAreas[$i] = $examArea;
        }
        return $examAreas;
    }

    /**
     * 本方法用于根据exam_area表的ORM填充Biz层的ExamArea对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\ExamArea $model 政治面貌信息ORM
     * @return void
     */
    public function fill($model) {
        $this->id = $model->id;
        $this->code = $model->code;
        $this->name = $model->name;
        $this->sort = $model->sort;
    }

    /**
     * 本方法用于根据id字段值确认考区是否存在
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 待确认考区的考区id
     * @return int $code 存在返回0 否则返回表示考区信息不存在的错误码
     */
    public function exist($id) {
        $code = 0;
        $model = new \App\Http\Models\ExamArea();
        $examAreaOrm = $model->findById($id);
        if ($examAreaOrm == null) {
            $code = Resp::EXAM_AREA_NOT_EXIST;
            return $code;
        }

        if ($examAreaOrm->status == \App\Http\Models\ExamArea::STATUS['delete']) {
            $code = Resp::EXAM_AREA_HAS_BEEN_DELETE;
            return $code;
        }

        $this->fill($examAreaOrm);
        return $code;
    }
}
