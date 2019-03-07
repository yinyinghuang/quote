
<!-- File: src/Template/Users/login.ctp -->
<?php $this->layout = 'login';?>
<div class="container full-height">
    <div class="row full-height">
        <div class="col-xs-12 col-sm-12 white-backgroud full-height">
        	<div class="row full-height">
        		<div class="col-xs-10 col-md-4 col-lg-2 login-view white-backgroud vertical-center">
		            
		        	<?= $this->Form->create() ?>
                        <div class="form-group text-center">
                            <h3>登入管理系統</h3>
                        </div>
		              	<div class="form-group">
		              		<?= $this->Form->input('username',['class' => 'form-control', 'label' => false, 'placeholder'=>'帳號']) ?>
		              	</div>
		             	<div class="form-group">
		              		<?= $this->Form->input('password',['class' => 'form-control', 'label' => false, 'placeholder'=>'密碼']) ?>
		              	</div>
		              	<div class="form-group">
		                	<?= $this->Form->button(__('登入'),['class' => 'btn btn-default btn-block']); ?>
		              	</div>
		            <?php echo $this->Form->end(); ?> 
        		</div>
        	</div>
        </div>
    </div>
</div>