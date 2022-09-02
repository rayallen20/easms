<?php
namespace App\Biz\Question\ChoiceQuestion;

use App\Biz\Question\Question;
use App\Http\Models\MultipleChoiceStem;
use App\Http\Models\SingleChoiceStem;
use App\Lib\Lib;
use App\Lib\Resp;

abstract class ChoiceQuestion extends Question {
    /**
     * @const array DISPLAY_TYPE 选择题统计结果展示形式
     * pieChart:饼状图
     * barGraph:柱状图
     */
    const DISPLAY_TYPE = [
        'pieChart' => 'pieChart',
        'barGraph' => 'barGraph',
    ];

    /**
     * @var string $displayType 选择题统计结果展示形式
     */
    public $displayType;

    /**
     * @var array<Option> 选择题的选项列表
     */
    public $options;

    /**
     * 本方法用于处理选择题在创建时的共性操作:
     * 1. 确认选项是否均为字符串
     * 2. 确认选择题统计结果展示形式
     * @access public
     * @author Roach<18410269837@163.com>
    */
    public function preCreate($probeId, $stem, $displayType, $options)
    {
        $result = [
            'code' => 0,
            'exceptionMessage' => ''
        ];

        $result = parent::preCreate($probeId, $stem, $displayType, $options);
        if ($result['code'] != 0) {
            return $result;
        }

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

        if (!in_array($displayType, self::DISPLAY_TYPE)) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '选择题统计结果展示形式必须为饼状图或柱状图';
            return $result;
        }
        $this->displayType = $displayType;

        $this->options = [];
        foreach ($options as $key => $option) {
            $optionBiz = new Option();
            $sort = $key + 1;
            $optionBiz->create($this, $option, $sort);
            $this->options[$key] = $optionBiz;
        }

        return $result;
    }

    /**
     * 本方法用于根据选择题的ORM信息 填充选择题的公共属性
     * @access public
     * @author Roach<18410269837@163.com>
     * @param MultipleChoiceStem|SingleChoiceStem $orm 单选题\多选题ORM
     */
    public function fill($orm)
    {
        parent::fill($orm);
        $this->displayType = $orm->display_type;
        $this->options = [];
        foreach ($orm->options as $option) {
            $optionBiz = new Option();
            $optionBiz->fill($option);
            $this->options[$optionBiz->sort] = $optionBiz;
        }
    }
}
