<?php
namespace App\Http\Models;

use App\Biz\Logger;
use Illuminate\Database\Eloquent\Model;

class OperateLog extends Model {
    /**
     * @const CREATED_AT 数据创建时间字段
     */
    const CREATED_AT =  'created_time';

    /**
     * @const UPDATED_AT 数据修改时间字段
     */
    const UPDATED_AT = 'updated_time';

    /**
     * @var string $table 表名
     */
    protected $table = 'operate_log';

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
     * 本方法用于记录日志
     * @access public
     * @author Roach<18410269837@163.com>
     * @param Logger $logger 日志对象
     * @return bool 落盘结果
    */
    public function log($logger) {
        $this->module = $logger->module;
        $this->operate_type = $logger->operateType;
        $this->operate_time = $logger->operateTime;
        $this->user_id = $logger->user->id;
        $this->ip = $logger->ip;
        $this->comment = $logger->comment;
        return $this->save();
    }
}
