<?php
session_start();
if(!(isset($_SESSION["loggedin"]))) {
  header("Location: index.php");
}
$servername = "13.126.21.209";
$username = "test_demo";
$password = "sankalp";
$dbname = "movie";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$genre_data = mysqli_query($conn,"select DISTINCT genre from movie");

$director_data = mysqli_query($conn,"select * from director");

$movie = mysqli_query($conn,"select * from movie");

?>


<!DOCTYPE html>
<!-- saved from url=(0095)https://dl2.pushbulletusercontent.com/gjr25Dxc96AOlpf8rzZR7Es4q9biAHYP/add_course_category.html -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><style id="stndz-style"></style>

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Stylesheet -->
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/font-awesome.min.css">
  <link rel="stylesheet" href="./css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="./css/navbar.css">

</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand col-3" href=""><img class="logo" style=""><b> Movie Management Information System</b></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse col-9" id="navbarNavDropdown">
      <ul class="navbar-nav col-1"></ul>
      <ul class="navbar-nav col-1">
        <li> <a class="nav-link" href="Search Starcast.php" id="starcast">Starcast</a></li>
      </ul>
      <ul class="navbar-nav col-1">
        <li> <a class="nav-link" href="Search Director.php" id="director">Director</a></li>
      </ul>
      <ul class="navbar-nav col-1">
        <li> <a class="nav-link" href="Search Movie.php" id="movieee">Movie</a></li>
      </ul>
      <ul class="navbar-nav col-2">
        <li> <a class="nav-link" href="Search Production House.php" id="productionHouse">Production House</a></li>
      </ul>
      <ul class="navbar-nav col-1">
        <li> <a class="nav-link" href="AllDataInOne.php" id="courses">Search</a></li>
      </ul>
      <ul class="navbar-nav col-1">
      <li> <a class="nav-link" href="logic.php?action=logout">Logout</a></li>
    </ul>
    </div>
  </nav>
  <br>
  <div class="container col-3 container-fluid">
    <p class="h2">Search Writer</p>
  </div>
  <br>
<div class="container col-8 container-fluid">

    <table class="table table-hover" id="myTable">
      <tbody><tr class="header">
        <th>Filter By</th>
        <th></th>
        <th>&nbsp;&nbsp;&nbsp;Value</th>
      </tr>
      <tr>
        <td>Genre (Category)</td>
        <td></td>
        <td>
          <div class="col-8">
            <div class="form-group">
              <select class="form-control" id="genre">
                <option>Select</option>
                <?php
                while (($row = $genre_data->fetch_assoc())) {
                  ?>
                  <option value="<?php echo $row['genre']; ?>"><?php echo $row['genre']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td>Director</td>
        <td></td>
        <td>
          <div class="col-8">
            <div class="form-group">
              <select class="form-control" id="directorr">
                <option>Select</option>
                <?php
                while (($row = $director_data->fetch_assoc())) {
                  ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td>Movie</td>
        <td></td>
        <td>
          <div class="col-8">
            <div class="form-group">
              <select class="form-control" id="movie">
                <option>Select</option>
                <?php
                while (($row = $movie->fetch_assoc())) {
                  ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
        </td>
      </tr>

    </tbody></table>
    <a type="button" class="btn btn-primary" id="search">Search</a>
    <br><br>
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Sr.</th>
          <th></th>
          <th></th>
          <th>Name</th>
        </tr>
      </thead>
      <tbody class="table_data">

      </tbody>
    </table>
  </div>
  <!-- Scripts -->
  <script type="text/javascript" src="./js/jquery.min.js.download"></script>

  <script type="text/javascript">
  $("#search").click(function() {

    if($("#genre").val()!="" || $("#director").val()!="" || $("#movie")!=""){

      $.ajax({
        url: "logic.php?getdata=writer&director="+$("#directorr").val()+"&genre="+$("#genre").val()+"&movie="+$("#movie").val(),
        data: "json",
        success: function(result){

          if(result){
            var json_res = jQuery.parseJSON(result);
            var html="";

            $.each( json_res, function( key, value ) {
              html+="<tr>";
              html=html+"<td>"+key+"</td>";
              html=html+"<td></td>";
              html=html+"<td></td>";
              html=html+"<td>"+value+"</td>";
              html+="</tr>";
            });
            // console.log(html);
            $(".table_data").html(html);
          }
        }
      });
    }
    else{
      alert("Please Select Atleast one of the following");
    }
  });
  </script>


</body></html>
