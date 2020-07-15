<?php
/**
 * @file
 * contains \Drupal\blcytoscape\Plugin\Block\BLCSDisplayBlock
 */
namespace Drupal\blcytoscape\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\blcytoscape\Controller\BLCSDisplayController;

/**
 * Provides display CytoScape
 *
 * @Block(
 *   id = "blcytoscape_display_block",
 *   admin_label = @Translation("BLCytoScape Display Block"),
 *   category = @Translation("BLCytoScape Block"),
 * )
 */
class BLCSDisplayBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
        $controller = new BLCSDisplayController;
        return $controller->content();
    }

    // protected function blockAccess(AccountInterface $account) {
    //     /**
    //      * @var \Drupal\node\Entity\Node $node
    //      */
    //     $node = \Drupal::routeMatch()->getParameter('node');
    //     $nid = $node->nid->value;
    //     /** @var \Drupal\rsvplist\EnablerService $enabler */
    //     $enabler = \Drupal::service('rsvplist.enabler');
    //     if(is_numeric($nid)) {
    //         if ($enabler->isEnabled($node)) {
    //             return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
    //         }
    //     }
    //     return AccessResult::forbidden();
    // }
}