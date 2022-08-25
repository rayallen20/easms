<?php
namespace App\Biz;

use App\Lib\Pagination;
use App\Lib\Resp;

class Teacher {
    /**
     * @const array GENDER 教职工性别字典
    */
    const GENDER = [
        'female' => 0,
        'male' => 1,
    ];

    /**
     * @const array OFFICE_HOLDING_STATUS 教职工任职状态字典
    */
    const OFFICE_HOLDING_STATUS = [
        [
            'code' => 10,
            'display' => '本校',
        ],

        [
            'code' => 20,
            'display' => '外校(境内)',
        ],

        [
            'code' => 30,
            'display' => '外校(境外)',
        ],
    ];

    /**
     * @const array EDUCATION_BACKGROUNDS 教职工学历字典
    */
    const EDUCATION_BACKGROUNDS = [
        [
            'code' => 10,
            'display' => '小学',
        ],

        [
            'code' => 20,
            'display' => '初中',
        ],

        [
            'code' => 30,
            'display' => '高中',
        ],

        [
            'code' => 40,
            'display' => '专科',
        ],

        [
            'code' => 50,
            'display' => '本科',
        ],

        [
            'code' => 60,
            'display' => '研究生',
        ],
    ];

    /**
     * @const array QUALIFICATIONS 教职工学位字典
    */
    const QUALIFICATIONS = [
        [
            'code' => 0,
            'display' => '无'
        ],

        [
            'code' => 10,
            'display' => '学士'
        ],

        [
            'code' => 20,
            'display' => '硕士'
        ],

        [
            'code' => 30,
            'display' => '博士'
        ],
    ];

    /**
     * @const array SOURCES 教职工学缘字典
     */
    const SOURCES = [
        [
            'code' => 10,
            'display' => '本校',
        ],

        [
            'code' => 20,
            'display' => '外校(境内)',
        ],

        [
            'code' => 30,
            'display' => '外校(境外)',
        ],
    ];

    /**
     * @var int $id 教职工id
    */
    public $id;

    /**
     * @var Department $department 教职工所属院系
    */
    public $department;

    /**
     * @var string $jobNumber 教职工工号
    */
    public $jobNumber;

    /**
     * @var string $name 教职工姓名
    */
    public $name;

    /**
     * @var int $gender 教职工性别
    */
    public $gender;

    /**
     * @var string $birthDate 教职工出生日期
    */
    public $birthDate;

    /**
     * @var string $intoSchoolDate 教职工入校时间
    */
    public $intoSchoolDate;

    /**
     * @var int $officeHoldingStatus 教职工任职状态
    */
    public $officeHoldingStatus;

    /**
     * @var int $educationBackground 教职工学历
    */
    public $educationBackground;

    /**
     * @var int $qualification 教职工学位
    */
    public $qualification;

    /**
     * @var int $source 教职工学缘
    */
    public $source;

    /**
     * @var JobTitle $jobTitle 教职工专业技术职称
    */
    public $jobTitle;

    /**
     * @var Subject $subject 教职工学科类别
    */
    public $subject;

    /**
     * @var Politics $politics 教职工政治面貌
    */
    public $politics;

    /**
     * @var Nationality $nationality 教职工国籍
    */
    public $nationality;

    /**
     * @var string $createdTime 教职工创建时间
    */
    public $createdTime;

    /**
     * @var string $updatedTime 教职工修改时间
    */
    public $updatedTime;

    /**
     * 本方法用于创建教职工
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $departmentId 院系id
     * @param string $jobNumber 教职工工号
     * @param string $name 教职工姓名
     * @param int $genderCode 教职工性别编码
     * @param string $birthDate 教职工出生日期
     * @param string $intoSchoolDate 教职工入校日期
     * @param int $officeHoldingCode 教职工任职状态编码
     * @param int $educationBackgroundCode 教职工学历编码
     * @param int $qualificationCode 教职工学历编码
     * @param int $sourceCode 教职工学缘编码
     * @param int $jobTitleId 专业技术职称id
     * @param int $subjectId 学科id
     * @param int $politicsId 政治面貌id
     * @param int $nationalityId 国籍id
     * @return int $code 操作状态码 0表示成功 操作失败则返回对应失败原因的状态码
     */
    public function create($departmentId, $jobNumber, $name, $genderCode, $birthDate, $intoSchoolDate,
                           $officeHoldingCode, $educationBackgroundCode, $qualificationCode, $sourceCode,
                           $jobTitleId, $subjectId, $politicsId, $nationalityId){
        // 确认院系id是否存在
        $departmentBiz = new Department();
        $code = $departmentBiz->exist($departmentId);
        if ($code != 0) {
            return $code;
        }
        $this->department = $departmentBiz;

        // 确认任职状态编码是否存在
        if (!self::existOfficeHolding($officeHoldingCode)) {
            $code = Resp::OFFICE_HOLDING_STATUS_NOT_EXIST;
            return $code;
        }
        $this->officeHoldingStatus = $officeHoldingCode;

        // 确认学历编码是否存在
        if (!self::existEducationBackground($educationBackgroundCode)) {
            $code = Resp::EDUCATION_BACKGROUND_NOT_EXIST;
            return $code;
        }
        $this->educationBackground = $educationBackgroundCode;

        // 确认学位编码是否存在
        if (!self::existQualification($qualificationCode)) {
            $code = Resp::QUALIFICATION_NOT_EXIST;
            return $code;
        }
        $this->qualification = $qualificationCode;

        // 确认学缘编码是否存在
        if (!self::existSource($sourceCode)) {
            $code = Resp::SOURCE_NOT_EXIST;
            return $code;
        }
        $this->source = $sourceCode;

        // 确认专业技术职称是否存在
        $jobTitle = new JobTitle();
        $code = $jobTitle->exist($jobTitleId);
        if ($code != 0) {
            return $code;
        }
        $this->jobTitle = $jobTitle;

        // 确认学科是否存在
        $subject = new Subject();
        $code = $subject->exist($subjectId);
        if ($code != 0) {
            return $code;
        }
        $this->subject = $subject;

        // 确认政治面貌是否存在
        $politics = new Politics();
        $code = $politics->exist($politicsId);
        if ($code != 0) {
            return $code;
        }
        $this->politics = $politics;

        // 确认国籍是否存在
        $nationality = new Nationality();
        $code = $nationality->exist($nationalityId);
        if ($code != 0) {
            return $code;
        }
        $this->nationality = $nationality;

        $this->jobNumber = $jobNumber;
        $this->name = $name;
        $this->gender = $genderCode;
        $this->birthDate = $birthDate;
        $this->intoSchoolDate = $intoSchoolDate;
        $model = new \App\Http\Models\Teacher();
        $result = $model->create($this);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于确定指定的任职状态编码是否存在对应的任职状态信息
     * @access private
     * @author Roach<18410269837@163.com>
     * @param int $officeHoldingCode 任职状态编码
     * @return bool true表示存在任职状态信息 false表示不存在
    */
    private function existOfficeHolding($officeHoldingCode) {
        foreach (self::OFFICE_HOLDING_STATUS as $officeHolding) {
            if ($officeHolding['code'] == $officeHoldingCode) {
                return true;
            }
        }
        return false;
    }

    /**
     * 本方法用于确定指定的学历编码是否存在对应的学历信息
     * @access private
     * @author Roach<18410269837@163.com>
     * @param int $educationBackgroundCode 学历编码
     * @return bool true表示存在学历信息 false表示不存在
     */
    private function existEducationBackground($educationBackgroundCode) {
        foreach (self::EDUCATION_BACKGROUNDS as $educationBackground) {
            if ($educationBackground['code'] == $educationBackgroundCode) {
                return true;
            }
        }
        return false;
    }

    /**
     * 本方法用于确定指定的学位编码是否存在对应的学位信息
     * @access private
     * @author Roach<18410269837@163.com>
     * @param int $qualificationCode 学位编码
     * @return bool true表示存在学位信息 false表示不存在
     */
    private function existQualification($qualificationCode) {
        foreach (self::QUALIFICATIONS as $qualification) {
            if ($qualification['code'] == $qualificationCode) {
                return true;
            }
        }
        return false;
    }

    /**
     * 本方法用于确定指定的学缘编码是否存在对应的学缘信息
     * @access private
     * @author Roach<18410269837@163.com>
     * @param int $sourceCode 学缘编码
     * @return bool true表示存在学缘信息 false表示不存在
     */
    private function existSource($sourceCode) {
        foreach (self::SOURCES as $source) {
            if ($source['code'] == $sourceCode) {
                return true;
            }
        }
        return false;
    }

    /**
     * 本方法用于列表展示教职工信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $currentPage 当前页数
     * @param int $itemPerPage 每页显示信息条数
     * @return array $result 本数组共3项内容:
     * array<Teacher> $result['teachers']:教职工信息集合
     * App\Lib\Pagination $result['pagination']:分页器对象
     * int $result['code']:错误码
     */
    public function list($currentPage, $itemPerPage) {
        $result = [
            'teachers' => [],
            'pagination' => null,
            'code' => 0
        ];

        $pagination = new Pagination($currentPage, $itemPerPage);
        $offset = $pagination->calcOffset();

        $model = new \App\Http\Models\Teacher();
        $teacherCollection = $model->findNormalTeachers($offset, $itemPerPage);
        for ($i = 0; $i <= count($teacherCollection) - 1; $i++) {
            $teacherOrm = $teacherCollection[$i];
            $teacher = new Teacher();
            $teacher->fill($teacherOrm);
            $result['teachers'][$i] = $teacher;
        }

        $totalTeacherNum = $model->countNormalTeachers();
        $pagination->calcTotalPage($totalTeacherNum);
        $result['pagination'] = $pagination;
        return $result;
    }

    /**
     * 本方法用于根据Teacher表的ORM填充Biz层的Teacher对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\Teacher $model 院系信息ORM
     * @return void
     */
    public function fill($model) {
        $this->id = $model->id;
        $this->department = new Department();
        $this->department->fill($model->department);
        $this->jobNumber = $model->job_number;
        $this->name = $model->name;
        $this->gender = $model->gender;
        $this->birthDate = $model->birth_date;
        $this->intoSchoolDate = $model->into_school_date;
        $this->officeHoldingStatus = $model->office_holding_code;
        $this->educationBackground = $model->education_background_code;
        $this->qualification = $model->qualification_code;
        $this->source = $model->source_code;
        $this->jobTitle = new JobTitle();
        $this->jobTitle->fill($model->jobTitle);
        $this->subject = new Subject();
        $this->subject->fill($model->subject);
        $this->politics = new Politics();
        $this->politics->fill($model->politics);
        $this->nationality = new Nationality();
        $this->nationality->fill($model->nationality);
        $this->createdTime = explode('.', $model->created_time)[0];
        $this->updatedTime = explode('.', $model->updated_time)[0];
    }

    /**
     * 本方法用于更新教职工信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 教职工id
     * @param int $departmentId 院系id
     * @param string $jobNumber 教职工工号
     * @param string $name 教职工姓名
     * @param int $genderCode 教职工性别编码
     * @param string $birthDate 教职工出生日期
     * @param string $intoSchoolDate 教职工入校日期
     * @param int $officeHoldingCode 教职工任职状态编码
     * @param int $educationBackgroundCode 教职工学历编码
     * @param int $qualificationCode 教职工学历编码
     * @param int $sourceCode 教职工学缘编码
     * @param int $jobTitleId 专业技术职称id
     * @param int $subjectId 学科id
     * @param int $politicsId 政治面貌id
     * @param int $nationalityId 国籍id
     * @return int $code 操作状态码 0表示成功 操作失败则返回对应失败原因的状态码
     */
    public function update($id, $departmentId, $jobNumber, $name, $genderCode, $birthDate, $intoSchoolDate,
                           $officeHoldingCode, $educationBackgroundCode, $qualificationCode, $sourceCode,
                           $jobTitleId, $subjectId, $politicsId, $nationalityId){
        $code = 0;
        $model = new \App\Http\Models\Teacher();
        $teacherOrm = $model->findById($id);
        if ($teacherOrm == null) {
            $code = Resp::TEACHER_NOT_EXIST;
            return $code;
        }

        if ($teacherOrm->status == \App\Http\Models\Teacher::STATUS['delete']) {
            $code = Resp::TEACHER_HAS_BEEN_DELETE;
            return $code;
        }

        // 确认院系id是否存在
        $departmentBiz = new Department();
        $code = $departmentBiz->exist($departmentId);
        if ($code != 0) {
            return $code;
        }
        $this->department = $departmentBiz;

        // 确认任职状态编码是否存在
        if (!self::existOfficeHolding($officeHoldingCode)) {
            $code = Resp::OFFICE_HOLDING_STATUS_NOT_EXIST;
            return $code;
        }
        $this->officeHoldingStatus = $officeHoldingCode;

        // 确认学历编码是否存在
        if (!self::existEducationBackground($educationBackgroundCode)) {
            $code = Resp::EDUCATION_BACKGROUND_NOT_EXIST;
            return $code;
        }
        $this->educationBackground = $educationBackgroundCode;

        // 确认学位编码是否存在
        if (!self::existQualification($qualificationCode)) {
            $code = Resp::QUALIFICATION_NOT_EXIST;
            return $code;
        }
        $this->qualification = $qualificationCode;

        // 确认学缘编码是否存在
        if (!self::existSource($sourceCode)) {
            $code = Resp::SOURCE_NOT_EXIST;
            return $code;
        }
        $this->source = $sourceCode;

        // 确认专业技术职称是否存在
        $jobTitle = new JobTitle();
        $code = $jobTitle->exist($jobTitleId);
        if ($code != 0) {
            return $code;
        }
        $this->jobTitle = $jobTitle;

        // 确认学科是否存在
        $subject = new Subject();
        $code = $subject->exist($subjectId);
        if ($code != 0) {
            return $code;
        }
        $this->subject = $subject;

        // 确认政治面貌是否存在
        $politics = new Politics();
        $code = $politics->exist($politicsId);
        if ($code != 0) {
            return $code;
        }
        $this->politics = $politics;

        // 确认国籍是否存在
        $nationality = new Nationality();
        $code = $nationality->exist($nationalityId);
        if ($code != 0) {
            return $code;
        }
        $this->nationality = $nationality;

        $this->jobNumber = $jobNumber;
        $this->name = $name;
        $this->gender = $genderCode;
        $this->birthDate = $birthDate;
        $this->intoSchoolDate = $intoSchoolDate;

        $result = $model->updateTeacher($teacherOrm, $this);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }

        $this->fill($teacherOrm);
        return $code;
    }

    /**
     * 本方法用于删除教职工
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 待删除院系的院系id
     * @return int $code 错误码 若操作无错误则返回0
     */
    public function delete($id) {
        $code = 0;
        $model = new \App\Http\Models\Teacher();
        $teacherOrm = $model->findById($id);
        if ($teacherOrm == null) {
            $code = Resp::TEACHER_NOT_EXIST;
            return $code;
        }

        if ($teacherOrm->status == \App\Http\Models\Teacher::STATUS['delete']) {
            $code = Resp::TEACHER_HAS_BEEN_DELETE;
            return $code;
        }

        $result = $model->updateStatus($teacherOrm, \App\Http\Models\Teacher::STATUS['delete']);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }
}
