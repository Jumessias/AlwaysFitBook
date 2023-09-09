<?php

namespace App\Controllers;

use App\Helpers\Helper;
use App\Helpers\Response;
use App\Models\Gallery;

class GalleryController
{
    public function index()
    {
        if(Helper::UserAuth() != null){
            return Response::json(Gallery::all());
        }else{
            return Response::notAuth();
        }
    }

    public function show(int $id)
    {
        if(Helper::UserAuth() != null){
            $gallery = Gallery::find($id);

            if ($gallery) {
                return $gallery;
            }
            return Response::notFound();
        }else{
            return Response::notAuth();
        }
    }

    public function store()
    {
        $user = Helper::UserAuth();
        if($user != null){

            $data = json_decode(file_get_contents("php://input"), true);

            if(!isset($data['title']) || !isset($data['category']) || !isset($data['image'])){
                return Response::json(['msg'=>'Os campos: Título, Categoria e Imagem são obrigatórios'], 400);
            }

            $uploadpath   = './upload/';
            $parts        = explode(";base64,", $data['image']);
            $imageparts   = explode("image/", @$parts[0]);
            $imagetype    = $imageparts[1];
            $imagebase64  = base64_decode($parts[1]);
            $name = uniqid() . '.png';
            $file         = $uploadpath . $name;
            file_put_contents($file, $imagebase64);

            $gallery = new Gallery();

            $gallery->title = $data["title"];
            $gallery->description = $data["description"] ?? null;
            $gallery->category = $data["category"];
            $gallery->favorite = false;
            $gallery->user = $user->id;
            $gallery->image = 'upload/'.$name;
            $gallery->created_at = date('y-m-d h:m:i');
            $gallery->updated_at = date('y-m-d h:m:i');
            
            $gallery->save();

            return Response::json((array)$gallery);
        }else{
            return Response::notAuth();
        }
    }

    public function update(int $id)
    {
        if(Helper::UserAuth() != null){
            $data = json_decode(file_get_contents("php://input"), true);

            if(!isset($data['title']) || !isset($data['category'])){
                return Response::json(['msg'=>'Os campos: Título, Categoria e Imagem são obrigatórios'], 400);
            }

            $updatedGallery = Gallery::update($id, $data);
            return Response::json($updatedGallery);
        }else{
            return Response::notAuth();
        }
    }

    public function destroy(int $id)
    {
        if(Helper::userAuth() != null){
            return Gallery::destroy($id);
        }else{
            return Response::notAuth();
        }
    }
}
