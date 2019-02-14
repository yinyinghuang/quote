<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Area $area
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Area'), ['action' => 'edit', $area->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Area'), ['action' => 'delete', $area->id], ['confirm' => __('Are you sure you want to delete # {0}?', $area->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Areas'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Area'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Districts'), ['controller' => 'Districts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New District'), ['controller' => 'Districts', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="areas view large-9 medium-8 columns content">
    <h3><?= h($area->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($area->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($area->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Pid') ?></th>
            <td><?= $this->Number->format($area->pid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sort') ?></th>
            <td><?= $this->Number->format($area->sort) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Districts') ?></h4>
        <?php if (!empty($area->districts)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Pid') ?></th>
                <th scope="col"><?= __('Area Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Sort') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($area->districts as $districts): ?>
            <tr>
                <td><?= h($districts->id) ?></td>
                <td><?= h($districts->pid) ?></td>
                <td><?= h($districts->area_id) ?></td>
                <td><?= h($districts->name) ?></td>
                <td><?= h($districts->sort) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Districts', 'action' => 'view', $districts->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Districts', 'action' => 'edit', $districts->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Districts', 'action' => 'delete', $districts->id], ['confirm' => __('Are you sure you want to delete # {0}?', $districts->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
