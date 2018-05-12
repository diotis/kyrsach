<?php

namespace Drupal\provider;

class DBFunctions {

//    public static function toJSON($msg, $error){
//        return json_encode(array(
//            'data' => $msg,
//            'error' => $error,
//        ));
//    }

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
    public static function add($table,array $entry) {
        $error = false;
        $msg = "add";
        try {
            db_insert($table)->fields($entry)->execute();
        }
        catch (\Exception $e) {
            $msg = t('db_insert failed. Message = %message', [
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
    public static function update($table, $id, array $fields) {
        $msg = 'update';
        $error = false;

        try {
            $query = \Drupal::database()->update($table);
            $query->fields($fields);
            $query->condition('id', $id);
            $query->execute();
        }
        catch (\Exception $e) {
            $msg = t('db_update failed. Message = %message', [
                    '%message' => $e->getMessage()
                ]);
            $error= true;
        }
        return json_encode(array(
            'data' => $msg,
            'error' => $error,
        ));
    }
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
    public static function load($table, array $entry = []) {
        $select = db_select($table, 'orders');
        $select->fields('orders');
        foreach ($entry as $field => $value) {
            $select->condition($field, $value);
        }
        return $select->execute()->fetchAll();
    }

    public static function chat($id,$last){
        $select = db_select('chat','orders');
        $select->fields('orders');
        $select->condition('contract_id', $id);
        $select->where('id > :last', [':last' => $last]);
        return $select->execute()->fetchAll();
    }

    public static function whereStates($state1,$state2){
        $select = db_select('contract','orders');
        $select->fields('orders');
        //$select->condition('user_id', $id);
        $select->where('(state = :state1) OR (state = :state2)  ', [':state1' => $state1,':state2'=>$state2]);
        return $select->execute()->fetchAll();
    }
}
