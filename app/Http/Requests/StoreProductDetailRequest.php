<?php

namespace App\Http\Requests;

use App\Models\ProductDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_detail_create');
    }

    public function rules()
    {
        return [
            'price' => [
                'required',
            ],
            'product_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
