<?php

$servername = "35.154.150.123";
$username = "mis";
$password = "sankalp";
$dbname = "movie";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$director_data = mysqli_query($conn,"select * from director");

$production_house_data = mysqli_query($conn,"select * from production_house");

$genre_data = mysqli_query($conn,"select DISTINCT genre from movie");

?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><style id="stndz-style"></style>

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Stylesheet -->
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/font-awesome.min.css">
  <link rel="stylesheet" href="./css/jquery.dataTables.min.css">

</head>

  <body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand col-3" href=""><b> Movie Management Information System</b></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse col-9" id="navbarNavDropdown">
    <ul class="navbar-nav col-1"></ul>
    <ul class="navbar-nav col-1">
      <li> <a class="nav-link" href="Search Movie.php" id="courses">Movie</a></li>
    </ul>
    <ul class="navbar-nav col-1">
      <li> <a class="nav-link" href="Search Director.php" id="courses">Director</a></li>
    </ul>
    <ul class="navbar-nav col-2">
      <li> <a class="nav-link" href="Search Production House.php" id="courses">Production House</a></li>
    </ul>
    <ul class="navbar-nav col-1">
      <li> <a class="nav-link" href="Search Writer.php" id="courses">Writer</a></li>
    </ul>

  </div>
</nav>
<br>
<br>
    <div class="container col-8 container-fluid">

                      <table class="table table-hover">
                        <tbody><tr class="header">
                          <th>Filter By</th>
                          <th></th>
                          <th>&nbsp;&nbsp;&nbsp;Value</th>
                        </tr>
                        <tr>
                          <td>Director</td>
                          <td></td>
                          <td>
                            <div class="col-8">
                               <div class="form-group">
                                  <select class="form-control" id="director">
                                    <option >Select</option>
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
                          <td>Genre (Category)</td>
                          <td></td>
                          <td>
                              <div class="col-8">
                               <div class="form-group">
                                  <select class="form-control" id="genre">
                                    <option >Select</option>
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
                          <td>Production House</td>
                          <td></td>
                          <td>
                              <div class="col-8">
                               <div class="form-group">
                                  <select class="form-control" id="productionHouse">
                                    <option >Select</option>
                                    <?php
                                      while (($row = $production_house_data->fetch_assoc())) {
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
    <script type="text/javascript" src="./js/popper.min.js.download"></script>
    <script type="text/javascript" src="./js/bootstrap.min.js.download"></script>
    <script type="text/javascript" src="./js/jquery.dataTables.min.js.download"></script>
    <script type="text/javascript">
      $("#search").click(function() {
        if($("#director").val()!="" || $("#genre").val()!="" || $("#productionHouse").val()){
          $.ajax({
            url: "logic.php?getdata=starcast&director="+$("#director").val()+"&genre="+$("#genre").val()+"&productionhouse="+$("#productionHouse").val(),
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

</body>
</html>
