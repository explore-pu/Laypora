<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceStoreRequest;
use App\Models\Resource;
use Exception;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = $request->user();

        $resources = Resource::query()
            ->with(['children'])
            ->select(['id', 'name', 'type', 'parent_id'])
            ->where(['user_id' => $user->id, 'parent_id' => 0])
            ->orderBy('type')
            ->get();

        return response()->json($resources);
    }

    public function store(ResourceStoreRequest $request)
    {
        try {
            $user = $request->user();

            $data = $request->validated();
            $data['user_id'] = $user->id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');

            $id = Resource::query()->insertGetId($data);

            return response()->json($id);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
