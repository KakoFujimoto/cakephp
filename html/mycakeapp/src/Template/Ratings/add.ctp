<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Ratings'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav> -->
<div class="ratings form large-9 medium-8 columns content">
    <?= $this->Form->create('Ratings') ?>
    <fieldset>
        <legend><?= __('取引相手を評価する') ?></legend>
        <?php
        echo $this->Form->hidden('user_id', ['value' => $authuser['id']]);
        // $authuserが出品者の場合
        if ($authuser['id'] === $biditems->user_id) {
            echo $this->Form->hidden('rated_user_id', ['value' => $bidinfo->user_id]);
            // $authuserが落札者の場合
        } elseif ($authuser['id'] === $bidinfo->user_id) {
            echo $this->Form->hidden('rated_user_id', ['value' => $biditems->user_id]);
        }
        echo $this->Form->control(
            'stars',
            [
                'type' => 'select',
                'options' => [
                    1 => '★１個',
                    2 => '★２個',
                    3 => '★３個',
                    4 => '★４個',
                    5 => '★５個'
                ],
                'empty' => '星の数を選んでください'
            ]
        );
        echo $this->Form->control('comments');
        echo $this->Form->hidden('bidinfo_id', ['value' => $bidinfo->id]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
