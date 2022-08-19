<?php
namespace App\Http\Models;

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
     * @param \App\Biz\Department $department 业务层Department对象 表示待创建的部门信息
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
}
