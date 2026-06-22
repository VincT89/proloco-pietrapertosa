<div class="auto-carousel" data-interval="<?php echo e($interval ?? 4000); ?>" data-fit="<?php echo e($objectFit ?? 'cover'); ?>">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $imgUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <img 
            src="<?php echo e($imgUrl); ?>" 
            alt="Carousel Image <?php echo e($idx + 1); ?>" 
            class="carousel-img <?php echo e($idx === 0 ? 'active' : ''); ?> object-<?php echo e($objectFit ?? 'cover'); ?>"
        />
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($images) > 1): ?>
        <div class="carousel-dots">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $imgUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="carousel-dot <?php echo e($idx === 0 ? 'active' : ''); ?>" data-index="<?php echo e($idx); ?>"></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\vince\OneDrive\Desktop\proloco-pietrapertosa\resources\views/components/auto-carousel.blade.php ENDPATH**/ ?>