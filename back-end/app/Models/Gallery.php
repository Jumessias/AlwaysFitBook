<?php

namespace App\Models;

use App\Repository\Gallery as DatabaseGallery;
use App\Helpers\Response;

class Gallery
{
    public int $id;
    public string $title;
    public string $description;
    public int $user;
    public string $category;
    public string $image;
    public int $favorite;
    public string|null $created_at;
    public string|null $updated_at;

    public static function all(): array
    {
        return DatabaseGallery::all();
    }

    public static function find(int $id): Gallery|bool
    {
        $gallery = self::_find($id);

        if ($gallery) {
            $gallery = (new Gallery())->load($gallery);
            return $gallery;
        }
        return false;
    }

    private static function _find(int $id): array|bool
    {
        $gallery = (new DatabaseGallery())->get_by_id($id);

        return $gallery;
    }

    private function load(array $gallery): Gallery
    {
        foreach ($gallery as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    public function toString(): array
    {
        return [
            "id"             => $this->id ?? 0,
            "title"         => $this->title,
            "description"    => $this->description,
            "category"       => $this->category,
            "image"          => $this->image,
            "favorite"       => $this->favorite,
            "user"           => $this->user,
            "created_at"     => $this->created_at,
            "updated_at"     => $this->updated_at
        ];
    }

    public function __toString()
    {
        return Response::json($this->toString());
    }

    public static function destroy(int $id): string
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return Response::notFound();
        }
        return DatabaseGallery::delete($id);
    }

    public function save(): string
    {
        $gallery = $this->toString();
        return DatabaseGallery::create($gallery);
    }

    public static function update(int $id, array $data): array
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return Response::notFound();
        }
        return DatabaseGallery::update($id, $data);
    }
}
