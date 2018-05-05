<?php

namespace Drupal\order_table\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\order_table\DbOrdersStorage;
use \Symfony\Component\HttpFoundation\Response;

class DbOrdersController extends ControllerBase
{
    public $isAdmin = false;

    public function entryList()
    {
        $user = \Drupal::currentUser();
        if ($user->isAuthenticated()) {
            $user = $user->getRoles();
            if (count($user) > 1) {
                if ($user[1] == 'administrator') {
                    $this->isAdmin = true;
                }
            }
        }
        $content = [];

        $content['message'] = [
            '#markup' => $this->t('Список отправленных вами заявок в оптовый магазин.'),
        ];
//
//        $content['need'] = [
//            '#type' => 'select',
//            '#title' => $this->t('Тип заявок: '),
//            '#options' => [
//                'all' => $this->t('Все'),
//                'done' => $this->t('Выполненные'),
//            ],
//            '#empty_option' => $this->t('-select-'),
//        ];

        $headers = [t('Имя'), t('Фамилия'), t('Тема'), t('Сообщение'), t('email'), t('телефон')];
        //if($isAdmin)array_unshift($headers,t(''));
        $null = "Заявок не найдено!";
        if(\Drupal::currentUser()->getRoles()[0]!='authenticated')
            $null.=" Вы должны быть зарегистрированы в системе.";
        $content['table'] = [
            '#type' => 'table',
            '#header' => $headers,
            '#empty' => t($null),
        ];
        if ($this->isAdmin) {
            $entries = DbOrdersStorage::load();
        } else {
            $entries = DbOrdersStorage::load_po_id();
        }

        for ($i = 0; $i < count($entries); $i++) {

//            if ($isAdmin) {
//                $content['table'][$i]['done'] = [
//                    '#type' => 'checkboxes',
//                    '#options' => ['SAT' => t('SAT'), 'ACT' => t('ACT')],
//                ];
//            }

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
                '#value' => 'X',
                '#attributes' => array('id' => 'del', 'data' => $i),
            );
            if (!$this->isAdmin) {
                $content['table'][$i]['edit'] = array(
                    '#type' => 'submit',
                    '#value' => 'Изменить',
                    '#attributes' => array('id' => 'edit', 'data' => $i),
                );
            }
        }
        $content['#cache']['max-age'] = 0;
        return $content;
    }

    public
    function del($id)
    {
        $user = \Drupal::currentUser()->getRoles();
        if ($user[0] == 'authenticated') {
            if (count($user) > 1) {
                if ($user[1] == 'administrator') {
                    $entries = DbOrdersStorage::load();
                    $this->isAdmin = true;
                }
            } else {
                $entries = DbOrdersStorage::load_po_id();
            }
            DbOrdersStorage::delete($entries[$id]->id);
            $build = array(
                '#type' => 'markup',
                '#markup' => t($id),
            );
        }
        return new Response(render($build));
    }


}
