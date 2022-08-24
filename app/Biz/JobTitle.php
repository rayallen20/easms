<?php
namespace App\Biz;

class JobTitle {
    /**
     * @var int $id 专业技术职称id
    */
    public $id;

    /**
     * @var int $code 专业技术职称编码
    */
    public $code;

    /**
     * @var string $name 专业技术职称名称
    */
    public $name;

    /**
     * @var int $sort 排序字段
    */
    public $sort;

    /**
     * 本方法用于列表展示专业技术职称信息
     * @access public
     * @author Roach<18410269837@163.com>
     * @return array<JobTitle> $jobTitles
    */
    public function list() {
        $jobTitles = [];
        $model = new \App\Http\Models\JobTitle();
        $jobTitleOrms = $model->findNormals();
        for ($i = 0; $i <= count($jobTitleOrms) - 1; $i++) {
            $jobTitleOrm = $jobTitleOrms[$i];
            $jobTitle = new JobTitle();
            $jobTitle->fill($jobTitleOrm);
            $jobTitles[$i] = $jobTitle;
        }
        return $jobTitles;
    }

    /**
     * 本方法用于根据job_title表的ORM填充Biz层的JobTitle对象
     * @access public
     * @author Roach<18410269837@163.com>
     * @param \App\Http\Models\JobTitle $model 专业技术职称信息ORM
     * @return void
    */
    public function fill($model) {
        $this->id = $model->id;
        $this->code = $model->code;
        $this->name = $model->name;
        $this->sort = $model->sort;
    }
}
