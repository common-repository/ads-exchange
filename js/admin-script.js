        document.addEventListener('DOMContentLoaded', function() {
            var checkbox = document.getElementById('agree_checkbox');
            var submitButton = document.getElementById('submit_button');

            checkbox.addEventListener('change', function() {
                submitButton.disabled = !this.checked;
            });

            // Inicialmente, desabilita o botão se o checkbox não estiver marcado
            submitButton.disabled = !checkbox.checked;
        });

