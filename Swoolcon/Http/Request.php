<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 16-11-13
 * Time: 下午10:13
 */
namespace Swoolcon\Http;

use Phalcon\Http\Request\Exception;
use Phalcon\Http\Request\File;

class Request implements \Phalcon\Http\RequestInterface, \Phalcon\Di\InjectionAwareInterface
{

    use HttpTrait;

    protected $_dependencyInjector;


    protected $_rawBody;

    protected $_filter;

    protected $_putCache;

    protected $_httpMethodParameterOverride = false;

    protected $_strictHostCheck = false;


    public function setDi(\Phalcon\DiInterface $dependencyInjector)
    {
        $this->_dependencyInjector = $dependencyInjector;
    }

    public function getDI()
    {
        return $this->_dependencyInjector;
    }

    private function _getRequest()
    {
        $requestObj = $this->getSwooleRequest();
        $get        = isset($requestObj->get) ? $requestObj->get : [];
        $post       = isset($requestObj->post) ? $requestObj->post : [];

        return array_merge($get, $post);
    }

    public function get($name = null, $filters = null, $defaultValue = null, $notAllowEmpty = false, $noRecursive = false)
    {
        return $this->getHelper($this->_getRequest(), $name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
    }

    /**
     * Gets a variable from the $_POST superglobal applying filters if needed
     * If no parameters are given the $_POST superglobal is returned
     * <code>
     * //Returns value from $_POST["user_email"] without sanitizing
     * $userEmail = $request->getPost("user_email");
     * //Returns value from $_POST["user_email"] with sanitizing
     * $userEmail = $request->getPost("user_email", "email");
     * </code>
     *
     * @param string $name
     * @param mixed $filters
     * @param mixed $defaultValue
     * @param bool $notAllowEmpty
     * @param bool $noRecursive
     * @return mixed
     */
    public function getPost($name = null, $filters = null, $defaultValue = null, $notAllowEmpty = false, $noRecursive = false)
    {
        return $this->getHelper($this->getSwooleRequest()->post, $name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
    }

    /**
     * Gets a variable from put request
     * <code>
     * //Returns value from $_PUT["user_email"] without sanitizing
     * $userEmail = $request->getPut("user_email");
     * //Returns value from $_PUT["user_email"] with sanitizing
     * $userEmail = $request->getPut("user_email", "email");
     * </code>
     *
     * @param string $name
     * @param mixed $filters
     * @param mixed $defaultValue
     * @param bool $notAllowEmpty
     * @param bool $noRecursive
     * @return mixed
     */
    public function getPut($name = null, $filters = null, $defaultValue = null, $notAllowEmpty = false, $noRecursive = false)
    {
        $put = $this->_putCache;
        if (!is_array($put)) {
            $put = [];
            parse_str($this->getRawBody(), $put);
            $this->_putCache = $put;
        }
        return $this->getHelper($put, $name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
    }

    /**
     * Gets variable from $_GET superglobal applying filters if needed
     * If no parameters are given the $_GET superglobal is returned
     * <code>
     * // Returns value from $_GET['id'] without sanitizing
     * $id = $request->getQuery('id');
     * // Returns value from $_GET['id'] with sanitizing
     * $id = $request->getQuery('id', 'int');
     * // Returns value from $_GET['id'] with a default value
     * $id = $request->getQuery('id', null, 150);
     * </code>
     *
     * @param string $name
     * @param mixed $filters
     * @param mixed $defaultValue
     * @param bool $notAllowEmpty
     * @param bool $noRecursive
     * @return mixed
     */
    public function getQuery($name = null, $filters = null, $defaultValue = null, $notAllowEmpty = false, $noRecursive = false)
    {
        return $this->getHelper($this->getSwooleRequest()->post, $name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
    }

    /**
     * Helper to get data from superglobals, applying filters if needed.
     * If no parameters are given the superglobal is returned.
     *
     * @param array $source
     * @param string $name
     * @param mixed $filters
     * @param mixed $defaultValue
     * @param bool $notAllowEmpty
     * @param bool $noRecursive
     * @return array|null
     * @throws Exception
     */
    protected final function getHelper(array $source, $name = null, $filters = null, $defaultValue = null, $notAllowEmpty = false, $noRecursive = false)
    {
        if ($name === null) {
            return $source;
        }

        if (!isset($source[$name]) || !$source[$name]) {
            return $defaultValue;
        }
        $value = $source[$name];

        if (null !== $filters) {
            $filter = $this->_filter;
            if (!is_object($filter)) {
                $dependencyInjector = $this->_dependencyInjector;
                if (!is_object($dependencyInjector)) {
                    throw new Exception('A dependency injection object is required to access the "filter" service');
                }
                $filter        = $dependencyInjector->getShared('filter');
                $this->_filter = $filter;
            }
            $value = $filter->sanitize($value, $filters, $noRecursive);
        }
        if (empty($value) && $notAllowEmpty === true) {
            return $defaultValue;
        }
        return $value;

    }

    /**
     * Gets variable from $_SERVER superglobal
     *
     * @param string $name
     * @return string|null
     */
    public function getServer($name)
    {
        $name    = strtolower($name);
        $request = $this->getSwooleRequest();
        return isset($request->server[$name]) ? $request->server[$name] : null;
    }

    /**
     * Checks whether $_REQUEST superglobal has certain index
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        $request = $this->_getRequest();
        return isset($request[$name]);
    }

    /**
     * Checks whether $_POST superglobal has certain index
     *
     * @param string $name
     * @return bool
     */
    public function hasPost($name)
    {
        return isset($this->getSwooleRequest()->post[$name]);
    }

    /**
     * Checks whether the PUT data has certain index
     *
     * @param string $name
     * @return bool
     */
    public function hasPut($name)
    {
        //以后来弄
        return false;
    }

    /**
     * Checks whether $_GET superglobal has certain index
     *
     * @param string $name
     * @return bool
     */
    public function hasQuery($name)
    {
        return isset($this->getSwooleRequest()->get[$name]);
    }

    /**
     * Checks whether $_SERVER superglobal has certain index
     *
     * @param string $name
     * @return bool
     */
    public final function hasServer($name)
    {
        $server = $this->getServer($name);
        return $server ? true : false;
    }

    /**
     * Gets HTTP header from request data
     *
     * @param string $header
     * @return string
     */
    public final function getHeader($header)
    {
        $header = strtolower($header);
        $header = str_replace('http_', '', $header);
        $header = str_replace('HTTP_', '', $header);
        $header = str_replace('_', '-', $header);

        $request = $this->getSwooleRequest();

        return isset($request->header[$header]) ? $request->header[$header] : '';

    }

    /**
     * Gets HTTP schema (http/https)
     *
     * @return string
     */
    public function getScheme()
    {
        $https = $this->getServer('https');
        if ($https) {
            if ($https == 'off') {
                $scheme = 'http';

            } else {
                $scheme = 'https';
            }
        } else {

            $scheme = 'http';
        }
        return $scheme;
    }

    /**
     * Checks whether request has been made using ajax
     *
     * @return bool
     */
    public function isAjax()
    {
        return $this->getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest';
        //return $this->getServer('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest';
    }   //

    /**
     * Checks whether request has been made using SOAP
     *
     * @return bool
     */
    public function isSoap()
    {
        //if($this->getServer('HTTP_SOAPACTION')){
        if ($this->getHeader('SOAPACTION')) {
            return true;
        } else {
            $contentType = $this->getContentType();
            if (!empty($contentType) && strpos($contentType, 'application/soap+xml') !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Alias of isSoap(). It will be deprecated in future versions
     *
     * @return bool
     */
    public function isSoapRequested()
    {
        return $this->isSoap();
    }

    /**
     * Checks whether request has been made using any secure layer
     *
     * @return bool
     */
    public function isSecure()
    {
        return $this->getScheme() === 'https';
    }

    /**
     * Alias of isSecure(). It will be deprecated in future versions
     *
     * @return bool
     */
    public function isSecureRequest()
    {
        return $this->isSecure();
    }

    /**
     * Gets HTTP raw request body
     *
     * @return string
     */
    public function getRawBody()
    {
        if (empty($this->_rawBody)) {
            $this->_rawBody = $this->getSwooleRequest()->rawContent();
        }
        return $this->_rawBody;
    }

    /**
     * Gets decoded JSON HTTP raw request body
     *
     * @param bool $associative
     * @return array|bool|\stdClass
     */
    public function getJsonRawBody($associative = false)
    {
        $rawBody = $this->getRawBody();
        if (!is_string($rawBody)) {
            return false;
        }
        return json_decode($rawBody, $associative);
    }

    /**
     * Gets active server address IP
     *
     * @return string
     */
    public function getServerAddress()
    {
        if ($addr = $this->getServer('SERVER_ADDR')) {
            return $addr;
        }

        return gethostbyname('localhost');
    }

    /**
     * Gets active server name
     *
     * @return string
     */
    public function getServerName()
    {
        if ($serverName = $this->getServer('SERVER_NAME')) {
            return $serverName;
        }
        return 'localhost';

    }

    /**
     * Gets host name used by the request.
     * `Request::getHttpHost` trying to find host name in following order:
     * - `$_SERVER['HTTP_HOST']`
     * - `$_SERVER['SERVER_NAME']`
     * - `$_SERVER['SERVER_ADDR']`
     * Optionally `Request::getHttpHost` validates and clean host name.
     * The `Request::$_strictHostCheck` can be used to validate host name.
     * Note: validation and cleaning have a negative performance impact because they use regular expressions.
     * <code>
     * use Phalcon\Http\Request;
     * $request = new Request;
     * $_SERVER['HTTP_HOST'] = 'example.com';
     * $request->getHttpHost(); // example.com
     * $_SERVER['HTTP_HOST'] = 'example.com:8080';
     * $request->getHttpHost(); // example.com:8080
     * $request->setStrictHostCheck(true);
     * $_SERVER['HTTP_HOST'] = 'ex=am~ple.com';
     * $request->getHttpHost(); // UnexpectedValueException
     * $_SERVER['HTTP_HOST'] = 'ExAmPlE.com';
     * $request->getHttpHost(); // example.com
     * </code>
     *
     * @return string
     */
    public function getHttpHost()
    {
        $strict = $this->_strictHostCheck;

        $host = $this->getHeader('host');
        if (!$host) {
            $host = $this->getServer('SERVER_NAME');
            if (!$host) {
                $host = $this->getServer('SERVER_ADDR');
            }
        }

        if ($host && $strict) {
            $host = strtolower(trim($host));
            if (strpos($host, ':') !== false) {
                //$host = preg_replace('/:[[:digit:]]+$/', '', $host);

                $host = preg_replace('/:[\d]+$/', '', $host);
            }

            if ('' != preg_replace('/[a-z0-9]+\.?/', '', $host)) {
                throw new \UnexpectedValueException('Invalid host ', $host);
            }
        }

        return (string)$host;
    }

    /**
     * Sets if the `Request::getHttpHost` method must be use strict validation of host name or not
     *
     * @param bool $flag
     * @return Request
     */
    public function setStrictHostCheck($flag = true)
    {
        $this->_strictHostCheck = $flag;
        return $this;
    }

    /**
     * Checks if the `Request::getHttpHost` method will be use strict validation of host name or not
     *
     * @return bool
     */
    public function isStrictHostCheck()
    {
        return $this->_strictHostCheck;
    }

    /**
     * Gets information about the port on which the request is made.
     *
     * @return int
     */
    public function getPort()
    {
        $host = $this->getHeader('host');
        if ($host) {
            $pos = strpos($host, ':');
            if (false !== $pos) {
                return intval(substr($host, $pos + 1));
            } else {
                return 'https' === $this->getScheme() ? 443 : 80;
            }
        }

        return intval($this->getServer('SERVER_PORT'));
    }

    /**
     * Gets HTTP URI which request has been made
     *
     * @return string
     */
    public final function getURI()
    {
        if ($uri = $this->getServer('REQUEST_URI')) {
            return $uri;
        }

        return '';
    }

    /**
     * Gets most possible client IPv4 Address. This method search in _SERVER['REMOTE_ADDR'] and optionally in _SERVER['HTTP_X_FORWARDED_FOR']
     *
     * @param bool $trustForwardedHeader
     * @return string|bool
     */
    public function getClientAddress($trustForwardedHeader = false)
    {
        $addr = null;
        if ($trustForwardedHeader) {
            //$addr = $this->getServer('HTTP_X_FORWARDED_FOR');
            $addr = $this->getHeader('X_FORWARDED_FOR');
            if ($addr === null) {
                //$addr = $this->getServer('HTTP_CLIENT_IP'); //
                $addr = $this->getHeader('CLIENT_IP'); //
            }
        }

        if ($addr === null) {
            $addr = $this->getServer('REMOTE_ADDR');
        }

        if (is_string($addr)) {
            if (strpos($addr, ',')) {
                return explode(',', $addr)[0];
            } else {
                return $addr;
            }
        }
        return false;
    }

    /**
     * Gets HTTP method which request has been made
     * If the X-HTTP-Method-Override header is set, and if the method is a POST,
     * then it is used to determine the "real" intended HTTP method.
     * The _method request parameter can also be used to determine the HTTP method,
     * but only if setHttpMethodParameterOverride(true) has been called.
     * The method is always an uppercased string.
     *
     * @return string
     */
    public final function getMethod()
    {
        $returnMethod = '';

        if ($requestMethod = $this->getServer('REQUEST_METHOD')) {
            $returnMethod = $requestMethod;
        }

        if ('POST' == $requestMethod) {
            if ($overridedMethod = $this->getHeader('X-HTTP-METHOD-OVERRIDE')) {
                $returnMethod = $overridedMethod;
            } else if ($this->_httpMethodParameterOverride) {
                if ($spoofedMethod = $this->_getRequest()['_method']) {
                    $returnMethod = $spoofedMethod;
                }
            }
        }

        if (!$this->isValidHttpMethod($returnMethod)) {
            $returnMethod = 'GET';
        }

        return strtoupper($returnMethod);
    }

    /**
     * Gets HTTP user agent used to made the request
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->getHeader('USER_AGENT') ? $this->getHeader('USER_AGENT') : '';
    }

    /**
     * Checks if a method is a valid HTTP method
     *
     * @param string $method
     * @return bool
     */
    public function isValidHttpMethod($method)
    {
        switch (strtoupper($method)) {
            case 'GET':
            case 'POST':
            case 'PUT':
            case 'DELETE':
            case 'HEAD':
            case 'OPTIONS':
            case 'PATCH':
            case 'PURGE':
            case 'GRACE':
            case 'CONNECT':
                return true;
        }

        return false;
    }

    /**
     * Check if HTTP method match any of the passed methods
     * When strict is true it checks if validated methods are real HTTP methods
     *
     * @param mixed $methods
     * @param bool $strict
     * @return bool
     * @throws Exception
     */
    public function isMethod($methods, $strict = false)
    {
        $httpMethod = $this->getMethod();
        if (is_string($methods)) {
            if ($strict && !$this->isValidHttpMethod($methods)) {
                throw new Exception('Invalid HTTP method:' . $methods);
            }
            return $methods == $httpMethod;
        }

        if (is_array($methods)) {
            foreach ($methods as $method) {
                if ($this->isMethod($method, $strict)) {
                    return true;
                }
            }
            return false;
        }

        if ($strict) {
            throw new Exception('Invalid HTTP method:non-string');
        }
        return false;

    }

    /**
     * Checks whether HTTP method is POST. if _SERVER["REQUEST_METHOD"]==="POST"
     *
     * @return bool
     */
    public function isPost()
    {
        return $this->getMethod() == 'POST';
    }

    /**
     * Checks whether HTTP method is GET. if _SERVER["REQUEST_METHOD"]==="GET"
     *
     * @return bool
     */
    public function isGet()
    {
        return $this->getMethod() == 'GET';
    }

    /**
     * Checks whether HTTP method is PUT. if _SERVER["REQUEST_METHOD"]==="PUT"
     *
     * @return bool
     */
    public function isPut()
    {
        return $this->getMethod() == 'PUT';
    }

    /**
     * Checks whether HTTP method is PATCH. if _SERVER["REQUEST_METHOD"]==="PATCH"
     *
     * @return bool
     */
    public function isPatch()
    {
        return $this->getMethod() == 'PATCH';
    }

    /**
     * Checks whether HTTP method is HEAD. if _SERVER["REQUEST_METHOD"]==="HEAD"
     *
     * @return bool
     */
    public function isHead()
    {
        return $this->getMethod() == 'HEAD';
    }

    /**
     * Checks whether HTTP method is DELETE. if _SERVER["REQUEST_METHOD"]==="DELETE"
     *
     * @return bool
     */
    public function isDelete()
    {
        return $this->getMethod() == 'DELETE';
    }

    /**
     * Checks whether HTTP method is OPTIONS. if _SERVER["REQUEST_METHOD"]==="OPTIONS"
     *
     * @return bool
     */
    public function isOptions()
    {
        return $this->getMethod() == 'OPTIONS';
    }

    /**
     * Checks whether HTTP method is PURGE (Squid and Varnish support). if _SERVER["REQUEST_METHOD"]==="PURGE"
     *
     * @return bool
     */
    public function isPurge()
    {
        return $this->getMethod() == 'PURGE';
    }

    /**
     * Checks whether HTTP method is TRACE. if _SERVER["REQUEST_METHOD"]==="TRACE"
     *
     * @return bool
     */
    public function isTrace()
    {
        return $this->getMethod() == 'TRACE';
    }

    /**
     * Checks whether HTTP method is CONNECT. if _SERVER["REQUEST_METHOD"]==="CONNECT"
     *
     * @return bool
     */
    public function isConnect()
    {
        return $this->getMethod() == 'CONNECT';
    }

    /**
     * Checks whether request include attached files
     *
     * @param bool $onlySuccessful
     * @return long
     */
    public function hasFiles($onlySuccessful = false)
    {
        $numberFiles = 0;

        $files = $this->getSwooleRequest()->files;
        if (!is_array($files)) {
            return 0;
        }

        foreach ($files as $file) {
            if ($error = $file['error']) {
                if (!is_array($error)) {
                    $numberFiles++;
                }
            }

            if (is_array($error)) {
                $numberFiles += $this->hasFileHelper($error, $onlySuccessful);
            }
        }

        return $numberFiles;
    }

    /**
     * Recursively counts file in an array of files
     *
     * @param mixed $data
     * @param bool $onlySuccessful
     * @return long
     */
    protected final function hasFileHelper($data, $onlySuccessful)
    {
        $numberFiles = 0;

        if (!is_array($data)) {
            return 1;
        }

        foreach ($data as $value) {
            if (!is_array($value)) {
                if (!$value || !$onlySuccessful) {
                    $numberFiles++;
                }
            }

            if (is_array($value)) {
                $numberFiles += $this->hasFileHelper($value, $onlySuccessful);
            }
        }

        return $numberFiles;
    }

    /**
     * Gets attached files as Phalcon\Http\Request\File instances
     *
     * @param bool $onlySuccessful
     * @return File[]
     */
    public function getUploadedFiles($onlySuccessful = false)
    {
        $files = [];

        $superFiles = $this->getSwooleRequest()->files;

        if (count($superFiles) > 0) {
            foreach ($superFiles as $prefix => $input) {
                if (is_array($input['name'])) {
                    $smoothInput = $this->smoothFiles(
                        $input['name'],
                        $input['type'],
                        $input['tmp_name'],
                        $input['size'],
                        $input['error'],
                        $prefix
                    );

                    foreach ($smoothInput as $file) {
                        if ($onlySuccessful == false || $file['error'] == UPLOAD_ERR_OK) {
                            $dataFile = [
                                'name'     => $file['name'],
                                'type'     => $file['type'],
                                'tmp_name' => $file['tmp_name'],
                                'size'     => $file['size'],
                                'error'    => $file['error'],
                            ];

                            $files[] = new File($input, $file['key']);
                        }
                    }

                } else {
                    if ($onlySuccessful == false || $input['error'] == UPLOAD_ERR_OK) {
                        $files[] = new File($input, $prefix);
                    }
                }
            }
        }

        return $files;
    }

    /**
     * Smooth out $_FILES to have plain array with all files uploaded
     *
     * @param array $names
     * @param array $types
     * @param array $tmp_names
     * @param array $sizes
     * @param array $errors
     * @param string $prefix
     * @return array
     */
    protected final function smoothFiles(array $names, array $types, array $tmp_names, array $sizes, array $errors, $prefix)
    {
        $files = [];
        foreach ($names as $idx => $name) {
            $p = $prefix . '.' . $idx;

            if (is_string($name)) {
                $files[] = [
                    'name'     => $name,
                    'type'     => $types[$idx],
                    'tmp_name' => $tmp_names[$idx],
                    'size'     => $sizes[$idx],
                    'error'    => $errors[$idx],
                    'key'      => $p,
                ];
            }

            if (is_array($name)) {
                $parentFiles = $this->smoothFiles(
                    $names[$idx],
                    $types[$idx],
                    $tmp_names[$idx],
                    $sizes[$idx],
                    $errors[$idx],
                    $p
                );

                foreach ($parentFiles as $file) {
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    /**
     * Returns the available headers in the request
     *
     * @return array
     */
    public function getHeaders()
    {
        //还有 content-type   content-length

        // abc-def   ->   Abc-Def

        $requestHeaders = $this->getSwooleRequest()->header;
        $headers        = [];
        foreach ($requestHeaders as $key => $val) {
            // abc-def -> Abc Def
            $name           = ucwords(strtolower(str_replace('-', ' ', $key)));
            $name           = str_replace(' ', '-', $name);
            $headers[$name] = $val;
        }
        return $headers;
    }

    /**
     * Gets web page that refers active request. ie: http://www.google.com
     *
     * @return string
     */
    public function getHTTPReferer()
    {
        if ($httpReferer = $this->getHeader('REFERER')) {
            return $httpReferer;
        }

        return '';
    }

    /**
     * Process a request header and return an array of values with their qualities
     *
     * @param string $serverIndex
     * @param string $name
     * @return array
     */
    protected final function _getQualityHeader($serverIndex, $name)
    {
        $returnedParts = [];

        //$tmpServer = $this->getServer($serverIndex);
        $tmpServer = $this->getHeader($serverIndex);
        $tmpServer = preg_split('/,\s*/', $tmpServer, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($tmpServer as $part) {
            $headerParts = [];
            $tmpParts    = preg_split('/\s*;\s*/', trim($part), -1, PREG_SPLIT_NO_EMPTY);
            foreach ($tmpParts as $headerPart) {
                if (strpos($headerPart, '=') !== false) {
                    $split = explode('=', $headerPart, 2);

                    if ($split[0] == 'q') {
                        $headerParts['quality'] = (double)$split[1];
                    } else {
                        $headerParts[$split[0]] = $split[1];
                    }
                } else {
                    $headerParts[$name]     = $headerPart;
                    $headerParts['quality'] = 1.0;
                }
            }
            unset($tmpParts);
            $returnedParts[] = $headerParts;
            unset($headerParts);
        }
        unset($tmpServer);
        return $returnedParts;
    }

    /**
     * Process a request header and return the one with best quality
     *
     * @param array $qualityParts
     * @param string $name
     * @return string
     */
    protected final function _getBestQuality(array $qualityParts, $name)
    {
        $i            = 0;
        $quality      = 0.0;
        $selectedName = '';

        foreach ($qualityParts as $accept) {

            if ($i == 0) {
                $qulity       = (double)$accept['quality'];
                $selectedName = $accept[$name];
            } else {
                $acceptQuality = (double)$accept['quality'];
                if ($acceptQuality > $quality) {
                    $quality      = $acceptQuality;
                    $selectedName = $accept[$name];
                }
            }

            $i++;
        }

        return $selectedName;
    }

    /**
     * Gets content type which request has been made
     *
     * @return string|null
     */
    public function getContentType()
    {
        $contentType = $this->getServer('CONTENT_TYPE');

        if ($contentType) {
            return $contentType;

        } else {
            if ($contentType = $this->getHeader('CONTENT-TYPE')) {
                return $contentType;
            }
        }

        return null;
    }

    /**
     * Gets an array with mime/types and their quality accepted by the browser/client from _SERVER["HTTP_ACCEPT"]
     *
     * @return array
     */
    public function getAcceptableContent()
    {
        return $this->_getQualityHeader('ACCEPT', 'accept');
    }

    /**
     * Gets best mime/type accepted by the browser/client from _SERVER["HTTP_ACCEPT"]
     *
     * @return string
     */
    public function getBestAccept()
    {
        return $this->_getBestQuality($this->getAcceptableContent(), 'accept');
    }

    /**
     * Gets a charsets array and their quality accepted by the browser/client from _SERVER["HTTP_ACCEPT_CHARSET"]
     *
     * @return mixed
     */
    public function getClientCharsets()
    {
        return $this->_getQualityHeader('ACCEPT-CHARSET', 'charset');
    }

    /**
     * Gets best charset accepted by the browser/client from _SERVER["HTTP_ACCEPT_CHARSET"]
     *
     * @return string
     */
    public function getBestCharset()
    {
        return $this->_getBestQuality($this->getClientCharsets(), 'charset');
    }

    /**
     * Gets languages array and their quality accepted by the browser/client from _SERVER["HTTP_ACCEPT_LANGUAGE"]
     *
     * @return array
     */
    public function getLanguages()
    {
        return $this->_getQualityHeader('ACCEPT-LANGUAGE', 'language');
    }

    /**
     * Gets best language accepted by the browser/client from _SERVER["HTTP_ACCEPT_LANGUAGE"]
     *
     * @return string
     */
    public function getBestLanguage()
    {
        return $this->_getBestQuality($this->getLanguages(), 'language');
    }

    /**
     * Gets auth info accepted by the browser/client from $_SERVER['PHP_AUTH_USER']
     *
     * @return array|null
     */
    public function getBasicAuth()
    {
        if (($usename = $this->getServer('PHP_AUTH_USER')) && ($password = $this->getServer('PHP_AUTH_PW'))) {

            return [
                'username' => $usename,
                'password' => $password,

            ];
        }

        return null;
    }

    /**
     * Gets auth info accepted by the browser/client from $_SERVER['PHP_AUTH_DIGEST']
     *
     * @return array
     */
    public function getDigestAuth()
    {
        $auth = [];

        if ($digest = $this->getServer('PHP_AUTH_DIGEST')) {
            $matches = [];
            //if(!preg_match_all('/(\w+)=([\'"]?)([^\'"" ,]+)\2/')){
            if (!preg_match_all("#(\\w+)=(['\"]?)([^'\" ,]+)\\2#", $digest, $matches, 2)) {
                return $auth;
            }

            if (is_array($matches)) {
                foreach ($matches as $match) {
                    $auth[$matches[1]] = $match[3];
                }
            }
        }

        return $auth;
    }

}