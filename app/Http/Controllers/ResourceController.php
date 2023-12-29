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

            $resource = new Resource();

            $resource->user_id = $user->id;
            $resource->parent_id = $data['parent_id'];
            $resource->type = $data['type'];
            $resource->name = $data['name'];
            $resource->save();

            return response()->json($resource->id);
        } catch (Exception $exception) {
            return response()->json(['msg'=> $exception->getMessage()], 500);
        }
    }

    public function rename(Request $request, string $id)
    {
        try {
            $resource = Resource::query()->find($id);
            $resource->name = $request->post('name');
            $resource->save();

            return response()->json(['msg' => '重命名成功']);
        } catch (Exception $exception) {
            return response()->json(['msg'=> $exception->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            Resource::query()->find($id)->delete();

            return response()->json(['msg' => '删除成功']);
        } catch (Exception $exception) {
            return response()->json(['msg'=> $exception->getMessage()], 500);
        }
    }
}
