<?php
namespace App\Biz;

class Politics {
    /**
     * @var int $id 政治面貌id
     */
    public $id;

    /**
     * @var int $code 政治面貌编码
     */
    public $code;

    /**
     * @var string $name 政治面貌名称
     */
    public $name;

    /**
     * @var int $sort 排序字段
     */
    public $sort;

    /**
     * 本方法用于列表展示政治面貌信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return array<Politics> $politics
     */
    public function list() {
        $politics = [];
        $model = new \App\Http\Models\Politics();
        $politicsOrms = $model->findNormals();
        for ($i = 0; $i <= count($politicsOrms) - 1; $i++) {
            $politicsOrm = $politicsOrms[$i];
            $politic = new Politics();
            $politic->fill($politicsOrm);
            $politics[$i] = $politic;
        }
        return $politics;
    }

    /**
     * 本方法用于根据politics表的ORM填充Biz层的Politics对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\Politics $model 政治面貌信息ORM
     * @return void
     */
    public function fill($model) {
        $this->id = $model->id;
        $this->code = $model->code;
        $this->name = $model->name;
        $this->sort = $model->sort;
    }
}
