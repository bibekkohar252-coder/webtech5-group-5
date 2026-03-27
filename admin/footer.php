</main>
<footer>
    <p>&copy; <?= date('Y') ?> Gorkha Institute of Technology. All rights reserved.</p>
</footer>
<script>
// Mobile menu toggle for admin
const mobileBtn = document.querySelector('.mobile-menu-btn');
const nav = document.querySelector('nav');
const authBtns = document.querySelector('.auth-buttons');

if (mobileBtn) {
    mobileBtn.addEventListener('click', function() {
        nav.classList.toggle('active');
        authBtns.classList.toggle('active');
    });
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const header = document.querySelector('header');
    if (!header.contains(event.target) && !mobileBtn?.contains(event.target)) {
        nav?.classList.remove('active');
        authBtns?.classList.remove('active');
    }
});
</script>
</body>
</html>