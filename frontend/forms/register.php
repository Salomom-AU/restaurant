<?php
session_start();
include __DIR__ . '/../../backend/db.php';
include __DIR__ . '/../../frontend/forms/header.php';
$setcolor = "";
$status = "";
$hide = "password";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : '';

    $showpwd = isset($_POST['showpwd']);
    if ($showpwd) {
        $hide = "text";
    }
    $telephone_clean = preg_replace('/[^0-9]/', '', $telephone);
    if (empty($name) || empty($username) || empty($email) || empty($telephone) || empty($password) || empty($confirm)) {
        $setcolor = "bg-red-500/50";
        $status = "Veuillez remplir tous les champs";
    } elseif (strlen($telephone_clean) < 10) {
        $setcolor = "bg-red-500/50";
        $status = "Le numéro doit contenir au moins 10 chiffres";
    } elseif (!preg_match('/^[0-9]+$/', $telephone_clean)) {
        $setcolor = "bg-red-500/50";
        $status = "Numéro de téléphone invalide";
    } elseif (strlen($password) < 8) {
        $setcolor = "bg-red-500/50";
        $status = "Le mot de passe doit contenir au moins 8 caractères";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $setcolor = "bg-red-500/50";
        $status = "Veuillez entrer un email valide";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $setcolor = "bg-red-500/50";
        $status = "Le nom d'utilisateur ne doit contenir que des lettres, des chiffres et des underscores";
    } elseif ($password !== $confirm) {
        $setcolor = "bg-red-500/50";
        $status = "Les mots de passe ne correspondent pas";
    } else {

        $stmt = $connect->prepare("SELECT id FROM users WHERE telephone = ?");
        $stmt->bind_param("s", $telephone_clean);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $setcolor = "bg-red-500/50";
            $status = "Ce numéro de téléphone existe déjà";
        } else {
            $stmt->close();

            $stmt = $connect->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $setcolor = "bg-red-500/50";
                $status = "Le nom d'utilisateur ou l'email existe déjà";
            } else {
                $stmt->close();
                $hashpassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $connect->prepare("INSERT INTO users (name, username, email, password, telephone) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $name, $username, $email, $hashpassword, $telephone_clean);

                if ($stmt->execute()) {
                    $setcolor = "bg-green-500/50";
                    $status = "Inscription réussie ! Redirection en cours...";

                    $_SESSION['user_id'] = $connect->insert_id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_username'] = $username;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_telephone'] = $telephone_clean;
                    $_SESSION['logged_in'] = "accepter";
                    echo '<meta http-equiv="refresh" content="2; url=../forms/connexion.php">';
                } else {
                    $setcolor = "bg-red-500/50";
                    $status = "Erreur lors de l'inscription";
                }
                $stmt->close();
            }
        }
    }
}


?>
 <div class="min-w-50 rounded-box fixed right-5 bottom-5 <?php echo $setcolor ?> items-center justify-center p-5 flex">
        <p class="text text-slate-100"><?php echo $status ?></p>
    </div>

<div class="w-full items-center  justify-center min-h-50 flex">
    <div class="w-full flex text-center gap-10 p-2 max-w-300 items-center min-h-20 ">
        <div class="w-full p-5 box min-h-50 ">
            <img src="/restaurant/frontend/asset/register.svg " class="w-full h-full" alt="register.svg">
        </div>
        <div class="w-full flex box flex-col gap-5 p-5 rounded-lg shadow-lg">
            <h1 class="text-2xl card-title mb-5 ">Create an account <span class="text-info">Admin</span></h1>
            <form class="w-full flex flex-col gap-3  " action="" method="post">
                <div class="w-full flex wrap gap-5">
                    <div class="w-full">
                        <input type="text" name="name" placeholder="Name" class="input bg-slate-500/10  w-full" />
                    </div>
                    <div class="w-full">
                        <input type="text" name="username" placeholder="Username" class="input bg-slate-500/10  w-full" />
                    </div>
                </div>
                <div class="w-full input border-none  bg-slate-500/10  items-center flex gap-5">
                    <input type="email" name="email" placeholder="Email" class=" w-full " /> | <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="w-full input border-none  bg-slate-500/10  items-center flex gap-5">
                    <input type="text" name="telephone" placeholder="telephone" class=" w-full " /> | <i class="fa-solid fa-phone"></i>
                </div>
                <div class="w-full input border-none  bg-slate-500/10  items-center flex gap-5 password-wrapper">
                    <input type="<?php echo $hide ?>" name="password" placeholder="Password" class="w-full " />
                    <i class="fa-solid fa-eye password-toggle"></i>
                </div>
                <div class="w-full input border-none  bg-slate-500/10  items-center flex gap-5 password-wrapper">
                    <input type="<?php echo $hide ?>" name="confirm" placeholder="Confirm your Password" class="w-full " />
                    <i class="fa-solid fa-eye password-toggle"></i>
                </div>
                <div class="flex justify-between  items-center p-2 ">
                    <div class=" flex item-center gap-2">
                        <input type="checkbox" name="agree" id="agree" class="" />
                        <label for="agree" class="text-sm">agree term and condition</label>
                    </div>
                </div>
                <div>
                    <input type="submit" name="submit" value="Sign up" class="btn btn-info w-full signup-btn" />
                </div>
            </form>
            <hr>
            <p>
                not have account , <a href="./connexion.php" class="link link-info">sign in</a>
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
        padding-right: 15px;
    }

    .password-wrapper input {
        padding-right: 35px;
        flex: 1;
    }

    .password-toggle {
        cursor: pointer;
        opacity: 0.6;
        transition: all 0.3s ease;
        font-size: 1.1rem;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
    }

    .password-toggle:hover {
        opacity: 1;
        transform: translateY(-50%) scale(1.1);
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {



        gsap.from('.box', {
            opacity: 0,
            y: 40,
            duration: 0.6,
            ease: 'power2.out'
        });

        gsap.from('.input', {
            opacity: 0,
            y: 20,
            duration: 0.5,
            stagger: 0.1,
            ease: 'power2.out'
        });

        gsap.from('h1', {
            opacity: 0,
            y: -20,
            duration: 0.6,
            ease: 'power2.out'
        });



        const passwordInputs = document.querySelectorAll('input[name="password"], input[name="confirm"]');
        const toggleIcons = document.querySelectorAll('.password-toggle');

        toggleIcons.forEach((icon, index) => {
            icon.addEventListener('click', function() {
                const input = passwordInputs[index];
                if (input) {
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';

                    this.className = isPassword ? 'fa-solid fa-eye-slash password-toggle' : 'fa-solid fa-eye password-toggle';


                    gsap.to(this, {
                        scale: 1.2,
                        duration: 0.15,
                        yoyo: true,
                        repeat: 1
                    });
                }
            });
        });


        const showPwd = document.querySelector('input[name="showpwd"]');
        if (showPwd) {
            showPwd.addEventListener('change', function() {
                const type = this.checked ? 'text' : 'password';
                passwordInputs.forEach(input => {
                    if (input) input.type = type;
                });

                gsap.to(this, {
                    scale: 1.3,
                    duration: 0.2,
                    yoyo: true,
                    repeat: 1
                });
            });
        }





        document.querySelectorAll('.fa-solid').forEach(icon => {
            if (!icon.classList.contains('password-toggle')) {
                icon.addEventListener('mouseenter', function() {
                    gsap.to(this, {
                        scale: 1.2,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });

                icon.addEventListener('mouseleave', function() {
                    gsap.to(this, {
                        scale: 1,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
            }
        });


        const statusDiv = document.querySelector('.rounded-box');
        if (statusDiv) {
            const message = statusDiv.textContent.trim();

            if (message !== '') {
                const isError = message.includes('veuillez') ||
                    message.includes('doit') ||
                    message.includes('existe') ||
                    message.includes('echouee') ||
                    message.includes('correspondent') ||
                    message.includes('valide');

                const isSuccess = message.includes('reussie');

                gsap.from(statusDiv, {
                    opacity: 0,
                    y: -20,
                    duration: 0.4,
                    ease: 'power2.out'
                });

                let displayTime = isError ? 6000 : 4000;

                setTimeout(function() {
                    gsap.to(statusDiv, {
                        opacity: 0,
                        y: 20,
                        duration: 0.5,
                        ease: 'power2.in',
                        onComplete: function() {
                            statusDiv.style.display = 'none';
                        }
                    });
                }, displayTime);


                if (isSuccess) {
                    gsap.from(statusDiv, {
                        scale: 0.8,
                        duration: 0.5,
                        ease: 'back.out'
                    });
                }
            }
        }


    });
</script>

<?php
include __DIR__ . '/../../frontend/forms/footer.php';
?>