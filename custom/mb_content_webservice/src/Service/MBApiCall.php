<?php

namespace Drupal\mb_demo_webservice\Service;

use GuzzleHttp\Client;
use Drupal\Component\Serialization\Json;


/**
 * Class MBApiCall.
 *
 * @package Drupal\mb_demo_webservice.
 */
class MBApiCall {

  /**
   * HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  private $client;

  /**
   * Constructor.
   *
   * @param \GuzzleHttp\Client $client
   *   HTTP client.
   */
  public function __construct(
    Client $client) {
    $this->client = $client;
  }

  /**
   * Call to the service end-point.
   *
   * @param string $route
   *   String having URL for API.
   *
   * @return array
   *   Returns array of response.
   */
  public function makeApiCall(string $route): array {
    $api_call_result = [];
    try {
      // Make API call.
      $api_call_result['success'] = TRUE;
      $api_call_result['data'] = $this->apiCall($route);
      return $api_call_result;
    }
    catch (Exception $ex) {
      $api_call_result['success'] = FALSE;
      $this->addLog('error', 'API call failed with error' . $ex->getMessage());

      $message_display = $this->getMessageMode();
      if ($message_display) {
        $this->messenger->addMessage($this->getFailureMessage(), 'warning');
      }
    }
    return $api_call_result;
  }

  /**
   * Make API call.
   *
   * @param string $route
   *   String having URL for API.
   *
   * @return array
   *   Returns array of response.
   */
  private function apiCall(string $route): array {
      $client = $this->client;
      $response_json = $client->get($route, [
          'headers' => [
            'content-type' => 'application/json',
          ]
        ])->getBody()->getContents();
      
      $response = Json::decode($response_json);
      return $response;
  }

}
