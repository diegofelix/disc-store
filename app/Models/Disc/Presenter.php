<?php

namespace App\Models\Disc;

use Illuminate\Support\Collection;

class Presenter
{
    public function present(Collection $discs): array
    {
        foreach ($discs as $disc) {
            $data[] = $this->presentSingleDisc($disc);
        }

        return $data ?? [];
    }

    public function presentSingleDisc(Disc $disc): array
    {
        return [
            'id' => $disc->id,
            'name' => $disc->name,
            'artist' => $disc->artist,
            'style' => $disc->style,
            'released_at' => $disc->released_at->format('Y-m-d'),
            'stock' => $disc->stock,
        ];
    }
}
