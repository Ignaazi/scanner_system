<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Konfigurasi Toast (Notifikasi kecil di pojok untuk aksi sukses/info)
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    <?php if (isset($_SESSION['sukses'])): ?>
        Toast.fire({
            icon: 'success',
            title: '<?= $_SESSION['sukses'] ?>'
        });
        <?php unset($_SESSION['sukses']); ?>

    <?php elseif (isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan!',
            text: '<?= $_SESSION['error'] ?>',
            confirmButtonColor: '#f97316'
        });
        <?php unset($_SESSION['error']); ?>

    <?php elseif (isset($_SESSION['warning'])): ?>
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: '<?= $_SESSION['warning'] ?>',
            confirmButtonColor: '#f97316'
        });
        <?php unset($_SESSION['warning']); ?>

    <?php elseif (isset($_SESSION['info'])): ?>
        Toast.fire({
            icon: 'info',
            title: 'Informasi',
            text: '<?= $_SESSION['info'] ?>'
        });
        <?php unset($_SESSION['info']); ?>
    <?php endif; ?>
</script>