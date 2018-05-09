<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.05.2018
 * Time: 19:40
 */

namespace Drupal\provider\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\provider\DBFunctions;
use \Symfony\Component\HttpFoundation\Response;

class Contract extends ControllerBase{
    public function getContract($id){
        $entry = DBFunctions::load('contract',['id'=>$id])[0];
        //проверка на state
        $content = [
            'custom' => [
                '#type' => 'container',
                '#attributes' => array('class' => 'contract_container'),
                'inside' => [
                    'contract'=>[
                        '#type'=>'container',
                        '#attributes' => array('class' => 'contract'),
                        'inside'=>[
                            'subject' =>array(
                                '#type' => 'textfield',
                                '#title' => $this
                                    ->t('Заголовок'),
                                '#value' => $entry->caption,
                                '#attributes' => array('disabled' => 'disabled')
                            ),
                            'message' =>array(
                                '#type' => 'textarea',
                                '#title' => $this
                                    ->t('Содержание'),
                                '#value' => $entry->data,
                                '#attributes' => array('disabled' => 'disabled')
                            ),
                        ]
                    ],
                    'chat'=>[
                        '#type'=>'container',
                        '#attributes' => array('class' => 'chat'),
                        'inside'=>[
                            'list' =>[
                                '#type'=>'container',
                                '#attributes' => array('class' => 'chat_list',
                                    'id'=>'chat'),
                                'inside'=>[]//добавить загрузку чата
                            ],
                            'message' =>[
                                '#type'=>'container',
                                '#attributes' => array('class' => 'chat_input'),
                                'inside'=>[
                                    'textarea'=>[
                                        '#type' => 'textarea',
                                        '#value' =>"",
                                        '#attributes' => array('id' => 'chat_text')
                                    ],
                                    'button'=>[
                                        '#type' => 'submit',
                                        '#attributes' => array('id' => 'chat_btn')
                                    ]
                                ]
                            ],
                        ]
                    ],
                ],
            ],
        ];
        return $content;
    }
}