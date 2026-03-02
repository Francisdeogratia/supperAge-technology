<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiAdvertisingController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $ads = DB::table('ads')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'ads'          => collect($ads->items())->map(fn($a) => $this->formatAd($a)),
            'total'        => $ads->total(),
            'current_page' => $ads->currentPage(),
            'last_page'    => $ads->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'budget'      => 'required|numeric|min:100',
            'currency'    => 'nullable|string|max:10',
            'link'        => 'nullable|url',
            'image'       => 'nullable|string',
            'ad_type'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = $request->user()->id;

        $id = DB::table('ads')->insertGetId([
            'user_id'     => $userId,
            'title'       => $request->title,
            'description' => $request->description,
            'budget'      => $request->budget,
            'currency'    => $request->currency ?? 'NGN',
            'link'        => $request->link,
            'image'       => $request->image,
            'ad_type'     => $request->ad_type ?? 'standard',
            'status'      => 'pending',
            'impressions' => 0,
            'clicks'      => 0,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $ad = DB::table('ads')->where('id', $id)->first();

        return response()->json(['ad' => $this->formatAd($ad), 'message' => 'Ad submitted for review'], 201);
    }

    public function show(Request $request, $id)
    {
        $userId = $request->user()->id;
        $ad     = DB::table('ads')->where('id', $id)->first();

        if (!$ad) return response()->json(['message' => 'Ad not found'], 404);
        if ($ad->user_id !== $userId) return response()->json(['message' => 'Unauthorized'], 403);

        return response()->json(['ad' => $this->formatAd($ad)]);
    }

    public function destroy(Request $request, $id)
    {
        $userId = $request->user()->id;
        $ad     = DB::table('ads')->where('id', $id)->first();

        if (!$ad) return response()->json(['message' => 'Ad not found'], 404);
        if ($ad->user_id !== $userId) return response()->json(['message' => 'Unauthorized'], 403);

        DB::table('ads')->where('id', $id)->delete();

        return response()->json(['message' => 'Ad deleted']);
    }

    private function formatAd($ad): array
    {
        return [
            'id'          => $ad->id,
            'title'       => $ad->title,
            'description' => $ad->description,
            'budget'      => (float) $ad->budget,
            'currency'    => $ad->currency,
            'link'        => $ad->link ?? null,
            'image'       => $ad->image ?? null,
            'ad_type'     => $ad->ad_type ?? 'standard',
            'status'      => $ad->status,
            'impressions' => (int) ($ad->impressions ?? 0),
            'clicks'      => (int) ($ad->clicks ?? 0),
            'created_at'  => $ad->created_at,
        ];
    }
}
