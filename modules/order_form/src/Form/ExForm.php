<?php

namespace Drupal\order_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ExForm extends FormBase
{
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['first_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Ваше имя'),
            '#required' => TRUE,
            '#size'=>30,
        ];
        $form['last_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Ваша фамилия'),
            '#required' => TRUE,
            '#size'=>30,
        ];

        $form['subject'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Тема'),
            '#required' => TRUE,
            '#size'=>30,
        ];

        $form['message'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Сообщение'),
            '#required' => TRUE,
        ];

        $form['email'] = array(
            '#type' => 'email',
            '#title' => $this->t('E-mail'),
            '#description' => $this->t('Введите ваш e-mail.'),
            '#placeholder' => $this->t('example@gmail.com'),
            '#size'=>30,
        );
        $form['tel'] = array(
            '#type' => 'tel',
            '#title' => $this->t('Телефон'),
            '#description' => $this->t('Enter your phone number'),
            '#placeholder' => $this->t('+375 (..) ... ... .'),
            '#pattern' => '375[0-9]{9}',
        );


        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Оставить заказ'),
        ];


        return $form;
    }

    public function getFormId()
    {
        return 'my_form';
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $email = $form_state->getValue('email');
        if(strlen($email)>0) {
            $fix = stristr(substr($email, strripos($email, '@'), strlen($email)), '.');
            if (!$fix) {
                $form_state->setErrorByName('email', $this->t('Неправильный адрес: добавьте доменную зону'));
            }
        }
    }


    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $val = $form_state->getValues();
        $query = \Drupal::database()->insert('order_form');

        $query->fields([
            'name' => $val['first_name'],
            'last_name' => $val['last_name'],
            'subject' => $val['subject'],
            'message' => $val['message'],
            'email' => $val['email'],
            'tel' => $val['tel'],
        ]);
        $query->execute();


        drupal_set_message('Ваш заказ принят!');
    }

}
