<?php
namespace App\Biz;

class Role {
    /**
     * @var int $id 角色id
    */
    public $id;

    /**
     * @var string $name 角色名称
    */
    public $name;

    /**
     * 本方法用于根据id判断对应角色是否存在
     * @access public
     * @return bool true表示存在 false表示不存在
    */
    public function exist() {
        $model = new \App\Http\Models\Role();
        $roleOrm = $model->findById($this->id);
        if ($roleOrm == null) {
            return false;
        }
        return true;
    }
}
