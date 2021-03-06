<?php
/**
 * 事件控制器
 */
namespace App\Http\Controllers\Api\V1;

use Auth;
use Validator;
use API;
use DB;	

use App\User;
use App\Checkin;


use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;


class TopController extends BaseController {


	public function users()
	{

		// $users = DB::table('checkin')
		// 	->select(DB::raw('count(1) as count,user_id'))
		// 	->where(DB::raw('YEAR(checkin_day)'),'2016')
		// 	->where(DB::raw('MONTH(checkin_day)'),'03')
		// 	->orderBy('count','desc')
		// 	->groupBy('user_id')
		// 	->take(10)
		// 	->get();
		// // $users = User::all()->where->take(10);
		// 	$users1 = array();
		// foreach($users as $k=>$user) {
		// 	var_dump($user);	
		// 	$users1[$k] = User::find($user->user_id)->first();
		// }

		// $users = Checkin::getQuery()
		// 	->select(DB::raw('count(1) as count,user_id'))
		// 	->groupBy('user_id')
		// 	->orderBy('count','DESC')
		// 	->take(10)
		// 	->get();
		$user_id  = $this->auth->user()->user_id;

		$users = User::join("checkin",'checkin.user_id','=','users.user_id')
				->select('users.*',DB::raw('count(1) as count'))
				->where(DB::raw('YEAR(checkin_day)'),date('Y'))
				->where(DB::raw('MONTH(checkin_day)'),date('m'))
				->groupBy('users.user_id')
				->orderBy('count','DESC')
				->take(10)
				->get();

		// 查询当前用户本月打卡的次数
		$count = DB::table('checkin')
					->where('user_id',$user_id)
					->where(DB::raw('YEAR(checkin_day)'),'=',date('Y'))
					->where(DB::raw('MONTH(checkin_day)'),'=',date('m'))
					->groupBy('user_id')
					->count();

		$rank_users = DB::table('checkin')
					->select(DB::raw('count(*) as count'))
					->where(DB::raw('YEAR(checkin_day)'),'=',date('Y'))
					->where(DB::raw('MONTH(checkin_day)'),'=',date('m'))
					->groupBy('user_id')
					->having('count', '>', $count)
					->get();

		$rank = count($rank_users)+1;

		// User::find(8)->checkins;

		$month = date('m');

		return compact('month','users','count','rank');
	}
}