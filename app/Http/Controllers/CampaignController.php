<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::all();
        return view('admin.manage_campaigns', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_name' => 'required|string|max:255',
            'commission_inr' => 'required|numeric',
            'did' => 'required|string|max:255',
            'payout_usd' => 'required|numeric',
            'status' => 'required|in:active,paused',
        ]);
        Campaign::create($request->only(['campaign_name', 'commission_inr', 'did', 'payout_usd', 'status']));
        return redirect()->back()->with('success', 'Campaign added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'campaign_name' => 'required|string|max:255',
            'commission_inr' => 'required|numeric',
            'did' => 'required|string|max:255',
            'payout_usd' => 'required|numeric',
            'status' => 'required|in:active,paused',
        ]);
        $campaign = Campaign::findOrFail($id);
        $campaign->update($request->only(['campaign_name', 'commission_inr', 'did', 'payout_usd', 'status']));
        return redirect()->back()->with('success', 'Campaign updated successfully!');
    }

    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();
        return redirect()->back()->with('success', 'Campaign deleted successfully!');
    }
}
