
        /* ============================================
           SMOOTH SCROLL FOR ANCHOR LINKS
           ============================================ */
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const y = target.getBoundingClientRect().top + window.pageYOffset - 60;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            });
        });

        /* ============================================
           SCROLL TRIGGER REFRESH ON LOAD
           ============================================ */
        window.addEventListener('load', () => {
            ScrollTrigger.refresh();
        });
    </script>
</body>

</html>
