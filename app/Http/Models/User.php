<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class User extends Model{
    /**
     * @const string CREATED_AT 数据创建时间字段
    */
    const CREATED_AT =  'created_time';

    /**
     * @const string UPDATED_AT 数据修改时间字段
     */
    const UPDATED_AT = 'updated_time';

    /**
     * @const array USER_STATUS 表示用户信息状态的数组 normal:正常 delete:删除
    */
    const STATUS = [
        'normal' => 'normal',
        'delete' => 'delete',
    ];

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
    public function findByAccountAndPassword($account, $password) {
        $user = $this->where('account', $account)->where('password', $password)->first();
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

    /**
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id
     * @return User $user
     */
    public function findById($id) {
        $user = $this->where('id', $id)->first();
        return $user;
    }

    /**
     * @access public
     * @author Roach<18410269837@163.com>
     * @param string $account
     * @return User $user
     */
    public function findByAccount($account) {
        $user = $this->where('account', $account)->first();
        return $user;
    }

    /**
     * 本方法用于创建用户
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Biz\User $user 业务层的User对象 表示待创建的用户信息
     * @return bool true表示创建成功 false表示创建失败
    */
    public function create($user) {
        $this->account = $user->account;
        $this->password = $user->password;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->mobile = $user->mobile;
        $this->role_id = $user->role->id;
        $this->status = self::STATUS['normal'];
        $this->sort = $this->findMaxId() + 1;
        return $this->save();
    }

    /**
     * 本方法用于查找user表中当前最大id值
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int $maxId id字段最大值 若user表中无数据 则返回0
    */
    public function findMaxId() {
        $maxId = $this->max('id');
        if ($maxId == null) {
            $maxId = 0;
            return $maxId;
        }
        return $maxId;
    }

    /**
     * 本方法用于分页查询状态正常的用户信息集合 结果集按sort字段值升序排序
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $offset 偏移量
     * @param int $limit 每页信息数量
     * @return Collection 查询到的结果集
    */
    public function findNormalUsers($offset, $limit) {
        $users = $this->where('status', self::STATUS['normal'])
            ->orderBy('sort', 'asc')
            ->offset($offset)
            ->limit($limit)
            ->get();
        return $users;
    }

    /**
     * 本方法用于计算状态正常的用户信息的总条数
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int 信息总条数
    */
    public function countNormalUser() {
        return $this->where('status', self::STATUS['normal'])->count();
    }

    /**
     * 本方法用于更新1条用户信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $id user表id字段值
     * @param string $username user表username字段值
     * @param string $email user表email字段值
     * @param string $mobile user表mobile字段值
     * @return bool 更新结果
    */
    public function updateUser($id, $username, $email, $mobile) {
        $arr = [
            'username' => $username,
            'email' => $email,
            'mobile' => $mobile
        ];
        return $this->where('id', $id)->update($arr);
    }

    /**
     * 本方法用于更新1条用户信息的password字段值
     * @access public
     * @author Roach<18410269837@163.com>
     *
    */
    public function updatePassword($id, $password) {
        $arr = [
            'password' => $password
        ];
        return $this->where('id', $id)->update($arr);
    }
}
