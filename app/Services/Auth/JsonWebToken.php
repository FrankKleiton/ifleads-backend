<?php

namespace App\Services\Auth;

use Jweety\EncoderInterface;

class JsonWebToken
{

    private $encoder;
    private $claims = [];

    public function __construct(EncoderInterface $encoder, array $claims)
    {
        $this->encoder = $encoder;
        foreach($claims as $key => $value) {
            $this->claims[$key] = $value;
        }
    }

    public function generateToken(array $payload, int $exp_time = 0): string
    {
        if (empty($payload))
            throw new \Exception("Paramater 'payload' can't be empty");

        if (!empty($exp_time))
            $this->claims['exp'] => $exp_time;

        $this->claims['payload'] = $payload;

        $token = $this->encoder->stringify($this->claims);

        return $token;
    }

    public function getPayload(string $token = ''): array
    {
        if ($this->validateToken($token)) {
            $claims = $this->encoder->parse($token);
            return (array) $claims->payload;
        }

        return null;
    }

    public function validateToken(string $token = ''): bool
    {
        if(!empty($token)) {
            $claims = $this->encoder->parse($token);
            $currentTime = time();
            $isSameHost = $claims->host === config('auth.jwt.initial_claims.host');

            if ($isSameHost && $claims->exp > $currentTime)
                return true;
        }

        return false;
    }
}
