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
            [
                'width_px' => 600,
                'height_px' => 400,
                'x_offset' => 0,
                'y_offset' => 22,
            ], [
                'width_px' => 600,
                'height_px' => 400,
                'x_offset' => 0,
                'y_offset' => 425,
            ], [
                'width_px' => 600,
                'height_px' => 400,
                'x_offset' => 0,
                'y_offset' => 828,
            ], [
                'width_px' => 600,
                'height_px' => 400,
                'x_offset' => 0,
                'y_offset' => 1231,
            ],
        ];

        $frame1 = Frame::create([
            'slot_count' => 4,
            'width_px' => 600,
            'height_px' => 1800,
            'data' => array_slice($data, 0, 4),
        ]);

        $frame2 = Frame::create([
            'slot_count' => 3,
            'width_px' => 600,
            'height_px' => 1800,
            'data' => array_slice($data, 0, 3),
        ]);

        $frame3 = Frame::create([
            'slot_count' => 4,
            'width_px' => 600,
            'height_px' => 1800,
            'data' => array_slice($data, 0, 4),
        ]);

        $frame1
            ->addMedia(storage_path('app/private/frame_1_600_1800.png'))
            ->preservingOriginal()
            ->toMediaCollection('image');

        $frame2
            ->addMedia(storage_path('app/private/frame_2_600_1800.png'))
            ->preservingOriginal()
            ->toMediaCollection('image');

        $frame3
            ->addMedia(storage_path('app/private/frame_3_600_1800.png'))
            ->preservingOriginal()
            ->toMediaCollection('image');
    }
}
