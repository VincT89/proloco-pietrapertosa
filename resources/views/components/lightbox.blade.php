<dialog id="gallery-modal" class="media-dialog" aria-label="{{ app()->getLocale() === 'en' ? 'Photo and video viewer' : 'Visualizzatore foto e video' }}">
    <div class="media-dialog-toolbar">
        <p id="lbCap" aria-live="polite"><span id="lbCapText"></span> <span id="lbCapCount"></span></p>
        <button type="button" class="media-dialog-close" onclick="closeGallery()" autofocus>{{ app()->getLocale() === 'en' ? 'Close' : 'Chiudi' }} <span aria-hidden="true">×</span></button>
    </div>
    <div class="media-dialog-stage">
        <div id="lbLoader" class="media-dialog-loading" role="status">{{ app()->getLocale() === 'en' ? 'Loading…' : 'Caricamento…' }}</div>
        <div id="lbMediaContainer"></div>
    </div>
    <div class="media-dialog-controls">
        <button type="button" id="lb-prev" onclick="lbPrev()">{{ app()->getLocale() === 'en' ? 'Previous' : 'Precedente' }}</button>
        <button type="button" id="lb-next" onclick="lbNext()">{{ app()->getLocale() === 'en' ? 'Next' : 'Successiva' }}</button>
    </div>
</dialog>
