<?php namespace App\Http\Controllers\Home;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * 父控制类类
 *
 * @author jiang <mylampblog@163.com>
 */
abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

}
