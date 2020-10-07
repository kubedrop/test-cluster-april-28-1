<?php

namespace Drupal\event_dispatchers;

/**
 * Enumeration of entity event dispatcher types.
 */
class EntityEventDispatcherType {
  const INSERT = 'entity.event.insert';
  const UPDATE = 'entity.event.update';
  const DELETE = 'entity.event.delete';
}
