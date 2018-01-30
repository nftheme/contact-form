<div id="nf-contact-form">
    <form class="<?php echo $style; ?>" name="contact_form" nf-contact>
    	<?php if(!empty($fields)): ?> 
    		<?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $field->render(); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    	<?php endif; ?>
    	<input type="hidden" name="type" value="<?php echo e((!empty($type)) ? $type : 'contact'); ?>">
    	<input type="hidden" name="status" value="<?php echo e((!empty($status)) ? $status : '0'); ?>">
    	<input type="hidden" name="name_slug" value="<?php echo e((!empty($name_slug)) ? $name_slug : '0'); ?>">
    </form>
</div>
