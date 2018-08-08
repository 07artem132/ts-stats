<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 30.07.2017
 * Time: 18:05
 */

namespace App\Http\Middleware;

use App\UserToken;
use Closure;
use App\Exceptions\TeamspeakInstancesNotAllowExceptions;
use \Illuminate\Http\Request;

class TokenVerifiTeamSpeakInstancesAllow
{
	/**
	 * @param $request
	 * @param Closure $next
	 *
	 * @return mixed
	 * @throws TeamspeakInstancesNotAllowExceptions
	 */
    public function handle(Request $request, Closure $next)
    {

        $server_id = (int)$request->server_id;
        $token = $request->header('X-token');

        $result = UserToken::with('servers')->where('token', '=', $token)->first();

        $AllowServers = $result->servers->toArray();


        if ($this->VerifiAllowServers($server_id, $AllowServers))
            return $next($request);

        throw new TeamspeakInstancesNotAllowExceptions($server_id);
    }

    function VerifiAllowServers(int $server_id, ?array $AllowServers): bool
    {
        if (empty($AllowServers))
            return false;

        for ($i = 0; $i < count($AllowServers); $i++)
            if ($server_id === $AllowServers[$i]['instance_id'])
                return true;

        return false;
    }

}