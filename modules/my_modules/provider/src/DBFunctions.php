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

//    public static function update(array $entry) {
//        try {
//            $count = db_update('provider')
//                ->fields($entry)
//                ->condition('id', $entry['id'])
//                ->execute();
//        }
//        catch (\Exception $e) {
//            drupal_set_message(t('db_update failed. Message = %message, query= %query', [
//                    '%message' => $e->getMessage(),
//                    '%query' => $e->query_string,
//                ]
//            ), 'error');
//        }
//        return $count;
//    }
    public static function provider_funs($filed1,$field2,$id){
        $error = false;
        $msg = "update id: ".$id;
        try {
            db_query("UPDATE `wholesale_store`.`provider` SET ".$filed1."=$field2 WHERE `id`=$id;");
        } catch (\Exception $e) {
            $msg = t('db_update failed. Message = %message', [
                    '%message' => $e->getMessage(),
                ]
            );
            $error = true;
        }
        return json_encode(array(
            'data' => $msg,
            'error' => $error,
        ));
    }
//    public static function load_po_id(array $entry = []) {
//        $select = db_select('provider', 'orders');
//        $select->condition('user', \Drupal::currentUser()->id());
//        $select->fields('orders');
//        foreach ($entry as $field => $value) {
//            $select->condition($field, $value);
//        }
//        return $select->execute()->fetchAll();
//    }
    public static function delete($id) {
        $error = false;
        $msg = "delete id: ".$id;
        try {
            db_delete('provider')
                ->condition('id', $id)
                ->execute();
        } catch (\Exception $e) {
            $msg = t('db_delete failed. Message = %message', [
                    '%message' => $e->getMessage(),
                ]
            );
            $error = true;
        }
        return json_encode(array(
            'data' => $msg,
            'error' => $error,
        ));
    }
    public static function load(array $entry = []) {
        $select = db_select('provider', 'orders');
        $select->fields('orders');
        foreach ($entry as $field => $value) {
            $select->condition($field, $value);
        }
        return $select->execute()->fetchAll();
    }


}
