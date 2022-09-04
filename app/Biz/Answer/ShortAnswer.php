<?php
namespace App\Biz\Answer;

class ShortAnswer {
    public $id;

    public $question;

    public $content;

    public $createdTime;

    public $updatedTime;

    public function count($question) {
        $this->question = $question;
        $model = new \App\Http\Models\ShortAnswer();
        $orms = $model->findByStemId($this->question->id);
        $answers = [];

        foreach ($orms as $orm) {
            $answer = new ShortAnswer();
            $answer->id = $orm->id;
            $answer->content = $orm->content;
            $answer->createdTime = explode('.', $orm->created_time)[0];
            $answer->updatedTime = explode('.', $orm->updated_time)[0];
            array_push($answers, $answer);
        }
        return $answers;
    }
}
