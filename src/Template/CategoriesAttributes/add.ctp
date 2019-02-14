<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CategoriesAttribute $categoriesAttribute
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Categories Attributes'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Attributes'), ['controller' => 'Attributes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Attribute'), ['controller' => 'Attributes', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="categoriesAttributes form large-9 medium-8 columns content">
    <?= $this->Form->create($categoriesAttribute) ?>
    <fieldset>
        <legend><?= __('Add Categories Attribute') ?></legend>
        <?php
            echo $this->Form->control('category_id', ['options' => $categories]);
            echo $this->Form->control('attribute_id', ['options' => $attributes]);
            echo $this->Form->control('level');
            echo $this->Form->control('unit');
            echo $this->Form->control('is_filter');
            echo $this->Form->control('filter_type');
            echo $this->Form->control('sort');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
