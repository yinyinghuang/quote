<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Brand $brand
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Brand'), ['action' => 'edit', $brand->brand]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Brand'), ['action' => 'delete', $brand->brand], ['confirm' => __('Are you sure you want to delete # {0}?', $brand->brand)]) ?> </li>
        <li><?= $this->Html->link(__('List Brands'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Brand'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="brands view large-9 medium-8 columns content">
    <h3><?= h($brand->brand) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Brand') ?></th>
            <td><?= h($brand->brand) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sort') ?></th>
            <td><?= $this->Number->format($brand->sort) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($brand->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($brand->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Visible') ?></th>
            <td><?= $brand->is_visible ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Categories') ?></h4>
        <?php if (!empty($brand->categories)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Pid') ?></th>
                <th scope="col"><?= __('Group Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Is Visible') ?></th>
                <th scope="col"><?= __('Sort') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($brand->categories as $categories): ?>
            <tr>
                <td><?= h($categories->id) ?></td>
                <td><?= h($categories->pid) ?></td>
                <td><?= h($categories->group_id) ?></td>
                <td><?= h($categories->name) ?></td>
                <td><?= h($categories->is_visible) ?></td>
                <td><?= h($categories->sort) ?></td>
                <td><?= h($categories->created) ?></td>
                <td><?= h($categories->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Categories', 'action' => 'view', $categories->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Categories', 'action' => 'edit', $categories->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Categories', 'action' => 'delete', $categories->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categories->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
