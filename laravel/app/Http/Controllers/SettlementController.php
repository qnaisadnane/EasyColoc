<?php

namespace App\Http\Controllers;

use App\Models\Settlement;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    public function markAsPaid(Settlement $settlement)
    {
        // Validation : seul le créancier ou l'admin peut marquer comme payé
        if (auth()->id() !== $settlement->creditor_id && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $settlement->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Paiement confirmé !');
    }
}
