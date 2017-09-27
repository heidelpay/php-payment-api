<?php
/**
 * Error page
 *
 * @license Use of this software requires acceptance of the License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
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
<?php echo htmlentities($_GET['errorMessage'], ENT_HTML5)?>
</div>
</body>
</html>