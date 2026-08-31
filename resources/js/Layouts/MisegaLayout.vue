<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { useCartStore } from '@/stores/useCartStore';
import MisegaLoader from '@/Components/MisegaLoader.vue';
import { ShoppingBag, Search, Menu, X, LogOut, Package } from '@lucide/vue';

const cartStore = useCartStore();
const page = usePage();
const user = computed(() => page.props.auth?.user);

const mobileMenuOpen = ref(false);
const searchQuery = ref('');

const handleSearch = () => {
    if (searchQuery.value.trim()) {
        router.get('/catalogue', { search: searchQuery.value.trim() });
    }
};
</script>

<template>
    <div class="min-h-screen bg-white text-gray-900 flex flex-col font-sans selection:bg-primary selection:text-white">
        <!-- Fullscreen 3D Manga Loader -->
        <MisegaLoader />

        <!-- Header / Navbar -->
        <header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm transition-all duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">

                <!-- Logo -->
                <Link href="/" class="flex items-center group shrink-0">
                    <!-- Logo complet sur desktop (sm+) -->
                    <div class="hidden sm:flex items-center gap-2.5">
                        <img
                            src="/img/favicon.png"
                            alt="Misega"
                            class="h-9 w-9 object-contain transition-transform duration-300 group-hover:scale-110"
                        />
                        <span class="font-gang text-2xl lg:text-3xl font-extrabold tracking-wider text-gray-900">MI<span class="text-primary">SEGA</span></span>
                    </div>
                    <!-- Icône seule sur mobile -->
                    <div class="flex sm:hidden items-center gap-2">
                        <img
                            src="/img/favicon.png"
                            alt="Misega"
                            class="h-9 w-9 object-contain transition-transform duration-300 group-hover:scale-110"
                        />
                        <span class="font-gang text-xl font-extrabold tracking-wider text-gray-900">MI<span class="text-primary">SEGA</span></span>
                    </div>
                </Link>

                <!-- Navigation Links (Desktop) -->
                <nav class="hidden md:flex items-center space-x-6 text-sm font-medium ml-2">
                    <Link
                        href="/catalogue"
                        class="text-gray-500 hover:text-gray-900 transition-colors py-1 border-b-2"
                        :class="page.url.startsWith('/catalogue') || page.url.startsWith('/catalog') ? 'border-primary text-gray-900 font-bold' : 'border-transparent'"
                    >
                        Catalogue
                    </Link>
                </nav>

                <!-- Search Bar (Desktop) -->
                <div class="hidden lg:flex items-center relative flex-1 max-w-md mx-6">
                    <input
                        v-model="searchQuery"
                        @keyup.enter="handleSearch"
                        type="text"
                        placeholder="Rechercher un manga, auteur..."
                        class="w-full bg-gray-100 text-sm text-gray-700 placeholder-gray-400 rounded-full py-2 pl-10 pr-4 border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition shadow-sm"
                    />
                    <Search @click="handleSearch" class="w-4 h-4 text-gray-400 absolute left-3.5 cursor-pointer hover:text-primary transition" />
                </div>

                <!-- User & Cart Actions -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Cart Button -->
                    <Link
                        href="/cart"
                        class="relative p-2.5 rounded-full bg-gray-100 hover:bg-gray-200 border border-gray-200 text-gray-600 hover:text-gray-900 transition flex items-center justify-center group"
                        title="Panier"
                    >
                        <ShoppingBag class="w-5 h-5 group-hover:scale-110 transition-transform" />
                        <span
                            v-if="cartStore.totalItems > 0"
                            class="absolute -top-1.5 -right-1.5 bg-primary text-white text-[10px] font-extrabold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white animate-bounce"
                        >
                            {{ cartStore.totalItems }}
                        </span>
                    </Link>

                    <!-- User Account / Login -->
                    <div v-if="user" class="relative flex items-center gap-2">
                        <Link
                            href="/orders"
                            class="hidden sm:flex items-center gap-2 px-3 py-2 text-xs font-semibold rounded-lg bg-gray-100 hover:bg-gray-200 border border-gray-200 text-gray-700 transition"
                        >
                            <Package class="w-4 h-4 text-primary" />
                            Mes Commandes
                        </Link>
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="p-2.5 rounded-full bg-gray-100 hover:bg-red-50 text-gray-400 hover:text-red-500 border border-gray-200 transition"
                            title="Déconnexion"
                        >
                            <LogOut class="w-4 h-4" />
                        </Link>
                    </div>
                    <div v-else class="flex items-center gap-2">
                        <Link
                            href="/login"
                            class="text-xs font-bold text-gray-600 hover:text-gray-900 px-3 py-2 rounded-lg transition"
                        >
                            Connexion
                        </Link>
                        <Link
                            href="/register"
                            class="text-xs font-bold text-white bg-primary hover:bg-primary-hover px-4 py-2 rounded-lg shadow-md shadow-primary/20 transition hover:scale-105"
                        >
                            Inscription
                        </Link>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2 text-gray-500 hover:text-gray-900"
                    >
                        <Menu v-if="!mobileMenuOpen" class="w-6 h-6" />
                        <X v-else class="w-6 h-6" />
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Drawer -->
            <div v-if="mobileMenuOpen" class="md:hidden bg-white border-b border-gray-200 px-4 pt-3 pb-6 space-y-3">
                <div class="relative mb-3">
                    <input
                        v-model="searchQuery"
                        @keyup.enter="handleSearch"
                        type="text"
                        placeholder="Rechercher..."
                        class="w-full bg-gray-100 text-sm text-gray-700 placeholder-gray-400 rounded-lg py-2 pl-9 pr-3 border border-gray-200 focus:outline-none focus:border-primary"
                    />
                    <Search class="w-4 h-4 text-gray-400 absolute left-3 top-3" />
                </div>
                <Link href="/catalogue" class="block text-sm font-semibold text-gray-700 py-1.5">Tous les Mangas</Link>
                <Link href="/catalogue?genre=Shonen" class="block text-sm font-semibold text-gray-500 py-1.5">Shōnen</Link>
                <Link href="/catalogue?genre=Seinen" class="block text-sm font-semibold text-gray-500 py-1.5">Seinen</Link>
                <div v-if="user" class="pt-2 border-t border-gray-200">
                    <Link href="/orders" class="block text-sm font-semibold text-primary py-1.5">Mes Commandes</Link>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 border-t border-gray-700 text-gray-400 text-sm mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Brand info -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <img src="/img/favicon.png" alt="Misega" class="h-8 w-8 object-contain" />
                            <span class="font-gang text-2xl font-extrabold text-white">MI<span class="text-primary">SEGA</span></span>
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Votre destination numéro 1 pour la commande de mangas en ligne. Qualité, livraison rapide et passion du manga au quotidien.
                        </p>
                    </div>

                    <!-- Navigation -->
                    <div>
                        <h4 class="font-gang text-white text-base mb-3 tracking-wide">Navigation</h4>
                        <ul class="space-y-2 text-xs">
                            <li><Link href="/catalogue" class="hover:text-primary transition">Catalogue Complet</Link></li>
                            <li><Link href="/catalogue?genre=Shonen" class="hover:text-primary transition">Shōnen Populaires</Link></li>
                            <li><Link href="/catalogue?genre=Seinen" class="hover:text-primary transition">Nouveautés Seinen</Link></li>
                        </ul>
                    </div>

                    <!-- Services -->
                    <div>
                        <h4 class="font-gang text-white text-base mb-3 tracking-wide">Client</h4>
                        <ul class="space-y-2 text-xs">
                            <li><Link href="/cart" class="hover:text-primary transition">Mon Panier</Link></li>
                            <li><Link href="/orders" class="hover:text-primary transition">Suivi de Commandes</Link></li>
                            <li><a href="#" class="hover:text-primary transition">FAQ & Support</a></li>
                        </ul>
                    </div>

                    <!-- Payment -->
                    <div class="space-y-3">
                        <h4 class="font-gang text-white text-base mb-3 tracking-wide">Paiement Sécurisé</h4>
                        <p class="text-xs text-gray-500">Expédition sous 24h ouvrées. Emballage soigné spécial manga.</p>
                        <div class="flex items-center gap-2 pt-2">
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-gray-800 border border-gray-700 rounded text-gray-300">VISA</span>
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-gray-800 border border-gray-700 rounded text-gray-300">MASTERCARD</span>
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-gray-800 border border-gray-700 rounded text-gray-300">PAYPAL</span>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-6 border-t border-gray-800 text-center text-xs text-gray-600 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p>© {{ new Date().getFullYear() }} MISEGA - Tous droits réservés.</p>
                    <p class="font-gang text-gray-500 tracking-wider">L'Art du Manga</p>
                </div>
            </div>
        </footer>
    </div>
</template>
