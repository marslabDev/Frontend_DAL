<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductDetailRequest;
use App\Http\Requests\StoreProductDetailRequest;
use App\Http\Requests\UpdateProductDetailRequest;
use App\Models\Product;
use App\Models\ProductDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProductDetailController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('product_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ProductDetail::with(['product'])->select(sprintf('%s.*', (new ProductDetail())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'product_detail_show';
                $editGate = 'product_detail_edit';
                $deleteGate = 'product_detail_delete';
                $crudRoutePart = 'product-details';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product']);

            return $table->make(true);
        }

        $products = Product::get();

        return view('admin.productDetails.index', compact('products'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.productDetails.create', compact('products'));
    }

    public function store(StoreProductDetailRequest $request)
    {
        $productDetail = ProductDetail::create($request->all());

        return redirect()->route('admin.product-details.index');
    }

    public function edit(ProductDetail $productDetail)
    {
        abort_if(Gate::denies('product_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productDetail->load('product');

        return view('admin.productDetails.edit', compact('productDetail', 'products'));
    }

    public function update(UpdateProductDetailRequest $request, ProductDetail $productDetail)
    {
        $productDetail->update($request->all());

        return redirect()->route('admin.product-details.index');
    }

    public function show(ProductDetail $productDetail)
    {
        abort_if(Gate::denies('product_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productDetail->load('product');

        return view('admin.productDetails.show', compact('productDetail'));
    }

    public function destroy(ProductDetail $productDetail)
    {
        abort_if(Gate::denies('product_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductDetailRequest $request)
    {
        ProductDetail::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
