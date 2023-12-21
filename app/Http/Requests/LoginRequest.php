<?php


namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return Builder|Model|object|null
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function authenticate()
    {
        // 判断是否认证限制
        $this->ensureIsNotRateLimited();

        // 验证用户
        if (!$user = User::query()->where($this->only('username'))->first()) {
            throw new ValidationException($this->getValidatorInstance(), $this->response([
                'username' => __('auth.failed')
            ]));
        }

        // 验证用户密码
        if (!Hash::check($this->input('password'), $user->password)) {
            // 限制累计次数
            RateLimiter::hit($this->throttleKey());

            throw new ValidationException($this->getValidatorInstance(), $this->response([
                'password' => __('auth.password')
            ]));
        }

        // 清除限制
        RateLimiter::clear($this->throttleKey());
        // 更新用户api_token，加上时间戳，用于验证过期时间
        $api_token = base64_encode(Str::random(22) . time());

        $user->api_token = $api_token;
        $user->save();

        return $user;
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws ValidationException|BindingResolutionException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw new ValidationException($this->getValidatorInstance(), $this->response([
            'username' => trans('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)])
        ]));
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey(): string
    {
        return Str::lower($this->input('username')) . '|' . $this->ip();
    }
}
