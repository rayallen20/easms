<?php
namespace App\Biz\Question\ShortQuestion;

use App\Biz\Question\Question;
use App\Http\Models\ShortStem;
use App\Lib\Resp;

class ShortQuestion extends Question
{
    /**
     * @const array ANSWER_TYPES 简答题类型
     * document:文本型
     * numeric:数字型
     */
    const ANSWER_TYPES = [
        'document',
        'numeric',
    ];

    /**
     * @var string $answerType 简答题答案类型
     */
    public $answerType;

    /**
     * 本方法用于创建简答题
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $answerType 简答题答案类型
     * @return array $result 表示创建结果的数组 本数组共2项内容:
     * int $result['code'] 标识错误类型的错误码错误码
     * string|null $result['exceptionMessage'] 标识参数校验的信息 该字段仅在错误码表示参数校验错误(即值为10001)时有效
    */
    public function create($probeId, $questionType, $stem, $displayType, $answerType, $options) {
        $result = [
            'code' => 0,
            'exceptionMessage' => ''
        ];

        $result = parent::preCreate($probeId, $stem, $displayType, $options);
        if ($result['code'] != 0) {
            return $result;
        }

        if (!in_array($answerType, self::ANSWER_TYPES)) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '简答题答案类型必须为文本型或数字型';
            return $result;
        }

        $this->answerType = $answerType;
        $saveResult = $this->probe->addShortQuestion($this);
        if (!$saveResult) {
            $result['code'] = Resp::SAVE_DATABASE_FAILED;
            return $result;
        }
        return $result;
    }

    /**
     * 本方法用于根据简答题ORM对象 填充简答题Biz层对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param ShortStem $orm
    */
    public function fill($orm)
    {
        parent::fill($orm);
        $this->answerType = $orm->answer_type;
    }

    /**
     * 本方法用于更新简答题信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $answerType 简答题答案类型
     * @return array $result
     * int $result['code'] 错误码
     * string $result['exceptionMessage'] 仅当错误码为校验参数错误时有内容 表示参数错误信息
    */
    public function update($id, $questionType, $stem, $displayType, $answerType, $options)
    {
        $result = [
            'code' => 0,
            'exceptionMessage' => ''
        ];

        if (!in_array($answerType, self::ANSWER_TYPES)) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '简答题答案类型必须为文本型或数字型';
            return $result;
        }

        $model = new ShortStem();
        $orm = $model->findById($id);
        if ($orm == null) {
            $result['code'] = Resp::QUESTION_NOT_EXIST;
            return $result;
        }

        if ($orm->status == ShortStem::STATUS['delete']) {
            $result['code'] = Resp::QUESTION_HAS_BEEN_DELETE;
            return $result;
        }

        $this->stem = $stem;
        $this->answerType = $answerType;
        $updateResult = $model->updateShortStem($orm, $this);
        if (!$updateResult) {
            $result['code'] = Resp::SAVE_DATABASE_FAILED;
            return $result;
        }

        $this->fill($orm);
        return $result;
    }
}
