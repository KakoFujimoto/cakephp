<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event; // added.
use Exception; // added.
class AuctionController extends AuctionBaseController
{
	// デフォルトテーブルを使わない
	public $useTable = false;
	// 初期化処理
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
	// トップページ
	public function index()
	{
		// ページネーションでBiditemsを取得
		$auction = $this->paginate('Biditems', [
			'order' => ['endtime' => 'desc'],
			'limit' => 10
		]);
		$this->set(compact('auction'));
	}
	// 商品情報の表示
	public function view($id = null)
	{
		// $idのBiditemを取得
		$biditem = $this->Biditems->get($id, [
			'contain' => ['Users', 'Bidinfo', 'Bidinfo.Users']
		]);
		// オークション終了時の処理
		if ($biditem->endtime < new \DateTime('now') and $biditem->finished == 0) {
			// finishedを1に変更して保存
			$biditem->finished = 1;
			$this->Biditems->save($biditem);
			// Bidinfoを作成する
			$bidinfo = $this->Bidinfo->newEntity();
			// Bidinfoのbiditem_idに$idを設定
			$bidinfo->biditem_id = $id;
			// 最高金額のBidrequestを検索
			$bidrequest = $this->Bidrequests->find('all', [
				'conditions' => ['biditem_id' => $id],
				'contain' => ['Users'],
				'order' => ['price' => 'desc']
			])->first();
			// Bidrequestが得られた時の処理
			if (!empty($bidrequest)) {
				// Bidinfoの各種プロパティを設定して保存する
				$bidinfo->user_id = $bidrequest->user->id;
				$bidinfo->user = $bidrequest->user;
				$bidinfo->price = $bidrequest->price;
				$this->Bidinfo->save($bidinfo);
			}
			// Biditemのbidinfoに$bidinfoを設定
			$biditem->bidinfo = $bidinfo;
		}
		// Bidrequestsからbiditem_idが$idのものを取得
		$bidrequests = $this->Bidrequests->find('all', [
			'conditions' => ['biditem_id' => $id],
			'contain' => ['Users'],
			'order' => ['price' => 'desc']
		])->toArray();
		// biditem_idが$idの$bidinfoをview.ctpに渡す処理　//added
		$bidinfo = $this->Bidinfo->find('all', [
			'conditions' => ['biditem_id' => $id],
			'contain' => ['Biditems', 'Users', 'Biditems.Users'],
			'order' => ['Bidinfo.id' => 'desc']
		])->first();
		// オブジェクト類をテンプレート用に設定
		$this->set(compact('biditem', 'bidrequests', 'bidinfo'));

		// ratingsテーブルからbidinfo_idが落札商品であるレコードを取得し渡す
		$bidinfo_id = $bidinfo->id;
		$this->set(compact('bidinfo_id'));
		$ratings = $this->Ratings->find(
			'all',
			['conditions' => ['bidinfo_id' => $bidinfo_id]]
		)->first();
		$this->set(compact('ratings'));
	}


	// 出品する処理
	public function add()
	{
		// Biditemインスタンスを用意
		$biditem = $this->Biditems->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			$tmp_data = $this->request->getData();

			// 本当に画像なのかを判断(gif,jpeg,png形式のみ許可)
			$image_check = exif_imagetype($tmp_data['image_path']['tmp_name']);
			if ($image_check === 1 || $image_check === 2 || $image_check === 3) {
				//アップロードされた画像名に日時とユーザーidを加える（画像名重複防止）
				$file_name =   'upload_image/' . date("YmdHis") .  $tmp_data['user_id'] . $tmp_data['image_path']['name'];
				//画像保存先パス
				$img_save_path = WWW_ROOT . 'img/' . $file_name;
				//画像の保存処理
				move_uploaded_file($tmp_data['image_path']['tmp_name'], $img_save_path);
				$tmp_data['image_path'] = $file_name;
			} else {
				$this->Flash->error(__('画像をアップロードして下さい'));
			}
			if ($tmp_data['image_path'] === $file_name) {
				// postの内容を全て取得し、$biditemに入れる
				$biditem = $this->Biditems->patchEntity($biditem, $tmp_data);
				if ($this->Biditems->save($biditem)) {
					// 成功時のメッセージ
					$this->Flash->success(__('保存しました。'));
					return $this->redirect(['action' => 'index']);
				} else {
					// 画像以外のデータ保存失敗時のメッセージ
					$this->Flash->error(__('データの保存に失敗しました。もう一度入力下さい。'));
				}
			} else {
				// 画像保存失敗時のメッセージ
				$this->Flash->error(__('画像の保存に失敗しました。もう一度入力下さい。'));
			}
		}
		// 値を保管
		$this->set(compact('biditem'));
	}

	// 入札の処理
	public function bid($biditem_id = null)
	{
		// 入札用のBidrequestインスタンスを用意
		$bidrequest = $this->Bidrequests->newEntity();
		// $bidrequestにbiditem_idとuser_idを設定
		$bidrequest->biditem_id = $biditem_id;
		$bidrequest->user_id = $this->Auth->user('id');
		// POST送信時の処理
		if ($this->request->is('post')) {
			// $bidrequestに送信フォームの内容を反映する
			$bidrequest = $this->Bidrequests->patchEntity($bidrequest, $this->request->getData());
			// Bidrequestを保存
			if ($this->Bidrequests->save($bidrequest)) {
				// 成功時のメッセージ
				$this->Flash->success(__('入札を送信しました。'));
				// トップページにリダイレクト
				return $this->redirect(['action' => 'view', $biditem_id]);
			}
			// 失敗時のメッセージ
			$this->Flash->error(__('入札に失敗しました。もう一度入力下さい。'));
		}
		// $biditem_idの$biditemを取得する
		$biditem = $this->Biditems->get($biditem_id);
		$this->set(compact('bidrequest', 'biditem'));
	}
	// 落札者とのメッセージ
	public function msg($bidinfo_id = null)
	{
		// Bidmessageを新たに用意
		$bidmsg = $this->Bidmessages->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			// 送信されたフォームで$bidmsgを更新
			$bidmsg = $this->Bidmessages->patchEntity($bidmsg, $this->request->getData());
			// Bidmessageを保存
			if ($this->Bidmessages->save($bidmsg)) {
				$this->Flash->success(__('保存しました。'));
			} else {
				$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
			}
		}
		try { // $bidinfo_idからBidinfoを取得する
			$bidinfo = $this->Bidinfo->get($bidinfo_id, ['contain' => ['Biditems']]);
		} catch (Exception $e) {
			$bidinfo = null;
		}
		// Bidmessageをbidinfo_idとuser_idで検索
		$bidmsgs = $this->Bidmessages->find('all', [
			'conditions' => ['bidinfo_id' => $bidinfo_id],
			'contain' => ['Users'],
			'order' => ['created' => 'desc']
		]);
		$this->set(compact('bidmsgs', 'bidinfo', 'bidmsg'));
	}
	// 落札情報の表示
	public function home()
	{
		// 自分が落札したBidinfoをページネーションで取得
		$bidinfo = $this->paginate('Bidinfo', [
			'conditions' => ['Bidinfo.user_id' => $this->Auth->user('id')],
			'contain' => ['Users', 'Biditems'],
			'order' => ['created' => 'desc'],
			'limit' => 10
		])->toArray();
		$this->set(compact('bidinfo'));
	}
	// 出品情報の表示
	public function home2()
	{
		// 自分が出品したBiditemをページネーションで取得
		$biditems = $this->paginate('Biditems', [
			'conditions' => ['Biditems.user_id' => $this->Auth->user('id')],
			'contain' => ['Users', 'Bidinfo'],
			'order' => ['created' => 'desc'],
			'limit' => 10
		])->toArray();
		$this->set(compact('biditems'));
	}

	// 落札者の発送先入力
	public function address($id = null)
	{
		// biditem_idが$idの$bidinfoをview.ctpに渡す処理　//added
		$bidinfo = $this->Bidinfo->find('all', [
			'conditions' => ['biditem_id' => $id],
			'contain' => ['Biditems', 'Users', 'Biditems.Users'],
			'order' => ['Bidinfo.id' => 'desc']
		])->first();
		$bidinfo->biditem_id = $id;
		// saveの処理
		if ($this->request->is('post')) {
			$data = $this->request->getData();
			$bidinfo = $this->Bidinfo->patchEntity($bidinfo, $data);
			if ($this->Bidinfo->save($bidinfo)) {
				$this->Flash->success(__('送信しました！'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('送信に失敗しました。もう一度入力ください'));
			}
		}
		$this->set(compact('bidinfo'));
	}

	// 出品者の発送連絡
	public function sending()
	{
		// ボタンが押されればis_sentをtrueにして保存する
		if ($this->request->is('post')) {
			$data = $this->request->getData();
			$bidinfo = $this->Bidinfo->get($data['bidinfo_id']);
			$bidinfo->is_sent = true;
			if ($this->Bidinfo->save($bidinfo)) {
				$this->Flash->success(__('落札者へ発送連絡をしました！'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('送信できませんでした。もう一度試してください'));
			}
		}
	}

	// 落札者の受取連絡
	public function receiving()
	{
		// ボタンが押されればis_receivedをtrueにして保存する
		if ($this->request->is('post')) {
			$data = $this->request->getData();
			$bidinfo = $this->Bidinfo->get($data['bidinfo_id']);
			$bidinfo->is_received = true;
			if ($this->Bidinfo->save($bidinfo)) {
				$this->Flash->success(__('出品者へ受取連絡をしました！'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('送信できませんでした。もう一度試してください'));
			}
		}
	}
}
