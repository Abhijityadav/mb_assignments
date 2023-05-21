<?php

namespace Drupal\mb_content_webservice\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\Core\Cache\CacheableMetadata;

/**
 * Provides a Demo Resource for Content
 *
 * @RestResource(
 *   id = "get_books",
 *   label = @Translation("Demo Resource of Content"),
 *   uri_paths = {
 *     "canonical" = "/mb_content_webservice/get-books"
 *   }
 * )
 */
class MBDemoResource extends ResourceBase {

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    $result = [];

    // Query to fetch nodes of type book which are published.
    $nids = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', 'book_detail')
      ->execute();
    $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
    if (count($nodes) > 0) {
      $book_arr = [];
      $index = 0;
      foreach ($nodes as $node) {
        $book_arr[$index]['book_id'] = $node->id();
        $book_arr[$index]['book_title'] = $node->getTitle();
        $book_arr[$index]['book_description'] = $node->body->value;
        $index++;
      }
      $result['result'] = ['count' => count($nodes), 'books' => $book_arr];
    } else {
      $result['result'] = ['count' => 0];
    }
    $response = new ResourceResponse($result, 200);
    $cache_meta_data = new CacheableMetadata();
    $cache_meta_data->setCacheMaxAge(2);
    $response->addCacheableDependency($page);
    $response->addCacheableDependency($cache_meta_data);
    return $response;
  }

}
