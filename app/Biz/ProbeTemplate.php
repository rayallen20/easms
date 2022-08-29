<?php
namespace App\Biz;

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
     * @var int $answerNum 作答人数
    */
    public $answerNum;

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
        $model = new \App\Http\Models\ProbeTemplate();
        $result = $model->create($this);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }
}
