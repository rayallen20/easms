<?php
namespace App\Http\Controllers\v1;

use App\Biz\Logger;
use App\Biz\Question\QuestionFactory;
use App\Biz\User;
use App\Http\Controllers\Controller;
use App\Lib\Lib;
use App\Lib\Resp;
use Illuminate\Http\Request;

class QuestionController extends Controller {
    /**
     * 本方法用于创建调研模板操作
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 用户jwt(必填)
     * probe.id int 调研模板id(必填)
     * question.type string 题目类型(必填)
     * question.stem string 题干(必填)
     * question.displayType string 统计结果展示形式(选填 仅在题型为单选题或多选题时必填)
     * question.answerType string 简答题类型(选填 仅在题型为简答题时必填)
     * question.option array<string> 选项(选填 仅在题型为单选题或多选题时必填)
     * @return string $json 返回至前端的json
     */
    public function create(Request $request) {
        // step1. 接收参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('probe.id');
        $questionType = $request->input('question.type');
        $stem = $request->input('question.stem');
        $displayType = $request->input('question.displayType');
        $answerType = $request->input('question.answerType');
        $options = $request->input('question.options');

        $params = [
            'jwt' => $jwt,
            'id' => $id,
            'questionType' => $questionType,
            'stem' => $stem,
            'displayType' => $displayType,
            'options' => $options,
        ];

        $rules = [
            'jwt' => 'required|string',
            'id' => 'required|int|min:1',
            'questionType' => 'required|string',
            'stem' => 'required|string',
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'id.required' => '调研模板id不能为空',
            'id.int' => '调研模板id必须为整型',
            'id.min' => '调研模板id字段值不能小于1',
            'questionType.required' => '问题类型不能为空',
            'questionType.string' => '问题类型内容必须为字符串',
            'stem.required' => '题干不能为空',
            'stem.string' => '题干内容必须为字符串',
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
        $questionFactory = new QuestionFactory();
        $result = $questionFactory->create($questionType);
        if ($result['code'] == Resp::PARAM_INVALID) {
            $json = $resp->paramInvalid($result['exceptionMessage'], []);
            return $json;
        }
        $questionBiz = $result['question'];
        $result = $questionBiz->create($id, $questionType, $stem, $displayType, $answerType, $options);
        if ($result['code'] == Resp::PARAM_INVALID) {
            $json = $resp->paramInvalid($result['exceptionMessage'], []);
            return $json;
        }

        if ($result['code'] == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logCreateQuestion();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end
        $json = $resp->success([]);
        return $json;
    }
}
