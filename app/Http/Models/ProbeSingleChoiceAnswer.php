<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ProbeSingleChoiceAnswer extends Model {
    /**
     * @const string CREATED_AT 数据创建时间字段
     */
    const CREATED_AT = 'created_time';

    /**
     * @const string UPDATED_AT 数据修改时间字段
     */
    const UPDATED_AT = 'updated_time';

    /**
     * @var string $table 表名
     */
    protected $table = 'probe_single_choice_answer';

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

    public function findByStemIdAndOptionId($stemId, $optionId) {
        return $this->where('stem_id', $stemId)
            ->where('option_id', $optionId)
            ->first();
    }
}