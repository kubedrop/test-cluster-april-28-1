<?php

/**
 * @file
 * Contains class kafka.
 */

namespace Drupal\event_dispatcher_kafka;

use Drupal\Core\Config\ConfigFactoryInterface;
use RdKafka\Producer;

/**
 * Kafka
 *
 * Manages and produce kafka topics.
 */
class Kafka {

  /**
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  private $config_factory;

  /**
   * @var Producer
   */
  private $rdkafka;

  /**
   * KafkaServices constructor.
   * @param ConfigFactoryInterface $config_factory
   * @param Producer $rdkafka
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config_factory = $config_factory->get('kafka_config.settings')->getRawData();
    $this->rdkafka = new Producer;
    $this->rdkafka->addBrokers($this->config_factory['kafka_broker_list']);
  }

  /**
   * @param $event_name
   * @param $event
   */
  public function send($event_name, $event) {
    // Check for kafka enable or not and early return
    if (!$this->config_factory['kafka_enable']) return;
    // Get list of event topics
    $list = self::getList($this->config_factory['kafka_topic_event_list']);
    $payload = [
      'data' => (array)$event,
    ];

    try {
      foreach ($list['list'] as $value) {
        if($event_name === $value['event']) {
          $kafka_topic = $this->rdkafka->newTopic($value['topic']);
          $kafka_topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($payload), 0);
        }
      }

      if ($this->config_factory['kafka_rest_event'] && (!in_array($event_name, $list['events']))) {
        $kafka_topic = $this->rdkafka->newTopic($event_name);
        $kafka_topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($payload), 0);
      }
    } catch (\Exception $e) {
      // Generic exception handling if something else gets thrown.
      \Drupal::logger('event_dispatcher_kafka')->error($e->getMessage());
    }
  }

  /**
   * @param $list
   * @return string|void
   */
  public static function decodeTopicList($list) {
    if (!$list) return;
    $list = unserialize($list);
    // Loop initiated
    $list_str = [];
    foreach($list as $key => $value) {
      $list_str[] = implode('|',$value);
    }

    return implode(PHP_EOL, $list_str);
  }

  /**
   * @param $list
   * @return mixed|void
   */
  public static function getList($list) {
    if (!$list) return;
    $list = unserialize($list);
    $events = [];
    foreach ($list as $key => $value) {
      $events[] = $value['event'];
    }

    return ['list' => $list,'events' => $events];
  }
}
