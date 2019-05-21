<?php
/**
 * Error page
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-payment-api/
 *
 * @author  Jens Richter
 */
?>
<html>
<head>
<title>Error page</title>
</head>
<body>
<div style="color: red">
<?php
    $message = isset($_GET['errorMessage']) ? $_GET['errorMessage'] : '';
    $shortId = isset($_GET['shortId']) ? $_GET['shortId'] : '';

    echo htmlentities($message);
    if (!empty($shortId)) {
        echo '<br> Please refer to shortId ' . htmlentities($shortId) . ' in hIP.';
    }
?>
</div>
</body>
</html>