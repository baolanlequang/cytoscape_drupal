<?php
/**
 * @file
 * contains \Drupal\rsvplist\Plugin\Block\RSVPBlock
 */
namespace Drupal\rsvplist\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides a 'RSVP' List Block.
 *
 * @Block(
 *   id = "rsvp_block",
 *   admin_label = @Translation("RSVP Block"),
 *   category = @Translation("RSVP Block"),
 * )
 */
class RSVPBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
        return \Drupal::formBuilder()->getForm('Drupal\rsvplist\Form\RSVPForm');
    }

    protected function blockAccess(AccountInterface $account) {
        /**
         * @var \Drupal\node\Entity\Node $node
         */
        $node = \Drupal::routeMatch()->getParameter('node');
        $nid = $node->nid->value;
        if(is_numeric($nid)) {
            return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
        }
        
        return AccessResult::forbidden();
    }
}