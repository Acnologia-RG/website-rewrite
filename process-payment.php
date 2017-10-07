<?php
/**
 * Process Payment Information - process-payment.php
 */
# TODO: REPLACE ALL BELOW VALUES/VARIABLES AND RUN TESTS ON REQUEST/RESPONSE #
/**
 * PayPal PHP API Libraries
 */
require __DIR__ . '/bootstrap.php';

/**
 * PayPal Classes/Namespaces
 */
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentCard;
use PayPal\Api\Transaction;

/**
 * Set / Filter Submitted Variables
 */
$firstName          = filter_var( $_POST['firstName'], FILTER_SANITIZE_STRING );
$lastName           = filter_var( $_POST['lastName'], FILTER_SANITIZE_STRING );
$email              = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL );
$cardType           = filter_var( $_POST['cardType'], FILTER_SANITIZE_STRING );
$creditCardNumber   = filter_var( $_POST['creditCardNumber'], FILTER_SANITIZE_NUMBER_INT );
$expDateMonth       = filter_var( $_POST['expDateMonth'], FILTER_SANITIZE_NUMBER_INT );
$expDateYear        = filter_var( $_POST['expDateYear'], FILTER_SANITIZE_NUMBER_INT );
$cvvNumber          = filter_var( $_POST['cvvNumber'], FILTER_SANITIZE_NUMBER_INT );
$address1           = filter_var( $_POST['address1'], FILTER_SANITIZE_STRING );
$address2           = filter_var( $_POST['address2'], FILTER_SANITIZE_STRING );
$city               = filter_var( $_POST['city'], FILTER_SANITIZE_STRING );
$state              = filter_var( $_POST['state'], FILTER_SANITIZE_STRING ); // 2 letter
$zip                = filter_var( $_POST['zip'], FILTER_SANITIZE_STRING );
$countryCode        = filter_var( $_POST['countryCode'], FILTER_SANITIZE_STRING ); // 2 letter
$phone              = filter_var( $_POST['phone'], FILTER_SANITIZE_NUMBER_INT );

/**
 * Redirect to Store if no Product is Selected
 */
if ( empty( $_POST['product'] > 0 ) ) {
    header( "Location: $url/shop.php", 302 );
    exit();
}
/**
 * Define Product Details
 */
else {
    $product = $productsArray[ $_POST['product'] ];
}

/**
 * Define Credit Card Information
 */
$card = new PaymentCard();
$card->setType($cardType)
     ->setNumber($creditCardNumber)
     ->setExpireMonth($expDateMonth)
     ->setExpireYear($expDateYear)
     ->setCvv2($cvvNumber)
     ->setFirstName($firstName)
     ->setBillingCountry($countryCode)
     ->setLastName("$lastName");

/**
 * Define Funding Instrument
 */
$fi = new FundingInstrument();
$fi->setPaymentCard($card);

/**
 * Define Payer Information
 */
$payer = new Payer();
$payer->setPaymentMethod("credit_card")
      ->setFundingInstruments(array($fi));

/**
 * Define Item(s) in Transaction
 */
$item = new Item();
$item->setName($product['name'])
     ->setDescription($product['description'])
     ->setCurrency($currencyCode)
     ->setQuantity(1)
     ->setTax($product['price']*$tax)
     ->setPrice($product['price']);
$itemList = new ItemList();
$itemList->setItems(array($item));

/**
 * Define Tax and Subtotal
 */
$details = new Details();
$details->setTax($product['price']*$tax)
        ->setShipping($shipping)
        ->setSubtotal(($product['price']));

/**
 * Define Currency/Totals/Details
 */
$amount = new Amount();
$amount->setCurrency($currencyCode)
       ->setTotal($product['price']+($product['price']*$tax))
       ->setDetails($details);

/**
 * Define Transaction
 */
$transaction = new Transaction();
$transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($paymentDescription)
            ->setInvoiceNumber(uniqid());

/**
 * Define Payment
 */
$payment = new Payment();
$payment->setIntent($paymentType)
        ->setPayer($payer)
        ->setTransactions(array($transaction));

/**
 * Develop Request
 */
$request = clone $payment;

/**
 * Process Payment
 */
try {
    $payment->create($apiContext);
} catch (Exception $ex) {
    var_dump(array('Create Payment Using Credit Card. If 500 Exception, try creating a new Credit Card using <a href="https://www.paypal-knowledge.com/infocenter/index?page=content&widgetview=true&id=FAQ1413">Step 4, on this link</a>, and using it.', 'Payment', null, $request, $ex));
    exit(1);
}
var_dump(array('Create Payment Using Credit Card', 'Payment', $payment->getId(), $request, $payment));
return $payment;
