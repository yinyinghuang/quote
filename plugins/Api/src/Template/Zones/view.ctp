<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $zone
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Zone'), ['action' => 'edit', $zone->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Zone'), ['action' => 'delete', $zone->id], ['confirm' => __('Are you sure you want to delete # {0}?', $zone->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Zones'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Zone'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="zones view large-9 medium-8 columns content">
    <h3><?= h($zone->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($zone->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($zone->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Pid') ?></th>
            <td><?= $this->Number->format($zone->pid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sort') ?></th>
            <td><?= $this->Number->format($zone->sort) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($zone->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($zone->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Visible') ?></th>
            <td><?= $zone->is_visible ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
