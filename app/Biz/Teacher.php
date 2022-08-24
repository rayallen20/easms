<?php
namespace App\Biz;

class Teacher {

    /**
     * @const array OFFICE_HOLDING_STATUS 教职工任职状态字典
    */
    const OFFICE_HOLDING_STATUS = [
        [
            'code' => 10,
            'display' => '本校',
        ],

        [
            'code' => 20,
            'display' => '外校(境内)',
        ],

        [
            'code' => 30,
            'display' => '外校(境外)',
        ],
    ];

    /**
     * @const array EDUCATION_BACKGROUNDS 教职工学历字典
    */
    const EDUCATION_BACKGROUNDS = [
        [
            'code' => 10,
            'display' => '小学',
        ],

        [
            'code' => 20,
            'display' => '初中',
        ],

        [
            'code' => 30,
            'display' => '高中',
        ],

        [
            'code' => 40,
            'display' => '专科',
        ],

        [
            'code' => 50,
            'display' => '本科',
        ],

        [
            'code' => 60,
            'display' => '研究生',
        ],
    ];

    /**
     * @const array QUALIFICATIONS 教职工学位字典
    */
    const QUALIFICATIONS = [
        [
            'code' => 0,
            'display' => '无'
        ],

        [
            'code' => 10,
            'display' => '学士'
        ],

        [
            'code' => 20,
            'display' => '硕士'
        ],

        [
            'code' => 30,
            'display' => '博士'
        ],
    ];

    /**
     * @const array SOURCES 教职工学缘字典
     */
    const SOURCES = [
        [
            'code' => 10,
            'display' => '本校',
        ],

        [
            'code' => 20,
            'display' => '外校(境内)',
        ],

        [
            'code' => 30,
            'display' => '外校(境外)',
        ],
    ];

    /**
     * @var int $id 教职工id
    */
    public $id;

    /**
     * @var Department $department 教职工所属院系
    */
    public $department;

    /**
     * @var string $jobNumber 教职工工号
    */
    public $jobNumber;

    /**
     * @var string $name 教职工姓名
    */
    public $name;

    /**
     * @var string $gender 教职工性别
    */
    public $gender;

    /**
     * @var string $birthDate 教职工出生日期
    */
    public $birthDate;

    /**
     * @var string $intoSchoolDate 教职工入校时间
    */
    public $intoSchoolDate;

    /**
     * @var int $officeHoldingStatus 教职工任职状态
    */
    public $officeHoldingStatus;

    /**
     * @var int $educationBackground 教职工学历
    */
    public $educationBackground;

    /**
     * @var int $qualification 教职工学位
    */
    public $qualification;

    /**
     * @var int $source 教职工学缘
    */
    public $source;

    /**
     * @var JobTitle $jobTitle 教职工专业技术职称
    */
    public $jobTitle;

    /**
     * @var Subject $subject 教职工学科类别
    */
    public $subject;

    /**
     * @var Politics $politics 教职工政治面貌
    */
    public $politics;

    /**
     * @var Nationality $nationality 教职工国籍
    */
    public $nationality;
}
