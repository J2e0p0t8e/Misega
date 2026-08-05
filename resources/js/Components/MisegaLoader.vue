<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';

// ─── State ─────────────────────────────────────────────────────────────────
const isVisible   = ref(true);
const isFadingOut = ref(false);
const bookPhase   = ref(0); // 0=idle, 1=drawing, 2=done

// ─── Kanji scene: depth-of-field via scale + blur ──────────────────────────
// scale > 1.5 → close → blurred heavily
// scale ~1    → focal plane → sharp
// scale < 0.5 → far → blurred moderately
const kanjis = [
    // --- Far plane (small, back-blurred) ---
    { id: 0,  char: '術', left: 4,  top: 5,   scale: 0.28, blur: 10, opacity: 0.08, dur: 12, delay: 0,   color: '#BC002D', rotDir: 1  },
    { id: 1,  char: '書', left: 88, top: 10,  scale: 0.32, blur: 9,  opacity: 0.09, dur: 10, delay: 2.5, color: '#ffffff', rotDir: -1 },
    { id: 2,  char: '読', left: 50, top: 85,  scale: 0.40, blur: 8,  opacity: 0.11, dur: 11, delay: 4,   color: '#BC002D', rotDir: 1  },
    { id: 3,  char: '芸', left: 78, top: 78,  scale: 0.45, blur: 7,  opacity: 0.12, dur: 9,  delay: 6,   color: '#ffffff', rotDir: -1 },
    // --- Near-far (slightly blurred) ---
    { id: 4,  char: '漫', left: 20, top: 72,  scale: 0.62, blur: 4,  opacity: 0.16, dur: 14, delay: 1,   color: '#ffffff', rotDir: 1  },
    { id: 5,  char: '画', left: 68, top: 55,  scale: 0.70, blur: 3,  opacity: 0.18, dur: 11, delay: 3,   color: '#BC002D', rotDir: -1 },
    // --- Focal plane (sharp, medium size) ---
    { id: 6,  char: '芸', left: 8,  top: 35,  scale: 1.0,  blur: 0,  opacity: 0.28, dur: 13, delay: 0.5, color: '#BC002D', rotDir: 1  },
    { id: 7,  char: '読', left: 84, top: 32,  scale: 0.95, blur: 0,  opacity: 0.25, dur: 10, delay: 5,   color: '#ffffff', rotDir: -1 },
    { id: 8,  char: '書', left: 38, top: 14,  scale: 1.05, blur: 0,  opacity: 0.30, dur: 12, delay: 2,   color: '#ffffff', rotDir: 1  },
    // --- Near-front (slightly blurred) ---
    { id: 9,  char: '術', left: 72, top: 20,  scale: 1.5,  blur: 3,  opacity: 0.18, dur: 9,  delay: 1.5, color: '#BC002D', rotDir: -1 },
    { id: 10, char: '漫', left: 22, top: 52,  scale: 1.65, blur: 4,  opacity: 0.15, dur: 10, delay: 3.5, color: '#ffffff', rotDir: 1  },
    // --- Front plane (large, heavy blur) ---
    { id: 11, char: '画', left: 60, top: 5,   scale: 2.6,  blur: 8,  opacity: 0.09, dur: 8,  delay: 0,   color: '#ffffff', rotDir: -1 },
    { id: 12, char: '芸', left: 5,  top: 68,  scale: 2.9,  blur: 10, opacity: 0.07, dur: 10, delay: 2,   color: '#BC002D', rotDir: 1  },
    { id: 13, char: '漫', left: 80, top: 60,  scale: 3.2,  blur: 12, opacity: 0.06, dur: 9,  delay: 4,   color: '#ffffff', rotDir: -1 },
];

// ─── Loader lifecycle ───────────────────────────────────────────────────────
const startFadeOut = () => {
    if (isFadingOut.value) return;
    isFadingOut.value = true;
    setTimeout(() => { isVisible.value = false; }, 900);
};

let unsubFinish = null;
let unsubStart  = null;
let fallbackTimer = null;

onMounted(() => {
    // Trigger SVG draw after a brief moment
    setTimeout(() => { bookPhase.value = 1; }, 200);
    setTimeout(() => { bookPhase.value = 2; }, 1800);

    let inertiaReady  = false;
    let minTimePassed = false;

    const tryFadeOut = () => {
        if (inertiaReady && minTimePassed) startFadeOut();
    };

    // Minimum display: 2.2s (enough for the SVG draw to complete)
    setTimeout(() => { minTimePassed = true; tryFadeOut(); }, 2200);

    // Inertia finish event on initial load
    unsubFinish = router.on('finish', () => { inertiaReady = true; tryFadeOut(); });

    // Hard fallback: never block the user more than 5s
    fallbackTimer = setTimeout(() => { if (isVisible.value) startFadeOut(); }, 5000);
});

onUnmounted(() => {
    if (unsubFinish) unsubFinish();
    if (unsubStart)  unsubStart();
    if (fallbackTimer) clearTimeout(fallbackTimer);
});
</script>

<template>
    <Transition
        name="loader-fade"
        appear
    >
        <div
            v-if="isVisible"
            class="fixed inset-0 z-[9999] flex items-center justify-center overflow-hidden select-none"
            :class="isFadingOut ? 'pointer-events-none' : ''"
            :style="{ opacity: isFadingOut ? 0 : 1, transition: 'opacity 0.9s cubic-bezier(0.4, 0, 0.2, 1)' }"
            aria-hidden="true"
        >
            <!-- ─── Background ───────────────────────────────────────────── -->
            <div class="absolute inset-0 bg-[#09090D]">
                <!-- Subtle radial vignette -->
                <div class="absolute inset-0" style="background: radial-gradient(ellipse 80% 70% at 50% 50%, rgba(188,0,45,0.05) 0%, transparent 70%);"></div>
                <!-- Scanline texture overlay -->
                <div class="absolute inset-0 opacity-[0.025]" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(255,255,255,0.5) 2px, rgba(255,255,255,0.5) 3px);"></div>
            </div>

            <!-- ─── Kanji 3D Scene (depth-of-field via scale + blur) ──────── -->
            <div class="absolute inset-0 overflow-hidden" style="perspective: 900px;">
                <div
                    v-for="k in kanjis"
                    :key="k.id"
                    class="absolute font-gang leading-none"
                    :style="{
                        left: `${k.left}%`,
                        top: `${k.top}%`,
                        fontSize: `${k.scale * 6}rem`,
                        color: k.color,
                        opacity: k.opacity,
                        filter: k.blur > 0 ? `blur(${k.blur}px)` : 'none',
                        animationName: 'kanjiFloat',
                        animationDuration: `${k.dur}s`,
                        animationDelay: `${k.delay}s`,
                        animationTimingFunction: 'ease-in-out',
                        animationIterationCount: 'infinite',
                        animationDirection: 'alternate',
                        '--rot-dir': k.rotDir,
                        transformOrigin: 'center center',
                        willChange: 'transform',
                    }"
                >{{ k.char }}</div>
            </div>

            <!-- ─── Center Branding ──────────────────────────────────────── -->
            <div class="relative z-10 flex flex-col items-center gap-6">

                <!-- SVG Open Book — draws itself via stroke-dashoffset -->
                <div class="relative w-24 h-24">
                    <svg
                        viewBox="0 0 100 100"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-full h-full overflow-visible"
                    >
                        <!-- Left Page -->
                        <path
                            pathLength="1"
                            d="M 50,22 C 36,14 18,18 10,22 L 10,76 C 18,72 36,68 50,78 Z"
                            stroke="#BC002D"
                            stroke-width="3"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            fill="rgba(188,0,45,0.06)"
                            :style="bookPhase >= 1 ? 'stroke-dasharray:1; stroke-dashoffset:0; transition: stroke-dashoffset 1.2s cubic-bezier(0.25,1,0.5,1) 0.2s' : 'stroke-dasharray:1; stroke-dashoffset:1'"
                        />
                        <!-- Right Page -->
                        <path
                            pathLength="1"
                            d="M 50,22 C 64,14 82,18 90,22 L 90,76 C 82,72 64,68 50,78 Z"
                            stroke="#ffffff"
                            stroke-width="3"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            fill="rgba(255,255,255,0.04)"
                            :style="bookPhase >= 1 ? 'stroke-dasharray:1; stroke-dashoffset:0; transition: stroke-dashoffset 1.2s cubic-bezier(0.25,1,0.5,1) 0.5s' : 'stroke-dasharray:1; stroke-dashoffset:1'"
                        />
                        <!-- Spine -->
                        <line
                            x1="50" y1="22" x2="50" y2="78"
                            stroke="#BC002D"
                            stroke-width="3.5"
                            stroke-linecap="round"
                            pathLength="1"
                            :style="bookPhase >= 1 ? 'stroke-dasharray:1; stroke-dashoffset:0; transition: stroke-dashoffset 0.6s ease-out 0.1s' : 'stroke-dasharray:1; stroke-dashoffset:1'"
                        />
                        <!-- Left page inner lines -->
                        <line x1="22" y1="36" x2="44" y2="33" stroke="rgba(188,0,45,0.4)" stroke-width="1.5" stroke-linecap="round"
                            pathLength="1"
                            :style="bookPhase >= 1 ? 'stroke-dasharray:1; stroke-dashoffset:0; transition: stroke-dashoffset 0.8s ease-out 0.9s' : 'stroke-dasharray:1; stroke-dashoffset:1'"
                        />
                        <line x1="22" y1="44" x2="44" y2="41" stroke="rgba(188,0,45,0.3)" stroke-width="1.5" stroke-linecap="round"
                            pathLength="1"
                            :style="bookPhase >= 1 ? 'stroke-dasharray:1; stroke-dashoffset:0; transition: stroke-dashoffset 0.8s ease-out 1.0s' : 'stroke-dasharray:1; stroke-dashoffset:1'"
                        />
                        <!-- Right page inner lines -->
                        <line x1="56" y1="33" x2="78" y2="36" stroke="rgba(255,255,255,0.25)" stroke-width="1.5" stroke-linecap="round"
                            pathLength="1"
                            :style="bookPhase >= 1 ? 'stroke-dasharray:1; stroke-dashoffset:0; transition: stroke-dashoffset 0.8s ease-out 1.1s' : 'stroke-dasharray:1; stroke-dashoffset:1'"
                        />
                        <line x1="56" y1="41" x2="78" y2="44" stroke="rgba(255,255,255,0.18)" stroke-width="1.5" stroke-linecap="round"
                            pathLength="1"
                            :style="bookPhase >= 1 ? 'stroke-dasharray:1; stroke-dashoffset:0; transition: stroke-dashoffset 0.8s ease-out 1.2s' : 'stroke-dasharray:1; stroke-dashoffset:1'"
                        />
                    </svg>
                </div>

                <!-- MISEGA Logotype — fades in after book draws -->
                <div
                    class="text-center"
                    :style="{ opacity: bookPhase >= 1 ? 1 : 0, transform: bookPhase >= 1 ? 'translateY(0)' : 'translateY(12px)', transition: 'opacity 0.8s ease 1.0s, transform 0.8s ease 1.0s' }"
                >
                    <h1 class="font-gang text-5xl sm:text-6xl font-extrabold tracking-widest text-white">
                        MI<span class="text-[#BC002D]">SEGA</span>
                    </h1>
                    <p class="text-gray-500 text-xs font-semibold tracking-[0.3em] uppercase mt-2">
                        L'Art du Manga
                    </p>
                </div>

                <!-- Loading bar -->
                <div
                    class="w-40 h-px bg-white/10 rounded-full overflow-hidden mt-2"
                    :style="{ opacity: bookPhase >= 1 ? 1 : 0, transition: 'opacity 0.5s ease 1.4s' }"
                >
                    <div
                        class="h-full bg-gradient-to-r from-transparent via-[#BC002D] to-transparent rounded-full"
                        :style="{ animation: 'loadingShimmer 1.8s ease-in-out infinite', backgroundSize: '200% 100%' }"
                    ></div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
@keyframes kanjiFloat {
    0% {
        transform:
            translateY(0px)
            rotateZ(0deg)
            rotateY(0deg);
    }
    100% {
        transform:
            translateY(calc(var(--rot-dir, 1) * -28px))
            rotateZ(calc(var(--rot-dir, 1) * 8deg))
            rotateY(calc(var(--rot-dir, 1) * 12deg));
    }
}

@keyframes loadingShimmer {
    0%   { background-position: 200% center; }
    100% { background-position: -200% center; }
}
</style>
