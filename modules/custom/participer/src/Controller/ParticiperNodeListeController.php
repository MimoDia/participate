<?php


namespace Drupal\participer\Controller;

use Drupal\Core\Controller\ControllerBase;
// use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\paragraphs\Entity\Paragraph;
// Chemin qui nous ramen vers le contrilleur de base.

 
 class ParticiperNodeListeController extends ControllerBase {

 	public function nodeListe(){   

	
	// // // // // // // // // // // // // // // // // // // //// // // // // // 
	// ##### METHODE 1 ACTIVEE  ######
	// // // // // // // // // // // // // // // //// // // // // // // // // //

 		   $node= \Drupal::entityTypeManager()->getlistBuilder('node')->getStorage()->loadByProperties( $values = array('type' => 'evenement'));


 		 	$titre=[];
 		 	foreach ($node as $mavalue) {
 		 	    // $titre[]= $mavalue->getTitle();
 		 	     $titre[]= $mavalue->toLink();  
 		  	}

 		  $list = array(
       	  '#theme' => 'item_list',
       	 '#items' => $titre,
    	   );
  		    return $list;

	// // // // // // // // // // // // // // // // // // // //// // // // // // 
	// ##### FIN METHODE 1  ######
	// // // // // // // // // // // // // // // //// // // // // // // // // //
 

	// // // // // // // // // // // // // // // // // // // //// // // // // // 
	// ##### METHODE 2 Desactivée ici par le return $list commanté  ######
	// // // // // // // // // // // // // // // //// // // // // // // // // //

 		$node= \Drupal::entityTypeManager()->getStorage('node'); 

 	    $id = \Drupal::entityQuery('node')->pager('10')->condition('type', 'evenement')->execute();


 	  $entity = $node->loadMultiple($id);
 	
 	   	     $title=[];
 		 	 foreach ($entity as $val) {
 		 	   // $title[]= $val->getTitle();
 		 	   $title[]= $val->toLink();
 		   }

   		  $list = array(
       	  '#theme' => 'item_list',
       	 '#items' => $title,
    	   );
 
  		   $pager = array('#type' => 'pager',);

  		 // return array($list, $pager);

	// // // // // // // // // // // // // // // // // // // //// // // // // // 
	// ##### FIN METHODE 2  ######
	// // // // // // // // // // // // // // // //// // // // // // // // // //
 }
 			    
}

