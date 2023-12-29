<?php

namespace App\Http\Controllers;

use App\Models\Markdown;
use App\Models\Resource;
use Exception;
use Illuminate\Http\Request;

class MarkdownController extends Controller
{
    public function show(Request $request, string $id)
    {
        $resource = Resource::query()->find($id);

        if ($resource->user_id !== $request->user()->id) {
            return response()->json(['msg' => '非法请求'], 403);
        }

        $markdown = Markdown::query()->where(['resource_id' => $id])->first();

        $content = $markdown ? $markdown->content : '';

        return response()->json($content);
    }

    public function save(Request $request, string $id)
    {
        try {
            $resource = Resource::query()->find($id);

            if ($resource->user_id !== $request->user()->id) {
                return response()->json(['msg' => '非法请求'], 403);
            }

            Markdown::query()->updateOrCreate(['resource_id' => $id], ['content' => $request->post('content')]);

            return response()->json(['msg' => '保存成功']);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
