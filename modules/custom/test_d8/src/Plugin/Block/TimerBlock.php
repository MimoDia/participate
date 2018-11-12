<?php

namespace Drupal\test_d8\Plugin\Block;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\test_d8\Helper\FileStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a timer block.
 *
 * @Block(
 *   id = "timer_qcm_test_drupal8",
 *   admin_label = @Translation("Timer Qcm Test Drupal 8"),
 * )
 */
class TimerBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * @var ConfigFactoryInterface
   */
  private $configFatcory;

  /**
   * @var TimeInterface
   */
  private $time;

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('datetime.time')
    );
  }

  /**
   * Constructor.
   *
   * @param   array                   $configuration      A configuration array containing information about the plugin instance.
   * @param   string                  $plugin_id          The plugin ID for the plugin instance.
   * @param   mixed                   $plugin_definition  The plugin implementation definition.
   * @param   ConfigFactoryInterface  $config_factory     The configuration factory service.
   * @param   TimeInterface           $time               The time service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ConfigFactoryInterface $config_factory,
    TimeInterface $time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->configFactory = $config_factory;
    $this->time = $time;
  }

  /**
  * {@inheritdoc}
  */
  public function build() {
    $time = $this->time->getCurrentTime();
    $timeLeft = $this->configFactory
      ->get('test_d8.settings')
      ->get('time_to_complete_test');

    /* /!\ FONCTIONNALITÉ COOKIES
    # override timeLeft if a test has not ended (page refresh or else)
    if (isset($_COOKIE['testD8'])){
      $cookie = unserialize($_COOKIE['testD8']);
      $timeLeft = $cookie['qcm_timer'];
    }
    */

    $build = [];
    $build['#theme'] = 'timer_qcm_test_drupal8';
    $build['#attached']['drupalSettings']['TestD8']['countdown'] = $time + $timeLeft;

    return $build;
  }

}
