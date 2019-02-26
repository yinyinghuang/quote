<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $fan
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Fans'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Comments'), ['controller' => 'Comments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Comment'), ['controller' => 'Comments', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="fans form large-9 medium-8 columns content">
    <?= $this->Form->create($fan) ?>
    <fieldset>
        <legend><?= __('Add Fan') ?></legend>
        <?php
            echo $this->Form->control('openid');
            echo $this->Form->control('nickName');
            echo $this->Form->control('avatarUrl');
            echo $this->Form->control('gender');
            echo $this->Form->control('city');
            echo $this->Form->control('province');
            echo $this->Form->control('country');
            echo $this->Form->control('language');
            echo $this->Form->control('sign_up', ['empty' => true]);
            echo $this->Form->control('last_access', ['empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
