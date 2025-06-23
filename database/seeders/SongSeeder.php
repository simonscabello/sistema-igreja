<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Song;
use App\Models\Tag;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Adoração',
            'Louvor',
            'Comunhão',
            'Gratidão',
            'Arrependimento',
            'Consagração',
            'Evangelismo',
            'Missões',
            'Família',
            'Jovens',
            'Crianças',
            'Natal',
            'Páscoa',
            'Batismo',
            'Casamento',
        ];

        foreach ($tags as $tagName) {
            Tag::firstOrCreate(['name' => $tagName]);
        }

        $songs = [
            [
                'name' => 'Grande é o Senhor',
                'key' => 'C',
                'youtube_link' => 'https://youtube.com/watch?v=example1',
                'spotify_link' => 'https://open.spotify.com/track/example1',
                'lyrics_link' => 'https://example.com/letra1',
                'chords_link' => 'https://example.com/cifra1',
                'tags' => ['Adoração', 'Louvor', 'Gratidão']
            ],
            [
                'name' => 'Santo, Santo, Santo',
                'key' => 'D',
                'youtube_link' => 'https://youtube.com/watch?v=example2',
                'lyrics_link' => 'https://example.com/letra2',
                'tags' => ['Adoração', 'Consagração']
            ],
            [
                'name' => 'Amazing Grace',
                'key' => 'G',
                'spotify_link' => 'https://open.spotify.com/track/example3',
                'chords_link' => 'https://example.com/cifra3',
                'tags' => ['Gratidão', 'Arrependimento']
            ],
            [
                'name' => 'Como é Grande o Meu Deus',
                'key' => 'A',
                'youtube_link' => 'https://youtube.com/watch?v=example4',
                'spotify_link' => 'https://open.spotify.com/track/example4',
                'lyrics_link' => 'https://example.com/letra4',
                'chords_link' => 'https://example.com/cifra4',
                'tags' => ['Adoração', 'Louvor', 'Gratidão']
            ],
            [
                'name' => 'Aos Pés da Cruz',
                'key' => 'Em',
                'lyrics_link' => 'https://example.com/letra5',
                'tags' => ['Arrependimento', 'Consagração']
            ],
        ];

        foreach ($songs as $songData) {
            $tags = $songData['tags'];
            unset($songData['tags']);
            
            $song = Song::create($songData);
            
            $tagIds = [];
            foreach ($tags as $tagName) {
                $tag = Tag::where('name', $tagName)->first();
                if ($tag) {
                    $tagIds[] = $tag->id;
                }
            }
            
            $song->tags()->attach($tagIds);
        }
    }
}
