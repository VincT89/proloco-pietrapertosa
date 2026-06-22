<?php
    
    
    $today = \Carbon\Carbon::today();
    $futuri = $events->filter(function($e) use ($today) {
        return !$e->start_date || \Carbon\Carbon::parse($e->start_date)->gte($today);
    });
    // Ordinamento
    $futuri = $futuri->sortBy(function($a) {
        return $a->start_date ? \Carbon\Carbon::parse($a->start_date)->timestamp : PHP_INT_MAX;
    });

?>

<?php $__env->startSection('title', ($page?->getTranslation('hero_title') ?? __('events.hero_title')) . ' · Proloco Pietrapertosana'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? __('events.hero_title'),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? __('events.hero_subtitle'),
        'img' => $page?->heroMedia?->url ?? asset('images/PietrapertosaEventi.jpeg')
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Eventi Annuali -->
    <section class="ev-sec-annuali">
        <div class="ed-wrap ev-header-wrap">
            <span class="ed-subtitle"><?php echo app('translator')->get('events.annual_subtitle'); ?></span>
            <h2 class="ed-title ev-title-nomargin"><?php echo app('translator')->get('events.annual_title'); ?></h2>
        </div>

        <div class="ev-card-grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $annualEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $gallery = $ev->galleryMedia->pluck('url')->toArray();
                ?>
                <div class="ev-card-giant <?php echo e(count($gallery) > 0 ? 'is-clickable' : ''); ?>" <?php if(count($gallery) > 0): ?> onclick='openGallery(<?php echo json_encode($gallery, 15, 512) ?>)' <?php endif; ?>>
                    <div class="ev-card-bg">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($gallery) > 0): ?>
                            <?php echo $__env->make('components.auto-carousel', ['images' => $gallery, 'interval' => 3000 + $loop->index * 500], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php else: ?>
                            <div class="ev-card-placeholder"></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="ev-card-overlay-right"></div>
                    <div class="ev-card-content">
                        <div class="ev-card-content-inner">
                            <span class="ev-card-subtitle">
                                <?php echo e($ev->getTranslation('subtitle')); ?>

                            </span>
                            <h3 class="ev-card-title">
                                <?php echo e($ev->getTranslation('title')); ?>

                            </h3>
                            <p class="ev-card-desc"><?php echo preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', html_entity_decode(strip_tags($ev->getTranslation('description')))); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    <!-- Eventi Dinamici -->
    <section class="ev-sec-current">
        <div class="ed-wrap ev-header-wrap">
            <span class="ed-subtitle"><?php echo app('translator')->get('events.upcoming_subtitle'); ?></span>
            <h2 class="ed-title ev-title-nomargin"><?php echo e(__('events.upcoming_title', ['year' => date('Y')])); ?></h2>
        </div>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($futuri->isEmpty()): ?>
            <div class="ev-empty-msg-2">
                <p class="ev-empty-italic"><?php echo app('translator')->get('events.no_events_upcoming'); ?></p>
            </div>
        <?php else: ?>
            <div class="ev-grid-cards">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $futuri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $gallery = $ev->galleryMedia->pluck('url')->toArray();
                    ?>
                    <div class="ev-card-normal <?php echo e(count($gallery) > 0 ? 'is-clickable' : ''); ?>" <?php if(count($gallery) > 0): ?> onclick='openGallery(<?php echo json_encode($gallery, 15, 512) ?>)' <?php endif; ?>>
                        <div class="ev-card-normal-bg">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($gallery) > 0): ?>
                                <div class="ev-card-normal-bg-inner">
                                    <img src="<?php echo e($gallery[0]); ?>" alt="" class="ev-card-normal-blur" />
                                </div>
                                <?php echo $__env->make('components.auto-carousel', ['images' => $gallery, 'interval' => 3000 + $loop->index * 500, 'objectFit' => 'contain'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php elseif($ev->cover): ?>
                                <div class="ev-card-normal-bg-inner">
                                    <img src="<?php echo e($ev->cover->url); ?>" alt="" class="ev-card-normal-blur" />
                                </div>
                                <img src="<?php echo e($ev->cover->url); ?>" class="pos-abs-cover object-contain" />
                            <?php else: ?>
                                <div class="ev-card-placeholder"></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="ev-card-normal-overlay"></div>
                        <div class="ev-card-normal-content">
                            <div class="ev-card-normal-inner">
                                <h3 class="ev-card-title">
                                    <?php echo e($ev->getTranslation('title')); ?>

                                </h3>
                                <span class="ev-card-normal-subtitle">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ev->start_date): ?>
                                        <?php echo e(\Carbon\Carbon::parse($ev->start_date)->format((app()->getLocale() === 'en') ? 'M d, Y' : 'd/m/Y')); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ev->end_date && $ev->end_date != $ev->start_date): ?>
                                            - <?php echo e(\Carbon\Carbon::parse($ev->end_date)->format((app()->getLocale() === 'en') ? 'M d, Y' : 'd/m/Y')); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php else: ?>
                                        <?php echo e((app()->getLocale() === 'en') ? 'To be defined' : 'Da definire'); ?>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                                <div class="event-desc ev-card-normal-desc">
                                    <?php echo clean($ev->getTranslation('description') ?? ''); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>

    <!-- Calendario e Archivio -->
    <section class="ed-sec alt">
        <div class="ed-wrap">
            <div class="ev-cal-wrap">
                <div>
                    <span class="ed-subtitle"><?php echo app('translator')->get('events.calendar_subtitle'); ?></span>
                    <h2 class="ed-title ev-cal-title"><?php echo app('translator')->get('events.calendar_title'); ?></h2>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($futuri->isEmpty()): ?>
                        <p class="ev-empty-msg-2 ev-empty-italic"><?php echo app('translator')->get('events.no_events_calendar'); ?></p>
                    <?php else: ?>
                        <div class="ev-cal-table-wrap">
                            <table class="resp-table ev-cal-table">
                                <thead>
                                    <tr class="ev-cal-th-row">
                                        <th class="ev-cal-th-1"><?php echo app('translator')->get('events.table_date'); ?></th>
                                        <th class="ev-cal-th-2"><?php echo app('translator')->get('events.table_title'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $futuri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $galleryList = $cal->galleryMedia->pluck('url')->toArray();
                                            $allGalleryMedia = $cal->galleryMedia->concat($cal->externalMedia);
                                            $galleryJsonData = $allGalleryMedia->map(fn($m) => [
                                                'type' => $m->type,
                                                'provider' => $m->provider,
                                                'url' => $m->url,
                                                'embed_url' => $m->embed_url
                                            ]);
                                        ?>
                                        <tr class="ev-cal-tr <?php echo e(count($galleryList) > 0 || $allGalleryMedia->count() > 0 ? 'is-clickable' : ''); ?>" <?php if(count($galleryList) > 0 || $allGalleryMedia->count() > 0): ?> onclick='openGallery(<?php echo json_encode($galleryJsonData, 15, 512) ?>)' <?php endif; ?>>
                                            <td class="ev-cal-td-1">
                                                <div class="ev-cal-date">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cal->start_date): ?>
                                                        <?php echo e(\Carbon\Carbon::parse($cal->start_date)->format((app()->getLocale() === 'en') ? 'M d, Y' : 'd/m/Y')); ?>

                                                    <?php else: ?>
                                                        <?php echo e((app()->getLocale() === 'en') ? 'To be defined' : 'Da definire'); ?>

                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cal->location): ?>
                                                    <div class="ev-cal-loc"><?php echo e($cal->getTranslation('location')); ?></div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td class="ev-cal-td-2">
                                                <h4 class="ev-cal-item-title"><?php echo e($cal->getTranslation('title')); ?></h4>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div style="margin-top: 40px;">
                            <?php echo e($events->links('pagination::bootstrap-5')); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </section>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\vince\OneDrive\Desktop\proloco-pietrapertosa\resources\views/pages/events.blade.php ENDPATH**/ ?>