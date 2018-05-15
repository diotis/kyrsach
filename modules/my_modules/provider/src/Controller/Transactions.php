<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.05.2018
 * Time: 19:44
 */

namespace Drupal\provider\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\provider\DBFunctions;


class Transactions extends ControllerBase
{
        public function getUserTransactions(){
        $entries = DBFunctions::load('ethereum', ['user_id'=>\Drupal::currentUser()->id()]);
        //перевернуть массив
        $content = [];
        $headers = ['№', t('Дата'), t('Hash')];
        $null = "Транзакций не найдено! Функция доступна после выполнения транзакций<a href='/user/executed'>Здесь<a/>";
        //$null = 'error';
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
            $content['table'][$i]['hash'] = array(
                '#markup' => $entries[$i]->hash,
            );

        }
        $content['#cache']['max-age'] = 0;
        return $content;

    }
}