<?php

/**
 * @file
 * Contains Drupal\event_dispatcher_kafka\Form\KafkaConfigForm.
 */
namespace Drupal\event_dispatcher_kafka\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\event_dispatcher_kafka\Kafka;

/**
 * Class KafkaConfigForm.
 *
 * @package Drupal\event_dispatcher\Form
 */
class KafkaConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'kafka_config.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kafka_config_setting_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('kafka_config.settings');
    $list = kafka::decodeTopicList($config->get('kafka_topic_event_list'));
    $form['kafka'] = array(
      '#type' => 'fieldset',
      '#title' => t('Configure Kafka'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      '#prefix' => t('Apache Kafka is an open-source stream-processing software platform which is used to handle
       the real-time data storage. It works as a broker between two parties, i.e., a sender and a receiver. It can handle
       about trillions of data events in a day.'),
    );

    $form['kafka']['kafka_enable'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enable Kafka'),
      '#description' => t('click on checkbox to enable kafka configuration.'),
      '#default_value' => $config->get('kafka_enable'),
    );

    $form['kafka']['kafka_topic_event_list'] = array(
      '#type' => 'textarea',
      '#title' => t('Topic Event List'),
      '#description' => t("Enter one value per line, in the format key|value. The key is the topic & value is the event
       name/type."),
      '#placeholder' => t('topic_name|event_name'),
      '#default_value' => $list,
      '#rows' => 10,
      '#required' => TRUE,
    );

    $form['kafka']['kafka_rest_event'] = array(
      '#type' => 'checkbox',
      '#title' => t('Pass other message to kafka'),
      '#description' => t('Click on checkbox to publish rest of the message/event on kafka.'),
      '#default_value' => $config->get('kafka_rest_event'),
    );

    $form['kafka']['kafka_broker_list'] = array(
      '#type' => 'textarea',
      '#title' => t('Servers/Broker List'),
      '#attributes' => array('placeholder' => t('10.0.0.1:9092, 10.0.0.2:9092'),),
      '#description' => t('Enter kafka brokers by comma(,) separate list (10.0.0.1:9092, 10.0.0.2:9092), A broker
      is a Kafka server and Kafka cluster typically consists of multiple brokers to maintain load balance.'),
      '#default_value' => $config->get('kafka_broker_list'),
      '#required' => TRUE,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $list = explode(PHP_EOL, $form_state->getValue('kafka_topic_event_list'));
    $key_value = [];
    foreach ($list as $key => $value) {
      $list_element = explode('|', $value);
      $key_value[$key] = ['topic' => $list_element[0], 'event' => rtrim($list_element[1])];
    }

    $this->config('kafka_config.settings')
      ->set('kafka_enable', $form_state->getValue('kafka_enable'))
      ->set('kafka_topic_event_list', serialize($key_value))
      ->set('kafka_rest_event', $form_state->getValue('kafka_rest_event'))
      ->set('kafka_broker_list', $form_state->getValue('kafka_broker_list'))
      ->save();
  }
}
