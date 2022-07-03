<?php

namespace App\Http\Controllers;

use App\SaleDistributor;
use Illuminate\Http\Request;

class SaleDistributorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $saleDistributor = SaleDistributor::query();
        $type = '';
        $type = $request->type == 'receiver' ? 'receiver' : 'deliverer';

        if ($request->ajax()){
            return $saleDistributor->whereType($type)
                ->where('name', 'LIKE', "%{$request->name}%")
                ->take(10)
                ->get();
        }
        return response($saleDistributor->paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaleDistributor  $saleDistributor
     * @return \Illuminate\Http\Response
     */
    public function show(SaleDistributor $saleDistributor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaleDistributor  $saleDistributor
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleDistributor $saleDistributor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SaleDistributor  $saleDistributor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleDistributor $saleDistributor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SaleDistributor  $saleDistributor
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleDistributor $saleDistributor)
    {
        //
    }
}
