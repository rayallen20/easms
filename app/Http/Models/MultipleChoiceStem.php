<?php
namespace App\Http\Models;

use App\Biz\Question\ChoiceQuestion\MultipleChoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class MultipleChoiceStem extends Model {
    /**
     * @const string CREATED_AT 数据创建时间字段
     */
    const CREATED_AT = 'created_time';

    /**
     * @const string UPDATED_AT 数据修改时间字段
     */
    const UPDATED_AT = 'updated_time';

    /**
     * @const array USER_STATUS 表示多选题信息状态的数组 normal:正常 delete:删除
     */
    const STATUS = [
        'normal' => 'normal',
        'delete' => 'delete',
    ];

    /**
     * @var string $table 表名
     */
    protected $table = 'multiple_choice_stem';

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
     * 本方法用于定义本表(multiple_choice_stem表)与multiple_choice_option表之间通过multiple_choice_stem.id和multiple_choice_option.stem_id建立的1对多关系
     * @return HasMany
     */
    public function options() {
        return $this->hasMany('App\Http\Models\MultipleChoiceOption', 'stem_id', 'id');
    }

    /**
     * 本方法用于使用事务创建1条单选题数据
     * 事务:
     * 1. 调研模板问题数量+1
     * 2. 创建单选题
     * 3. 创建选项
     * @param ProbeTemplate $probeOrm 调研模板ORM
     * @param MultipleChoice $multipleChoice 多选题业务层对象
     * @return bool true表示创建成功 false表示创建失败
     * @throws \Exception $e
     */
    public function create($probeOrm, $multipleChoice) {
        DB::beginTransaction();
        try {
            // 调研模板问题数量+1
            $probeOrm->topic_number += 1;
            $probeOrm->save();

            // 创建多选题
            $this->probe_id = $multipleChoice->probe->id;
            $this->content = $multipleChoice->stem;
            $this->sort = $multipleChoice->sort;
            $this->display_type = $multipleChoice->displayType;
            $this->status = self::STATUS['normal'];
            $this->save();

            // 创建选项
            foreach ($multipleChoice->options as $option) {
                $optionOrm = new MultipleChoiceOption();
                $optionOrm->stem_id = $this->id;
                $optionOrm->content = $option->content;
                $optionOrm->sort = $option->sort;
                $optionOrm->status = MultipleChoiceOption::STATUS['normal'];
                $optionOrm->save();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
