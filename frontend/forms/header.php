<!DOCTYPE html>
<html lang="en" data-theme="synthwave">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<header class="gap-4 px-10 py-4 flex items-center w-full justify-between">
    <div class="flex items-center gap-5">

        <div class="text-2xl font-bold">
            <span class="text-info">R</span>esto <span class="text-info">FOOD</span>
        </div>
    </div>
    <div class="w-10 h-10">
        <img src="/restaurant/frontend/asset/logo.gif" class="w-full h-full" alt="logo.gif">
    </div>
</header>
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



        const passwordInput = document.querySelector('input[name="password"]');
        const toggleIcon = document.querySelector('.password-toggle');

        if (passwordInput && toggleIcon) {
            toggleIcon.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                this.className = isPassword ? 'fa-solid fa-eye-slash password-toggle' : 'fa-solid fa-eye password-toggle';
                gsap.to(this, {
                    scale: 1.2,
                    duration: 0.15,
                    yoyo: true,
                    repeat: 1
                });
            });
        }




        const statusDiv = document.querySelector('.rounded-box');
        if (statusDiv) {
            const message = statusDiv.textContent.trim();
            if (message !== '') {
                const isError = message.includes('veuillez') ||
                    message.includes('incorrect') ||
                    message.includes('invalide') ||
                    message.includes('erreur');
                const isSuccess = message.includes('reussie') || message.includes('connexion');
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

        document.querySelectorAll('.fa-solid, .fa-regular').forEach(icon => {
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

    });
</script>