<?php
namespace App\Biz\Question\ChoiceQuestion;

class Option {
    /**
     * @var int $id 选项id
    */
    public $id;

    /**
     * @var ChoiceQuestion $question 选项所属题目
    */
    public $question;

    /**
     * @var string $content 选项内容
    */
    public $content;

    /**
     * @var int $sort 选项在题目中的顺序
    */
    public $sort;

    /**
     * @var string $createTime 选项创建时间
     */
    public $createdTime;

    /**
     * @var string $updateTime 选项修改时间
     */
    public $updatedTime;

    public function create($question, $content, $sort) {
        $this->question = $question;
        $this->content = $content;
        $this->sort = $sort;
    }
}
