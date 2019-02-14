<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Attribute $attribute
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $attribute->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $attribute->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Attributes'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="attributes form large-9 medium-8 columns content">
    <?= $this->Form->create($attribute) ?>
    <fieldset>
        <legend><?= __('Edit Attribute') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('is_visible');
            echo $this->Form->control('sort');
            echo $this->Form->control('categories._ids', ['options' => $categories]);
            echo $this->Form->control('products._ids', ['options' => $products]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
