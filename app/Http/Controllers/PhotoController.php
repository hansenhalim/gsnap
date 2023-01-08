<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoPaper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class PhotoController extends Controller
{
    public function create(Request $request)
    {
        $photoPaper = PhotoPaper::findOrFail($request->photo_paper_id);
        $photoPaper->load('frame.media', 'photos');

        $baseUrl = null ?? 'http://localhost:8001';
        $triggerUrl = $baseUrl . '/api/trigger';

        return Inertia::render('Photo/Create', [
            'photoPaper' => $photoPaper,
            'triggerUrl' => $triggerUrl,
            'bearerToken' => $request->user()->createToken('DSLR')->plainTextToken,
            'uploadUrl' => route('photos.store'),
        ]);
    }

    public function store(Request $request)
    {
        $photoPaper = PhotoPaper::findOrFail($request->photo_paper_id);

        if ($photoPaper->frame->slot_count - $photoPaper->photos()->count() < 1) {
            $data = ['message' => 'Paper Photo is already full.'];
            return response()->json($data, Response::HTTP_TOO_MANY_REQUESTS);
        }

        $photo = $photoPaper->photos()->create();

        $media = $photo
            ->addMediaFromRequest('image')
            ->toMediaCollection('image');

        $photo->selected_url = $media->getUrl();
        $photo->save();

        $request->user()->tokens()->delete();

        $data = [
            'photoPaper' => $photoPaper,
            'photo' => $photo,
        ];

        return response()->json($data, Response::HTTP_CREATED);
    }

    public function update(Request $request, Photo $photo)
    {
        $filters = ['original', 'greyscale', 'sepia'];

        // loop trick
        array_push($filters, $filters[0]);

        $oldFilter = $photo->applied_filter;
        $oldFilterIdx = array_search($oldFilter, $filters);
        $newFilterIdx = $oldFilterIdx + 1;
        $newFilter = $filters[$newFilterIdx];

        $photo->applied_filter = $newFilter;
        $photo->selected_url = $photo->getFirstMediaUrl('image', $newFilter);
        $photo->save();

        return back();
    }

    public function destroy(Photo $photo)
    {
        $photo->delete();
        return back();
    }

    public function trigger(Request $request)
    {
        if (App::isProduction()) abort(403);

        $output = `cd ../storage/app/private && gphoto2 --capture-preview`;

        $photo = fopen(storage_path('app/private/capture_preview.jpg'), 'r');

        $output = `cd ../storage/app/private && rm capture_preview.jpg`;

        $response = Http::acceptJson()
            ->withToken($request->bearer_token)
            ->attach('image', $photo)
            ->post($request->upload_url, ['photo_paper_id' => $request->photo_paper_id]);

        if ($response->failed()) return response()->json($response->json(), $response->status());

        return response()->json(['message' => 'Photo captured and uploaded successfully!'], Response::HTTP_CREATED);
    }
}
