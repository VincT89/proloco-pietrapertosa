<div class="modal lb-modal" id="gallery-modal" onclick="closeGallery()">
    <div class="ov"></div>
    <div class="lb-img-wrap" onclick="event.stopPropagation()">
        
        <button id="lb-prev" class="lb-btn lb-prev" onclick="lbPrev(event)" aria-label="Precedente">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <div class="lb-inner">
            <button class="x lb-close" onclick="closeGallery()" aria-label="Chiudi">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"></path></svg>
            </button>
            
            <div id="lbLoader" class="lb-loader" aria-hidden="true"></div>
            <div id="lbMediaContainer" class="lb-media-container"></div>
            
            <div class="lb-caption-wrap" id="lbCap">
                <span id="lbCapText"></span>
                <span id="lbCapCount" class="lb-caption-count"></span>
            </div>
        </div>

        <button id="lb-next" class="lb-btn lb-next" onclick="lbNext(event)" aria-label="Successiva">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>
</div>
<?php /**PATH C:\Users\vince\OneDrive\Desktop\proloco-pietrapertosa\resources\views/components/lightbox.blade.php ENDPATH**/ ?>