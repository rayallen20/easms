<?php
namespace App\Biz;

use App\Lib\Resp;
use Kafka\ProducerConfig;

class ProbeAnswer {
    /**
     * @var int $id 作答内容id
    */
    public $id;

    /**
     * @var ProbeTemplate 问卷对象
    */
    public $probe;

    /**
     * @var Student $student 学生对象
    */
    public $student;

    /**
     * @var string $content 作答内容
    */
    public $content;

    /**
     * @var string $createdTime 作答内容创建时间
     */
    public $createdTime;

    /**
     * @var string $updatedTime 作答内容修改时间
     */
    public $updatedTime;

    public function __construct($probe, $student) {
        $this->probe = $probe;
        $this->student = $student;
    }

    public function create($content) {
        $this->content = json_encode($content);
        $code = 0;
        $model = new \App\Http\Models\ProbeAnswer();
        $saveResult = $model->create($this);
        if (!$saveResult) {
            $code = Resp::SAVE_DATABASE_FAILED;
            return $code;
        }
        return $code;
    }

    public function exist() {
        $code = 0;
        $model = new \App\Http\Models\ProbeAnswer();
        $orm = $model->findByProbeIdAndStudentId($this->probe->id, $this->student->id);
        if ($orm != null) {
            $code = Resp::STUDENT_HAS_BEEN_ANSWERED;
            return $code;
        }
        // $this->fill($orm);
        return $code;
    }

    public function fill($orm) {
        $this->id = $orm->id;
        $this->probe = new ProbeTemplate();
        $this->probe->id = $orm->probe_id;
        $this->student = new Student();
        $this->student->id = $orm->student_id;
        $this->content = $orm->content;
        $this->createdTime = explode('.', $orm->created_time)[0];
        $this->updatedTime = explode('.', $orm->updated_time)[0];
    }

    public function send($answers) {
        $config = ProducerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        //  设置broker地址
        $config->setMetadataBrokerList('123.57.222.77:9092');
        //  设置broker的代理版本
        $config->setBrokerVersion('1.0.0');
        //  只需leader确认消息
        $config->setRequiredAck(1);
        //  选择异步
        $config->setIsAsyn(true);
        //  每500毫秒发送消息
        $config->setProduceInterval(500);
        //  创建生产者实例
        $producer = new \Kafka\Producer();
        $producer->send([
            [
                'topic' => 'answer',
                'value' => json_encode($answers),
            ],
        ]);
    }
}
