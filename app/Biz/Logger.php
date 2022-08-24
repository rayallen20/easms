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
        'login' => '登录',
        'logout' => '注销',
        'createUser' => '创建系统用户',
        'updateUser' => '更新用户信息',
        'updatePassword' => '修改密码',
        'deleteUser' => '删除用户',
        'createDepartment' => '创建院系',
        'updateDepartment' => '更新院系信息',
        'deleteDepartment' => '删除院系信息',
        'createMajor' => '创建专业',
        'updateMajor' => '更新专业信息',
        'deleteMajor' => '删除专业信息',
        'createTeacher' => '创建教职工',
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

    /**
     * 本方法用于记录注销操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logLogout(){
        $code = 0;
        $this->module = self::MODULES['user'];
        $this->operateType = self::OPERATIONS['logout'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于记录创建用户操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logCreateUser(){
        $code = 0;
        $this->module = self::MODULES['user'];
        $this->operateType = self::OPERATIONS['createUser'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于记录更新用户信息操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logUpdateUser(){
        $code = 0;
        $this->module = self::MODULES['user'];
        $this->operateType = self::OPERATIONS['updateUser'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于记录更新用户密码操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logUpdatePassword(){
        $code = 0;
        $this->module = self::MODULES['user'];
        $this->operateType = self::OPERATIONS['updatePassword'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于记录删除用户操作操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logDeleteUser(){
        $code = 0;
        $this->module = self::MODULES['user'];
        $this->operateType = self::OPERATIONS['deleteUser'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于记录创建院系操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logCreateDepartment(){
        $code = 0;
        $this->module = self::MODULES['department'];
        $this->operateType = self::OPERATIONS['createDepartment'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于记录更新院系操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logUpdateDepartment(){
        $code = 0;
        $this->module = self::MODULES['department'];
        $this->operateType = self::OPERATIONS['updateDepartment'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于记录删除院系操作操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logDeleteDepartment(){
        $code = 0;
        $this->module = self::MODULES['department'];
        $this->operateType = self::OPERATIONS['deleteDepartment'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于记录创建专业操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logCreateMajor(){
        $code = 0;
        $this->module = self::MODULES['department'];
        $this->operateType = self::OPERATIONS['createMajor'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于记录更新专业操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logUpdateMajor(){
        $code = 0;
        $this->module = self::MODULES['department'];
        $this->operateType = self::OPERATIONS['updateMajor'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于记录删除专业操作操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logDeleteMajor(){
        $code = 0;
        $this->module = self::MODULES['department'];
        $this->operateType = self::OPERATIONS['deleteMajor'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    /**
     * 本方法用于记录创建教职工操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logCreateTeacher(){
        $code = 0;
        $this->module = self::MODULES['teacher'];
        $this->operateType = self::OPERATIONS['createTeacher'];
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
