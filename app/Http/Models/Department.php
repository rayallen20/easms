<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model {
    /**
     * @const string CREATED_AT 数据创建时间字段
     */
    const CREATED_AT =  'created_time';

    /**
     * @const string UPDATED_AT 数据修改时间字段
     */
    const UPDATED_AT = 'updated_time';

    /**
     * @const array STATUS 表示院系信息状态的数组 normal:正常 delete:删除
     */
    const STATUS = [
        'normal' => 'normal',
        'delete' => 'delete',
    ];

    /**
     * @var string $table 表名
     */
    protected $table = 'department';

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
     * 本方法用于定义本表(department表)与major表之间通过department.id和major.department_id建立的1对多关系
     * @return HasMany
    */
    public function majors() {
        return $this->hasMany('App\Http\Models\Major', 'department_id', 'id');
    }

    /**
     * 本方法用于创建1条department表中的信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Biz\Department $department 业务层Department对象 表示待创建的院系信息
     * @return bool true表示创建成功 false表示创建失败
    */
    public function create($department) {
        $this->name = $department->name;
        $this->principal_name = $department->principalName;
        $this->principal_mobile = $department->principalMobile;
        $this->status = self::STATUS['normal'];
        $this->sort = $this->findMaxId() + 1;
        return $this->save();
    }

    /**
     * 本方法用于查找department表中当前最大id值
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $maxId id字段最大值 若department表中无数据 则返回0
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
     * 本方法用于分页查询状态正常的院系信息集合 结果集按sort字段值升序排序
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $offset 偏移量
     * @param int $limit 每页信息数量
     * @return Collection 查询到的结果集
     */
    public function findNormalDepartments($offset, $limit) {
        $departments = $this->where('status', self::STATUS['normal'])
            ->orderBy('sort', 'asc')
            ->offset($offset)
            ->limit($limit)
            ->get();
        return $departments;
    }

    /**
     * 本方法用于计算状态正常的院系信息的总条数
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int 信息总条数
     */
    public function countNormalDepartments() {
        return $this->where('status', self::STATUS['normal'])->count();
    }

    public function findById($id) {
        return $this->where('id', $id)->first();
    }

    /**
     * 本方法用于更新1条院系信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Department $orm 要更新的orm
     * @param string $name department表的name字段值
     * @param string $principalName department表的principal_name字段值
     * @param string $principalMobile department表的principal_mobile字段值
     * @return bool 更新结果
     */
    public function updateDepartment($orm, $name, $principalName, $principalMobile) {
        $orm->name = $name;
        $orm->principal_name = $principalName;
        $orm->principal_mobile = $principalMobile;
        return $orm->save();
    }

    /**
     * 本方法用于更新1条院系信息的状态
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Department $orm 要更新的orm
     * @param string $status 更新后的status字段值
     * @return bool 更新结果
    */
    public function updateStatus($orm, $status) {
        $orm->status = $status;
        return $orm->save();
    }
}
