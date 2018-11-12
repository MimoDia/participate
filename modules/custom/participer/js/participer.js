
/*
(function($, Drupal, drupalSettings){
   $(document).ready(function(){



alert('hello');

   });
})(jQuery, Drupal, drupalSettings);
*/


	///////////////////////////////////////////
	///////////////////////////////////////////


(function($, Drupal, drupalSettings){
   'use strict';
   Drupal.behaviors.participer = {
       attach: function(context, settings) {   
       
		//soumission automatique du formulaire
          $('#edit-choix-participation--wrapper .radio').once('titatoudadadoum').change(function(){
            $(this).closest('form').submit();
         });  
        	// cache les boutons radio et le bouton submit
          //$('#submit-form input[type="radio"], #edit-submit').hide();
          $('#edit-choix-participation--wrapper input[type="radio"]').hide();
	  $('#edit-choix-participation--wrapper').next('#edit-submit').hide();
 
		// ajoute les classes bootstrap
          $('#submit-form label').addClass("btn btn-primary  btn-lg btn-block");
          $('#edit-choix-participation--wrapper input:radio:checked').closest("label").removeClass('btn-primary  btn-lg btn-block').addClass("btn-success  btn-lg btn-block");         
	}
   };

}) (jQuery, Drupal, drupalSettings);

	///////////////////////////////////////////
	///////////////////////////////////////////

/*

///////////////////////////////////////////
///////////////////////////////////////////
// Methode 2 pour passer une varible dans un fichier js. Ici ,pour supprimer le boutton addtocalendar 
// si la dateDuchamps est inférieur a la date du jour. Cela va avec le fichier participer.module (partie function participer_page_attachments
// où on a recupererr la date et la passer éà drupalsettings: $page['#attached']['drupalSettings']['participer']['participer']['dateDuchamps'] =  $dateDuchamps;
///////////////////////////////////////////
///////////////////////////////////////////

(function($, Drupal, drupalSettings){

   $(document).ready(function(){
    var now = new Date();
    var dateDuchamps = new Date(drupalSettings.participer.participer.dateDuchamps);
	//console.log(dateDuchamps);

          if(dateDuchamps < now){
           $('.addtocalendar').remove();
	  }

   });

})(jQuery, Drupal, drupalSettings);

*/





