<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>iDiscuss - Coding Forums</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php include 'partials/_header.php'; ?>
    
    <?php
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `categories` WHERE category_id=$id";
    $result = mysqli_query($connection, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $catname = $row['category_name'];
        $catdesc = $row['category_description'];
    }
    ?>

    <?php
    $showAlert = false;
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            // Insert into thread db
            $th_title = $_POST['title'];
            $th_desc = $_POST['desc'];
            $th_postedby = $_POST['username'];
            $th_title = str_replace("<", "&lt;", $th_title);
            $th_title = str_replace(">", "&gt;", $th_title);
            $th_desc = str_replace("<", "&lt;", $th_desc);
            $th_desc = str_replace(">", "&gt;", $th_desc);
            $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_posted_by`, `thread_time`) VALUES ( '$th_title', '$th_desc', '$id', '$th_postedby', current_timestamp())";
            $result = mysqli_query($connection, $sql);
            $showAlert = true;
            if ($showAlert) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your thread has been added! Please wait for community to respond
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                  </div>';
            }
        }
    }
    ?>

    <!-- Category container starts here -->
    <div class="container my-4">
        <div class="jumbotron py-2">
            <h1 class="display-4"><small>Welcome to <?php echo $catname; ?> forums</h1>
            <p class="lead"> <?php echo $catdesc; ?></small></p>
            <hr class="my-4">
            <pre>
• This is a peer to peer forum. 
• No Spam / Advertising / Self-promote in the forums is not allowed. 
• Do not post copyright-infringing material. 
• Do not post “offensive” posts, links or images and Do not cross postquestions. 
• Remain respectful of other members at all times.</pre>
        </div>
    </div>

    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        echo '<div class="container">
            <h1 class="py-2">Start a Discussion</h1> 
            <form action="' . $_SERVER["REQUEST_URI"] . '" method="post">
                <div class="form-group">
                    <input type="hidden" name="username" value ="' . $_SESSION['username'] . '">
                </div>
                <div class="form-group">
                    <label for="title">Problem Title</label>
                    <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
                    <small id="emailHelp" class="form-text text-muted">Keep your title as short and crisp as
                    possible</small>
                </div>
                <div class="form-group">
                    <label for="desc">Ellaborate Your Concern</label>
                    <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>';
    } else {
        echo '
        <div class="container">
        <h1 class="py-2">Start a Discussion</h1> 
           <p class="lead"><em>You are not logged in. Please login to be able to start a Discussion</em></p>
        </div>
        ';
    }
    ?>

    <div class="container mb-5" style="min-height: 433px;">
        <h1 class="py-2">Browse Questions</h1>
        <?php
        $id = $_GET['catid'];
        $sql = "SELECT * FROM `threads` WHERE thread_cat_id=$id";
        $result = mysqli_query($connection, $sql);
        $noResult = true;
        while ($row = mysqli_fetch_assoc($result)) {
            $noResult = false;
            $id = $row['thread_id'];
            $title = $row['thread_title'];
            $desc = $row['thread_desc'];
            $th_postedby = $row['thread_posted_by'];
            $thread_time = $row['thread_time'];
            $time = new DateTime("$thread_time");
            $time->format('jS \of F Y h:i:s A');


            echo '<div class="media my-3">
            <img src="img/userdefault.png" width="54px" class="mr-3" alt="...">
            <div class="media-body">' .
                '<h5 class="mt-0"> <a class="text-dark" href="thread.php?threadid=' . $id . '">' . $title . ' </a></h5>
                ' . $desc . ' </div>' . '<div class="font-weight-bold my-0"> Asked by:- <em>' . $th_postedby . ' (' . $time->format('jS F \, Y \a\t h:i A') . '</em>)</div>' .
                '</div>';
        }
        // echo var_dump($noResult);
        if ($noResult) {
            echo '<div class="jumbotron jumbotron-fluid py-2">
                    <div class="container">
                        <p class="display-4"><small>No Threads Found</p>
                        <p class="lead"> Be the first person to ask a question</small></p>
                    </div>
                 </div> ';
        }
        ?>
    </div>

    <?php include 'partials/_footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>