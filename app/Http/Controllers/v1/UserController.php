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

        if ($code == $resp::USER_HAS_BEEN_DELETED) {
            $json = $resp->userHasBeenDeleted([]);
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

        if ($code == $resp::USER_HAS_BEEN_DELETED) {
            $json = $resp->userHasBeenDeleted([]);
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

        if ($code == $resp::USER_HAS_BEEN_DELETED) {
            $json = $resp->userHasBeenDeleted([]);
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

        // 校验密码是否合规
        $isPassword = $lib->isPassword($params['password']);
        if (!$isPassword['flag']) {
            $json = $resp->paramInvalid($isPassword['reason'], []);
            return $json;
        }

        // 校验2次输入的密码是否一致
        if ($params['password'] != $params['repeatPassword']) {
            $json = $resp->paramInvalid('两次输入的密码不一致', []);
            return $json;
        }
        return null;
    }

    /**
     * 本方法用于以列表形式查看系统用户信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * currentPage int 当前页数
     * itemPerPage int 每页显示信息条数
     * @return string $json 返回的JSON
    */
    public function list(Request $request) {
        // step1. 接收参数 验证规则 start
        $jwt = $request->input('user.jwt');
        $currentPage = $request->input('pagination.currentPage');
        $itemPerPage = $request->input('pagination.itemPerPage');

        $params = [
            'currentPage' => $currentPage,
            'itemPerPage' => $itemPerPage,
            'jwt' => $jwt,
        ];

        $rules = [
            'currentPage' => 'required|int|min:1',
            'itemPerPage' => 'required|int|min:1',
            'jwt' => 'required|string',
        ];

        $exceptionMessages = [
            'currentPage.required' => '当前页数不能为空',
            'currentPage.int' => '当前页数必须为整型',
            'currentPage.min' => '当前页数不得小于1',
            'itemPerPage.required' => '每页显示条目不能为空',
            'itemPerPage.int' => '每页显示条目必须为整型',
            'itemPerPage.min' => '每页显示条目不得小于1',
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
        // step1. 接收参数 验证规则 end

        // step2. 鉴权 start
        $userBiz = new User();
        $code = $userBiz->authenticate($jwt);
        if ($code == Resp::PARSE_JWT_FAILED) {
            $json = $resp->parseJwtFailed([]);
            return $json;
        }

        if ($code == Resp::JWT_INVALID) {
            $json = $resp->jwtInvalid([]);
            return $json;
        }

        if ($code == $resp::USER_HAS_BEEN_DELETED) {
            $json = $resp->userHasBeenDeleted([]);
            return $json;
        }

        if ($userBiz->role->name != 'super_admin') {
            $json = $resp->permissionDeny([]);
            return $json;
        }
        // step2. 鉴权 end

        // step3. 处理逻辑 start
        $result = $userBiz->list($currentPage, $itemPerPage);
        // step3. 处理逻辑 end

        // step4. 封装返回值结构 start
        $data = [
            'users' => [],
            'pagination' => $result['pagination']
        ];
        for ($i = 0; $i <= count($result['users']) - 1; $i++) {
            $user = $result['users'][$i];

            if ($user->email == null) {
                $user->email = '';
            }

            if ($user->mobile == null) {
                $user->mobile = '';
            }

            if ($user->lastLoginTime == null) {
                $user->lastLoginTime = '';
            }

            $data['users'][] = [
                'id' => $user->id,
                'account' => $user->account,
                'username' => $user->username,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'role' => $user->role->name,
                'lastLoginTime' => $user->lastLoginTime,
            ];
        }
        // step4. 封装返回值结构 end

        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于修改用户信息(除账号和密码)
     * 账号一经创建不可修改 密码单独修改
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * username string 用户名
     * email string 电子邮箱
     * mobile string 手机号
     * @return string $json 返回至前端的JSON
    */
    public function update(Request $request) {
        // step1. 接受参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('user.id');
        $username = $request->input('user.username');
        $email = $request->input('user.email');
        $mobile = $request->input('user.mobile');

        $params = [
            'jwt' => $jwt,
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'mobile' => $mobile
        ];

        $json = self::checkUpdateParam($params);
        if ($json != null) {
            return $json;
        }
        // step1. 接受参数并校验 end

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

        if ($code == $resp::USER_HAS_BEEN_DELETED) {
            $json = $resp->userHasBeenDeleted([]);
            return $json;
        }

        // 本操作中若传入的id和jwt中的id不符 则同样判定为没有权限
        if ($userBiz->id != $id) {
            $json = $resp->onlyUpdateSelf([]);
            return $json;
        }
        // step2. 鉴权 end

        // step3. 修改信息 start
        $code = $userBiz->update($username, $email, $mobile);
        if ($code == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 修改信息 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logUpdateUser();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end

        // step4. 封装返回值结构 start
        if ($userBiz->email == null) {
            $userBiz->email = '';
        }

        if ($userBiz->mobile == null) {
            $userBiz->mobile = '';
        }

        if ($userBiz->lastLoginTime == null) {
            $userBiz->lastLoginTime = '';
        }

        $data = [
            'user' => [
                'id' => $userBiz->id,
                'account' => $userBiz->account,
                'username' => $userBiz->username,
                'email' => $userBiz->email,
                'mobile' => $userBiz->mobile,
                'role' => $userBiz->role->name,
                'lastLoginTime' => $userBiz->lastLoginTime,
            ],
        ];
        // step4. 封装返回值结构 end
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于为update方法校验参数
     * 规则:
     * id: 必须为大于0的整数
     * email: 必须包含@和.
     * mobile: 必须为1开头的11位数字
     * @access private
     * @author Roach<18410269837@163.com>
     * @param array $params 参数列表
     * @return string|null 参数合规则返回空 否则返回一个标识参数不合规原因的JSON
     */
    private function checkUpdateParam($params) {
        $rules = [
            'jwt' => 'required|string',
            'id' => 'required|int|min:1',
            'username' => 'required|string',
            'email' => 'required|string',
            'mobile' => 'required|string',
        ];

        $exceptionMessages = [
            'username.required' => '用户名不能为空',
            'username.string' => '用户名内容必须为字符串',
            'email.required' => '邮箱不能为空',
            'email.string' => '邮箱内容不符合邮箱格式',
            'mobile.required' => '电话不能为空',
            'mobile.string' => '电话内容必须为字符串',
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'id.required' => '用户id不能为空',
            'id.int' => '用户id必须为整型',
            'id.min' => 'id字段值不能小于1'
        ];

        $lib = new Lib();
        $resp = new Resp();
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

        return null;
    }

    /**
     * 本方法用于修改用户密码
     * 账号一经创建不可修改 密码单独修改
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * jwt string 用户jwt
     * id int 用户id
     * oldPassword string 原密码
     * newPassword string 新密码
     * newPasswordRepeat string 重复新密码
     * @return string $json 返回至前端的JSON
    */
    public function updatePassword(Request $request) {
        // step1. 接受参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('user.id');
        $oldPassword = $request->input('user.oldPassword');
        $newPassword = $request->input('user.newPassword');
        $newPasswordRepeat = $request->input('user.newPasswordRepeat');

        $params = [
            'jwt' => $jwt,
            'id' => $id,
            'oldPassword' => $oldPassword,
            'newPassword' => $newPassword,
            'newPasswordRepeat' => $newPasswordRepeat,
        ];
        $json = self::checkUpdatePasswordParam($params);
        if ($json != null) {
            return $json;
        }
        // step1. 接受参数并校验 end

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

        if ($code == $resp::USER_HAS_BEEN_DELETED) {
            $json = $resp->userHasBeenDeleted([]);
            return $json;
        }

        if ($userBiz->id != $id) {
            $json = $resp->onlyUpdateSelf([]);
            return $json;
        }
        // step2. 鉴权 end

        // step3. 处理逻辑 start
        $code = $userBiz->updatePassword($oldPassword, $newPassword);
        if ($code == Resp::INCORRECT_PASSWORD) {
            $json = $resp->incorrectPassword([]);
            return $json;
        }

        if ($code == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logUpdatePassword();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end

        $json = $resp->success([]);
        return $json;
    }

    /**
     * 本方法用于为updatePassword方法校验参数
     * @access private
     * @author Roach<18410269837@163.com>
     * @param array $params 参数列表
     * 规则:
     * jwt: 必须为字符串
     * id: 必须为大于0的整数
     * oldPassword: 必须符合密码规则(8-16位的、包含大写字母、小写字母、数字的字符串)
     * newPassword: 必须符合密码规则(8-16位的、包含大写字母、小写字母、数字的字符串)
     * newPasswordRepeat: 必须符合密码规则(8-16位的、包含大写字母、小写字母、数字的字符串)
     * @return string|null 参数合规则返回空 否则返回一个标识参数不合规原因的JSON
    */
    private function checkUpdatePasswordParam($params) {
        $rules = [
            'jwt' => 'required|string',
            'id' => 'required|int|min:1',
            'oldPassword' => 'required|string|min:8|max:16',
            'newPassword' => 'required|string|min:8|max:16',
            'newPasswordRepeat' => 'required|string|min:8|max:16',
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'id.required' => '用户id不能为空',
            'id.int' => '用户id必须为整型',
            'id.min' => 'id字段值不能小于1',
            'oldPassword.required' => '原密码不能为空',
            'oldPassword.string' => '原密码内容必须为字符串',
            'oldPassword.min' => '原密码长度不得低于8位',
            'oldPassword.max' => '原密码长度不得高于16位',
            'newPassword.required' => '新密码不能为空',
            'newPassword.string' => '新密码内容必须为字符串',
            'newPassword.min' => '新密码长度不得低于8位',
            'newPassword.max' => '新密码长度不得高于16位',
            'newPasswordRepeat.required' => '重复新密码不能为空',
            'newPasswordRepeat.string' => '重复新密码内容必须为字符串',
            'newPasswordRepeat.min' => '重复新密码长度不得低于8位',
            'newPasswordRepeat.max' => '重复新密码长度不得高于16位',
        ];

        $lib = new Lib();
        $resp = new Resp();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }

        $isPassword = $lib->isPassword($params['oldPassword']);
        if (!$isPassword['flag']) {
            $json = $resp->paramInvalid('原密码的'.$isPassword['reason'], []);
            return $json;
        }

        $isPassword = $lib->isPassword($params['newPassword']);
        if (!$isPassword['flag']) {
            $json = $resp->paramInvalid('新密码的'.$isPassword['reason'], []);
            return $json;
        }

        // 校验2次输入的密码是否一致
        if ($params['newPassword'] != $params['newPasswordRepeat']) {
            $json = $resp->paramInvalid('两次输入的新密码不一致', []);
            return $json;
        }
        return null;
    }

    /**
     * 本方法用于删除系统用户
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 操作者用户jwt
     * target.id int 被删除的用户id
     * @return string $json 返回至前端的JSON
    */
    public function delete(Request $request) {
        // step1. 接收参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('target.id');

        $params = [
            'jwt' => $jwt,
            'id' => $id
        ];

        $rules = [
            'jwt' => 'required|string',
            'id' => 'required|int|min:1',
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'id.required' => '用户id不能为空',
            'id.int' => '用户id必须为整型',
            'id.min' => 'id字段值不能小于1',
        ];

        $lib = new Lib();
        $resp = new Resp();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }
        // step1. 接收参数并校验 end

        // step2. 鉴权 start
        $userBiz = new User();
        $code = $userBiz->authenticate($jwt);
        if ($code == Resp::PARSE_JWT_FAILED) {
            $json = $resp->parseJwtFailed([]);
            return $json;
        }

        if ($code == Resp::JWT_INVALID) {
            $json = $resp->jwtInvalid([]);
            return $json;
        }

        if ($code == $resp::USER_HAS_BEEN_DELETED) {
            $json = $resp->userHasBeenDeleted([]);
            return $json;
        }

        if ($userBiz->role->name != 'super_admin') {
            $json = $resp->permissionDeny([]);
            return $json;
        }
        // step2. 鉴权 end

        // step3. 处理逻辑 start
        $code = $userBiz->delete($id);
        if ($code == Resp::CAN_NOT_DELETE_SELF) {
            $json = $resp->canNotDeleteSelf([]);
            return $json;
        }

        if ($code == Resp::TARGET_USER_NOT_EXIST) {
            $json = $resp->targetUserNotExist([]);
            return $json;
        }

        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logDeleteUser();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end

        $json = $resp->success([]);
        return $json;
    }

    /**
     * 本方法用于根据id显示单条用户信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 操作者用户jwt
     * target.id int 被查看的用户id
    */
    public function show(Request $request) {
        // step1. 接收参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('target.id');

        $params = [
            'jwt' => $jwt,
            'id' => $id
        ];

        $rules = [
            'jwt' => 'required|string',
            'id' => 'required|int|min:1',
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'id.required' => '用户id不能为空',
            'id.int' => '用户id必须为整型',
            'id.min' => 'id字段值不能小于1',
        ];

        $lib = new Lib();
        $resp = new Resp();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }
        // step1. 接收参数并校验 end

        // step2. 鉴权 start
        $userBiz = new User();
        $code = $userBiz->authenticate($jwt);
        if ($code == Resp::PARSE_JWT_FAILED) {
            $json = $resp->parseJwtFailed([]);
            return $json;
        }

        if ($code == Resp::JWT_INVALID) {
            $json = $resp->jwtInvalid([]);
            return $json;
        }

        if ($code == $resp::USER_HAS_BEEN_DELETED) {
            $json = $resp->userHasBeenDeleted([]);
            return $json;
        }

        // 若既不是超管也不是本人 则无权限查看其他用户的信息
        if ($userBiz->role->name != 'super_admin' && $userBiz->id != $id) {
            $json = $resp->permissionDeny([]);
            return $json;
        }
        // step2. 鉴权 end

        // step3. 处理逻辑 start
        $result = $userBiz->show($id);
        if ($result['code'] == Resp::TARGET_USER_NOT_EXIST) {
            $json = $resp->targetUserNotExist([]);
            return $json;
        }

        if ($result['code'] == Resp::USER_HAS_BEEN_DELETED) {
            $json = $resp->userHasBeenDeleted([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 封装返回结构 start

        if ($result['user']->email == null) {
            $result['user']->email = '';
        }

        if ($result['user']->mobile == null) {
            $result['user']->mobile = '';
        }

        if ($result['user']->lastLoginTime == null) {
            $result['user']->lastLoginTime = '';
        }

        $data = [
            'user' => [
                'id' => $result['user']->id,
                'account' => $result['user']->account,
                'username' => $result['user']->username,
                'email' => $result['user']->email,
                'mobile' => $result['user']->mobile,
                'role' => $result['user']->role->name,
                'lastLoginTime' => $result['user']->lastLoginTime,
            ],
        ];
        // step4. 封装返回结构 end
        $json = $resp->success($data);
        return $json;
    }
}
