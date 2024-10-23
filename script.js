




document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('create');
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            localStorage.setItem('formData', JSON.stringify(data));
            window.location.href = 'create';
        });
    }
});