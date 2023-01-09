<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use App\Models\Order;
use App\Models\PhotoPaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;

class PhotoPaperController extends Controller
{
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $frames = Frame::with('media')->get();

        return Inertia::render('PhotoPaper/Create', [
            'order' => $order,
            'frames' => $frames,
        ]);
    }

    public function store(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $frame = Frame::findOrFail($request->frame_id);

        $photoPaper = PhotoPaper::updateOrCreate(
            ['order_id' => $order->id],
            ['frame_id' => $frame->id],
        );

        return Redirect::route('photos.create', ['photo_paper_id' => $photoPaper->id]);
    }

    public function show(PhotoPaper $photoPaper)
    {
        $photoPaper->final_url = $photoPaper->getFirstMediaUrl('image');
        return Inertia::render('PhotoPaper/Show', ['photoPaper' => $photoPaper]);
    }

    public function edit(PhotoPaper $photoPaper)
    {
        //
    }

    public function update(Request $request, PhotoPaper $photoPaper)
    {
        $photos = $photoPaper->photos;
        $frame = $photoPaper->frame;

        if ($photos->count() !== $frame->slot_count) abort(403);

        $img = Image::canvas($frame->width_px, $frame->height_px);

        foreach ($frame->data as $key => $data) {
            $filter = $photos[$key]->applied_filter;
            $source = $photos[$key]->getFirstMediaPath('image', $filter);
            $source = Image::make($source);
            $source->resize($data['width_px'], $data['height_px']);
            $img->insert($source, 'top-left', $data['x_offset'], $data['y_offset']);
        }

        $img->insert($frame->getFirstMediaPath('image'));

        $pathToFile = 'output.jpg';

        $img->save($pathToFile);

        $photoPaper
            ->addMedia($pathToFile)
            ->toMediaCollection('image');

        return Redirect::route('photo-papers.show', $photoPaper);
    }

    public function destroy(PhotoPaper $photoPaper)
    {
        //
    }
}
