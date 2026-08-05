<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MisegaLayout from '@/Layouts/MisegaLayout.vue';
import { CheckCircle2, Package, Home, Sparkles } from 'lucide-vue-next';

const props = defineProps({
    order: { type: Object, required: true }
});
</script>

<template>
    <Head title="Commande Confirmée ! - MISEGA" />

    <MisegaLayout>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="bg-white border border-gray-200 rounded-3xl p-8 sm:p-12 text-center space-y-8 shadow-xl relative overflow-hidden">
                <!-- Background ambient glow -->
                <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-80 h-80 bg-emerald-500/10 blur-3xl rounded-full pointer-events-none"></div>

                <!-- Icon -->
                <div class="w-20 h-20 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/10 animate-bounce">
                    <CheckCircle2 class="w-10 h-10" />
                </div>

                <!-- Text -->
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold uppercase tracking-wider">
                        <Sparkles class="w-3.5 h-3.5" />
                        Paiement Validé avec Succès
                    </div>
                    <h1 class="font-gang text-3xl sm:text-4xl text-gray-900">Merci pour votre commande !</h1>
                    <p class="text-gray-600 text-sm max-w-md mx-auto">
                        Votre commande <strong class="text-gray-900 font-mono">#MSG-{{ order.id }}</strong> a bien été prise en compte et est en cours de préparation par nos équipes.
                    </p>
                </div>

                <!-- Summary Box -->
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 text-left space-y-4 max-w-lg mx-auto">
                    <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                        <span class="text-xs text-gray-500">Statut</span>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 border border-emerald-300 px-2.5 py-0.5 rounded-full">
                            {{ order.status === 'completed' ? 'Payée / En Préparation' : order.status }}
                        </span>
                    </div>

                    <div class="space-y-2 text-xs text-gray-700">
                        <div class="flex justify-between">
                            <span>Destinataire :</span>
                            <span class="font-bold text-gray-900">{{ order.shipping_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Email :</span>
                            <span class="font-bold text-gray-900">{{ order.shipping_email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Adresse :</span>
                            <span class="font-bold text-gray-900 text-right max-w-xs truncate">{{ order.shipping_address }}</span>
                        </div>
                    </div>

                    <!-- Items list -->
                    <div class="border-t border-gray-200 pt-3 space-y-2">
                        <span class="text-xs font-semibold text-gray-500 block mb-2">Articles commandés :</span>
                        <div 
                            v-for="item in order.items" 
                            :key="item.id"
                            class="flex items-center justify-between text-xs text-gray-800"
                        >
                            <span>{{ item.volume?.manga?.title }} - Tome {{ item.volume?.volume_number }} (x{{ item.quantity }})</span>
                            <span class="font-bold text-gray-900">{{ (item.unit_price * item.quantity).toFixed(2) }} €</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-3 flex justify-between items-center text-sm font-bold text-gray-900">
                        <span>Total payé</span>
                        <span class="text-primary text-base font-extrabold">{{ order.total_amount.toFixed(2) }} €</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <Link 
                        href="/orders" 
                        class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold px-6 py-3 rounded-xl border border-gray-200 transition flex items-center justify-center gap-2"
                    >
                        <Package class="w-4 h-4 text-primary" />
                        Voir l'historique de mes commandes
                    </Link>
                    <Link 
                        href="/catalog" 
                        class="w-full sm:w-auto bg-primary hover:bg-primary-hover text-white text-xs font-bold px-6 py-3 rounded-xl transition shadow-md shadow-primary/20 flex items-center justify-center gap-2"
                    >
                        <Home class="w-4 h-4" />
                        Retour au catalogue
                    </Link>
                </div>
            </div>
        </div>
    </MisegaLayout>
</template>
