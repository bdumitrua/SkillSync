<?php

namespace App\Kafka;

class KafkaService
{
    protected KafkaProducer $producer;

    public function __construct(KafkaProducer $producer)
    {
        $this->producer = $producer;
    }

    // /opt/kafka_2.13-2.8.1/bin/kafka-console-consumer.sh --bootstrap-server localhost:9092 --topic postsLikes --from-beginning
    public function sendPostLike(array $post, array $postAuthor, array $whoLiked)
    {
        $topic = 'postsLikes';

        $data = [
            'post' => $post,
            'postAuthor' => $postAuthor,
            'whoLiked' => $whoLiked,
        ];

        $this->producer->produce($topic, $data);
    }
}
