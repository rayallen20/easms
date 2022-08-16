<?php
namespace App\Lib;

use Illuminate\Support\Facades\Validator;

class Lib {
    /**
     * 本方法用于根据给定的参数、校验规则、错误信息,验证参数是否合规,合规则返回空字符串,否则返回错误信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param array $params 参数数组
     * @param array $rules 校验规则数组
     * @param array $messages 错误信息数组
     * @return array<string> $errs 错误信息
    */
    public function validate($params, $rules, $messages) {
        $validator = Validator::make($params, $rules, $messages);
        if($validator->fails()){
            return $validator->errors()->all();
        }
        return null;
    }
}
