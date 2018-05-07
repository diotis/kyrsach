<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05.05.2018
 * Time: 17:02
 */

namespace Drupal\provider\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ContractForm extends FormBase
{
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['first_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Имя'),
            '#required' => TRUE,
        ];
        $form['last_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Фамилия'),
            '#required' => TRUE,
        ];

        $form['subject'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Тема'),
            '#required' => TRUE,
        ];

        $form['message'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Сообщение'),
            '#required' => TRUE,
        ];

        $form['email'] = array(
            '#type' => 'email',
            '#title' => $this->t('E-mail'),
            '#placeholder' => $this->t('example@gmail.com'),
        );
        $form['tel'] = array(
            '#type' => 'tel',
            '#title' => $this->t('Телефон'),
            '#placeholder' => $this->t('+375 (..) ... ... .'),
            '#pattern' => '375[0-9]{9}',
        );
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Оставить заказ'),
        ];
        return $form;
    }

    public function getFormId()
    {
        return 'contract_form';
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
    }


    public function submitForm(array &$form, FormStateInterface $form_state)
    {


        drupal_set_message('Submit!');
    }

}
