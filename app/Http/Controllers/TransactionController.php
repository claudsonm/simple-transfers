<?php

namespace App\Http\Controllers;

use App\Aggregates\WalletAggregateRoot;
use App\Http\Requests\CreateTransactionRequest;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTransactionRequest $request)
    {
        $aggregateRoot = WalletAggregateRoot::retrieve($request->retrievePayerWallet()->uuid);
        $aggregateRoot->createTransaction($request->getValueAsMoney(), $request->input('payee'));
        $aggregateRoot->persist();

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }
}
