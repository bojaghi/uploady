<!DOCTYPE html>
<html lang="ko">
<body>
<form id="form" method="post" action="" enctype="multipart/form-data">
    <input type="file" name="test_me[]" multiple/>
    <button>Submit</button>
</form>
<?php if (isset($_FILES['test_me'])) : ?>
    <pre><?php print_r($_FILES['test_me']); ?></pre>
<?php endif; ?>
</body>
</html>
