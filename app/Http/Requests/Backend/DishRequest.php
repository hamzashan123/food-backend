<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class DishRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
            {
                return [
                    'name' => ['required', 'max:255'],
                    'price' => ['required', 'numeric'],                                        
                    'tags.*' => ['required'],
                    'ingrediants.*' => ['required'],
                    'people_types_id' => ['required'],
                    'status' => ['required'],
                    'description' => ['required', 'max:1000'],
                    'details' => ['required', 'max:10000'],
                    'images' => ['required'],
                    'images.*' => ['mimes:jpg,jpeg,png,gif', 'max:3000']
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => ['required', 'max:255'],
                    'description' => ['required', 'max:1000'],
                    'price' => ['required', 'numeric'],                    
                    'tags.*' => ['required'],
                    'ingrediants.*' => ['required'],
                    'people_types_id' => ['required'],
                    'details' => ['required', 'max:10000'],
                    'status' => ['required'],
                    'images' => ['nullable'],
                    'images.*' => ['mimes:jpg,jpeg,png,gif', 'max:3000']
                ];
            }
            default: break;
        }
    }
}
