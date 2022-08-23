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

    /**
     * 本方法用于以列表形式查看指定院系下的专业信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * jwt
     * id int 院系id
     * currentPage int 当前页数
     * itemPerPage int 每页显示信息条数
     * @return string $json 返回的JSON
     */
    public function list(Request $request) {
        // step1. 接收参数 验证规则 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('department.id');
        $currentPage = $request->input('pagination.currentPage');
        $itemPerPage = $request->input('pagination.itemPerPage');

        $params = [
            'currentPage' => $currentPage,
            'itemPerPage' => $itemPerPage,
            'jwt' => $jwt,
            'id' => $id,
        ];

        $rules = [
            'currentPage' => 'required|int|min:1',
            'itemPerPage' => 'required|int|min:1',
            'jwt' => 'required|string',
            'id' => 'required|int|min:1'
        ];

        $exceptionMessages = [
            'currentPage.required' => '当前页数不能为空',
            'currentPage.int' => '当前页数必须为整型',
            'currentPage.min' => '当前页数不得小于1',
            'itemPerPage.required' => '每页显示条目不能为空',
            'itemPerPage.int' => '每页显示条目必须为整型',
            'itemPerPage.min' => '每页显示条目不得小于1',
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'id.required' => '院系id不能为空',
            'id.int' => '院系id必须为整型',
            'id.min' => '院系id不得小于1',
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
        // step2. 鉴权 end

        // step3. 处理逻辑 start
        $majorBiz = new Major();
        $result = $majorBiz->list($id, $currentPage, $itemPerPage);
        if ($result['code'] == Resp::DEPARTMENT_NOT_EXIST) {
            $json = $resp->departmentNotExist([]);
            return $json;
        }

        if ($result['code'] == Resp::DEPARTMENT_HAS_BEEN_DELETE) {
            $json = $resp->departmentHasBeenDeleted([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 封装返回值结构 start
        $data = [
            'user' => [
                'role' => $userBiz->role->name
            ],
            'pagination' => $result['pagination'],
            'majors' => []
        ];

        for ($i = 0; $i <= count($result['majors']) - 1; $i++) {
            $major = $result['majors'][$i];
            $data['majors'][] = [
                'id' => $major->id,
                'department' => $major->department->name,
                'name' => $major->name,
                'createdTime' => $major->createdTime,
                'updatedTime' => $major->updatedTime,
            ];
        }
        // step4. 封装返回值结构 end

        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于修改院系信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt 操作者jwt
     * major.id int 专业id
     * major.name string 修改后的专业名称
     * @return string $json 返回至前端的JSON
     */
    public function update(Request $request) {
        // step1. 接收参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('major.id');
        $name = $request->input('major.name');

        $params = [
            'jwt' => $jwt,
            'id' => $id,
            'name' => $name
        ];

        $rules = [
            'jwt' => 'required|string',
            'id' => 'required|int|min:1',
            'name' => 'required|string',
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'name.required' => '专业名称不能为空',
            'name.string' => '专业名称内容必须为字符串',
            'id.required' => '专业id不能为空',
            'id.int' => '专业id必须为整型',
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

        // step3. 处理逻辑 start
        $majorBiz = new Major();
        $code = $majorBiz->update($id, $name);

        if ($code == Resp::MAJOR_NOT_EXIST) {
            $json = $resp->majorNotExist([]);
            return $json;
        }

        if ($code == Resp::MAJOR_HAS_BEEN_DELETE) {
            $json = $resp->majorHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logUpdateMajor();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end

        // step5. 封装返回值结构 start
        $data = [
            'user' => [
                'role' => $userBiz->role->name,
            ],
            'major' => [
                'id' => $majorBiz->id,
                'name' => $majorBiz->name,
                'createdTime' => $majorBiz->createdTime,
                'updatedTime' => $majorBiz->updatedTime,
            ],
        ];
        $json = $resp->success($data);
        return $json;
        // step5. 封装返回值结构 end
    }

    /**
     * 本方法用于删除院系
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 操作者用户jwt
     * major.id int 被删除的院系信息id
     * @return string $json 返回至前端的JSON
     */
    public function delete(Request $request) {
        // step1. 接受参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('major.id');

        $params = [
            'jwt' => $jwt,
            'id' => $id,
        ];

        $rules = [
            'jwt' => 'required|string',
            'id' => 'required|int|min:1',
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'id.required' => '专业id不能为空',
            'id.int' => '专业id必须为整型',
            'id.min' => 'id字段值不能小于1',
        ];

        $lib = new Lib();
        $resp = new Resp();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }
        // step1. 接受参数并校验 end

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
        $code = $majorBiz->delete($id);
        if ($code == Resp::MAJOR_NOT_EXIST) {
            $json = $resp->majorNotExist([]);
            return $json;
        }

        if ($code == Resp::MAJOR_HAS_BEEN_DELETE) {
            $json = $resp->majorHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logDeleteMajor();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end

        $json = $resp->success([]);
        return $json;
    }
}
