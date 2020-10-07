CONTENTS OF THIS FILE
---------------------
 * Introduction
 * Requirement
 * Installation
 * Configuration
 * Maintainers

INTRODUCTION
------------

 * This module provides event dispatcher and kafka services to publish drupal event as kafka topic and messages.
 * Apache Kafka is an open-source stream-processing software platform which is used to handle the real-time data storage.
 * It works as a broker between two parties, i.e., a sender and a receiver. It can handle about trillions of data events
 * in a day.

REQUIREMENTS
------------

  * PHP 5.6 and above, libkafka v0.11.0, php extension rdkafka-3.0.3

INSTALLATION
------------
 * Install as you would normally install a contributed drupal module. See:
   https://www.drupal.org/documentation/install/modules-themes/modules-8
   for further information.

CONFIGURATION
-------------
 * After installation of module Clear all cache first (Important to do this).
 * To configure kafka go to /admin/config/system/kafka_configuration.
 * Click on kafka enable checkbox to activate kafka services.
 * Kafka Prefix prepend to drupal event name and publish as topic on kafka server.
 * A broker is a Kafka server and Kafka cluster typically consists of multiple brokers to maintain load balance.

MAINTAINER
-----------
Current maintainers:
  * Jayjeet Jadeja (j2r) - https://www.drupal.org/u/j2r
  * Chandravilas Kute (chandravilas) - https://www.drupal.org/u/chandravilas
