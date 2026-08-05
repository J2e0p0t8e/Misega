<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

// ─── Generate random floating pages ───────────────────────────────────────
const pages = ref([]);
const particles = ref([]);
const parallaxOffset = ref(0);
let rafId = null;
let targetOffset = 0;
let currentOffset = 0;

const generateScene = () => {
    // 10 floating manga page shapes
    const pageCount = 10;
    const pageShapes = [];
    for (let i = 0; i < pageCount; i++) {
        pageShapes.push({
            id: i,
            left: Math.random() * 100,       // % horizontal
            top: Math.random() * 120 - 10,   // % vertical (allow offscreen)
            width: 60 + Math.random() * 80,  // px width
            rotateZ: (Math.random() - 0.5) * 40,  // -20 to 20 deg
            rotateX: (Math.random() - 0.5) * 30,
            rotateY: (Math.random() - 0.5) * 25,
            opacity: 0.025 + Math.random() * 0.05, // 2.5% – 7.5% opacity
            delay: Math.random() * 6,         // animation delay seconds
            duration: 8 + Math.random() * 10, // animation duration seconds
            isRed: Math.random() > 0.65,      // 35% chance of red tint
            scale: 0.7 + Math.random() * 0.8,
            floatAmplitude: 20 + Math.random() * 30,
        });
    }
    pages.value = pageShapes;

    // 24 small floating particles
    const particleData = [];
    for (let i = 0; i < 24; i++) {
        particleData.push({
            id: i,
            left: Math.random() * 100,
            top: Math.random() * 100,
            size: 2 + Math.random() * 4,
            isRed: Math.random() > 0.5,
            opacity: 0.15 + Math.random() * 0.25,
            delay: Math.random() * 8,
            duration: 6 + Math.random() * 8,
            driftX: (Math.random() - 0.5) * 40,
        });
    }
    particles.value = particleData;
};

// ─── Smooth parallax scroll with lerp ─────────────────────────────────────
const onScroll = () => {
    // Target parallax: moves at 25% of scroll speed
    targetOffset = window.scrollY * 0.25;
};

const animateParallax = () => {
    // Lerp for butter-smooth motion
    currentOffset += (targetOffset - currentOffset) * 0.08;
    parallaxOffset.value = currentOffset;
    rafId = requestAnimationFrame(animateParallax);
};

onMounted(() => {
    generateScene();
    window.addEventListener('scroll', onScroll, { passive: true });
    rafId = requestAnimationFrame(animateParallax);
});

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
    if (rafId) cancelAnimationFrame(rafId);
});
</script>

<template>
    <!-- Full-bleed background scene, pointer-events off so it never blocks clicks -->
    <div
        class="absolute inset-0 overflow-hidden pointer-events-none z-0"
        aria-hidden="true"
    >
        <!-- Parallax wrapper — moves slower than page content -->
        <div
            :style="{ transform: `translateY(${-parallaxOffset}px)`, willChange: 'transform' }"
            class="absolute inset-0"
            style="transform-style: preserve-3d; perspective: 800px;"
        >
            <!-- ─── Floating Manga Pages ─────────────────────────────── -->
            <div
                v-for="page in pages"
                :key="`page-${page.id}`"
                class="absolute rounded-sm"
                :style="{
                    left: `${page.left}%`,
                    top: `${page.top}%`,
                    width: `${page.width * page.scale}px`,
                    aspectRatio: '3 / 4',
                    transform: `perspective(800px) rotateX(${page.rotateX}deg) rotateY(${page.rotateY}deg) rotateZ(${page.rotateZ}deg)`,
                    opacity: page.opacity,
                    background: page.isRed
                        ? 'rgba(188, 0, 45, 0.45)'
                        : 'rgba(20, 20, 30, 0.5)',
                    border: page.isRed
                        ? '1px solid rgba(188, 0, 45, 0.3)'
                        : '1px solid rgba(0, 0, 0, 0.25)',
                    animationName: 'floatPage',
                    animationDuration: `${page.duration}s`,
                    animationDelay: `${page.delay}s`,
                    animationTimingFunction: 'ease-in-out',
                    animationIterationCount: 'infinite',
                    animationDirection: 'alternate',
                    '--float-y': `${page.floatAmplitude}px`,
                    '--float-rx': `${(page.rotateX + 15).toFixed(1)}deg`,
                    '--float-ry': `${(page.rotateY - 12).toFixed(1)}deg`,
                }"
            >
                <!-- Inner page line-art detail (very subtle) -->
                <div class="absolute inset-3 flex flex-col gap-1.5 justify-end opacity-60">
                    <div class="h-px w-4/5 rounded" :class="page.isRed ? 'bg-red-300/50' : 'bg-gray-400/30'"></div>
                    <div class="h-px w-2/3 rounded" :class="page.isRed ? 'bg-red-300/40' : 'bg-gray-400/20'"></div>
                    <div class="h-px w-3/4 rounded" :class="page.isRed ? 'bg-red-300/30' : 'bg-gray-400/20'"></div>
                </div>
            </div>

            <!-- ─── Floating 3D Particles ───────────────────────────── -->
            <div
                v-for="p in particles"
                :key="`particle-${p.id}`"
                class="absolute rounded-full"
                :style="{
                    left: `${p.left}%`,
                    top: `${p.top}%`,
                    width: `${p.size}px`,
                    height: `${p.size}px`,
                    opacity: p.opacity,
                    background: p.isRed ? 'rgb(188, 0, 45)' : 'rgb(20, 20, 30)',
                    boxShadow: p.isRed
                        ? `0 0 ${p.size * 3}px rgba(188, 0, 45, 0.6)`
                        : `0 0 ${p.size * 2}px rgba(0, 0, 0, 0.3)`,
                    animationName: 'floatParticle',
                    animationDuration: `${p.duration}s`,
                    animationDelay: `${p.delay}s`,
                    animationTimingFunction: 'ease-in-out',
                    animationIterationCount: 'infinite',
                    animationDirection: 'alternate',
                    '--drift-x': `${p.driftX}px`,
                }"
            ></div>
        </div>
    </div>
</template>

<style scoped>
@keyframes floatPage {
    from {
        transform: perspective(800px)
            rotateX(var(--float-rx, 0deg))
            rotateY(var(--float-ry, 0deg))
            rotateZ(inherit)
            translateY(0px);
    }
    to {
        transform: perspective(800px)
            rotateX(calc(var(--float-rx, 0deg) - 20deg))
            rotateY(calc(var(--float-ry, 0deg) + 18deg))
            rotateZ(inherit)
            translateY(var(--float-y, 25px));
    }
}

@keyframes floatParticle {
    from {
        transform: translateY(0px) translateX(0px) scale(1);
        opacity: inherit;
    }
    to {
        transform: translateY(-45px) translateX(var(--drift-x, 20px)) scale(1.4);
        opacity: calc(inherit * 0.5);
    }
}
</style>
