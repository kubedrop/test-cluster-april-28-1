<?php

/**
 * @file
 * Contains class EventDispatcherKafkaServiceProvider.
 */

namespace Drupal\event_dispatcher_kafka;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Class EventDispatcherKafkaServiceProvider.
 * Overwrite service
 */
class EventDispatcherKafkaServiceProvider extends ServiceProviderBase {
  public function alter(ContainerBuilder $container) {
    $container = $container->getDefinition('event_dispatchers.event_service');
    $container->setClass('Drupal\event_dispatcher_kafka\Kafka');
  }
}
