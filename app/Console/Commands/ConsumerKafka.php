<?php

namespace App\Console\Commands;

use App\Biz\ProbeTemplate;
use App\Biz\Question\Question;
use App\Http\Models\ProbeMultipleChoiceAnswer;
use App\Http\Models\ProbeSingleChoiceAnswer;
use App\Http\Models\ShortAnswer;
use Illuminate\Console\Command;

class ConsumerKafka extends Command {
    protected $signature = 'consumer:kafka';

    protected $description = '消费kafka';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $config = \Kafka\ConsumerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $kafkaUrl = env('KAFKA_URL');
        $config->setMetadataBrokerList($kafkaUrl);
        $config->setGroupId('test2');
        $config->setBrokerVersion('1.0.0');
        $config->setTopics(['answer']);
        $consumer = new \Kafka\Consumer();
        $consumer->start(function($topic, $part, $message) {
            $probe = json_decode($message['message']['value'], true);
            $probeId = $probe['id'];
            $answers = $probe['answers'];
            $probeBiz = new ProbeTemplate();
            $code = $probeBiz->exist($probeId);
            if ($code != 0) {
                return;
            }
            $orms = [];
            foreach ($answers as $answer) {
                $type = $answer['type'];
                $questionId = $answer['id'];
                if ($type == Question::TYPES['shortQuestion']) {
                    $orm = self::createShortAnswerOrm($questionId, $answer['answer']);
                    array_push($orms, $orm);
                }

                if ($type == Question::TYPES['singleChoice']) {
                    $optionId = $answer['answer'];
                    $orm = self::createSingleChoiceAnswerOrm($probeId, $questionId, $optionId);
                    array_push($orms, $orm);
                }

                if ($type == Question::TYPES['multipleChoice']) {
                    $optionIds = $answer['answer'];
                    foreach ($optionIds as $optionId) {
                        $orm = self::createMultipleChoiceAnswer($probeId, $questionId, $optionId);
                        array_push($orms, $orm);
                    }
                }
            }

            $result = $probeBiz->answer($orms);
            var_dump($result);
            return;
        });
    }

    private function createShortAnswerOrm($questionId, $content) {
        $orm = new ShortAnswer();
        $orm->createOrm($questionId, $content);
        return $orm;
    }

    private function createSingleChoiceAnswerOrm($probeId, $questionId, $optionId) {
        $model = new ProbeSingleChoiceAnswer();
        $orm = $model->findByStemIdAndOptionId($questionId, $optionId);
        if ($orm != null) {
            $orm->be_chosen_number += 1;
            return $orm;
        } else {
            $model->probe_id = $probeId;
            $model->stem_id = $questionId;
            $model->option_id = $optionId;
            $model->be_chosen_number = 1;
            return $model;
        }
    }

    private function createMultipleChoiceAnswer($probeId, $questionId, $optionId) {
        $model = new ProbeMultipleChoiceAnswer();
        $orm = $model->findByStemIdAndOptionId($questionId, $optionId);
        if ($orm != null) {
            $orm->be_chosen_number += 1;
            return $orm;
        } else {
            $model->probe_id = $probeId;
            $model->stem_id = $questionId;
            $model->option_id = $optionId;
            $model->be_chosen_number = 1;
            return $model;
        }
    }
}
