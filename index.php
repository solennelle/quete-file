<!-- tu vas devoir mettre en place un système d'upload d'images multiple,
qui n'acceptera que les fichiers de moins de 1Mo, et uniquement des fichiers jpg, png ou gif -->
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" crossorigin="anonymous">
    <title>Upload de fichiers</title>
</head>
<?php
if (isset ($_POST['submit'])) {
    $errors = [];
    $maxSize = 1048576;
    $extensions = ['png', 'gif', 'jpg'];
    if (count($_FILES['upload']['name']) > 0) {
        for ($i = 0; $i < count($_FILES['upload']['name']); $i++) {
            $extension = pathinfo($_FILES['upload']['name'][$i], PATHINFO_EXTENSION);
            if (!in_array($extension, $extensions)) {
                $errors['extension'] = "The only files accepted are .png, .gif et .jpg.";
            }
            if($_FILES['upload']['size'][$i] > $maxSize){
                $errors['size'] = 'File size is greater than allowed size (1Mo).';
            }
            $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
            if ($tmpFilePath != "" && empty($errors)) {
                $shortname = 'image' . uniqid() . '.' . $extension;
                $uploadDir = 'upload/';
                $uploadFile = $uploadDir . $shortname;
                move_uploaded_file($tmpFilePath, $uploadFile);
            }
        }
    }
}
if (isset ($_POST['delete']) && file_exists ('upload/'.$_POST['delete'])) {
    unlink ('upload/'.$_POST['delete']);
}
?>

<body>
<h1 class="text-center p-5">FILES UPLOAD</h1>
<form action="" method="post" enctype="multipart/form-data">
    <label> Select Files: </label>
    <input type="file" name="upload[]" multiple >
    <input type="submit" name="submit" value="Upload" class="btn btn-primary">
</form>
<?php
if (isset ($errors['extension'])) {
    echo $errors['extension'];
}
if (isset ($errors['size'])) {
    echo $errors['size'];
}
?>
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <?php
        $directory = new FilesystemIterator(dirname("upload/__FILE__"));
        foreach ($directory as $fileinfo) { ?>
            <div class="card">
                <img class="card-img-top img-thumbnail" src="upload/<?=$fileinfo->getFilename()?>">
                <div class="card-body text-center">
                    <p class="card-title"><?=$fileinfo->getFilename()?></p>
                    <form method="POST">
                        <input id="delete" name="delete" type="hidden" value="<?=$fileinfo->getFilename()?>">
                        <button class="btn btn-dark" >Delete</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>
