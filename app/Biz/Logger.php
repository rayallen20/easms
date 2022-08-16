<?php
namespace App\Biz;

use App\Http\Models\OperateLog;
use App\Lib\Resp;

class Logger {
    /**
     * @const MODULES 功能模块映射关系
     */
    const MODULES = [
        'user' => '用户模块',
        'department' => '院系模块',
        'teacher' => '教职工模块',
        'student' => '学生模块',
        'probe' => '调研模板模块'
    ];

    /**
     * @const array $operations 操作类型映射关系
     */
    const OPERATIONS = [
        'login' => '登录'
    ];

    /**
     * @var string $module 操作所属模块
    */
    public $module;

    /**
     * @var string $operateType 操作对应的功能类型
    */
    public $operateType;

    /**
     * @var string $operateTime 执行操作的时间
     */
    public $operateTime;

    /**
     * @var string $ip 执行操作的IP地址
    */
    public $ip;

    /**
     * @var User $user 操作用户
    */
    public $user;

    /**
     * @var string $comment 备注信息
     */

    public $comment;

    public function __construct($ip, $user, $comment) {
        $this->ip = $ip;
        $this->user = $user;
        $this->comment = $comment;
    }

    /**
     * 本方法用于记录登录操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
    */
    public function logLogin(){
        $code = 0;
        $this->module = self::MODULES['user'];
        $this->operateType = self::OPERATIONS['login'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }
}
