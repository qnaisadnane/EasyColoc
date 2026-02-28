<?php

namespace App\Http\Controllers;

use App\Models\Settlement;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    public function store(Request $request, \App\Models\Colocation $colocation)
    {
        $request->validate([
            'debtor_id' => 'required|exists:users,id',
            'creditor_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'month' => 'required|string|max:2',
            'year' => 'required|string|max:4',
        ]);

        // Seul le créancier (celui qui reçoit l'argent) peut marquer comme payé
        if (auth()->id() !== (int)$request->creditor_id) {
            abort(403, "Seul le destinataire du paiement peut valider cette transaction.");
        }

        Settlement::create([
            'colocation_id' => $colocation->id,
            'debtor_id' => $request->debtor_id,
            'creditor_id' => $request->creditor_id,
            'amount' => $request->amount,
            'month' => $request->month,
            'year' => $request->year,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Règlement enregistré par le créancier !');
    }

    public function markAsPaid(Settlement $settlement)
    {
        // ... (votre code existant si nécessaire, on peut le garder pour l'instant)
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
