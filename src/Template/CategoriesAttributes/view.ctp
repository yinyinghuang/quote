<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CategoriesAttribute $categoriesAttribute
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Categories Attribute'), ['action' => 'edit', $categoriesAttribute->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Categories Attribute'), ['action' => 'delete', $categoriesAttribute->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categoriesAttribute->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Categories Attributes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Categories Attribute'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Attributes'), ['controller' => 'Attributes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Attribute'), ['controller' => 'Attributes', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="categoriesAttributes view large-9 medium-8 columns content">
    <h3><?= h($categoriesAttribute->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Category') ?></th>
            <td><?= $categoriesAttribute->has('category') ? $this->Html->link($categoriesAttribute->category->name, ['controller' => 'Categories', 'action' => 'view', $categoriesAttribute->category->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Attribute') ?></th>
            <td><?= $categoriesAttribute->has('attribute') ? $this->Html->link($categoriesAttribute->attribute->name, ['controller' => 'Attributes', 'action' => 'view', $categoriesAttribute->attribute->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Unit') ?></th>
            <td><?= h($categoriesAttribute->unit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($categoriesAttribute->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Level') ?></th>
            <td><?= $this->Number->format($categoriesAttribute->level) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Filter Type') ?></th>
            <td><?= $this->Number->format($categoriesAttribute->filter_type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sort') ?></th>
            <td><?= $this->Number->format($categoriesAttribute->sort) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Filter') ?></th>
            <td><?= $categoriesAttribute->is_filter ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
