@extends('layouts.admin')

@section('content')
<div x-data="tipsEditPreview()" class="mb-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-playfair text-2xl text-dark-oak">Edit Tips</h1>
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <span class="text-sm text-warm-gray">Preview</span>
            <div class="relative">
                <input type="checkbox" x-model="showPreview" class="sr-only peer">
                <div class="w-10 h-5 bg-sand rounded-full peer peer-checked:bg-sage-green transition-colors"></div>
                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
            </div>
        </label>
    </div>

    <div class="grid grid-cols-1 gap-6" :class="showPreview ? 'lg:grid-cols-5' : ''">
        <div :class="showPreview ? 'lg:col-span-3' : ''">
            <div class="bg-white rounded-2xl border border-amber-100 p-6">
                <form action="{{ route('admin.tips.update', $tip) }}" method="POST" enctype="multipart/form-data" id="form-tip-edit-{{ $tip->id }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div x-data="{ selectedIcon: '{{ old('icon', $tip->icon) }}' }">
                        <label class="block text-sm font-medium text-dark-oak mb-1">Icon</label>
                        <div class="grid grid-cols-7 sm:grid-cols-9 md:grid-cols-13 gap-2 mb-1">
                            @php $icons = \App\Models\Tip::iconOptions(); @endphp
                            @foreach($icons as $key)
                            <button type="button" @click="selectedIcon = '{{ $key }}'"
                                class="w-10 h-10 rounded-lg border-2 flex items-center justify-center transition-all"
                                :class="selectedIcon === '{{ $key }}' ? 'border-sage-green bg-sage-green/10 ring-2 ring-sage-green/30' : 'border-sand hover:border-sage-green hover:bg-sage-green/5'"
                                title="{{ $key }}">
                                <x-icons.tip :icon="$key" class="w-5 h-5 text-dark-oak" />
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="icon" x-model="selectedIcon">
                        @error('icon')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-1">Judul Tips</label>
                        <input type="text" name="title" value="{{ old('title', $tip->title) }}" x-model="form.title"
                               class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                        @error('title')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-1">Konten</label>
                        <textarea name="content" rows="6" x-model="form.content"
                                  class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">{{ old('content', $tip->content) }}</textarea>
                        @error('content')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-oak mb-2">Gambar Background</label>

                        @php $currentBg = $tip->background_image_url; @endphp

                        @if($currentBg)
                        <div id="current-bg-wrap" class="mb-3 flex items-center gap-4 p-3 bg-cream rounded-xl border border-amber-100">
                            <div class="w-24 h-16 rounded-xl bg-cream overflow-hidden flex-shrink-0 shadow-sm"><img src="{{ $currentBg }}" alt="Bg saat ini" class="w-full h-full object-contain p-1"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-dark-oak">Gambar saat ini</p>
                                <label class="flex items-center gap-1.5 mt-2 cursor-pointer">
                                    <input type="checkbox" name="remove_background_image" value="1"
                                           class="rounded border-sand text-terracotta">
                                    <span class="text-xs text-terracotta">Hapus gambar ini</span>
                                </label>
                            </div>
                        </div>
                        @endif

                        <div id="drop-zone"
                             class="relative border-2 border-dashed border-sand rounded-2xl p-6 text-center cursor-pointer hover:border-sage-green hover:bg-sage-green/5 transition-all group"
                             onclick="document.getElementById('bg-image-input').click()">

                            <div id="upload-placeholder" :class="form.bgPreview ? 'hidden' : ''">
                                <div class="w-12 h-12 rounded-full bg-cream flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-dark-oak">{{ $currentBg ? 'Pilih gambar baru untuk mengganti' : 'Klik atau drag & drop gambar di sini' }}</p>
                                <p class="text-xs text-warm-gray mt-1">PNG, JPG, WEBP — Otomatis dikompres</p>
                            </div>

                            <div id="image-preview" :class="form.bgPreview ? '' : 'hidden'">
                                <img id="preview-img" :src="form.bgPreview" alt="Preview" class="mx-auto max-h-48 rounded-xl object-contain shadow">
                                <p id="preview-name" class="text-xs text-warm-gray mt-2 truncate" x-text="form.bgName"></p>
                                <button type="button" onclick="clearBg(event)"
                                        class="mt-2 text-xs text-terracotta hover:underline">Batal pilih gambar baru</button>
                            </div>

                            <input type="file" id="bg-image-input" name="background_image" accept="image/*" class="hidden" @change="handleBgUpload">
                        </div>
                        @error('background_image')<p class="text-terracotta text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-dark-oak mb-1">Urutan</label>
                            <input type="number" name="order" value="{{ old('order', $tip->order) }}" min="0" class="w-full border border-sand rounded-xl px-4 py-2.5 focus:border-sage-green focus:outline-none focus:ring-2 focus:ring-sage-green/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-dark-oak mb-1">Status</label>
                            <label class="flex items-center gap-2 mt-2.5">
                                <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-sand text-sage-green focus:ring-sage-green">
                                <span class="text-sm text-dark-oak">Aktif</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click.prevent="openConfirm('save', 'Simpan Perubahan', 'Simpan perubahan tips?', '', 'form-tip-edit-{{ $tip->id }}')" class="bg-sage-green text-white rounded-xl px-6 py-2.5 font-medium hover:brightness-110 transition-all">Simpan Perubahan</button>
                        <a href="{{ route('admin.tips') }}" class="border border-sand text-dark-oak rounded-xl px-6 py-2.5 font-medium">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- PREVIEW PANEL --}}
        <div x-show="showPreview" class="lg:col-span-2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            <div class="sticky top-8">
                <div class="bg-white rounded-2xl border border-amber-100 p-4 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-playfair text-base text-dark-oak">Preview Tips</h3>
                        <span class="text-[10px] px-2 py-0.5 rounded-full" :class="form.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" x-text="form.is_active ? 'Aktif' : 'Nonaktif'"></span>
                    </div>

                    {{-- Tips card — matches homepage style exactly --}}
                    <div class="relative p-6 rounded-2xl border-2 overflow-hidden group max-w-sm mx-auto border-black/20 hover:border-black/50">
                        <template x-if="form.bgPreview || form.currentBgUrl">
                            <div>
                                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" :style="'background-image: url(' + (form.bgPreview || form.currentBgUrl) + ')'"></div>
                                <div class="absolute inset-0 bg-gradient-to-r from-dark-oak/80 to-dark-oak/60"></div>
                            </div>
                        </template>
                        <div class="relative z-10 flex items-center gap-4 mb-3">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 :class="form.bgPreview || form.currentBgUrl ? 'bg-white/20' : 'bg-sage-green/10'">
                                <div :class="(form.bgPreview || form.currentBgUrl) ? 'text-white' : 'text-sage-green'">
                                    <x-icons.tip :icon="$tip->icon ?? 'lightbulb'" class="w-5 h-5" />
                                </div>
                            </div>
                            <h3 class="font-playfair text-lg" :class="form.bgPreview || form.currentBgUrl ? 'text-white' : 'text-dark-oak'"
                                x-text="form.title || 'Judul Tips'"></h3>
                        </div>
                        <p class="text-sm leading-relaxed relative z-10"
                           :class="form.bgPreview || form.currentBgUrl ? 'text-white/90' : 'text-warm-gray'"
                           x-text="form.content || 'Konten tips akan tampil di sini...'"></p>
                    </div>

                    <p class="text-[11px] text-warm-gray text-center mt-4">Tampilan pada halaman homepage</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function tipsEditPreview() {
    return {
        showPreview: false,
        form: {
            title: @js(old('title', $tip->title)),
            content: @js(old('content', $tip->content)),
            is_active: {{ old('is_active', $tip->is_active) ? 'true' : 'false' }},
            bgPreview: null,
            bgName: '',
            currentBgUrl: @js($currentBg),
        },
        handleBgUpload(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (ev) => {
                this.form.bgPreview = ev.target.result;
                this.form.bgName = file.name;
                document.getElementById('upload-placeholder').classList.add('hidden');
                document.getElementById('image-preview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        },
    };
}

document.addEventListener('DOMContentLoaded', function () {
    const dropZone = document.getElementById('drop-zone');
    if (!dropZone) return;
    dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('border-sage-green','bg-sage-green/5'); });
    dropZone.addEventListener('dragleave', () => { dropZone.classList.remove('border-sage-green','bg-sage-green/5'); });
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-sage-green','bg-sage-green/5');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('bg-image-input').files = dt.files;
            document.getElementById('bg-image-input').dispatchEvent(new Event('change'));
        }
    });
});

function clearBg(e) {
    e.stopPropagation();
    const alpine = document.querySelector('[x-data="tipsEditPreview()"]')?.__x;
    if (alpine) {
        alpine.$data.form.bgPreview = null;
        alpine.$data.form.bgName = '';
    }
    document.getElementById('bg-image-input').value = '';
    document.getElementById('upload-placeholder').classList.remove('hidden');
    document.getElementById('image-preview').classList.add('hidden');
}
</script>
@endpush

