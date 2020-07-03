<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Rating[]|\Cake\Collection\CollectionInterface $ratings
 */
?>

<div class="ratings index large-9 medium-8 columns content">
    <h3><?= __('ユーザーの情報を見る') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rated_user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('stars') ?></th>
                <th scope="col"><?= $this->Paginator->sort('comments') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ratings as $rating) : ?>
                <tr>
                    <td><?= $rating->has('user') ? $this->Html->link($rating->user->id, ['controller' => 'Users', 'action' => 'view', $rating->user->id]) : '' ?></td>
                    <td><?= $this->Number->format($rating->rated_user_id) ?></td>
                    <td><?= $this->Number->format($rating->stars) ?></td>
                    <td><?= h($rating->comments) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
