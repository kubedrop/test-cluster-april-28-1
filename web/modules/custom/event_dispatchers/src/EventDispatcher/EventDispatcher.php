<?php

namespace Drupal\event_dispatchers\EventDispatcher;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;

/**
 * EventDispatcher to dispatch event
 *
 * This class extend the drupal event dispatcher and call the service to dispatch the event to external source.
 */
class EventDispatcher extends ContainerAwareEventDispatcher {

  /**
   * EventDispatcher constructor.
   * @param ContainerInterface $container
   * @param array $listeners
   */
  public function __construct(ContainerInterface $container, array $listeners = []) {
    parent::__construct($container, $listeners);
  }

  /**
   * @param string $event_name
   * @param Event|NULL $event
   * @return Event|void|null
   */
  public function dispatch($event_name, Event $event = NULL) {
    parent::dispatch($event_name, $event);

    switch ($event_name) {
      case 'entity.event.update':
      case 'entity.event.insert':
      case 'entity.event.delete':
        $event = $event->getEntity();
      break;
    }

    $service = \Drupal::service('event_dispatchers.event_service');
    $service->send($event_name, $event);
  }
}
