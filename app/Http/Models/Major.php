<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Major extends Model
{
    /**
     * @const string CREATED_AT 数据创建时间字段
     */
    const CREATED_AT = 'created_time';

    /**
     * @const string UPDATED_AT 数据修改时间字段
     */
    const UPDATED_AT = 'updated_time';

    /**
     * @const array USER_STATUS 表示用户信息状态的数组 normal:正常 delete:删除
     */
    const STATUS = [
        'normal' => 'normal',
        'delete' => 'delete',
    ];

    /**
     * @var string $table 表名
     */
    protected $table = 'major';

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
     * 本方法用于定义本表(major表)与department表之间通过major.department_id和department.id建立的1对1关系
     * @return HasOne
     */
    public function department() {
        return $this->hasOne('App\Http\Models\Major', 'id', 'department_id');
    }

    /**
     * 本方法用于创建1条major表中的信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Biz\Major $major 业务层Major对象 表示待创建的专业信息
     * @return bool true表示创建成功 false表示创建失败
     */
    public function create($major) {
        $this->department_id = $major->department->id;
        $this->name = $major->name;
        $this->status = Major::STATUS['normal'];
        $this->sort = $this->findMaxId() + 1;
        return $this->save();
    }

    /**
     * 本方法用于查找major表中当前最大id值
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
     * 本方法用于分页查询状态正常的专业信息集合 结果集按sort字段值升序排序
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $offset 偏移量
     * @param int $limit 每页信息数量
     * @return Collection 查询到的结果集
     */
    public function findNormalMajors($departmentId, $offset, $limit) {
        $departments = $this->where('department_id', $departmentId)
            ->where('status', self::STATUS['normal'])
            ->orderBy('sort', 'asc')
            ->offset($offset)
            ->limit($limit)
            ->get();
        return $departments;
    }

    /**
     * 本方法用于计算指定院系下状态正常的专业信息的总条数
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $departmentId 指定院系id
     * @return int 信息总条数
     */
    public function countNormalMajors($departmentId) {
        return $this->where('department_id', $departmentId)
            ->where('status', self::STATUS['normal'])
            ->count();
    }

    public function findById($id) {
        return $this->where('id', $id)->first();
    }

    /**
     * 本方法用于更新1条专业信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Major $orm 要更新的orm
     * @param string $name department表的name字段值
     * @return bool 更新结果
     */
    public function updateMajor($orm, $name) {
        $orm->name = $name;
        return $orm->save();
    }
}
