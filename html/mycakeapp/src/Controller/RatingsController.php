<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event; // added.
use Exception; // added.

/**
 * Ratings Controller
 *
 * @property \App\Model\Table\RatingsTable $Ratings
 *
 * @method \App\Model\Entity\Rating[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RatingsController extends AuctionBaseController
{
    // デフォルトテーブルを使わない
    public $useTable = false;

    public function initialize()
    {

        parent::initialize();
        $this->loadComponent('Paginator');
        // 必要なモデルをすべてロード
        $this->loadModel('Users');
        $this->loadModel('Biditems');
        $this->loadModel('Bidrequests');
        $this->loadModel('Bidinfo');
        $this->loadModel('Bidmessages');
        $this->loadModel('Messages');
        $this->loadModel('Ratings');


        // ログインしているユーザー情報をauthuserに設定
        $this->set('authuser', $this->Auth->user());
        // レイアウトをauctionに変更
        $this->viewBuilder()->setLayout('auction');
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Bidinfo', 'Biditems'],
        ];
        $ratings = $this->paginate($this->Ratings);

        $this->set(compact('ratings'));
    }


    //  * @param string|null $id Rating id.

    public function view($id = null)
    {
        $rating = $this->Ratings->get($id, [
            'contain' => ['Users', 'Bidinfo', 'Biditems'],
        ]);

        $this->set('rating', $rating);
    }

    public function add($id = null)
    {
        // bidinfoのidが$idと一致するものだけをadd.ctpに渡す
        $bidinfo = $this->Bidinfo->get($id);
        // biditemsのidがbidinfoのbiditem_idと一致するものをadd.ctpに渡す
        $biditems = $this->Biditems->get($bidinfo->biditem_id);

        //saveの処理
        // $rating = $this->Ratings->newEntity();
        // if ($this->request->is('post')) {
        //     $rating = $this->Ratings->patchEntity($rating, $this->request->getData());
        //     if ($this->Ratings->save($rating)) {
        //         $this->Flash->success(__('The rating has been saved.'));

        //         return $this->redirect(['action' => 'index']);
        //     }
        //     $this->Flash->error(__('The rating could not be saved. Please, try again.'));
        // }


        $this->set(compact('bidinfo', 'biditems'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Rating id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $rating = $this->Ratings->get($id, [
            'contain' => ['Users', 'Bidinfo', 'Biditems'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $rating = $this->Ratings->patchEntity($rating, $this->request->getData());
            if ($this->Ratings->save($rating)) {
                $this->Flash->success(__('The rating has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The rating could not be saved. Please, try again.'));
        }
        $users = $this->Ratings->Users->find('list', ['limit' => 200]);
        $ratedUsers = $this->Ratings->RatedUsers->find('list', ['limit' => 200]);
        $bidinfo = $this->Ratings->Bidinfo->find('list', ['limit' => 200]);
        $this->set(compact('rating', 'users', 'ratedUsers', 'bidinfo'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Rating id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $rating = $this->Ratings->get($id);
        if ($this->Ratings->delete($rating)) {
            $this->Flash->success(__('The rating has been deleted.'));
        } else {
            $this->Flash->error(__('The rating could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
