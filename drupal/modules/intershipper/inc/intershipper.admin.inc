<?php
/**
 * @file
 * Admin settings to intershipper server.
 */

/**
 * Implements hook_form().
 */
function intershipper_settings_form($form, &$form_state) {
  $api_vars = intershipper_decrypt_vars(FALSE);

  $form['intershipper_username'] = array(
    '#type' => 'textfield',
    '#title' => t('Intershipper username'),
    '#default_value' => $api_vars['intershipper_username'],
    '#required' => TRUE,
  );
  $form['intershipper_password'] = array(
    '#type' => 'password',
    '#title' => t('Password'),
    '#required' => FALSE,
    '#description' => t('Please leave blank if you do not want to update your password at this time.'),
  );
  $form['intershipper_postal'] = array(
    '#type' => 'textfield',
    '#title' => t('Intershipper Postal'),
    '#default_value' => variable_get('intershipper_postal'),
    '#required' => TRUE,
  );
  $form['intershipper_country'] = array(
    '#type' => 'textfield',
    '#title' => t('Intershipper Country'),
    '#default_value' => variable_get('intershipper_country'),
    '#required' => TRUE,
  );
  $form['intershipper_length'] = array(
    '#type' => 'textfield',
    '#title' => t('Intershipper Length'),
    '#default_value' => variable_get('intershipper_length'),
    '#required' => TRUE,
  );
  $form['intershipper_width'] = array(
    '#type' => 'textfield',
    '#title' => t('Intershipper Width'),
    '#default_value' => variable_get('intershipper_width'),
    '#required' => TRUE,
  );
  $form['intershipper_height'] = array(
    '#type' => 'textfield',
    '#title' => t('Intershipper height'),
    '#default_value' => variable_get('intershipper_height'),
    '#required' => TRUE,
  );
  $form['intershipper_carrier'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Shipping providers'),
    '#default_value' => variable_get('intershipper_carrier') ? variable_get('intershipper_carrier') : array(),
    '#required' => TRUE,
    '#options' => array(
      'UPS' => t('UPS'),
      'FDX' => t('FedEx'),
    )
  );
  $form['intershipper_classes'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Classes'),
    '#required' => TRUE,
    '#default_value' => variable_get('intershipper_classes') ? variable_get('intershipper_classes') : array(),
    '#options' => array(
      'GND' => t('Ground'),
      '1DY' => t('1st Day'),
      '2DY' => t('2nd Day'),
      '3DY' => t('3rd Day'),
    )
  );
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save configuration'),
  );
  return $form;
}

/*
 * Implements hook_form_validate().
 */
function intershipper_settings_form_validate($form, &$form_state) {
  // If the Password field is empty, then they're not trying to update it and we should ignore it.
  if (empty($form_state['input']['intershipper_password'])) {
    unset($form_state['input']['intershipper_password']);
  }
}
/*
 * Implements hook_form_submit().
 */
function intershipper_settings_form_submit($form, &$form_state) {
  // Encrypt the UPS API credentials if available
  if (isset($form_state['input']['intershipper_username']) && $form_state['input']['intershipper_username']) {
    $form_state['input']['intershipper_username'] = encrypt($form_state['input']['intershipper_username']);
    if (!empty($form_state['input']['intershipper_password'])) {
      $form_state['input']['intershipper_password'] = encrypt($form_state['input']['intershipper_password']);
    }
  }

  if (empty($form_state['input']['commerce_ups_password'])) {
    unset($form_state['input']['commerce_ups_password']);
  }

  foreach ($form_state['input'] as $key => $val) {
    if (stripos($key, 'intershipper') !== FALSE) {
      variable_set($key, $val);
    }
  }

  commerce_shipping_services_reset();
  entity_defaults_rebuild();
  rules_clear_cache(TRUE);
  menu_rebuild();


  drupal_set_message(t('The Intershipper configuration options have been saved.'));
}
