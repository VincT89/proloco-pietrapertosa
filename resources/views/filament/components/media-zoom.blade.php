<dialog x-ref="previewDialog" class="media-picker-zoom" aria-label="Anteprima del file" @keydown.escape.stop @click="if ($event.target === $el) $el.close()">
    <div>
        <button type="button" @click="$refs.previewDialog.close()" autofocus>Chiudi anteprima</button>
        <p x-text="previewName"></p>
        <img :src="previewUrl || undefined" :alt="previewName">
    </div>
</dialog>
