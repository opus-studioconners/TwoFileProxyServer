<?php
        session_start();
        function funPrepareFiles() {
                $files = $_FILES;
                $files2 = [];
                foreach ($files as $input => $infoArr) {
                        $filesByInput = [];
                        foreach ($infoArr as $key => $valueArr) {
                                if (is_array($valueArr)) { // file input "multiple"
                                        foreach($valueArr as $i=>$value) {
                                                $filesByInput[$i][$key] = $value;
                                        }
                                }
                                else { // -> string, normal file input
                                        $filesByInput[] = $infoArr;
                                        break;
                                }
                        }
                        $files2 = array_merge($files2,$filesByInput);
                }
                $files3 = [];
                foreach($files2 as $file) { // let's filter empty & errors
                        if (!$file['error']) $files3[] = $file;
                }
                return $files3;
        }

        $objHTTPRequest = new stdClass();
        $objHTTPRequest->GET = $_GET;
        $objHTTPRequest->POST = $_POST;
        $objHTTPRequest->FILES = funPrepareFiles();
        $objHTTPRequest->SERVER = $_SERVER;
        $objHTTPRequest->SESSION = new stdClass();
        $objHTTPRequest->SESSION->id = session_id();

        $arrHTTPOptions = array(
                'http' => array(
                        'method'  => 'POST',
                        'content' => json_encode($objHTTPRequest),
                        'header'=>  "Content-Type: application/json\r\n" .
                        "Accept: application/json\r\n"
                )
        );

        $objHTTPContext  = stream_context_create( $arrHTTPOptions );

        //PRODUCTION
        //$strResponse =  @file_get_contents("https://your-production-webhook-url")

        //DEVELOPMENT
        $strResponse = @file_get_contents("https://your-development-webhook-url")

        // Send Header
        if(isset($http_response_header[3])){
                header($http_response_header[3]);
        }
        // Send Body/Content
        echo $strResponse;
?>
