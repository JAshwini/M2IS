<?php
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

if($_GET['getdata']=="starcast"){
	$data = array();
	if($_GET['director']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select"){
		$result = mysqli_query($conn,'select * from actor where id in (select actor_id from actors_in_movie where movie_id in (select id from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].') && production_id='.$_GET['productionhouse'].' && genre="'.$_GET['genre'].'"))');

	}
	elseif($_GET['director']=="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select"){
		$result = mysqli_query($conn,'select * from actor where id in (select actor_id from actors_in_movie where movie_id in (select id from movie where production_id='.$_GET['productionhouse'].' && genre="'.$_GET['genre'].'"))');
	}
	elseif ($_GET['director']!="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']!="Select") {
		$result = mysqli_query($conn,'select * from actor where id in (select actor_id from actors_in_movie where movie_id in (select id from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].') && production_id='.$_GET['productionhouse'].'))');
	}
	elseif ($_GET['director']!="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']=="Select") {
		$result = mysqli_query($conn,'select * from actor where id in (select actor_id from actors_in_movie where movie_id in (select id from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].')))');
	}
	elseif ($_GET['director']=="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']!="Select") {
		$result = mysqli_query($conn,'select * from actor where id in (select actor_id from actors_in_movie where movie_id in (select id from movie where production_id='.$_GET['productionhouse'].'))');
	}
	elseif ($_GET['director']=="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select") {
		$result = mysqli_query($conn,'select * from actor where id in (select actor_id from actors_in_movie where movie_id in (select id from movie where genre="'.$_GET['genre'].'"))');
	}
	elseif ($_GET['director']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select") {
		$result = mysqli_query($conn,'select * from actor where id in (select actor_id from actors_in_movie where movie_id in (select id from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].') && genre="'.$_GET['genre'].'"))');
	}
	$i=1;
	while ($row = $result->fetch_assoc()) {
		$data[$i]=$row["name"];
		$i++;
	}
	echo json_encode($data);
}

if($_GET['getdata']=="director"){
	$data = array();

	if($_GET['starcast']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select"){

		$result = mysqli_query($conn,'select * from director where id in (select director_id from directors_of_movie where movie_id in (select id from movie where id in (select movie_id from actors_in_movie where actor_id='.$_GET['starcast'].') && production_id='.$_GET['productionhouse'].' && genre="'.$_GET['genre'].'")');

	}
	elseif($_GET['starcast']=="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select"){
		$result = mysqli_query($conn,'select * from director where id in (select director_id from directors_of_movie where movie_id in (select id from movie where production_id='.$_GET['productionhouse'].' && genre="'.$_GET['genre'].'"))');
	}
	elseif ($_GET['starcast']!="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']!="Select") {
		$result = mysqli_query($conn,'select * from director where id in (select director_id from directors_of_movie where movie_id in (select id from movie where id in (select movie_id from actors_in_movie where actor_id='.$_GET['starcast'].') && production_id='.$_GET['productionhouse'].'))');
	}
	elseif ($_GET['starcast']!="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']=="Select") {
		$result = mysqli_query($conn,'select * from director where id in (select director_id from directors_of_movie where movie_id in (select id from movie where id in (select movie_id from actors_in_movie where actor_id='.$_GET['starcast'].')))');
	}
	elseif ($_GET['starcast']=="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']!="Select") {
		$result = mysqli_query($conn,'select * from director where id in (select director_id from directors_of_movie where movie_id in (select id from movie where production_id='.$_GET['productionhouse'].'))');
	}
	elseif ($_GET['starcast']=="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select") {
		$result = mysqli_query($conn,'select * from director where id in (select director_id from directors_of_movie where movie_id in (select id from movie where genre="'.$_GET['genre'].'"))');
	}
	elseif ($_GET['starcast']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select") {
		$result = mysqli_query($conn,'select * from director where id in (select director_id from directors_of_movie where movie_id in (select id from movie where id in (select movie_id from actors_in_movie where actor_id='.$_GET['starcast'].') && genre="'.$_GET['genre'].'"))');
	}
	$i=1;
	while ($row = $result->fetch_assoc()) {
		$data[$i]=$row["name"];
		$i++;
	}
	echo json_encode($data);
}


if($_GET['getdata']=="moviepanel"){
	$data = array();

	if($_GET['starcast']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select" && $_GET['director']!="Select"){

		$result = mysqli_query($conn,'select * from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].' && (select id from movie where id in (select movie_id from actors_in_movie where actor_id='.$_GET['starcast'].') && production_id='.$_GET['productionhouse'].' && genre="'.$_GET['genre'].'"))');

	}
	elseif($_GET['starcast']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select" && $_GET['director']=="Select"){
		$result = mysqli_query($conn,'select * from movie where id in (select movie_id from actors_in_movie where actor_id='.$_GET['starcast'].') && production_id='.$_GET['productionhouse'].' && genre="'.$_GET['genre'].'"');
	}
	elseif ($_GET['starcast']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select" && $_GET['director']!="Select") {
		$result = mysqli_query($conn,'select * from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].' && (select id from movie where id in (select movie_id from actors_in_movie where actor_id='.$_GET['starcast'].') && genre="'.$_GET['genre'].'"))');
	}
	elseif ($_GET['starcast']!="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']!="Select" && $_GET['director']!="Select") {
		$result = mysqli_query($conn,'select * from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].' && (select id from movie where id in (select movie_id from actors_in_movie where actor_id='.$_GET['starcast'].') && production_id='.$_GET['productionhouse'].')');
	}
	elseif ($_GET['starcast']=="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select" && $_GET['director']!="Select") {
		$result = mysqli_query($conn,'select * from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].' && (select id from movie where production_id='.$_GET['productionhouse'].' && genre="'.$_GET['genre'].'"))');
	}
	elseif ($_GET['starcast']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select" && $_GET['director']=="Select") {
		$result = mysqli_query($conn,'select * from movie where id in (select id from movie where id in (select movie_id from actors_in_movie where actor_id='.$_GET['starcast'].') && genre="'.$_GET['genre'].'")');
	}
	elseif ($_GET['starcast']!="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']=="Select" && $_GET['director']!="Select") {
		$result = mysqli_query($conn,'select * from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].' && (select id from movie where id in (select movie_id from actors_in_movie where actor_id='.$_GET['starcast'].')))');
	}
	elseif ($_GET['starcast']=="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']!="Select" && $_GET['director']!="Select") {
		$result = mysqli_query($conn,'select * from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].' && (select id from movie where production_id='.$_GET['productionhouse'].')');
	}
	elseif ($_GET['starcast']=="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select" && $_GET['director']!="Select") {
		$result = mysqli_query($conn,'select * from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].' && (select id from movie where genre="'.$_GET['genre'].'"))');
	}
	elseif ($_GET['starcast']!="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']!="Select" && $_GET['director']=="Select") {
		$result = mysqli_query($conn,'select * from movie where id in (select id from movie where id in (select movie_id from actors_in_movie where actor_id='.$_GET['starcast'].') && production_id='.$_GET['productionhouse'].')');
	}
	elseif ($_GET['starcast']=="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select" && $_GET['director']=="Select") {
		$result = mysqli_query($conn,'select * from movie where production_id='.$_GET['productionhouse'].' && genre="'.$_GET['genre'].'" ');
	}
	elseif ($_GET['starcast']=="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']=="Select" && $_GET['director']!="Select") {
		$result = mysqli_query($conn,'select * from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].')');
	}
	elseif ($_GET['starcast']=="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select" && $_GET['director']=="Select") {
		$result = mysqli_query($conn,'select * from movie where genre="'.$_GET['genre'].'")');
	}
	elseif ($_GET['starcast']!="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']=="Select" && $_GET['director']=="Select") {
		$result = mysqli_query($conn,'select * from movie where id in (select movie_id from actors_in_movie where actor_id='.$_GET['starcast'].')');
	}
	elseif ($_GET['starcast']=="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']!="Select" && $_GET['director']=="Select") {
		$result = mysqli_query($conn,'select * from movie where production_id='.$_GET['productionhouse'].'');
	}
	$i=1;
	while ($row = $result->fetch_assoc()) {
		$data[$i]=$row["title"];
		$i++;
	}
	echo json_encode($data);
}


if($_GET['getdata']=="writer"){
	$data = array();

	if($_GET['director']!="Select" && $_GET['genre']!="Select" && $_GET['movie']!="Select"){

		$result = mysqli_query($conn,'select * from writer where id in (select writer_id from writers_of_movie where movie_id in (select id from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].') && id='.$_GET['movie'].' && genre="'.$_GET['genre'].'")');

	}
	elseif($_GET['director']=="Select" && $_GET['genre']!="Select" && $_GET['movie']!="Select"){
		$result = mysqli_query($conn,'select * from writer where id in (select writer_id from writers_of_movie where movie_id in (select id from movie where id='.$_GET['movie'].' && genre="'.$_GET['genre'].'"))');
	}
	elseif ($_GET['director']!="Select" && $_GET['genre']=="Select" && $_GET['movie']!="Select") {
		$result = mysqli_query($conn,'select * from writer where id in (select writer_id from writers_of_movie where movie_id in (select id from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].') && production_id='.$_GET['movie'].'))');
	}
	elseif ($_GET['director']!="Select" && $_GET['genre']=="Select" && $_GET['movie']=="Select") {
		$result = mysqli_query($conn,'select * from writer where id in (select writer_id from writers_of_movie where movie_id in (select id from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].')))');
	}
	elseif ($_GET['director']=="Select" && $_GET['genre']=="Select" && $_GET['movie']!="Select") {
		$result = mysqli_query($conn,'select * from writer where id in (select writer_id from writers_of_movie where movie_id in (select id from movie where id='.$_GET['movie'].'))');
	}
	elseif ($_GET['director']=="Select" && $_GET['genre']!="Select" && $_GET['movie']=="Select") {
		$result = mysqli_query($conn,'select * from writer where id in (select writer_id from writers_of_movie where movie_id in (select id from movie where genre="'.$_GET['genre'].'"))');
	}
	elseif ($_GET['director']!="Select" && $_GET['genre']!="Select" && $_GET['movie']=="Select") {
		$result = mysqli_query($conn,'select * from writer where id in (select writer_id from writers_of_movie where movie_id in (select id from movie where id in (select movie_id from directors_of_movie where director_id='.$_GET['director'].')))');
	}
	$i=1;
	while ($row = $result->fetch_assoc()) {
		$data[$i]=$row["name"];
		$i++;
	}
	echo json_encode($data);
}

if($_GET['getdata']=="ProductionHouse"){
	$data = array();

	if($_GET['productionhouse']!="Select"){

		$result = mysqli_query($conn,'select * from movie where production_id ='.$_GET['productionhouse'].'');

	}
	$i=1;
	while ($row = $result->fetch_assoc()) {
		$data[$i] = $row["title"];
		$i++;
	}
	echo json_encode($data);
}
