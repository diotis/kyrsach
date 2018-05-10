<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.05.2018
 * Time: 15:59
 */

namespace Drupal\provider\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\provider\DBFunctions;

class Creating extends ControllerBase{

    public function CreateContract($id)
    {

        $form_class = 'Drupal\provider\Form\ContractForm';
        $parameter = 'contract_form';

        $entry = DBFunctions::load('provider', ['id' => $id])[0];
        $user = user_load($entry->user);
        $user_data = $user->field_familia->value . ' ' . $user->field_name->value;
        $content = [
            'custom' => [
                '#type' => 'container',
                '#attributes' => array('class' => 'container_contract'),
                'inside' => [
                    'from' => [
                        '#type' => 'container',
                        '#attributes' => array('class' => 'contract_from'),
                        'inside' => [
                            'subject' => array(
                                '#type' => 'textfield',
                                '#title' => $this
                                    ->t('Тема'),
                                '#value' => $entry->subject,
                                '#attributes' => array('disabled' => 'disabled')

                            ),
                            'message' => array(
                                '#type' => 'textarea',
                                '#title' => $this
                                    ->t('Сообщение'),
                                '#value' => $entry->message,
                                '#attributes' => array('disabled' => 'disabled')

                            ),
                            'user' => array(
                                '#type' => 'container',
                                '#attributes' => array('id' => 'user_link', 'user_id' => $entry->user),
                                'inside' => [
                                    '#markup' => $user_data,
                                ],
                            ),
                            'date' => array(
                                '#type' => 'textfield',
                                '#title' => $this
                                    ->t('Дата'),
                                '#value' => $entry->date,
                                '#attributes' => array('disabled' => 'disabled')

                            ),
                        ]
                    ],
                    'to' => [
                        '#type' => 'container',
                        '#attributes' => array('class' => 'contract_to'),
                        'inside' => [
                            'form' => $this->formBuilder()->getForm($form_class, $parameter),
                        ]
                    ],
                ],
            ],

        ];

        return $content;
    }

}