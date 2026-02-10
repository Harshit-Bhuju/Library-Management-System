<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

setFlash('error', 'Payment failed or cancelled.');
redirect('student/borrow_history.php');
