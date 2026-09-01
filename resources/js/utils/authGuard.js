import { router, usePage } from '@inertiajs/vue3';

/**
 * Checks if the current user is authenticated and email-verified.
 * - If not authenticated: redirects to route('login') (/login)
 * - If authenticated but not verified: redirects to route('verification.notice') (/verify-email)
 * - If authenticated and verified: returns true
 *
 * @returns {boolean}
 */
export function requireAuthAndVerified() {
    const page = usePage();
    const user = page.props.auth?.user;

    if (!user) {
        const loginUrl = typeof route === 'function' ? route('login') : '/login';
        router.visit(loginUrl);
        return false;
    }

    if (!user.email_verified_at) {
        const verifyUrl = typeof route === 'function' ? route('verification.notice') : '/verify-email';
        router.visit(verifyUrl);
        return false;
    }

    return true;
}
