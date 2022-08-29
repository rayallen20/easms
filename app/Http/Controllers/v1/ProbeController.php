<?php
namespace App\Http\Controllers\v1;

use App\Biz\Logger;
use App\Biz\ProbeTemplate;
use App\Biz\User;
use App\Http\Controllers\Controller;
use App\Lib\Lib;
use App\Lib\Resp;
use Illuminate\Http\Request;

class ProbeController extends Controller {
    /**
     * 本方法用于创建调研模板操作
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 用户jwt(必填)
     * probe.name 调研模板名称
     * probe.startDate 调研模板开始作答时间
     * probe.endDate 调研模板结束作答时间
     * @return string $json 返回至前端的json
     */
    public function create(Request $request) {
        // step1. 接收参数并校验 start
        $jwt = $request->input('user.jwt');
        $name = $request->input('probe.name');
        $startDate = $request->input('probe.startDate');
        $endDate = $request->input('probe.endDate');

        $params = [
            'jwt' => $jwt,
            'name' => $name,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        $rules = [
            'jwt' => 'required|string',
            'name' => 'required|string',
            'startDate' => 'required|date|date_format:Y-m-d',
            'endDate' => 'required|date|date_format:Y-m-d',
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'name.required' => '调研模板名称不能为空',
            'name.string' => '调研模板名称内容必须为字符串',
            'startDate.required' => '调研模板开始日期不能为空',
            'startDate.date' => '调研模板开始日期不是有效日期',
            'startDate.date_format' => '调研模板开始日期必须为年-月-日格式字符串',
            'endDate.required' => '调研模板结束日期不能为空',
            'endDate.date' => '调研模板结束日期不是有效日期',
            'endDate.date_format' => '调研模板结束日期必须为年-月-日格式字符串',
        ];
        $lib = new Lib();
        $resp = new Resp();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }

//        $isEarly = $lib->isEarly($startDate, $endDate);
//        if (!$isEarly) {
//            $json = $resp->paramInvalid('开始时间必须晚于结束时间', []);
//            return $json;
//        }
//
//        $isEndDateEarly = $lib->isEarlyToday($endDate);
//        if ($isEndDateEarly) {
//            $json = $resp->paramInvalid('结束时间不得早于当天', []);
//            return $json;
//        }
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
        $probeBiz = new ProbeTemplate();
        $code = $probeBiz->create($name, $startDate, $endDate);
        if ($code == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logCreateProbe();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end
        $json = $resp->success([]);
        return $json;
    }

    /**
     * 本方法用于以列表形式查看调研模板信息
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
        $probeBiz = new ProbeTemplate();
        $result = $probeBiz->list($currentPage, $itemPerPage);
        // step3. 处理逻辑 end

        // step4. 封装返回值结构 start
        $data = [
            'user' => [
                'role' => $userBiz->role->name
            ],
            'pagination' => $result['pagination'],
            'probes' => []
        ];

       for ($i = 0; $i <= count($result['probes']) - 1; $i++) {
           $probe = $result['probes'][$i];
           $data['probes'][$i] = [
               'id' => $probe->id,
               'name' => $probe->name,
               'startDate' => $probe->startDate,
               'endDate' => $probe->endDate,
               'topicNumber' => $probe->topicNumber,
               'sort' => $probe->sort,
               'createdTime' => $probe->createdTime,
               'updatedTime' => $probe->updatedTime,
           ];
       }
        // step4. 封装返回值结构 end

        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于修改调研模板信息操作
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 用户jwt(必填)
     * probe.id 调研模板id
     * probe.name 调研模板名称
     * probe.startDate 调研模板开始作答时间
     * probe.endDate 调研模板结束作答时间
     * @return string $json 返回至前端的json
     */
    public function update(Request $request) {
        // step1. 接收参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('probe.id');
        $name = $request->input('probe.name');
        $startDate = $request->input('probe.startDate');
        $endDate = $request->input('probe.endDate');

        $params = [
            'jwt' => $jwt,
            'id' => $id,
            'name' => $name,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        $rules = [
            'jwt' => 'required|string',
            'name' => 'required|string',
            'id' => 'required|int|min:1',
            'startDate' => 'required|date|date_format:Y-m-d',
            'endDate' => 'required|date|date_format:Y-m-d',
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'id.required' => '调研模板id不能为空',
            'id.int' => '调研模板id必须为整型',
            'id.min' => '调研模板id字段值不能小于1',
            'name.required' => '调研模板名称不能为空',
            'name.string' => '调研模板名称内容必须为字符串',
            'startDate.required' => '调研模板开始日期不能为空',
            'startDate.date' => '调研模板开始日期不是有效日期',
            'startDate.date_format' => '调研模板开始日期必须为年-月-日格式字符串',
            'endDate.required' => '调研模板结束日期不能为空',
            'endDate.date' => '调研模板结束日期不是有效日期',
            'endDate.date_format' => '调研模板结束日期必须为年-月-日格式字符串',
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
        $probeBiz = new ProbeTemplate();
        $code = $probeBiz->update($id, $name, $startDate, $endDate);
        if ($code == Resp::PROBE_NOT_EXIST) {
            $json = $resp->probeNotExist([]);
            return $json;
        }

        if ($code == Resp::PROBE_HAS_BEEN_DELETE) {
            $json = $resp->probeHasBeenDeleted([]);
            return $json;
        }

        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logUpdateProbe();
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
            'probe' => [
                'id' => $probeBiz->id,
                'name' => $probeBiz->name,
                'startDate' => $probeBiz->startDate,
                'endDate' => $probeBiz->endDate,
                'topicNumber' => $probeBiz->topicNumber,
                'sort' => $probeBiz->sort,
                'createdTime' => $probeBiz->createdTime,
                'updatedTime' => $probeBiz->updatedTime,
            ]
        ];

        $json = $resp->success($data);
        return $json;
        // step5. 封装返回值结构 end
    }

    /**
     * 本方法用于删除调研模板
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 操作者用户jwt
     * probe.id int 被删除的调研模板信息id
     * @return string $json 返回至前端的JSON
     */
    public function delete(Request $request) {
        // step1. 接收参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('probe.id');

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
            'id.required' => '调研模板id不能为空',
            'id.int' => '调研模板id必须为整型',
            'id.min' => '调研模板id字段值不能小于1',
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
        $probeBiz = new ProbeTemplate();
        $code = $probeBiz->delete($id);
        if ($code == Resp::PROBE_NOT_EXIST) {
            $json = $resp->probeNotExist([]);
            return $json;
        }

        if ($code == Resp::PROBE_HAS_BEEN_DELETE) {
            $json = $resp->probeHasBeenDeleted([]);
            return $json;
        }

        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logDeleteProbe();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end

        $json = $resp->success([]);
        return $json;
    }
}
