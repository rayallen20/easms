<?php

namespace App\Http\Controllers\v1;

use App\Biz\Logger;
use App\Biz\User;
use App\Http\Controllers\Controller;
use App\Lib\Lib;
use App\Lib\Resp;
use Illuminate\Http\Request;

class UserController extends Controller {
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
}
