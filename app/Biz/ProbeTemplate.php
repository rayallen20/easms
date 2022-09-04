<?php
namespace App\Biz;

use App\Biz\Question\ChoiceQuestion\MultipleChoice;
use App\Biz\Question\ChoiceQuestion\SingleChoice;
use App\Biz\Question\Question;
use App\Biz\Question\ShortQuestion\ShortQuestion;
use App\Http\Models\MultipleChoiceStem;
use App\Http\Models\ShortStem;
use App\Http\Models\SingleChoiceStem;
use App\Lib\Pagination;
use App\Lib\Resp;

class ProbeTemplate {
    /**
     * @var int $id 调研问卷id
    */
    public $id;

    /**
     * @var string $name 调研问卷名称
     */
    public $name;

    /**
     * @var string $startDate 调研问卷开始作答日期
     */
    public $startDate;

    /**
     * @var string $endDate 调研问卷结束作答日期
     */
    public $endDate;

    /**
     * @var int $topicNumber 调研问卷题目数量
    */
    public $topicNumber;

    /**
     * @var array<Question> 调研问卷所属问题
    */
    public $questions;

    /**
     * @var int $answererNum 作答人数
    */
    public $answererNum;

    /**
     * @var int $sort 排序字段
     */
    public $sort;

    /**
     * @var string $createTime 调研问卷创建时间
    */
    public $createdTime;

    /**
     * @var string $updateTime 调研问卷修改时间
     */
    public $updatedTime;

    /**
     * @var \App\Http\Models\ProbeTemplate $orm 调研模板ORM
    */
    private $orm;

    /**
     * 本方法用于创建学生
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $name 调研问卷名称
     * @param string $startDate 调研问卷开始作答日期
     * @param string $endDate 调研问卷结束作答日期
     * @return int $code 操作状态码 0表示成功 操作失败则返回对应失败原因的状态码
     */
    public function create($name, $startDate, $endDate) {
        $code = 0;
        $this->name = $name;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->topicNumber = 0;
        $this->answererNum = 0;
        $model = new \App\Http\Models\ProbeTemplate();
        $result = $model->create($this);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于列表展示学生信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $currentPage 当前页数
     * @param int $itemPerPage 每页显示信息条数
     * @return array $result 本数组共3项内容:
     * array<ProbeTemplate> $result['probes']:调研模板信息集合
     * App\Lib\Pagination $result['pagination']:分页器对象
     * int $result['code']:错误码
     */
    public function list($currentPage, $itemPerPage) {
        $result = [
            'probes' => [],
            'pagination' => null,
            'code' => 0
        ];

        $pagination = new Pagination($currentPage, $itemPerPage);
        $offset = $pagination->calcOffset();

        $model = new \App\Http\Models\ProbeTemplate();
        $probeCollection = $model->findNormalProbes($offset, $itemPerPage);
        for ($i = 0; $i <= count($probeCollection) - 1; $i++) {
            $probeOrm = $probeCollection[$i];
            $probe = new ProbeTemplate();
            $probe->fill($probeOrm);
            $result['probes'][$i] = $probe;
        }

        $totalProbeNum = $model->countNormalProbes();
        $pagination->calcTotalPage($totalProbeNum);
        $result['pagination'] = $pagination;
        return $result;
    }

    /**
     * 本方法用于根据probe_template表的ORM填充Biz层的ProbeTemplate对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\ProbeTemplate $model 调研模板信息ORM
     * @return void
     */
    public function fill($model) {
        $this->id = $model->id;
        $this->name = $model->name;
        $this->startDate = $model->start_date;
        $this->endDate = $model->end_date;
        $this->topicNumber = $model->topic_number;
        $this->answererNum = $model->answerer_num;
        $this->sort = $model->sort;
        $this->createdTime = explode('.', $model->created_time)[0];
        $this->updatedTime = explode('.', $model->updated_time)[0];
        $this->orm = $model;

        // 此处对题目进行排序 使用 题目的序号 - 1 作为数组中的索引
        $this->questions = [];
        foreach ($model->shortQuestions as $shortQuestion) {
           if ($shortQuestion->status == ShortStem::STATUS['normal']) {
               $shortQuestionBiz = new ShortQuestion();
               $shortQuestionBiz->fill($shortQuestion);
               $this->questions[$shortQuestionBiz->sort - 1] = $shortQuestionBiz;
           }
        }

        foreach ($model->singleChoices as $singleChoice) {
            if ($singleChoice->status == SingleChoiceStem::STATUS['normal']) {
                $singleChoiceBiz = new SingleChoice();
                $singleChoiceBiz->fill($singleChoice);
                $this->questions[$singleChoiceBiz->sort - 1] = $singleChoiceBiz;
            }
        }

        foreach ($model->multipleChoices as $multipleChoice) {
            if ($multipleChoice->status == MultipleChoiceStem::STATUS['normal']) {
                $multipleChoiceBiz = new MultipleChoice();
                $multipleChoiceBiz->fill($multipleChoice);
                $this->questions[$multipleChoiceBiz->sort - 1] = $multipleChoiceBiz;
            }
        }
    }

    /**
     * 本方法用于更新调研模板信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 调研模板id
     * @param string $name 调研问卷名称
     * @param string $startDate 调研问卷开始作答日期
     * @param string $endDate 调研问卷结束作答日期
     * @return int $code 操作状态码 0表示成功 操作失败则返回对应失败原因的状态码
     */
    public function update($id, $name, $startDate, $endDate) {
        $code = 0;
        $model = new \App\Http\Models\ProbeTemplate();
        $probeOrm = $model->findById($id);
        if ($probeOrm == null) {
            $code = Resp::PROBE_NOT_EXIST;
            return $code;
        }

        if ($probeOrm->status == \App\Http\Models\ProbeTemplate::STATUS['delete']) {
            $code = Resp::PROBE_HAS_BEEN_DELETE;
            return $code;
        }

        $this->name = $name;
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        $result = $model->updateProbe($probeOrm, $this);

        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }

        $this->fill($probeOrm);
        return $code;
    }

    /**
     * 本方法用于删除调研模板
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 待删除调研模板的学生id
     * @return int $code 错误码 若操作无错误则返回0
     */
    public function delete($id) {
        $code = 0;
        $model = new \App\Http\Models\ProbeTemplate();
        $probeOrm = $model->findById($id);
        if ($probeOrm == null) {
            $code = Resp::PROBE_NOT_EXIST;
            return $code;
        }

        if ($probeOrm->status == \App\Http\Models\ProbeTemplate::STATUS['delete']) {
            $code = Resp::PROBE_HAS_BEEN_DELETE;
            return $code;
        }

        $result = $model->deleteProbe($probeOrm);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于根据id字段值确认调研模板是否存在
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 待确认调研模板的调研模板id
     * @return int $code 存在返回0 否则返回表示院系信息不存在的错误码
     */
    public function exist($id) {
        $code = 0;
        $model = new \App\Http\Models\ProbeTemplate();
        $probeOrm = $model->findById($id);
        if ($probeOrm == null) {
            $code = Resp::PROBE_NOT_EXIST;
            return $code;
        }

        if ($probeOrm->status == \App\Http\Models\ProbeTemplate::STATUS['delete']) {
            $code = Resp::PROBE_HAS_BEEN_DELETE;
            return $code;
        }

        $this->fill($probeOrm);
        return $code;
    }

    /**
     * 本方法用于在调研模板下新增一道简答题
     * @access public
     * @author Roach<18410269837@163.com>
     * @param ShortQuestion $short 简答题对象
     * @return bool true表示新增成功 false表示新增失败
    */
    public function addShortQuestion($short) {
        $model = new ShortStem();
        return $model->create($this->orm, $short);
    }

    /**
     * 本方法用于在调研模板下新增一道单选题
     * @access public
     * @author Roach<18410269837@163.com>
     * @param SingleChoice $singleChoice 单选题对象
     * @return bool true表示新增成功 false表示新增失败
    */
    public function addSingleChoice($singleChoice) {
        $model = new SingleChoiceStem();
        return $model->create($this->orm, $singleChoice);
    }

    /**
     * 本方法用于在调研模板下新增一道多选题
     * @access public
     * @author Roach<18410269837@163.com>
     * @param MultipleChoice $multipleChoice 多选题对象
     * @return bool true表示新增成功 false表示新增失败
     */
    public function addMultipleChoice($multipleChoice) {
       $model = new MultipleChoiceStem();
       return $model->create($this->orm, $multipleChoice);
    }

    /**
     * 本方法用于在指定的调研模板下列表显示问题
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 调研模板id
     * @return int $code 错误码
    */
    public function listQuestion($id) {
        $code = $this->exist($id);
        if ($code == Resp::PROBE_NOT_EXIST) {
            return $code;
        }

        if ($code == Resp::PROBE_HAS_BEEN_DELETE) {
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于在指定的调研模板下删除指定问题
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 调研模板id
     * @param int $sort 题目序号
     * @return int $code 错误码
    */
    public function deleteQuestion($id, $sort) {
        $code = 0;
        $code = $this->exist($id);
        if ($code == Resp::PROBE_NOT_EXIST) {
            return $code;
        }

        if ($code == Resp::PROBE_HAS_BEEN_DELETE) {
            return $code;
        }
        $model = new \App\Http\Models\ProbeTemplate();
        $saveResult = $model->deleteQuestion($this->orm, $sort);
        if (!$saveResult) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    public function answer($orms) {
        $model = new \App\Http\Models\ProbeTemplate();
        $saveResult = $model->answer($this->orm, $orms);
        return $saveResult;
    }
}
