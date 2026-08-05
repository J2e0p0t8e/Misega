<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MisegaLayout from '@/Layouts/MisegaLayout.vue';
import { useCartStore } from '@/stores/useCartStore';
import { ShoppingBag, Trash2, ArrowRight, ArrowLeft, Truck, ShieldCheck, Sparkles } from 'lucide-vue-next';

const cartStore = useCartStore();

const shippingCost = computed(() => {
    return cartStore.totalPrice >= 35 ? 0 : 4.90;
});

const grandTotal = computed(() => {
    return cartStore.totalPrice + shippingCost.value;
});

const progressToFreeShipping = computed(() => {
    if (cartStore.totalPrice >= 35) return 100;
    return Math.min(100, Math.round((cartStore.totalPrice / 35) * 100));
});

const amountRemainingForFreeShipping = computed(() => {
    return Math.max(0, 35 - cartStore.totalPrice);
});
</script>

<template>
    <Head title="Votre Panier - MISEGA" />

    <MisegaLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8 border-b border-gray-200 pb-4">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-primary/10 rounded-2xl border border-primary/20 text-primary">
                        <ShoppingBag class="w-6 h-6" />
                    </div>
                    <div>
                        <h1 class="font-gang text-3xl font-extrabold text-gray-900">Mon Panier</h1>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ cartStore.totalItems }} article{{ cartStore.totalItems > 1 ? 's' : '' }} sélectionné{{ cartStore.totalItems > 1 ? 's' : '' }}
                        </p>
                    </div>
                </div>

                <Link href="/catalog" class="hidden sm:flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-gray-900 transition">
                    <ArrowLeft class="w-4 h-4" />
                    Continuer mes achats
                </Link>
            </div>

            <!-- Cart Items & Summary Grid -->
            <div v-if="cartStore.items.length > 0" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left: Items Table -->
                <div class="lg:col-span-8 space-y-4">
                    <!-- Free shipping banner -->
                    <div class="bg-white border border-gray-200 p-4 rounded-2xl space-y-2 shadow-sm">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span v-if="amountRemainingForFreeShipping > 0" class="text-gray-700 flex items-center gap-1.5">
                                <Truck class="w-4 h-4 text-primary" />
                                Plus que <strong class="text-primary">{{ amountRemainingForFreeShipping.toFixed(2) }} €</strong> pour la livraison OFFERTE !
                            </span>
                            <span v-else class="text-emerald-600 flex items-center gap-1.5 font-bold">
                                <Sparkles class="w-4 h-4" />
                                Félicitations ! La livraison est GRATUITE !
                            </span>
                            <span class="text-gray-500 font-mono">{{ progressToFreeShipping }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                            <div 
                                class="bg-gradient-to-r from-primary to-primary-light h-full transition-all duration-500 rounded-full"
                                :style="{ width: `${progressToFreeShipping}%` }"
                            ></div>
                        </div>
                    </div>

                    <!-- Item Cards -->
                    <div 
                        v-for="item in cartStore.items" 
                        :key="item.volume_id"
                        class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 flex items-center gap-4 sm:gap-6 shadow-sm transition hover:shadow-md"
                    >
                        <!-- Cover -->
                        <Link :href="`/manga/${item.manga_id}`" class="w-16 sm:w-20 aspect-[3/4] rounded-xl overflow-hidden bg-gray-100 shrink-0 border border-gray-200">
                            <img :src="item.cover_image" :alt="item.manga_title" class="w-full h-full object-cover" />
                        </Link>

                        <!-- Manga details -->
                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] font-semibold text-gray-400 block uppercase tracking-wider">{{ item.author }}</span>
                            <Link :href="`/manga/${item.manga_id}`" class="font-gang text-lg text-gray-900 hover:text-primary transition line-clamp-1">
                                {{ item.manga_title }}
                            </Link>
                            <span class="text-xs font-bold text-gray-600 block mt-0.5">Tome {{ item.volume_number }}</span>
                            <span class="text-xs text-primary font-bold mt-1 block sm:hidden">{{ item.price.toFixed(2) }} €</span>
                        </div>

                        <!-- Qty Selector -->
                        <div class="flex items-center border border-gray-200 rounded-xl bg-gray-50 overflow-hidden shrink-0">
                            <button 
                                @click="cartStore.updateQuantity(item.volume_id, item.quantity - 1)"
                                class="px-3 py-1.5 text-gray-600 hover:text-gray-900 hover:bg-gray-200 text-xs font-bold"
                            >
                                -
                            </button>
                            <span class="px-2.5 py-1 text-xs font-bold text-gray-900 min-w-[28px] text-center">
                                {{ item.quantity }}
                            </span>
                            <button 
                                @click="cartStore.updateQuantity(item.volume_id, item.quantity + 1)"
                                class="px-3 py-1.5 text-gray-600 hover:text-gray-900 hover:bg-gray-200 text-xs font-bold"
                            >
                                +
                            </button>
                        </div>

                        <!-- Total price for item -->
                        <div class="hidden sm:block text-right shrink-0 min-w-[80px]">
                            <span class="text-xs text-gray-400 block">Sous-total</span>
                            <span class="font-bold text-gray-900 text-base">
                                {{ (item.price * item.quantity).toFixed(2) }} €
                            </span>
                        </div>

                        <!-- Delete Button -->
                        <button 
                            @click="cartStore.removeItem(item.volume_id)"
                            class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition shrink-0"
                            title="Supprimer du panier"
                        >
                            <Trash2 class="w-4 h-4" />
                        </button>
                    </div>

                    <!-- Clear Cart Action -->
                    <div class="flex justify-between items-center pt-2">
                        <button 
                            @click="cartStore.clearCart()" 
                            class="text-xs text-gray-500 hover:text-red-500 flex items-center gap-1 transition"
                        >
                            <Trash2 class="w-3.5 h-3.5" />
                            Vider le panier
                        </button>
                    </div>
                </div>

                <!-- Right: Summary Card -->
                <div class="lg:col-span-4 bg-white border border-gray-200 rounded-2xl p-6 space-y-6 sticky top-28 shadow-lg">
                    <h3 class="font-gang text-xl text-gray-900 border-b border-gray-100 pb-4">Résumé de la commande</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Sous-total articles</span>
                            <span class="font-bold text-gray-900">{{ cartStore.totalPrice.toFixed(2) }} €</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span class="flex items-center gap-1">
                                Frais de livraison
                                <Truck class="w-3.5 h-3.5 text-gray-400" />
                            </span>
                            <span :class="shippingCost === 0 ? 'text-emerald-600 font-bold' : 'text-gray-900 font-bold'">
                                {{ shippingCost === 0 ? 'GRATUIT' : `${shippingCost.toFixed(2)} €` }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-between items-end">
                        <div>
                            <span class="text-xs text-gray-500 block font-semibold">Total TTC</span>
                            <span class="font-gang text-2xl font-extrabold text-primary">
                                {{ grandTotal.toFixed(2) }} €
                            </span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <Link 
                        href="/checkout"
                        class="w-full bg-primary hover:bg-primary-hover text-white text-sm font-bold py-3.5 px-4 rounded-xl shadow-md shadow-primary/20 flex items-center justify-center gap-2 transition hover:scale-[1.02]"
                    >
                        Valider mon panier
                        <ArrowRight class="w-4 h-4" />
                    </Link>

                    <div class="pt-4 border-t border-gray-100 space-y-2 text-center text-xs text-gray-500">
                        <div class="flex items-center justify-center gap-1.5 text-emerald-600 font-semibold">
                            <ShieldCheck class="w-4 h-4" />
                            Paiement 100% Sécurisé
                        </div>
                        <p class="text-[11px] text-gray-400">Paiement simulé sécurisé. Expédition sous 24 heures.</p>
                    </div>
                </div>
            </div>

            <!-- Empty Cart State -->
            <div v-else class="text-center py-20 bg-white rounded-2xl border border-gray-200 p-8 space-y-5 max-w-xl mx-auto shadow-sm">
                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto text-primary">
                    <ShoppingBag class="w-10 h-10" />
                </div>
                <h2 class="font-gang text-2xl text-gray-900">Votre panier est vide</h2>
                <p class="text-gray-500 text-sm">
                    Vous n'avez pas encore ajouté de tomes à votre panier. Parcourez notre catalogue pour découvrir nos meilleures séries !
                </p>
                <Link 
                    href="/catalog" 
                    class="inline-flex items-center gap-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold px-6 py-3 rounded-xl transition shadow-md shadow-primary/20"
                >
                    Découvrir le catalogue
                    <ArrowRight class="w-4 h-4" />
                </Link>
            </div>
        </div>
    </MisegaLayout>
</template>
