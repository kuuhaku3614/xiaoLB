<?php
    require_once "membership.class.php";

    $productObj = new Product();
    $keyword = '';
    $expiry_filter = '';

    $array = $productObj->showAll(); // Initial fetch without filters

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['search'])) {
            // Search request
            $keyword = isset($_POST['keyword']) ? htmlentities($_POST['keyword']) : '';
            $expiry_filter = isset($_POST['expiry_filter']) ? htmlentities($_POST['expiry_filter']) : '';
            $array = $productObj->showAll($keyword, $expiry_filter);
        } elseif (isset($_POST['refresh'])) {
            // Refresh request - reset filters
            $keyword = '';
            $expiry_filter = '';
            $array = $productObj->showAll();
        }
    }

    // Determine row class for styling based on expiry date
    function getRowClass($expiryDate) {
        $currentDate = new DateTime();
        $expiryDate = new DateTime($expiryDate);
        $interval = $currentDate->diff($expiryDate);
        $daysUntilExpiry = $interval->days;

        if ($currentDate > $expiryDate) {
            return 'expired';
        } elseif ($daysUntilExpiry <= 7) {
            return 'expiring-soon';
        }
        return '';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JC Powerzone</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header">
        <h1>JC Powerzone Members List</h1>
    </div>

    <div class="form">
        <form action="" method="post">
            <label for="keyword">Search</label>
            <input type="text" name="keyword" id="keyword" value="<?= htmlspecialchars($keyword) ?>">
            <input type="submit" value="Search" name="search" id="search">
            <input type="submit" value="Refresh" name="refresh" id="refresh">
            <select name="expiry_filter" id="expiry_filter">
                <option value="" disabled selected>--Select--</option>
                <option value="expired" <?= $expiry_filter == 'expired' ? 'selected' : '' ?>>Expired</option>
                <option value="expiring" <?= $expiry_filter == 'expiring' ? 'selected' : '' ?>>Expiring Soon</option>
            </select>
        </form>

        <div class="table-container">
            <table class="table" border="1">
                <tr>
                    <th>No.</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Membership Date</th>
                    <th>Expiry Date</th>
                    <th>Mode of Payment</th>
                    <th>Payment Status</th>
                    <th>Promo</th>
                    <th>Phone</th>
                    <th colspan="2">Action</th>
                </tr>

                <?php
                if (empty($array)) {
                    // Display message if no members match the filter or if table is empty
                    echo '<tr><td colspan="11">No members found or table is empty.</td></tr>';
                } else {
                    $i = 1;
                    foreach ($array as $arr) {
                        $rowClass = getRowClass($arr['expiry_date']);
                ?>
                    <tr class="<?= $rowClass ?>">
                        <td><?= $i ?></td>
                        <td><?= htmlspecialchars($arr['first_name']) ?></td>
                        <td><?= htmlspecialchars($arr['last_name']) ?></td>
                        <td><?= htmlspecialchars($arr['membership_date']) ?></td>
                        <td><?= htmlspecialchars($arr['expiry_date']) ?></td>
                        <td><?= htmlspecialchars($arr['mode_payment']) ?></td>
                        <td><?= htmlspecialchars($arr['payment_status']) ?></td>
                        <td><?= htmlspecialchars($arr['promo']) ?></td>
                        <td><?= htmlspecialchars($arr['phoneNo']) ?></td>
                        <td class="action">
                            <a href="edit.php?id=<?= $arr['id'] ?>">Renew</a>
                            <a href="" class="deleteBtn" data-id="<?= $arr['id'] ?>" data-name="<?= $arr['first_name'] ?>">Remove</a>
                        </td>
                    </tr>
                <?php
                        $i++;
                    }
                }
                ?>
            </table>
        </div>

        <script src="delete.js"></script>
        <h1><a href="addMember.php">Add Member</a></h1>
    </div>
</body>
</html>