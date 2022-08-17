<?php

namespace App\Http\Controllers\v1;

use App\Biz\Logger;
use App\Biz\Role;
use App\Biz\User;
use App\Http\Controllers\Controller;
use App\Lib\Lib;
use App\Lib\Resp;
use Illuminate\Http\Request;

class UserController extends Controller {
    /**
     * 本方法用于用户登录操作
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * account string 账号(必填)
     * password string 密码(必填)
     * @return string $json 返回的JSON
    */
    public function login(Request $request) {
        // step1. 接收参数 验证规则 start
        $account = $request->post('account');
        $password = $request->post('password');

        $params = [
            'account' => $account,
            'password' => $password
        ];

        $rules = [
            'account' => 'required|string',
            'password' => 'required|string|min:8|max:16'
        ];

        $exceptionMessages = [
            'account.required' => '账号不能为空',
            'account.string' => '账号内容必须为字符串',
            'password.required' => '密码不能为空',
            'password.string' => '密码内容必须为字符串',
            'password.min' => '密码长度不得低于8位',
            'password.max' => '密码长度不得高于16位'
        ];

        $resp = new Resp();

        $lib = new Lib();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }
        // step1. 接受参数 验证规则 end

        // step2. 逻辑处理 start
        $userBiz = new User();
        $code = $userBiz->login($account, $password);
        if ($code == $resp::ACCOUNT_NOT_EXIST) {
            $json = $resp->accountNotExist([]);
            return $json;
        }

        if ($code == $resp::INCORRECT_PASSWORD) {
            $json = $resp->incorrectPassword([]);
            return $json;
        }

        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step2. 逻辑处理 end

        // step3. 记录操作日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logLogin();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 记录操作日志 end

        $data = [
            'jwt' => $userBiz->jwt->token,
            'role' => $userBiz->role
        ];
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于用户登出操作 本操作并不涉及用户状态的改变
     * 仅需确认用户存在 然后记录登出操作日志即可
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * jwt string token值(必填)
     * @return string $json 返回的JSON
     */
    public function logout(Request $request){
        // step1. 接收参数 验证规则 start
        $jwt = $request->post('jwt');

        $params = [
            'jwt' => $jwt
        ];

        $rules = [
            'jwt' => 'required|string',
        ];

        $exceptionMessages = [
            'token.required' => 'token值不能为空',
        ];

        $resp = new Resp();

        $lib = new Lib();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }
        // step1. 接收参数 验证规则 end

        // step2. 逻辑处理 start
        $userBiz = new User();
        $code = $userBiz->logout($jwt);
        if ($code == Resp::PARSE_JWT_FAILED) {
            $json = $resp->parseJwtFailed([]);
            return $json;
        }

        if ($code == Resp::JWT_INVALID) {
            $json = $resp->jwtInvalid([]);
            return $json;
        }
        // step2. 逻辑处理 end

        // step3. 记录操作日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logLogout();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 记录操作日志 end
        $json = $resp->success([]);
        return $json;
    }

    /**
     * 本方法用于用户登录操作
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 操作者的jwt(必填)
     * target.account string 被创建者的账号(必填)
     * target.password string 被创建者的密码(必填)
     * target.repeatPassword string 重复输入的被创建者密码(必填)
     * @return string $json 返回的JSON
     */
    public function create(Request $request) {
        // step1. 接收参数 验证规则 start
        $account = $request->input('target.account');
        $password = $request->input('target.password');
        $repeatPassword = $request->input('target.repeatPassword');
        $username = $request->input('target.username');
        $email = $request->input('target.email');
        $mobile = $request->input('target.mobile');
        $roleId = $request->input('target.roleId');
        $jwt = $request->input('user.jwt');

        $params = [
            'account' => $account,
            'password' => $password,
            'repeatPassword' => $repeatPassword,
            'username' => $username,
            'email' => $email,
            'mobile' => $mobile,
            'roleId' => $roleId,
            'jwt' => $jwt
        ];
        $json = self::checkCreateParam($params);
        if ($json != null) {
            return $json;
        }
        // step1. 接收参数 验证规则 end

        // step2. 鉴权 start
        $userBiz = new User();
        $resp = new Resp();
        $code = $userBiz->authenticate($jwt);
        if ($code == Resp::PARSE_JWT_FAILED) {
            $json = $resp->parseJwtFailed([]);
            return $json;
        }

        if ($code == Resp::JWT_INVALID) {
            $json = $resp->jwtInvalid([]);
            return $json;
        }

        if ($userBiz->role->name != 'super_admin') {
            $json = $resp->permissionDeny([]);
            return $json;
        }
        // step2. 鉴权 end

        // step3. 创建用户 start
        $target = new User();
        $target->account = $account;
        $target->password = $password;
        $target->username = $username;
        $target->email = $email;
        $target->mobile = $mobile;
        $target->role = new Role();
        $target->role->id = $roleId;
        $code = $userBiz->create($target);

        if ($code == Resp::ACCOUNT_EXISTED) {
            $json = $resp->accountExisted([]);
            return $json;
        }

        if ($code == Resp::ROLE_NOT_EXIST) {
            $json = $resp->roleNotExist([]);
            return $json;
        }

        if ($code == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 创建用户 end

        // step4. 记录操作日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logCreateUser();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录操作日志 end
        $json = $resp->success([]);
        return $json;
    }

    /**
     * 本方法用于为create方法校验参数
     * 规则:
     * email: 必须包含@和.
     * password: 必须包含大写字母、小写字母、数字、特殊字符
     * mobile: 必须为1开头的11位数字
     * @access private
     * @author Roach<18410269837@163.com>
     * @param array $params 参数列表
     * @return string|null 参数合规则返回空 否则返回一个标识参数不合规原因的JSON
     */
    private function checkCreateParam(array $params) {
        $rules = [
            'account' => 'required|string',
            'password' => 'required|string|min:8|max:16',
            'repeatPassword' => 'required|string|min:8|max:16',
            'username' => 'required|string',
            'email' => 'required|string',
            'mobile' => 'required|string',
            'roleId' => 'required|int',
            'jwt' => 'required|string'
        ];

        $exceptionMessages = [
            'account.required' => '账号不能为空',
            'account.string' => '账号内容必须为字符串',
            'password.required' => '密码不能为空',
            'password.string' => '密码内容必须为字符串',
            'password.min' => '密码长度不得低于8位',
            'password.max' => '密码长度不得高于16位',
            'repeatPassword.required' => '重复输入的密码不能为空',
            'repeatPassword.string' => '重复输入的密码内容必须为字符串',
            'repeatPassword.min' => '重复输入的密码长度不得低于8位',
            'repeatPassword.max' => '重复输入的密码长度不得高于16位',
            'username.required' => '用户名不能为空',
            'username.string' => '用户名内容必须为字符串',
            'email.required' => '邮箱不能为空',
            'email.string' => '邮箱内容不符合邮箱格式',
            'mobile.required' => '电话不能为空',
            'mobile.string' => '电话内容必须为字符串',
            'roleId.required' => '角色不能为空',
            'roleId.int' => '角色ID必须为int',
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串'
        ];

        $resp = new Resp();

        $lib = new Lib();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }

        // 校验email是否合规
        $isEmail = $lib->isEmail($params['email']);
        if (!$isEmail) {
            $json = $resp->paramInvalid('邮箱内容不符合邮箱格式', []);
            return $json;
        }

        // 校验mobile是否合规
        $isMobile = $lib->isMobile($params['mobile']);
        if (!$isMobile) {
            $json = $resp->paramInvalid('手机号内容不符合手机号格式', []);
            return $json;
        }

        // 校验密码是否含有大写字母
        $containLarge = $lib->containLarge($params['password']);
        if (!$containLarge) {
            $json = $resp->paramInvalid('密码内容不包含大写字母', []);
            return $json;
        }

        // 校验密码是否包含小写字母
        $containSmall = $lib->containSmall($params['password']);
        if (!$containSmall) {
            $json = $resp->paramInvalid('密码内容不包含小写字母', []);
            return $json;
        }

        // 校验密码是否包含特殊字符
        $containSpecial = $lib->containSpecialChar($params['password']);
        if (!$containSpecial) {
            $json = $resp->paramInvalid('密码内容不包含特殊字符', []);
            return $json;
        }

        // 校验密码是否包含数字
        $containNumber = $lib->containNumber($params['password']);
        if(!$containNumber){
            $json = $resp->paramInvalid('密码内容不包含数字', []);
            return $json;
        }

        // 校验2次输入的密码是否一致
        if ($params['password'] != $params['repeatPassword']) {
            $json = $resp->paramInvalid('两次输入的密码不一致', []);
            return $json;
        }
        return null;
    }
}
