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

    /**
     * 本方法用于校验给定字符串是否符合电子邮件格式
     * 规则:字符串中必须包含至少1个@和1个.
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $email 待校验的邮箱字符串
     * @return bool true表示给定字符串符合电子邮箱格式 false表示给定字符串不符合电子邮箱格式
    */
    public function isEmail($email)
    {
        $atChar = '@';
        $splitChar = '.';
        $atCharExist = strpos($email, $atChar);
        if (!$atCharExist) {
            return false;
        }

        $splitCharExist = strpos($email, $splitChar);
        if (!$splitCharExist) {
            return false;
        }
        return true;
    }

    /**
     * 本方法用于判断给定字符串是否符合手机号格式
     * 规则:字符串必须为11位数字
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $mobile 待校验的手机号
     * @return bool true表示符合手机号格式 false表示不符合手机号格式
    */
    public function isMobile($mobile) {
        if (preg_match("/^1\d{10}$/", $mobile)) {
            return true;
        }
        return false;
    }

    /**
     * 本方法用于判定给定字符串是否包含大写字母
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $str 待校验的字符串
     * @return bool true表示包含大写字母 false表示不包含大写字母
    */
    public function containLarge($str) {
        for($i = 0; $i < strlen($str); $i++) {
            if (ord($str[$i]) >= ord('A') && ord($str[$i]) <= ord('Z')) {
                return true;
            }
        }
        return false;
    }

    /**
     * 本方法用于判定给定字符串是否包含小写字母
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $str 待校验的字符串
     * @return bool true表示包含小写字母 false表示不包含小写字母
     */
    public function containSmall($str) {
        for($i = 0; $i < strlen($str); $i++) {
            if (ord($str[$i]) >= ord('a') && ord($str[$i]) <= ord('z')) {
                return true;
            }
        }
        return false;
    }

    /**
     * 本方法用于判定给定字符串中是否包含特殊字符
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $str 待校验的字符串
     * @return bool true表示包含特殊字符 false表示不包含特殊字符
    */
    public function containSpecialChar($str) {
        $specialChars = "~`.!@#$%^&*()-_=+[]{}\|;:'";
        $strArr = str_split($str);

        for ($i = 0; $i < count($strArr); $i++) {
            if (strpos($specialChars, $strArr[$i]) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * 本方法用于判定给定字符串中是否包含数字
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $str 待校验的字符串
     * @return bool true表示包含数字 false表示不包含数字
    */
    public function containNumber(string $str) {
        $numberChars = '1234567890';
        $strArr = str_split($str);

        for ($i = 0; $i < count($strArr); $i++) {
            if (strpos($numberChars, $strArr[$i]) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * 本方法用于校验给定字符串是否符合密码规则
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $str 待校验的字符串
     * @return array $result 校验结果数组
     * $result['flag']:是否符合密码规则
     * $result['reason']:不符合密码规则的原因
    */
    public function isPassword($str) {
        $result = [
            'flag' => true,
            'reason' => '',
        ];

        // 校验密码是否含有大写字母
        if (!$this->containLarge($str)) {
            $result['flag'] = false;
            $result['reason'] = '密码内容不包含大写字母';
            return $result;
        }

        // 校验密码是否含有小写字母
        if (!$this->containSmall($str)) {
            $result['flag'] = false;
            $result['reason'] = '密码内容不包含小写字母';
            return $result;
        }

        // 校验密码是否含有特殊字符
        if (!$this->containSpecialChar($str)) {
            $result['flag'] = false;
            $result['reason'] = '密码内容不包含特殊字符';
            return $result;
        }

        // 校验密码是否含有数字
        if (!$this->containNumber($str)) {
            $result['flag'] = false;
            $result['reason'] = '密码内容不包含数字';
            return $result;
        }

        return $result;
    }
}
