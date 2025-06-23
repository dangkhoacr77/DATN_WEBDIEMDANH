<?php

namespace App\Http\Controllers\Trangchu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrangchuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = [
            (object)[
                'title' => 'Biểu mẫu 1',
                'description' => 'Mô tả biểu mẫu 1',
                'created_at' => now()
            ],
            (object)[
                'title' => 'Biểu mẫu 2',
                'description' => 'Mô tả biểu mẫu 2',
                'created_at' => now()->subDays(2)
            ]
        ];
        return view('Trangchu.Trangchu', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
