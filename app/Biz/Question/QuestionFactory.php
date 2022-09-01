<?php
namespace App\Biz\Question;

use App\Biz\Question\ChoiceQuestion\MultipleChoice;
use App\Biz\Question\ChoiceQuestion\SingleChoice;
use App\Lib\Resp;
use App\Biz\Question\ShortQuestion\ShortQuestion;

class QuestionFactory {
    /**
     * 本方法用于创建一个问题对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $questionType 题目类型
     * @return array $result 表示创建结果的数组 本数组共2项内容:
     * int $result['code'] 标识错误类型的错误码错误码
     * string|null $result['exceptionMessage'] 标识参数校验的信息 该字段仅在错误码表示参数校验错误(即值为10001)时有效
     * Question $result['question'] 对应类型的问题对象
     */
    public function create($questionType) {
        $result = [
            'code' => 0,
            'exceptionMessage' => '',
            'question' => null
        ];
        switch ($questionType) {
            case Question::TYPES['shortQuestion']:
                $result['question'] = new ShortQuestion();
                return $result;
            case Question::TYPES['singleChoice']:
                $result['question'] = new SingleChoice();
                return $result;
            case Question::TYPES['multipleChoice']:
                $result['question'] = new MultipleChoice();
                return $result;
            default:
                $result['code'] = Resp::PARAM_INVALID;
                $result['exceptionMessage'] = '题目类型只能为单选题、多选题或简答题';
                return $result;
        }
    }
}
