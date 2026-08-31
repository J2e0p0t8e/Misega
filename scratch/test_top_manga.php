<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$mangaSeedList = [
    ['title' => 'Jujutsu Kaisen', 'author' => 'Gege Akutami', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/210341l.jpg'],
    ['title' => 'Chainsaw Man', 'author' => 'Tatsuki Fujimoto', 'genre' => 'Action / Dark Fantasy', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/216464l.jpg'],
    ['title' => 'One Piece', 'author' => 'Eiichiro Oda', 'genre' => 'Aventure', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/253146l.jpg'],
    ['title' => 'Berserk', 'author' => 'Kentarou Miura', 'genre' => 'Seinen / Dark Fantasy', 'cover' => 'https://cdn.myanimelist.net/images/manga/1/157897l.jpg'],
    ['title' => 'Spy x Family', 'author' => 'Tatsuya Endo', 'genre' => 'Comédie / Action', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/219741l.jpg'],
    ['title' => 'Demon Slayer', 'author' => 'Koyoharu Gotouge', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/179023l.jpg'],
    ['title' => 'Attack on Titan', 'author' => 'Hajime Isayama', 'genre' => 'Action / Dark Fantasy', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/37846l.jpg'],
    ['title' => 'My Hero Academia', 'author' => 'Kohei Horikoshi', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/1/157338l.jpg'],
    ['title' => 'Naruto', 'author' => 'Masashi Kishimoto', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/117681l.jpg'],
    ['title' => 'Bleach', 'author' => 'Tite Kubo', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/180031l.jpg'],
    ['title' => 'Dragon Ball', 'author' => 'Akira Toriyama', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/54453l.jpg'],
    ['title' => 'Hunter x Hunter', 'author' => 'Yoshihiro Togashi', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/253151l.jpg'],
    ['title' => 'Death Note', 'author' => 'Tsugumi Ohba', 'genre' => 'Seinen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/253147l.jpg'],
    ['title' => 'Tokyo Ghoul', 'author' => 'Sui Ishida', 'genre' => 'Action / Dark Fantasy', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/164947l.jpg'],
    ['title' => 'Fullmetal Alchemist', 'author' => 'Hiromu Arakawa', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/243675l.jpg'],
    ['title' => 'Vinland Saga', 'author' => 'Makoto Yukimura', 'genre' => 'Seinen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/188177l.jpg'],
    ['title' => 'Slam Dunk', 'author' => 'Takehiko Inoue', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/258749l.jpg'],
    ['title' => 'Monster', 'author' => 'Naoki Urasawa', 'genre' => 'Seinen', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/258224l.jpg'],
    ['title' => 'Vagabond', 'author' => 'Takehiko Inoue', 'genre' => 'Seinen', 'cover' => 'https://cdn.myanimelist.net/images/manga/1/259287l.jpg'],
    ['title' => 'Kingdom', 'author' => 'Yasuhisa Hara', 'genre' => 'Seinen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/171872l.jpg'],
    ['title' => 'JoJo no Kimyou na Bouken', 'author' => 'Hirohiko Araki', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/54583l.jpg'],
    ['title' => 'Haikyuu!!', 'author' => 'Haruichi Furudate', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/182513l.jpg'],
    ['title' => 'Solo Leveling', 'author' => 'Chugong', 'genre' => 'Action / Dark Fantasy', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/222240l.jpg'],
    ['title' => 'Blue Lock', 'author' => 'Muneyuki Kaneshiro', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/217342l.jpg'],
    ['title' => 'Gintama', 'author' => 'Hideaki Sorachi', 'genre' => 'Comédie / Action', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/166046l.jpg'],
    ['title' => 'One Punch Man', 'author' => 'ONE', 'genre' => 'Comédie / Action', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/258226l.jpg'],
    ['title' => 'Mob Psycho 100', 'author' => 'ONE', 'genre' => 'Comédie / Action', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/171804l.jpg'],
    ['title' => 'Tokyo Revengers', 'author' => 'Ken Wakui', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/206126l.jpg'],
    ['title' => 'Dr. Stone', 'author' => 'Riichiro Inagaki', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/194481l.jpg'],
    ['title' => 'Black Clover', 'author' => 'Yuki Tabata', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/166245l.jpg'],
    ['title' => 'Fairy Tail', 'author' => 'Hiro Mashima', 'genre' => 'Aventure', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/181671l.jpg'],
    ['title' => 'Fire Force', 'author' => 'Atsushi Ohkubo', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/178051l.jpg'],
    ['title' => 'Kaiju No. 8', 'author' => 'Naoya Matsumoto', 'genre' => 'Action / Dark Fantasy', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/237077l.jpg'],
    ['title' => 'Soul Eater', 'author' => 'Atsushi Ohkubo', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/258752l.jpg'],
    ['title' => 'Noragami', 'author' => 'Adachitoka', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/180718l.jpg'],
    ['title' => 'Blue Exorcist', 'author' => 'Kazue Kato', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/181488l.jpg'],
    ['title' => 'Seven Deadly Sins', 'author' => 'Nakaba Suzuki', 'genre' => 'Aventure', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/178050l.jpg'],
    ['title' => 'Made in Abyss', 'author' => 'Akihito Tsukushi', 'genre' => 'Aventure', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/182512l.jpg'],
    ['title' => 'The Promised Neverland', 'author' => 'Kaiu Shirai', 'genre' => 'Action / Dark Fantasy', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/188176l.jpg'],
    ['title' => 'Kaguya-sama: Love is War', 'author' => 'Aka Akasaka', 'genre' => 'Comédie / Action', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/188883l.jpg'],
    ['title' => 'Food Wars!', 'author' => 'Yuto Tsukuda', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/181489l.jpg'],
    ['title' => 'Assassination Classroom', 'author' => 'Yusei Matsui', 'genre' => 'Comédie / Action', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/178052l.jpg'],
    ['title' => 'Parasyte', 'author' => 'Hitoshi Iwaaki', 'genre' => 'Seinen / Dark Fantasy', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/258750l.jpg'],
    ['title' => 'Claymore', 'author' => 'Norihiro Yagi', 'genre' => 'Action / Dark Fantasy', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/258751l.jpg'],
    ['title' => 'Hell\'s Paradise', 'author' => 'Yuji Kaku', 'genre' => 'Action / Dark Fantasy', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/213506l.jpg'],
    ['title' => 'Dandadan', 'author' => 'Yukinobu Tatsu', 'genre' => 'Action / Dark Fantasy', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/250787l.jpg'],
    ['title' => 'Sakamoto Days', 'author' => 'Yuto Suzuki', 'genre' => 'Comédie / Action', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/243676l.jpg'],
    ['title' => 'Frieren: Beyond Journey\'s End', 'author' => 'Kanehito Yamada', 'genre' => 'Aventure', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/232470l.jpg'],
    ['title' => 'Boruto: Two Blue Vortex', 'author' => 'Masashi Kishimoto', 'genre' => 'Shonen', 'cover' => 'https://cdn.myanimelist.net/images/manga/3/287312l.jpg'],
    ['title' => 'D.Gray-man', 'author' => 'Katsura Hoshino', 'genre' => 'Action / Dark Fantasy', 'cover' => 'https://cdn.myanimelist.net/images/manga/2/258753l.jpg'],
];

echo "List count: " . count($mangaSeedList) . "\n";
