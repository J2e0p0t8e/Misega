<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Vérification de l'adresse e-mail — MISEGA" />

        <!-- Icon / Header -->
        <div class="mb-6 text-center sm:text-left">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-primary/10 text-primary mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="font-gang text-2xl text-gray-900 tracking-wide mb-1.5">
                Vérifiez votre <span class="text-primary">e-mail</span>
            </h1>
            <p class="text-sm text-gray-600 leading-relaxed">
                Merci pour votre inscription ! Un e-mail de confirmation vous a été envoyé. Veuillez cliquer sur le lien qu'il contient pour activer votre compte et accéder à votre panier et vos commandes.
            </p>
        </div>

        <!-- Success notification -->
        <div
            v-if="verificationLinkSent"
            class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm font-medium text-emerald-800 flex items-start gap-3"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span>Un nouvel e-mail de confirmation vient de vous être envoyé. Vérifiez également vos spams si nécessaire.</span>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <PrimaryButton
                class="w-full justify-center py-3.5"
                :class="{ 'opacity-60': form.processing }"
                :disabled="form.processing"
            >
                <span v-if="form.processing" class="mr-2 h-3.5 w-3.5 animate-spin rounded-full border-2 border-white border-t-transparent inline-block"></span>
                Renvoyer l'e-mail de confirmation
            </PrimaryButton>

            <div class="flex items-center justify-center pt-2">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-xs font-medium text-gray-400 hover:text-gray-700 transition-colors underline cursor-pointer"
                >
                    Se déconnecter
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
