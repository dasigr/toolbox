<?php

/**
 * Import tax rates from a csv file.
 */
function accumod_tax_import_form($form, &$form_state) {
  $form['file'] = array(
    '#type' => 'file',
    '#title' => t('CSV'),
    '#description' => t('Upload a file, allowed extensions: csv'),
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Submit',
  );

  return $form;
}

/**
 * Validate csv file.
 */
function accumod_tax_import_form_validate($form, &$form_state) {
  $file = file_save_upload('file', array(
    // Validate extensions.
    'file_validate_extensions' => array('csv'),
  ));
  // If the file passed validation:
  if ($file) {
    // Move the file into the Drupal file system.
    if ($file = file_move($file, 'private://')) {
      // Save the file for use in the submit handler.
      $form_state['storage']['file'] = $file;
    }
    else {
      form_set_error('file', t("Failed to write the uploaded file to the site's file folder."));
    }
  }
  else {
    form_set_error('file', t('No file was uploaded.'));
  }
}

function accumod_tax_import_form_submit($form, &$form_state) {
  $file = $form_state['storage']['file'];
  // We are done with the file, remove it from storage.
  unset($form_state['storage']['file']);
  // Make the storage of the file permanent.
  $file->status = FILE_STATUS_PERMANENT;
  // Save file status.
  file_save($file);

  $csv = drupal_realpath($file->uri);

  // Get list of products from a CSV file.
  $rows = array();
  if (($handle = fopen($csv, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
      $rows[] = $data;
    }
    fclose($handle);
  }

  // Get headers.
  $headers = array_shift($rows);

  // Reformat array of products.
  $tax_rates = array();
  foreach ($rows as $key => $row) {
    $new_row = array();
    foreach ($row as $i => $cell) {
      $new_row[strtolower($headers[$i])] = $cell;
    }

    $tax_rates[] = $new_row;
  }

  foreach ($tax_rates as $tax_rate) {
    accumod_tax_add($tax_rate);
  }

  // Delete file.
  file_delete($file);
}

/**
 * Add new tax rate.
 */
function accumod_tax_add($tax_rate) {
  $record = new stdClass();
  $record->country = 'US';
  $record->administrative_area = 'CA';
  $record->sub_administrative_area = $tax_rate['county'];
  $city = str_replace('*', '', $tax_rate['city']);
  $record->locality = $city;
  $record->postal_code = '';
  $record->thoroughfare = '';
  $record->premise = '';
  $rate = str_replace('%', '', $tax_rate['rate']);
  $rate = number_format($rate * 0.01, 4);
  $record->tax_rate = $rate;

  drupal_write_record('accumod_tax_rate', $record);
  drupal_set_message('Tax rates had been imported.');
}
