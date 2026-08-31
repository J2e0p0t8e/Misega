<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Connexion — MISEGA" />

        <!-- Page heading -->
        <div class="mb-8">
            <h1 class="font-gang text-2xl text-gray-900 tracking-wide mb-1">
                Bon retour <span class="text-primary">!</span>
            </h1>
            <p class="text-sm text-gray-500">Connectez-vous à votre compte MISEGA.</p>
        </div>

        <!-- Success status (e.g. after password reset) -->
        <div v-if="status" class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">

            <!-- Email -->
            <div>
                <InputLabel for="email" value="Adresse e-mail" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1.5"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="vous@exemple.fr"
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <InputLabel for="password" value="Mot de passe" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs text-gray-400 hover:text-primary transition-colors"
                    >
                        Mot de passe oublié ?
                    </Link>
                </div>
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <!-- Remember me -->
            <div class="flex items-center gap-2.5">
                <Checkbox id="remember" name="remember" v-model:checked="form.remember" />
                <label for="remember" class="text-sm text-gray-600 cursor-pointer select-none">
                    Se souvenir de moi
                </label>
            </div>

            <!-- Submit -->
            <PrimaryButton
                class="w-full mt-2"
                :class="{ 'opacity-60': form.processing }"
                :disabled="form.processing"
            >
                <span v-if="form.processing" class="mr-2 h-3.5 w-3.5 animate-spin rounded-full border-2 border-white border-t-transparent inline-block"></span>
                Se connecter
            </PrimaryButton>
        </form>

        <!-- Register link -->
        <p class="mt-8 text-center text-sm text-gray-500">
            Pas encore de compte ?
            <Link :href="route('register')" class="font-semibold text-primary hover:text-primary-hover transition-colors">
                S'inscrire
            </Link>
        </p>
    </GuestLayout>
</template>
