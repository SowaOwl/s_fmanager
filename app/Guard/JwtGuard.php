<?php

namespace App\Guard;

use App\Models\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\JWT;

class JwtGuard implements Guard
{
    use GuardHelpers;

    public function __construct(protected JWT $jwt, protected Request $request)
    {
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        if ($this->jwt->setRequest($this->request)->getToken() && $this->jwt->check()) {
            $id = $this->jwt->payload()->get('sub');

            $this->user = new User();
            $this->user->id = $id;

            $this->setUserFromRedis($this->user);

            return $this->user;
        }
        return null;
    }

    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    private function setUserFromRedis(User $user)
    {
        $redis = Redis::connection();
        $response = $redis->get('users' . $user->id);
        $attributes = json_decode($response, true);
        foreach ($attributes as $key => $value) {
            $user->$key = $value;
        }
    }
}
