<?php

namespace App\Kafka;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Enqueue\RdKafka\RdKafkaConnectionFactory;
use App\Exceptions\KafkaProducerException;

class KafkaProducer
{
    protected $context;

    /**
     *
     * @param RdKafkaConnectionFactory $connectionFactory
     * @param string $topicName
     */
    public function __construct(RdKafkaConnectionFactory $connectionFactory)
    {
        $this->context = $connectionFactory->createContext();
    }

    /**
     * Отправляет сообщение в Kafka.
     *
     * @param string $topicName
     * @param mixed $messageData
     * @return void
     * @throws KafkaProducerException
     */
    public function produce(string $topicName, $messageData): void
    {
        if (app()->environment('testing')) {
            return;
        }

        try {
            $topic = $this->context->createTopic($topicName);
            $message = $this->context->createMessage(json_encode($messageData), ['uuid' => Str::uuid()]);

            Log::info("Sending message in {$topicName} topic", [
                'body' => $message->getBody(),
                'properties' => $message->getProperties()
            ]);

            $this->context->createProducer()->send($topic, $message);
        } catch (\Exception $e) {
            Log::error("Error sending message in {$topicName} topic", [
                'error' => $e->getMessage(),
                'data' => $messageData
            ]);

            throw new KafkaProducerException('Error producing message to Kafka', 0, $e);
        }
    }
}
