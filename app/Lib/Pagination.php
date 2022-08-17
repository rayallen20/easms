<?php
namespace App\Lib;

class Pagination {
    /**
     * @var int $currentPage 当前页数
    */
    public $currentPage;

    /**
     * @var int $itemPerPage 每页显示信息条数
    */
    public $itemPerPage;

    /**
     * @var int $totalPage 总页数
    */
    public $totalPage;

    public function __construct($currentPage, $itemPerPage) {
        $this->currentPage = $currentPage;
        $this->itemPerPage = $itemPerPage;
    }

    /**
     * 本方法用于根据信息总条数计算总页数
     * @access public
     * @author Roach<18410269837@163.com>
     * @param int $itemTotalNum 信息总条数
     * @return void
    */
    public function calcTotalPage($itemTotalNum) {
        $this->totalPage = ceil($itemTotalNum / $this->itemPerPage);
    }

    /**
     * 本方法用于根据当前页数和每页显示信息条数计算偏移量
     * @access public
     * @author Roach<18410269837@163.com>
     * @return int 偏移量
    */
    public function calcOffset() {
        return ($this->currentPage - 1) * $this->itemPerPage;
    }
}
