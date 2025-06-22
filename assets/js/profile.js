        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    const tabId = this.getAttribute('data-tab');
                    
                    tabPanes.forEach(pane => pane.classList.remove('active'));
                    
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });

// Личные данные: активация кнопки и AJAX сохранение
const personalForm = document.getElementById('personal-data-form');
if (personalForm) {
    const saveBtn = document.getElementById('save-personal-btn');
    const inputs = personalForm.querySelectorAll('input');
    let initial = {};
    inputs.forEach(input => {
        initial[input.name] = input.value;
        input.addEventListener('input', () => {
            let changed = false;
            inputs.forEach(inp => {
                if (inp.value !== initial[inp.name]) changed = true;
            });
            saveBtn.disabled = !changed;
        });
    });
    personalForm.addEventListener('submit', function(e) {
        e.preventDefault();
        saveBtn.disabled = true;
        const formData = new FormData(personalForm);
        formData.append('action', 'update_personal');
        fetch('/includes/submit.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Обновить initial значения
                inputs.forEach(input => {
                    initial[input.name] = input.value;
                });
                saveBtn.disabled = true;
            } else {
                saveBtn.disabled = false;
                if (data.message) alert(data.message);
            }
        });
    });
}