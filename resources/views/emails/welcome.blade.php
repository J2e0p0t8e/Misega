<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue chez MISEGA</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f5f7;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .wrapper {
            width: 100%;
            background-color: #f4f5f7;
            padding: 40px 15px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #0d0d11;
            padding: 35px 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 28px;
            letter-spacing: 2px;
            margin: 0;
            font-weight: 800;
        }
        .header span {
            color: #e50914;
        }
        .content {
            padding: 35px 30px;
            line-height: 1.6;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .badge-verified {
            display: inline-block;
            background-color: #ecfdf5;
            color: #047857;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 30px 0 20px;
        }
        .btn {
            background-color: #e50914;
            color: #ffffff !important;
            display: inline-block;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(229, 9, 20, 0.3);
        }
        .footer {
            background-color: #fafafa;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #f3f4f6;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1>MISEGA<span>.</span></h1>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="badge-verified">✓ E-mail vérifié avec succès</div>
                <h2 class="greeting">Bienvenue dans la communauté, {{ $user->name }} ! 🎌</h2>
                <p>
                    Votre adresse e-mail a été confirmée avec succès. Votre compte est désormais pleinement actif sur <strong>MISEGA</strong>.
                </p>
                <p>
                    Vous avez désormais accès à l'ensemble de notre catalogue, à votre panier d'achat ainsi qu'à l'historique complet de vos commandes.
                </p>

                <div class="btn-wrapper">
                    <a href="{{ url('/catalogue') }}" class="btn">Découvrir le Catalogue</a>
                </div>

                <p style="font-size: 14px; color: #6b7280; margin-top: 25px;">
                    Une question ou besoin d'aide ? Notre équipe reste à votre entière disposition à l'adresse <a href="mailto:contact@misega.ecksonahouansou.me" style="color: #e50914;">contact@misega.ecksonahouansou.me</a>.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0;">© {{ date('Y') }} Éditions MISEGA — Tous droits réservés.</p>
            </div>
        </div>
    </div>
</body>
</html>
