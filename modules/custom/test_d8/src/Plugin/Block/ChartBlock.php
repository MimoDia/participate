<?php

namespace Drupal\test_d8\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a chart block.
 *
 * @Block(
 *   id = "chart_tests_drupal8",
 *   admin_label = @Translation("Dashboard Test Drupal 8 (Chart)"),
 * )
 */
class ChartBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * The entity type manager service.
   * @var EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * The configuration factory service.
   * @var ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * The current request.
   * @var Request
   */
  private $request;

  /**
   * The current user.
   * @var AccountProxyInterface
   */
  private $user;

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
        $container->get('entity_type.manager'),
        $container->get('config.factory'),
        $container->get('request_stack')->getCurrentRequest(),
        $container->get('current_user')
      );
  }

  /**
   * Constructor.
   *
   * @param   array                       $configuration        A configuration array containing information about the plugin instance.
   * @param   string                      $plugin_id            The plugin ID for the plugin instance.
   * @param   mixed                       $plugin_definition    The plugin implementation definition.
   * @param   EntityTypeManagerInterface  $entity_type_manager  The entity type manager service.
   * @param   ConfigFactoryInterface      $config_factory       The configuration factory service.
   * @param   Request                     $request              The current request.
   * @param   AccountProxyInterface       $user                 The current user.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    Request $request,
    AccountProxyInterface $user
    ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->request = $request;
    $this->user = $user;
  }

  /**
  * {@inheritdoc}
  */
  public function build() {
    $themes = $this->getThemes();

    $config = $this->configFactory->get('test_d8.settings');
    $numberOfQuestions = $config->get('number_of_questions');

    $build['#theme'] = 'chart_tests_drupal8';

    $jsData = [];
    $hasAtLeastOneScore = false;
    $chartColors = $config->get('chart_colors');

    $i = 0;
    foreach ($themes as $themeId => $themeName){
      $scores = $this->getScoresByTheme($themeId);

      if (!empty($scores)){
        $hasAtLeastOneScore = true;

        $dataPoints = [];
        foreach ($scores as $value){
          # Date (* 1000 to return microseconds, just like the js method
          # Date.UTC(year, month, day)).
          $date = $value['date_test'] * 1000;
          $dataPoints[] = [$date, (float)$value['score']];
        }

        $object = new \stdClass;
        $object->name = $themeName;
        $object->data = $dataPoints;
        $object->color = $chartColors[$i];
        ++$i;

        $jsData[] = $object;
      }
    }

    $build['#attached']['drupalSettings']['TestD8']['chart']['data'] = $jsData;
    $build['#data']['anytest'] = $hasAtLeastOneScore;

    # Avoid displaying test results on manager's profile.
    $build['#data']['manager_profile'] = false;
    list(, $path, $uid) = explode('/', $this->request->getpathInfo());
    if (('user' == $path) && in_array('manager', User::load($uid)->getRoles())){
      $build['#data']['manager_profile'] = true;
    }

    # TODO: Pas de cache pour ce bloc.
    //$build['#cache']['max-age'] = 0;
    //\Drupal::service('page_cache_kill_switch')->trigger();

    return $build;
  }

  protected function getThemes() {
    $nodeStorage = $this->entityTypeManager->getStorage('node');
    $ids = $nodeStorage->getQuery()->condition('type', 'test')->execute();
    $allThemes = $nodeStorage->loadMultiple($ids);

    $themes = [];
    foreach($allThemes as $d){
      $themes[$d->id()] = $d->getTitle();
    }
    ksort($themes);
    return $themes;
  }

  # Returns current user's tests (filtered by theme).l
  protected function getScoresByTheme($themeId){
    $nodeStorage = $this->entityTypeManager->getStorage('node');
    $ids = $nodeStorage->getQuery()
      ->condition('type','score')
      ->condition('uid', $this->user->id())
      ->condition('field_score_nid', $themeId)
      ->sort('created', 'ASC')
      ->execute();
    $nodes = $nodeStorage->loadMultiple($ids);
    $result = [];
    foreach ($nodes as $id => $obj){
      $result[] = [
        'score' => $obj->get('field_score_result')->getValue()[0]['value'],
        'date_test' => $obj->get('created')->getValue()[0]['value'],
      ];
    }
    return $result;
  }

}
