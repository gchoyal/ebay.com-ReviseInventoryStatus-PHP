<?php 

$productionurl = "https://api.ebay.com/ws/api.dll";

$sandboxurl = "https://api.sandbox.ebay.com/ws/api.dll";

$clvl = "981";

$devid = "200e95fe-XXXX-XXXX-XXXX-XXXXXXXXXXXX";

$appid = "Girdhari-XXXXXXX-PRD-XXXXXXXXX-XXXXXXXX";

$certid = "PRD-8deaec30156f-XXXX-XXXX-XXXX-XXXX";

$siteid = 3; //UK

$retailerToken = 'AgAAAA**AQAAAA**XXXXXXXXX**XXXX**nY+XXXXXXXXX+XXXXXX........';

//You can replace with your data 
$items = array(
			
			array(
				'id' => '65456465465', //ItemID of product on ebay, For variation product use parent product item ID
				'qty' => 10, //Qty which you want to update to ebay for above Item ID
				'sku' => 'XXXXXXX' //SKU of product, For variation of product you can place here SKU 
			),
			array(
				'id' => '666656556',
				'qty' => 20,
				'sku' => 'YYYYYY'
			)
			
		);	
		
function reviseBulkInventoryItems($items, $retailerToken) { //http://developer.ebay.com/DevZone/XML/docs/Reference/eBay/ReviseInventoryStatus.html
			
    $xml = '<?xml version="1.0" encoding="utf-8"?>
			<ReviseInventoryStatusRequest xmlns="urn:ebay:apis:eBLBaseComponents">
				<RequesterCredentials>
					<eBayAuthToken>' . $retailerToken . '</eBayAuthToken>
				</RequesterCredentials>
				<Version>981</Version>';
				
				foreach( $items as $item ){
					
					$xml .= '<InventoryStatus>
								<ItemID>'.$item['id'].'</ItemID>
								<Quantity>'.$item['qty'].'</Quantity>
								<SKU>'.$item['sku'].'</SKU>
							</InventoryStatus>';
					
				}
				
			$xml .= '</ReviseInventoryStatusRequest>';

    $reults = sendtoebay($xml, "ReviseInventoryStatus");
    return $reults;
}

function sendtoebay($postData, $callName) {
    global $clvl, $devid, $appid, $certid, $siteid, $productionurl;

    $header = array(
        "X-EBAY-API-COMPATIBILITY-LEVEL: $clvl",
        "X-EBAY-API-DEV-NAME: $devid",
        "X-EBAY-API-APP-NAME: $appid",
        "X-EBAY-API-CERT-NAME: $certid",
        "X-EBAY-API-SITEID: $siteid",
        "X-EBAY-API-CALL-NAME: " . $callName
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $productionurl);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $results = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    return $results;
}

/*** Now RUN API ****/

reviseBulkInventoryItems($items, $retailerToken);	//Run API

?>