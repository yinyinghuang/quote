<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Brand[]|\Cake\Collection\CollectionInterface $brands
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Brand'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="brands index large-9 medium-8 columns content">
    <h3><?= __('Brands') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('brand') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_visible') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sort') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($brands as $brand): ?>
            <tr>
                <td><?= h($brand->brand) ?></td>
                <td><?= h($brand->is_visible) ?></td>
                <td><?= $this->Number->format($brand->sort) ?></td>
                <td><?= h($brand->created) ?></td>
                <td><?= h($brand->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $brand->brand]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $brand->brand]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $brand->brand], ['confirm' => __('Are you sure you want to delete # {0}?', $brand->brand)]) ?>
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
