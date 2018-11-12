(function($, Drupal, drupalSettings){
  'use strict';
  Drupal.behaviors.jsClassementTheme = {
    attach: function (context, settings) {

      $("table tr").once('jsClassementTheme').each(function(){
        var tableClass = $(this).attr('class');
        $(this).removeClass(tableClass).closest("table").addClass(tableClass);
      });

    }
  }
})(jQuery, Drupal, drupalSettings);



