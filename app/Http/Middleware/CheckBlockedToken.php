<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 07.07.2017
 * Time: 15:43
 */

namespace App\Http\Middleware;

use App\Exceptions\TokenBlocked;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use App\UserToken;
use Closure;
use \Illuminate\Http\Request;
use App\Exceptions\InvalidToken;

class CheckBlockedToken
{
	/**
	 * @param Request $request
	 * @param Closure $next
	 *
	 * @return mixed
	 * @throws InvalidToken
	 * @throws TokenBlocked
	 */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->header('X-token');
            $tokenDB = UserToken::Token($token)->firstOrFail();
            if ($tokenDB->Blocked === 1) {
                throw new TokenBlocked();
            }

        } catch (ModelNotFoundException $e) {
            throw new InvalidToken();
        }

        return $next($request);
    }

}
