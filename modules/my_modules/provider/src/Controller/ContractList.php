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

    public function getContracts(array $conditions = []){
        $entries = DBFunctions::load('contract', $conditions);
        $content = [];
        $headers = ['№', t('Дата'), t('Заголовок'), t('Пользователь'), t('Функции')];
        $null = "Контрактов не найдено!";
        $content['table'] = [
            '#type' => 'table',
            '#header' => $headers,
            '#empty' => t($null),
        ];
        for ($i = 0; $i < count($entries); $i++) {

            $content['table'][$i]['number'] = array(
                '#markup' => ($i+1),
            );
            $content['table'][$i]['date'] = array(
                '#markup' => $this->t($entries[$i]->date),
            );
            $content['table'][$i]['caption'] = array(
                '#markup' => $this->t($entries[$i]->caption),
            );
            $content['table'][$i]['user_id'] = array(
                '#markup' => $this->t($entries[$i]->user_id),
            );
            if ($conditions['state'] == 'new')
            $content['table'][$i]['select'] = [
                '#type' => 'select',
                '#options' => [
                    t('- Выбор действия -'),
                    'later' => $this
                        ->t('Отложить'),
                    'offer' => $this
                        ->t('Предложить'),
                    'refuse' => $this
                        ->t('Отказаться'),
                ],
                '#attributes' => array('id' => 'actions', 'data' => $entries[$i]->id),
            ];
        }
        $content['#cache']['max-age'] = 0;
        return $content;
    }

    //Новые контракты
    public function getNewContracts(){
        return $this->getContracts(['state'=>'new']);
    }
    //Отложенные контракты
    public function getDeferredContracts(){
        return $this->getContracts(['state'=>'deferred']);
    }
    //В работе контракты
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

}