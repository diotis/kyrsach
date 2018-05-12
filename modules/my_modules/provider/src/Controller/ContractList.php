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

class ContractList extends ControllerBase{
    private function build($msg){
        $build = array(
            '#type' => 'markup',
            '#markup' => $msg,
        );
        return new Response(render($build));
    }
    public static function getContracts(array $conditions = []){
        if ($conditions['state'] == 'confirmed')
            $entries = DBFunctions::whereStates('confirmed','completed');
        else
            $entries = DBFunctions::load('contract', $conditions);

        //перевернуть массив
        $content = [];
        $headers = ['№', t('Дата'), t('Заголовок'), t('Пользователь'), t('Функции')];
        $null = "Контрактов не найдено!";
        $content['table'] = [
            '#type' => 'table',
            '#header' => $headers,
            '#empty' => t($null),
        ];
        for ($i = 0; $i < count($entries); $i++) {
            $attr = array('id' => 'actions', 'data' => $entries[$i]->id);
            $user = user_load($entries[$i]->user_id);
            $user_label = $user->field_name->value . ' ' . $user->field_familia->value;
            $content['table'][$i]['number'] = array(
                '#markup' => ($i + 1),
            );
            $content['table'][$i]['date'] = array(
                '#markup' => $entries[$i]->date,
            );
            $content['table'][$i]['caption'] = array(
                '#markup' => $entries[$i]->caption,
            );
            $content['table'][$i]['user_id'] = array(
                '#markup' => $user_label,
            );

            if ($conditions['state'] == 'new')
                $content['table'][$i]['select'] = [
                    '#type' => 'select',
                    '#options' => [
                        t('- Выбор действия -'),
                        'later' => 'Отложить',
                        'offer' => 'Предложить',
                        'refuse' => 'Отказать',
                        'view' => 'Просмотреть'
                    ],
                    '#attributes' => $attr,
                ];
            else if ($conditions['state'] == 'proposed')
                $content['table'][$i]['select'] = [
                    '#type' => 'select',
                    '#options' => [
                        t('- Выбор действия -'),
                        'cancel' => 'Отмена',
                        'view' => 'Просмотреть'
                    ],
                    '#attributes' => $attr,
                ];
            else if ($conditions['state'] == 'deferred')
                $content['table'][$i]['select'] = [
                    '#type' => 'select',
                    '#options' => [
                        t('- Выбор действия -'),
                        'offer' => 'Предложить',
                        'refuse' => 'Отказать',
                        'view' => 'Просмотреть'
                    ],
                    '#attributes' => $attr,
                ];
            else if ($conditions['state'] == 'refused')
                $content['table'][$i]['select'] = [
                    '#type' => 'select',
                    '#options' => [
                        t('- Выбор действия -'),
                        'later' => 'Восстановить',
                        'view' => 'Просмотреть'
                    ],
                    '#attributes' => $attr,
                ];
            else if ($conditions['state'] == 'confirmed') {
                $options = [];
                if ($entries[$i]->state!='confirmed'){
                    $options =[ t('- Выбор действия -'),
                        'go' => 'Перейти',
                        'view' => 'Просмотреть',
                        'complete' => 'Завершить',
                    ];
                }else
                    $options =[ t('- Выбор действия -'),
                    'go' => 'Перейти',
                    'view' => 'Просмотреть'
                ];
                $content['table'][$i]['select'] = [
                    '#type' => 'select',
                    '#options' =>$options,
                    '#attributes' => $attr,
                ];
            }
            $content['table'][$i]['#attributes']['state'] = $entries[$i]->state;
            $content['table'][$i]['#attributes']['user_id'] = $entries[$i]->user_id;
            $content['table'][$i]['#attributes']['node'] = $entries[$i]->nid;
        }
        $content['#cache']['max-age'] = 0;
        return $content;
    }

    //Новые контракты
    public function getNewContracts(){
        return $this->getContracts(['state'=>'new']);
    }
    //Предложенные контракты
    public function getProposedContracts(){
        return $this->getContracts(['state'=>'proposed']);
    }
    //Отложенные контракты
    public function getDeferredContracts(){
        return $this->getContracts(['state'=>'deferred']);
    }
    //В работе контракты //completed, 'state'=>'completed']
    public function getConfirmedContracts(){
        return $this->getContracts(['state'=>'confirmed']);
    }
    //Отказанные
    public function getRefusedContracts(){
        return $this->getContracts(['state'=>'refused']);
    }
    //Завершенные
    public function getExecutedContracts(){
        return $this->getContracts(['state'=>'executed']);
    }
    //Отложить или Отмена предложенного
    public function later($id){
        return $this->build(DBFunctions::update('contract',$id,['state'=>'deferred']));
    }
    //Предложить
    public function propose($id){
        return $this->build(DBFunctions::update('contract',$id,['state'=>'proposed']));
    }
    //Отказаться
    public function refuse($id){
        return $this->build(DBFunctions::update('contract',$id,['state'=>'refused']));
    }
    //Принять завершение
    public function execut($id){
        return $this->build(DBFunctions::update('contract',$id,['state'=>'executed']));
    }
}