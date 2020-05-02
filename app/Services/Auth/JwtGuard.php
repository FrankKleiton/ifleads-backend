<?php

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class JwtGuard implements Guard
{
    use GuardHelpers;

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

    /**
     * Check if user credentials is valid
     * 
     * Check if user credentials provided is equivalent
     * with an persisted user. The persisted password must
     * be stored as a hash for this method work.
     * 
     * @param array $credentials
     * @return string|null
     */
    public function attempt(array $credentials): ?string
    {
      $user = User::where('email', $credentials['email'])->first();

      if (!Hash::check($credentials['senha'], $user->senha)) {
          return null;
      }

      return $this->jwt->generateToken([
        'email' => $user->email, 
        'id' => $user->id
      ]);
    }
}
