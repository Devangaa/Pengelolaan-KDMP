<?php

use CodeIgniter\HTTP\Response;

if (!function_exists('api_response')) {
    /**
     * Return consistent JSON API response for successful requests
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @return Response
     */
    function api_response(bool $success = true, string $message = '', $data = null, int $statusCode = 200): Response
    {
        $response = service('response');
        
        $payload = [
            'success' => $success,
            'message' => $message,
        ];

        if ($data !== null) {
            $payload['data'] = $data;
        }

        return $response
            ->setJSON($payload)
            ->setStatusCode($statusCode)
            ->setHeader('Content-Type', 'application/json');
    }
}

if (!function_exists('api_error')) {
    /**
     * Return error response with consistent format
     * @param string $message
     * @param int $statusCode
     * @param array $errors
     * @return Response
     */
    function api_error(string $message = 'An error occurred', int $statusCode = 500, array $errors = []): Response
    {
        $response = service('response');

        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $payload['errors'] = $errors;
        }

        return $response
            ->setJSON($payload)
            ->setStatusCode($statusCode)
            ->setHeader('Content-Type', 'application/json');
    }
}

if (!function_exists('api_validation_error')) {
    /**
     * Return validation error response
     * @param array $errors
     * @param string $message
     * @return Response
     */
    function api_validation_error(array $errors, string $message = 'Validation failed'): Response
    {
        return api_error($message, 422, $errors);
    }
}

if (!function_exists('api_unauthorized')) {
    /**
     * Return 401 Unauthorized response
     * @param string $message
     * @return Response
     */
    function api_unauthorized(string $message = 'Unauthorized'): Response
    {
        return api_error($message, 401);
    }
}

if (!function_exists('api_forbidden')) {
    /**
     * Return 403 Forbidden response
     * @param string $message
     * @return Response
     */
    function api_forbidden(string $message = 'Forbidden'): Response
    {
        return api_error($message, 403);
    }
}

if (!function_exists('api_not_found')) {
    /**
     * Return 404 Not Found response
     * @param string $message
     * @return Response
     */
    function api_not_found(string $message = 'Resource not found'): Response
    {
        return api_error($message, 404);
    }
}

if (!function_exists('api_conflict')) {
    /**
     * Return 409 Conflict response (resource already exists, shift active, etc)
     * @param string $message
     * @return Response
     */
    function api_conflict(string $message = 'Resource conflict'): Response
    {
        return api_error($message, 409);
    }
}

if (!function_exists('api_server_error')) {
    /**
     * Return 500 Internal Server Error response
     * @param string $message
     * @param bool $showDetail
     * @return Response
     */
    function api_server_error(string $message = 'Internal server error', bool $showDetail = false): Response
    {
        $msg = $message;

        // Only show detailed error in development
        if ($showDetail && ENVIRONMENT !== 'production') {
            $msg = $message;
        } else {
            $msg = 'Terjadi kesalahan pada server. Silakan hubungi administrator.';
        }

        return api_error($msg, 500);
    }
}

if (!function_exists('html_error_response')) {
    /**
     * Return HTML error response with redirect
     * @param string $message
     * @param string $redirectUrl
     * @param string $type (error, success, warning, info)
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    function html_error_response(string $message, string $redirectUrl, string $type = 'error')
    {
        return redirect()->to($redirectUrl)->with($type, $message);
    }
}

if (!function_exists('html_success_response')) {
    /**
     * Return HTML success response with redirect
     * @param string $message
     * @param string $redirectUrl
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    function html_success_response(string $message, string $redirectUrl)
    {
        return html_error_response($message, $redirectUrl, 'success');
    }
}
