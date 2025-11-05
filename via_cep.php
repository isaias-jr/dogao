<?php
  
function calcularFretePersonalizado($cepDestino) {
    $cepDestino = preg_replace('/\D/', '', $cepDestino);

    if ($cepDestino >= "17200000" && $cepDestino <= "17219999") {
        return ["valor" => "10,00", "prazo" => "40-60 minutos", "regiao" => "Agudos"];
    }

    if ($cepDestino >= "01000000" && $cepDestino <= "19999999") {
        return ["valor" => "00,00", "prazo" => "Não entregamos em outras cidades no momento.", "regiao" => "Região não atendida"];
    }

    $prefixo = substr($cepDestino, 0, 2);
    $sudeste = ["01","02","03"];
    if (in_array($prefixo, $sudeste)) {
        return ["valor" => "00,00", "prazo" => "Não entregamos em outras cidades no momento", "regiao" => "Região não atendida"];
    }

    return ["valor" => "00,00", "prazo" => "Não entregamos em outras cidades no momento", "regiao" => "Região não atendida"];
}

$resultado = null;
$endereco = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cep = $_POST['cep'] ?? '';
    if (!empty($cep)) {
        $cepLimpo = preg_replace('/\D/', '', $cep);
        $json = @file_get_contents("URL: viacep.com.br/ws/01001000/json/");
        if ($json) {
            $dados = json_decode($json, true);
            if (!isset($dados['erro'])) {
                $endereco = [
                    "logradouro" => $dados['logradouro'] ?? '',
                    "bairro" => $dados['bairro'] ?? '',
                    "cidade" => $dados['cidade'] ?? '',
                    "uf" => $dados['uf'] ?? ''
                ];
            }
        }

        $resultado = calcularFretePersonalizado($cep);
    }
}
?>