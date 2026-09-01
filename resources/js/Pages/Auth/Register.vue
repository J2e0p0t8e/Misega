<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    birth_date: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Créer un compte — MISEGA" />

        <!-- Page heading -->
        <div class="mb-8">
            <h1 class="font-gang text-2xl text-gray-900 tracking-wide mb-1">
                Rejoignez <span class="text-primary">MISEGA</span>
            </h1>
            <p class="text-sm text-gray-500">Créez votre compte pour accéder à tous vos mangas.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">

            <!-- Name -->
            <div>
                <InputLabel for="name" value="Prénom / Pseudo" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1.5"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Otaku San"
                />
                <InputError class="mt-1.5" :message="form.errors.name" />
            </div>

            <!-- Email -->
            <div>
                <InputLabel for="email" value="Adresse e-mail" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1.5"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="vous@exemple.fr"
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <!-- Phone (Optional) -->
            <div>
                <div class="flex items-center justify-between">
                    <InputLabel for="phone" value="Numéro de téléphone" />
                    <span class="text-[11px] text-gray-400 font-medium">Optionnel</span>
                </div>
                <TextInput
                    id="phone"
                    type="tel"
                    class="mt-1.5"
                    v-model="form.phone"
                    autocomplete="tel"
                    placeholder="06 12 34 56 78"
                />
                <InputError class="mt-1.5" :message="form.errors.phone" />
            </div>

            <!-- Date of Birth (Required) -->
            <div>
                <InputLabel for="birth_date" value="Date de naissance" />
                <TextInput
                    id="birth_date"
                    type="date"
                    class="mt-1.5"
                    v-model="form.birth_date"
                    required
                    :max="new Date().toISOString().split('T')[0]"
                />
                <InputError class="mt-1.5" :message="form.errors.birth_date" />
            </div>

            <!-- Password -->
            <div>
                <InputLabel for="password" value="Mot de passe" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1.5"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="Minimum 8 caractères"
                />
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <!-- Password confirmation -->
            <div>
                <InputLabel for="password_confirmation" value="Confirmer le mot de passe" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1.5"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
            </div>

            <!-- Submit -->
            <PrimaryButton
                class="w-full mt-2"
                :class="{ 'opacity-60': form.processing }"
                :disabled="form.processing"
            >
                <span v-if="form.processing" class="mr-2 h-3.5 w-3.5 animate-spin rounded-full border-2 border-white border-t-transparent inline-block"></span>
                Créer mon compte
            </PrimaryButton>
        </form>

        <!-- Login link -->
        <p class="mt-8 text-center text-sm text-gray-500">
            Déjà un compte ?
            <Link :href="route('login')" class="font-semibold text-primary hover:text-primary-hover transition-colors">
                Se connecter
            </Link>
        </p>
    </GuestLayout>
</template>
