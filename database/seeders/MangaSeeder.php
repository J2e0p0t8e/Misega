<?php

namespace Database\Seeders;

use App\Models\Manga;
use App\Models\Volume;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MangaSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::firstOrCreate(
            ['email' => 'admin@misega.fr'],
            [
                'name' => 'Admin Misega',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Client User
        User::firstOrCreate(
            ['email' => 'client@misega.fr'],
            [
                'name' => 'Otaku Fan',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );

        $mangas = [
            [
                'title' => 'Jujutsu Kaisen',
                'author' => 'Gege Akutami',
                'publisher' => 'Ki-oon',
                'genre' => 'Shonen',
                'synopsis' => "Souffrance, regrets, humilation... Les sentiments négatifs que ressentent les humains deviennent des Fléaux qui se terrent dans notre quotidien. Yuji Itadori, un lycéen doté d'une force physique hors du commun, va consommer une relique maudite de classe S pour sauver ses amis.",
                'status' => 'En cours',
                'cover_image' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=600&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'volumes_count' => 5,
                'price' => 6.95,
            ],
            [
                'title' => 'Chainsaw Man',
                'author' => 'Tatsuki Fujimoto',
                'publisher' => 'Kazé / Crunchyroll',
                'genre' => 'Action / Dark Fantasy',
                'synopsis' => "Pour rembourser ses dettes, Denji vit dans la misère la plus totale en chasseur de démons avec Pochita, le démon tronçonneuse. Après une trahison, Denji renait sous la forme d'un hybride démon-humain redoutable.",
                'status' => 'En cours',
                'cover_image' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=600&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'volumes_count' => 6,
                'price' => 7.29,
            ],
            [
                'title' => 'One Piece',
                'author' => 'Eiichiro Oda',
                'publisher' => 'Glénat',
                'genre' => 'Aventure',
                'synopsis' => "Monkey D. Luffy rêve de devenir le Roi des Pirates en trouvant le légendaire trésor laissé par Gol D. Roger : le One Piece. Entouré de son équipage au Chapeau de Paille, il sillonne les mers du monde entier.",
                'status' => 'En cours',
                'cover_image' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?w=600&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'volumes_count' => 8,
                'price' => 6.90,
            ],
            [
                'title' => 'Berserk',
                'author' => 'Kentarō Miura',
                'publisher' => 'Glénat',
                'genre' => 'Seinen / Dark Fantasy',
                'synopsis' => "Guts, le Guerrier Noir, promène son imposante silhouette et son épée gigantesque dans un monde médiéval fantastique ravagé par les guerres et les démons. Une quête de vengeance épique et sombre.",
                'status' => 'En cours',
                'cover_image' => 'https://images.unsplash.com/photo-1563089145-599997674d42?w=600&auto=format&fit=crop&q=80',
                'is_featured' => false,
                'volumes_count' => 4,
                'price' => 8.90,
            ],
            [
                'title' => 'Spy x Family',
                'author' => 'Tatsuya Endō',
                'publisher' => 'Kurokawa',
                'genre' => 'Comédie / Action',
                'synopsis' => "Twilight, un espion d'élite, doit fonder une famille factice pour une mission ultra-secrète. Ce qu'il ne sait pas, c'est que sa femme est une tueuse à gages et sa fille adoptive une télépathe !",
                'status' => 'En cours',
                'cover_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&auto=format&fit=crop&q=80',
                'is_featured' => false,
                'volumes_count' => 4,
                'price' => 6.95,
            ],
            [
                'title' => 'Demon Slayer (Kimetsu no Yaiba)',
                'author' => 'Koyoharu Gotōge',
                'publisher' => 'Panini Manga',
                'genre' => 'Shonen',
                'synopsis' => "Tanjiro, un jeune vendeur de charbon, voit sa famille massacrée par un démon. Sa sœur Nezuko est la seule survivante, mais elle est transformée en démon. Tanjiro s'engage chez les Pourfendeurs de Démons pour la sauver.",
                'status' => 'Terminé',
                'cover_image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'volumes_count' => 5,
                'price' => 7.29,
            ],
        ];

        foreach ($mangas as $mData) {
            $volumesCount = $mData['volumes_count'];
            $basePrice = $mData['price'];
            unset($mData['volumes_count'], $mData['price']);

            $manga = Manga::create($mData);

            for ($i = 1; $i <= $volumesCount; $i++) {
                Volume::create([
                    'manga_id' => $manga->id,
                    'volume_number' => $i,
                    'price' => $basePrice,
                    'stock' => rand(5, 30),
                    'cover_image_url' => $manga->cover_image,
                    'isbn' => '978-2-' . rand(10000, 99999) . '-' . rand(100, 999) . '-' . rand(0, 9),
                ]);
            }
        }
    }
}
