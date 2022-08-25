<?php
namespace App\Http\Controllers\v1;

use App\Biz\ExamArea;
use App\Biz\Logger;
use App\Biz\Nation;
use App\Biz\Student;
use App\Biz\User;
use App\Http\Controllers\Controller;
use App\Lib\Lib;
use App\Lib\Resp;
use Illuminate\Http\Request;

class StudentController extends Controller {
    /**
     * 本方法用于显示所有民族信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
     */
    public function showNation() {
        $nationBiz = new Nation();
        $nationCollection = $nationBiz->list();
        $data = [];
        for ($i = 0; $i <= count($nationCollection) - 1; $i++) {
            $nation = [
                'id' => $nationCollection[$i]->id,
                'code' => $nationCollection[$i]->code,
                'name' => $nationCollection[$i]->name,
            ];
            $data[$i] = $nation;
        }
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有考区信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
     */
    public function showExamArea() {
        $examAreaBiz = new ExamArea();
        $examAreaCollection = $examAreaBiz->list();
        $data = [];
        for ($i = 0; $i <= count($examAreaCollection) - 1; $i++) {
            $examArea = [
                'id' => $examAreaCollection[$i]->id,
                'code' => $examAreaCollection[$i]->code,
                'name' => $examAreaCollection[$i]->name,
            ];
            $data[$i] = $examArea;
        }
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有培养层次
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
     */
    public function showEducationLevel() {
        $data = Student::EDUCATION_LEVEL;
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有学制
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
     */
    public function showLengthOfSchool() {
        $data = Student::LENGTH_OF_SCHOOL;
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有学位
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
     */
    public function showDegree() {
        $data = Student::DEGREE;
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于创建教职工操作
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 用户jwt(必填)
     * student.number string 学号
     * student.name string 姓名
     * student.idNumber string 身份证号
     * student.gender int 性别
     * student.nation.id int 民族id
     * student.examArea.id int 考区id
     * student.department.id int 院系id
     * student.major.id int 专业id
     * student.majorDirection int 专业方向
     * student.grade string 年级
     * student.class string 班级
     * student.educationLevel.code int 培养层次编码
     * student.lengthOfSchool.code int 学制编码
     * student.degree.code int 学位编码
     * @return string $json 返回至前端的json
     */
    public function create(Request $request) {
        // step1. 接受参数 校验 start
        $jwt = $request->input('user.jwt');
        $studentNumber = $request->input('student.number');
        $name = $request->input('student.name');
        $idNumber = $request->input('student.idNumber');
        $genderCode = $request->input('student.gender.code');
        $nationId = $request->input('student.nation.id');
        $examAreaId = $request->input('student.examArea.id');
        $departmentId = $request->input('student.department.id');
        $majorId = $request->input('student.major.id');
        $majorDirection = $request->input('student.majorDirection');
        $grade = $request->input('student.grade');
        $class = $request->input('student.class');
        $educationLevelCode = $request->input('student.educationLevel.code');
        $lengthOfSchoolCode = $request->input('student.lengthOfSchool.code');
        $degreeCode = $request->input('student.degree.code');

        $params = [
            'jwt' => $jwt,
            'studentNumber' => $studentNumber,
            'name' => $name,
            'idNumber' => $idNumber,
            'genderCode' => $genderCode,
            'nationId' => $nationId,
            'examAreaId' => $examAreaId,
            'departmentId' => $departmentId,
            'majorId' => $majorId,
            'majorDirection' => $majorDirection,
            'grade' => $grade,
            'class' => $class,
            'educationLevelCode' => $educationLevelCode,
            'lengthOfSchoolCode' => $lengthOfSchoolCode,
            'degreeCode' => $degreeCode,
        ];
        $json = self::checkCreateParam($params);
        if ($json != null) {
            return $json;
        }
        // step1. 接受参数 校验 end

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
        $studentBiz = new Student();
        $code = $studentBiz->create($studentNumber, $name, $idNumber, $genderCode, $nationId, $examAreaId,
            $departmentId, $majorId, $majorDirection, $grade, $class, $educationLevelCode,
            $lengthOfSchoolCode, $degreeCode);

        if ($code == Resp::EDUCATION_LEVEL_NOT_EXIST) {
            $json = $resp->educationLevelNotExist([]);
            return $json;
        }

        if ($code == Resp::LENGTH_OF_SCHOOL_NOT_EXIST) {
            $json = $resp->lengthOfSchoolNotExist([]);
            return $json;
        }

        if ($code == Resp::DEGREE_NOT_EXIST) {
            $json = $resp->degreeNotExist([]);
            return $json;
        }

        if ($code == Resp::DEPARTMENT_NOT_EXIST) {
            $json = $resp->departmentNotExist([]);
            return $json;
        }

        if ($code == Resp::DEPARTMENT_HAS_BEEN_DELETE) {
            $json = $resp->departmentHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::MAJOR_NOT_EXIST) {
            $json = $resp->majorNotExist([]);
            return $json;
        }

        if ($code == Resp::MAJOR_HAS_BEEN_DELETE) {
            $json = $resp->majorHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::MAJOR_NOT_BELONGS_TO_DEPARTMENT) {
            $json = $resp->majorNotBelongsToDepartment([]);
            return $json;
        }

        if ($code == Resp::NATION_NOT_EXIST) {
            $json = $resp->nationNotExist([]);
            return $json;
        }

        if ($code == Resp::NATION_HAS_BEEN_DELETE) {
            $json = $resp->nationHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::EXAM_AREA_NOT_EXIST) {
            $json = $resp->examAreaNotExist([]);
            return $json;
        }

        if ($code == Resp::EXAM_AREA_HAS_BEEN_DELETE) {
            $json = $resp->examAreaHasBeenDeleted([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logCreateStudent();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end
        $json = $resp->success([]);
        return $json;
    }

    /**
     * 本方法用于为create方法校验参数
     * @access private
     * @author Roach<18410269837@163.com>
     * 规则:
     * studentNumber: 必须存在 长度不大于10位
     * name: 必须存在 长度不大于20位
     * idNumber: 必须存在 长度不大于18位
     * majorDirection: 必须存在 长度不大于20位
     * grade: 必须存在 长度不大于20位
     * class: 必须存在 长度不大于20位
     * @access private
     * @author Roach<18410269837@163.com>
     * @param array $params 参数列表
     * @return string|null 参数合规则返回空 否则返回一个标识参数不合规原因的JSON
     */
    private function checkCreateParam($params) {
        $rules = [
            'jwt' => 'required|string',
            'studentNumber' => 'required|string|max:10',
            'name' => 'required|string|max:10',
            'idNumber' => 'required|string|max:18',
            'genderCode' => 'required|int|min:0|max:1',
            'nationId' => 'required|int|min:1',
            'examAreaId' => 'required|int|min:1',
            'departmentId' => 'required|int|min:1',
            'majorId' => 'required|int|min:1',
            'majorDirection' => 'required|string|max:20',
            'grade' => 'required|string|max:20',
            'class' => 'required|string|max:20',
            'educationLevelCode' => 'required|int',
            'lengthOfSchoolCode' => 'required|int',
            'degreeCode' => 'required|int',
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'studentNumber.required' => '学号不能为空',
            'studentNumber.string' => '学号必须为字符串',
            'studentNumber.max' => '学号长度不得超过10位',
            'name.required' => '姓名不能为空',
            'name.string' => '姓名内容必须为字符串',
            'name.max' => '姓名长度不得超过10位',
            'idNumber.required' => '身份证号不能为空',
            'idNumber.string' => '身份证号必须为字符串',
            'idNumber.max' => '身份证号长度不得超过18位',
            'genderCode.required' => '教职工性别编码不能为空',
            'genderCode.int' => '教职工性别编码必须为整型',
            'genderCode.min' => '教职工性别编码必须大于等于0',
            'genderCode.max' => '教职工性别编码必须小于等于1',
            'nationId.required' => '民族id不能为空',
            'nationId.int' => '民族id必须为整型',
            'nationId.min' => '民族id字段值不能小于1',
            'examAreaId.required' => '考区id不能为空',
            'examAreaId.int' => '考区id必须为整型',
            'examAreaId.min' => '考区id字段值不能小于1',
            'departmentId.required' => '院系id不能为空',
            'departmentId.int' => '院系id必须为整型',
            'departmentId.min' => '院系id字段值不能小于1',
            'majorId.required' => '专业id不能为空',
            'majorId.int' => '专业id必须为整型',
            'majorId.min' => '专业id字段值不能小于1',
            'majorDirection.required' => '专业方向不能为空',
            'majorDirection.string' => '专业方向内容必须为字符串',
            'majorDirection.max' => '专业方向长度不得超过20位',
            'grade.required' => '年级不能为空',
            'grade.string' => '年级内容必须为字符串',
            'grade.max' => '年级长度不得超过20位',
            'class.required' => '班级不能为空',
            'class.string' => '班级内容必须为字符串',
            'class.max' => '班级长度不得超过20位',
            'educationLevelCode.required' => '培养层次编码不能为空',
            'educationLevelCode.int' => '培养层次编码必须为整型',
            'lengthOfSchoolCode.required' => '学制编码不能为空',
            'lengthOfSchoolCode.int' => '学制编码必须为整型',
            'degreeCode.required' => '学位编码不能为空',
            'degreeCode.int' => '学位编码必须为整型',
        ];

        $lib = new Lib();
        $resp = new Resp();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }

        $isIdNumber = $lib->isIdNumber($params['idNumber']);
        if (!$isIdNumber) {
            $json = $resp->paramInvalid('身份证号必须为18位数字或以X结尾的17位数字', []);
            return $json;
        }
        return null;
    }
}
