<?php
//no  cache headers 
header("Expires: Mon, 26 Jul 2012 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php
    require('config.php');
	//require_once('errhandler.php');
	//require_once('keepalive.php');
	require_once('classpagecommon.php');
	
	define('TPL_ADMINRPT_HEADER', 'tpladminreportsheader.php');
	define('TPL_ADMINRPT_LEFTNAV', 'tpladminreportsleftnav.php');
	define('TPL_ADMINRPT_FOOTER', 'tpladminreportsfooter.php');
	define('BASE_URI', 'http://10.211.55.21:8080');
	
	//load the constructor
	AdminReports::getObject();
	
	/**
     * Reporting class
     * Created by: Joseph Ian G. Farillas
     * Created date: 08-26-10
     * joseph@accordiasolution.com
     */
	class AdminReports {
		
		// these variables are for date range
		private $startStrTime = null;
		private $endStrTime = null;
		private $limitDays = 31; //31 days
		// end 
		// data variable holds passing information processed by the server to the browser
		public $data = null;
		
		public function __construct()
		{
			if ( isset($_POST['func'] ) ) {
				switch($_POST['func'])
				{
					case 'validateDateRange':
						return $this->validateDateRange($_POST['startDate'], $_POST['endDate']);
					break;
					default:
						return true;
					break;
				}
			} else {
				return true;
			}
					 
		}
		
		public function verifyLogin()
		{
			(!isset($_SESSION['WEB_ADMIN_SESSION'])) ? header('location:login.php') : '';
	
		}
		
		public function header()
		{
			require(TPL_ADMINRPT_HEADER);
		}
		
		public function leftNav()
		{
			require(TPL_ADMINRPT_LEFTNAV);
		}
		
		public function footer()
		{
			require(TPL_ADMINRPT_FOOTER);
		}
		
		public function validateDateRange($startDate, $endDate)
		{
			try
			{
				$this->startStrTime = strtotime($startDate);
				$this->endStrTime = strtotime($endDate);
				$mkLimitDays = strtotime("+$this->limitDays days", $this->startStrTime);
				//print $this->endStrTime.' - '.$mkLimitDays;
				//print $this->startStrTime.' - '.$this->endStrTime;
				// when the validation errors doesn't occur set to 1 otherwise load the error page.
				error_log($this->endStrTime);
				error_log($mkLimitDays);
				$this->data = ($this->endStrTime > $mkLimitDays) ? require(TPL_VALIDATE_ERR) : print 1;
			}
			catch(Exception $e)
			{
				error_log($e);
			}
		}
		
		/**
		 * calling external birt reports via birt viewer
		 * @param $uri string
		 * @return string
		 */
		public function getReports()
		{
			$ch = curl_init();
			$timeout = 10;
			$uri = 'http://localhost:8080/birt-viewer/frameset?__report=test.rptdesign&sample=my+parameter';
			curl_setopt($ch, CURLOPT_URL, $uri);
			//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		public static function getObject($object = null)
		{
		    $arr = func_get_args();
			$createObject = (!array_key_exists('0', $arr)) ? new AdminReports() : $object;
			return $createObject;
		}
	}
?>