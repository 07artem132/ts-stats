<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 11.08.2018
 * Time: 8:17
 */

namespace App\Traits;

use App\Instance;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Collection;

Trait InstanceListTrait {

	/**
	 * @return array
	 */
	private function GetInstanceList(): Collection {
		if ( $this->IsCacheInstanceList() ) {
			return $this->GetInstanceListFromCache();
		} else {
			$response = $this->GetInstanceListFromDB();
			$this->SetCacheInstanceList($response);
			return $response;
		}
	}

	/**
	 * @return bool
	 */
	private function IsCacheInstanceList(): bool {
		$redis = Redis::connection();

		$len = $redis->command( 'LLEN', [
			config( 'cache.prefix' ) . ':InstanceList'
		] );

		return $len === 0 ? false : true;
	}

	/**
	 * @param $instanceList
	 */
	private function SetCacheInstanceList( Collection $instanceList ): void {
		$redis = Redis::connection();

		foreach ( $instanceList as $key => $value ) {
			$redis->command( 'hset', [
				config( 'cache.prefix' ) . ':InstanceList',
				$key,
				serialize($value)
			] );
		}

	}

	/**
	 * @return array
	 */
	private function GetInstanceListFromCache(): Collection {
		$redis = Redis::connection();

		$result = $redis->command( 'HGETALL', [
			config( 'cache.prefix' ) . ':InstanceList'
		] );

		for ( $i = 1; $i <= count( $result ); $i ++ ) {
			$result[ $i ] = unserialize( $result[ $i ] );
		}

		$result = collect($result);

		return $result->sortKeys();
	}

	/**
	 * @return array
	 */
	private function GetInstanceListFromDB(): Collection {
		return collect(Instance::select( [ 'id', 'ipaddress' ] )->orderBy( 'id' )->get()->getDictionary());
	}
}