</div> <!-- /.container -->
<footer class=" text-center py-3 mt-50 mb-0 bg-secondary">
  <div class="container">
    <p class="mb-0">Contact: info@rpkarongi.edu.rw | Phone: +250 78xxxxxxx</p>
    <small>&copy; <?php echo date('Y'); ?> RP Karongi Library. All rights reserved.</small>
    <small>developed by student of Rp karong students</small>
  </div>
</footer>

<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script>
// Basic Bootstrap validation
(function () {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})();
</script>
</body>
</html>
