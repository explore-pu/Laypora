<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var Auth
     */
    protected Auth $auth;

    /**
     * Create a new middleware instance.
     *
     * @param Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $guard
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next, $guard = null): mixed
    {
        // 判断请求是否有认证信息
        if (!$request->hasHeader('Authorization')) {
            return response()->json(['msg' => '无认证信息'], 401);
        }
        // 获取密钥
        $api_token = substr($request->header('Authorization'), 7);
        // 判断是否是空密钥
        if (trim($api_token) === '') {
            return response()->json(['msg' => '认证信息为空'], 401);
        }
        // 解密密钥
        $decrypt_token = base64_decode($api_token);
        // 一天的时间
        $day = 60 * 60 * 24;
        // 判断密钥是否过期
        if (time() - substr($decrypt_token, -10) > $day * 2) {
            return response()->json(['msg' => '认证信息过期，请重新登录'], 401);
        }
        // 如果认证失败
        if ($this->auth->guard($guard)->guest()) {
            return response()->json(['msg' => '认证失败'], 401);
        }

        return $next($request);
    }
}
