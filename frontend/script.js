document.addEventListener('DOMContentLoaded', () => {
    const rows = document.querySelectorAll('tbody tr ');
    rows.forEach((row, index) => {
        gsap.from(row, {
            opacity: 0,
            y: 40,
            duration: 0.5,
            ease: "back.out(1.2)",
            delay: index * 0.06
        });
    });
});
