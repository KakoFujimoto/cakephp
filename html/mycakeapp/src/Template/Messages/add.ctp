<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Message $message
 */
?>
<div class="messages form large-9 medium-8 columns content">
    <?= $this->Form->create($message) ?>
    <fieldset>
        <legend><?= __('取引相手にメッセージを送信する') ?></legend>
        <?php
        echo $this->Form->hidden('user_id', ['value' => $authuser['id']]);
        echo $this->Form->control(
            'message',
            [
                'type' => 'textarea',
                'cols' => 10,
                'rows' => 5
            ]
        );
        echo $this->Form->hidden('bidinfo_id', ['value' => $bidinfo->id]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
