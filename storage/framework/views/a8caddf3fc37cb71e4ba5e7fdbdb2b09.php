<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php if (! empty(trim($__env->yieldContent('title')))): ?>
            <?php echo $__env->yieldContent('title'); ?>
        <?php else: ?>
            Pietrapertosa - Sospesi tra cielo e pietra - Pro Loco
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </title>
    <meta name="description" content="<?php echo $__env->yieldContent('seo_description', 'Associazione Pro Loco Pietrapertosana: il borgo più alto della Basilicata, tra le guglie delle Dolomiti Lucane. Castello saraceno, Arabata, Volo dell\'Angelo, news, eventi, locandine e foto.'); ?>">

    <link rel="icon" href="<?php echo e(asset('images/logo.png')); ?>" type="image/png">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="ready">
    <?php echo $__env->make('components.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <?php echo $__env->yieldContent('content'); ?>

    <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('components.lightbox', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('components.cookie-consent', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH C:\Users\vince\OneDrive\Desktop\proloco-pietrapertosa\resources\views/layouts/app.blade.php ENDPATH**/ ?>