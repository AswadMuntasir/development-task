<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expence;
use Illuminate\Support\Facades\DB;

class ExpenceController extends Controller
{
    public function index()
    {
        $expences = Expence::all();
        return response()->json([
            'status' => 'success',
            'expences' => $expences,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_reason' => 'required|string|max:255',
        ]);
        if($request->src) {
            $name = $request->file('src')->getClientOriginalName();
            $path = $request->file('src')->store('public/images');
        } else {
            $path = $request->file('src');
        }

        $expence = Expence::create([
            'expense_reason' => $request->expense_reason,
            'amount' => $request->amount,
            'note' => $request->note,
            'src' => $path,
            'custom_date' => $request->custom_date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Expence created successfully',
            'expence' => $expence,
        ]);
    }

    public function show($id)
    {
        $expence = Expence::find($id);
        return response()->json([
            'status' => 'success',
            'expence' => $expence,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'expense_reason' => 'required|string|max:255',
        ]);

        if($request->src) {
            $name = $request->file('src')->getClientOriginalName();
            $path = $request->file('src')->store('public/images');
        } else {
            $path = $request->file('src');
        }

        $expence = Expence::find($id);
        $expence->expense_reason = $request->expense_reason;
        $expence->amount = $request->amount;
        $expence->note = $request->note;
        $expence->src = $path;
        $expence->custom_date = $request->custom_date;
        $expence->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Expence updated successfully',
            'expence' => $expence,
        ]);
    }

    public function destroy($id)
    {
        $expence = Expence::find($id);
        $expence->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Expence deleted successfully',
            'expence' => $expence,
        ]);
    }

    public function expenceFilter(Request $request)
    {
        if ($request->datePrice == "NewToOld") {
            $expences = DB::table('expences')->orderBy('updated_at', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'expences' => $expences,
            ]);
        } else if ($request->datePrice == "OldToNew") {
            $expences = DB::table('expences')->orderBy('updated_at', 'asc')->get();
            return response()->json([
                'status' => 'success',
                'expences' => $expences,
            ]);
        } else if ($request->datePrice == "LowToHigh") {
            $expences = DB::table('expences')->orderBy('amount', 'asc')->get();
            return response()->json([
                'status' => 'success',
                'expences' => $expences,
            ]);
        } else if ($request->datePrice == "HighToLow") {
            $expences = DB::table('expences')->orderBy('amount', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'expences' => $expences,
            ]);
        } else if ($request->datePrice != null) {
            $expences = DB::table('expences')->Where('expense_reason', $request->datePrice)->get();
            return response()->json([
                'status' => 'success',
                'expences' => $expences,
            ]);
        }
    }
}