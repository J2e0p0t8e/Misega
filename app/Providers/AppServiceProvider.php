<?php

namespace App\Providers;

use App\Listeners\SendWelcomeEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Send Welcome Email upon successful email verification
        Event::listen(
            Verified::class,
            SendWelcomeEmail::class,
        );

        // Customize email verification notification sender and copy
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->from('bienvenue@misega.ecksonahouansou.me', 'MISEGA')
                ->subject('Vérifiez votre adresse e-mail — MISEGA')
                ->greeting('Bonjour ' . $notifiable->name . ' !')
                ->line('Merci pour votre inscription sur MISEGA. Veuillez cliquer sur le bouton ci-dessous pour confirmer votre adresse e-mail et activer votre compte.')
                ->action('Confirmer mon adresse e-mail', $url)
                ->line('Ce lien de confirmation expirera dans 60 minutes.')
                ->line('Si vous n\'avez pas créé de compte sur MISEGA, aucune action supplémentaire n\'est requise.');
        });
    }
}
