<?php

namespace Drupal\next_embedded\Plugin\Next\SitePreviewer;

use Drupal\Core\Entity\EntityInterface;
use Drupal\next\Plugin\SitePreviewerBase;

/**
 * Previews Next pages in embedded mode
 *
 * @SitePreviewer(
 *  id = "embedded",
 *  label = "Embedded preview",
 *  description = "Embeds Next pages as preview"
 * )
 */
class Embedded extends SitePreviewerBase {

  /**
   * {@inheritdoc}
   */
  public function render(EntityInterface $entity, array $sites) {
    // Todos
    // Try to fix caching issues - Defer?
    // See if you can run in dev mode - Defer? Or maybe get scripts?
    // Resolve styling conflicts

    $build = [];

    // TODO - Construct request URL dynamically
    $client = \Drupal::httpClient();
    // TODO - In future this should come from config
    $base = "http://192.168.7.149:3000";
    $uri = \Drupal::request()->getRequestUri();
    $response = $client->request('GET', $base . $uri);

    // Parse the response so we can embed the dom elements we need
    $dom = new \DOMDocument();
    // Using @ to supress warnings due to DOMDocument() thinking html5 elements are invalid
    @$dom->loadHTML($response->getBody());
    $next_body = $dom->getElementById('__next');
    $styles = $dom->getElementsByTagName('link');

    foreach ($styles as $style) {
      // Create a link element for the stylesheet
      $link_description = [
        'rel' => 'stylesheet',
        'href' => $base . $style->getAttribute('href'),
      ];

     // Add the link tag to the head
     $attachments['#attached']['html_head_link'][] = [$link_description];
    }

    foreach ($sites as $site) {
      $build[] = [
        '#markup' => $dom->saveHTML($next_body),
        $attachments
      ];
    }

    return $build;
  }

}