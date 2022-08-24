<?php
namespace App\Biz;

use App\Lib\Resp;

class Subject {
    /**
     * @var int $id 学科id
     */
    public $id;

    /**
     * @var int $code 学科编码
     */
    public $code;

    /**
     * @var string $name 学科名称
     */
    public $name;

    /**
     * @var int $sort 排序字段
     */
    public $sort;

    /**
     * 本方法用于列表展示学科信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return array<Subject> $subjects
     */
    public function list() {
        $subjects = [];
        $model = new \App\Http\Models\Subject();
        $subjectOrms = $model->findNormals();
        for ($i = 0; $i <= count($subjectOrms) - 1; $i++) {
            $subjectOrm = $subjectOrms[$i];
            $subject = new Subject();
            $subject->fill($subjectOrm);
            $subjects[$i] = $subject;
        }
        return $subjects;
    }

    /**
     * 本方法用于根据subject表的ORM填充Biz层的Subject对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\Subject $model 学科信息ORM
     * @return void
     */
    public function fill($model) {
        $this->id = $model->id;
        $this->code = $model->code;
        $this->name = $model->name;
        $this->sort = $model->sort;
    }

    /**
     * 本方法用于根据id字段值确认学科类别是否存在
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 待确认学科类别的学科类别id
     * @return int $code 存在返回0 否则返回表示学科类别不存在的错误码
     */
    public function exist($id) {
        $code = 0;
        $model = new \App\Http\Models\Subject();
        $subjectOrm = $model->findById($id);
        if ($subjectOrm == null) {
            $code = Resp::SUBJECT_NOT_EXIST;
            return $code;
        }

        if ($subjectOrm->status == \App\Http\Models\Subject::STATUS['delete']) {
            $code = Resp::SUBJECT_HAS_BEEN_DELETE;
            return $code;
        }

        $this->fill($subjectOrm);
        return $code;
    }
}
