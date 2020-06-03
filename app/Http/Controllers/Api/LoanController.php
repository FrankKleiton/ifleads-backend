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
        $loans = Loan::all();

        return response()->json($loans);
    }

    /**
     * Store a newly created loan in database.
     *
     * @param  \App\Http\Requests\StoreLoan  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLoan $request)
    {
        $info = (object) $request->validated();

        $material = Material::find($info->material_id);

        if ($material->returner_registration_mark) {
            return response()->json([
                'status' => 'forbidden',
                'message' => "Lost Materials can't be loan"
            ], 403);
        } else {
            if (
                ! $material->amount
                || $material->amount < $info->material_amount
            ) {
                return response()->json([
                    'status' => 'fail',
                    'message' => sprintf('The material amount %d is insuficient to do a loan.', $material->amount)
                ], 400);
            }
        }

        $loan = new Loan;
        $loan->fill([
            'tooker_id' => $info->tooker_id,
            'material_amount' => $info->material_amount,
            'loan_time' => now(),
        ]);

        $material->amount -= $info->material_amount;
        $material->save();

        $loan->material()->associate($material);
        $loan->user()->associate(Auth::user());
        $loan->save();

        $loan->makeHidden(['material', 'user']);
        $loan->refresh();

        return response()->json($loan, 201);
    }

    /**
     * Display the specified loan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Provide a valid loan, please.'
            ], 400);
        }

        return response()->json($loan);
    }

    /**
     * Update the specified loan in database.
     *
     * @param  \App\Http\Requests\UpdateLoan  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $loan = Loan::find($id);

        if (!$loan || $loan->return_time) {
            return response()->json([
                'status' => 'fail',
                'message' => !$loan
                    ? "The provided loan doesn't exists"
                    : 'Material already returned'
            ], 400);
        }

        $loan->return_time = now();

        $material = $loan->material()->first();
        $material->amount += $loan->material_amount;
        $material->save();

        $loan->save();

        return response()->json($loan, 200);
    }

    /**
     * Remove the specified loan from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json([
                'status' => 'fail',
                'message' => "Provide a valid loan, please."
            ], 400);
        }

        $loan->delete();
    }
}
