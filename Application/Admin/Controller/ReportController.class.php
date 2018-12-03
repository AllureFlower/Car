<?php 

namespace Admin\Controller;

class ReportController extends CommonController {
	
	private function getmonth($index)
	{
		$months =  [
			0 => strtotime('2017-01-01 00:00:00'),
			1 => strtotime('2017-02-01 00:00:00'),
			2 => strtotime('2017-03-01 00:00:00'),
			3 => strtotime('2017-04-01 00:00:00'),
			4 => strtotime('2017-05-01 00:00:00'),
			5 => strtotime('2017-06-01 00:00:00'),
			6 => strtotime('2017-07-01 00:00:00'),
			7 => strtotime('2017-08-01 00:00:00'),
			8 => strtotime('2017-09-01 00:00:00'),
			9 => strtotime('2017-10-01 00:00:00'),
			10 => strtotime('2017-11-01 00:00:00'),
			11 => strtotime('2017-12-01 00:00:00'),
			12 => strtotime('2018-01-01 00:00:00'),
		];
		return $months[$index];
	}
	 
	public function index() {
		$u_count = $this->month();
		$t_count = $this->trad();
		$r_count = $this->release();
		$f_count = $this->feedback();
		
		$this->assign('u_count', $u_count);
		$this->assign('t_count', $t_count);
		$this->assign('r_count', $r_count);
		$this->assign('f_count', $f_count);
		
		$this->display();
	}
	
	private function month()
	{
		$u_count[0] = 	M('user')->where(['time' => ['between', "{$this->getmonth(0)}, {$this->getmonth(1)}"]])->count();
		$u_count[1] = 	M('user')->where(['time' => ['between', "{$this->getmonth(1)}, {$this->getmonth(2)}"]])->count();
		$u_count[2] = 	M('user')->where(['time' => ['between', "{$this->getmonth(2)}, {$this->getmonth(3)}"]])->count();
		$u_count[3] = 	M('user')->where(['time' => ['between', "{$this->getmonth(3)}, {$this->getmonth(4)}"]])->count();
		$u_count[4] = 	M('user')->where(['time' => ['between', "{$this->getmonth(4)}, {$this->getmonth(5)}"]])->count();
		$u_count[5] = 	M('user')->where(['time' => ['between', "{$this->getmonth(5)}, {$this->getmonth(6)}"]])->count();
		$u_count[6] = 	M('user')->where(['time' => ['between', "{$this->getmonth(6)}, {$this->getmonth(7)}"]])->count();
		$u_count[7] = 	M('user')->where(['time' => ['between', "{$this->getmonth(7)}, {$this->getmonth(8)}"]])->count();
		$u_count[8] = 	M('user')->where(['time' => ['between', "{$this->getmonth(8)}, {$this->getmonth(9)}"]])->count();
		$u_count[9] = 	M('user')->where(['time' => ['between', "{$this->getmonth(9)}, {$this->getmonth(10)}"]])->count();
		$u_count[10] = 	M('user')->where(['time' => ['between', "{$this->getmonth(10)}, {$this->getmonth(11)}"]])->count();
		$u_count[11] = 	M('user')->where(['time' => ['between', "{$this->getmonth(11)}, {$this->getmonth(12)}"]])->count();
		return jsArray($u_count);
	}	
	
	private function trad()
	{
		$t_count[0] = 	M('trad')->where(['time' => ['between', "{$this->getmonth(0)}, {$this->getmonth(1)}"]])->count();
		$t_count[1] = 	M('trad')->where(['time' => ['between', "{$this->getmonth(1)}, {$this->getmonth(2)}"]])->count();
		$t_count[2] = 	M('trad')->where(['time' => ['between', "{$this->getmonth(2)}, {$this->getmonth(3)}"]])->count();
		$t_count[3] = 	M('trad')->where(['time' => ['between', "{$this->getmonth(3)}, {$this->getmonth(4)}"]])->count();
		$t_count[4] = 	M('trad')->where(['time' => ['between', "{$this->getmonth(4)}, {$this->getmonth(5)}"]])->count();
		$t_count[5] = 	M('trad')->where(['time' => ['between', "{$this->getmonth(5)}, {$this->getmonth(6)}"]])->count();
		$t_count[6] = 	M('trad')->where(['time' => ['between', "{$this->getmonth(6)}, {$this->getmonth(7)}"]])->count();
		$t_count[7] = 	M('trad')->where(['time' => ['between', "{$this->getmonth(7)}, {$this->getmonth(8)}"]])->count();
		$t_count[8] = 	M('trad')->where(['time' => ['between', "{$this->getmonth(8)}, {$this->getmonth(9)}"]])->count();
		$t_count[9] = 	M('trad')->where(['time' => ['between', "{$this->getmonth(9)}, {$this->getmonth(10)}"]])->count();
		$t_count[10] = 	M('trad')->where(['time' => ['between', "{$this->getmonth(10)}, {$this->getmonth(11)}"]])->count();
		$t_count[11] = 	M('trad')->where(['time' => ['between', "{$this->getmonth(11)}, {$this->getmonth(12)}"]])->count();
		return jsArray($t_count);
	}
	
	private function release()
	{
		$r_count[0] = 	M('release')->where(['time' => ['between', "{$this->getmonth(0)}, {$this->getmonth(1)}"]])->count();
		$r_count[1] = 	M('release')->where(['time' => ['between', "{$this->getmonth(1)}, {$this->getmonth(2)}"]])->count();
		$r_count[2] = 	M('release')->where(['time' => ['between', "{$this->getmonth(2)}, {$this->getmonth(3)}"]])->count();
		$r_count[3] = 	M('release')->where(['time' => ['between', "{$this->getmonth(3)}, {$this->getmonth(4)}"]])->count();
		$r_count[4] = 	M('release')->where(['time' => ['between', "{$this->getmonth(4)}, {$this->getmonth(5)}"]])->count();
		$r_count[5] = 	M('release')->where(['time' => ['between', "{$this->getmonth(5)}, {$this->getmonth(6)}"]])->count();
		$r_count[6] = 	M('release')->where(['time' => ['between', "{$this->getmonth(6)}, {$this->getmonth(7)}"]])->count();
		$r_count[7] = 	M('release')->where(['time' => ['between', "{$this->getmonth(7)}, {$this->getmonth(8)}"]])->count();
		$r_count[8] = 	M('release')->where(['time' => ['between', "{$this->getmonth(8)}, {$this->getmonth(9)}"]])->count();
		$r_count[9] = 	M('release')->where(['time' => ['between', "{$this->getmonth(9)}, {$this->getmonth(10)}"]])->count();
		$r_count[10] = 	M('release')->where(['time' => ['between', "{$this->getmonth(10)}, {$this->getmonth(11)}"]])->count();
		$r_count[11] = 	M('release')->where(['time' => ['between', "{$this->getmonth(11)}, {$this->getmonth(12)}"]])->count();
		return jsArray($r_count);
	}
	
	private function feedback()
	{
		$f_count[0] = 	M('feedback')->where(['time' => ['between', "{$this->getmonth(0)}, {$this->getmonth(1)}"]])->count();
		$f_count[1] = 	M('feedback')->where(['time' => ['between', "{$this->getmonth(1)}, {$this->getmonth(2)}"]])->count();
		$f_count[2] = 	M('feedback')->where(['time' => ['between', "{$this->getmonth(2)}, {$this->getmonth(3)}"]])->count();
		$f_count[3] = 	M('feedback')->where(['time' => ['between', "{$this->getmonth(3)}, {$this->getmonth(4)}"]])->count();
		$f_count[4] = 	M('feedback')->where(['time' => ['between', "{$this->getmonth(4)}, {$this->getmonth(5)}"]])->count();
		$f_count[5] = 	M('feedback')->where(['time' => ['between', "{$this->getmonth(5)}, {$this->getmonth(6)}"]])->count();
		$f_count[6] = 	M('feedback')->where(['time' => ['between', "{$this->getmonth(6)}, {$this->getmonth(7)}"]])->count();
		$f_count[7] = 	M('feedback')->where(['time' => ['between', "{$this->getmonth(7)}, {$this->getmonth(8)}"]])->count();
		$f_count[8] = 	M('feedback')->where(['time' => ['between', "{$this->getmonth(8)}, {$this->getmonth(9)}"]])->count();
		$f_count[9] = 	M('feedback')->where(['time' => ['between', "{$this->getmonth(9)}, {$this->getmonth(10)}"]])->count();
		$f_count[10] = 	M('feedback')->where(['time' => ['between', "{$this->getmonth(10)}, {$this->getmonth(11)}"]])->count();
		$f_count[11] = 	M('feedback')->where(['time' => ['between', "{$this->getmonth(11)}, {$this->getmonth(12)}"]])->count();
		return jsArray($f_count);
	}
	
}