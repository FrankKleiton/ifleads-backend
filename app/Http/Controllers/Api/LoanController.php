<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanStore;
use App\Material;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Loan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkLostMaterial')->only('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = Loan::all();

        return response()->json($loans);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanStore $request)
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

        $loan = $loan->makeHidden(['material', 'user']);

        return response()->json($loan, 201);
    }

    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $fresh_info = $request->validate([
            'tooker_id' => 'integer',
            'loaned' => 'boolean'
        ]);

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
