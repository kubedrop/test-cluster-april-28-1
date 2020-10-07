<?php

/**
 * @file providing the Event Services.
 *
 * This class is placeholder service and this is overwriting at other place in module
 */
namespace Drupal\event_dispatchers\Services;

class EventServices {

  /**
   * @param $event_name
   * @param $event
   * @return bool
   */
  public function send($event_name, $event) {
    return TRUE;
  }
}
