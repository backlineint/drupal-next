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
    $build = [];

    // TODO - Construct request URL dynamically
    $client = \Drupal::httpClient();
    $response = $client->request('GET', 'http://10.8.12.244:3000/blog/example-article');
  
    list($head, $rest) = preg_split('/(?=<\/head>)/', $response->getBody());
    list($body, $end) = preg_split('/(?=<\/body>)/', $rest);

    foreach ($sites as $site) {
      $build[] = [
        // '#type' => 'link',
        // '#title' => $this->t('Open preview'),
        // '#url' => $site->getPreviewUrlForEntity($entity),
        '#markup' => $body,
      ];
    }

    return $build;
  }

}