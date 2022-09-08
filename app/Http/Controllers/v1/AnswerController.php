<?php
namespace App\Http\Controllers\v1;

use App\Biz\ProbeAnswer;
use App\Biz\ProbeTemplate;
use App\Biz\Student;
use App\Http\Controllers\Controller;
use App\Lib\Lib;
use App\Lib\Resp;
use Illuminate\Http\Request;

class AnswerController extends Controller {
    /**
     * 本方法用于接收问卷答案并存入Kafka
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * student.number string 学生学号(必填)
     * probe.id int 调研模板id(必填)
     * probe.answers array 调研模板作答信息(必填)
     * probe.answers.id int 问题id(必填)
     * probe.answers.type string 问题类型(必填)
     * answers.answer int|string|array<int> 作答信息 单选题为int 简答题为string 多选题为array<int>
     * @return string $json 返回至前端的JSON
    */
    public function answer(Request $request) {
        // step1 接收参数 校验 start
        $studentNumber = $request->input('student.number');
        $probe = $request->input('probe');
        $probeId = $request->input('probe.id');
        $answers = $request->input('probe.answers');

        $params = [
            'probeId' => $probeId,
            'studentNumber' => $studentNumber
        ];

        $rules = [
            'probeId' => 'required|int|min:1',
            'studentNumber' => 'required|string'
        ];

        $exceptionMessages = [
            'probeId.required' => '问卷id不能为空',
            'probeId.int' => '问卷id必须为整型',
            'probeId.min' => '问卷id字段值不能小于1',
            'studentNumber.required' => '学号必须存在',
            'studentNumber.string' => '学号内容必须为字符串',
        ];

        $lib = new Lib();
        $resp = new Resp();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }
        // step1 接收参数 校验 end

        // step2. 检测学生是否存在 start
        $studentBiz = new Student();
        $code = $studentBiz->existByNumber($studentNumber);
        if ($code == Resp::STUDENT_NOT_EXIST) {
            $json = $resp->studentNotExist([]);
            return $json;
        }

        if ($code == Resp::STUDENT_HAS_BEEN_DELETE) {
            $json = $resp->studentHasBeenDeleted([]);
            return $json;
        }
        // step2. 检测学生是否存在 end

        // step3. 检测学生是否已作答 start
        $probeBiz = new ProbeTemplate();
        $probeBiz->id = $probeId;
        $answerBiz = new ProbeAnswer($probeBiz, $studentBiz);
        $code = $answerBiz->exist();
        if ($code == Resp::STUDENT_HAS_BEEN_ANSWERED) {
            $json = $resp->studentHasBeenAnswer([]);
            return $json;
        }
        // step3. 检测学生是否已作答 end

        // step4. 保存作答内容 start
        $content = $request->input();
        $code = $answerBiz->create($content);
        if ($code == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 保存作答内容 end

        // step5. 检测作答时间是否合规 start
        $code = $probeBiz->exist($probeId);
        if ($code == Resp::PROBE_NOT_EXIST) {
            $json = $resp->probeNotExist([]);
            return $json;
        }

        if ($code == Resp::PROBE_HAS_BEEN_DELETE) {
            $json = $resp->probeHasBeenDeleted([]);
            return $json;
        }

        $todayDate = date('Y-m-d');
        $startIsEarlier = $lib->isEarly($todayDate, $probeBiz->startDate);
        if ($startIsEarlier) {
            $json = $resp->todayBeforeProbeStartDate([]);
            return $json;
        }
        $endIsEarlier = $lib->isEarly($probeBiz->endDate, $todayDate);
        if ($endIsEarlier) {
            // 晚于作答时间
            $json = $resp->todayAfterProbeEndDate([]);
            return $json;
        }
        // step5. 检测作答时间是否合规 end

        // step6. 发送作答内容至MQ start
        $answerBiz->send($probe);
        // step6. 发送作答内容至MQ end
        $json = $resp->success([]);
        return $json;
    }
}
