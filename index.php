<?php

//Connect to the Database
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";
$insert = false;
$update = false;
$delete = false;

//Create a Connection
$conn = new mysqli($servername, $username, $password, $database);

//Die if connection is not successfull
if (!$conn) {
  die("Sorry we failed to connect --> " . mysqli_connect_error());
}

// Delete Button Working
if (isset($_GET['delete'])) {
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `mynotes` WHERE `sno` = $sno";
  $result = mysqli_query($conn, $sql);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['snoEdit'])) {
    //update the record
    $sno = $_POST["snoEdit"];
    $title = $_POST["titleEdit"];
    $description = $_POST["descriptionEdit"];

    $sql = "UPDATE `mynotes` SET `title` = '$title' , `description` = '$description' WHERE `mynotes`.`sno` = $sno";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      $update = true;
    }
  } else {
    $title = $_POST["title"];
    $description = $_POST["description"];

    $sql = "INSERT INTO `mynotes` (`title`,`description`) VALUES ('$title','$description')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      //echo "Record has been successfully inserted<br>";
      $insert = true;
    } else {
      echo "Record was not inserted successfully due to the error ---> " . mysqli_error($conn);
    }
  }
}
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/5.0.2/css/dataTables.dataTables.min.css">
  <title>My Notes</title>
</head>

<body>
  <!-- Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="/CRUD/index.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="mb-3">
              <label for="titleEdit" class="form-label">Note Title</label>
              <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="titleEdit">
            </div>
            <div class="mb-3">
              <label for="descriptionEdit" class="form-label">Description</label>
              <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer" d-block mr-auto>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><img src="https://cdn-icons-png.flaticon.com/512/8643/8643728.png" alt="" style="width: 70px;"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
        </ul>
        <form class="d-flex">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>

  <?php
  if ($insert) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert' mt-2>
  <strong>SUCCESS !!</strong> Your note has been inserted successfully.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>";
  }
  if ($delete) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert' mt-2>
  <strong>SUCCESS !!</strong> Your note has been deleted successfully.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>";
  }
  if ($update) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert' mt-2>
  <strong>SUCCESS !!</strong> Your note has been updated successfully.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>";
  }
  ?>

  <div class="container mt-5">
    <h2>Add a Note</h2>
    <form action="/CRUD/index.php" method="POST">
      <div class="mb-3">
        <label for="title" class="form-label">Note Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="title">
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>

  <div class="container my-4">
    <table class="table mt-4" id="myTable">
      <hr>
      <thead>
        <tr>
          <th scope="col">S.no</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM `mynotes`";
        $result = mysqli_query($conn, $sql);
        $sno = 0;
        while ($row = mysqli_fetch_assoc($result)) {
          $sno = $sno + 1;
          echo "<tr>
          <th scope='row'>" . $sno . "</th>
          <td>" . $row['title'] . "</td>
          <td>" . $row['description'] . "</td>
          <td><button class='edit btn btn-sm btn-primary' id=" . $row['sno'] . ">Edit</button> <button class='delete btn btn-sm btn-primary' id=d" . $row['sno'] . ">Delete</button></td>
        </tr>";
        }
        ?>
      </tbody><!--<button class='edit btn btn-sm btn-primary'>Edit</button>-->
    </table>
    <hr>
  </div><br><br>

  <!-- FOOTER -->

  <!-- Footer -->
  <footer class="text-center text-lg-start bg-body-tertiary text-muted">

    <!-- Section: Links  -->
    <section class="">
      <div class="container text-center text-md-start mt-5">
        <!-- Grid row -->
        <div class="row mt-3">
          <!-- Grid column -->
          <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
            <!-- Content -->
            <h6 class="text-uppercase fw-bold mb-4">
              <i class="fas fa-gem me-3"></i>Rahul Sharma
            </h6>
            <p>
             Hey this is a small database project created by me hope you liked it.
            </p>
          </div>
          <!-- Grid column -->

          <!-- Grid column -->
          <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold mb-4">
              Resources
            </h6>
            <p>
              <a href="https://en.wikipedia.org/wiki/HTML" class="text-reset">HTML</a>
            </p>
            <p>
              <a href="https://en.wikipedia.org/wiki/CSS" class="text-reset">CSS</a>
            </p>
            <p>
              <a href="https://www.javascript.com/" class="text-reset">JAVASCRIPT</a>
            </p>
            <p>
              <a href="https://www.php.net/" class="text-reset">PHP</a>
            </p>
            <p>
              <a href="https://getbootstrap.com/docs/5.0/getting-started/introduction/" class="text-reset">BOOTSTRAP</a>
            </p>
          </div>
          <!-- Grid column -->

          <!-- Grid column -->
          <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold mb-4">
              Social links
            </h6>
            <p>
              <a href="https://www.instagram.com/rahul.shrma.02/" class="text-reset"><img src="https://cdn4.iconfinder.com/data/icons/social-media-black-white-2/600/Instagram_glyph_svg-512.png" width="30px" alt=""></a>
            </p>
            <p>
              <a href="#!" class="text-reset"><img src="https://cdn3.iconfinder.com/data/icons/glypho-social-and-other-logos/64/logo-facebook-512.png" width="30px" alt=""></a>
            </p>
            <p>
              <a href="https://github.com/RahulSharma010" class="text-reset"><img src="https://cdn4.iconfinder.com/data/icons/basic-ui-symbols-vol-5/1024/github_app_software_mobile-512.png" width="30px" alt=""></a>
            </p>
            <p>
              <a href="https://www.linkedin.com/in/rahul-sharma-868840250/" class="text-reset"><img src="https://cdn3.iconfinder.com/data/icons/picons-social/57/11-linkedin-512.png" width="30px" alt=""></a>
            </p>
          </div>
          <!-- Grid column -->

          <!-- Grid column -->
          <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
            <p><i class="fas fa-home me-3"></i> Odisha, Bhubaneswar 751024</p>
            <p>
              <i class="fas fa-envelope me-3"></i>
              sharmarahul5279@gmail.com
            </p>
            <p>
              <a href="https://sharmarahul5279.wixsite.com/rahul-web" target="_blank" class="text-reset">Portfolio</a>
            </p>

          </div>
          <!-- Grid column -->
        </div>
        <!-- Grid row -->
      </div>
    </section>
    <!-- Section: Links  -->

    <!-- Copyright -->
    <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2021 Copyright:
      <a class="text-reset fw-bold" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
    <!-- Copyright -->
  </footer>
  <!-- Footer -->

  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
  <script>
    let table = new DataTable('#myTable');
  </script>
  <script>
    document.getElementById('myTable').addEventListener('click', function(e) {
      if (e.target.classList.contains('edit')) {
        // Edit button clicked
        let tr = e.target.closest('tr');
        let title = tr.getElementsByTagName('td')[0].innerText;
        let description = tr.getElementsByTagName('td')[1].innerText;
        let sno = e.target.id;

        // Update modal fields
        document.getElementById('titleEdit').value = title;
        document.getElementById('descriptionEdit').value = description;
        document.getElementById('snoEdit').value = sno;

        // Show the modal
        $('#editModal').modal('toggle');
      } else if (e.target.classList.contains('delete')) {
        // Delete button clicked
        let sno = e.target.id.substr(1);

        if (confirm('Are you sure you want to delete this note?')) {
          window.location = `/CRUD/index.php?delete=${sno}`;
        }
      }
    });
  </script>
  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>