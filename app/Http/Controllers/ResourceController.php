<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceRequest;
use App\Models\Resource;

class ResourceController extends Controller
{
    //
    public function index(ResourceRequest $request)
    {
        $user = $request->user();

        $resources = Resource::query()
            ->with([
                'children' => function ($query) {
                    $query->select(['id', 'name', 'type', 'parent_id']);
                }
            ])
            ->select(['id', 'name', 'type', 'parent_id'])
            ->where(['user_id' => $user->id, 'parent_id' => 0])
            ->get();

        return response()->json($resources);
    }
}
