<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Frame;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'email' => 'fpsecond.hh@gmail.com',
            'name' => 'Hansen Halim',
            'password' => '$2y$10$SvKxoRhQZUOcyOi1xxiS1eQT6fi4JXT7srM4JL0jrM2YHRtIBEKm.', //password
        ]);

        $data = [
            [[
                'width_px' => 600,
                'height_px' => 400,
                'x_offset' => 0,
                'y_offset' => 22,
            ]], [[
                'width_px' => 600,
                'height_px' => 400,
                'x_offset' => 0,
                'y_offset' => 425,
            ]], [[
                'width_px' => 600,
                'height_px' => 400,
                'x_offset' => 0,
                'y_offset' => 828,
            ]], [[
                'width_px' => 600,
                'height_px' => 400,
                'x_offset' => 600,
                'y_offset' => 22,
            ]], [[
                'width_px' => 600,
                'height_px' => 400,
                'x_offset' => 600,
                'y_offset' => 425,
            ]], [[
                'width_px' => 600,
                'height_px' => 400,
                'x_offset' => 600,
                'y_offset' => 828,
            ]]
        ];

        $frame = Frame::create([
            'slot_count' => 6,
            'width_px' => 1200,
            'height_px' => 1800,
            'data' => $data,
        ]);

        $frame
            ->addMedia(storage_path('app/private/frame.png'))
            ->preservingOriginal()
            ->toMediaCollection('image');

        $frame
            ->addMedia(storage_path('app/private/thumb.jpg'))
            ->preservingOriginal()
            ->toMediaCollection('thumb');
    }
}
