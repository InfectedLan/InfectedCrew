<html>
  <head>
    <script src="../api/scripts/jquery-1.11.3.min.js"></script>
  </head>
  <body>
    <script>
      $(document).ready(function () {
        $(document).on('click', 'input:checkbox', function () {
            $(this).closest('form').trigger('submit');
        });

        $('.event-checklist-check').on('submit', function (event) {
            event.preventDefault();
            alert('called');
            //checkNote(this);
        });
      });
    </script>

    <form class="event-checklist-check" method="post">
        <input type="hidden" name="id" value="1">
        <input type="checkbox" name="done" value="1">
    </form>
    <form class="event-checklist-check" method="post">
        <input type="hidden" name="id" value="2">
        <input type="checkbox" name="done" value="1">
    </form>
    <form class="event-checklist-check" method="post">
        <input type="hidden" name="id" value="3">
        <input type="checkbox" name="done" value="1">
    </form>
  </body>
</html>
