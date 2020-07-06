<?php if (!empty($bidinfo)) : ?>
	<h2>商品「<?= $bidinfo->biditem->name ?>」</h2>
	<?php if (isset($bidinfo) && $authuser['id'] === $bidinfo->user_id && is_null($bidinfo->address)) : ?>
		<h6><a href="<?= $this->Url->build(['action' => 'address', $bidinfo->id]) ?>">[発送先入力]</a></h6>
	<?php endif; ?>
	<?php if (isset($bidinfo->address) && ($authuser['id'] === $bidinfo->user_id || $authuser['id'] === $biditems->user_id)) : ?>
		<div class="related">
			<h4><?= __('発送先情報') ?></h4>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<th scope="col">住所</th>
					<th scope="col">名前</th>
					<th scope="col">電話番号</th>
				</tr>
				<tr>
					<td><?= h($bidinfo->address) ?></td>
					<td><?= h($bidinfo->name) ?></td>
					<td><?= h($bidinfo->tel) ?></td>
				</tr>
			</table>
			<!-- 発送連絡のボタン -->
			<?php if (isset($bidinfo->address) && $authuser['id'] === $biditems->user_id && is_null($bidinfo->is_sent)) : ?>
				<?= $this->Form->create(
					'Bidinfo',
					[
						'type' => 'post',
						'url' => ['action' => 'sending']
					]
				) ?>
				<?php
				echo $this->Form->hidden('bidinfo_id', ['value' => $bidinfo->id]);
				?>
				<?= $this->Form->button(__('発送連絡をする')) ?>
				<?= $this->Form->end() ?>
				<!-- 発送連絡のボタン -->
			<?php endif; ?>
			<!-- 発送連絡をしたかの表示 -->
			<?php if (isset($bidinfo->is_sent) && ($authuser['id'] === $bidinfo->user_id || $authuser['id'] === $biditem->user_id)) : ?>
				<div class="related">
					<h4><?= __('商品は発送されました') ?></h4>
				</div>
			<?php endif; ?>
			<!-- ここまで -->
			<!-- 受取連絡のボタン -->
			<?php if (isset($bidinfo->is_sent) && $authuser['id'] === $bidinfo->user_id && is_null($bidinfo->is_received)) : ?>
				<?= $this->Form->create(
					'Bidinfo',
					[
						'type' => 'post',
						'url' => ['action' => 'receiving']
					]
				) ?>
				<?php
				echo $this->Form->hidden('bidinfo_id', ['value' => $bidinfo->id]);
				?>
				<?= $this->Form->button(__('受取連絡をする')) ?>
				<?= $this->Form->end() ?>
				<!-- 受取連絡のボタン -->
			<?php endif; ?>
			<?php if (isset($bidinfo->is_received) && ($authuser['id'] === $bidinfo->user_id || $authuser['id'] === $biditems->user_id) && ($ratings->user_id !== $authuser['id'])) : ?>
				<h6><a href="<?= $this->Url->build(['controller' => 'Ratings', 'action' => 'add', $bidinfo->id]) ?>">[取引相手の評価をする]</a></h6>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<h3>取引相手にメッセージを送る</h3>
	<?= $this->Form->create($bidmsg) ?>
	<?= $this->Form->hidden('bidinfo_id', ['value' => $bidinfo->id]) ?>
	<?= $this->Form->hidden('user_id', ['value' => $authuser['id']]) ?>
	<?= $this->Form->textarea('message', ['rows' => 2]); ?>
	<?= $this->Form->button('Submit') ?>
	<?= $this->Form->end() ?>
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th scope="col">送信者</th>
				<th class="main" scope="col">メッセージ</th>
				<th scope="col">送信時間</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($bidmsgs)) : ?>
				<?php foreach ($bidmsgs as $msg) : ?>
					<tr>
						<td><?= h($msg->user->username) ?></td>
						<td><?= h($msg->message) ?></td>
						<td><?= h($msg->created) ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td colspan="3">※メッセージがありません。</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
<?php else : ?>
	<h2>※落札情報はありません。</h2>
<?php endif; ?>
