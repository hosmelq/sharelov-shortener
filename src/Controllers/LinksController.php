<?php

namespace Sharelov\Shortener\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Shortener;
use Sharelov\Shortener\Exceptions\NonExistentHashException;

class LinksController extends Controller
{
    //
    
    /*

    * Show the form for creating the new link

    *

    * @return Response

    */

    public function store (Request $request)
    {
        try
        {
            $hash = Shortener::make($request->url,$request->expires_at);
        }

        catch (ValidationException $e)
        {
            return response()->json(['success' => 'false','hash'=>'','url'=>'']);
        }
        
        $domain = config('shortener.domain');
        $path = config('shortener.path');
        $url = $domain.'/'.$path.'/'.$hash;
       
        if(!filter_var($url, FILTER_VALIDATE_URL)){
            throw new ValidationException('Can\'t generate a valid url, check your shortener.php configuration');
        } 

        return response()->json(['success' => 'true','hash'=>$hash,'url'=>$url]);
    }
    /*

    * Translate a redirect or return error mesage

    *

    * @return Response

    */

    public function translateHash ($hash)
    {
        try
        {
            $url = Shortener::getUrlByHash($hash);
            return redirect()->to($url);
        }

        catch (NonExistentHashException $e)
        {
            abort(404);
        }


    }
}
