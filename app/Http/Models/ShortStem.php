<?php
namespace App\Http\Models;

use App\Biz\Question\ShortQuestion\ShortQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShortStem extends Model {
    /**
     * @const string CREATED_AT 数据创建时间字段
     */
    const CREATED_AT = 'created_time';

    /**
     * @const string UPDATED_AT 数据修改时间字段
     */
    const UPDATED_AT = 'updated_time';

    /**
     * @const array USER_STATUS 表示简答题信息状态的数组 normal:正常 delete:删除
     */
    const STATUS = [
        'normal' => 'normal',
        'delete' => 'delete',
    ];

    /**
     * @var string $table 表名
     */
    protected $table = 'short_stem';

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
     * 本方法用于使用事务创建1条简答题数据同时更新调研模板中的问题数量
     * @param ProbeTemplate $probeOrm
     * @param ShortQuestion $shortQuestion 业务层简答题对象
     * @return bool true表示创建成功 false表示创建失败
     * @throws \Exception $e
     */
    public function create($probeOrm, $shortQuestion) {
        DB::beginTransaction();
        try {
            // 调研模板问题数量+1
            $probeOrm->topic_number += 1;
            $probeOrm->save();

            // 创建题目
            $this->probe_id = $shortQuestion->probe->id;
            $this->content = $shortQuestion->stem;
            $this->answer_type = $shortQuestion->answerType;
            $this->status = self::STATUS['normal'];
            $this->sort = $shortQuestion->sort;
            $this->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function findById($id) {
        return $this->where('id', $id)->first();
    }

    /**
     * 本方法用于更新1条简答题信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param ShortStem $orm 要更新的orm
     * @param ShortQuestion $biz 业务层简答题对象 表示待更新简答题
     * @return bool 更新结果
     */
    public function updateShortStem($orm, $biz) {
        $orm->content = $biz->stem;
        $orm->answer_type = $biz->answerType;
        return $orm->save();
    }
}
