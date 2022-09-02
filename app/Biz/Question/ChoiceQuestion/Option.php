<?php
namespace App\Biz\Question\ChoiceQuestion;

use App\Http\Models\MultipleChoiceOption;
use App\Http\Models\SingleChoiceOption;

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

    /**
     * 本方法用于根据选项ORM填充选项Biz层对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param SingleChoiceOption|MultipleChoiceOption $orm 单选题选项ORM或多选题选项ORM
    */
    public function fill($orm) {
        $this->id = $orm->id;
        $this->content = $orm->content;
        $this->sort = $orm->sort;
        $this->createdTime = explode('.', $orm->created_time)[0];
        $this->updatedTime = explode('.', $orm->updated_time)[0];
    }
}
