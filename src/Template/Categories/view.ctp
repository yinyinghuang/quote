<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Category $category
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Category'), ['action' => 'edit', $category->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Category'), ['action' => 'delete', $category->id], ['confirm' => __('Are you sure you want to delete # {0}?', $category->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Groups'), ['controller' => 'Groups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Group'), ['controller' => 'Groups', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Attributes'), ['controller' => 'Attributes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Attribute'), ['controller' => 'Attributes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Brands'), ['controller' => 'Brands', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Brand'), ['controller' => 'Brands', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="categories view large-9 medium-8 columns content">
    <h3><?= h($category->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Group') ?></th>
            <td><?= $category->has('group') ? $this->Html->link($category->group->name, ['controller' => 'Groups', 'action' => 'view', $category->group->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($category->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($category->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Pid') ?></th>
            <td><?= $this->Number->format($category->pid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sort') ?></th>
            <td><?= $this->Number->format($category->sort) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($category->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($category->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Visible') ?></th>
            <td><?= $category->is_visible ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Attributes') ?></h4>
        <?php if (!empty($category->attributes)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Is Visible') ?></th>
                <th scope="col"><?= __('Sort') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($category->attributes as $attributes): ?>
            <tr>
                <td><?= h($attributes->id) ?></td>
                <td><?= h($attributes->name) ?></td>
                <td><?= h($attributes->is_visible) ?></td>
                <td><?= h($attributes->sort) ?></td>
                <td><?= h($attributes->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Attributes', 'action' => 'view', $attributes->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Attributes', 'action' => 'edit', $attributes->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Attributes', 'action' => 'delete', $attributes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attributes->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Brands') ?></h4>
        <?php if (!empty($category->brands)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Brand') ?></th>
                <th scope="col"><?= __('Is Visible') ?></th>
                <th scope="col"><?= __('Sort') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($category->brands as $brands): ?>
            <tr>
                <td><?= h($brands->brand) ?></td>
                <td><?= h($brands->is_visible) ?></td>
                <td><?= h($brands->sort) ?></td>
                <td><?= h($brands->created) ?></td>
                <td><?= h($brands->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Brands', 'action' => 'view', $brands->brand]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Brands', 'action' => 'edit', $brands->brand]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Brands', 'action' => 'delete', $brands->brand], ['confirm' => __('Are you sure you want to delete # {0}?', $brands->brand)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Products') ?></h4>
        <?php if (!empty($category->products)): ?>
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
            <?php foreach ($category->products as $products): ?>
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
