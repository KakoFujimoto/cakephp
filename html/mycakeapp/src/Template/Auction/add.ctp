<h2>商品を出品する</h2>
<?= $this->Form->create($biditem, ['type' => 'file']) ?>
<fieldset>
	<legend>※商品名と終了日時を入力：</legend>
	<?php
	echo $this->Form->hidden('user_id', ['value' => $authuser['id']]);
	echo '<p><strong>USER: ' . $authuser['username'] . '</strong></p>';
	echo $this->Form->control('name');
	echo $this->Form->hidden('finished', ['value' => 0]);
	echo $this->Form->control('endtime');
	// textarea追加部分
	echo $this->Form->control('detail', array('cols' => 20, 'rows' => 5, 'value' => '商品の詳細説明を入れてください'));
	//画像追加
	echo $this->Form->control('image_path', array(
		'label' => false,
		'type' => 'file'
	));
	?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>