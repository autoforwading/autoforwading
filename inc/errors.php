<?php if (count($errors) > 0): ?>
	<div class="error">
		<?php foreach ($errors as $error): ?>
			<p><?php echo $error; ?></p> 
		<?php endforeach ?>
	</div>
<?php endif ?>

<?php if (count($success) > 0): ?>
	<div class="success">
		<?php foreach ($success as $error): ?>
			<p><?php echo $error; ?></p> 
		<?php endforeach ?>
	</div>
<?php endif ?>