<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Notificação</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-msg">

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
<script>
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toast-msg');
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams && (urlParams.has('success') || urlParams.has('error'))) {
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toast)
        if (urlParams.get('success')) {
            toastMsg.innerHTML = urlParams.get('success')
            toast.classList.add('bg-success', 'text-white')
        }
        if (urlParams.get('error')) {
            toastMsg.innerHTML = urlParams.get('error')
            toast.classList.add('bg-danger', 'text-white')
        }
        toastBootstrap.show()
    }
</script>