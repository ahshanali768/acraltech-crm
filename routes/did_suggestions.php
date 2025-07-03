<?php

use Illuminate\Support\Facades\Route;
use App\Models\Campaign;

Route::get('/agent/did-suggestions', function() {
    $dids = Campaign::select('did', 'campaign_name')->get()->map(function($c) {
        return [
            'did' => $c->did,
            'label' => $c->did . ($c->campaign_name ? ' - ' . $c->campaign_name : '')
        ];
    });
    return response()->json($dids);
})->middleware(['auth', 'role:agent']);
