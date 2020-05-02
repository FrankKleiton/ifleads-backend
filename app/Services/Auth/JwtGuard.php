<?php

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\GuradHelpers;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JwtGuard implements Guard
{
    use GuradHelpers;

    protected $request;

    protected $jwt;

    public function __construct(
        UserProvider $provider,
        Request $request,
        JsonWebToken $jwt
    ) {
        $this->provider = $provider;
        $this->request = $request;
        $this->jwt = $jwt;
    }

    public function user()
    {
        if (!is_null($this->user))
            return $this->user;

        $user = null;

        $token = $this->request->bearerToken();

        try {
            if ($token) {
                $payload = $this->jwt->getPayload($token);
                $user = $this->provider->retrieveByCredentials($payload);
            }
            /* $this->user é definido no GuardHelper */
            return $this->user = $user;
        } catch(\Exception $e) {
            abort(403, $e->getMessage());
        }
    }

    public function validate(array $credentials = [])
    {
        $token = $this->request->bearerToken();

        return $token;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }
}
