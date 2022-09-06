<?php
namespace App\Biz;

use App\Lib\Lib;
use App\Lib\Pagination;
use App\Lib\Resp;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Files\ExcelFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Student {
    /**
     * @const array GENDER 学生性别字典
     */
    const GENDER = [
        'female' => 0,
        'male' => 1,
    ];

    /**
     * @const array EDUCATION_LEVEL 培养层次字典
     */
    const EDUCATION_LEVEL = [
        [
            'code' => 60,
            'display' => '研究生',
        ],
        [
            'code' => 50,
            'display' => '本科',
        ],
        [
            'code' => 40,
            'display' => '专科',
        ],
    ];

    /**
     * @const array LEVEL_OF_SCHOOL 学制类型字典
     */
    const LENGTH_OF_SCHOOL = [
        [
            'code' => 50,
            'display' => '五年',
        ],
        [
            'code' => 40,
            'display' => '四年',
        ],
        [
            'code' => 30,
            'display' => '三年',
        ],
    ];

    /**
     * @const array DEGREE 学位字典
     */
    const DEGREE = [
        [
            'code' => 30,
            'display' => '博士',
        ],
        [
            'code' => 20,
            'display' => '硕士',
        ],
        [
            'code' => 10,
            'display' => '学士',
        ],
        [
            'code' => 0,
            'display' => '无',
        ],
    ];

    /**
     * @var int $id 学生id
    */
    public $id;

    /**
     * @var string $number 学号
    */
    public $number;

    /**
     * @var string $name 姓名
    */
    public $name;

    /**
     * @var string $idNumber 身份证号
    */
    public $idNumber;

    /**
     * @var int $gender 学生性别
    */
    public $gender;

    /**
     * @var Nation $nation 学生民族
    */
    public $nation;

    /**
     * @var ExamArea $examArea 学生考区
    */
    public $examArea;

    /**
     * @var Department $department 学生院系
    */
    public $department;

    /**
     * @var Major $major 学生专业
    */
    public $major;

    /**
     * @var string $majorDirection 专业方向
    */
    public $majorDirection;

    /**
     * @var string $grade 年级
    */
    public $grade;

    /**
     * @var string $class 班级
    */
    public $class;

    /**
     * @var int $educationLevel 培养层次
    */
    public $educationLevel;

    /**
     * @var int $lengthOfSchool 学制
    */
    public $lengthOfSchool;

    /**
     * @var int $degree 学位
    */
    public $degree;

    /**
     * @var int $sort 排序字段
    */
    public $sort;

    /**
     * @var string $createdTime 学生创建时间
     */
    public $createdTime;

    /**
     * @var string $updatedTime 学生修改时间
     */
    public $updatedTime;

    /**
     * 本方法用于创建学生
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $number 学生学号
     * @param string $name 学生姓名
     * @param string $idNumber 学生身份证号
     * @param int $genderCode 学生性别编码
     * @param int $nationId 学生民族id
     * @param int $examAreaId 学生所在考区id
     * @param int $departmentId 学生所属院系id
     * @param int $majorId 学生所属专业id
     * @param string $majorDirection 学生专业方向
     * @param string $grade 学生年级
     * @param string  $class 学生班级
     * @param int $educationLevelCode 学生培养层次编码
     * @param int $lengthOfSchoolCode 学生学制编码
     * @param int $degreeCode 学生学位编码
     * @return int $code 操作状态码 0表示成功 操作失败则返回对应失败原因的状态码
    */
    public function create($number, $name, $idNumber, $genderCode, $nationId, $examAreaId,
                           $departmentId, $majorId, $majorDirection, $grade, $class,
                           $educationLevelCode, $lengthOfSchoolCode, $degreeCode) {
        // 确认培养层次编码是否存在
        if (!self::existEducationLevel($educationLevelCode)) {
            $code = Resp::EDUCATION_LEVEL_NOT_EXIST;
            return $code;
        }
        $this->educationLevel = $educationLevelCode;

        // 确认学制是否存在
        if (!self::existLengthOfSchool($lengthOfSchoolCode)) {
            $code = Resp::LENGTH_OF_SCHOOL_NOT_EXIST;
            return $code;
        }
        $this->lengthOfSchool = $lengthOfSchoolCode;

        // 确认学位是否存在
        if (!self::existDegree($degreeCode)) {
            $code = Resp::DEGREE_NOT_EXIST;
            return $code;
        }
        $this->degree = $degreeCode;

        // 确认院系id是否存在
        $departmentBiz = new Department();
        $code = $departmentBiz->exist($departmentId);
        if ($code != 0) {
            return $code;
        }

        // 确认专业id是否存在
        $majorBiz = new Major();
        $code = $majorBiz->exist($majorId);
        if ($code != 0) {
            return $code;
        }

        // 确认专业是否隶属于指定院系
        if ($majorBiz->department->id != $departmentBiz->id) {
            $code = Resp::MAJOR_NOT_BELONGS_TO_DEPARTMENT;
            return $code;
        }
        $this->department = $departmentBiz;
        $this->major = $majorBiz;

        // 确认民族是否存在
        $nationBiz = new Nation();
        $code = $nationBiz->exist($nationId);
        if ($code != 0) {
            return $code;
        }
        $this->nation = $nationBiz;

        // 确认考区是否存在
        $examAreaBiz = new ExamArea();
        $code = $examAreaBiz->exist($examAreaId);
        if ($code != 0) {
            return $code;
        }
        $this->examArea = $examAreaBiz;

        $this->number = $number;
        $this->name = $name;
        $this->gender = $genderCode;
        $this->idNumber = $idNumber;
        $this->majorDirection = $majorDirection;
        $this->grade = $grade;
        $this->class = $class;

        $model = new \App\Http\Models\Student();
        $result = $model->create($this);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于确定指定的培养层次编码是否存在对应的培养层次信息
     * @access private
     * @author Roach<18410269837@163.com>
     * @param int $educationLevelCode 培养层次编码
     * @return bool true表示存在培养层次信息 false表示不存在
     */
    private function existEducationLevel($educationLevelCode) {
        foreach (self::EDUCATION_LEVEL as $educationLevel) {
            if ($educationLevel['code'] == $educationLevelCode) {
                return true;
            }
        }
        return false;
    }

    /**
     * 本方法用于确定指定的学制编码是否存在对应的学制信息
     * @access private
     * @author Roach<18410269837@163.com>
     * @param int $lengthOfSchoolCode 学制编码
     * @return bool true表示存在学制信息 false表示不存在
     */
    private function existLengthOfSchool($lengthOfSchoolCode) {
        foreach (self::LENGTH_OF_SCHOOL as $lengthOfSchool) {
            if ($lengthOfSchool['code'] == $lengthOfSchoolCode) {
                return true;
            }
        }
        return false;
    }

    /**
     * 本方法用于确定指定的学位编码是否存在对应的学位信息
     * @access private
     * @author Roach<18410269837@163.com>
     * @param int $degreeCode 学位编码
     * @return bool true表示存在学位信息 false表示不存在
     */
    private function existDegree($degreeCode) {
        foreach (self::DEGREE as $degree) {
            if ($degree['code'] == $degreeCode) {
                return true;
            }
        }
        return false;
    }

    /**
     * 本方法用于列表展示学生信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $currentPage 当前页数
     * @param int $itemPerPage 每页显示信息条数
     * @return array $result 本数组共3项内容:
     * array<Student> $result['students']:学生信息集合
     * App\Lib\Pagination $result['pagination']:分页器对象
     * int $result['code']:错误码
     */
    public function list($currentPage, $itemPerPage) {
        $result = [
            'students' => [],
            'pagination' => null,
            'code' => 0
        ];

        $pagination = new Pagination($currentPage, $itemPerPage);
        $offset = $pagination->calcOffset();

        $model = new \App\Http\Models\Student();
        $studentCollection = $model->findNormalStudents($offset, $itemPerPage);
        for ($i = 0; $i <= count($studentCollection) - 1; $i++) {
            $studentOrm = $studentCollection[$i];
            $student = new Student();
            $student->fill($studentOrm);
            $result['students'][$i] = $student;
        }

        $totalStudentNum = $model->countNormalStudents();
        $pagination->calcTotalPage($totalStudentNum);
        $result['pagination'] = $pagination;
        return $result;
    }

    /**
     * 本方法用于根据Student表的ORM填充Biz层的Student对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\Student $model 学生信息ORM
     * @return void
     */
    public function fill($model) {
        $this->id = $model->id;
        $this->number = $model->number;
        $this->idNumber= $model->id_number;
        $this->gender = $model->gender;
        $this->nation = new Nation();
        $this->nation->fill($model->nation);
        $this->examArea = new ExamArea();
        $this->examArea->fill($model->exam_area);
        $this->department = new Department();
        $this->department->fill($model->department);
        $this->major = new Major();
        $this->major->fill($model->major);
        $this->majorDirection = $model->major_direction;
        $this->grade = $model->grade;
        $this->class = $model->class;
        $this->educationLevel = $model->education_level_code;
        $this->lengthOfSchool = $model->length_of_school_code;
        $this->degree = $model->degree_code;
        $this->createdTime = explode('.', $model->created_time)[0];
        $this->updatedTime = explode('.', $model->updated_time)[0];
    }

    /**
     * 本方法用于更新学生
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 学生id
     * @param string $number 学生学号
     * @param string $name 学生姓名
     * @param string $idNumber 学生身份证号
     * @param int $genderCode 学生性别编码
     * @param int $nationId 学生民族id
     * @param int $examAreaId 学生所在考区id
     * @param int $departmentId 学生所属院系id
     * @param int $majorId 学生所属专业id
     * @param string $majorDirection 学生专业方向
     * @param string $grade 学生年级
     * @param string  $class 学生班级
     * @param int $educationLevelCode 学生培养层次编码
     * @param int $lengthOfSchoolCode 学生学制编码
     * @param int $degreeCode 学生学位编码
     * @return int $code 操作状态码 0表示成功 操作失败则返回对应失败原因的状态码
     */
    public function update($id, $number, $name, $idNumber, $genderCode, $nationId, $examAreaId,
                           $departmentId, $majorId, $majorDirection, $grade, $class,
                           $educationLevelCode, $lengthOfSchoolCode, $degreeCode) {
        $code = 0;
        $model = new \App\Http\Models\Student();
        $studentOrm = $model->findById($id);
        if ($studentOrm == null) {
            $code = Resp::STUDENT_NOT_EXIST;
            return $code;
        }

        if ($studentOrm->status == \App\Http\Models\Student::STATUS['delete']) {
            $code = Resp::STUDENT_HAS_BEEN_DELETE;
            return $code;
        }

        // 确认培养层次编码是否存在
        if (!self::existEducationLevel($educationLevelCode)) {
            $code = Resp::EDUCATION_LEVEL_NOT_EXIST;
            return $code;
        }
        $this->educationLevel = $educationLevelCode;

        // 确认学制是否存在
        if (!self::existLengthOfSchool($lengthOfSchoolCode)) {
            $code = Resp::LENGTH_OF_SCHOOL_NOT_EXIST;
            return $code;
        }
        $this->lengthOfSchool = $lengthOfSchoolCode;

        // 确认学位是否存在
        if (!self::existDegree($degreeCode)) {
            $code = Resp::DEGREE_NOT_EXIST;
            return $code;
        }
        $this->degree = $degreeCode;

        // 确认院系id是否存在
        $departmentBiz = new Department();
        $code = $departmentBiz->exist($departmentId);
        if ($code != 0) {
            return $code;
        }

        // 确认专业id是否存在
        $majorBiz = new Major();
        $code = $majorBiz->exist($majorId);
        if ($code != 0) {
            return $code;
        }

        // 确认专业是否隶属于指定院系
        if ($majorBiz->department->id != $departmentBiz->id) {
            $code = Resp::MAJOR_NOT_BELONGS_TO_DEPARTMENT;
            return $code;
        }
        $this->department = $departmentBiz;
        $this->major = $majorBiz;

        // 确认民族是否存在
        $nationBiz = new Nation();
        $code = $nationBiz->exist($nationId);
        if ($code != 0) {
            return $code;
        }
        $this->nation = $nationBiz;

        // 确认考区是否存在
        $examAreaBiz = new ExamArea();
        $code = $examAreaBiz->exist($examAreaId);
        if ($code != 0) {
            return $code;
        }
        $this->examArea = $examAreaBiz;

        $this->number = $number;
        $this->name = $name;
        $this->gender = $genderCode;
        $this->idNumber = $idNumber;
        $this->majorDirection = $majorDirection;
        $this->grade = $grade;
        $this->class = $class;

        $result = $model->updateStudent($studentOrm, $this);

        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }

        $this->fill($studentOrm);
        return $code;
    }

    /**
     * 本方法用于删除学生
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id 待删除院系的学生id
     * @return int $code 错误码 若操作无错误则返回0
     */
    public function delete($id) {
        $code = 0;
        $model = new \App\Http\Models\Student();
        $studentOrm = $model->findById($id);
        if ($studentOrm == null) {
            $code = Resp::STUDENT_NOT_EXIST;
            return $code;
        }

        if ($studentOrm->status == \App\Http\Models\Student::STATUS['delete']) {
            $code = Resp::STUDENT_HAS_BEEN_DELETE;
            return $code;
        }

        $result = $model->updateStatus($studentOrm, \App\Http\Models\Student::STATUS['delete']);
        if (!$result) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于根据学号确认学生是否存在
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $number 学号
     * @return int $code 存在返回0 否则返回对应错误码
    */
    public function existByNumber($number) {
        $code = 0;
        $model = new \App\Http\Models\Student();
        $studentOrm = $model->findByNumber($number);
        if ($studentOrm == null) {
            $code = Resp::STUDENT_NOT_EXIST;
            return $code;
        }

        if ($studentOrm->status == \App\Http\Models\Student::STATUS['delete']) {
            $code = Resp::STUDENT_HAS_BEEN_DELETE;
            return $code;
        }

        $this->fill($studentOrm);
        return $code;
    }

    /**
     * 本方法用于读取excel中的内容并创建学生信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param UploadedFile $excel 文件对象
     * @return array
     */
    public function readExcel($excel) {
        $result = [
            'code' => 0,
            'exceptionMessage' => '',
        ];
        try {
            $path = env('EXCEL_PATH');
            $lib = new Lib();
            $fileName = $lib->getNowDateTime() . "." . $excel->getClientOriginalExtension();
            $saveFile = $excel->move($path, $fileName);
        } catch (FileException $e) {
            $result['code'] = Resp::MOVE_FILE_FAILED;
            return $result;
        }
        $result = self::rowToStudent($path. "/".$fileName);
        if ($result['code'] != 0) {
            return $result;
        }
        $model = new \App\Http\Models\Student();
        $saveResult = $model->saveOrms($result['orms']);
        if (!$saveResult) {
            $result['code'] = Resp::SAVE_DATABASE_FAILED;
            return $result;
        }
        return $result;
    }

    private function rowToStudent($fileName) {
        $result = [
            'code' => 0,
            'exceptionMessage' => '',
            'orms' => [],
        ];
        $excelReader = \PHPExcel_IOFactory::load($fileName);
        $sheet = $excelReader->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        if ($highestRow > 100001) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '表格行数不得大于100001行';
            return $result;
        }

        $rowArr = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; $column <= 13; $column++) {
                $val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
                $rowArr[$row - 1][] = $val;
            }
            $rowResult = self::checkRow($rowArr[$row - 1]);
            if ($rowResult['code'] != 0) {
                $result['code'] = $rowResult['code'];
                $result['exceptionMessage'] = '第' . $row . "行的" . $rowResult['exceptionMessage'];
                return $result;
            } else {
                $result['orms'][] = $rowResult['orm'];
            }
        }

        $numbers = array_column($rowArr, 1);
        $distinctNumbers = array_unique($numbers);
        if (count($numbers) != count($distinctNumbers)) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '表格中有相同的学号';
            return $result;
        }
        return $result;
    }

    private function checkRow($row) {
        $result = [
            'code' => 0,
            'exceptionMessage' => '',
            'orm' => null
        ];
        $name = $row[0];
        $number = (string)$row[1];
        $idNumber = (string)$row[2];
        $gender = $row[3];
        $nation = $row[4];
        $examArea = $row[5];
        $departmentName = $row[6];
        $majorName = $row[7];
        $majorDirection = $row[8];
        $grade = $row[9];
        $class = $row[10];
        $educationLevel = $row[11];
        $lengthOfSchool = $row[12];
        $degree = $row[13];

        $studentOrm = new \App\Http\Models\Student();
        $studentOrm->name = $name;

        $targetStudentOrm = $studentOrm->findByNumber($number);
        if ($targetStudentOrm != null) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '学号在数据库中已存在';
            return $result;
        }
        $studentOrm->number = $number;

        $lib = new Lib();
        $isIdNumber = $lib->isIdNumber($idNumber);
        if (!$isIdNumber) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '身份证号格式不是18位数字或以X结尾的17位数字';
            return $result;
        }
        $studentOrm->id_number = $idNumber;

        if ($gender != "男" && $gender != "女") {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '性别必须为男或女';
        }
        if ($gender == "男") {
            $studentOrm->gender = "1";
        } else {
            $studentOrm->gender = "0";
        }

        $nationModel = new \App\Http\Models\Nation();
        $nationOrm = $nationModel->findByName($nation);
        if ($nationOrm == null) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '民族不存在';
            return $result;
        }
        $studentOrm->nation_id = $nationOrm->id;

        $examAreaModel = new \App\Http\Models\ExamArea();
        $examAreaOrm = $examAreaModel->findByName($examArea);
        if ($examAreaOrm == null) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '考区不存在';
            return $result;
        }
        $studentOrm->exam_area_id = $examAreaOrm->id;

        $departmentModel = new \App\Http\Models\Department();
        $departmentOrm = $departmentModel->findByName($departmentName);
        if ($departmentOrm == null) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '院系不存在';
            return $result;
        }
        $studentOrm->department_id = $departmentOrm->id;

        $major = null;
        foreach ($departmentOrm->majors as $majorOrm) {
            if ($majorOrm->status == \App\Http\Models\Major::STATUS['normal'] && $majorName == $majorOrm->name) {
                $major = $majorOrm;
                break;
            }
        }
        if ($major == null) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '院系下不存在该专业';
            return $result;
        }
        $studentOrm->major_id = $major->id;
        $studentOrm->major_direction = $majorDirection;
        $studentOrm->grade = $grade;
        $studentOrm->class = $class;

        $educationLevelCode = null;
        foreach (self::EDUCATION_LEVEL as $educationLevelArr) {
            if ($educationLevel == $educationLevelArr['display']) {
                $educationLevelCode = $educationLevelArr['code'];
                break;
            }
        }
        if ($educationLevelCode == null) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '培养层次只能为研究生、本科或专科';
            return $result;
        }
        $studentOrm->education_level_code = $educationLevelCode;

        $lengthOfSchoolCode = null;
        foreach (self::LENGTH_OF_SCHOOL as $lengthOfSchoolArr) {
            if ($lengthOfSchool == $lengthOfSchoolArr['display']) {
                $lengthOfSchoolCode = $lengthOfSchoolArr['code'];
                break;
            }
        }
        if ($lengthOfSchoolCode == null) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '学制只能为三年、四年或五年';
            return $result;
        }
        $studentOrm->length_of_school_code = $lengthOfSchoolCode;

        $degreeCode = null;
        foreach (self::DEGREE as $degreeArr) {
            if ($degree == $degreeArr['display']) {
                $degreeCode = $degreeArr['code'];
                break;
            }
        }

        if ($degreeCode == null) {
            $result['code'] = Resp::PARAM_INVALID;
            $result['exceptionMessage'] = '学位只能为博士、硕士、学士或无';
            return $result;
        }
        $studentOrm->degree_code = $degreeCode;
        $result['orm'] = $studentOrm;
        return $result;
    }
}
