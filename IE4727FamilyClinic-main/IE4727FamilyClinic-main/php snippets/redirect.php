<?php

/*not working well atm -> redirects back to current page that u clicked from*/
function redirectToPreviousPage() {
    // Redirect to the previous page, or homepage if no referrer
    $previousPage = $_SERVER['HTTP_REFERER'] ?? '../php/index.php';
    header("Location: $previousPage");
    exit();
}
?>