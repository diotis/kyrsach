<?php

namespace Drupal\order_table;

class DbOrdersStorage {

  public static function insert(array $entry) {
    $return_value = NULL;
    try {
      $return_value = db_insert('order_form')
        ->fields($entry)
        ->execute();
    }
    catch (\Exception $e) {
      drupal_set_message(t('db_insert failed. Message = %message, query= %query', [
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      ]
      ), 'error');
    }
    return $return_value;
  }


  public static function update(array $entry) {
    try {
      // db_update()...->execute() returns the number of rows updated.
      $count = db_update('order_form')
        ->fields($entry)
        ->condition('id', $entry['id'])
        ->execute();
    }
    catch (\Exception $e) {
      drupal_set_message(t('db_update failed. Message = %message, query= %query', [
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      ]
      ), 'error');
    }
    return $count;
  }
    public static function load_po_id(array $entry = []) {
        // Read all fields from the dbtng_example table.
        $select = db_select('order_form', 'orders');
        $select->condition('user', \Drupal::currentUser()->id());
        $select->fields('orders');

        // Add each field and value as a condition to this query.
        foreach ($entry as $field => $value) {
            $select->condition($field, $value);
        }
        // Return the result in object format.
        return $select->execute()->fetchAll();
    }


  public static function delete($id) {
    db_delete('order_form')
        ->condition('id', $id)
        ->execute();
}

  public static function load(array $entry = []) {
    // Read all fields from the dbtng_example table.
    $select = db_select('order_form', 'orders');
    $select->fields('orders');
    // Add each field and value as a condition to this query.
    foreach ($entry as $field => $value) {
      $select->condition($field, $value);
    }
    // Return the result in object format.
    return $select->execute()->fetchAll();
  }
  // public static function advancedLoad() {
  //   $select = db_select('order_form', 'e');
  //   // Join the users table, so we can get the entry creator's username.
  //   $select->join('users_field_data', 'u', 'e.uid = u.uid');
  //   // Select these specific fields for the output.
  //   $select->addField('e', 'pid');
  //   $select->addField('u', 'name', 'username');
  //   $select->addField('e', 'name');
  //   $select->addField('e', 'surname');
  //   $select->addField('e', 'age');
  //   // Filter only persons named "John".
  //   $select->condition('e.name', 'John');
  //   // Filter only persons older than 18 years.
  //   $select->condition('e.age', 18, '>');
  //   // Make sure we only get items 0-49, for scalability reasons.
  //   $select->range(0, 50);

  //   $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

  //   return $entries;
  // }

}
