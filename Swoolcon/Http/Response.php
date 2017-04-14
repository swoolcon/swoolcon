<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 16-11-13
 * Time: 下午10:13
 */
namespace Swoolcon\Http;
use Phalcon\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Http\ResponseInterface;
use Swoolcon\Http\Response\Headers;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Http\Response as PhalconResponse;

/**
 * Class SwooleResponse
 * @package PhalconPlus\Http
 * @property DiInterface $_dependencyInjector
 */
class Response extends PhalconResponse implements ResponseInterface , InjectionAwareInterface{

    use HttpTrait;

    public function __construct($content = null, $code = null, $status = null) {

        $this->_headers = new Headers();

        if($content !== null){
            $this->_content = $content;
        }

        if ($code !== null) {
            $this->setStatusCode($code, $status);
        }
    }

    public function setDI(DiInterface $di)
    {
        $this->_dependencyInjector = $di;

        //这一步本来不应该写在这里，但目前没有办法，下面的setHeaders 不是必须调的

        $this->setHeaders($this->_headers);

    }

    /**
     * @param PhalconResponse\HeadersInterface|InjectionAwareInterface $headers
     * @return $this
     */
    public function setHeaders(PhalconResponse\HeadersInterface $headers)
    {
        $this->getSwooleRequest();
        $this->getSwooleResponse();


        $headers->setDI($this->_dependencyInjector);
        $this->_headers = $headers;
        return $this;
    }



    public function setStatusCode($code, $message = null) {
        $headers = $this->getHeaders();
        $currentHeadersRaw = $headers->toArray();

        if (is_array($currentHeadersRaw)) {
            foreach($currentHeadersRaw as $key=>$val){
                if (is_string($key) && strstr($key, 'HTTP/')) {
                    $headers->remove($key);
                }
            }
        }

        if($message === null){
            // See: http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
            $statusCodes = [
                // INFORMATIONAL CODES
                "100" => "Continue",                        // RFC 7231, 6.2.1
                "101" => "Switching Protocols",             // RFC 7231, 6.2.2
                "102" => "Processing",                      // RFC 2518, 10.1
                // SUCCESS CODES
                "200" => "OK",                              // RFC 7231, 6.3.1
                "201" => "Created",                         // RFC 7231, 6.3.2
                "202" => "Accepted",                        // RFC 7231, 6.3.3
                "203" => "Non-Authoritative Information",   // RFC 7231, 6.3.4
                "204" => "No Content",                      // RFC 7231, 6.3.5
                "205" => "Reset Content",                   // RFC 7231, 6.3.6
                "206" => "Partial Content",                 // RFC 7233, 4.1
                "207" => "Multi-status",                    // RFC 4918, 11.1
                "208" => "Already Reported",                // RFC 5842, 7.1
                "226" => "IM Used",                         // RFC 3229, 10.4.1
                // REDIRECTION CODES
                "300" => "Multiple Choices",                // RFC 7231, 6.4.1
                "301" => "Moved Permanently",               // RFC 7231, 6.4.2
                "302" => "Found",                           // RFC 7231, 6.4.3
                "303" => "See Other",                       // RFC 7231, 6.4.4
                "304" => "Not Modified",                    // RFC 7232, 4.1
                "305" => "Use Proxy",                       // RFC 7231, 6.4.5
                "306" => "Switch Proxy",                    // RFC 7231, 6.4.6 (Deprecated)
                "307" => "Temporary Redirect",              // RFC 7231, 6.4.7
                "308" => "Permanent Redirect",              // RFC 7538, 3
                // CLIENT ERROR
                "400" => "Bad Request",                     // RFC 7231, 6.5.1
                "401" => "Unauthorized",                    // RFC 7235, 3.1
                "402" => "Payment Required",                // RFC 7231, 6.5.2
                "403" => "Forbidden",                       // RFC 7231, 6.5.3
                "404" => "Not Found",                       // RFC 7231, 6.5.4
                "405" => "Method Not Allowed",              // RFC 7231, 6.5.5
                "406" => "Not Acceptable",                  // RFC 7231, 6.5.6
                "407" => "Proxy Authentication Required",   // RFC 7235, 3.2
                "408" => "Request Time-out",                // RFC 7231, 6.5.7
                "409" => "Conflict",                        // RFC 7231, 6.5.8
                "410" => "Gone",                            // RFC 7231, 6.5.9
                "411" => "Length Required",                 // RFC 7231, 6.5.10
                "412" => "Precondition Failed",             // RFC 7232, 4.2
                "413" => "Request Entity Too Large",        // RFC 7231, 6.5.11
                "414" => "Request-URI Too Large",           // RFC 7231, 6.5.12
                "415" => "Unsupported Media Type",          // RFC 7231, 6.5.13
                "416" => "Requested range not satisfiable", // RFC 7233, 4.4
                "417" => "Expectation Failed",              // RFC 7231, 6.5.14
                "418" => "I'm a teapot",                    // RFC 7168, 2.3.3
                "421" => "Misdirected Request",
                "422" => "Unprocessable Entity",            // RFC 4918, 11.2
                "423" => "Locked",                          // RFC 4918, 11.3
                "424" => "Failed Dependency",               // RFC 4918, 11.4
                "425" => "Unordered Collection",
                "426" => "Upgrade Required",                // RFC 7231, 6.5.15
                "428" => "Precondition Required",           // RFC 6585, 3
                "429" => "Too Many Requests",               // RFC 6585, 4
                "431" => "Request Header Fields Too Large", // RFC 6585, 5
                "451" => "Unavailable For Legal Reasons",   // RFC 7725, 3
                "499" => "Client Closed Request",
                // SERVER ERROR
                "500" => "Internal Server Error",           // RFC 7231, 6.6.1
                "501" => "Not Implemented",                 // RFC 7231, 6.6.2
                "502" => "Bad Gateway",                     // RFC 7231, 6.6.3
                "503" => "Service Unavailable",             // RFC 7231, 6.6.4
                "504" => "Gateway Time-out",                // RFC 7231, 6.6.5
                "505" => "HTTP Version not supported",      // RFC 7231, 6.6.6
                "506" => "Variant Also Negotiates",         // RFC 2295, 8.1
                "507" => "Insufficient Storage",            // RFC 4918, 11.5
                "508" => "Loop Detected",                   // RFC 5842, 7.2
                "510" => "Not Extended",                    // RFC 2774, 7
                "511" => "Network Authentication Required"  // RFC 6585, 6

            ];

            if(!isset($statusCodes[$code])){
                throw new PhalconResponse\Exception('Non-standard statuscode given without a message');
            }

            $defaultMessage = $statusCodes[$code];
            $message = $defaultMessage;
        }


        $headers->setRaw('HTTP/1.1 ' . $code . ' ' . $message);
        $headers->set('Status', $code . ' ' . $message);

        $this->_swooleResponse->status($code);
        return $this;
    }

    public function sendCookies()
    {
        /** @var Manager $eventManager */
        $eventManager = $this->_dependencyInjector->get('eventsManager');
        $eventManager->fire('response:beforeSendCookies',$this);
        return parent::sendCookies();
    }

}