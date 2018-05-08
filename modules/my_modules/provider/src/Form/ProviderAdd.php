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

class ProviderAdd extends FormBase
{
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $user = user_load(\Drupal::currentUser()->id());
        $form['first_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Ваше имя'),
            '#required' => TRUE,
            '#default_value' => $user->field_name->value,
        ];
        if ($user->field_name->value!=null){
            $form['first_name']['#attributes'] = array('disabled' => 'disabled');
        }
        $form['last_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Ваша фамилия'),
            '#required' => TRUE,
            '#default_value' => $user->field_familia->value,
        ];
        if ($user->field_familia->value!=null){
            $form['last_name']['#attributes'] = array('disabled' => 'disabled');
        }
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
            '#description' => $this->t('Введите ваш e-mail.'),
            '#placeholder' => $this->t('example@gmail.com'),
            '#default_value' => $user->getEmail(),
        );
        if ($user->getEmail()!=null){
            $form['email']['#attributes'] = array('disabled' => 'disabled');
        }
        $form['tel'] = array(
            '#type' => 'tel',
            '#title' => $this->t('Телефон'),
            '#description' => $this->t('Введите ваш номер телефона.'),
            '#placeholder' => $this->t('+375 (..) ... ... .'),
            '#pattern' => '375[0-9]{9}',
        );
        if ($user->field_telephone->value!=null){
            $form['tel']['#attributes'] = array('disabled' => 'disabled');
        }
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Оставить заказ'),
        ];
        return $form;
    }

    public function getFormId()
    {
        return 'provider_form';
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
        $query = \Drupal::database()->insert('provider');

        $user = user_load(\Drupal::currentUser()->id());

        if(!$user->field_name->value){
            $user->field_name->value = $val['first_name'];
        }
        if(!$user->field_familia->value){
            $user->field_familia->value = $val['last_name'];
        }
        if(!$user->getEmail()){
            $user->setEmail($val['email']);
        }
        if(!$user->field_telephone->value){
            $user->field_telephone->value = $val['tel'];
        }
        $currentDate = date('Y/m/d h:i:s', time());
        $val['read'] = 'fls';
        $user_id = \Drupal::currentUser()->id();
        if(!$user_id)$user_id=-1;
        $query->fields([
            'date'=>$currentDate,
            'subject' => $val['subject'],
            'message' => $val['message'],
            'user' => $user_id,
            'read_state' => 'false',
            'refused_state' => 'false',
        ]);
        $query->execute();
        $user->save();
        drupal_set_message('Ваш заказ принят!'.$currentDate);
    }

}
