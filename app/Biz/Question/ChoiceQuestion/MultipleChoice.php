<?php
namespace App\Biz\Question\ChoiceQuestion;

use App\Lib\Resp;

class MultipleChoice extends ChoiceQuestion {
    public function create($probeId, $questionType, $stem, $displayType, $answerType, $options)
    {
        $result = [
            'code' => 0,
            'exceptionMessage' => ''
        ];

        $result = parent::preCreate($probeId, $stem, $displayType, $options);
        if ($result['code'] != 0) {
            return $result;
        }

        $saveResult = $this->probe->addMultipleChoice($this);
        if (!$saveResult) {
            $result['code'] = Resp::SAVE_DATABASE_FAILED;
            return $result;
        }
        return $result;
    }
}
