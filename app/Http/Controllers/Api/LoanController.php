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
    public function __construct()
    {
        $this->middleware('checkLostMaterial')->only('store');
    }

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

        $loan = new Loan;
        $loan->fill([
            'tooker_id' => $info->tooker_id,
            'loan_time' => now(),
            'loaned' => true,
        ]);

        $loan->material()->associate($material);
        $loan->user()->associate(Auth::user());
        $loan->save();

        $loan->makeHidden(['material', 'user']);

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
    public function update(UpdateLoan $request, $id)
    {
        $fresh_info = $request->validated();

        $loan = Loan::find($id);
        $loan->fill($fresh_info);

        if ($loan->isDirty('loaned')) {
            $loan->return_time = now();
        }

        $loan->save();

        if (!$loan->wasChanged()) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Provide valid fields, please.'
            ], 400);
        }

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

        // REMIND MYSELF OF CHECK IF THIS VALIDATION CAN BE MAKE IN LOAN HOOK
        if ($loan->loaned) {
            return response()->json([
                'status' => 'fail',
                'message' => "It's not possible exclude loaned materials's loan. Get the material back, please."
            ], 400);
        }

        $loan->delete();
    }
}
