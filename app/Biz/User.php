<?php
namespace App\Biz;

use App\Lib\Jwt;
use App\Lib\Pagination;
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
     * @var Role $role 用户角色名称
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
        $userOrm = $model->findByAccount($account);
        if ($userOrm == null) {
            $code =  $resp::ACCOUNT_NOT_EXIST;
            return $code;
        }
        $code = self::check($userOrm, $password);
        if ($code != 0) {
            return $code;
        }
        // step1. 校验参数名密码是否正确 end

        // step2. 校验用户状态 start
        if ($userOrm->status != \App\Http\Models\User::STATUS['normal']) {
            $code = Resp::USER_HAS_BEEN_DELETED;
            return $code;
        }
        // step2. 校验用户状态 end

        // step3. 生成JWT start
        $this->fill($userOrm);
        $this->generateJwt($userOrm);
        // step3. 生成JWT end

        // step4. 更新最后登录时间 start
        $lastLoginDate = date('Y-m-d H:i:s');
        $code = $this->updateLastLoginTime($userOrm, $lastLoginDate, $code, $resp);
        if ($code != 0) {
            return $code;
        }
        // step4. 更新最后登录时间 end
        $this->lastLoginTime = $lastLoginDate;
        return $code;
    }

    /**
     * 本方法用于根据User表的查询结果检查登录账号密码是否正确
     * @access private
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\User $userOrm user表根据account字段值的查询结果ORM
     * @param string $password 用户填写的密码经md5加密后的值
     * @return int $code 错误码值
    */
    private function check($userOrm, $password) {
        $code = 0;
        if ($password != $userOrm->password) {
            $code = Resp::INCORRECT_PASSWORD;
            return $code;

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
        $this->role = new Role();
        $this->role->id = $model->role->id;
        $this->role->name = $model->role->name;
        $this->lastLoginTime = $model->last_login_time;
    }

    /**
     * 本方法用于处理登出操作
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $jwt jwt值
     * @return int $code 错误码 若操作无错误则返回0
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

        if ($userOrm->status != \App\Http\Models\User::STATUS['normal']) {
            $code = Resp::USER_HAS_BEEN_DELETED;
            return $code;
        }
        // step2. 根据jwt的解析结果查询用户信息 end
        $this->fill($userOrm);
        return $code;
    }

    /**
     * 本方法用于根据jwt进行鉴权
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $jwt jwt值
     * @return int $code 错误码 若鉴权无错误则返回0
    */
    public function authenticate($jwt) {
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

        if ($userOrm->status != \App\Http\Models\User::STATUS['normal']) {
            $code = Resp::USER_HAS_BEEN_DELETED;
            return $code;
        }
        // step2. 根据jwt的解析结果查询用户信息 end
        $this->fill($userOrm);
        return $code;
    }

    /**
     * 本方法用于创建用户
     * @access public
     * @author Roach<18410269837@163.com>
     * @param User $target 待创建用户
     * @return int $code 操作错误码
    */
    public function create(User $target) {
        $code = 0;

        // 检测同名账号是否存在
        if ($this->existSameAccount($target->account)) {
            $code = Resp::ACCOUNT_EXISTED;
            return $code;
        }

        // 检测角色是否存在
        if (!$target->role->exist()) {
            $code = Resp::ROLE_NOT_EXIST;
            return $code;
        }

        // 落盘保存
        $target->password = md5($target->password);
        $model = new \App\Http\Models\User();
        $result = $model->create($target);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于根据账号检测用户是否存在
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $account 账号
     * @return bool true表示存在 false表示不存在
    */
    private function existSameAccount($account) {
        $model = new \App\Http\Models\User();
        $userOrm = $model->findByAccount($account);
        if ($userOrm == null) {
            return false;
        }
        return true;
    }

    /**
     * 本方法用于列表展示用户信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $currentPage 当前页数
     * @param int $itemPerPage 每页显示信息条数
     * @return array $result 本数组共3项内容:
     * array<User> $result['users']:用户信息集合
     * App\Lib\Pagination $result['pagination']:分页器对象
     * int $result['code']:错误码
    */
    public function list($currentPage, $itemPerPage) {
        $result = [
            'users' => [],
            'pagination' => null,
            'code' => 0
        ];

        $pagination = new Pagination($currentPage, $itemPerPage);
        $offset = $pagination->calcOffset();

        $model = new \App\Http\Models\User();
        $userCollection = $model->findNormalUsers($offset, $itemPerPage);
        for ($i = 0; $i <= count($userCollection) - 1; $i++) {
            $userOrm = $userCollection[$i];
            $user = new User();
            $user->fill($userOrm);
            $result['users'][$i] = $user;
        }

        $totalUserNum = $model->countNormalUser();
        $pagination->calcTotalPage($totalUserNum);
        $result['pagination'] = $pagination;
        return $result;
    }

    /**
     * 本方法用于更新用户信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $username 更新后的用户名
     * @param string $email 更新后的邮箱
     * @param string $mobile 更新后的手机号
     * @return int $code 表示更新结果的错误码 成功则返回0
    */
    public function update($username, $email, $mobile) {
        $code = 0;

        $model = new \App\Http\Models\User();
        $result = $model->updateUser($this->id, $username, $email, $mobile);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }

        $this->username = $username;
        $this->email = $email;
        $this->mobile = $mobile;
        return $code;
    }

    /**
     * 本方法用于修改用户密码
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $oldPassword 原密码
     * @param string $newPassword 新密码
     * @return int $code 错误码 若操作无错误则返回0
    */
    public function updatePassword($oldPassword, $newPassword) {
        $code = 0;
        $oldPassword = md5($oldPassword);
        if ($oldPassword != $this->password) {
            $code = Resp::INCORRECT_PASSWORD;
            return $code;
        }

        $newPassword = md5($newPassword);
        $model = new \App\Http\Models\User();
        $result = $model->updatePassword($this->id, $newPassword);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }

        $this->password = $newPassword;
        return $code;
    }

    /**
     * 本方法用于删除用户
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $targetId 待删除用户的用户id
     * @return int $code 错误码 若操作无错误则返回0
    */
    public function delete($targetId) {
        $code = 0;
        if ($targetId == $this->id) {
            $code = Resp::CAN_NOT_DELETE_SELF;
            return $code;
        }

        $model = new \App\Http\Models\User();
        $userOrm = $model->findById($targetId);
        if($userOrm == null) {
            $code = Resp::TARGET_USER_NOT_EXIST;
            return $code;
        }

        $result = $userOrm->deleteUser();

        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }
}
