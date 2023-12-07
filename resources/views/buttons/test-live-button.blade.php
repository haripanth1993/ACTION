<?php
	if (isset($_GET['id']))
	{
		$id = $_GET['id'];
	}
	else
	{
		$id = "SPK-SEVC";
	}
	$data = array(
		'widgetId' => $id
	);
	$curl = curl_init();
	$payload = json_encode($data);
	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.actionbutton.co/api/Widget/GetResultsAsync',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json-patch+json',
			'accept: text/plain',
			'Origin: https://actionbutton.voxara.net',
		) ,
		CURLOPT_POSTFIELDS => $payload,
	));

	$response = curl_exec($curl);

	curl_close($curl);
	$response = json_decode($response);

	echo "<pre>";
	echo json_encode($response, JSON_PRETTY_PRINT);
	echo "</pre>";

?>
