<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

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
     * @const array USER_STATUS 表示学科信息状态的数组 normal:正常 delete:删除
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
}
