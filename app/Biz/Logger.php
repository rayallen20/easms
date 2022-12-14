<?php
namespace App\Biz;

use App\Http\Models\OperateLog;
use App\Lib\Pagination;
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
        'updateTeacher' => '更新教职工信息',
        'deleteTeacher' => '删除教职工信息',
        'createStudent' => '创建学生',
        'updateStudent' => '更新学生信息',
        'deleteStudent' => '删除学生信息',
        'createProbe' => '创建调研模板',
        'updateProbe' => '更新调研模板',
        'deleteProbe' => '删除调研模板',
        'createQuestion' => '创建问题',
        'updateQuestion' => '更新问题',
        'deleteQuestion' => '删除问题',
        'uploadExcel' => '上传表格'
    ];

    public $id;

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

    /**
     * 本方法用于记录更新教职工信息操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logUpdateTeacher(){
        $code = 0;
        $this->module = self::MODULES['teacher'];
        $this->operateType = self::OPERATIONS['updateTeacher'];
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
     * 本方法用于记录删除教职工操作操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logDeleteTeacher(){
        $code = 0;
        $this->module = self::MODULES['teacher'];
        $this->operateType = self::OPERATIONS['deleteTeacher'];
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
     * 本方法用于记录创建学生操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logCreateStudent(){
        $code = 0;
        $this->module = self::MODULES['student'];
        $this->operateType = self::OPERATIONS['createStudent'];
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
     * 本方法用于记录更新学生信息操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logUpdateStudent(){
        $code = 0;
        $this->module = self::MODULES['student'];
        $this->operateType = self::OPERATIONS['updateStudent'];
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
     * 本方法用于记录删除学生操作操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logDeleteStudent(){
        $code = 0;
        $this->module = self::MODULES['student'];
        $this->operateType = self::OPERATIONS['deleteStudent'];
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
     * 本方法用于记录创建调研模板操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logCreateProbe(){
        $code = 0;
        $this->module = self::MODULES['probe'];
        $this->operateType = self::OPERATIONS['createProbe'];
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
     * 本方法用于记录更新调研模板信息操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logUpdateProbe(){
        $code = 0;
        $this->module = self::MODULES['probe'];
        $this->operateType = self::OPERATIONS['updateProbe'];
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
     * 本方法用于记录删除调研模板操作操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logDeleteProbe(){
        $code = 0;
        $this->module = self::MODULES['probe'];
        $this->operateType = self::OPERATIONS['deleteProbe'];
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
     * 本方法用于记录创建问题操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logCreateQuestion(){
        $code = 0;
        $this->module = self::MODULES['probe'];
        $this->operateType = self::OPERATIONS['createQuestion'];
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
     * 本方法用于记录更新问题信息操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logUpdateQuestion(){
        $code = 0;
        $this->module = self::MODULES['probe'];
        $this->operateType = self::OPERATIONS['updateQuestion'];
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
     * 本方法用于记录删除问题操作操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logDeleteQuestion(){
        $code = 0;
        $this->module = self::MODULES['probe'];
        $this->operateType = self::OPERATIONS['deleteQuestion'];
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
     * 本方法用于记录上传学生信息表格操作操作的日志信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $code
     */
    public function logUploadExcel(){
        $code = 0;
        $this->module = self::MODULES['student'];
        $this->operateType = self::OPERATIONS['uploadExcel'];
        $this->operateTime = date('Y-m-d H:i:s');
        $model = new OperateLog();
        $res = $model->log($this);
        if (!$res) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    public function list($currentPage, $itemPerPage) {
        $result = [
            'logs' => [],
            'pagination' => null,
            'code' => 0
        ];

        $pagination = new Pagination($currentPage, $itemPerPage);
        $offset = $pagination->calcOffset();

        $model = new OperateLog();
        $loggerCollection = $model->findLogs($offset, $itemPerPage);
        for ($i = 0; $i < count($loggerCollection) - 1; $i++) {
            $loggerOrm = $loggerCollection[$i];
            $logger = new Logger(null, null, null);
            $logger->fill($loggerOrm);
            $result['logs'][$i] = $logger;
        }

        $totalLoggers = $model->countLoggers();
        $pagination->calcTotalPage($totalLoggers);
        $result['pagination'] = $pagination;
        return $result;
    }

    public function fill($orm) {
        $this->id = $orm->id;
        $this->module = $orm->module;
        $this->operateType = $orm->operate_type;
        $this->operateTime = (string)$orm->operate_time;
        $this->ip = $orm->ip;
        $this->comment = $orm->comment;
        $this->user = new User();
        $this->user->fill($orm->user);
    }
}
