<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Rating[]|\Cake\Collection\CollectionInterface $ratings
 */
?>

<div class="ratings index large-9 medium-8 columns content">
    <h3><?= __('Ratings') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rated_user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('stars') ?></th>
                <th scope="col"><?= $this->Paginator->sort('comments') ?></th>
                <th scope="col"><?= $this->Paginator->sort('bidinfo_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ratings as $rating) : ?>
                <tr>
                    <td><?= $this->Number->format($rating->id) ?></td>
                    <td><?= $rating->has('user') ? $this->Html->link($rating->user->id, ['controller' => 'Users', 'action' => 'view', $rating->user->id]) : '' ?></td>
                    <td><?= $this->Number->format($rating->rated_user_id) ?></td>
                    <td><?= $this->Number->format($rating->stars) ?></td>
                    <td><?= h($rating->comments) ?></td>
                    <td><?= $this->Number->format($rating->bidinfo_id) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $rating->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $rating->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $rating->id], ['confirm' => __('Are you sure you want to delete # {0}?', $rating->id)]) ?>
                    </td>
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
