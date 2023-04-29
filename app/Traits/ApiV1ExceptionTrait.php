<?php

namespace App\Traits;

use App\Exceptions\InvalidFileTypeException;
use App\Exceptions\UnAuthorizedRequestException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

trait ApiV1ExceptionTrait
{
    use CustomResponseTrait;

    public function apiException($request, $e)
    {
        //dd(class_basename($e));
        // dd($e->getMessage());

        if ($e->getMessage() == '403 Forbidden') {
            $msg = $e->getMessage();
            Log::alert($msg);
            $message = 'You do not have permissions to access this api.';
            return $this->sendResponse(Response::HTTP_FORBIDDEN, [], $message, [$msg]);
        }

        if ($e instanceof ModelNotFoundException) {
            $msg = $e->getMessage();
            Log::alert($msg);
            $message = 'Record not found.';
            return $this->sendResponse(Response::HTTP_NOT_FOUND, [], $message, [$msg]);
        }

        if ($e instanceof QueryException) {
            $msg = $e->getMessage();
            Log::critical($msg);
            $message = 'Internal Server Error.';
            return $this->sendResponse(Response::HTTP_INTERNAL_SERVER_ERROR, [], $message, [$msg], 500);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            $msg = $e->getMessage();
            Log::critical($msg);
            $message = 'HTTP Method Not Allowed.';
            return $this->sendResponse(Response::HTTP_METHOD_NOT_ALLOWED, [], $message, [$msg], 405);
        }

        if ($e instanceof NotFoundHttpException) {
            $msg = $e->getMessage();
            Log::alert($msg);
            $message = 'Not found';
            return $this->sendResponse(Response::HTTP_NOT_FOUND, [], $message, [$message]);
        }

        if ($e instanceof AccessDeniedHttpException) {
            $msg = $e->getMessage();
            Log::alert($msg);
            $message = 'Access denied.';
            return $this->sendResponse(Response::HTTP_NON_AUTHORITATIVE_INFORMATION, [], $message, [$msg]);
        }

        if ($e instanceof AuthorizationException || $e->getMessage() == '203 Access denied') {
            $msg = $e->getMessage();
            Log::alert($msg);
            $message = 'Unauthorized Request.';
            return $this->sendResponse(Response::HTTP_NON_AUTHORITATIVE_INFORMATION, [], $message, [$msg]);
        }

        if ($e instanceof AuthenticationException) {
            $msg = $e->getMessage();
            Log::alert($msg);
            $message = 'Unauthenticated Request.';
            return $this->sendResponse(Response::HTTP_NON_AUTHORITATIVE_INFORMATION, [], $message, [$msg]);
        }

        if ($e instanceof InvalidFileTypeException) {
            Log::critical($e->getMessage());
            return $this->sendResponse(Response::HTTP_UNPROCESSABLE_ENTITY, [], $e->getMessage(), [$e->getMessage()], ['files' => $e->getMessage()]);
        }

        if ($e instanceof ValidationException) {
            Log::critical($e->getMessage());
            Log::critical($e->errors());

            // $errors    = $e->validator->errors()->getMessages();
            //OR
            $allErrors = $e->errors();

            return $this->sendResponse(422, [], array_values($allErrors)[0][0], $e->validator->getMessageBag()->all(), $allErrors, 422);
        }

        if ($e instanceof UnAuthorizedRequestException) {
            Log::critical($e->getMessage());
            return $this->sendResponse(401, [], null, ['Unauthorized request.'], 401);
        }

        if ($e instanceof UnauthorizedHttpException) {
            Log::critical($e->getMessage());
            $message = 'Unauthorized http exception';
            return $this->sendResponse(Response::HTTP_UNAUTHORIZED, [], $message, [$message]);
        }

        if ($e instanceof \ErrorException) {
            Log::critical($e->getMessage());
            return $this->sendResponse(500, [], null, [$e->getMessage()], 500);
        }

        if ($e instanceof ClientException) {
            $message = $e->getResponse()->getBody();
            $code = $e->getCode();

            return response($message, $code)->header('Content-Type', 'application/json');
        }

        if ($e instanceof RuntimeException) {
            $msg = $e->getMessage();
            Log::critical($msg);
            $message = 'Unkown issue, contact system administrator or check logs.';
            return $this->sendResponse(Response::HTTP_INTERNAL_SERVER_ERROR, [], $message, [$msg], 500);
        }

        return parent::render($request, $e);
    }
}
