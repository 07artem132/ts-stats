<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 06.07.2017
 * Time: 19:47
 */

namespace App\Http\Middleware;

use App\Exceptions\InvalidToken;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\IPNotAllow;
use App\UserToken;
use Closure;
use Auth;

class TokenVerifiAllowIP
{

    public function handle($request, Closure $next)
    {
        try {
            $token = $request->header('X-token');
            $tokenDB = UserToken::Token($token)->firstOrFail();

            if ($tokenDB->app_type === 1)
                return $next($request);

            $IpArray = explode(',', (string)$tokenDB->allow_ip);

            for ($i = 0; $i < count($IpArray); $i++) {
                if ($IpArray[$i] === $request->ip())
                    return $next($request);
            }

            throw new IPNotAllow();
        } catch (ModelNotFoundException $e) {
            throw new InvalidToken();
        }


    }
}
