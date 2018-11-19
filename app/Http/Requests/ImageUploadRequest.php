<?php

namespace App\Http\Requests;

use App\Models\SnipeModel;
use Intervention\Image\Facades\Image;
use Storage;
use Illuminate\Support\Facades\File;

class ImageUploadRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'mimes:png,gif,jpg,jpeg,svg',
            'avatar' => 'mimes:png,gif,jpg,jpeg,svg',
        ];
    }

    public function response(array $errors)
    {
        return $this->redirector->back()->withInput()->withErrors($errors, $this->errorBag);
    }

    /**
     * Handle and store any images attached to request
     * @param SnipeModel $item Item the image is associated with
     * @param String $path  location for uploaded images, defaults to uploads/plural of item type.
     * @return SnipeModel        Target asset is being checked out to.
     */
    public function handleImages($item, $w = 550, $path = null)
    {

        $type = strtolower(class_basename(get_class($item)));

        if(is_null($path)) {
            $path =  str_plural($type);
        }


        if ($this->hasFile('image')) {
            if (!config('app.lock_passwords')) {

                if(!Storage::disk('public')->exists($path)) Storage::disk('public')->makeDirectory($path, 775);

                $upload = $image = $this->file('image');
                $ext = $image->getClientOriginalExtension();
                $file_name = $type.'-'.str_random(18).'.'.$ext;

                if ($image->getClientOriginalExtension()!='svg') {
                    $upload = Image::make($image->getRealPath())->resize(null, $w, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                // This requires a string instead of an object, so we use ($string)
                Storage::disk('public')->put($path.'/'.$file_name, (string)$upload->encode());

                // Remove Current image if exists
                if (($item->image) && (file_exists($path.'/'.$item->image))) {
                    Storage::disk('public')->delete($path.'/'.$file_name);
                }

                $item->image = $file_name;
            }
        } elseif ($this->input('image_delete')=='1') {
            Storage::disk('public')->delete($path.'/'.$item->image);
            $item->image = null;
        }
        return $item;
    }
}
