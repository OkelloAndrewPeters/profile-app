<?php
$pdo = new \PDO("sqlite:profileapp.db");

$commands = [
    'CREATE TABLE IF NOT EXISTS app_users (
        phone_number      VARCHAR(15)   NOT NULL,
        first_name        VARCHAR(80)   NOT NULL,
        last_name         VARCHAR(80)   NOT NULL,
        password_hash     VARCHAR(255)  NOT NULL,
        PRIMARY KEY (phone_number)
    );',
    'CREATE TABLE IF NOT EXISTS oauth_clients (
        client_id             VARCHAR(80)   NOT NULL,
        client_secret         VARCHAR(80),
        redirect_uri          VARCHAR(2000),
        grant_types           VARCHAR(80),
        scope                 VARCHAR(4000),
        user_id               VARCHAR(80),
        PRIMARY KEY (client_id)
    );',
    'CREATE TABLE IF NOT EXISTS oauth_access_tokens (
        access_token         VARCHAR(40)    NOT NULL,
        client_id            VARCHAR(80)    NOT NULL,
        user_id              VARCHAR(80),
        expires              TIMESTAMP      NOT NULL,
        scope                VARCHAR(4000),
        PRIMARY KEY (access_token)
    );',
    'CREATE TABLE IF NOT EXISTS oauth_authorization_codes (
        authorization_code  VARCHAR(40)     NOT NULL,
        client_id           VARCHAR(80)     NOT NULL,
        user_id             VARCHAR(80),
        redirect_uri        VARCHAR(2000),
        expires             TIMESTAMP       NOT NULL,
        scope               VARCHAR(4000),
        id_token            VARCHAR(1000),
        PRIMARY KEY (authorization_code)
    );',
    'CREATE TABLE IF NOT EXISTS oauth_refresh_tokens (
        refresh_token       VARCHAR(40)     NOT NULL,
        client_id           VARCHAR(80)     NOT NULL,
        user_id             VARCHAR(80),
        expires             TIMESTAMP       NOT NULL,
        scope               VARCHAR(4000),
        PRIMARY KEY (refresh_token)
    );',
    'CREATE TABLE IF NOT EXISTS oauth_users (
        username            VARCHAR(80),
        password            VARCHAR(80),
        first_name          VARCHAR(80),
        last_name           VARCHAR(80),
        email               VARCHAR(80),
        email_verified      BOOLEAN,
        scope               VARCHAR(4000),
        PRIMARY KEY (username)
    );',
    'CREATE TABLE IF NOT EXISTS oauth_scopes (
        scope               VARCHAR(80)     NOT NULL,
        is_default          BOOLEAN,
        PRIMARY KEY (scope)
    );',
    'CREATE TABLE IF NOT EXISTS oauth_jwt (
        client_id           VARCHAR(80)     NOT NULL,
        subject             VARCHAR(80),
        public_key          VARCHAR(2000)   NOT NULL
    );'
];

foreach ($commands as $command) {
    $pdo->exec($command);
}

function insertUser($firstName, $lastName, $phoneNumber, $password)
{
    global $pdo;
    $sql = 'INSERT INTO app_users(phone_number, first_name, last_name, password_hash) VALUES(:phone_number, :first_name, :last_name, :password_hash)';

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':phone_number' => $phoneNumber,
        ':first_name' => $firstName,
        ':last_name' => $lastName,
        ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);
}

function getUser($phoneNumber)
{
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM app_users WHERE phone_number = :phone_number LIMIT 1');
    $stmt->execute([':phone_number' => $phoneNumber]);

    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    if($row == false) {
        return null;
    }
    return $row;
}

return $pdo;
