<?php
require_once "membership.class.php";
require_once "function.php";

$productObj = new Product();

$first_name = $last_name = $membership_date = $payment_status = $mode = $promo = $expiry_date = $phoneNo = '';
$first_nameErr = $last_nameErr = $membership_dateErr = $payment_statusErr = $modeErr = $promoErr = $expiry_dateErr = $phoneNoErr = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $membership_date = date('Y-m-d'); // Set current date automatically
    $payment_status = clean_input($_POST['payment_status']);
    $mode = clean_input($_POST['mode']);
    $promo = clean_input($_POST['promo']);
    $expiry_date = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : ''; // Check if expiry_date is set
    $phoneNo = clean_input($_POST['phoneNo']);

    if (empty($first_name)) {
        $first_nameErr = ' Required';
    }
    if (empty($last_name)) {
        $last_nameErr = ' Required';
    }
    if (empty($membership_date)) {
        $membership_dateErr = ' Required';
    } else {
        $currentDate = date('Y-m-d');
        if ($membership_date < $currentDate) {
            $membership_dateErr = ' The date must be today';
        }
    }
    if (empty($expiry_date) && !empty($promo)) {
        // Calculate expiry date based on promo
        if ($promo === "student") {
            $expiry_date = date('Y-m-d', strtotime('+1 month', strtotime($membership_date)));
        } elseif ($promo === "3months") {
            $expiry_date = date('Y-m-d', strtotime('+3 months', strtotime($membership_date)));
        } else {
            // Handle invalid promo code or other scenarios
            $expiry_dateErr = " Invalid promo code";
        }
    }
    if (empty($payment_status)) {
        $payment_statusErr = ' Required';
    }
    if (empty($mode)) {
        $modeErr = ' Required';
    }
    if (empty($promo)) {
        $promoErr = ' Required';
    }
    if (empty($phoneNo)) {
        $phoneNoErr = 'Required';
    }elseif(!is_numeric(($phoneNo))){
        $phoneNoErr = ' Must be a number';
    }elseif(countDigit($phoneNo) > 11 ){
        $phoneNoErr = ' Must not be great than 11 digits';
    }

    if (empty($first_nameErr) && empty($last_nameErr) && empty($membership_dateErr) && empty($payment_statusErr) && empty($modeErr) && empty($promoErr) && empty($expiry_dateErr) && empty($phoneNoErr)) {
        $productObj->first_name = $first_name;
        $productObj->last_name = $last_name;
        $productObj->membership_date = $membership_date;
        $productObj->payment_status = $payment_status;
        $productObj->mode_payment = $mode;
        $productObj->promo = $promo;
        $productObj->phoneNo = $phoneNo;
        $productObj->expiry_date = $expiry_date;

        if ($productObj->add()) {
            header('location: membership.php');
        } else {
            echo 'Something is Wrong';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member</title>
    <link rel="stylesheet" href="form.css">
</head>
<body>
<div class="form">
        <form action="" method="post">
            <label for="first_name">First Name</label><span class="error"><?= $first_nameErr ?></span>
            <br>
            <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($first_name) ?>">
            <br>
            <label for="last_name">Last Name</label><span class="error"><?= $last_nameErr ?></span>
            <br>
            <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($last_name) ?>">
            <br>
            <label for="membership_date">Membership Date</label><span class="error"><?= $membership_dateErr ?></span>
            <br>
            <input type="date" name="membership_date" id="membership_date" value="<?= date('Y-m-d') ?>">
            <br>
            <label for="payment_status">Payment Status</label><span class="error"><?= $payment_statusErr ?></span>
            <br>
            <input type="radio" name="payment_status" id="payment_status" value="paid" checked>
            <label for="paid">Paid</label>
            <br>
            <input type="radio" name="payment_status" id="payment_status_unpaid" value="unpaid">
            <label for="unpaid">Unpaid</label>
            <br>
            <label for="mode">Mode of Payment</label><span class="error"><?= $modeErr ?></span>
            <br>
            <input type="radio" name="mode" id="E-Wallet" value="E-Wallet" checked>
            <label for="E-Wallet">E-Wallet</label>
            <br>
            <input type="radio" name="mode" id="Cash" value="Cash" checked>
            <label for="Cash">Cash</label>
            <br>
            <label for="promo">Promo Code</label><span class="error"><?= $promoErr ?></span>
            <br>
            <select name="promo" id="promo" onchange="updateExpiryDate(this.value)">
                <option value="">Select Promo</option>
                <option value="student">(400/month)</option>
                <option value="3months">(1000/3months)</option>
            </select>
            <br>
            <label for="expiry_date">Expiry Date</label>
            <input type="date" name="expiry_date" id="expiry_date" value="<?= htmlspecialchars($expiry_date) ?>">
            <br>
            <label for="">Phone Number</label><span class="error"><?= $phoneNoErr ?></span>
            <br>
            <input type="tel" name="phoneNo" id="phoneNo" value="<?= $phoneNo ?>">
            <br>
            <div class="buttons">
                <button type="button"><a href="membership.php">Return</a></button>
                <input type="submit" value="Add Member">    
            </div>
        </form>
    </div>
    <script src="promo.js">
        
    </script>
</body>
</html>