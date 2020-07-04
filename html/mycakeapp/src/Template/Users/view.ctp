<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->username) . 'の情報' ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('名前') ?></th>
            <td><?= h($user->username) ?></td>
            <th scope="row"><?= __('評価の平均値') ?></th>
            <td> <?php $stars = array_column($rate, 'stars'); ?>
                <?php $stars_sum = array_sum($stars) / count($stars); ?>
                <?php echo (round($stars_sum, 1)); ?></td>
        </tr>
    </table>
    <?php if (!empty($user->ratings)) : ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('評価コメント') ?></th>
            </tr>
            <?php $comments = array_column($rate, 'comments'); ?>
            <?php foreach ($comments as $comment) : ?>
                <tr>
                    <td><?= h($comment) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
