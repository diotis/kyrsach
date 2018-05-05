<?php

namespace Drupal\provider;

class DBFunctions {

    public static function insert(array $entry) {
        $return_value = NULL;
        try {
            $return_value = db_insert('provider')
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
            $count = db_update('provider')
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
        $select = db_select('provider', 'orders');
        $select->condition('user', \Drupal::currentUser()->id());
        $select->fields('orders');
        foreach ($entry as $field => $value) {
            $select->condition($field, $value);
        }
        return $select->execute()->fetchAll();
    }
    public static function delete($id) {
        db_delete('provider')
            ->condition('id', $id)
            ->execute();
    }
    public static function load(array $entry = []) {
        $select = db_select('provider', 'orders');
        dpm($select);
        $select->fields('orders');
        foreach ($entry as $field => $value) {
            $select->condition($field, $value);
        }
        return $select->execute()->fetchAll();
    }


}
