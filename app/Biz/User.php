<?php
namespace App\Biz;

use App\Lib\Jwt;
use App\Lib\Resp;

class User {
    /**
     * @var int $id 用户id
    */
    public $id;

    /**
     * @var string $account 账号
    */
    public $account;

    /**
     * @var string $password 密码
    */
    public $password;

    /**
     * @var string $username 登录后显示的用户名
    */
    public $username;

    /**
     * @var string $email 用户邮箱
    */
    public $email;

    /**
     * @var string $mobile 手机号
    */
    public $mobile;

    /**
     * @var string $role 用户角色名称
    */
    public $role;

    /**
     * @var Jwt $jwt 登录token
    */
    public $jwt;

    /**
     * @var string $lastLoginTime 最后登录时间
    */
    public $lastLoginTime;

    /**
     * 本方法用于处理登录操作
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $account 账号
     * @param string $password 密码
     * @return int $code 错误码 0表示没有错误
    */
    public function login($account, $password) {
        // step1. 校验参数名密码是否正确 start
        $code = 0;
        $resp = new Resp();
        $password = md5($password);
        $model = new \App\Http\Models\User();
        $userOrm = $model->findByAccount($account, $password);
        $code = self::check($userOrm, $password, $code, $resp);
        if ($code != 0) {
            return $code;
        }
        // step1. 校验参数名密码是否正确 end

        // step2. 生成JWT start
        $this->fill($userOrm);
        $this->generateJwt($userOrm);
        // step2. 生成JWT end

        // step3. 更新最后登录时间 start
        $lastLoginDate = date('Y-m-d H:i:s');
        $code = $this->updateLastLoginTime($userOrm, $lastLoginDate, $code, $resp);
        if ($code != 0) {
            return $code;
        }
        // step3. 更新最后登录时间 end
        $this->lastLoginTime = $lastLoginDate;
        return $code;
    }

    /**
     * 本方法用于根据User表的查询结果检查登录账号密码是否正确
     * @access private
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\User $userOrm user表根据account字段值的查询结果ORM
     * @param string $password 用户填写的密码经md5加密后的值
     * @param int $code 错误码值
     * @param Resp $resp 返回JSON类 在本方法中用作code字典
     * @return int $code 错误码值
    */
    private function check($userOrm, $password, $code, $resp) {
        if ($userOrm == null) {
            return $resp::ACCOUNT_NOT_EXIST;
        }

        if ($password != $userOrm->password) {
            return $resp::INCORRECT_PASSWORD;

        }
        return $code;
    }

    /**
     * 本方法用于根据userId生成jwt
     * @access private
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\User $userOrm user表根据account字段值的查询结果ORM
    */
    private function generateJwt($userOrm) {
        $jwt = new Jwt();
        $jwt->generate(['id' => $userOrm->id]);
        $this->jwt = $jwt;
    }

    /**
     * 本方法用于更新用户最后登录时间
     * @access private
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\User $userOrm user表根据account字段值的查询结果ORM
     * @param string $lastLoginDate 最后登录时间
     * @param int $code 错误码值
     * @param Resp $resp 返回JSON类 在本方法中用作code字典
     * @return int $code 错误码值
    */
    private function updateLastLoginTime($userOrm, $lastLoginDate, $code, $resp) {

        $saveRes = $userOrm->updateLastLoginTime($lastLoginDate);
        if (!$saveRes) {
            $code = $resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于根据User表的ORM填充Biz层的User对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param array $params 参数数组
     * @param array $rules 校验规则数组
     * @param array $messages 错误信息数组
     * @return void
    */
    public function fill($model) {
        $this->id = $model->id;
        $this->account = $model->account;
        $this->password = $model->password;
        $this->username = $model->username;
        $this->email = $model->email;
        $this->mobile = $model->mobile;
        $this->role = $model->role->name;
    }

    /**
     * 本方法用于处理登出操作
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $jwt jwt值
     * @return int $code
    */
    public function logout($jwt) {
        $code = 0;

        // step1. 解析jwt start
        $this->jwt = new Jwt();
        $this->jwt->token = $jwt;
        $claims = $this->jwt->parse();
        if ($claims == null) {
            $code = Resp::PARSE_JWT_FAILED;
            return $code;
        }
        // step1. 解析jwt end

        // step2. 根据jwt的解析结果查询用户信息 start
        $userModel = new \App\Http\Models\User();
        $userOrm = $userModel->findById($claims['id']);
        if ($userOrm == null) {
            $code = Resp::JWT_INVALID;
            return $code;
        }
        // step2. 根据jwt的解析结果查询用户信息 end
        $this->fill($userOrm);
        return $code;
    }
}
