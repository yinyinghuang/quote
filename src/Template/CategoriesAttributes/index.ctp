<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CategoriesAttribute[]|\Cake\Collection\CollectionInterface $categoriesAttributes
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Categories Attribute'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Attributes'), ['controller' => 'Attributes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Attribute'), ['controller' => 'Attributes', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="categoriesAttributes index large-9 medium-8 columns content">
    <h3><?= __('Categories Attributes') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('category_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('attribute_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('level') ?></th>
                <th scope="col"><?= $this->Paginator->sort('unit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_filter') ?></th>
                <th scope="col"><?= $this->Paginator->sort('filter_type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sort') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categoriesAttributes as $categoriesAttribute): ?>
            <tr>
                <td><?= $this->Number->format($categoriesAttribute->id) ?></td>
                <td><?= $categoriesAttribute->has('category') ? $this->Html->link($categoriesAttribute->category->name, ['controller' => 'Categories', 'action' => 'view', $categoriesAttribute->category->id]) : '' ?></td>
                <td><?= $categoriesAttribute->has('attribute') ? $this->Html->link($categoriesAttribute->attribute->name, ['controller' => 'Attributes', 'action' => 'view', $categoriesAttribute->attribute->id]) : '' ?></td>
                <td><?= $this->Number->format($categoriesAttribute->level) ?></td>
                <td><?= h($categoriesAttribute->unit) ?></td>
                <td><?= h($categoriesAttribute->is_filter) ?></td>
                <td><?= $this->Number->format($categoriesAttribute->filter_type) ?></td>
                <td><?= $this->Number->format($categoriesAttribute->sort) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $categoriesAttribute->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $categoriesAttribute->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $categoriesAttribute->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categoriesAttribute->id)]) ?>
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
