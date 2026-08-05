<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useCartStore } from '@/stores/useCartStore';
import { ShoppingCart, Eye, BookOpen } from 'lucide-vue-next';

const props = defineProps({
    manga: {
        type: Object,
        required: true,
    }
});

const cartStore = useCartStore();

const cardRef = ref(null);
const tiltStyle = ref({});
const isHovered = ref(false);

const handleMouseMove = (e) => {
    if (!cardRef.value) return;
    const rect = cardRef.value.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    const centerX = rect.width / 2;
    const centerY = rect.height / 2;
    const rotateX = ((y - centerY) / centerY) * -12; // tilt angle X
    const rotateY = ((x - centerX) / centerX) * 12;  // tilt angle Y

    tiltStyle.value = {
        transform: `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) scale3d(1.03, 1.03, 1.03)`,
        transition: 'transform 0.1s ease-out',
    };
};

const handleMouseEnter = () => {
    isHovered.value = true;
};

const handleMouseLeave = () => {
    isHovered.value = false;
    tiltStyle.value = {
        transform: 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)',
        transition: 'transform 0.5s ease-out',
    };
};

const firstVolume = computed(() => {
    return props.manga.volumes && props.manga.volumes.length > 0 ? props.manga.volumes[0] : null;
});

const handleQuickAdd = (e) => {
    e.preventDefault();
    e.stopPropagation();
    if (firstVolume.value) {
        cartStore.addItem(props.manga, firstVolume.value, 1);
    }
};
</script>

<template>
    <div 
        ref="cardRef"
        @mousemove="handleMouseMove"
        @mouseenter="handleMouseEnter"
        @mouseleave="handleMouseLeave"
        :style="tiltStyle"
        class="group bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-shadow duration-300 flex flex-col h-full relative cursor-pointer select-none"
    >
        <!-- 3D Glare effect overlay -->
        <div 
            v-if="isHovered"
            class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/20 to-transparent pointer-events-none z-20 rounded-2xl"
        ></div>

        <!-- Cover Image Container -->
        <div class="relative aspect-[3/4] overflow-hidden bg-gray-100 cursor-pointer">
            <Link :href="`/manga/${manga.id}`">
                <img 
                    :src="manga.cover_image" 
                    :alt="manga.title" 
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
            </Link>

            <!-- Genre Badge -->
            <div class="absolute top-3 left-3 bg-gray-900/85 backdrop-blur-md text-white text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded-md shadow">
                {{ manga.genre }}
            </div>

            <!-- Status Badge -->
            <div 
                class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded shadow"
                :class="manga.status === 'Terminé' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300'"
            >
                {{ manga.status }}
            </div>

            <!-- Hover Quick Action Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center p-4 gap-2 z-10">
                <Link 
                    :href="`/manga/${manga.id}`"
                    class="flex-1 bg-white/20 hover:bg-white/30 backdrop-blur-md text-white text-xs font-bold py-2 px-3 rounded-xl border border-white/30 flex items-center justify-center gap-1.5 transition"
                >
                    <Eye class="w-3.5 h-3.5" />
                    Fiche produit
                </Link>
                <button 
                    v-if="firstVolume && firstVolume.stock > 0"
                    @click="handleQuickAdd"
                    class="bg-primary hover:bg-primary-hover text-white text-xs font-bold p-2.5 rounded-xl shadow-lg shadow-primary/40 transition hover:scale-110 active:scale-95"
                    title="Ajouter le Tome 1"
                >
                    <ShoppingCart class="w-4 h-4" />
                </button>
            </div>
        </div>

        <!-- Details -->
        <div class="p-4 flex flex-col flex-1 justify-between bg-white">
            <div>
                <span class="text-[11px] font-semibold text-gray-400 block mb-1">Par {{ manga.author }}</span>
                <Link :href="`/manga/${manga.id}`">
                    <h3 class="font-gang text-gray-900 text-lg group-hover:text-primary transition-colors line-clamp-1">
                        {{ manga.title }}
                    </h3>
                </Link>
                <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed">
                    {{ manga.synopsis }}
                </p>
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-1 text-xs text-gray-500 font-medium">
                    <BookOpen class="w-3.5 h-3.5 text-primary" />
                    <span>{{ manga.volumes ? manga.volumes.length : 0 }} Tomes</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-gray-400 block">À partir de</span>
                    <span class="font-bold text-gray-900 text-sm">
                        {{ firstVolume ? firstVolume.price.toFixed(2) : '6.90' }} €
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
