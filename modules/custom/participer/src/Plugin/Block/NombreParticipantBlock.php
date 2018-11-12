<?php
namespace Drupal\participer\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Plugin\Factory\DefaultFactory;

// Pour les permissions d'acces au droit
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;


/**
 * Provides a 'ParticiperBlock' block.
 *
 * @Block(
 *  id = "calcul_nombre_total_partcipant_block",
 *  admin_label = @Translation("Total nomber of personne participate to a event block"),
 * )
 */


$values = [ 'type' => 'evenement'];

$nodes = \Drupal::entityTypeManager()->getListBuilder('node')->getStorage()->loadByProperties($values);
// kint($nodes); 


class NombreParticipantBlock extends BlockBase{
  /**
  * {@inheritdoc}
  */


  protected function blockAccess(AccountInterface $account){
    return AccessResult::allowedIfHasPermission($account, 'mapermission access formBlockToEventPartcicipate');// mapermission access formBlockToEventPartcicipate : id de ma permisson dans le fichier permissions.yml (racine)
  }

public function build() {
 
    $node= \Drupal::routeMatch()->getParameter('node');
    $nodeType = $node->getType();
    $nid= \Drupal::routeMatch()->getParameter('node')->id();
        
   // kint($nid);
    if($nodeType=='evenement'){
        ///  METHODE 1
         $query =\Drupal::service('database')->select('participer_event_inscription', 'par');

        $dataparticipants= $query->fields('par', array('nom', 'prenom', 'user_picture'))
            ->condition('nid', $nid)
            ->condition('choix_participation', 'participe')
            ->execute()
            ->fetchAll();

                 // kint($dataparticipants);

           $total_participation = count($dataparticipants);
               // kint($total_participation); 

		$Participants = [];
	
	foreach($dataparticipants as $listeParticipants){
		
		 $Participants[] = ucfirst($listeParticipants->prenom);
		 $Participants[] = strtoupper($listeParticipants->nom); 
		 $Participants[] = $listeParticipants->user_picture;

		}

		$Participants = implode(' ', $Participants); // mets les données sur une seul ligne separee par un espace // toto DIALLO

          return array(
			/*
                      '#markup' => $this->t
		       		('Number of participate people: %total_participation,  Participants: %nom' ,  
                        	 array(
					'%total_participation' => $total_participation, 
                         		'%nom' => $Participants, 
                      		     )
				),
			*/
		      '#cache' => array(
			'keys' => [''],
			'contexts' => ['user'],
			'tag' => ['user:'.\Drupal::service('current_user')->id()],
			'age' => '10',
		    		       ),
			// Passer les valeurs des variables au block.html.twig comme j'ai fait une surcharge de template du block
                       '#variables'=>[ 
                                         'total_participation' => $total_participation, 
                         		 'participants'        => $dataparticipants, 
                                    ],
                    );

     }
  }  
}   


              // Methode pour afficher tous les reusltats
            $query =\Drupal::service('database')->select('participer_event_inscription', 'par');
            $req= $query->fields('par',
                  array(
              'nid',
              'node_title',
              'uid',
              'nom',
              'email',
              'choix_participation',
              'date' , 
              ))
             ->execute();
            while($res= $req->fetchAssoc()){
                  $choix_participation= $res['choix_participation'];
                 // kint($choix_participation);
            }


/*
              // Methode 2 pour afficher tous les reusltats
            $query =\Drupal::service('database')->select('participer_event_inscription', 'par');
            $req= $query->fields('par',
                  array(
              'choix_participation'
              ))
             ->execute()
             ->fetchAssoc();
             $choix_participation = array();
            foreach($req as $resultats ){
                  $choix_participation = $resultats->choix_participation;   
            }

             kint($choix_participation);
*/

//  $listeUid[]=[];
//'%uid' => '<a href="'.\Drupal::Url::fromRoute(route:entity.user.canonical).'">' .$listeUid. '</a>',
//  $listeUid = implode(', ', $listeUid); 

            /* $count= $query->fields('par', array('nid', 'nom', 'uid'))
            ->condition('nid', $nid)
            ->condition('choix_participation', 'participe')
            ->countQuery()->execute()->fetchAll();*/
