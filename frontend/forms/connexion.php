<?php
session_start();
include __DIR__ . '/../../backend/db.php';
include __DIR__ . '/../../frontend/forms/header.php';

if (isset($_SESSION['user_id'])) {
    header("Location:../main/main.php");
    exit;
}

$status = "";
$setcolor = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    @$username = trim($_POST['name'] ?? '');
    @$password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($username) || empty($password)) {
        $setcolor = "bg-red-500/50";
        $status = "veuillez remplir tout les champs";
    } else {

        try {
            $tableCheck = $connect->query("SHOW TABLES LIKE 'users'");
            if ($tableCheck->num_rows == 0) {
                $setcolor = "bg-red-500/50";
                $status = "Table users non trouvée";
            } else {

                $stmt = $connect->prepare("SELECT id, name, username, email, telephone, password FROM users WHERE telephone = ? OR email = ?");
                $stmt->bind_param("ss", $username, $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {

                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['user_username'] = $user['username'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_telephone'] = $user['telephone'];
                        $_SESSION['logged_in'] = "accepter";

                        if ($remember) {
                            $token = bin2hex(random_bytes(32));
                            setcookie('remember_token', $token, time() + (86400 * 30), "/");
                        }
                        $status = "connexion reussie ! redirection en cours ...";
                        $setcolor = "bg-green-500/50";
                        echo '<meta http-equiv="refresh" content="2; url=../main/main.php">';
                    } else {
                        $setcolor = "bg-red-500/50";
                        $status = "Mot de passe incorrect";
                    }
                } else {
                    $setcolor = "bg-red-500/50";
                    $status = "Numéro de téléphone ou email invalide";
                }
                $stmt->close();
            }
        } catch (Exception $e) {
            $setcolor = "bg-red-500/50";
            $status = "une erreur est survenue. Veuillez réessayer";
            error_log("erreur de connexion : " . $e->getMessage());
        }
    }
}

?>
<div class="min-w-50 rounded-box fixed right-5 bottom-5 <?php echo $setcolor ?> items-center justify-center p-5 flex">
    <p class="text text-slate-100"><?php echo $status ?></p>
</div>


<div class="w-full items-center justify-center min-h-50 flex">
    <div class="w-full flex text-center gap-10 p-2 max-w-300 items-center min-h-20 ">
        <div class="w-full p-5 box min-h-50 ">
            <img src="/restaurant/frontend/asset/connexion.svg" class="w-full h-full" alt="login.svg">
        </div>
        <div class="w-full flex flex-col gap-5 p-5 rounded-lg shadow-lg box">
            <h1 class="text-2xl card-title mb-5 ">Welcome<span class="text-info"> Back</span></h1>
            <form class="w-full flex flex-col gap-3" action="" method="post">
                <div class="w-full input-wrapper password-wrapper">
                    <input type="text" name="name" placeholder="Enter your email or number phone" class="input bg-slate-500/10 w-full" />
                    <i class="fa-solid fa-envelope iconInput"></i>
                </div>
                <div class="w-full input-wrapper password-wrapper">
                    <input type="password" name="password" placeholder="Enter your password" class="input bg-slate-500/10 w-full" />
                    <i class="fa-solid fa-eye password-toggle"></i>
                </div>
                <div class="flex items-center ">
                    <div class="w-full px-3 py-2 items-center flex gap-2">
                        <input type="checkbox" name="remember" id="rememb" class="checkbox checkbox-info w-4 h-4 " />
                        <label for="rememb" class="text-sm">always remember me !</label>
                    </div>
                    <div class="w-full text-right">
                        <a href="./forgot.php" class=" text-sm hover:underline text-primary">forgot password ?</a>
                    </div>
                </div>
                <div>
                    <input type="submit" name="submit" value="login" class="btn btn-info w-full login-btn" />
                </div>
            </form>
            <hr>
            <p>
                not have account , <a href="./register.php" class="link link-info">register</a>
            </p>
        </div>
    </div>
</div>

<style>
    h1 {
        font-size: clamp(2rem, 3vw, 5rem);
    }

    input {
        border: none;
    }

    .password-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-wrapper input {
        padding-right: 40px;
    }
    .iconInput ,
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        opacity: 0.6;
        transition: all 0.3s ease;
        z-index: 10;
        font-size: 1.1rem;
    }

    .password-toggle:hover {
        opacity: 1;
        transform: translateY(-50%) scale(1.1);
    }
</style>





<?php
include __DIR__ . '/../../frontend/forms/footer.php';
?>