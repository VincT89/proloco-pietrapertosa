document.addEventListener('alpine:init', () => {
    Alpine.data('mediaPicker', ({ state, multiple = false }) => ({
        state, multiple, previewUrl: null, previewName: '',
        ids() {
            return (Array.isArray(this.state) ? this.state : (this.state ? [this.state] : [])).map(Number);
        },
        has(id) { return this.ids().includes(Number(id)); },
        choose(id) {
            const selected = this.ids();
            this.state = this.multiple
                ? (this.has(id) ? selected.filter(value => value !== id) : [...selected, id]) : [id];
        },
        remove(id) { this.state = this.multiple ? this.ids().filter(value => value !== id) : null; },
        move(id, direction) {
            const selected = this.ids();
            const index = selected.indexOf(id), next = index + direction;
            if (index < 0 || next < 0 || next >= selected.length) return;
            [selected[index], selected[next]] = [selected[next], selected[index]];
            this.state = selected;
        },
        preview(url, name) {
            this.previewUrl = url; this.previewName = name;
            this.$refs.previewDialog.showModal();
        },
    }));
});
