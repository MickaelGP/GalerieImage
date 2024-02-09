<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Models\Photo;
use Illuminate\Http\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image; //Intervention image
use Carbon\Carbon; 

class PhotoController extends Controller
{
    public function __construct(){
        Carbon::setLocale('fr');
    }
     protected $perPage = 6;

    public function index()
    {
        $photos = Photo::orderByDesc('created_at')->paginate($this->perPage);
        $data = [
            'title' => $description = 'Mon album',
            'description' => $description,
            'photos' => $photos ?? false,
        ];
        return view('photo.index', $data);
    }

    public function store(StorePhotoRequest $request)
    {
        //
        $photos = $request->validated();
        if($photos){
            $ext = $request->file('photo')->extension();
            $filename = Str::uuid().'.'.$ext;
            
            $photo = Photo::create($photos);
            $photo->filename = $filename;
            $photo->path = $request->file('photo')->storeAs('photos',$filename,'public');
            $photo->url = Storage::disk('public')->url($photo->path);
            $photo->size = Storage::disk('public')->size($photo->path);

            @mkdir(public_path('thumbs')); //Creation du dossier thumbs

            Image::make($request->file('photo'))->fit(348,225)->save(public_path('thumbs/'.$filename),50);

            $photo->thumb_path = Storage::putFileAs('photos/thunbs', new File(public_path('thumbs/'.$filename)),$filename);
            $photo->thumb_url = Storage::url($photo->thumb_path);
            $photo->thumb_size = Storage::size($photo->thumb_path);
            $photo->width = Image::make(request()->file('photo'))->width();
            $photo->height = Image::make(request()->file('photo'))->height();

            FileFacade::delete(public_path('thumbs/'.$filename));
            $photo->save();

            return back()->with('success','Photo uploader');
        }
    }

    public function download(Photo $photo)
    {
        return Storage::download($photo->path);
    }
    
    public function destroy(Photo $photo)
    {
        Storage::delete($photo->thumb_path);
        Storage::delete($photo->path);
        $photo->delete();

        return back()->with('success','Photo supprimer');
    }
}
