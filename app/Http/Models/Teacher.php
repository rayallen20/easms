<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

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
        $this->gender = $teacher->gender;
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
}
