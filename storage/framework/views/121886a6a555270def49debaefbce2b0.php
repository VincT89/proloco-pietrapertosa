<div class="section-hero">
    <div class="section-hero-bg">
        <img class="bgi-img" src="<?php echo e($img); ?>" alt="" class="object-<?php echo e($bgPosition ?? 'center'); ?>" />
        <div class="hero-gradient"></div>
    </div>
    <div class="section-hero-content fad">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($subtitle) && $subtitle): ?>
            <span class="lbl-mut section-hero-subtitle"><?php echo e($subtitle); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <h1 class="wr section-hero-title">
            <?php echo e($title); ?>

        </h1>
    </div>
</div>
<?php /**PATH C:\Users\vince\OneDrive\Desktop\proloco-pietrapertosa\resources\views/components/section-hero.blade.php ENDPATH**/ ?>