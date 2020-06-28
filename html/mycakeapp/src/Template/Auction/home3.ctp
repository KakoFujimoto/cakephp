<h2>発送先を入力する</h2>
<?= $this->Form->create('null', [
    'type' => 'post',
    'url' => [
        'controller' => 'Auction',
        'action' => 'home3'
    ]
]) ?>
<fieldset>
    <legend><?= __('※住所、名前、電話番号を入力してください') ?></legend>
    <?php
    echo $this->Form->hidden('biditem_id', ['value' => $biditem_id]);
    echo $this->Form->hidden('user_id', ['value' => $user_id]);
    echo $this->Form->hidden('price', ['value' => $price]);
    echo $this->Form->control('address');
    echo $this->Form->control('name');
    echo $this->Form->control('tel');
    ?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
<?= pr($bidinfo) ?>
