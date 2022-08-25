<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model {
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
    protected $table = 'teacher';

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
     * 本方法用于定义本表(teacher表)与department表之间通过teacher.department_id和department.id建立的1对1关系
     * @return HasOne
     */
    public function department() {
        return $this->hasOne('App\Http\Models\Department', 'id', 'department_id');
    }

    /**
     * 本方法用于定义本表(teacher表)与job_title表之间通过teacher.job_title_id和job_title.id建立的1对1关系
     * @return HasOne
     */
    public function jobTitle() {
        return $this->hasOne('App\Http\Models\JobTitle', 'id', 'job_title_id');
    }

    /**
     * 本方法用于定义本表(teacher表)与subject表之间通过teacher.subject_id和subject.id建立的1对1关系
     * @return HasOne
     */
    public function subject() {
        return $this->hasOne('App\Http\Models\Subject', 'id', 'subject_id');
    }

    /**
     * 本方法用于定义本表(teacher表)与politics表之间通过teacher.politics_id和politics.id建立的1对1关系
     * @return HasOne
     */
    public function politics() {
        return $this->hasOne('App\Http\Models\Politics', 'id', 'politics_id');
    }

    /**
     * 本方法用于定义本表(teacher表)与nationality表之间通过teacher.nationality_id和nationality.id建立的1对1关系
     * @return HasOne
     */
    public function nationality() {
        return $this->hasOne('App\Http\Models\Nationality', 'id', 'nationality_id');
    }

    /**
     * 本方法用于创建1条Teacher表中的信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Biz\Teacher $teacher 业务层Teacher对象 表示待创建的教职工
     * @return bool true表示创建成功 false表示创建失败
     */
    public function create($teacher) {
        $this->department_id = $teacher->department->id;
        $this->job_number = $teacher->jobNumber;
        $this->name = $teacher->name;
        $this->gender = (string)$teacher->gender;
        $this->birth_date = $teacher->birthDate;
        $this->into_school_date = $teacher->intoSchoolDate;
        $this->office_holding_code = $teacher->officeHoldingStatus;
        $this->education_background_code = $teacher->educationBackground;
        $this->qualification_code = $teacher->qualification;
        $this->source_code = $teacher->source;
        $this->job_title_id = $teacher->jobTitle->id;
        $this->subject_id = $teacher->subject->id;
        $this->politics_id = $teacher->politics->id;
        $this->nationality_id = $teacher->nationality->id;
        $this->status = self::STATUS['normal'];
        $this->sort = $this->findMaxId() + 1;
        return $this->save();
    }

    /**
     * 本方法用于查找teacher表中当前最大id值
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $maxId id字段最大值 若teacher表中无数据 则返回0
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
     * 本方法用于分页查询状态正常的教职工信息集合 结果集按sort字段值升序排序
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $offset 偏移量
     * @param int $limit 每页信息数量
     * @return Collection 查询到的结果集
     */
    public function findNormalTeachers($offset, $limit) {
        $departments = $this->where('status', self::STATUS['normal'])
            ->orderBy('sort', 'asc')
            ->offset($offset)
            ->limit($limit)
            ->get();
        return $departments;
    }

    /**
     * 本方法用于计算状态正常的教职工信息的总条数
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int 信息总条数
     */
    public function countNormalTeachers() {
        return $this->where('status', self::STATUS['normal'])->count();
    }
}
