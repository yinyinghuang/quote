<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Attribute $attribute
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Attribute'), ['action' => 'edit', $attribute->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Attribute'), ['action' => 'delete', $attribute->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attribute->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Attributes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Attribute'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="attributes view large-9 medium-8 columns content">
    <h3><?= h($attribute->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($attribute->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($attribute->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sort') ?></th>
            <td><?= $this->Number->format($attribute->sort) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($attribute->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Visible') ?></th>
            <td><?= $attribute->is_visible ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Categories') ?></h4>
        <?php if (!empty($attribute->categories)): ?>
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
            <?php foreach ($attribute->categories as $categories): ?>
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
    <div class="related">
        <h4><?= __('Related Products') ?></h4>
        <?php if (!empty($attribute->products)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Pid') ?></th>
                <th scope="col"><?= __('Zone Id') ?></th>
                <th scope="col"><?= __('Group Id') ?></th>
                <th scope="col"><?= __('Category Id') ?></th>
                <th scope="col"><?= __('Brand') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Is New') ?></th>
                <th scope="col"><?= __('Is Hot') ?></th>
                <th scope="col"><?= __('Price Hong Min') ?></th>
                <th scope="col"><?= __('Price Hong Max') ?></th>
                <th scope="col"><?= __('Price Water Min') ?></th>
                <th scope="col"><?= __('Price Water Max') ?></th>
                <th scope="col"><?= __('Caption') ?></th>
                <th scope="col"><?= __('Album') ?></th>
                <th scope="col"><?= __('Filter') ?></th>
                <th scope="col"><?= __('Rating') ?></th>
                <th scope="col"><?= __('Sort') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($attribute->products as $products): ?>
            <tr>
                <td><?= h($products->id) ?></td>
                <td><?= h($products->pid) ?></td>
                <td><?= h($products->zone_id) ?></td>
                <td><?= h($products->group_id) ?></td>
                <td><?= h($products->category_id) ?></td>
                <td><?= h($products->brand) ?></td>
                <td><?= h($products->name) ?></td>
                <td><?= h($products->is_new) ?></td>
                <td><?= h($products->is_hot) ?></td>
                <td><?= h($products->price_hong_min) ?></td>
                <td><?= h($products->price_hong_max) ?></td>
                <td><?= h($products->price_water_min) ?></td>
                <td><?= h($products->price_water_max) ?></td>
                <td><?= h($products->caption) ?></td>
                <td><?= h($products->album) ?></td>
                <td><?= h($products->filter) ?></td>
                <td><?= h($products->rating) ?></td>
                <td><?= h($products->sort) ?></td>
                <td><?= h($products->created) ?></td>
                <td><?= h($products->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Products', 'action' => 'view', $products->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Products', 'action' => 'edit', $products->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Products', 'action' => 'delete', $products->id], ['confirm' => __('Are you sure you want to delete # {0}?', $products->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
