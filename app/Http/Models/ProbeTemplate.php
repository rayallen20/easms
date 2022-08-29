<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ProbeTemplate extends Model {
    /**
     * @const CREATED_AT 数据创建时间字段
     */
    const CREATED_AT =  'created_time';

    /**
     * @const UPDATED_AT 数据修改时间字段
     */
    const UPDATED_AT = 'updated_time';

    /**
     * @const array USER_STATUS 表示调研模板信息状态的数组 normal:正常 delete:删除
     */
    const STATUS = [
        'normal' => 'normal',
        'delete' => 'delete',
    ];

    /**
     * @var string $table 表名
     */
    protected $table = 'probe_template';

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
     * 本方法用于创建1条probe_template表中的信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Biz\ProbeTemplate $probe 业务层ProbeTemplate对象 表示待创建的调研问卷模板
     * @return bool true表示创建成功 false表示创建失败
     */
    public function create($probe) {
        $this->name = $probe->name;
        $this->start_date = $probe->startDate;
        $this->end_date = $probe->endDate;
        $this->status = self::STATUS['normal'];
        $this->sort = $this->findMaxId() + 1;
        return $this->save();
    }

    /**
     * 本方法用于查找probe_template表中当前最大id值
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $maxId id字段最大值 若probe_template表中无数据 则返回0
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
