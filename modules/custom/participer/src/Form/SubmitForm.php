<?php

namespace Drupal\participer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database;
use Drupal\Core\Session\AccountProxy;


/**
 * Class SubmitForm.
 */
class SubmitForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'submit_form';
  }

  	// Verifier que le champs 'choix_participation' contient un enregistrement 
	protected function verifDataBD(){
		$nid = \Drupal::routeMatch()->getParameter('node')->id();
		$uid = \Drupal::currentUser()->id();

		$connexion= \Drupal::service('database');

		$data = $connexion->select('participer_event_inscription', 'par')
		->fields('par', 
			array('choix_participation'))

			// ->condition('nid', $nid)
 
			->condition('uid', $uid)
			->condition('nid', $nid)
			->orderBy('nid', $direction = 'ASC')

		->execute()
		->fetchAll();
		
		// S'il existe des données en base, je retourne le choix posté par l'utilisateur.
		if(!empty($data)){
			// Retourne un array qui contient la valeur du champs // 0 (participe) 												correspond au 1er element du TA qui est le choix de participer a l'evnmt.
		    return $data[0]->choix_participation; 
		}
	}


  /**
   * {@inheritdoc}
   */

  public function buildForm(array $form, FormStateInterface $form_state) {

// Affiche le numero de l'id de l'evenement de l'url en cours.
    $nid = \Drupal::routeMatch()->getParameter('node')->id(); 

    $uid = \Drupal::currentUser()->id();
    $data = $this->verifDataBD(); 
	

    $form['choix_participation'] = array(
       '#type' => 'radios', 
       '#title' => 'Cela vous intéresse ?',
       '#options' => array(
         	'participe' =>  'Y participer',
         	'participePas'    =>  'Ne pas y participer',
         	'participePeutEtre'  =>  'Peut être y participer',
        ),
    );
	
    // S'il existe des données en base.
    if ($data){
    	// La valeur est celle existante en BD est cochée par default pour un uid et nid encours.
    	$form['choix_participation']['#default_value'] = $data; 
    }


    $form['submit'] = [
      '#type' => 'submit',
      '#value' => ' Validez votre choix',
    ];  
    kint("plop"); die();
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {


    parent::validateForm($form, $form_state);


  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

			$nid = \Drupal::routeMatch()->getParameter('node')->id();
			$email = \Drupal::currentUser()->getEmail();
			
			$prenom= \Drupal\user\Entity\User::load(\Drupal::currentUser()->id())->get('field_prenom')->getString();
			$user_picture= \Drupal\user\Entity\User::load(\Drupal::currentUser()->id())->get('user_picture')->entity->uri->value;
			
			$date = time();
			$node_title = \Drupal::routeMatch()->getParameter('node')->getTitle() ;
			$nom = \Drupal::currentUser()->getAccountName();
			$uid = \Drupal::currentUser()->id();
			$choix_participation = $form_state->getValue('choix_participation');

			$connexion= \Drupal::service('database');

		if(empty($this->verifDataBD())){
				 $this->insertInscriptionEvent($connexion, $nid, $node_title, $uid, $nom, $email, $choix_participation, $date, $prenom, $user_picture);
				 return drupal_set_message($this->t('Votre inscription a bien été enregistré'),'status');
		}else{
				 $this->updateInscriptionEvent($connexion, $nid, $node_title, $uid, $nom, $email, $choix_participation, $date, $prenom, $user_picture);
				 return drupal_set_message($this->t('Votre inscription a bien été modifié'),'status');
		}

		// $form_state->setRebuild();


  } // Fin function submit.

	public function insertInscriptionEvent($connexion, $nid, $node_title, $uid, $nom, $email, $choix_participation, $date, $prenom, $user_picture){

		$insert = $connexion->insert('participer_event_inscription')
				->fields(
					array(
						'nid' =>  $nid,
						'node_title'  => $node_title,
						'uid' => $uid,
						'nom' => $nom,
						'email' => $email,
						'prenom' => $prenom,
						'user_picture' => $user_picture,
						'choix_participation' => $choix_participation,
						'date' => $date,					
		))
		->execute();
		return true;
	}

	public function updateInscriptionEvent($connexion, $nid, $node_title, $uid, $nom, $email, $choix_participation, $date, $prenom, $user_picture){

		$update = $connexion->update('participer_event_inscription')
					->fields(
					array(
						'nid' =>  $nid,
						'node_title'  => $node_title,
						'uid' => $uid,
						'nom' => $nom,
						'email' => $email,
						'prenom' => $prenom,
						'user_picture' => $user_picture,
						'choix_participation' => $choix_participation,
						'date' =>$date,	

					))
					->condition('nid', $nid)
					->condition('uid', $uid)
					->execute();

		return true;
	}
 

} 