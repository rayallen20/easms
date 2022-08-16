<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{
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
    protected $table = 'user';

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
     * 本方法用于定义本表(user表)与role表之间通过user.role_id和role.id建立的1对1关系
    */
    public function role() {
        return $this->hasOne('App\Http\Models\Role', 'id', 'role_id');
    }

    /**
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $account 账户名
     * @param string $password 密码
     * @return User $user
    */
    public function findByAccount($account, $password) {
        $user = $this->where('account', $account)->first();
        return $user;
    }

    /**
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $lastLoginTime 最后登录时间
     * @return bool 更新结果
    */
    public function updateLastLoginTime($lastLoginTime) {
        $this->last_login_time = $lastLoginTime;
        return $this->save();
    }
}
