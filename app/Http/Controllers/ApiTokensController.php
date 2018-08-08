<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 31.07.2018
 * Time: 8:41
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Token;
use \Auth;

class ApiTokensController extends Controller {
	public function __construct() {
		$this->middleware( 'auth' );
	}

	function list() {
		$ApiTokens = Token::UserID( Auth::id() )->get();

		return view( 'TokenList', [ 'ApiTokens' => $ApiTokens ] );
	}

	function add() {
		return view( 'addToken' );

	}
}