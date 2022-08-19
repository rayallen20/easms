<?php
namespace App\Http\Controllers\v1;

use App\Biz\Department;
use App\Biz\Logger;
use App\Biz\User;
use App\Http\Controllers\Controller;
use App\Lib\Lib;
use App\Lib\Resp;
use Illuminate\Http\Request;

class DepartmentController extends Controller {
    /**
     * 本方法用于创建院系操作
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 用户jwt(必填)
     * department.name 院系名称(必填)
     * department.principalName 院系负责人姓名(必填)
     * department.principalMobile 院系负责人手机号(必填)
     * @return string $json 返回至前端的json
    */
    public function create(Request $request) {
        // step1. 接收参数并校验 start
        $jwt = $request->input('user.jwt');
        $name = $request->input('department.name');
        $principalName = $request->input('department.principalName');
        $principalMobile = $request->input('department.principalMobile');

        $params = [
            'jwt' => $jwt,
            'name' => $name,
            'principalName' => $principalName,
            'principalMobile' => $principalMobile
        ];

        $rules = [
            'jwt' => 'required|string',
            'name' => 'required|string',
            'principalName' => 'required|string',
            'principalMobile' => 'required|string'
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'name.required' => '院系名称不能为空',
            'name.string' => '院系名称内容必须为字符串',
            'principalName.required' => '院系负责人姓名不能为空',
            'principalName.string' => '院系负责人姓名必须为字符串',
            'principalMobile.required' => '院系负责人手机号不能为空',
            'principalMobile.string' => '院系负责人手机号必须为字符串',
        ];

        $resp = new Resp();

        $lib = new Lib();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }
        $isMobile = $lib->isMobile($principalMobile);
        if (!$isMobile) {
            $json = $resp->paramInvalid('手机号内容不符合手机号格式', []);
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
        $departmentBiz = new Department();
        $code = $departmentBiz->create($name, $principalName, $principalMobile);
        if ($code == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logCreateDepartment();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end
        $json = $resp->success([]);
        return $json;
    }
}
