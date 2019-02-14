<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Area $area
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $area->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $area->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Areas'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Districts'), ['controller' => 'Districts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New District'), ['controller' => 'Districts', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="areas form large-9 medium-8 columns content">
    <?= $this->Form->create($area) ?>
    <fieldset>
        <legend><?= __('Edit Area') ?></legend>
        <?php
            echo $this->Form->control('pid');
            echo $this->Form->control('name');
            echo $this->Form->control('sort');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
