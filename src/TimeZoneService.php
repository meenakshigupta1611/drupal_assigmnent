<?php

namespace Drupal\drupal_assignment;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TimeZoneService.
 */
class TimeZoneService implements TimeZoneServiceInterface {


  /**
   * Site Loction config storage.
   *
   * @var \Drupal\Core\Config\Config
   */
  private $configFactory;

  /**
   * Constructs a new TimeZoneService object.
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function CurrentTime() {
    $date = '';
    $timezone = $this->configFactory->get('drupal_assignment.sitelocation')->get('timezone');
    if ($timezone) {
      $dateTime = new DrupalDateTime();
      $dateTime->setTimezone(new \DateTimeZone($timezone));
      $date = $dateTime->format('jS M Y - H:i A');
    }
    return $date;
  }

}
