<?php

namespace WebmaniaBR;

class NFe {

    function __construct( array $vars ){

        $this->consumerKey = $vars['consumer_key'];
        $this->consumerSecret = $vars['consumer_secret'];
        $this->accessToken = $vars['oauth_access_token'];
        $this->accessTokenSecret = $vars['oauth_access_token_secret'];

    }

    function statusSefaz( $data = null ){

        $data = array();
        $response = self::connectWebmaniaBR( 'GET', 'https://webmaniabr.com/api/1/nfe/sefaz/', $data );
        if (isset($response->error)) return $response;
        if ($response->status == 'online') return true;
        else return false;

    }

    function validadeCertificado( $data = null ){

        $data = array();
        $response = self::connectWebmaniaBR( 'GET', 'https://webmaniabr.com/api/1/nfe/certificado/', $data );
        if (isset($response->error)) return $response;
        return $response->expiration;

    }

    function emissaoNotaFiscal( array $data ){

        $response = self::connectWebmaniaBR( 'POST', 'https://webmaniabr.com/api/1/nfe/emissao/', $data );
        return $response;

    }

    function consultaNotaFiscal( $chave, $ambiente ){

        $data = array();
        $data['chave'] = $chave;
        $data['ambiente'] = $ambiente;
        $response = self::connectWebmaniaBR( 'GET', 'https://webmaniabr.com/api/1/nfe/consulta/', $data );
        return $response;

    }

    function cancelarNotaFiscal( $chave, $motivo, $ambiente ){

        $data = array();
        $data['chave'] = $chave;
        $data['motivo'] = $motivo;
        $data['ambiente'] = $ambiente;
        $response = self::connectWebmaniaBR( 'PUT', 'https://webmaniabr.com/api/1/nfe/cancelar/', $data );
        return $response;

    }

    function inutilizarNumeracao( $sequencia, $motivo, $ambiente ){

        $data = array();
        $data['sequencia'] = $sequencia;
        $data['motivo'] = $motivo;
        $data['ambiente'] = $ambiente;
        $response = self::connectWebmaniaBR( 'PUT', 'https://webmaniabr.com/api/1/nfe/inutilizar/', $data );
        return $response;

    }

    function cartaCorrecao( $chave, $correcao, $ambiente ){

        $data = array();
        $data['chave'] = $chave;
        $data['correcao'] = $correcao;
        $data['ambiente'] = $ambiente;
        $response = self::connectWebmaniaBR( 'POST', 'https://webmaniabr.com/api/1/nfe/cartacorrecao/', $data );
        return $response;

    }

    function devolucaoNotaFiscal( $chave, $classe_imposto, $natureza_operacao, $ambiente ){

        $data = array();
        $data['chave'] = $chave;
        $data['classe_imposto'] = $classe_imposto;
        $data['natureza_operacao'] = $natureza_operacao;
        $data['ambiente'] = $ambiente;
        $response = self::connectWebmaniaBR( 'POST', 'https://webmaniabr.com/api/1/nfe/devolucao/', $data );
        return $response;

    }

    function connectWebmaniaBR( $request, $endpoint, $data ){

        @set_time_limit( 300 );
        ini_set('max_execution_time', 300);
        ini_set('max_input_time', 300);
        ini_set('memory_limit', '256M');

        $headers = array(
          'Cache-Control: no-cache',
          'Content-Type:application/json',
          'X-Consumer-Key: '.$this->consumerKey,
          'X-Consumer-Secret: '.$this->consumerSecret,
          'X-Access-Token: '.$this->accessToken,
          'X-Access-Token-Secret: '.$this->accessTokenSecret
        );

        $rest = curl_init();
        curl_setopt($rest, CURLOPT_CONNECTTIMEOUT , 300);
        curl_setopt($rest, CURLOPT_TIMEOUT, 300);
        curl_setopt($rest, CURLOPT_URL, $endpoint.'?time='.time());
        curl_setopt($rest, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($rest, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($rest, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($rest, CURLOPT_CUSTOMREQUEST, $request);
        curl_setopt($rest, CURLOPT_POSTFIELDS, json_encode( $data ));
        curl_setopt($rest, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($rest, CURLOPT_FRESH_CONNECT, true);
        $response = curl_exec($rest);
        curl_close($rest);

        return json_decode($response);

    }

}

?>
