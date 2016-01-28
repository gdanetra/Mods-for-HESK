<?php
define('IN_SCRIPT', 1);
define('HESK_PATH', '../../../');
define('INTERNAL_API_PATH', '../../');
require_once(HESK_PATH . 'hesk_settings.inc.php');
require_once(HESK_PATH . 'inc/common.inc.php');
require_once(HESK_PATH . 'inc/attachments.inc.php');
require_once(HESK_PATH . 'inc/posting_functions.inc.php');
require_once(INTERNAL_API_PATH . 'core/output.php');
require_once(INTERNAL_API_PATH . 'dao/calendar_dao.php');

hesk_load_internal_api_database_functions();
hesk_dbConnect();

$modsForHesk_settings = mfh_getSettings();

// Routing
$request_method = $_SERVER['REQUEST_METHOD'];
if ($request_method === 'GET') {
    $start = hesk_GET('start');
    $end = hesk_GET('end');
    $events = get_events($start, $end, $hesk_settings);

    return output($events);
} elseif ($request_method === 'POST') {
    $action = hesk_POST('action');

    if ($action === 'create') {
        $event['title'] = hesk_POST('title');
        $event['location'] = hesk_POST('location');
        $event['start'] = hesk_POST('startTime');
        $event['end'] = hesk_POST('endTime');
        $event['all_day'] = hesk_POST('allDay') === 'true';
        $event['comments'] = hesk_POST('comments');

        $id = create_event($event, $hesk_settings);

        return output($id);
    } elseif ($action === 'update') {
        if (!isset($_POST['id'])) {
            mfh_log_error('internal-api/admin/calendar', 'Unable to update an event as it has no ID.', $_SESSION['id']);
            return http_response_code(400);
        }
        $event['id'] = hesk_POST('id');
        $event['title'] = hesk_POST('title');
        $event['start'] = hesk_POST('startTime');
        $event['end'] = hesk_POST('endTime');
        $event['all_day'] = hesk_POST('allDay') === 'true';
        $event['comments'] = hesk_POST('comments');

        update_event($event, $hesk_settings);

        return http_response_code(200);
    } elseif ($action === 'delete') {
        $id = hesk_POST('id');

        delete_event($id, $hesk_settings);
        return http_response_code(200);
    } elseif ($action === 'update-ticket') {
        $ticket['due_date'] = hesk_POST('dueDate');
        $ticket['trackid'] = hesk_POST('trackingId');

        update_ticket_due_date($ticket, $hesk_settings);

        return http_response_code(200);
    }
}

return http_response_code(400);