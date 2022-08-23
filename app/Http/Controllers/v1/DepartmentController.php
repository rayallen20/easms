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

    /**
     * 本方法用于以列表形式查看院系信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * jwt string 用户jwt
     * currentPage int 当前页数
     * itemPerPage int 每页显示信息条数
     * @return string $json 返回的JSON
     */
    public function list(Request $request) {
        // step1. 接受参数 验证规则 start
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
        // step1. 接受参数 验证规则 end

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
        $departmentBiz = new Department();
        $result = $departmentBiz->list($currentPage, $itemPerPage);
        // step3. 处理逻辑 end

        // step4. 封装返回值结构 start
        $data = [
            'user' => [
                'role' => $userBiz->role->name
            ],
            'pagination' => $result['pagination'],
            'departments' => []
        ];

        for ($i = 0; $i <= count($result['departments']) - 1; $i++) {
            $department = $result['departments'][$i];

            if ($department->principalName == null) {
                $department->principalName = '';
            }

            if ($department->principalMobile == null) {
                $department->principalMobile = '';
            }

            $data['departments'][] = [
                'id' => $department->id,
                'name' => $department->name,
                'principalName' => $department->principalName,
                'principalMobile' => $department->principalMobile,
                'createdTime' => $department->createdTime,
                'updatedTime' => $department->updatedTime,
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
     * department.id int 院系id
     * department.name string 院系名称
     * department.principalName string 院系负责人姓名
     * department.principalMobile string 院系负责人手机号
     * @return string $json 返回至前端的JSON
     */
    public function update(Request $request) {
        // step1. 接收参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('department.id');
        $name = $request->input('department.name');
        $principalName = $request->input('department.principalName');
        $principalMobile = $request->input('department.principalMobile');

        $params = [
            'jwt' => $jwt,
            'id' => $id,
            'name' => $name,
            'principalName' => $principalName,
            'principalMobile' => $principalMobile
        ];

        $json = self::checkUpdateParam($params);
        if ($json != null) {
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
        $departmentBiz = new Department();
        $code = $departmentBiz->update($id, $name, $principalName, $principalMobile);
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
        $code = $logger->logUpdateDepartment();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end

        // step5. 封装返回值结构 start

        if ($departmentBiz->principalName == null) {
            $departmentBiz->principalName = '';
        }

        if ($departmentBiz->principalMobile == null) {
            $departmentBiz->principalMobile = '';
        }

        $data = [
            'user' => [
                'role' => $userBiz->role->name,
            ],
            'department' => [
                'id' => $departmentBiz->id,
                'name' => $departmentBiz->name,
                'principalName' => $departmentBiz->principalName,
                'principalMobile' => $departmentBiz->principalMobile,
                'createdTime' => $departmentBiz->createdTime,
                'updatedTime' => $departmentBiz->updatedTime,
            ]
        ];
        $json = $resp->success($data);
        return $json;
        // step5. 封装返回值结构 end
    }

    /**
     * 本方法用于为update方法校验参数
     * 规则:
     * id: 必须为大于0的整数
     * name: 必须存在
     * principalName: 必须存在
     * principalMobile: 必须为1开头的11位数字
     * @access private
     * @author Roach<18410269837@163.com>
     * @param array $params 参数列表
     * @return string|null 参数合规则返回空 否则返回一个标识参数不合规原因的JSON
    */
    private function checkUpdateParam($params) {
        $rules = [
            'jwt' => 'required|string',
            'id' => 'required|int|min:1',
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
            'id.required' => '院系id不能为空',
            'id.int' => '院系id必须为整型',
            'id.min' => 'id字段值不能小于1'
        ];

        $lib = new Lib();
        $resp = new Resp();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }

        // 校验mobile是否合规
        $isMobile = $lib->isMobile($params['principalMobile']);
        if (!$isMobile) {
            $json = $resp->paramInvalid('院系负责人手机号内容不符合手机号格式', []);
            return $json;
        }

        return null;
    }

    /**
     * 本方法用于删除院系
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 操作者用户jwt
     * department.id int 被删除的院系信息id
     * @return string $json 返回至前端的JSON
     */
    public function delete(Request $request) {
        // step1. 接收参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('department.id');

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
            'id.required' => '院系id不能为空',
            'id.int' => '院系id必须为整型',
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
        $departmentBiz = new Department();
        $code = $departmentBiz->delete($id);
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
        $code = $logger->logDeleteDepartment();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end

        $json = $resp->success([]);
        return $json;
    }
}
