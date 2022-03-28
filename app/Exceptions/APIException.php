<?php

namespace App\Exceptions;

use Exception;

class APIException extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }
 
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*public function render($request)
    {
        
    }*/
    public function render($request, NotFoundHttpException $exception)
    {

            if ($request->is('api/v1/*')) {
                return response()->json([
                    'message' => 'Record not found.'
                ], 405);
            }        
        
    }
}