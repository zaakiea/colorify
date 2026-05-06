@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-6xl font-bold text-gray-900 mb-1" style="font-family:'Poppins',sans-serif">Wernoin Color</h1>
            <h1 class="text-6xl font-bold text-gray-900 mb-4" style="font-family:'Poppins',sans-serif">Generator</h1>
            <p class="text-gray-600">Enter up to 3 colors — they'll be blended into one smooth 11-swatch palette</p>
        </div>

        {{-- Color Inputs --}}
        <div class="mb-10">
            <div class="max-w-md mx-auto" id="colorInputsWrap"></div>
            <div class="flex justify-center mt-4" id="addColorBtnWrap">
                <button id="addColorBtn" class="text-gray-500 flex items-center gap-1 hover:text-gray-700 transition-colors text-sm px-4 py-2 rounded-full border border-gray-300 hover:border-gray-400 bg-white">
                    <span class="text-lg leading-none">+</span>
                    <span id="addColorBtnLabel">Add secondary color</span>
                </button>
            </div>
        </div>

        {{-- Single blended palette --}}
        <div id="paletteSection" class="mb-12 hidden">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-3">
                    <div id="colorDots" class="flex gap-1"></div>
                    <h2 class="text-2xl font-semibold text-gray-900" id="paletteName">Blended Palette</h2>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportPalette()" class="px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors">Export CSS</button>
                    <button class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 transition-colors">Save</button>
                </div>
            </div>

            <div id="loadingBar" class="hidden mb-4">
                <div class="h-1 w-full bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-1 rounded-full animate-loading-bar"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1 text-center" id="loadingText">Fetching palette…</p>
            </div>

            <div class="grid gap-2" id="paletteGrid" style="grid-template-columns:repeat(11,1fr)"></div>
            <div id="paletteLegend" class="flex gap-6 mt-4 justify-center text-xs text-gray-500"></div>
        </div>

        {{-- Related Colors — Color Theory --}}
        <div class="mt-16" id="relatedSection">
            <div class="flex justify-between items-center mb-2">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-1">Related colours</h2>
                    <p class="text-gray-500 text-sm">Colours derived from color theory based on your primary colour.</p>
                </div>
                {{-- Tab switcher --}}
                <div class="flex gap-1 bg-gray-100 rounded-lg p-1 text-sm" id="relatedTabs">
                    <button data-tab="complementary" class="tab-btn px-3 py-1.5 rounded-md font-medium transition-all text-gray-500 hover:text-gray-800">Complementary</button>
                    <button data-tab="analogous" class="tab-btn px-3 py-1.5 rounded-md font-medium transition-all text-gray-500 hover:text-gray-800">Analogous</button>
                    <button data-tab="triadic" class="tab-btn px-3 py-1.5 rounded-md font-medium transition-all text-gray-500 hover:text-gray-800">Triadic</button>
                    <button data-tab="split" class="tab-btn px-3 py-1.5 rounded-md font-medium transition-all text-gray-500 hover:text-gray-800">Split</button>
                    <button data-tab="tetradic" class="tab-btn px-3 py-1.5 rounded-md font-medium transition-all text-gray-500 hover:text-gray-800">Tetradic</button>
                </div>
            </div>

            {{-- Tab description --}}
            <p class="text-xs text-gray-400 mb-6 text-right" id="tabDescription"></p>

            {{-- Related swatches --}}
            <div id="relatedGrid" class="grid grid-cols-2 gap-4"></div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const MAX_COLORS = 3;
        const TOTAL_SWATCHES = 11;
        const LABELS = ['Primary', 'Secondary', 'Tertiary'];

        const colors = [{
            hex: '#EB3DAE'
            , name: ''
            , rawPalette: null
            , fetchId: 0
        }];

        const inputsWrap = document.getElementById('colorInputsWrap');
        const addBtn = document.getElementById('addColorBtn');
        const addBtnLabel = document.getElementById('addColorBtnLabel');
        const addBtnWrap = document.getElementById('addColorBtnWrap');
        const paletteSection = document.getElementById('paletteSection');
        const paletteGrid = document.getElementById('paletteGrid');
        const paletteName = document.getElementById('paletteName');
        const colorDots = document.getElementById('colorDots');
        const paletteLegend = document.getElementById('paletteLegend');
        const loadingBar = document.getElementById('loadingBar');
        const loadingText = document.getElementById('loadingText');
        const relatedGrid = document.getElementById('relatedGrid');
        const relatedTabs = document.getElementById('relatedTabs');
        const tabDescription = document.getElementById('tabDescription');

        let activeTab = 'complementary';

        // ── Utilities ──────────────────────────────────────────
        function debounce(fn, ms) {
            let t;
            return (...a) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...a), ms);
            };
        }

        function isValidHex(h) {
            return /^#[0-9a-fA-F]{6}$/.test(h);
        }

        function hexToRgb(hex) {
            const n = parseInt(hex.replace('#', ''), 16);
            return [(n >> 16) & 255, (n >> 8) & 255, n & 255];
        }

        function rgbToHex(r, g, b) {
            return '#' + [r, g, b]
                .map(v => Math.round(Math.max(0, Math.min(255, v))).toString(16).padStart(2, '0'))
                .join('');
        }

        function lerp(a, b, t) {
            return a + (b - a) * t;
        }

        // ── HSL helpers ────────────────────────────────────────
        function hexToHsl(hex) {
            let [r, g, b] = hexToRgb(hex).map(v => v / 255);
            const max = Math.max(r, g, b)
                , min = Math.min(r, g, b);
            let h, s, l = (max + min) / 2;
            if (max === min) {
                h = s = 0;
            } else {
                const d = max - min;
                s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                switch (max) {
                    case r:
                        h = ((g - b) / d + (g < b ? 6 : 0)) / 6;
                        break;
                    case g:
                        h = ((b - r) / d + 2) / 6;
                        break;
                    case b:
                        h = ((r - g) / d + 4) / 6;
                        break;
                }
            }
            return [h * 360, s * 100, l * 100];
        }

        function hslToHex(h, s, l) {
            h = ((h % 360) + 360) % 360;
            s = Math.max(0, Math.min(100, s)) / 100;
            l = Math.max(0, Math.min(100, l)) / 100;
            const a = s * Math.min(l, 1 - l);
            const f = n => {
                const k = (n + h / 30) % 12;
                return l - a * Math.max(Math.min(k - 3, 9 - k, 1), -1);
            };
            return rgbToHex(Math.round(f(0) * 255), Math.round(f(8) * 255), Math.round(f(4) * 255));
        }
        // Generate a mini shade strip (5 swatches) for a given hue
        function miniStrip(hex) {
            const [h, s, l] = hexToHsl(hex);
            return [
                hslToHex(h, s, Math.min(l + 30, 95))
                , hslToHex(h, s, Math.min(l + 15, 85))
                , hex
                , hslToHex(h, s, Math.max(l - 15, 15))
                , hslToHex(h, s, Math.max(l - 30, 5))
            , ];
        }

        // ── Color theory generators ────────────────────────────
        const SCHEMES = {
            complementary: {
                label: 'Complementary'
                , desc: 'Opposite on the color wheel — high contrast, bold pairings.'
                , generate(hex) {
                    const [h, s, l] = hexToHsl(hex);
                    return [{
                            label: 'Base'
                            , hex
                        }
                        , {
                            label: 'Complement'
                            , hex: hslToHex(h + 180, s, l)
                        }
                    , ];
                }
            }
            , analogous: {
                label: 'Analogous'
                , desc: 'Adjacent on the color wheel — harmonious, natural feel.'
                , generate(hex) {
                    const [h, s, l] = hexToHsl(hex);
                    return [{
                            label: '-30°'
                            , hex: hslToHex(h - 30, s, l)
                        }
                        , {
                            label: 'Base'
                            , hex
                        }
                        , {
                            label: '+30°'
                            , hex: hslToHex(h + 30, s, l)
                        }
                        , {
                            label: '+60°'
                            , hex: hslToHex(h + 60, s, l)
                        }
                    , ];
                }
            }
            , triadic: {
                label: 'Triadic'
                , desc: 'Three equally spaced hues — vibrant yet balanced.'
                , generate(hex) {
                    const [h, s, l] = hexToHsl(hex);
                    return [{
                            label: 'Base'
                            , hex
                        }
                        , {
                            label: '+120°'
                            , hex: hslToHex(h + 120, s, l)
                        }
                        , {
                            label: '+240°'
                            , hex: hslToHex(h + 240, s, l)
                        }
                    , ];
                }
            }
            , split: {
                label: 'Split-Complementary'
                , desc: 'Complement split into two — contrast without tension.'
                , generate(hex) {
                    const [h, s, l] = hexToHsl(hex);
                    return [{
                            label: 'Base'
                            , hex
                        }
                        , {
                            label: 'Split A'
                            , hex: hslToHex(h + 150, s, l)
                        }
                        , {
                            label: 'Split B'
                            , hex: hslToHex(h + 210, s, l)
                        }
                    , ];
                }
            }
            , tetradic: {
                label: 'Tetradic'
                , desc: 'Four hues forming a rectangle — rich, complex palettes.'
                , generate(hex) {
                    const [h, s, l] = hexToHsl(hex);
                    return [{
                            label: 'Base'
                            , hex
                        }
                        , {
                            label: '+90°'
                            , hex: hslToHex(h + 90, s, l)
                        }
                        , {
                            label: '+180°'
                            , hex: hslToHex(h + 180, s, l)
                        }
                        , {
                            label: '+270°'
                            , hex: hslToHex(h + 270, s, l)
                        }
                    , ];
                }
            }
        , };

        // ── Render related section ─────────────────────────────
        function renderRelated() {
            const primaryHex = colors[0].hex;
            if (!isValidHex(primaryHex)) return;

            const scheme = SCHEMES[activeTab];
            tabDescription.textContent = scheme.desc;

            const pairs = scheme.generate(primaryHex);

            relatedGrid.style.gridTemplateColumns = `repeat(${Math.min(pairs.length, 4)}, 1fr)`;
            relatedGrid.innerHTML = '';

            pairs.forEach(({
                label
                , hex
            }) => {
                const strip = miniStrip(hex);
                const [h, s, l] = hexToHsl(hex);

                const card = document.createElement('div');
                card.className = 'bg-white rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow';
                card.innerHTML = `
                <div class="flex gap-1 mb-3 rounded-xl overflow-hidden h-14">
                    ${strip.map(c => `
                        <div class="flex-1 cursor-pointer relative group/swatch transition-all duration-200 hover:flex-[1.5]"
                             style="background:${c}"
                             data-hex="${c.toUpperCase()}"
                             title="${c.toUpperCase()}">
                            <div class="absolute inset-0 flex items-end justify-center pb-1 opacity-0 group-hover/swatch:opacity-100 transition-opacity">
                                <span class="text-white font-mono" style="font-size:7px;text-shadow:0 1px 2px rgba(0,0,0,.5)">${c.toUpperCase()}</span>
                            </div>
                        </div>
                    `).join('')}
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">${label}</p>
                        <p class="text-xs text-gray-400 font-mono mt-0.5">${hex.toUpperCase()}</p>
                        <p class="text-xs text-gray-400 mt-0.5">H:${Math.round(h)}° S:${Math.round(s)}% L:${Math.round(l)}%</p>
                    </div>
                    <button class="copy-card-btn w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors flex-shrink-0" title="Copy hex" data-hex="${hex}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                    </button>
                </div>
            `;

                // Copy main hex on button click
                card.querySelector('.copy-card-btn').addEventListener('click', () => {
                    navigator.clipboard.writeText(hex).then(() => showNotification('Copied ' + hex.toUpperCase()));
                });

                // Copy swatch hex on swatch click
                card.querySelectorAll('[data-hex]').forEach(sw => {
                    sw.addEventListener('click', () => {
                        const c = sw.dataset.hex;
                        navigator.clipboard.writeText(c).then(() => showNotification('Copied ' + c));
                    });
                });

                // Apply to primary
                card.addEventListener('dblclick', () => {
                    colors[0].hex = hex;
                    rebuildRows();
                    fetchPalette(0);
                    showNotification('Applied ' + hex.toUpperCase() + ' as primary');
                });

                relatedGrid.appendChild(card);
            });
        }

        // ── Tab switching ──────────────────────────────────────
        function setActiveTab(tab) {
            activeTab = tab;
            relatedTabs.querySelectorAll('.tab-btn').forEach(btn => {
                const isActive = btn.dataset.tab === tab;
                btn.classList.toggle('bg-white', isActive);
                btn.classList.toggle('shadow-sm', isActive);
                btn.classList.toggle('text-gray-900', isActive);
                btn.classList.toggle('text-gray-500', !isActive);
            });
            renderRelated();
        }
        relatedTabs.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => setActiveTab(btn.dataset.tab));
        });
        setActiveTab('complementary');

        // ── Notification ───────────────────────────────────────
        function showNotification(msg) {
            const el = document.createElement('div');
            el.textContent = msg;
            el.className = 'fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-full text-sm font-medium shadow-lg z-50';
            el.style.animation = 'fadeInOut 2s ease forwards';
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 2000);
        }

        // ── Blend palette ──────────────────────────────────────
        function buildBlendedPalette() {
            const ready = colors.filter(c => c.rawPalette && c.rawPalette.length);
            if (!ready.length) return [];
            const allStops = ready.flatMap(c => c.rawPalette.map(s => s.hex));
            const result = [];
            for (let i = 0; i < TOTAL_SWATCHES; i++) {
                const t = i / (TOTAL_SWATCHES - 1);
                const pos = t * (allStops.length - 1);
                const lo = Math.floor(pos);
                const hi = Math.min(lo + 1, allStops.length - 1);
                const frac = pos - lo;
                const a = hexToRgb(allStops[lo]);
                const b = hexToRgb(allStops[hi]);
                result.push(rgbToHex(lerp(a[0], b[0], frac), lerp(a[1], b[1], frac), lerp(a[2], b[2], frac)));
            }
            return result;
        }

        // ── Render main palette ────────────────────────────────
        function renderPalette() {
            updateLoading();
            const swatches = buildBlendedPalette();
            const hasAny = colors.some(c => c.rawPalette && c.rawPalette.length > 0);

            if (!hasAny) {
                paletteSection.classList.add('hidden');
                return;
            }
            paletteSection.classList.remove('hidden');

            const ready = colors.filter(c => c.rawPalette && c.rawPalette.length > 0);
            const names = ready.map(c => c.name).filter(Boolean);
            paletteName.textContent = names.length ?
                names.join(' × ') :
                (ready.length > 1 ? 'Blended Palette' : 'Color Palette');

            colorDots.innerHTML = colors.map(c =>
                `<div class="w-4 h-4 rounded-full border border-white shadow-sm" style="background:${c.hex};opacity:${c.rawPalette ? 1 : 0.3}"></div>`
            ).join('');

            paletteLegend.innerHTML = colors.map((c, i) =>
                `<span class="flex items-center gap-1 ${c.rawPalette ? '' : 'opacity-40'}">
                <span class="inline-block w-2 h-2 rounded-full" style="background:${c.hex}"></span>
                ${LABELS[i]}: ${c.hex.toUpperCase()}
                ${c.rawPalette === null ? '<span class="text-gray-400">(loading…)</span>' : ''}
            </span>`
            ).join('');

            if (!swatches.length) return;
            paletteGrid.innerHTML = '';
            swatches.forEach(hex => {
                const block = document.createElement('div');
                block.style.backgroundColor = hex;
                block.className = 'aspect-square relative group cursor-pointer transition-all duration-300 rounded-xl hover:shadow-lg hover:scale-105 hover:z-10';
                block.innerHTML = `
                <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-b from-transparent to-black/30"></div>
                <div class="absolute bottom-2 left-0 right-0 text-center font-medium text-white opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-1 group-hover:translate-y-0" style="font-size:9px">${hex.toUpperCase()}</div>
            `;
                block.addEventListener('click', e => {
                    navigator.clipboard.writeText(hex).then(() => showNotification('Copied ' + hex));
                    const ripple = document.createElement('div');
                    ripple.className = 'absolute bg-white rounded-full opacity-30 pointer-events-none';
                    ripple.style.animation = 'ripple 1s linear';
                    const rect = block.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height) * 2;
                    Object.assign(ripple.style, {
                        width: size + 'px'
                        , height: size + 'px'
                        , left: (e.clientX - rect.left - size / 2) + 'px'
                        , top: (e.clientY - rect.top - size / 2) + 'px'
                    , });
                    block.appendChild(ripple);
                    setTimeout(() => ripple.remove(), 1000);
                });
                paletteGrid.appendChild(block);
            });

            // Re-render related whenever palette updates
            renderRelated();
        }

        function updateLoading() {
            const anyPending = colors.some(c => c.rawPalette === null);
            loadingBar.classList.toggle('hidden', !anyPending);
            if (anyPending) {
                const n = colors.filter(c => c.rawPalette === null).length;
                loadingText.textContent = `Generating ${n > 1 ? n + ' palettes' : 'palette'}…`;
            }
        }

        // ── Fetch ──────────────────────────────────────────────
        function fetchPalette(index) {
            const entry = colors[index];
            if (!isValidHex(entry.hex)) return;

            entry.rawPalette = null;
            const myId = ++entry.fetchId;
            updateLoading();

            fetch('/generate-palette', {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    , }
                    , body: JSON.stringify({
                        color: entry.hex
                    })
                , })
                .then(res => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(data => {
                    if (entry.fetchId !== myId) return;
                    if (data.error) throw new Error(data.error);
                    const palette = Array.isArray(data.palette) ? data.palette.filter(s => s && s.hex) : [];
                    if (!palette.length) throw new Error('Empty palette');
                    entry.name = data.colorName || '';
                    entry.rawPalette = palette;
                    renderPalette();
                })
                .catch(err => {
                    if (entry.fetchId !== myId) return;
                    console.error(err);
                    entry.rawPalette = [];
                    showNotification('Could not generate palette for ' + entry.hex);
                    renderPalette();
                });
        }

        const debouncedFetch = debounce(fetchPalette, 400);

        // ── Input row builder ──────────────────────────────────
        function buildRow(index) {
            const hex = colors[index].hex;
            const row = document.createElement('div');
            row.dataset.index = index;
            row.innerHTML = `
            <div class="bg-white rounded-full border shadow-sm overflow-hidden flex items-center p-1 mb-3">
                <div class="w-10 h-10 rounded-full ml-1 relative flex-shrink-0" style="background:${hex}">
                    <input type="color" value="${hex}" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full rounded-full">
                </div>
                <input type="text" value="${hex}" placeholder="${hex}" class="flex-1 px-4 py-2 focus:outline-none text-gray-700 text-sm min-w-0">
                <span class="text-xs font-medium text-gray-400 mr-2 uppercase tracking-wide flex-shrink-0">${LABELS[index]}</span>
                ${index > 0 ? `<button class="remove-btn text-gray-300 hover:text-red-400 transition-colors mr-2 text-xl leading-none flex-shrink-0">&times;</button>` : ''}
            </div>
        `;
            const preview = row.querySelector('[style]');
            const picker = row.querySelector('input[type=color]');
            const textIn = row.querySelector('input[type=text]');

            picker.addEventListener('input', e => {
                preview.style.background = e.target.value;
                textIn.value = e.target.value;
            });
            picker.addEventListener('change', e => {
                colors[index].hex = e.target.value;
                fetchPalette(index);
            });
            textIn.addEventListener('input', e => {
                let v = e.target.value.trim();
                if (v.length === 6 && !v.startsWith('#')) {
                    v = '#' + v;
                    e.target.value = v;
                }
                if (isValidHex(v)) {
                    preview.style.background = v;
                    picker.value = v;
                    colors[index].hex = v;
                    debouncedFetch(index);
                }
            });
            textIn.addEventListener('keydown', e => {
                if (e.key !== 'Enter') return;
                let v = e.target.value.trim();
                if (v.length === 6 && !v.startsWith('#')) v = '#' + v;
                if (isValidHex(v)) {
                    colors[index].hex = v;
                    fetchPalette(index);
                } else showNotification('Invalid hex — use format #RRGGBB');
            });
            if (index > 0) row.querySelector('.remove-btn').addEventListener('click', () => removeColor(index));
            return row;
        }

        function rebuildRows() {
            inputsWrap.innerHTML = '';
            colors.forEach((_, i) => inputsWrap.appendChild(buildRow(i)));
            updateAddBtn();
        }

        function updateAddBtn() {
            if (colors.length >= MAX_COLORS) {
                addBtnWrap.classList.add('hidden');
                return;
            }
            addBtnWrap.classList.remove('hidden');
            addBtnLabel.textContent = colors.length === 1 ? 'Add secondary color' : 'Add tertiary color';
        }

        addBtn.addEventListener('click', () => {
            if (colors.length >= MAX_COLORS) return;
            const defaults = ['#EB3D7A', '#3DEBCF'];
            colors.push({
                hex: defaults[colors.length - 1] || '#888888'
                , name: ''
                , rawPalette: null
                , fetchId: 0
            });
            rebuildRows();
            fetchPalette(colors.length - 1);
        });

        function removeColor(index) {
            colors.splice(index, 1);
            rebuildRows();
            renderPalette();
        }

        window.exportPalette = function() {
            const swatches = buildBlendedPalette();
            if (!swatches.length) {
                showNotification('No palette to export yet');
                return;
            }
            const ready = colors.filter(c => c.rawPalette && c.rawPalette.length);
            const prefix = ready.length === 1 ?
                (ready[0].name || 'color').toLowerCase().replace(/\s+/g, '-') :
                'blend';
            const lines = [`/* Wernoin — ${paletteName.textContent} */`];
            swatches.forEach((hex, i) => lines.push(`--color-${prefix}-${(i + 1) * 100}: ${hex};`));
            navigator.clipboard.writeText(lines.join('\n')).then(() => showNotification('CSS variables copied!'));
        };

        // ── Init ───────────────────────────────────────────────
        rebuildRows();
        fetchPalette(0);
    });

</script>

<style>
    @keyframes ripple {
        from {
            transform: scale(0);
            opacity: 0.4;
        }

        to {
            transform: scale(1);
            opacity: 0;
        }
    }

    @keyframes fadeInOut {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        10% {
            opacity: 1;
            transform: translateY(0);
        }

        90% {
            opacity: 1;
            transform: translateY(0);
        }

        100% {
            opacity: 0;
            transform: translateY(-20px);
        }
    }

    @keyframes loadingBar {
        0% {
            width: 5%;
            background: #EB3DAE;
        }

        50% {
            width: 85%;
            background: #3D9AEB;
        }

        100% {
            width: 5%;
            background: #3DEB8A;
        }
    }

    .animate-loading-bar {
        animation: loadingBar 1.6s ease-in-out infinite;
    }

</style>
@endpush
@endsection
