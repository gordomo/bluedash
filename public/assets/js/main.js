$( document ).ready(() => {
    document.querySelectorAll('.login').forEach((form) => {
        form.addEventListener("submit", function(e) {
            e.preventDefault();
            $(this).find('.form-submit')[0].toggleAttribute('disabled', true);
            $(this).submit();
        });
    });
});
