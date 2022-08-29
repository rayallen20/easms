<?php
namespace App\Biz;

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
    }
}
