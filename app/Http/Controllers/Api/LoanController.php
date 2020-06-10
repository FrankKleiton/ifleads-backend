<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoan;
use App\Http\Requests\UpdateLoan;
use App\Material;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Loan;
use Illuminate\Support\Facades\Auth;


class LoanController extends Controller
{
    /**
     * Display a listing of the loans.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Loan::all());
    }

    /**
     * Store a newly created loan in database.
     *
     * @param  \App\Http\Requests\StoreLoan  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLoan $request, Material $material)
    {
        $info = (object) $request->validated();

        if ($material->returner_registration_mark) {
            return response()->json([
                'status' => 'forbidden',
                'message' => __('response.invalid_material_type')
            ], 403);
        }

        if ($material->isAnBorrowableAmount($info->material_amount)) {
            return response()->json([
                'status' => 'fail',
                'message' => __('response.insuficient_material')
            ], 400);
        }

        // I leave that way because the application is too small
        // to use a service pattern and does'nt make sense create
        // a model method that update another model.
        $loan = new Loan;
        $loan->fill([
            'tooker_id' => $info->tooker_id,
            'material_amount' => $info->material_amount,
            'loan_time' => now(),
        ]);

        $material->decrement('amount', $info->material_amount);

        $loan->material()->associate($material);
        $loan->user()->associate(Auth::user());
        $loan->save();

        return response()->json($loan, 201);
    }

    /**
     * Display the specified loan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        return response()->json($loan);
    }

    /**
     * Update the specified loan in database.
     *
     * @param  \App\Http\Requests\UpdateLoan  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Loan $loan)
    {
        if ($loan->return_time) {
            return response()->json([
                'status' => 'fail',
                'message' => __('response.material_returned')
            ], 400);
        }

        $loan->return_time = now();

        $material = $loan->material()->first();
        $material->increment('amount', $loan->material_amount);

        $loan->save();

        return response()->json($loan, 200);
    }

    /**
     * Remove the specified loan from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();
    }
}
