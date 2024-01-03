<?php
require __DIR__ . '/../vendor/autoload.php';

use Google\Client;
use Google\Service\Calendar;



class GoogleCalendarClient {

	private $calendar_account;
	private $time_zone;
	private $title;
	private $description;
	private $start_date;
	private $end_date;
	private $color;

	private $colorArray = array(
		1 => 'blue',
		2 => 'green',
		3 => 'purple',
		4 => 'red',
		5 => 'yellow',
		6 => 'orange',
		7 => 'turquoise',
		8 => 'gray',
		9 => 'bold blue',
		10 => 'bold green',
		11 => 'bold red'
	);


	public function __construct($calendar_account, $time_zone)
	{
		$this->calendar_account = $calendar_account;
		$this->time_zone = $time_zone;
	}
	private function authorizeServiceAccount()
	{
		try {
			$client = new Client();
			$client->setApplicationName('G_Calendar');
			$filename = __DIR__ .'/.gcreds/credentials.json';

			if (!file_exists($filename)) {
				throw new \Exception("The file $filename does not exist.");
			}

			$credentialsPath = realpath($filename);

			$client->setAuthConfig($credentialsPath);
			$client->setScopes([Calendar::CALENDAR, Calendar::CALENDAR_EVENTS]);

			$client->setAccessType('offline');
			$client->setSubject('mypersonalsa@test-calendar-405315.iam.gserviceaccount.com');
			$client->authorize();

			return $client;

		} catch (\Exception $e) {
			// Log or handle the exception appropriately
			// throw new \Exception("Error authorizing service account: " . $e->getMessage());

			$this->logToFile(print_r([$e->getMessage(), $config], true));
		}
	}



	private function getCalendar()
	{
		$client = $this->authorizeServiceAccount();
		if ($client != false) {
			return new Calendar($client);
		}
		return false;
	}


	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function setStartDate($start_date)
	{
		$this->start_date = $start_date;
	}

	public function setEndDate($end_date)
	{
		$this->end_date = $end_date;
	}

	public function setColor($color_id)
	{
		$this->color = $color_id;
	}

	public function getColors():array
	{
		return $this->colorArray;
	}

	public function addEventToGoogle()
	{
		try {

			$start_date = new DateTime($this->start_date);
			$end_date = new DateTime($this->end_date);

			$start_date = $start_date->format('Y-m-d\TH:i:s');
			$end_date = $end_date->format('Y-m-d\TH:i:s');


			$config = array(
				'summary' => $this->title,
				'description' => $this->description,
				'start' => array(
					'dateTime' => $start_date,
					'timeZone' => $this->time_zone,
				),
				'end' => array(
					'dateTime' => $end_date,
					'timeZone' => $this->time_zone,
				),
				'colorId' => $this->color,
			);


			$event = new Calendar\Event($config);

			$calendarService = $this->getCalendar();
			$nevent = $calendarService->events->insert($this->calendar_account, $event);

			if($nevent->htmlLink != "") {
				return $nevent->htmlLink;
			}

		} catch (\Google\Service\Exception $e) {
			$this->logToFile(print_r([$e->getMessage(), $config], true));
			return false;
		}
	}

	private function logToFile($message)
	{
		$logFile = __DIR__ . '/error_log.txt';
		$message = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
		file_put_contents($logFile, $message, FILE_APPEND);
	}

}

?>
