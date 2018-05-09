<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.05.2018
 * Time: 19:40
 */

namespace Drupal\provider\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\provider\DBFunctions;
use \Symfony\Component\HttpFoundation\Response;

class Chat extends ControllerBase{
    private function build($msg){
        $build = array(
            '#type' => 'markup',
            '#markup' => $msg,
        );
        return new Response(render($build));
    }
    private function filter($entr){
        $i=0;
        foreach ($entr as $item){
            unset($item->contract_id);
            unset($item->last_id);
            $i++;
        }
        return json_encode($entr);
    }
    public function start($id){
        //проверка кому принадлежит контракт
        //загрузка последних 20
        $entry = DBFunctions::load('chat',['contract_id'=>$id]);
        return $this->build($this->filter($entry));
    }

    public function update($id,$last){
        $entry = DBFunctions::chat($id,$last);
        return $this->build($this->filter($entry));
    }

    public function add($id,$message)
    {
        return $this->build(DBFunctions::add('chat',
            [
                'contract_id' => $id,
                'message' => $message,
                'date' => date('Y/m/d h:i:s', time()),
                'owner_id' => \Drupal::currentUser()->id()
            ]
        ));
    }
}