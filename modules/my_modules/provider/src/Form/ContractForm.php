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
use Drupal\provider\DBFunctions;
use Drupal\node\Entity\Node;

class ContractForm extends FormBase{
    public function buildForm(array $form, FormStateInterface $form_state){
        $form['city'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Город'),
            '#required' => TRUE,
        ];
        $form['subject'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Заголовок'),
            '#required' => TRUE,
        ];
        $form['message'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Контракт'),
            '#required' => TRUE,
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Предложить контракт'),
        ];
        return $form;
    }

    public function getFormId(){
        return 'contract_form';
    }
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        //города в таблице нету provider
        $id = str_replace('/contract_create/', '', \Drupal::service('path.current')->getPath());
        $entry = DBFunctions::load('provider', ['id' => $id])[0];
        $val = $form_state->getValues();
        $user = user_load($entry->user);
        $node = Node::create([
            'type' => 'kontrakt',
            'title' => 'Contract ' . $user->field_familia->value . ' ' . $user->field_name->value,
            'field_gorod' => $val['city'],
            'field_zagolovok' => $val['subject'],
            'field_predlozen' => $entry->user,
            'body' => $val['message']
        ]);
        $node->save();

        DBFunctions::add('contract', [
            'user_id' => $entry->user,
            'date' => date('Y/m/d h:i:s', time()),
            'caption' => $val['subject'],
            'data' => $val['message'],
            'state' => 'new',
            'nid' => $node->id()
        ]);

        DBFunctions::update('provider', $id, ['state' => 'processed']);
        //переместить в обработанные
        drupal_set_message(t('Контракт добавлен в '.'<a href="/contracts/new">Новые</a>.'.'<a href="/node/'.$node->id().'">Контаркт</a>'));
    }

}
