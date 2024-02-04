<?php

namespace App\Services;

class PhotoService
{
    public function getAll($query, ?string $sort)
    {
        switch ($sort) {
            case 'newest':
                $photos = $query->orderByDesc('created_at');
                break;

            case 'oldest':
                $photos = $query->orderBy('created_at');
                break;

            case 'download':
                $photos = $query->withCount('downloads')->orderByDesc('downloads_count');
                break;

            case 'popular':
                $photos = $query->withCount([
                    'votes as likes' => function($query){
                        return $query->where('like', true);
                    },
                    'votes as dislikes' => function($query){
                        return $query->where('dislike', true);
                    }
                ])->orderByRaw('likes - dislikes desc');
                break;

            default:
                $photos = $query->orderByDesc('created_at');
                break;
        }

        return $photos->paginate()->withQueryString();
    }
}