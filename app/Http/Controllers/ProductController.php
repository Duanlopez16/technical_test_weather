<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * ProductController
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $products = \App\Models\Product::where('status', '=', '1')
                ->orderBy('name')
                ->get(['id', 'uuid', 'name']);

            if (!empty($products->toArray())) {
                $response_data = \App\Services\Utils::get_response('success', 'success', $products);
            } else {
                $response_data = \App\Services\Utils::get_response('succcess', 'No encontramos productos.');
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }
        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * get_products_pag
     *
     * @return void
     */
    public function get_products_pag()
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $limit = request()->query('limit') ?? 10;
            $countries = \App\Models\Product::select('id', 'uuid', 'name')
                ->OrderBy('name', 'desc')->paginate($limit);
            $response_data = \App\Services\Utils::get_response('success', 'success', $countries);
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }
        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\ProductRequest $request)
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $model = new \App\Models\Product();
            $model->name = $request->name;
            $model->description = $request->description;
            if ($model->save()) {
                $response_data = \App\Services\Utils::get_response('succcess', 'Guardado correctamente', $model->uuid);
            } else {
                $response_data = \App\Services\Utils::get_response('error', 'Error al guardar el registro');
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }
        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function show(int $product_id)
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $product_data = \App\Models\Product::where('id', '=', $product_id)->first(['id', 'uuid', 'name']);
            if (!empty($product_data)) {
                $response_data = \App\Services\Utils::get_response('success', 'success', $product_data);
            } else {
                $response_data = \App\Services\Utils::get_response('success', 'No encontramos el producto ingresado.');
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }

        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * show_uuid
     *
     * @param  string $product_uuid
     * @return void
     */
    public function show_uuid(string $product_uuid)
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $product_data = \App\Models\Product::where('uuid', '=', $product_uuid)->first(['id', 'uuid', 'name']);
            if (!empty($product_data)) {
                $response_data = \App\Services\Utils::get_response('success', 'success', $product_data);
            } else {
                $response_data = \App\Services\Utils::get_response('success', 'No encontramos el producto ingresado.');
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }

        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\ProductRequest $request, string $uuid)
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $response_validate =  \App\Services\Utils::validate_uuid($uuid);
            if ($response_validate->status) {
                $product_data = \App\Models\Product::where('uuid', '=', $uuid)->where('status', '=', 1)->first();
                if (!empty($product_data)) {
                    $product_data->name = $request->name;
                    $product_data->description = $request->description;
                    if ($product_data->update()) {
                        $response_data = \App\Services\Utils::get_response('error', 'Actualizado correctamente.', $product_data->uuid);
                    } else {
                        $response_data = \App\Services\Utils::get_response('error', 'No se logro editar el registro.');
                    }
                } else {
                    $response_data = \App\Services\Utils::get_response('error', 'No encontramos el producto ingresado.');
                }
            } else {
                $response_data = \App\Services\Utils::get_response('error', $response_validate->message);
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }
        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $uuid)
    {
        try {
            $validate_uuid =  \App\Services\Utils::validate_uuid($uuid);
            if ($validate_uuid->status) {
                $product_data = \App\Models\Product::where('uuid', '=', $uuid)->where('status', '=', 1)->first();
                if (!empty($product_data)) {
                    $product_data->status = 0;
                    if ($product_data->update()) {
                        $response_data = \App\Services\Utils::get_response('success', 'Eliminado correctamente.');
                    } else {
                        $response_data = \App\Services\Utils::get_response('error', 'No se logró eliminar el país.');
                    }
                } else {
                    $response_data = \App\Services\Utils::get_response('error', 'No encontramos el país seleccionado.');
                }
            } else {
                $response_data = \App\Services\Utils::get_response('error', $validate_uuid->message);
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }
        return response()->json($response_data->response_data, $response_data->status_code);
    }
}
