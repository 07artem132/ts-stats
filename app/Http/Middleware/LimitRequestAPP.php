<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 06.07.2017
 * Time: 21:10
 */

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\UserToken;
use App\Exceptions\InvalidToken;
use App\Exceptions\TooManyRequest;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use \Illuminate\Database\Eloquent\ModelNotFoundException;

class LimitRequestAPP
{
    /**
     * The rate limiter instance.
     *
     * @var \Illuminate\Cache\RateLimiter
     */
    protected $limiter;

    /**
     * LimitRequestAPP constructor.
     * @param RateLimiter $limiter
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, Closure $next)
    {
        try {
            $token = $request->header('X-token');
            $tokenDB = UserToken::Token($token)->firstOrFail();

            ($tokenDB->app_type === 1) ? $limit = env('LIMIT_REQUEST_APP_TYPE_1', 30) : $limit = env('LIMIT_REQUEST_APP_TYPE_2', 60);
            ($tokenDB->app_type === 1) ? $decayMinutes = env('LIMIT_REQUEST_APP_TYPE_1_DECAY_MINUTES', 1) : $decayMinutes = env('LIMIT_REQUEST_APP_TYPE_2_DECAY_MINUTES', 1);


            $key = $this->resolveRequestSignature($request);

            if ($this->limiter->tooManyAttempts($key, $limit, $decayMinutes)) {
                throw new TooManyRequest($this->limiter->availableIn($key));
                // return $this->buildResponse($key, $limit);
            }

            $this->limiter->hit($key, $decayMinutes);

            $response = $next($request);

            return $this->addHeaders(
                $response, $limit,
                $this->calculateRemainingAttempts($key, $limit)
            );
        } catch (ModelNotFoundException $e) {
            throw new InvalidToken();
        }
    }

    /**
     * Resolve request signature.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    protected function resolveRequestSignature($request)
    {
        // return $request->fingerprint();
        return $request->header('X-token');
    }

    /**
     * Create a 'too many attempts' response.
     *
     * @param  string $key
     * @param  int $maxAttempts
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse($key, $maxAttempts)
    {
        $response = new Response('Too Many Attempts.', 429);

        $retryAfter = $this->limiter->availableIn($key);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }

    /**
     * Add the limit header information to the given response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response $response
     * @param  int $maxAttempts
     * @param  int $remainingAttempts
     * @param  int|null $retryAfter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addHeaders(Response $response, $maxAttempts, $remainingAttempts, $retryAfter = null)
    {
        $headers = [
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ];

        if (!is_null($retryAfter)) {
            $headers['Retry-After'] = $retryAfter;
            $headers['X-RateLimit-Reset'] = Carbon::now()->getTimestamp() + $retryAfter;
        }

        $response->headers->add($headers);

        return $response;
    }

    /**
     * Calculate the number of remaining attempts.
     *
     * @param  string $key
     * @param  int $maxAttempts
     * @param  int|null $retryAfter
     * @return int
     */
    protected function calculateRemainingAttempts($key, $maxAttempts, $retryAfter = null)
    {
        if (is_null($retryAfter)) {
            return $this->limiter->retriesLeft($key, $maxAttempts);
        }

        return 0;
    }
}
