<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex font-sans">

        <!-- ─── Left decorative panel (desktop only) ─────────────────── -->
        <div class="hidden lg:flex lg:w-[42%] xl:w-[38%] flex-col relative bg-[#09090D] overflow-hidden shrink-0">

            <!-- Top red accent line -->
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-primary via-primary-light to-transparent"></div>

            <!-- Floating kanji — purely decorative, very subtle -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none select-none" aria-hidden="true">
                <span class="absolute -top-4 -left-4 font-gang text-[200px] leading-none text-white/[0.025]">漫</span>
                <span class="absolute top-1/4 right-2 font-gang text-[110px] leading-none text-primary/[0.07]">画</span>
                <span class="absolute top-1/2 -left-2 font-gang text-[130px] leading-none text-white/[0.03]">読</span>
                <span class="absolute bottom-1/4 right-8 font-gang text-[85px] leading-none text-primary/[0.06]">書</span>
                <span class="absolute bottom-10 left-6 font-gang text-[150px] leading-none text-white/[0.02]">芸</span>
            </div>

            <!-- Radial ambient glow -->
            <div
                class="absolute inset-0 pointer-events-none"
                style="background: radial-gradient(ellipse 70% 55% at 25% 45%, rgba(188,0,45,0.10) 0%, transparent 75%);"
            ></div>

            <!-- Panel content -->
            <div class="relative z-10 flex flex-col h-full px-10 py-12">

                <!-- Logo link back to home -->
                <Link href="/" class="inline-flex items-center gap-3">
                    <img src="/img/misega-icon-book-only-transparent.png" alt="MISEGA logo" class="w-9 h-9 object-contain" />
                    <span class="font-gang text-2xl tracking-[0.2em] text-white">
                        MI<span class="text-primary">SEGA</span>
                    </span>
                </Link>

                <!-- Bottom tagline block -->
                <div class="mt-auto">
                    <span class="inline-block text-[10px] font-bold tracking-[0.35em] uppercase text-primary/80 mb-4">
                        Votre boutique manga premium
                    </span>
                    <h2 class="font-gang text-4xl xl:text-5xl text-white leading-[1.15] mb-5">
                        L'Art du<br/>
                        <span class="text-primary">Manga</span>
                    </h2>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-[260px]">
                        Des milliers de titres, des classiques aux dernières sorties. Livraison rapide, qualité garantie.
                    </p>

                    <div class="mt-8 h-px w-12 bg-gradient-to-r from-primary to-transparent rounded-full"></div>
                    <p class="mt-5 text-xs text-gray-700 font-medium">© 2026 MISEGA</p>
                </div>
            </div>
        </div>

        <!-- ─── Right form panel ──────────────────────────────────────── -->
        <div class="flex-1 flex flex-col items-center justify-center bg-white px-6 py-14 sm:px-12">

            <!-- Mobile logo (only visible on small screens) -->
            <Link href="/" class="flex items-center gap-2 mb-10 lg:hidden">
                <img src="/img/favicon.png" alt="MISEGA" class="w-8 h-8 rounded-md" />
                <span class="font-gang text-xl tracking-[0.2em] text-gray-900">
                    MI<span class="text-primary">SEGA</span>
                </span>
            </Link>

            <!-- Form slot, constrained width -->
            <div class="w-full max-w-[380px]">
                <slot />
            </div>
        </div>
    </div>
</template>
