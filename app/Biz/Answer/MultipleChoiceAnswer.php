<?php
namespace App\Biz\Answer;

use App\Http\Models\ProbeMultipleChoiceAnswer;

class MultipleChoiceAnswer {
    const RATE_PRECISION = 2;
    public $probe;

    public $question;

    public function count($question) {
        $this->probe = $question->probe;
        $this->question = $question;
        $model = new ProbeMultipleChoiceAnswer();

        foreach ($this->question->options as $option) {
            $orm = $model->findByStemIdAndOptionId($question->id, $option->id);
            $option->beChooseNum = $orm->be_chosen_number;
            $option->rate = round($orm->be_chosen_number / $this->probe->answererNum, self::RATE_PRECISION);
        }
    }
}
