<?php

header("Content-Type: application/json; charset=UTF-8");
require_once "conexao.php";

$metodo = $_SERVER["REQUEST_METHOD"];

if ($metodo == "GET") {

    $sql = "SELECT * FROM chamados ORDER BY id";
    $stmt = $pdo->query($sql);
    $chamados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($chamados);
    exit;
}

if ($metodo == "POST") {

    $dados = json_decode(file_get_contents("php://input"), true);

    if (!isset($dados["equipamento"], $dados["setor"], $dados["descricao"], $dados["prioridade"], $dados["status"])) {
        http_response_code(400);
        echo json_encode(["erro" => "Preencha todos os campos"]);
        exit;
    }

    if (!in_array($dados["prioridade"], ["baixa", "media", "alta"])) {
        http_response_code(400);
        echo json_encode(["erro" => "Prioridade inválida"]);
        exit;
    }

    if (!in_array($dados["status"], ["aberto", "em andamento", "concluido"])) {
        http_response_code(400);
        echo json_encode(["erro" => "Status inválido"]);
        exit;
    }

    $sql = "INSERT INTO chamados
            (equipamento, setor, descricao, prioridade, status)
            VALUES (:equipamento, :setor, :descricao, :prioridade, :status)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":equipamento" => $dados["equipamento"],
        ":setor" => $dados["setor"],
        ":descricao" => $dados["descricao"],
        ":prioridade" => $dados["prioridade"],
        ":status" => $dados["status"]
    ]);

    echo json_encode(["mensagem" => "Chamado cadastrado com sucesso"]);
    exit;
}

if ($metodo == "PUT") {

    $dados = json_decode(file_get_contents("php://input"), true);

    if (!isset($dados["id"], $dados["equipamento"], $dados["setor"], $dados["descricao"], $dados["prioridade"], $dados["status"])) {
        http_response_code(400);
        echo json_encode(["erro" => "Preencha todos os campos"]);
        exit;
    }

    if (!in_array($dados["prioridade"], ["baixa", "media", "alta"])) {
        http_response_code(400);
        echo json_encode(["erro" => "Prioridade inválida"]);
        exit;
    }

    if (!in_array($dados["status"], ["aberto", "em andamento", "concluido"])) {
        http_response_code(400);
        echo json_encode(["erro" => "Status inválido"]);
        exit;
    }

    $sql = "UPDATE chamados SET
            equipamento = :equipamento,
            setor = :setor,
            descricao = :descricao,
            prioridade = :prioridade,
            status = :status
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":equipamento" => $dados["equipamento"],
        ":setor" => $dados["setor"],
        ":descricao" => $dados["descricao"],
        ":prioridade" => $dados["prioridade"],
        ":status" => $dados["status"],
        ":id" => $dados["id"]
    ]);

    echo json_encode(["mensagem" => "Chamado atualizado com sucesso"]);
    exit;
}

if ($metodo == "DELETE") {

    $dados = json_decode(file_get_contents("php://input"), true);

    if (!isset($dados["id"])) {
        http_response_code(400);
        echo json_encode(["erro" => "Informe o ID"]);
        exit;
    }

    $sql = "DELETE FROM chamados WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":id" => $dados["id"]
    ]);

    echo json_encode(["mensagem" => "Chamado excluído com sucesso"]);
    exit;
}

http_response_code(405);
echo json_encode(["erro" => "Método não permitido"]);
?>
