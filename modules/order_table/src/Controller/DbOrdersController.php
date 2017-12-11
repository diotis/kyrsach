<?php

namespace Drupal\order_table\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\order_table\DbOrdersStorage;
use \Symfony\Component\HttpFoundation\Response;

class DbOrdersController extends ControllerBase {

    public function entryList() {
    $content = [];

    $content['message'] = [
      '#markup' => $this->t('Generate a list of all entries in the database. There is no filter in the query.'),
    ];

    $rows = [];
    $headers = [t('Id'), t('Name'), t('Surname'), t('Subject'), t('Message'), t('email'), t('phone')];

    foreach ($entries = DbOrdersStorage::load() as $entry) {
      // Sanitize each entry.
      $need = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', (array) $entry);
      array_pop($need);
      $rows[]=$need;
    }
    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => t('No entries available.'),
    ];
    $content['#cache']['max-age'] = 0;

    return $content;
  }

    public function my_entryList() {
        $content = [];

        $content['message'] = [
            '#markup' => $this->t('Generate a list of all entries in the database. There is no filter in the query.'),
        ];

        $headers = [t('Name'), t('Surname'), t('Subject'), t('Message'), t('email'), t('phone')];

        $content['table'] = [
            '#type' => 'table',
            '#header' => $headers,
            '#empty' => t('No entries available.'),
        ];

        $entries = DbOrdersStorage::load_po_id();

        for( $i=0; $i<count($entries); $i++){
            $content['table'][$i]['name'] = array(
                '#markup' => $this->t($entries[$i]->name),
            );
            $content['table'][$i]['last_name'] = array(
                '#markup' => $this->t($entries[$i]->last_name),
            );
            $content['table'][$i]['subject'] = array(
                '#markup' => $this->t($entries[$i]->subject),
            );
            $content['table'][$i]['message'] = array(
                '#markup' => $this->t($entries[$i]->message),
            );
            $content['table'][$i]['email'] = array(
                '#markup' => $this->t($entries[$i]->email),
            );
            $content['table'][$i]['tel'] = array(
                '#markup' => $this->t($entries[$i]->tel),
            );
            $content['table'][$i]['delete'] = array(
                '#type' => 'submit',
                '#value' => 'Удалить',
                '#attributes' => array('id'=>'del', 'data'=>$i),
            );
            $content['table'][$i]['edit'] = array(
                '#type' => 'submit',
                '#value' => 'Редактировать',
                '#attributes' => array('id'=>'edit', 'data'=>$i),

            );
        }

        $content['#cache']['max-age'] = 0;
        return $content;
    }

    public function del($id){
        DbOrdersStorage::delete(DbOrdersStorage::load_po_id()[$id]->id);
        $build = array(
            '#type' => 'markup',
            '#markup' => t($id),
        );
        return new Response(render($build));
    }


}
