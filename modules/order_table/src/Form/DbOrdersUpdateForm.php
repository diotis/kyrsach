<?php

namespace Drupal\order_table\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\order_table\DbOrdersStorage;

class DbOrdersUpdateForm extends FormBase {

    public $entry_id;

  public function getFormId() {
    return 'order_update_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

//    $form['pid'] = [
//      '#type' => 'select',
//      '#options' => $options,
//      '#title' => t('Choose entry to update'),
//      '#default_value' => $default_entry->pid,
//      '#ajax' => [
//        'wrapper' => 'updateform',
//        'callback' => [$this, 'updateCallback'],
//      ],
//    ];

      $current_path = \Drupal::service('path.current')->getPath();
      $id = str_replace('/edit/','',$current_path);
      $data = DbOrdersStorage::load_po_id()[$id];
      $this->entry_id = $data->id;
      $form['first_name'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Ваше имя'),
          '#required' => TRUE,
          '#default_value'=> $data->name,
      ];
      $form['last_name'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Ваша фамилия'),
          '#required' => TRUE,
          '#default_value'=> $data->last_name,
      ];

      $form['subject'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Тема'),
          '#required' => TRUE,
          '#default_value'=> $data->subject,
      ];

      $form['message'] = [
          '#type' => 'textarea',
          '#title' => $this->t('Сообщение'),
          '#required' => TRUE,
          '#default_value'=> $data->message,
      ];

      $form['email'] = array(
          '#type' => 'email',
          '#title' => $this->t('E-mail'),
          '#placeholder' => $this->t('example@gmail.com'),
          '#default_value'=> $data->email,
      );
      $form['tel'] = array(
          '#type' => 'tel',
          '#title' => $this->t('Телефон'),
          '#placeholder' => $this->t('+375 (..) ... ... .'),
          '#pattern' => '375[0-9]{9}',
          '#default_value'=> $data->tel,
      );

      $form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => $this->t('Редактировать заказ'),
      ];
      return $form;

  }
  /**
   * AJAX callback handler for the pid select.
   *
   * When the pid changes, populates the defaults from the database in the form.
   */
  public function updateCallback(array $form, FormStateInterface $form_state) {
    $entries = $form_state->getValue('entries');
    $entry = $entries[$form_state->getValue('pid')];
    foreach (['name', 'surname', 'age'] as $item) {
      $form[$item]['#value'] = $entry->$item;
    }
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
      $email = $form_state->getValue('email');
      if(strlen($email)>0) {
          $fix = stristr(substr($email, strripos($email, '@'), strlen($email)), '.');
          if (!$fix) {
              $form_state->setErrorByName('email', $this->t('Неправильный адрес: добавьте доменную зону'));
          }
      }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
      $entry = [
          'id' => $this->entry_id,
          'name'=> $form_state->getValue('first_name'),
          'last_name'=> $form_state->getValue('last_name'),
          'subject'=> $form_state->getValue('subject'),
          'message'=> $form_state->getValue('message'),
          'email'=> $form_state->getValue('email'),
          'tel'=> $form_state->getValue('tel'),
      ];
      DbOrdersStorage::update($entry);

      drupal_set_message('Данные были обновлены!');
  }

}
