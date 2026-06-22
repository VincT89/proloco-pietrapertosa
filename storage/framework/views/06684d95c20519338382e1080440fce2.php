<?php $__env->startSection('title', 'Proloco Pietrapertosana'); ?>

<?php
    

    // Dati fallback presi dal React data/home.js in caso DB vuoto
    if ($events->isEmpty()) {
        $fallbackEvents = [
            (object)['id' => 1, 'title' => 'Sapori d\'Autunno', 'title_en' => 'Tastes of Autumn', 'cover_image_url' => asset('images/sapori_hero.png'), 'start_date' => null, 'fallback_date' => (app()->getLocale() === 'en') ? 'November' : 'Novembre'],
            (object)['id' => 2, 'title' => 'Sulle Tracce degli Arabi', 'title_en' => 'On the Traces of Arabs', 'cover_image_url' => asset('images/arabata.jpeg'), 'start_date' => null, 'fallback_date' => (app()->getLocale() === 'en') ? 'August' : 'Agosto'],
            (object)['id' => 3, 'title' => 'Borgo Festival', 'title_en' => 'Village Festival', 'cover_image_url' => asset('images/PietrapertosaEventi.png'), 'start_date' => null, 'fallback_date' => (app()->getLocale() === 'en') ? 'July' : 'Luglio']
        ];
        $eventsToDisplay = $fallbackEvents;
    } else {
        $eventsToDisplay = $events->map(function($ev) {
            return (object)[
                'id' => $ev->id,
                'title' => $ev->title,
                'title_en' => $ev->title_en,
                'cover_image_url' => $ev->cover ? $ev->cover->url : null,
                'start_date' => $ev->start_date,
                'fallback_date' => null
            ];
        });
    }

    $fallbackScopri = [
        (object)['id' => 1, 'nome' => 'L\'Arabata', 'nome_en' => 'The Arabata', 'img' => asset('images/arabata01.jpg')],
        (object)['id' => 2, 'nome' => 'Castello Normanno-Svevo', 'nome_en' => 'Norman-Swabian Castle', 'img' => asset('images/castello.jpg')],
        (object)['id' => 3, 'nome' => 'Sentiero delle Sette Pietre', 'nome_en' => 'Path of the Seven Stones', 'img' => asset('images/percorsi.jpg')],
        (object)['id' => 4, 'nome' => 'Volo dell\'Angelo', 'nome_en' => 'Flight of the Angel', 'img' => asset('images/voloAngelo.jpg')]
    ];
?>

<?php $__env->startSection('content'); ?>
    <header class="hero" id="top">
        <div class="hero-media" id="heroMedia">
            <div class="h-bg">
                <img src="<?php echo e($page?->heroMedia?->url ?? asset('images/immaginePaese.jpg')); ?>" alt="" class="home-hero-bg" />
                <div class="home-hero-gradient"></div>
            </div>
        </div>
        <div class="hero-in">
            <h1 class="hero-h1">
                <span class="ln">
                    <span><?php echo e($page?->hero_title ?? __('home.hero_title_1')); ?></span>
                </span>
                <span class="ln">
                    <span><?php echo clean($page?->hero_subtitle ?? __('home.hero_title_2')); ?></span>
                </span>
            </h1>
            <div class="hero-foot">
                <p class="hero-desc"><?php echo e($page?->intro_text ?? __('home.hero_desc')); ?></p>
            </div>
        </div>
        <div class="hero-scroll"><i></i></div>
    </header>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($eventsToDisplay) > 0): ?>
        <section class="ed-sec">
            <div class="ed-wrap">
                <div class="ed-section-header">
                    <div>
                        <span class="ed-subtitle"><?php echo app('translator')->get('home.calendar'); ?></span>
                        <a href="<?php echo e(url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'events' : 'eventi'))); ?>" class="ed-link-clean">
                            <h2 class="ed-title ed-title-no-margin"><?php echo app('translator')->get('home.upcoming_events'); ?></h2>
                        </a>
                    </div>
                    <a href="<?php echo e(url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'events' : 'eventi'))); ?>" class="ed-link-more"><?php echo app('translator')->get('home.see_all'); ?></a>
                </div>
                
                <div class="ed-grid">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $eventsToDisplay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'events' : 'eventi'))); ?>" class="ed-link-clean">
                            <div class="ed-card">
                                <div class="ed-img-box tall">
                                    <img src="<?php echo e($ev->cover_image_url ?? 'https://placehold.co/400x600/14181f/d9aa63?text=Locandina'); ?>" alt="<?php echo e(app()->getLocale() === 'en' && !empty($ev->title_en) ? $ev->title_en : $ev->title); ?>" />
                                </div>
                                <div class="ed-glass-cap">
                                    <span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ev->start_date): ?>
                                            <?php echo e(\Carbon\Carbon::parse($ev->start_date)->format((app()->getLocale() === 'en') ? 'M d, Y' : 'd/m/Y')); ?>

                                        <?php else: ?>
                                            <?php echo e($ev->fallback_date); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </span>
                                    <h3 class="ed-title"><?php echo e(app()->getLocale() === 'en' && !empty($ev->title_en) ? $ev->title_en : $ev->title); ?></h3>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <section class="ed-sec alt">
        <div class="ed-wrap">
            <div class="ed-section-header">
                <div>
                    <span class="ed-subtitle"><?php echo app('translator')->get('home.featured_updates'); ?></span>
                    <h2 class="ed-title ed-title-no-margin"><?php echo app('translator')->get('home.latest_news'); ?></h2>
                </div>
            </div>
            
            <div class="ed-list ed-list-wrapper">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($news->isEmpty()): ?>
                    <div class="ed-empty-msg">
                        <?php echo app('translator')->get('home.no_news'); ?>
                    </div>
                <?php else: ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notizia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'news' : 'notizie'))); ?>" class="ed-link-clean">
                            <div class="ed-list-item ed-list-item-hover">
                                <div class="ed-item-row">
                                    <div class="ed-item-date">
                                        <?php echo e(\Carbon\Carbon::parse($notizia->published_at ?? $notizia->created_at)->format((app()->getLocale() === 'en') ? 'M d, Y' : 'd/m/Y')); ?>

                                    </div>
                                    <div>
                                        <h4 class="ed-item-title">
                                            <?php echo e($notizia->getTranslation('title')); ?>

                                        </h4>
                                        <p class="ed-item-category">
                                            <?php echo e((app()->getLocale() === 'en') ? 'News' : 'Avviso'); ?>

                                        </p>
                                    </div>
                                </div>
                                <span class="ed-item-arrow">→</span>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    <section class="ed-sec alt">
        <div class="ed-wrap">
            <div class="ed-split ed-split-center">
                <div class="ed-col-center">
                    <span class="ed-subtitle"><?php echo app('translator')->get('home.explore_territory'); ?></span>
                    <h2 class="ed-title ed-title-large"><?php echo app('translator')->get('home.discover_pietrapertosa'); ?></h2>
                    <p class="ed-desc-text"><?php echo app('translator')->get('home.discover_desc'); ?></p>
                    
                    <ul class="ed-ul-clean">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fallbackScopri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scopri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="ed-li-flex">
                                <span class="ed-list-bullet"></span>
                                <span class="ed-li-text"><?php echo e(app()->getLocale() === 'en' && !empty($scopri->nome_en) ? $scopri->nome_en : $scopri->nome); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>

                    <div>
                        <a href="https://www.borgoracconta.it/citta/pietrapertosa/" target="_blank" rel="noopener noreferrer" class="ed-btn ed-btn-gold">
                            <?php echo app('translator')->get('home.go_to_borgo'); ?>
                        </a>
                    </div>
                </div>

                <div class="borgo-imgs">
                    <div class="bi1"><img src="<?php echo e($fallbackScopri[0]->img); ?>" alt="<?php echo e($fallbackScopri[0]->nome); ?>" /></div>
                    <div class="bi2"><img src="<?php echo e($fallbackScopri[1]->img); ?>" alt="<?php echo e($fallbackScopri[1]->nome); ?>" /></div>
                    <div class="bi3"><img src="<?php echo e($fallbackScopri[2]->img); ?>" alt="<?php echo e($fallbackScopri[2]->nome); ?>" /></div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                document.body.classList.add('ready');
            }, 100);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\vince\OneDrive\Desktop\proloco-pietrapertosa\resources\views/pages/home.blade.php ENDPATH**/ ?>