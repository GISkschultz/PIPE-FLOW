
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="bootstrap-3.3.6-dist/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>

<style>

    .popup {
    position: fixed;
    top: 60%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        height: 90%;
        width: 50%;
        overflow: auto;


    }



    .container
    {

        padding: 10px;
        width: 50%;
    }

</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">




    <div class="container"><div id="popup" align="center"><p>  <form action="upload.php" method="post" enctype="multipart/form-data">
                <div class="panel panel-primary">

                    <div class="panel-heading">Pipe Flow PDF Generator</div>
                    <div class="panel-body">Select a CSV file to upload.</div>
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <br>
                    <input type="submit" value="Submit CSV File" name="submit">
            </form>
            <br>
                </div>

             </p></div></div>

</head>
</html>
