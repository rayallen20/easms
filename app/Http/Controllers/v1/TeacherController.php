<?php
namespace App\Http\Controllers\v1;

use App\Biz\JobTitle;
use App\Biz\Logger;
use App\Biz\Nationality;
use App\Biz\Politics;
use App\Biz\Subject;
use App\Biz\Teacher;
use App\Biz\User;
use App\Http\Controllers\Controller;
use App\Lib\Lib;
use App\Lib\Resp;
use Illuminate\Http\Request;

class TeacherController extends Controller {
    /**
     * 本方法用于显示所有任职状态
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
    */
    public function showOfficeHolding() {
        $data = Teacher::OFFICE_HOLDING_STATUS;
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有学历
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
    */
    public function showEducationBackground() {
        $data = Teacher::EDUCATION_BACKGROUNDS;
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
    public function showQualification() {
        $data = Teacher::QUALIFICATIONS;
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有学缘
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
    */
    public function showSource() {
        $data = Teacher::SOURCES;
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有专业技术职称
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
    */
    public function showJobTitle() {
        $jobTitleBiz = new JobTitle();
        $jobTitleCollection = $jobTitleBiz->list();
        $data = [];
        for ($i = 0; $i <= count($jobTitleCollection) - 1; $i++) {
            $jobTitle = [
                'id' => $jobTitleCollection[$i]->id,
                'code' => $jobTitleCollection[$i]->code,
                'name' => $jobTitleCollection[$i]->name,
            ];
            $data[$i] = $jobTitle;
        }
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有学科类别
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
     */
    public function showSubject() {
        $subjectBiz = new Subject();
        $subjectCollection = $subjectBiz->list();
        $data = [];
        for ($i = 0; $i <= count($subjectCollection) - 1; $i++) {
            $subject = [
                'id' => $subjectCollection[$i]->id,
                'code' => $subjectCollection[$i]->code,
                'name' => $subjectCollection[$i]->name,
            ];
            $data[$i] = $subject;
        }
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有政治面貌
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
     */
    public function showPolitics() {
        $politicsBiz = new Politics();
        $politicsCollection = $politicsBiz->list();
        $data = [];
        for ($i = 0; $i <= count($politicsCollection) - 1; $i++) {
            $subject = [
                'id' => $politicsCollection[$i]->id,
                'code' => $politicsCollection[$i]->code,
                'name' => $politicsCollection[$i]->name,
            ];
            $data[$i] = $subject;
        }
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有国籍
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
     */
    public function showNationality() {
        $nationalityBiz = new Nationality();
        $nationalityCollection = $nationalityBiz->list();
        $data = [];
        for ($i = 0; $i <= count($nationalityCollection) - 1; $i++) {
            $nationality = [
                'id' => $nationalityCollection[$i]->id,
                'code' => $nationalityCollection[$i]->code,
                'name' => $nationalityCollection[$i]->name,
            ];
            $data[$i] = $nationality;
        }
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
     * teacher.department.id int 教职工所属院系ID(必填)
     * teacher.jobNumber string 教职工工号(必填)
     * teacher.name string 教职工姓名(必填)
     * teacher.gender.code int 教职工性别编码(必填) 性别字典:App\Biz\Teacher::GENDER
     * teacher.birthDate string 教职工出生日期(必填)
     * teacher.intoSchoolDate string 教职工入校时间(必填)
     * teacher.officeHolding.code int 教职工任职状态编码 任职状态字典:App\Biz\Teacher::OFFICE_HOLDING_STATUS
     * teacher.educationBackground.code int 教职工学历编码 学历字典:App\Biz\Teacher::EDUCATION_BACKGROUNDS
     * teacher.qualification.code int 教职工学位编码 学位字典:App\Biz\Teacher::QUALIFICATIONS
     * teacher.source.code int 教职工学缘编码 学缘字典:App\Biz\Teacher::SOURCES
     * teacher.jobTitle.id int 专业技术职称id
     * teacher.subject.id int 学科类别id
     * teacher.politics.id 政治面貌id
     * teacher.nationality.id 国籍id
     * @return string $json 返回至前端的json
     */
    public function create(Request $request) {
        // step1 接收参数 校验 start
        $jwt = $request->input('user.jwt');
        $departmentId = $request->input('teacher.department.id');
        $jobNumber = $request->input('teacher.jobNumber');
        $name = $request->input('teacher.name');
        $genderCode = $request->input('teacher.gender.code');
        $birthDate = $request->input('teacher.birthDate');
        $intoSchoolDate = $request->input('teacher.intoSchoolDate');
        $officeHoldingCode = $request->input('teacher.officeHolding.code');
        $educationBackgroundCode = $request->input('teacher.educationBackground.code');
        $qualificationCode = $request->input('teacher.qualification.code');
        $sourceCode = $request->input('teacher.source.code');
        $jobTitleId = $request->input('teacher.jobTitle.id');
        $subjectId = $request->input('teacher.subject.id');
        $politicsId = $request->input('teacher.politics.id');
        $nationalityId = $request->input('teacher.nationality.id');

        $params = [
            'jwt' => $jwt,
            'departmentId' => $departmentId,
            'jobNumber' => $jobNumber,
            'name' => $name,
            'genderCode' => $genderCode,
            'birthDate' => $birthDate,
            'intoSchoolDate' => $intoSchoolDate,
            'officeHoldingCode' => $officeHoldingCode,
            'educationBackgroundCode' => $educationBackgroundCode,
            'qualificationCode' => $qualificationCode,
            'sourceCode' => $sourceCode,
            'jobTitleId' => $jobTitleId,
            'subjectId' => $subjectId,
            'politicsId' => $politicsId,
            'nationalityId' => $nationalityId,
        ];
        $json = self::checkCreateParam($params);
        if ($json != null) {
            return $json;
        }
        // step1 接收参数 校验 end

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
        $teacherBiz = new Teacher();
        $code = $teacherBiz->create($departmentId, $jobNumber, $name, $genderCode, $birthDate, $intoSchoolDate,
            $officeHoldingCode, $educationBackgroundCode, $qualificationCode, $sourceCode, $jobTitleId, $subjectId,
            $politicsId, $nationalityId);

        if ($code == Resp::DEPARTMENT_NOT_EXIST) {
            $json = $resp->departmentNotExist([]);
            return $json;
        }

        if ($code == Resp::DEPARTMENT_HAS_BEEN_DELETE) {
            $json = $resp->departmentHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::OFFICE_HOLDING_STATUS_NOT_EXIST) {
            $json = $resp->officeHoldingNotExist([]);
            return $json;
        }

        if ($code == Resp::EDUCATION_BACKGROUND_NOT_EXIST) {
            $json = $resp->educationBackgroundNotExist([]);
            return $json;
        }

        if ($code == Resp::QUALIFICATION_NOT_EXIST) {
            $json = $resp->qualificationNotExist([]);
            return $json;
        }

        if ($code == Resp::SOURCE_NOT_EXIST) {
            $json = $resp->sourceNotExist([]);
            return $json;
        }

        if ($code == Resp::JOB_TITLE_NOT_EXIST) {
            $json = $resp->jobTitleNotExist([]);
            return $json;
        }

        if ($code == Resp::JOB_TITLE_HAS_BEEN_DELETE) {
            $json = $resp->jobTitleHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::SUBJECT_NOT_EXIST) {
            $json = $resp->subjectNotExist([]);
            return $json;
        }

        if ($code == Resp::SUBJECT_HAS_BEEN_DELETE) {
            $json = $resp->subjectHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::POLITICS_NOT_EXIST) {
            $json = $resp->politicsNotExist([]);
            return $json;
        }

        if ($code == Resp::POLITICS_HAS_BEEN_DELETE) {
            $json = $resp->politicsHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::NATIONALITY_NOT_EXIST) {
            $json = $resp->nationalityNotExist([]);
            return $json;
        }

        if ($code == Resp::NATIONALITY_HAS_BEEN_DELETE) {
            $json = $resp->nationalityHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logCreateTeacher();
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
     * name: 必须存在
     * birthDate: 必须为一个表示日期的字符串
     * intoSchoolDate: 必须为一个表示日期的字符串
     * @access private
     * @author Roach<18410269837@163.com>
     * @param array $params 参数列表
     * @return string|null 参数合规则返回空 否则返回一个标识参数不合规原因的JSON
     */
    private function checkCreateParam($params) {
        $rules = [
            'jwt' => 'required|string',
            'departmentId' => 'required|int|min:1',
            'jobNumber' => 'required|string',
            'name' => 'required|string',
            'genderCode' => 'required|int|min:0|max:1',
            'birthDate' => 'required|date|date_format:Y-m-d',
            'intoSchoolDate' => 'required|date|date_format:Y-m-d',
            'officeHoldingCode' => 'required|int',
            'educationBackgroundCode' => 'required|int',
            'qualificationCode' => 'required|int',
            'sourceCode' => 'required|int',
            'jobTitleId' => 'required|int|min:1',
            'subjectId' => 'required|int|min:1',
            'politicsId' => 'required|int|min:1',
            'nationalityId' => 'required|int|min:1',
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'departmentId.required' => '院系id不能为空',
            'departmentId.int' => '院系id必须为整型',
            'departmentId.min' => '院系id字段值不能小于1',
            'jobNumber.required' => '教职工工号不能为空',
            'jobNumber.string' => '教职工工号必须为字符串',
            'name.required' => '教职工姓名不能为空',
            'name.string' => '教职工姓名必须为字符串',
            'genderCode.required' => '教职工性别编码不能为空',
            'genderCode.int' => '教职工性别编码必须为整型',
            'genderCode.min' => '教职工性别编码必须大于等于0',
            'genderCode.max' => '教职工性别编码必须小于等于1',
            'birthDate.required' => '教职工出生日期不能为空',
            'birthDate.date' => '教职工出生日期不是有效日期',
            'birthDate.date_format' => '教职工出生日期必须为年-月-日格式字符串',
            'intoSchoolDate.required' => '教职工入校日期不能为空',
            'intoSchoolDate.date' => '教职工入校日期不是有效日期',
            'intoSchoolDate.date_format' => '教职工入校日期必须为年-月-日格式字符串',
            'officeHoldingCode.required' => '任职状态编码不能为空',
            'officeHoldingCode.int' => '任职状态编码必须为整型',
            'educationBackgroundCode.required' => '学历编码不能为空',
            'educationBackgroundCode.int' => '学历编码必须为整型',
            'qualificationCode.required' => '学位编码不能为空',
            'qualificationCode.int' => '学位编码必须为整型',
            'sourceCode.required' => '学缘编码不能为空',
            'sourceCode.int' => '学缘编码必须为整型',
            'jobTitleId.required' => '专业技术职称id不能为空',
            'jobTitleId.int' => '专业技术职称id必须为整型',
            'jobTitleId.min' => '专业技术职称id字段值不能小于1',
            'subjectId.required' => '学科类别id不能为空',
            'subjectId.int' => '学科类别id必须为整型',
            'subjectId.min' => '学科类别id字段值不能小于1',
            'politicsId.required' => '政治面貌id不能为空',
            'politicsId.int' => '政治面貌id必须为整型',
            'politicsId.min' => '政治面貌id字段值不能小于1',
            'nationalityId.required' => '国籍id不能为空',
            'nationalityId.int' => '国籍id必须为整型',
            'nationalityId.min' => '国籍id字段值不能小于1',
        ];
        $lib = new Lib();
        $resp = new Resp();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
            return $json;
        }
        return null;
    }

    /**
     * 本方法用于以列表形式查看教师信息
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
        $teacherBiz = new Teacher();
        $result = $teacherBiz->list($currentPage, $itemPerPage);
        // step3. 处理逻辑 end

        // step4. 封装返回值结构 start
        $data = [
            'user' => [
                'role' => $userBiz->role->name
            ],
            'pagination' => $result['pagination'],
            'teachers' => []
        ];

        for ($i = 0; $i <= count($result['teachers']) - 1; $i++) {
            $teacher = $result['teachers'][$i];
            $data['teachers'][$i] = [
                'id' => $teacher->id,
                'department' => [
                    'id' => $teacher->department->id,
                    'name' => $teacher->department->name,
                ],
                'jobNumber' => $teacher->jobNumber,
                'name' => $teacher->name,
                'gender' => [
                    'code' => $teacher->gender,
                    'display' => self::confirmGender($teacher->gender)
                ],
                'birthDate' => $teacher->birthDate,
                'intoSchoolDate' => $teacher->intoSchoolDate,
                'officeHolding' => [
                    'code' => $teacher->officeHoldingStatus,
                    'display' => self::confirmOfficeHolding($teacher->officeHoldingStatus)
                ],
                'educationBackground' => [
                    'code' => $teacher->educationBackground,
                    'display' => self::confirmEducationBackground($teacher->educationBackground)
                ],
                'qualification' => [
                    'code' => $teacher->qualification,
                    'display' => self::confirmQualification($teacher->qualification)
                ],
                'source' => [
                    'code' => $teacher->source,
                    'display' => self::confirmSource($teacher->source)
                ],
                'jobTitle' => [
                    'id' => $teacher->jobTitle->id,
                    'name' => $teacher->jobTitle->name,
                ],
                'subject' => [
                    'id' => $teacher->subject->id,
                    'name' => $teacher->subject->name,
                ],
                'politics' => [
                    'id' => $teacher->politics->id,
                    'name' => $teacher->politics->name,
                ],
                'nationality' => [
                    'id' => $teacher->nationality->id,
                    'name' => $teacher->nationality->name,
                ],
                'createdTime' => $teacher->createdTime,
                'updatedTime' => $teacher->updatedTime,
            ];
        }
        // step4. 封装返回值结构 end

        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于根据性别编码确认性别
    */
    private function confirmGender($genderCode) {
        foreach (Teacher::GENDER as $gender => $code) {
            if ($genderCode == $code) {
                return $gender;
            }
        }
        return null;
    }

    private function confirmOfficeHolding($officeHoldingStatus) {
        foreach (Teacher::OFFICE_HOLDING_STATUS as $officeHolding) {
            if ($officeHolding['code'] == $officeHoldingStatus) {
                return $officeHolding['display'];
            }
        }
        return null;
    }

    private function confirmEducationBackground($educationBackgroundCode) {
        foreach (Teacher::EDUCATION_BACKGROUNDS as $educationBackground) {
            if ($educationBackground['code'] == $educationBackgroundCode) {
                return $educationBackground['display'];
            }
        }
        return null;
    }

    private function confirmQualification($qualificationCode) {
        foreach (Teacher::QUALIFICATIONS as $qualification) {
            if ($qualification['code'] == $qualificationCode) {
                return $qualification['display'];
            }
        }
        return null;
    }

    private function confirmSource($sourceCode) {
        foreach (Teacher::SOURCES as $source) {
            if ($source['code'] == $sourceCode) {
                return $source['display'];
            }
        }
        return null;
    }

    /**
     * 本方法用于修改教职工操作
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Request $request 请求组件
     * 实际参数为:
     * user.jwt string 用户jwt(必填)
     * teacher.id 教职工id(必填)
     * teacher.department.id int 教职工所属院系ID(必填)
     * teacher.jobNumber string 教职工工号(必填)
     * teacher.name string 教职工姓名(必填)
     * teacher.gender.code int 教职工性别编码(必填) 性别字典:App\Biz\Teacher::GENDER
     * teacher.birthDate string 教职工出生日期(必填)
     * teacher.intoSchoolDate string 教职工入校时间(必填)
     * teacher.officeHolding.code int 教职工任职状态编码 任职状态字典:App\Biz\Teacher::OFFICE_HOLDING_STATUS
     * teacher.educationBackground.code int 教职工学历编码 学历字典:App\Biz\Teacher::EDUCATION_BACKGROUNDS
     * teacher.qualification.code int 教职工学位编码 学位字典:App\Biz\Teacher::QUALIFICATIONS
     * teacher.source.code int 教职工学缘编码 学缘字典:App\Biz\Teacher::SOURCES
     * teacher.jobTitle.id int 专业技术职称id
     * teacher.subject.id int 学科类别id
     * teacher.politics.id 政治面貌id
     * teacher.nationality.id 国籍id
     * @return string $json 返回至前端的json
     */
    public function update(Request $request) {
        // step1. 接受参数并校验 start
        $jwt = $request->input('user.jwt');
        $teacherId = $request->input('teacher.id');
        $departmentId = $request->input('teacher.department.id');
        $jobNumber = $request->input('teacher.jobNumber');
        $name = $request->input('teacher.name');
        $genderCode = $request->input('teacher.gender.code');
        $birthDate = $request->input('teacher.birthDate');
        $intoSchoolDate = $request->input('teacher.intoSchoolDate');
        $officeHoldingCode = $request->input('teacher.officeHolding.code');
        $educationBackgroundCode = $request->input('teacher.educationBackground.code');
        $qualificationCode = $request->input('teacher.qualification.code');
        $sourceCode = $request->input('teacher.source.code');
        $jobTitleId = $request->input('teacher.jobTitle.id');
        $subjectId = $request->input('teacher.subject.id');
        $politicsId = $request->input('teacher.politics.id');
        $nationalityId = $request->input('teacher.nationality.id');

        $params = [
            'jwt' => $jwt,
            'teacherId' => $teacherId,
            'departmentId' => $departmentId,
            'jobNumber' => $jobNumber,
            'name' => $name,
            'genderCode' => $genderCode,
            'birthDate' => $birthDate,
            'intoSchoolDate' => $intoSchoolDate,
            'officeHoldingCode' => $officeHoldingCode,
            'educationBackgroundCode' => $educationBackgroundCode,
            'qualificationCode' => $qualificationCode,
            'sourceCode' => $sourceCode,
            'jobTitleId' => $jobTitleId,
            'subjectId' => $subjectId,
            'politicsId' => $politicsId,
            'nationalityId' => $nationalityId,
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

        if ($userBiz->role->name != 'super_admin') {
            $json = $resp->permissionDeny([]);
            return $json;
        }
        // step2. 鉴权 end

        // step3. 处理逻辑 start
        $teacherBiz = new Teacher();
        $code = $teacherBiz->update($teacherId, $departmentId, $jobNumber, $name, $genderCode, $birthDate, $intoSchoolDate,
            $officeHoldingCode, $educationBackgroundCode, $qualificationCode, $sourceCode, $jobTitleId, $subjectId,
            $politicsId, $nationalityId);

        if ($code == Resp::TEACHER_NOT_EXIST) {
            $json = $resp->teacherNotExist([]);
            return $json;
        }

        if ($code == Resp::TEACHER_HAS_BEEN_DELETE) {
            $json = $resp->teacherHasBeenDeleted([]);
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

        if ($code == Resp::OFFICE_HOLDING_STATUS_NOT_EXIST) {
            $json = $resp->officeHoldingNotExist([]);
            return $json;
        }

        if ($code == Resp::EDUCATION_BACKGROUND_NOT_EXIST) {
            $json = $resp->educationBackgroundNotExist([]);
            return $json;
        }

        if ($code == Resp::QUALIFICATION_NOT_EXIST) {
            $json = $resp->qualificationNotExist([]);
            return $json;
        }

        if ($code == Resp::SOURCE_NOT_EXIST) {
            $json = $resp->sourceNotExist([]);
            return $json;
        }

        if ($code == Resp::JOB_TITLE_NOT_EXIST) {
            $json = $resp->jobTitleNotExist([]);
            return $json;
        }

        if ($code == Resp::JOB_TITLE_HAS_BEEN_DELETE) {
            $json = $resp->jobTitleHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::SUBJECT_NOT_EXIST) {
            $json = $resp->subjectNotExist([]);
            return $json;
        }

        if ($code == Resp::SUBJECT_HAS_BEEN_DELETE) {
            $json = $resp->subjectHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::POLITICS_NOT_EXIST) {
            $json = $resp->politicsNotExist([]);
            return $json;
        }

        if ($code == Resp::POLITICS_HAS_BEEN_DELETE) {
            $json = $resp->politicsHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::NATIONALITY_NOT_EXIST) {
            $json = $resp->nationalityNotExist([]);
            return $json;
        }

        if ($code == Resp::NATIONALITY_HAS_BEEN_DELETE) {
            $json = $resp->nationalityHasBeenDeleted([]);
            return $json;
        }

        if ($code == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logUpdateTeacher();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end

        $data = [
            'user' => [
                'role' => $userBiz->role->name,
            ],
            'teacher' => [
                'id' => $teacherBiz->id,
                'department' => [
                    'id' => $teacherBiz->department->id,
                    'name' => $teacherBiz->department->name,
                ],
                'jobNumber' => $teacherBiz->jobNumber,
                'name' => $teacherBiz->name,
                'gender' => [
                    'code' => $teacherBiz->gender,
                    'display' => self::confirmGender($teacherBiz->gender)
                ],
                'birthDate' => $teacherBiz->birthDate,
                'intoSchoolDate' => $teacherBiz->intoSchoolDate,
                'officeHolding' => [
                    'code' => $teacherBiz->officeHoldingStatus,
                    'display' => self::confirmOfficeHolding($teacherBiz->officeHoldingStatus)
                ],
                'educationBackground' => [
                    'code' => $teacherBiz->educationBackground,
                    'display' => self::confirmEducationBackground($teacherBiz->educationBackground)
                ],
                'qualification' => [
                    'code' => $teacherBiz->qualification,
                    'display' => self::confirmQualification($teacherBiz->qualification)
                ],
                'source' => [
                    'code' => $teacherBiz->source,
                    'display' => self::confirmSource($teacherBiz->source)
                ],
                'jobTitle' => [
                    'id' => $teacherBiz->jobTitle->id,
                    'name' => $teacherBiz->jobTitle->name,
                ],
                'subject' => [
                    'id' => $teacherBiz->subject->id,
                    'name' => $teacherBiz->subject->name,
                ],
                'politics' => [
                    'id' => $teacherBiz->politics->id,
                    'name' => $teacherBiz->politics->name,
                ],
                'nationality' => [
                    'id' => $teacherBiz->nationality->id,
                    'name' => $teacherBiz->nationality->name,
                ],
                'createdTime' => $teacherBiz->createdTime,
                'updatedTime' => $teacherBiz->updatedTime,
            ]
        ];

        $json = $resp->success($data);
        return $json;
        // step5. 封装返回值结构 end
    }

    /**
     * 本方法用于为update方法校验参数
     * 规则:
     * name: 必须存在
     * birthDate: 必须为一个表示日期的字符串
     * intoSchoolDate: 必须为一个表示日期的字符串
     * @access private
     * @author Roach<18410269837@163.com>
     * @param array $params 参数列表
     * @return string|null 参数合规则返回空 否则返回一个标识参数不合规原因的JSON
     */
    private function checkUpdateParam($params) {
        $rules = [
            'jwt' => 'required|string',
            'teacherId' => 'required|int|min:1',
            'departmentId' => 'required|int|min:1',
            'jobNumber' => 'required|string',
            'name' => 'required|string',
            'genderCode' => 'required|int|min:0|max:1',
            'birthDate' => 'required|date|date_format:Y-m-d',
            'intoSchoolDate' => 'required|date|date_format:Y-m-d',
            'officeHoldingCode' => 'required|int',
            'educationBackgroundCode' => 'required|int',
            'qualificationCode' => 'required|int',
            'sourceCode' => 'required|int',
            'jobTitleId' => 'required|int|min:1',
            'subjectId' => 'required|int|min:1',
            'politicsId' => 'required|int|min:1',
            'nationalityId' => 'required|int|min:1',
        ];

        $exceptionMessages = [
            'jwt.required' => 'jwt不能为空',
            'jwt.string' => 'jwt内容必须为字符串',
            'teacherId.required' => '教职工id不能为空',
            'teacherId.int' => '教职工id必须为整型',
            'teacherId.min' => '教职工id字段值不能小于1',
            'departmentId.required' => '院系id不能为空',
            'departmentId.int' => '院系id必须为整型',
            'departmentId.min' => '院系id字段值不能小于1',
            'jobNumber.required' => '教职工工号不能为空',
            'jobNumber.string' => '教职工工号必须为字符串',
            'name.required' => '教职工姓名不能为空',
            'name.string' => '教职工姓名必须为字符串',
            'genderCode.required' => '教职工性别编码不能为空',
            'genderCode.int' => '教职工性别编码必须为整型',
            'genderCode.min' => '教职工性别编码必须大于等于0',
            'genderCode.max' => '教职工性别编码必须小于等于1',
            'birthDate.required' => '教职工出生日期不能为空',
            'birthDate.date' => '教职工出生日期不是有效日期',
            'birthDate.date_format' => '教职工出生日期必须为年-月-日格式字符串',
            'intoSchoolDate.required' => '教职工入校日期不能为空',
            'intoSchoolDate.date' => '教职工入校日期不是有效日期',
            'intoSchoolDate.date_format' => '教职工入校日期必须为年-月-日格式字符串',
            'officeHoldingCode.required' => '任职状态编码不能为空',
            'officeHoldingCode.int' => '任职状态编码必须为整型',
            'educationBackgroundCode.required' => '学历编码不能为空',
            'educationBackgroundCode.int' => '学历编码必须为整型',
            'qualificationCode.required' => '学位编码不能为空',
            'qualificationCode.int' => '学位编码必须为整型',
            'sourceCode.required' => '学缘编码不能为空',
            'sourceCode.int' => '学缘编码必须为整型',
            'jobTitleId.required' => '专业技术职称id不能为空',
            'jobTitleId.int' => '专业技术职称id必须为整型',
            'jobTitleId.min' => '专业技术职称id字段值不能小于1',
            'subjectId.required' => '学科类别id不能为空',
            'subjectId.int' => '学科类别id必须为整型',
            'subjectId.min' => '学科类别id字段值不能小于1',
            'politicsId.required' => '政治面貌id不能为空',
            'politicsId.int' => '政治面貌id必须为整型',
            'politicsId.min' => '政治面貌id字段值不能小于1',
            'nationalityId.required' => '国籍id不能为空',
            'nationalityId.int' => '国籍id必须为整型',
            'nationalityId.min' => '国籍id字段值不能小于1',
        ];
        $lib = new Lib();
        $resp = new Resp();
        $errors = $lib->validate($params, $rules, $exceptionMessages);
        if ($errors != null) {
            $json = $resp->paramInvalid($errors[0], []);
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
     * teacher.id int 被删除的教职工信息id
     * @return string $json 返回至前端的JSON
     */
    public function delete(Request $request) {
        // step1. 接收参数并校验 start
        $jwt = $request->input('user.jwt');
        $id = $request->input('teacher.id');

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
            'id.required' => '教职工id不能为空',
            'id.int' => '教职工id必须为整型',
            'id.min' => '教职工id字段值不能小于1',
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
        $teacherBiz = new Teacher();
        $code = $teacherBiz->delete($id);
        if ($code == Resp::TEACHER_NOT_EXIST) {
            $json = $resp->teacherNotExist([]);
            return $json;
        }

        if ($code == Resp::TEACHER_HAS_BEEN_DELETE) {
            $json = $resp->teacherHasBeenDeleted([]);
            return $json;
        }
        if ($code == Resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step3. 处理逻辑 end

        // step4. 记录日志 start
        $logger = new Logger($request->getClientIp(), $userBiz, '');
        $code = $logger->logDeleteTeacher();
        if ($code == $resp::SAVE_DATABASE_FAILED) {
            $json = $resp->DBFailed([]);
            return $json;
        }
        // step4. 记录日志 end

        $json = $resp->success([]);
        return $json;
    }
}
