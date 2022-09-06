<?php
namespace App\Lib;

class Resp {
    /**
     * @const int SUCCESS 本状态码表示响应成功
    */
    const SUCCESS = 200;

    /**
     * @const int PARAM_INVALID 本状态码表示参数无效
     */
    const PARAM_INVALID = 10001;

    /**
     * @const int ACCOUNT_NOT_EXIST 本状态码表示用户账户不存在
    */
    const ACCOUNT_NOT_EXIST = 10002;

    /**
     * @const int INCORRECT_PASSWORD 本状态码表示登录密码错误
    */
    const INCORRECT_PASSWORD = 10003;

    /**
     * @const int SAVE_DATABASE_FAILED 本状态码表示更新数据库错误
    */
    const SAVE_DATABASE_FAILED = 10004;

    /**
     * @const int PARSE_TOKEN_FAILED 本状态码表示解析jwt至数组失败
    */
    const PARSE_JWT_FAILED = 10005;

    /**
     * @const int TOKEN_INVALID 本状态码表示无法根据token的解析结果查询出一个用户信息
    */
    const JWT_INVALID = 10006;

    /**
     * @const int PERMISSION_DENY 本状态码表示用户无权限进行当前操作
    */
    const PERMISSION_DENY = 10007;

    /**
     * @const int ACCOUNT_EXISTED 本状态码表示待创建用户的账号已存在
    */
    const ACCOUNT_EXISTED = 10008;

    /**
     * @const int ROLE_NOT_EXIST 本状态码表示角色不存在
     */
    const ROLE_NOT_EXIST = 10009;

    /**
     * @const int ONLY_UPDATE_SELF_INFO 本状态码表示在更新用户信息时 用户的id与jwt中保存的id信息不符
    */
    const ONLY_UPDATE_SELF_INFO = 10010;

    /**
     * @const int CAN_NOT_DELETE_SELF 本状态码表示在删除用户时 当前用户不得删除自己
    */
    const CAN_NOT_DELETE_SELF = 10011;

    /**
     * @const int USER_HAS_BEEN_DELETED 本状态码表示用户已经被删除
    */
    const USER_HAS_BEEN_DELETED = 10012;

    /**
     * @const int TARGET_USER_NOT_EXIST 本状态码表示操作的目标用户不存在
    */
    const TARGET_USER_NOT_EXIST = 10013;

    /**
     * @const int DEPARTMENT_NOT_EXIST 本状态码表示院系信息不存在
    */
    const DEPARTMENT_NOT_EXIST = 10014;

    /**
     * @const int DEPARTMENT_HAS_BEEN_DELETE 本状态码表示院系信息已经被删除
    */
    const DEPARTMENT_HAS_BEEN_DELETE = 10015;

    /**
     * @const int MAJOR_NOT_EXIST 本状态码表示专业信息不存在
    */
    const MAJOR_NOT_EXIST = 10016;

    /**
     * @const int MAJOR_HAS_BEEN_DELETE 本状态码表示专业信息已经被删除
    */
    const MAJOR_HAS_BEEN_DELETE = 10017;

    /**
     * @const int OFFICE_HOLDING_STATUS_NOT_EXIST 本状态码表示任职状态不存在
    */
    const OFFICE_HOLDING_STATUS_NOT_EXIST = 10018;

    /**
     * @const int EDUCATION_BACKGROUND_NOT_EXIST 本状态码表示学历不存在
     */
    const EDUCATION_BACKGROUND_NOT_EXIST = 10019;

    /**
     * @const int QUALIFICATION_NOT_EXIST 本状态码表示学位不存在
    */
    const QUALIFICATION_NOT_EXIST = 10020;

    /**
     * @const int SOURCE_NOT_EXIST 本状态码表示学缘不存在
     */
    const SOURCE_NOT_EXIST = 10021;

    /**
     * @const int JOB_TITLE_NOT_EXIST 本状态码表示专业技术职称不存在
    */
    const JOB_TITLE_NOT_EXIST = 10022;

    /**
     * @const int JOB_TITLE_HAS_BEEN_DELETE 本状态码表示专业技术职称已被删除
    */
    const JOB_TITLE_HAS_BEEN_DELETE = 10023;

    /**
     * @const int SUBJECT_NOT_EXIST 本状态码表示学科类别不存在
     */
    const SUBJECT_NOT_EXIST = 10024;

    /**
     * @const int SUBJECT_HAS_BEEN_DELETE 本状态码表示学科类别已被删除
     */
    const SUBJECT_HAS_BEEN_DELETE = 10025;

    /**
     * @const int POLITICS_NOT_EXIST 本状态码表示政治面貌不存在
     */
    const POLITICS_NOT_EXIST = 10026;

    /**
     * @const int POLITICS_HAS_BEEN_DELETE 本状态码表示政治面貌已经被删除
     */
    const POLITICS_HAS_BEEN_DELETE = 10027;

    /**
     * @const int NATIONALITY_NOT_EXIST 本状态码表示国籍不存在
    */
    const NATIONALITY_NOT_EXIST = 10028;

    /**
     * @const int NATIONALITY_HAS_BEEN_DELETE 本状态码表示国籍已经被删除
     */
    const NATIONALITY_HAS_BEEN_DELETE = 10029;

    /**
     * @const int TEACHER_NOT_EXIST 本状态码表示教职工信息不存在
     */
    const TEACHER_NOT_EXIST = 10030;

    /**
     * @const int TEACHER_HAS_BEEN_DELETE 本状态码表示教职工信息已经被删除
     */
    const TEACHER_HAS_BEEN_DELETE = 10031;

    /**
     * @const int MAJOR_NOT_BELONGS_TO_DEPARTMENT 本状态码表示指定专业不隶属于指定院系
    */
    const MAJOR_NOT_BELONGS_TO_DEPARTMENT = 10032;

    /**
     * @const int NATION_NOT_EXIST 本状态码表示民族信息不存在
     */
    const NATION_NOT_EXIST = 10033;

    /**
     * @const int NATION_HAS_BEEN_DELETE 本状态码表示民族信息已经被删除
     */
    const NATION_HAS_BEEN_DELETE = 10034;

    /**
     * @const int EDUCATION_LEVEL_NOT_EXIST 本状态码表示培养层次信息不存在
    */
    const EDUCATION_LEVEL_NOT_EXIST = 10035;

    /**
     * @const int LENGTH_OF_SCHOOL_NOT_EXIST 本状态码表示学制信息不存在
     */
    const LENGTH_OF_SCHOOL_NOT_EXIST = 10036;

    /**
     * @const int DEGREE_NOT_EXIST 本状态码表示学位信息不存在
     */
    const DEGREE_NOT_EXIST = 10037;

    /**
     * @const int EXAM_AREA_NOT_EXIST 本状态码表示考区信息不存在
     */
    const EXAM_AREA_NOT_EXIST = 10038;

    /**
     * @const int EXAM_AREA_HAS_BEEN_DELETE 本状态码表示考区信息已经被删除
     */
    const EXAM_AREA_HAS_BEEN_DELETE = 10039;

    /**
     * @const int STUDENT_NOT_EXIST 本状态码表示学生信息不存在
     */
    const STUDENT_NOT_EXIST = 10040;

    /**
     * @const int STUDENT_HAS_BEEN_DELETE 本状态码表示学生信息已经被删除
     */
    const STUDENT_HAS_BEEN_DELETE = 10041;

    /**
     * @const PROBE_NOT_EXIST 本状态码表示调研模板信息不存在
    */
    const PROBE_NOT_EXIST = 10042;

    /**
     * @const int PROBE_HAS_BEEN_DELETE 本状态码表示调研模板信息已经被删除
     */
    const PROBE_HAS_BEEN_DELETE = 10043;

    /**
     * @const QUESTION_NOT_EXIST 本状态码表示问题信息不存在
     */
    const QUESTION_NOT_EXIST = 10044;

    /**
     * @const int QUESTION_HAS_BEEN_DELETE 本状态码表示问题信息已经被删除
     */
    const QUESTION_HAS_BEEN_DELETE = 10045;

    /**
     * @const int STUDENT_HAS_BEEN_ANSWERED 本状态码表示学生已经作答
    */
    const STUDENT_HAS_BEEN_ANSWERED = 10046;

    /**
     * @const int UPLOAD_FILE_FAILED 本状态码表示上传文件失败
     */
    const UPLOAD_FILE_FAILED = 10047;

    /**
     * @const int MOVE_FILE_FAILED 本状态码表示移动文件失败
     */
    const MOVE_FILE_FAILED = 10048;

    const MESSAGE = [
        self::SUCCESS => '操作成功',
        self::ACCOUNT_NOT_EXIST => '账号不存在',
        self::INCORRECT_PASSWORD => '密码不正确',
        self::SAVE_DATABASE_FAILED => '数据库写入错误',
        self::PARSE_JWT_FAILED => '解析token失败',
        self::JWT_INVALID => 'token无效',
        self::PERMISSION_DENY => '用户无权限执行该操作',
        self::ACCOUNT_EXISTED => '存在账号名重复的用户,请更改账号内容',
        self::ROLE_NOT_EXIST => '角色信息不存在',
        self::ONLY_UPDATE_SELF_INFO => '传入的id与jwt中的信息不符',
        self::CAN_NOT_DELETE_SELF => '当前用户不得删除自身',
        self::USER_HAS_BEEN_DELETED => '用户已经被删除,无法执行当前操作',
        self::TARGET_USER_NOT_EXIST => '操作的目标用户不存在',
        self::DEPARTMENT_NOT_EXIST => '院系信息不存在',
        self::DEPARTMENT_HAS_BEEN_DELETE => '院系信息已经被删除,无法执行当前操作',
        self::MAJOR_NOT_EXIST => '专业信息不存在',
        self::MAJOR_HAS_BEEN_DELETE => '专业信息已经被删除,无法执行当前操作',
        self::OFFICE_HOLDING_STATUS_NOT_EXIST => '任职状态不存在',
        self::EDUCATION_BACKGROUND_NOT_EXIST => '学历不存在',
        self::QUALIFICATION_NOT_EXIST => '学位不存在',
        self::SOURCE_NOT_EXIST => '学位不存在',
        self::JOB_TITLE_NOT_EXIST => '专业技术职称不存在',
        self::JOB_TITLE_HAS_BEEN_DELETE => '专业技术职称已被删除,无法执行当前操作',
        self::SUBJECT_NOT_EXIST => '学科类别不存在',
        self::SUBJECT_HAS_BEEN_DELETE => '学科类别已被删除,无法执行当前操作',
        self::POLITICS_NOT_EXIST => '政治面貌不存在',
        self::POLITICS_HAS_BEEN_DELETE => '政治面貌已被删除,无法执行当前操作',
        self::NATIONALITY_NOT_EXIST => '国籍不存在',
        self::NATIONALITY_HAS_BEEN_DELETE => '国籍已被删除,无法执行当前操作',
        self::TEACHER_NOT_EXIST => '教职工信息不存在',
        self::TEACHER_HAS_BEEN_DELETE => '教职工已被删除,无法执行当前操作',
        self::MAJOR_NOT_BELONGS_TO_DEPARTMENT => '该专业不隶属于该院系,无法执行当前操作',
        self::NATION_NOT_EXIST => '民族不存在',
        self::NATION_HAS_BEEN_DELETE => '民族已被删除,无法执行当前操作',
        self::EDUCATION_LEVEL_NOT_EXIST => '培养层次信息不存在',
        self::LENGTH_OF_SCHOOL_NOT_EXIST => '学制信息不存在',
        self::DEGREE_NOT_EXIST => '学位信息不存在',
        self::EXAM_AREA_NOT_EXIST => '考区信息不存在',
        self::EXAM_AREA_HAS_BEEN_DELETE => '考区已被删除,无法执行当前操作',
        self::STUDENT_NOT_EXIST => '学生信息不存在',
        self::STUDENT_HAS_BEEN_DELETE => '学生已被删除,无法执行当前操作',
        self::PROBE_NOT_EXIST => '调研模板信息不存在',
        self::PROBE_HAS_BEEN_DELETE => '调研模板已被删除,无法执行当前操作',
        self::QUESTION_NOT_EXIST => '问题信息不存在',
        self::QUESTION_HAS_BEEN_DELETE => '问题已被删除,无法执行当前操作',
        self::STUDENT_HAS_BEEN_ANSWERED => '该问卷已被作答,无法重复填写',
        self::UPLOAD_FILE_FAILED => '文件上传失败,请重新上传',
        self::MOVE_FILE_FAILED => '文件移动失败,请重新上传',
    ];

    /**
     * 本方法用于生成返回至前端的JSON
     * @access private
     * @author Roach<18410269837@163.com>
     * @param int $code 状态码
     * @param string $message 状态码对应的错误信息
     * @param map<string:interface> $data 有效载荷 注意:该参数只能为关联数组
     * @param int|null $flag 标注是否在JSON序列化时将元素全部转化为对象的flag
     * @return string 返回至客户端的JSON
    */
    private function generate($code, $message, $data, $flag=JSON_FORCE_OBJECT) {
        $resp = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        return json_encode($resp, $flag);
    }

    /**
     * 本方法用于生成当参数不合规时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $message 参数报错信息
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function paramInvalid($message, $data) {
        return self::generate(self::PARAM_INVALID, $message, $data);
    }

    /**
     * 本方法用于生成当账号不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
    */
    public function accountNotExist($data) {
        return self::generate(self::ACCOUNT_NOT_EXIST, self::MESSAGE[self::ACCOUNT_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成当密码不正确时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function incorrectPassword($data) {
        return self::generate(self::INCORRECT_PASSWORD, self::MESSAGE[self::INCORRECT_PASSWORD], $data);
    }

    /**
     * 本方法用于生成当数据库落盘失败时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function DBFailed($data) {
        return self::generate(self::SAVE_DATABASE_FAILED, self::MESSAGE[self::SAVE_DATABASE_FAILED], $data);
    }

    /**
     * 本方法用于生成当响应成功时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function success($data) {
        if ($data == []) {
            return self::generate(self::SUCCESS, self::MESSAGE[self::SUCCESS], $data);
        } else {
            return self::generate(self::SUCCESS, self::MESSAGE[self::SUCCESS], $data, null);
        }
    }

    /**
     * 本方法用于生成当解析token失败时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function parseJwtFailed($data) {
        return self::generate(self::PARSE_JWT_FAILED, self::MESSAGE[self::PARSE_JWT_FAILED], $data);
    }

    /**
     * 本方法用于生成当无法根据jwt中的信息查找到用户信息时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function jwtInvalid($data) {
        return self::generate(self::JWT_INVALID, self::MESSAGE[self::JWT_INVALID], $data);
    }

    /**
     * 本方法用于生成当用户无权限执行当前操作时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function permissionDeny($data) {
        return self::generate(self::PERMISSION_DENY, self::MESSAGE[self::PERMISSION_DENY], $data);
    }

    /**
     * 本方法用于生成当账号已存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function accountExisted($data) {
        return self::generate(self::ACCOUNT_EXISTED, self::MESSAGE[self::ACCOUNT_EXISTED], $data);
    }

    /**
     * 本方法用于生成当角色信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function roleNotExist($data) {
        return self::generate(self::ROLE_NOT_EXIST, self::MESSAGE[self::ROLE_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在更新用户信息操作中 当用户的id与jwt中保存的id信息不符时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function onlyUpdateSelf($data) {
        return self::generate(self::ONLY_UPDATE_SELF_INFO, self::MESSAGE[self::ONLY_UPDATE_SELF_INFO], $data);
    }

    /**
     * 本方法用于生成在删除用户时 当前用户删除的目标对象为自身时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function canNotDeleteSelf($data) {
        return self::generate(self::CAN_NOT_DELETE_SELF, self::MESSAGE[self::CAN_NOT_DELETE_SELF], $data);
    }

    /**
     * 本方法用于生成在删除用户时 当前用户删除的目标对象为自身时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function userHasBeenDeleted($data) {
        return self::generate(self::USER_HAS_BEEN_DELETED, self::MESSAGE[self::USER_HAS_BEEN_DELETED], $data);
    }

    /**
     * 本方法用于生成在操作的目标用户不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function targetUserNotExist($data) {
        return self::generate(self::TARGET_USER_NOT_EXIST, self::MESSAGE[self::TARGET_USER_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在院系信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function departmentNotExist($data) {
        return self::generate(self::DEPARTMENT_NOT_EXIST, self::MESSAGE[self::DEPARTMENT_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在院系信息已经被删除时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function departmentHasBeenDeleted($data) {
        return self::generate(self::DEPARTMENT_HAS_BEEN_DELETE, self::MESSAGE[self::DEPARTMENT_HAS_BEEN_DELETE], $data);
    }

    /**
     * 本方法用于生成在院系信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function majorNotExist($data) {
        return self::generate(self::MAJOR_NOT_EXIST, self::MESSAGE[self::MAJOR_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在专业信息已经被删除时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function majorHasBeenDeleted($data) {
        return self::generate(self::MAJOR_HAS_BEEN_DELETE, self::MESSAGE[self::MAJOR_HAS_BEEN_DELETE], $data);
    }

    /**
     * 本方法用于生成在任职状态不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function officeHoldingNotExist($data) {
        return self::generate(self::OFFICE_HOLDING_STATUS_NOT_EXIST, self::MESSAGE[self::OFFICE_HOLDING_STATUS_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在学历不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function educationBackgroundNotExist($data) {
        return self::generate(self::EDUCATION_BACKGROUND_NOT_EXIST, self::MESSAGE[self::EDUCATION_BACKGROUND_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在学历不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function qualificationNotExist($data) {
        return self::generate(self::QUALIFICATION_NOT_EXIST, self::MESSAGE[self::QUALIFICATION_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在学缘不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function sourceNotExist($data) {
        return self::generate(self::SOURCE_NOT_EXIST, self::MESSAGE[self::SOURCE_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在专业技术职称不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function jobTitleNotExist($data) {
        return self::generate(self::JOB_TITLE_NOT_EXIST, self::MESSAGE[self::JOB_TITLE_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在专业技术职称信息已经被删除时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function jobTitleHasBeenDeleted($data) {
        return self::generate(self::JOB_TITLE_HAS_BEEN_DELETE, self::MESSAGE[self::JOB_TITLE_HAS_BEEN_DELETE], $data);
    }

    /**
     * 本方法用于生成在学科类别不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function subjectNotExist($data) {
        return self::generate(self::SUBJECT_NOT_EXIST, self::MESSAGE[self::SUBJECT_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在学科类别信息已经被删除时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function subjectHasBeenDeleted($data) {
        return self::generate(self::SUBJECT_HAS_BEEN_DELETE, self::MESSAGE[self::SUBJECT_HAS_BEEN_DELETE], $data);
    }

    /**
     * 本方法用于生成在政治面貌不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function politicsNotExist($data) {
        return self::generate(self::POLITICS_NOT_EXIST, self::MESSAGE[self::POLITICS_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在政治面貌信息已经被删除时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function politicsHasBeenDeleted($data) {
        return self::generate(self::POLITICS_HAS_BEEN_DELETE, self::MESSAGE[self::POLITICS_HAS_BEEN_DELETE], $data);
    }

    /**
     * 本方法用于生成在国籍不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function nationalityNotExist($data) {
        return self::generate(self::NATIONALITY_NOT_EXIST, self::MESSAGE[self::NATIONALITY_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在国籍信息已经被删除时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function nationalityHasBeenDeleted($data) {
        return self::generate(self::NATIONALITY_HAS_BEEN_DELETE, self::MESSAGE[self::NATIONALITY_HAS_BEEN_DELETE], $data);
    }

    /**
     * 本方法用于生成在教职工信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function teacherNotExist($data) {
        return self::generate(self::TEACHER_NOT_EXIST, self::MESSAGE[self::TEACHER_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在教职工信息已经被删除时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function teacherHasBeenDeleted($data) {
        return self::generate(self::TEACHER_HAS_BEEN_DELETE, self::MESSAGE[self::TEACHER_HAS_BEEN_DELETE], $data);
    }

    /**
     * 本方法用于生成在指定专业不隶属于指定院系时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function majorNotBelongsToDepartment($data) {
        return self::generate(self::MAJOR_NOT_BELONGS_TO_DEPARTMENT, self::MESSAGE[self::MAJOR_NOT_BELONGS_TO_DEPARTMENT], $data);
    }

    /**
     * 本方法用于生成在民族信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function nationNotExist($data) {
        return self::generate(self::NATION_NOT_EXIST, self::MESSAGE[self::NATION_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在民族信息已经被删除时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function nationHasBeenDeleted($data) {
        return self::generate(self::NATION_HAS_BEEN_DELETE, self::MESSAGE[self::NATION_HAS_BEEN_DELETE], $data);
    }

    /**
     * 本方法用于生成在培养层次信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function educationLevelNotExist($data) {
        return self::generate(self::EDUCATION_LEVEL_NOT_EXIST, self::MESSAGE[self::EDUCATION_LEVEL_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在学制信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function lengthOfSchoolNotExist($data) {
        return self::generate(self::LENGTH_OF_SCHOOL_NOT_EXIST, self::MESSAGE[self::LENGTH_OF_SCHOOL_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在学位信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function degreeNotExist($data) {
        return self::generate(self::DEGREE_NOT_EXIST, self::MESSAGE[self::DEGREE_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在考区信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function examAreaNotExist($data) {
        return self::generate(self::EXAM_AREA_NOT_EXIST, self::MESSAGE[self::EXAM_AREA_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在考区信息已经被删除时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function examAreaHasBeenDeleted($data) {
        return self::generate(self::EXAM_AREA_HAS_BEEN_DELETE, self::MESSAGE[self::EXAM_AREA_HAS_BEEN_DELETE], $data);
    }

    /**
     * 本方法用于生成在学生信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function studentNotExist($data) {
        return self::generate(self::STUDENT_NOT_EXIST, self::MESSAGE[self::STUDENT_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在学生信息已经被删除时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function studentHasBeenDeleted($data) {
        return self::generate(self::STUDENT_HAS_BEEN_DELETE, self::MESSAGE[self::STUDENT_HAS_BEEN_DELETE], $data);
    }

    /**
     * 本方法用于生成在调研模板信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function probeNotExist($data) {
        return self::generate(self::PROBE_NOT_EXIST, self::MESSAGE[self::PROBE_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在调研模板信息已经被删除时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function probeHasBeenDeleted($data) {
        return self::generate(self::PROBE_HAS_BEEN_DELETE, self::MESSAGE[self::PROBE_HAS_BEEN_DELETE], $data);
    }

    /**
     * 本方法用于生成在问题信息不存在时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function questionNotExist($data) {
        return self::generate(self::QUESTION_NOT_EXIST, self::MESSAGE[self::QUESTION_NOT_EXIST], $data);
    }

    /**
     * 本方法用于生成在问题信息已经被删除时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function questionHasBeenDeleted($data) {
        return self::generate(self::QUESTION_HAS_BEEN_DELETE, self::MESSAGE[self::QUESTION_HAS_BEEN_DELETE], $data);
    }

    /**
     * 本方法用于生成在学生已经回答指定问卷时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function studentHasBeenAnswer($data) {
        return self::generate(self::STUDENT_HAS_BEEN_ANSWERED, self::MESSAGE[self::STUDENT_HAS_BEEN_ANSWERED], $data);
    }

    /**
     * 本方法用于生成在文件上传失败时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function uploadFileFailed($data) {
        return self::generate(self::UPLOAD_FILE_FAILED, self::MESSAGE[self::UPLOAD_FILE_FAILED], $data);
    }

    /**
     * 本方法用于生成在文件移动失败时返回至前端的JSON
     * @access public
     * @author Roach<18410269837@163.com>
     * @param map<string:interface> $data 有效载荷
     * @return string 参数错误的JSON
     */
    public function moveFileFailed($data) {
        return self::generate(self::MOVE_FILE_FAILED, self::MESSAGE[self::MOVE_FILE_FAILED], $data);
    }
}

