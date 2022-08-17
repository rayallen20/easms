<?php
namespace App\Lib;

class Resp {
    /**
     * @const int SUCCESS 本状态码表示响应成功
    */
    const SUCCESS = 200;

    /**
     * @const int PARAM_INVALID 本状态码表示参数无效
     */
    const PARAM_INVALID = 10001;

    /**
     * @const int ACCOUNT_NOT_EXIST 本状态码表示用户账户不存在
    */
    const ACCOUNT_NOT_EXIST = 10002;

    /**
     * @const int INCORRECT_PASSWORD 本状态码表示登录密码错误
    */
    const INCORRECT_PASSWORD = 10003;

    /**
     * @const int SAVE_DATABASE_FAILED 本状态码表示更新数据库错误
    */
    const SAVE_DATABASE_FAILED = 10004;

    /**
     * @const int PARSE_TOKEN_FAILED 本状态码表示解析jwt至数组失败
    */
    const PARSE_JWT_FAILED = 10005;

    /**
     * @const int TOKEN_INVALID 本状态码表示无法根据token的解析结果查询出一个用户信息
    */
    const JWT_INVALID = 10006;

    /**
     * @const int PERMISSION_DENY 本状态码表示用户无权限进行当前操作
    */
    const PERMISSION_DENY = 10007;

    /**
     * @const int ACCOUNT_EXISTED 本状态码表示待创建用户的账号已存在
    */
    const ACCOUNT_EXISTED = 10008;

    /**
     * @const int ROLE_NOT_EXIST 本状态码表示角色不存在
     */
    const ROLE_NOT_EXIST = 10009;

    const MESSAGE = [
        self::SUCCESS => '操作成功',
        self::ACCOUNT_NOT_EXIST => '账号不存在',
        self::INCORRECT_PASSWORD => '密码不正确',
        self::SAVE_DATABASE_FAILED => '数据库写入错误',
        self::PARSE_JWT_FAILED => '解析token失败',
        self::JWT_INVALID => 'token无效',
        self::PERMISSION_DENY => '用户无权限执行该操作',
        self::ACCOUNT_EXISTED => '存在账号名重复的用户,请更改账号内容',
        self::ROLE_NOT_EXIST => '角色信息不存在',
    ];

    /**
     * 本方法用于生成返回至前端的JSON
     * @access private
     * @author Roach<18410269837@163.com>
     * @param int $code 状态码
     * @param string $message 状态码对应的错误信息
     * @param map<string:interface> $data 有效载荷 注意:该参数只能为关联数组
     * @param int|null $flag 标注是否在JSON序列化时将元素全部转化为对象的flag
     * @return string 返回至客户端的JSON
    */
    private function generate($code, $message, $data, $flag=JSON_FORCE_OBJECT) {
        $resp = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        return json_encode($resp, $flag);
    }

    /**
     * 本方法用于生成当参数不合规时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $message 参数报错信息
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function paramInvalid($message, $data) {
        return self::generate(self::PARAM_INVALID, $message, $data);
    }

    /**
     * 本方法用于生成当账号不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
    */
    public function accountNotExist($data) {
        return self::generate(self::ACCOUNT_NOT_EXIST, self::MESSAGE[self::ACCOUNT_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成当密码不正确时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function incorrectPassword($data) {
        return self::generate(self::INCORRECT_PASSWORD, self::MESSAGE[self::INCORRECT_PASSWORD], $data);
    }

    /**
     * 本方法用于生成当数据库落盘失败时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function DBFailed($data) {
        return self::generate(self::SAVE_DATABASE_FAILED, self::MESSAGE[self::SAVE_DATABASE_FAILED], $data);
    }

    /**
     * 本方法用于生成当响应成功时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function success($data) {
        if ($data == []) {
            return self::generate(self::SUCCESS, self::MESSAGE[self::SUCCESS], $data);
        } else {
            return self::generate(self::SUCCESS, self::MESSAGE[self::SUCCESS], $data, null);
        }
    }

    /**
     * 本方法用于生成当解析token失败时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function parseJwtFailed($data) {
        return self::generate(self::PARSE_JWT_FAILED, self::MESSAGE[self::PARSE_JWT_FAILED], $data);
    }

    /**
     * 本方法用于生成当无法根据jwt中的信息查找到用户信息时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function jwtInvalid($data) {
        return self::generate(self::JWT_INVALID, self::MESSAGE[self::JWT_INVALID], $data);
    }

    /**
     * 本方法用于生成当用户无权限执行当前操作时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function permissionDeny($data) {
        return self::generate(self::PERMISSION_DENY, self::MESSAGE[self::PERMISSION_DENY], $data);
    }

    /**
     * 本方法用于生成当账号已存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function accountExisted($data) {
        return self::generate(self::ACCOUNT_EXISTED, self::MESSAGE[self::ACCOUNT_EXISTED], $data);
    }

    /**
     * 本方法用于生成当角色信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function roleNotExist($data) {
        return self::generate(self::ROLE_NOT_EXIST, self::MESSAGE[self::ROLE_NOT_EXIST], $data);
    }
}

