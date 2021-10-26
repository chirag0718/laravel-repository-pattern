<?php

namespace App\Repository;


use Illuminate\Http\Request;

interface BaseRepositoryInterface
{
    public function upserts(Request $request, int $id = null);
}
