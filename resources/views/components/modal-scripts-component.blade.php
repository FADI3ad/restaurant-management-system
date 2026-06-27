<div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('is-active');
                }, 10);
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('is-active');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 250);
            }
        }

        function showDetails() {
            openModal('modal-show');
        }

        function editSection() {
            openModal('modal-edit');
        }

        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                closeModal(event.target.id);
            }
        });
    </script>



</div>
