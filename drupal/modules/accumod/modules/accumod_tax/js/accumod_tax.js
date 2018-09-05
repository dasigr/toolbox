(function($) {

  Drupal.behaviors.accumodTax = {
    attach: function (context, settings) {
      
      var $state = $('select[name="customer_profile_shipping[commerce_customer_address][und][0][administrative_area]"]');

      var state_code = $state.val();
      if (state_code === 'CA') {
        $('fieldset.commerce_fieldgroup_pane__group_tax').removeClass('element-hidden');
      }

      $state.on('change', function(e) {
        var state_code = $state.val();
        if (state_code === 'CA') {
          $('fieldset.commerce_fieldgroup_pane__group_tax').removeClass('element-hidden');
        }
        else {
          $('fieldset.commerce_fieldgroup_pane__group_tax').addClass('element-hidden');
        }
      });
      
    }
  };

})(jQuery);
