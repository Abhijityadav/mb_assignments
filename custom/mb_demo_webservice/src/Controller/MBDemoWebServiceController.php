<?php

namespace Drupal\mb_demo_webservice\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;

/**
 * Provides route responses for the Webservice listing page.
 */
class MBDemoWebServiceController extends ControllerBase {

  /**
   * Returns the listing page.
   *
   * @return array
   *   A page markup.
   */
  public function dataDisplay() {
    $render_page = [];
    $webservice_result = \Drupal::service('mb_demo_webservice.data_display')->makeApiCall('https://dummyjson.com/products');
    if ($webservice_result['success']) {
      $render_page = $this->processData($webservice_result['data']);
    }
    else {
      $render_page = 'Error Occured';
    }
    $output = [
      '#type' => '#markup',
      '#markup' => render($render_page)
    ];
    return $output;
  }

  /**
   * Process webservice response.
   */
  public function processData($data) {
    $rows = [];
    foreach ($data["products"] as $info) {
      $rows[] = [$info['title'], $info['brand'], $info['description']];
    }
    $header = [
      'title' => t('Title'),
      'brand' => t('Brand'),
      'description' => t('Description'),
    ];
    $build['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => t('No content has been found.'),
    ];
    return $build;
  }

}
