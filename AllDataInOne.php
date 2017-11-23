<?php 
session_start();
if(!(isset($_SESSION["loggedin"]))) {
  header("Location: index.php");
}
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
      <li> <a class="nav-link" href="Search Starcast.php" id="courses">Starcast</a></li>
    </ul>
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
    <ul class="navbar-nav col-1">
      <li> <a class="nav-link" href="logic.php?action=logout">Logout</a></li>
    </ul>

  </div>
</nav>
<br>
<div class="container col-3 container-fluid">
  <p class="h2">All Data</p>
</div>
<br>

<div>
  <input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Search for results.. ">

  <table class="table table-hover" id="myTable">
  <thead>
    <tr class="header">
      <th>Movie</th>
      <th>Year</th>
      <th>Released</th>
      <th>Genre</th>
      <th>Runtime</th>
      <th>ProductionHouse</th>
      <th>IMDBRating</th>
      <th>RottentomatoesRating</th>
      <th>MetacriticRating</th>
      <th>Director/s</th>
      <th>Writer/s</th>
      <th>Actor/s</th>
      <th>Country</th>
      <th>BoxOffice</th>
    </tr>
  </thead>
  <tbody class="table_data">
  </tbody>
  </table>
</div>
<script type="text/javascript" src="./js/jquery.min.js.download"></script>
    <script type="text/javascript" src="./js/popper.min.js.download"></script>
    <script type="text/javascript" src="./js/bootstrap.min.js.download"></script>
    <script type="text/javascript" src="./js/jquery.dataTables.min.js.download"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $.ajax({
      url: "logic.php?getdata=allData",
      data: "json",
      success: function(result){
        var data = JSON.parse(result);
        var str="";
        for(var i = 1; i<=Object.keys(data).length;i++){
          console.log(data[i]);
          str += "<tr>";
          str += "<td>"+data[i].movie_and_prod[0].title+"</td>";
          str += "<td>"+data[i].movie_and_prod[0].year+"</td>";
          str += "<td>"+data[i].movie_and_prod[0].released+"</td>";
          str += "<td>"+data[i].movie_and_prod[0].genre+"</td>";
          str += "<td>"+data[i].movie_and_prod[0].runtime+"</td>";
          str += "<td>"+data[i].movie_and_prod[0].name+"</td>";
          str += "<td>"+data[i].movie_and_prod[0].imdb_rating+"</td>";
          str += "<td>"+data[i].movie_and_prod[0].rottentomatoes_rating+"</td>";
          str += "<td>"+data[i].movie_and_prod[0].metacritic_rating+"</td>";
          str += "<td>"+data[i].director[0].name+"</td>";
          str += "<td>"+data[i].writer[0].name+"</td>";
          str += "<td>"+data[i].actor[0].name+"</td>";
          str += "<td>"+data[i].loc_and_boxoffice[0].countries+"</td>";
          str += "<td>"+data[i].loc_and_boxoffice[0].box_office+"</td>";
          str += "</tr>";
        }
        $(".table_data").html(str);
      }
    });
  });
      
function myFunction() {
  // Declare variables 
  var input, filter, table, tr, id, i,name;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    movie = tr[i].getElementsByTagName("td")[0];
    year = tr[i].getElementsByTagName("td")[1];
    released = tr[i].getElementsByTagName("td")[2];
    genre = tr[i].getElementsByTagName("td")[3];
    runtime = tr[i].getElementsByTagName("td")[4];
    ph = tr[i].getElementsByTagName("td")[5];
    imdb = tr[i].getElementsByTagName("td")[6];
    rt = tr[i].getElementsByTagName("td")[7];
    met = tr[i].getElementsByTagName("td")[8];
    dir = tr[i].getElementsByTagName("td")[9];
    wri = tr[i].getElementsByTagName("td")[10];
    act = tr[i].getElementsByTagName("td")[11];
    cnt = tr[i].getElementsByTagName("td")[12];
    boxoffice = tr[i].getElementsByTagName("td")[13];
    if (movie || year || released || genre || runtime || ph || imdb || rt || met || dir || wri || act || cnt || boxoffice) {
      if (movie.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }
      else if(year.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else if(released.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else if(genre.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else if(runtime.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else if(ph.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else if(imdb.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else if(rt.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else if(met.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else if(dir.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else if(wri.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else if(act.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else if(cnt.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else if(boxoffice.innerHTML.toUpperCase().indexOf(filter) > -1){
        tr[i].style.display = "";
      }
      else {
        tr[i].style.display = "none";
      }
    }
  }
}

    </script>