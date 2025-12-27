<?php

namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller
{
    public function login()
    {
        $this->view('auth/login');
    }

    public function authenticate()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login Success
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Remove password from session data
            unset($user['password']);
            $_SESSION['user'] = $user;

            $this->redirect('/');
        } else {
            // Login Failed
            echo "Email ou senha inválidos. <a href='/login'>Tentar novamente</a>";
        }
    }

    public function register()
    {
        $this->view('auth/register');
    }

    public function store()
    {
        // 1. Get/Validate Data
        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $birthdate = $_POST['birthdate'] ?? null;
        $gender = $_POST['gender'] ?? null;

        if (!$name || !$email || !$password) {
            die("Por favor, preencha todos os campos obrigatórios.");
        }

        // 2. Hash Password
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // 3. Save to Database
        $db = \App\Core\Database::getInstance();

        try {
            $stmt = $db->prepare("INSERT INTO users (name, email, password, birthdate, gender) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $passwordHash, $birthdate, $gender]);

            // Redirect to login
            $this->redirect('/login');
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                die("Este email já está cadastrado.");
            }
            die("Erro ao cadastrar: " . $e->getMessage());
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        $this->redirect('/login');
    }
}
