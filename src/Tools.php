<?
namespace Yyh\Utils;

class Tools{
    public static function dre($var)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Keep-Alive,User-Agent,If-Modified-Since,Cache-Control,Content-Type,Access-Control-Allow-Headers, Authorization, X-Requested-With, token");
        dd($var);
    }
}