<div id="nf-contact-form">
    <form name="contact_form" nf-contact>
    	<?php if(!empty($fields)): ?> 
    		<?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $field->render(); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    	<?php endif; ?>
    	<input type="hidden" name="type" value="<?php echo e((!empty($type)) ? $type : 'contact'); ?>">
        <input type="submit" class="btn btn-primary" value="Submit">
    </form>
</div>
