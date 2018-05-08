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

    public function CreateContract($id){

        $form_class = 'Drupal\provider\Form\ContractForm';
        $parameter = 'contract_form';

        $entry = DBFunctions::load('provider',['id'=>$id])[0];
        $content = [
                'custom' => [
                    '#type' => 'container',
                    '#attributes' => array('class' => 'container_contract'),
                    'inside' => [
                        'from'=>[
                            '#type'=>'container',
                            '#attributes' => array('class' => 'contract_from'),
                            'inside'=>[
                                'first_name' =>array(
                                    '#type' => 'textfield',
                                    '#title' => $this
                                        ->t('Name'),
                                    '#value' => $entry->name,
                                    '#attributes' => array('disabled' => 'disabled')
                                ),
                                'last_name' =>array(
                                    '#type' => 'textfield',
                                    '#title' => $this
                                        ->t('LastName'),
                                    '#value' => $entry->last_name,
                                    '#attributes' => array('disabled' => 'disabled')

                                ),
                                'subject' =>array(
                                    '#type' => 'textfield',
                                    '#title' => $this
                                        ->t('Subject'),
                                    '#value' => $entry->subject,
                                    '#attributes' => array('disabled' => 'disabled')

                                ),
                                'message' =>array(
                                    '#type' => 'textarea',
                                    '#title' => $this
                                        ->t('Message'),
                                    '#value' => $entry->message,
                                    '#attributes' => array('disabled' => 'disabled')

                                ),
                                'email' =>array(
                                    '#type' => 'textfield',
                                    '#title' => $this
                                        ->t('Email'),
                                    '#value' => $entry->email,
                                    '#attributes' => array('disabled' => 'disabled')

                                ),
                                'tel' =>array(
                                    '#type' => 'textfield',
                                    '#title' => $this
                                        ->t('Tel'),
                                    '#value' => $entry->tel,
                                    '#attributes' => array('disabled' => 'disabled')

                                ),
                            ]
                        ],
                        'to'=>[
                            '#type'=>'container',
                            '#attributes' => array('class' => 'contract_to'),
                            'inside'=>[
                                'form' => $this->formBuilder()->getForm($form_class, $parameter),
                            ]
                        ],
                    ],
                ],

        ];

        return $content;
    }

}