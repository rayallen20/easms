<?php
namespace App\Http\Controllers\v1;

use App\Biz\JobTitle;
use App\Biz\Nationality;
use App\Biz\Politics;
use App\Biz\Subject;
use App\Biz\Teacher;
use App\Http\Controllers\Controller;
use App\Lib\Resp;

class TeacherController extends Controller {
    /**
     * 本方法用于显示所有任职状态
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
    */
    public function showOfficeHolding() {
        $data = Teacher::OFFICE_HOLDING_STATUS;
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有学历
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
    */
    public function showEducationBackground() {
        $data = Teacher::EDUCATION_BACKGROUNDS;
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有学位
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
    */
    public function showQualification() {
        $data = Teacher::QUALIFICATIONS;
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有学缘
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
    */
    public function showSource() {
        $data = Teacher::SOURCES;
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有专业技术职称
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
    */
    public function showJobTitle() {
        $jobTitleBiz = new JobTitle();
        $jobTitleCollection = $jobTitleBiz->list();
        $data = [];
        for ($i = 0; $i <= count($jobTitleCollection) - 1; $i++) {
            $jobTitle = [
                'id' => $jobTitleCollection[$i]->id,
                'code' => $jobTitleCollection[$i]->code,
                'name' => $jobTitleCollection[$i]->name,
            ];
            $data[$i] = $jobTitle;
        }
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有学科类别
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
     */
    public function showSubject() {
        $subjectBiz = new Subject();
        $subjectCollection = $subjectBiz->list();
        $data = [];
        for ($i = 0; $i <= count($subjectCollection) - 1; $i++) {
            $subject = [
                'id' => $subjectCollection[$i]->id,
                'code' => $subjectCollection[$i]->code,
                'name' => $subjectCollection[$i]->name,
            ];
            $data[$i] = $subject;
        }
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有政治面貌
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
     */
    public function showPolitics() {
        $politicsBiz = new Politics();
        $politicsCollection = $politicsBiz->list();
        $data = [];
        for ($i = 0; $i <= count($politicsCollection) - 1; $i++) {
            $subject = [
                'id' => $politicsCollection[$i]->id,
                'code' => $politicsCollection[$i]->code,
                'name' => $politicsCollection[$i]->name,
            ];
            $data[$i] = $subject;
        }
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }

    /**
     * 本方法用于显示所有国籍
     * @access public
     * @author Roach<18410269837@163.com>
     * @return string $json
     */
    public function showNationality() {
        $nationalityBiz = new Nationality();
        $nationalityCollection = $nationalityBiz->list();
        $data = [];
        for ($i = 0; $i <= count($nationalityCollection) - 1; $i++) {
            $nationality = [
                'id' => $nationalityCollection[$i]->id,
                'code' => $nationalityCollection[$i]->code,
                'name' => $nationalityCollection[$i]->name,
            ];
            $data[$i] = $nationality;
        }
        $resp = new Resp();
        $json = $resp->success($data);
        return $json;
    }
}
