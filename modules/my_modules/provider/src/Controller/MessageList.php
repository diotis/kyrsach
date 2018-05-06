<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05.05.2018
 * Time: 19:40
 */

namespace Drupal\provider\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\provider\DBFunctions;
use \Symfony\Component\HttpFoundation\Response;

class MessageList extends ControllerBase
{
    //public $isAdmin = false;

    public function get_incoming($bool){
        if ($this->currentUser()->id() == '1') {
            $entries = DBFunctions::load(['refused' => $bool ? 'true':'false']);
        } else {
        return $message['msg'] = array(
            '#markup'=> 'Данную страницу могут просматривать только администраторы!',
        );}
        $content = [];
        $headers = ['№', t('Имя'), t('Фамилия'), t('Тема'), t('Сообщение'), t('email'), t('телефон'), t('функции')];
        $null = "Заявок не найдено!";

        $content['table'] = [
            '#type' => 'table',
            '#header' => $headers,
            '#empty' => t($null),
        ];

        for ($i = 0; $i < count($entries); $i++) {

            $content['table'][$i] = array(
                '#attributes' => array(
                    'read' => $entries[$i]->read,
                ),
            );

            $content['table'][$i]['number'] = array(
                '#markup' => ($i+1),
            );
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

            if($bool){

            }else {
                $content['table'][$i]['select'] = [
                    '#type' => 'select',
                    '#options' => [
                        t('- Выбор действие -'),
                        'confirm' => $this
                            ->t('Заключить сделку'),
                        'hide' => $this
                            ->t('Пометить как прочитанное'),
                        'refuse' => $this
                            ->t('Отказать'),

                    ],
                    '#attributes' => array('id' => 'move', 'data' => $entries[$i]->id),
                ];
            }

        }
        $content['#cache']['max-age'] = 0;
        return $content;
    }

    public function provider(){
        return $this->get_incoming(false);
    }
    public function refused(){
        return $this->get_incoming(true);
    }

//    public function del($id)
//    {
//        $user = \Drupal::currentUser()->getRoles();
//        if ($user[0] == 'authenticated') {
//            $entries = null;
//            if (count($user) > 1) {
//                if ($user[1] == 'administrator') {
//                    $entries = DBFunctions::load();
//                    $this->isAdmin = true;
//                }
//            } else {
//                $entries = DBFunctions::load_po_id();
//            }
//            DBFunctions::delete($entries[$id]->id);
//            $build = array(
//                '#type' => 'markup',
//                '#markup' => t($id),
//            );
//        }
//        return new Response(render($build));
//    }
    public function hide($id){
        $msg = DBFunctions::provider_funs('`read`',$id);
        $build = array(
            '#type' => 'markup',
            '#markup' => $msg,
        );
        return new Response(render($build));
    }
    public function refuse($id){
        $msg = DBFunctions::provider_funs('`refused`',$id);
        $build = array(
            '#type' => 'markup',
            '#markup' => $msg,
        );
        return new Response(render($build));
    }
}

