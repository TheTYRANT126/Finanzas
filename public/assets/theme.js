(() => {
    try {
        const theme = localStorage.getItem('saldo-theme');
        if (theme === 'dark' || theme === 'light') document.documentElement.dataset.theme = theme;
    } catch {
        // El tema del dispositivo sigue disponible sin almacenamiento local.
    }
})();
