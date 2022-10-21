<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductDetailRequest;
use App\Http\Requests\UpdateProductDetailRequest;
use App\Http\Resources\Admin\ProductDetailResource;
use App\Models\ProductDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductDetailApiController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('product_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductDetailResource(ProductDetail::with(['product'])->get());
    }

    public function store(StoreProductDetailRequest $request)
    {
        $productDetail = ProductDetail::create($request->all());

        return (new ProductDetailResource($productDetail))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ProductDetail $productDetail)
    {
        // abort_if(Gate::denies('product_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductDetailResource($productDetail->load(['product']));
    }

    public function update(UpdateProductDetailRequest $request, ProductDetail $productDetail)
    {
        $productDetail->update($request->all());

        return (new ProductDetailResource($productDetail))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ProductDetail $productDetail)
    {
        // abort_if(Gate::denies('product_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productDetail->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
