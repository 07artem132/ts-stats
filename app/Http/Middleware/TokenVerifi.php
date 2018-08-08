<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 06.07.2017
 * Time: 19:47
 */

namespace App\Http\Middleware;

use App\Exceptions\InvalidToken;
use App\Exceptions\NotSpecified;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use App\UserToken;
use Closure;
use Auth;
use \Illuminate\Http\Request;

class TokenVerifi
{
	/**
	 * @param $request
	 * @param Closure $next
	 *
	 * @return mixed
	 * @throws InvalidToken
	 * @throws NotSpecified
	 */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->header('X-token');
            if (empty($token))
                throw new NotSpecified('X-token');

            $tokenDB = UserToken::Token($token)->firstOrFail();

            AUTH::onceUsingId($tokenDB->user_id);

        } catch (ModelNotFoundException $e) {
            throw new InvalidToken();
        }

        return $next($request);
    }

}
