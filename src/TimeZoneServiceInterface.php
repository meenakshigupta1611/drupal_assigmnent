<?php

namespace Drupal\drupal_assignment;

/**
 * Interface TimeZoneServiceInterface.
 */
interface TimeZoneServiceInterface {

  /**
   * Get Current time based on timezone.
   *
   * @return string
   *   Date.
   */
  public function CurrentTime();

}
