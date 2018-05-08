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

class MessageList extends ControllerBase
{
    public function get_incoming($bool){
        if ($this->currentUser()->id() == '1') {
            $entries = DBFunctions::load('provider',['refused_state' => $bool ? 'true':'false']);
        } else {
        return $message['msg'] = array(
            '#markup'=> 'Данную страницу могут просматривать только администраторы!',
        );}
        $content = [];
        $headers = ['№', t('Дата'), t('Тема'), t('Сообщение'),t('Пользователь'),t('Функции')];
        $null = "Заявок не найдено!";

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
                '#markup' => $entries[$i]->date,
            );
            $content['table'][$i]['subject'] = array(
                '#markup' => $this->t($entries[$i]->subject),
            );
            $content['table'][$i]['message'] = array(
                '#markup' => $this->t($entries[$i]->message),
            );
            $content['table'][$i]['user'] = array(
                '#markup' => $this->t($entries[$i]->user),
            );
            if($bool){
                $content['table'][$i]['select'] = [
                    '#type' => 'select',
                    '#options' => [
                        t('- Выбор действия -'),
                        'delete_provider' => $this
                            ->t('Удалить'),
                        'reestablish' => $this
                            ->t('Восстановить'),
                    ],
                    '#attributes' => array('id' => 'move', 'data' => $entries[$i]->id),
                ];

            }else {
                $content['table'][$i]['#attributes'] = array(
                        'read' => $entries[$i]->read_state);
                $content['table'][$i]['select'] = [
                    '#type' => 'select',
                    '#options' => [
                        t('- Выбор действия -'),
                        'confirm' => $this
                            ->t('Заключить сделку'),
                        'hide' => $this
                            ->t('Пометить как прочитанное'),
                        'refuse' => $this
                            ->t('Отказать'),
                    ],
                    '#attributes' => array('id' => 'move', 'data' => $entries[$i]->id),
                ];
            }

        }
        $content['#cache']['max-age'] = 0;
        return $content;
    }

    public function provider(){
        return $this->get_incoming(false);
    }
    public function refused(){
        return $this->get_incoming(true);
    }
    public function delete_provider($id){
        return $this->build(DBFunctions::delete($id));
    }
    public function reestablish($id){
        return $this->build(DBFunctions::provider_funs('`refused_state`',"'false'",$id));
    }
    public function hide($id){
        return $this->build(DBFunctions::provider_funs('`read_state`',"'true'",$id));
    }
    public function refuse($id){
        return $this->build(DBFunctions::provider_funs('`refused_state`',"'true'",$id));
    }
    private function build($msg){
        $build = array(
            '#type' => 'markup',
            '#markup' => $msg,
        );
        return new Response(render($build));
    }
}

