<?php
namespace App\Http\Controllers\v1;

use App\Biz\Logger;
use App\Biz\Major;
use App\Biz\User;
use App\Http\Controllers\Controller;
use App\Lib\Lib;
use App\Lib\Resp;
use Illuminate\Http\Request;

class MajorController extends Controller {
    /**
     * 本方法用于创建专业操作
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 用户jwt(必填)
     * department.id 院系id(必填)
     * major.name 专业名称(必填)
     * @return string $json 返回至前端的json
     */
    public function create(Request $request) {
        // step1. 接收参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('department.id');
        $name = $request->input('department.major.name');

        $params = [
            'jwt' => $jwt,
            'id' => $id,
            'name' => $name,
        ];

        $rules = [
            'jwt' => 'required|string',
            'id' => 'required|int|min:1',
            'name' => 'required|string',
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'id.required' => 'id不能为空',
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
        $majorBiz = new Major();
        $code = $majorBiz->create($id, $name);
        if ($code == Resp::DEPARTMENT_NOT_EXIST) {
            $json = $resp->departmentNotExist([]);
            return $json;
        }

        if ($code == Resp::DEPARTMENT_HAS_BEEN_DELETE) {
            $json = $resp->departmentHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logCreateMajor();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end
        $json = $resp->success([]);
        return $json;
    }
}
