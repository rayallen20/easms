<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model {
    /**
     * @const string CREATED_AT 数据创建时间字段
     */
    const CREATED_AT = 'created_time';

    /**
     * @const string UPDATED_AT 数据修改时间字段
     */
    const UPDATED_AT = 'updated_time';

    /**
     * @const array USER_STATUS 表示学生信息状态的数组 normal:正常 delete:删除
     */
    const STATUS = [
        'normal' => 'normal',
        'delete' => 'delete',
    ];

    /**
     * @var string $table 表名
     */
    protected $table = 'student';

    /**
     * @var string $primaryKey 主键字段名
     */
    protected $primaryKey = 'id';

    /**
     * @var bool $timestamps 使用时间戳
     */
    public $timestamps = true;

    /**
     * @var string $dateFormat 时间戳格式
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 本方法用于定义本表(student表)与nation表之间通过student.nation_id和nation.id建立的1对1关系
     * @return HasOne
     */
    public function nation() {
        return $this->hasOne('App\Http\Models\Nation', 'id', 'nation_id');
    }

    /**
     * 本方法用于定义本表(student表)与exam_area表之间通过student.exam_area_id和exam_area.id建立的1对1关系
     * @return HasOne
     */
    public function exam_area() {
        return $this->hasOne('App\Http\Models\ExamArea', 'id', 'exam_area_id');
    }

    /**
     * 本方法用于定义本表(student表)与department表之间通过student.department_id和department.id建立的1对1关系
     * @return HasOne
     */
    public function department() {
        return $this->hasOne('App\Http\Models\Department', 'id', 'department_id');
    }

    /**
     * 本方法用于定义本表(student表)与major表之间通过student.major_id和major.id建立的1对1关系
     * @return HasOne
     */
    public function major() {
        return $this->hasOne('App\Http\Models\Major', 'id', 'major_id');
    }

    /**
     * 本方法用于创建1条Student表中的信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Biz\Student $student 业务层Student对象 表示待创建的学生
     * @return bool true表示创建成功 false表示创建失败
     */
    public function create($student) {
        $this->number = $student->number;
        $this->id_number = $student->idNumber;
        $this->name = $student->name;
        $this->gender = (string)$student->gender;
        $this->nation_id = $student->nation->id;
        $this->exam_area_id = $student->examArea->id;
        $this->department_id = $student->department->id;
        $this->major_id = $student->major->id;
        $this->major_direction = $student->majorDirection;
        $this->grade = $student->grade;
        $this->class = $student->class;
        $this->education_level_code = $student->educationLevel;
        $this->length_of_school_code = $student->lengthOfSchool;
        $this->degree_code = $student->degree;
        $this->status = self::STATUS['normal'];
        $this->sort = $this->findMaxId() + 1;
        return $this->save();
    }

    /**
     * 本方法用于查找student表中当前最大id值
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $maxId id字段最大值 若student表中无数据 则返回0
     */
    public function findMaxId() {
        $maxId = $this->max('id');
        if ($maxId == null) {
            $maxId = 0;
            return $maxId;
        }
        return $maxId;
    }

    /**
     * 本方法用于分页查询状态正常的学生信息集合 结果集按sort字段值升序排序
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $offset 偏移量
     * @param int $limit 每页信息数量
     * @return Collection 查询到的结果集
     */
    public function findNormalStudents($offset, $limit) {
        $departments = $this->where('status', self::STATUS['normal'])
            ->orderBy('sort', 'asc')
            ->offset($offset)
            ->limit($limit)
            ->get();
        return $departments;
    }

    /**
     * 本方法用于计算状态正常的学生信息的总条数
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int 信息总条数
     */
    public function countNormalStudents() {
        return $this->where('status', self::STATUS['normal'])->count();
    }

    public function findById($id) {
        return $this->where('id', $id)->first();
    }

    /**
     * 本方法用于更新1条Student表中的信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Student $orm 要更新的orm
     * @param \App\Biz\Student $student 业务层Student对象 表示待创建的学生
     * @return bool true表示更新成功 false表示更新失败
     */
    public function updateStudent($orm, $student) {
        $orm->number = $student->number;
        $orm->id_number = $student->idNumber;
        $orm->name = $student->name;
        $orm->gender = (string)$student->gender;
        $orm->nation_id = $student->nation->id;
        $orm->exam_area_id = $student->examArea->id;
        $orm->department_id = $student->department->id;
        $orm->major_id = $student->major->id;
        $orm->major_direction = $student->majorDirection;
        $orm->grade = $student->grade;
        $orm->class = $student->class;
        $orm->education_level_code = $student->educationLevel;
        $orm->length_of_school_code = $student->lengthOfSchool;
        $orm->degree_code = $student->degree;
        return $orm->save();
    }

    /**
     * 本方法用于更新1条学生信息的状态
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Student $orm 要更新的orm
     * @param string $status 更新后的status字段值
     * @return bool 更新结果
     */
    public function updateStatus($orm, $status) {
        $orm->status = $status;
        return $orm->save();
    }

    public function findByNumber($number) {
        return $this->where('number', $number)->first();
    }
}
