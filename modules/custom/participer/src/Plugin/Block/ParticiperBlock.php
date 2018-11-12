<?php
namespace Drupal\participer\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Plugin\Factory\DefaultFactory;

// Pour les permissions d'acces au droit
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;


/**
 * Provides a 'ParticiperBlock' block.
 *
 * @Block(
 *  id = "participate_event_block",
 *  admin_label = @Translation("Participer participate to a event block"),
 * )
 */
class ParticiperBlock extends BlockBase{
  /**
  * {@inheritdoc}
  */

  protected function blockAccess(AccountInterface $account){
    return AccessResult::allowedIfHasPermission($account, 'mapermission access formBlockToEventPartcicipate');// mapermission access formBlockToEventPartcicipate : id de ma permisson dans le fichier permissions.yml (racine)
  }

  public function build(){
     // $build = array();
    $build = array();

      // $build['#markup'] = '' . t('My Custom Form') . '';
      $build['form'] = \Drupal::formBuilder()->getForm('\Drupal\participer\Form\SubmitForm');

      //  $build['form'] = \Drupal::formBuilder()->getForm('\Drupal\participer\Form\ParticiperGeneraleForm');

      return $build; 

        //  return array( Drupal::formBuilder()->getForm('\Drupal\participer\Form\SubmitForm') );

      
  }

}
