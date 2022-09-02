<?php
namespace App\Biz\Question\ChoiceQuestion;

use App\Http\Models\SingleChoiceStem;
use App\Lib\Lib;
use App\Lib\Resp;

class SingleChoice extends ChoiceQuestion {
    public function create($probeId, $questionType, $stem, $displayType, $answerType, $options) {
        $result = [
            'code' => 0,
            'exceptionMessage' => ''
        ];

        $result = parent::preCreate($probeId, $stem, $displayType, $options);
        if ($result['code'] != 0) {
            return $result;
        }

        $saveResult = $this->probe->addSingleChoice($this);
        if (!$saveResult) {
            $result['code'] = Resp::SAVE_DATABASE_FAILED;
            return $result;
        }
        return $result;
    }

    /**
     * 本方法用于更新简答题信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $displayType 单选题统计结果展示形式
     * @param array $options 单选题选项
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

        $params = [
            'displayType' => $displayType,
            'options' => $options,
        ];

        $rules = [
            'displayType' => 'required|string',
            'options' => 'array|min:1',
            'options.*' => 'string|distinct'
        ];

        $exceptionMessages = [
            'displayType.required' => '选择题统计结果展示形式内容不能为空',
            'displayType.string' => '选择题统计结果展示形式内容必须为字符串',
            'options.array' => '选项列表必须为数组',
            'options.min' => '选项列表至少包含1个选项',
            'options.*.string' => '选项内容必须为字符串',
            'options.*.distinct' => '选项内容必须唯一',
        ];

        $lib = new Lib();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = $errors[0];
            return $result;
        }

        if (!in_array($displayType, parent::DISPLAY_TYPE)) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '选择题统计结果展示形式必须为饼状图或柱状图';
            return $result;
        }

        $model = new SingleChoiceStem();
        $orm = $model->findById($id);
        if ($orm == null) {
            $result['code'] = Resp::QUESTION_NOT_EXIST;
            return $result;
        }

        if ($orm->status == SingleChoiceStem::STATUS['delete']) {
            $result['code'] = Resp::QUESTION_HAS_BEEN_DELETE;
            return $result;
        }

        $this->stem = $stem;
        $this->displayType = $displayType;
        $this->options = [];
        foreach ($options as $key => $option) {
            $optionBiz = new Option();
            $sort = $key + 1;
            $optionBiz->create($this, $option, $sort);
            $this->options[$key] = $optionBiz;
        }

        $saveResult = $model->updateSingleChoice($orm, $this);
        if (!$saveResult) {
            $result['code'] = Resp::SAVE_DATABASE_FAILED;
            return $result;
        }
        // 此处需要重新获取一次orm 因为在事务提交之前的orm无法感知在事务中创建的选项orm信息
        $orm = $model->findById($id);
        $this->fill($orm);
        return $result;
    }
}
