<?php

namespace Drupal\drupal_assignment\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\drupal_assignment\TimeZoneServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Provides a 'SiteLocationBlock' block.
 *
 * @Block(
 *  id = "site_location_block",
 *  admin_label = @Translation("Site location block"),
 * )
 */
class SiteLocationBlock extends BlockBase implements ContainerFactoryPluginInterface, TrustedCallbackInterface {

  /**
   * Site Loction config storage.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * The Timezone service.
   *
   * @var Drupal\drupal_assignment\TimeZoneServiceInterface
   */
  protected $timeZoneService;

  /**
   * Constructs a SiteLocationBlock object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $configFactory, TimeZoneServiceInterface $timeZoneService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $configFactory;
    $this->timeZoneService = $timeZoneService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('drupal_assignment.timezone'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $country = $this->configFactory->get('drupal_assignment.sitelocation')->get('country');
    $city = $this->configFactory->get('drupal_assignment.sitelocation')->get('city');
    $build = [
      '#theme' => 'site_location_block',
      '#country' => $country,
      '#city' => $city,
      '#current_time' => [
        '#lazy_builder' => [
          static::class . '::lazyBuilder', [],
        ],
        '#create_placeholder' => TRUE,
      ],
    ];
    return $build;
  }

  /**
   * Site Location Block #lazy_builder callback.
   *
   * @return array
   *   A renderable array with content to replace the #lazy_builder
   */
  public static function lazyBuilder() {
    // @todo Currently, Called service directly as we are using static callback function here and DI was not working properly for this .There's probably a better way to do this.
    $time = \Drupal::service('drupal_assignment.timezone')->CurrentTime();
    $build = [
      'lazy_builder_time' => [
        '#markup' => $time,
      ],
    ];
    return $build;
  }

  /**
   * {@inheritDoc}
   */
  public static function trustedCallbacks() {
    return ['lazyBuilder'];
  }

}
