<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.05.2018
 * Time: 19:40
 */

namespace Drupal\provider\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\provider\DBFunctions;
use \Symfony\Component\HttpFoundation\Response;

class UserContracts extends ControllerBase{
    private function build($msg){
        $build = array(
            '#type' => 'markup',
            '#markup' => $msg,
        );
        return new Response(render($build));
    }
    public function getUserContracts($state)
    {
        //confirmed  completed
        $entries = DBFunctions::load('contract', ['user_id' => \Drupal::currentUser()->id(),
            'state' => $state]);
        //перевернуть массив
        $content = [];
        $headers = ['№', t('Дата'), t('Заголовок'), t('Функции')];
        $null = "Контрактов не найдено! <a href='/create_sentence'>Оставить заявку<a/>";
        $content['table'] = [
            '#type' => 'table',
            '#header' => $headers,
            '#empty' => t($null),
        ];
        for ($i = 0; $i < count($entries); $i++) {

            $content['table'][$i]['number'] = array(
                '#markup' => ($i + 1),
            );
            $content['table'][$i]['date'] = array(
                '#markup' => $entries[$i]->date,
            );
            $content['table'][$i]['caption'] = array(
                '#markup' => $entries[$i]->caption,
            );
            if ($state=='proposed')
            $content['table'][$i]['select'] = [
                '#type' => 'select',
                '#options' => [
                    t('- Выбор действия -'),
                    'view' => 'Просмотреть',
                    'cancel' => 'Отклонить',
                    'confirm' => 'Принять',
                ],
                '#attributes' => array('id' => 'actions', 'data' => $entries[$i]->id),
            ];
            if ($state=='confirmed')
                $content['table'][$i]['select'] = [
                    '#type' => 'select',
                    '#options' => [
                        t('- Выбор действия -'),
                        'go' => 'Перейти',
                        'view' => 'Просмотреть',
                        'end' => 'Завершить',
                    ],
                    '#attributes' => array('id' => 'actions', 'data' => $entries[$i]->id),
                ];
            if ($state=='executed')
                $content['table'][$i]['select'] = [
                    '#type' => 'select',
                    '#options' => [
                        t('- Выбор действия -'),
                        'view' => 'Просмотреть',
                        'contract'=>'Добавить в блокчейн'
                    ],
                    '#attributes' => array('id' => 'actions', 'data' => $entries[$i]->id),
                ];
            $content['table'][$i]['#attributes']['state'] = $entries[$i]->state;
        }
        $content['#cache']['max-age'] = 0;
        return $content;
    }

    public function getProposed(){
        return $this->getUserContracts('proposed');
    }
    public function getConfirmed(){
        return $this->getUserContracts('confirmed');
    }
    public function getExecuted(){
        return $this->getUserContracts('executed');
    }
    //Предложить
    public function confirm($id){
        return $this->build(DBFunctions::update('contract',$id,['state'=>'confirmed']));
    }
    //Отказаться
    public function cancel($id){
        return $this->build(DBFunctions::update('contract',$id,['state'=>'refused']));
    }
    //Просмотреть
    //Завершить
    public function complete($id){
        return $this->build(DBFunctions::update('contract',$id,['state'=>'completed']));
    }

}