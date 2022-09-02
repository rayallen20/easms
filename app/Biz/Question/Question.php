<?php
namespace App\Biz\Question;

use App\Biz\ProbeTemplate;
use App\Http\Models\MultipleChoiceStem;
use App\Http\Models\SingleChoiceStem;
use App\Lib\Resp;

abstract class Question {
    /**
     * @const array TYPES 问卷中包含的题目类型
     * shortQuestion:简答题
     * singleChoice:单选题
     * multipleChoice:多选题
     */
    const TYPES = [
        'shortQuestion' => 'shortQuestion',
        'singleChoice' => 'singleChoice',
        'multipleChoice' => 'multipleChoice'
    ];

    /**
     * @var int $id 题题目id
     */
    public $id;

    /**
     * @var \App\Biz\ProbeTemplate $probe 题目所属问卷
     */
    public $probe;

    /**
     * @var string $stem 题干内容
     */
    public $stem;

    /**
     * @var int $sort 题目在所属问卷中的顺序(可以认为是题号)
     */
    public $sort;

    /**
     * @var string $createdTime 题目创建时间
     */
    public $createdTime;

    /**
     * @var string $updatedTime 题目修改时间
     */
    public $updatedTime;

    /**
     * 本方法用于处理所有问题类在创建时的共性操作:
     * 1. 确认指定的问卷是否存在
     * 2. 确认问题在问卷中的序号
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $probeId 调研模板id
     * @param string $stem 题干内容
     * @return array $result 表示预处理结果的数组 本数组共2项内容:
     * int $result['code'] 标识错误类型的错误码错误码
     * string|null $result['exceptionMessage'] 标识参数校验的信息 该字段仅在错误码表示参数校验错误(即值为10001)时有效
    */
    public function preCreate($probeId, $stem, $displayType, $options) {
        $result = [
            'code' => 0,
            'exceptionMessage' => ''
        ];

        $probeBiz = new ProbeTemplate();
        $result['code'] = $probeBiz->exist($probeId);
        if ($result['code'] == Resp::PROBE_NOT_EXIST) {
            return $result;
        }

        if ($result['code'] == Resp::PROBE_HAS_BEEN_DELETE) {
            return $result;
        }

        $this->probe = $probeBiz;
        $this->stem = $stem;
        $this->sort = $this->probe->topicNumber + 1;
        return $result;
    }

    /**
     * 本方法用于根据不同类型题目的ORM信息 填充题目的公共属性
     * @access public
     * @author Roach<18410269837@163.com>
     * @param SingleChoiceStem|MultipleChoiceStem|SingleChoiceStem $orm 简答题\单选题\多选题ORM
    */
    public function fill($orm) {
        $this->id = $orm->id;
        $this->stem = $orm->content;
        $this->sort = $orm->sort;
        $this->createdTime = explode('.', $orm->created_time)[0];
        $this->updatedTime = explode('.', $orm->updated_time)[0];
    }

    /**
     * 本方法用于定义创建问题操作的抽象方法
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $probeId 调研模板id
     * @param string $questionType 题目类型
     * @param string $stem 题干内容
     * @param string $displayType 选择题统计结果展示形式
     * @param string $answerType 简答题答案类型
     * @param array<string> $options 选择题选项
    */
    public abstract function create($probeId, $questionType, $stem, $displayType, $answerType, $options);
}
