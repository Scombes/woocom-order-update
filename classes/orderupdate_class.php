<?php
/**
*
*Order Update CLASS
*
**/
Class orderupdate {

  public function __construct(){}

    //function to log messages
    private function logMessage($message)
    {
        $this->messageArray[] = $message;
    }

    //Function to show messages
    public function getMessages()
    {
        return $this->messageArray;
    }


  //Function to read XML File and save contents to $orders
  public function readXML($file){
    $orders = simplexml_load_file($file);
    return $orders;
  }

  public function processOrders($orders){

    //Loop through each order
    foreach ($orders->order as $order){

      //Get order number, tracking numbers, and carrier
      $ordernumber = intval($order->orderId);
      $carrier = 'UPS';
			foreach ($order->packages->package as $item){
                $trackingnumber = (string)$item->tracking;
              }
      //If $ordernumber is not blank, then run it through singleResult
			if(!empty($ordernumber)){
        $singleResult = $this -> updateOrders($ordernumber, $carrier, $trackingnumber);
        $this->logMessage($singleResult);
      }

    }
    return true;
  }//end processOrders function

  private function updateOrders($ordernumber, $carrier, $trackingnumber){

    //create new order object
		$orderUpdate = new WC_Order(strval($ordernumber));

    if($orderUpdate->status != 'completed'){

        //add tracking number and carrier info as meta data
				update_post_meta( $ordernumber, 'tracking_number', $trackingnumber);
				update_post_meta( $ordernumber, 'carrier', $carrier );
        $gettrackingnumber = get_post_meta($ordernumber, 'tracking_number' );

				//formulate shipping note
				$order_shipped_note = "Order has been shipped via UPS Ground and the tracking number is ".$gettrackingnumber[0].".";

				//update order status
				$orderUpdate->update_status( 'completed', 'Order Shipped'  );

				//add order note
				$orderUpdate->add_order_note($order_shipped_note, 0  );

        $resultmessage = "The order $ordernumber was updated. The tracking number is ".$gettrackingnumber[0].".";
    }
    return $resultmessage;
  }//end updateOrders function


}//End orderUpdate Class
?>
