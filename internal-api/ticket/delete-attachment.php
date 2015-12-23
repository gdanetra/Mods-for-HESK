<?php
define('IN_SCRIPT', 1);
define('HESK_PATH', '../../');
define('INTERNAL_API_PATH', '../');
require_once(HESK_PATH . 'hesk_settings.inc.php');
require_once(HESK_PATH . 'inc/common.inc.php');
require_once(HESK_PATH . 'inc/attachments.inc.php');
require_once(HESK_PATH . 'inc/posting_functions.inc.php');
require_once(INTERNAL_API_PATH . 'core/output.php');
require_once(INTERNAL_API_PATH . 'dao/attachment_dao.php');

hesk_load_internal_api_database_functions();
hesk_dbConnect();

$modsForHesk_settings = mfh_getSettings();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $id = delete_temp_attachment($id, true);
    return http_response_code(200);
}

return http_response_code(400);