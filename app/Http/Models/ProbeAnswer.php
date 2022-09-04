<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ProbeAnswer extends Model {
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
    protected $table = 'probe_answer';

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
     * 本方法用于创建1条ProbeAnswer表的信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Biz\ProbeAnswer $probeAnswer 业务层ProbeAnswer对象 表示待创建的作答信息
     * @return bool true表示创建成功 false表示创建失败
    */
    public function create($probeAnswer) {
        $this->probe_id = $probeAnswer->probe->id;
        $this->student_id = $probeAnswer->student->id;
        $this->content = $probeAnswer->content;
        return $this->save();
    }

    /**
     * 本方法用于根据本表的probe_id和student_id字段值查询1条数据
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $probeId 调研问卷id
     * @param int $studentId 学生id
     * @return ProbeAnswer|null
    */
    public function findByProbeIdAndStudentId($probeId, $studentId) {
        return $this->where('probe_id', $probeId)
            ->where('student_id', $studentId)
            ->first();
    }
}
