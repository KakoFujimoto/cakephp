<h2>「<?= $biditem->name ?>」の情報</h2>
<table class="vertical-table">
	<tr>
		<th class="small" scope="row">出品者</th>
		<td><?= $biditem->has('user') ? $biditem->user->username : '' ?></td>
	</tr>
	<tr>
		<th scope="row">商品名</th>
		<td><?= h($biditem->name) ?></td>
	</tr>
	<tr>
		<th scope="row">商品ID</th>
		<td><?= $this->Number->format($biditem->id) ?></td>
	</tr>
	<tr>
		<th scope="row">終了時間</th>
		<td><?= h($biditem->endtime) ?></td>
	</tr>
	<tr>
		<th scope="row">投稿時間</th>
		<td><?= h($biditem->created) ?></td>
	</tr>
	<tr>
		<th scope="row"><?= __('終了した？') ?></th>
		<td><?= $biditem->finished ? __('Yes') : __('No'); ?></td>
	</tr>
	<tr>
		<th scope="row">残りあと…</th>
		<td>
			<div id="timer"></div>
		</td>
	</tr>
	<tr>
		<th scope="row">商品の詳細</th>
		<td><?= h($biditem->detail) ?></td>
	</tr>
	<tr>
		<th scope="row">商品画像</th>
		<td>
			<?= $this->Html->image($biditem->image_path, array('height' => 100, 'width' => 100)) ?>
		</td>
	</tr>
</table>
<div class="related">
	<h4><?= __('落札情報') ?></h4>
	<?php if (!empty($biditem->bidinfo)) : ?>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th scope="col">落札者</th>
				<th scope="col">落札金額</th>
				<th scope="col">落札日時</th>
			</tr>
			<tr>
				<td><?= h($biditem->bidinfo->user->username) ?></td>
				<td><?= h($biditem->bidinfo->price) ?>円</td>
				<td><?= h($biditem->endtime) ?></td>
			</tr>
		</table>
	<?php else : ?>
		<p><?= '※落札情報は、ありません。' ?></p>
	<?php endif; ?>
</div>
<div class="related">
	<h4><?= __('入札情報') ?></h4>
	<?php if (!$biditem->finished) : ?>
		<h6><a href="<?= $this->Url->build(['action' => 'bid', $biditem->id]) ?>">《入札する！》</a></h6>
		<?php if (!empty($bidrequests)) : ?>
			<table cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th scope="col">入札者</th>
						<th scope="col">金額</th>
						<th scope="col">入札日時</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($bidrequests as $bidrequest) : ?>
						<tr>
							<td><?= h($bidrequest->user->username) ?></td>
							<td><?= h($bidrequest->price) ?>円</td>
							<td><?= $bidrequest->created ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p><?= '※入札は、まだありません。' ?></p>
		<?php endif; ?>
	<?php else : ?>
		<p><?= '※入札は、終了しました。' ?></p>
	<?php endif; ?>
	<h6><a href="<?= $this->Url->build(['action' => 'home3', $biditem->id]) ?>">[発送先入力]</a></h6>
	<h6><a href="<?= $this->Url->build(['controller' => 'Messages', 'action' => 'add', $bidinfo->id]) ?>">[取引相手にメッセージを送る]</a></h6>
</div>
<!-- カウントダウンタイマー -->
<?php
echo $this->Html->scriptStart(array('inline' => false));
echo
	<<<END
function countdownTimer() {
    //現在日時を取得
    var nowTime = new Date();
    //オークション終了日時を取得
    var endTime = new Date('$biditem->endtime');
    //オークション終了日時までの差分を取得（ミリ秒単位）
    var timeDifference = Math.floor(endTime - nowTime);
    if (timeDifference >= 0) {
        //一日をミリ秒で表した数値
        var oneDay = 24 * 60 * 60 * 1000;
        //日数差分を取得
        var days = Math.floor(timeDifference / oneDay);
        //差分時間を取得
        var hours = Math.floor((timeDifference % oneDay) / (60 * 60 * 1000));
        //差分分数を取得
        var minutes = Math.floor((timeDifference % oneDay) / (60 * 1000)) % 60;
        //差分秒数を取得
        var seconds = Math.floor((timeDifference % oneDay) / 1000) % 60 % 60;
        //HTML上に出力
        var limitTime = days + "日" + hours + "時間" + minutes + "分" + seconds + "秒";
        document.getElementById("timer").innerHTML = limitTime;
        //1秒ごとに処理を繰り返す仕組み
        setTimeout(countdownTimer, 1000);
    } else {
        document.getElementById("timer").innerHTML = "終了済み";
    }
}
countdownTimer();
END;
echo $this->Html->scriptEnd();
?>
