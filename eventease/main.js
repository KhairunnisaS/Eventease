document.querySelector('.google-button').addEventListener('click', function() {
    window.location.href = 'https://accounts.google.com/signin'; // URL untuk Google sign-in
});

document.querySelector('.facebook-button').addEventListener('click', function() {
    window.location.href = 'https://www.facebook.com/login'; // URL untuk Facebook login
});

document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.querySelector('.input-password');
    const togglePassword = document.querySelector('.toggle-password');
    const eyeIcon = document.querySelector('#eye-icon');

    if (!passwordInput || !togglePassword || !eyeIcon) {
        console.error('Elemen tidak ditemukan!');
        return;  // Stop jika elemen tidak ditemukan
    }

    togglePassword.addEventListener('click', function () {
        console.log('Icon clicked!'); // Verifikasi apakah event listener dipanggil
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';  
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash'); 
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
});