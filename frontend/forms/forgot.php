<?php

session_start();
include __DIR__ . '/../../backend/db.php';
include __DIR__ . '/../../frontend/forms/header.php';

?>

<div class="w-full items-center justify-center min-h-50 flex">
    <div class="w-full flex text-center gap-10 p-2 max-w-300 items-center min-h-20 ">
        <div class="w-full p-5 box min-h-50 ">
            <img src="/restaurant/frontend/asset/sendEmail.png" class="w-full h-full" alt="login.svg">
        </div>
        <div class="w-full flex flex-col gap-5 p-5 rounded-lg shadow-lg box">
            <h1 class="text-4xl card-title mb-2 ">Forgot<span class="text-info"> password</span></h1>
            <form method="POST" class="flex flex-col gap-3">
                <label class="label text-left" for="">we will send you a password of de recuperation , with your <p class="text-info info-content">email or phone number</p></label>
                <div class="w-full input-wrapper password-wrapper">
                    <input type="text" name="name" placeholder="Enter your email or number phone" class="input bg-slate-500/10 w-full" />
                    <i class="fa-solid fa-envelope iconInput"></i>
                </div>
                <button class="btn btn-info">send your email <i class="fas fa-send"></i> </button>
            </form>
            <hr>
            <p>
                <a href="./register.php" class="link link-info">register</a> , <a href="./connexion.php" class="link link-info">sign in</a>
            </p>
        </div>
    </div>
</div>
<style>
    .password-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .iconInput {
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
</style>



<?php
include __DIR__ . '/../../frontend/forms/footer.php';
?>