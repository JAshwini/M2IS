<?php
session_start();
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

if(isset($_GET['action']) && $_GET['action']=="login"){
	$res = mysqli_query($conn,'select * from user where username="'.$_GET["username"].'" and password = "'.$_GET['password'].'"');
	$result = $res->fetch_assoc();

	if($result){
		$_SESSION["loggedin"] = true;
		$_SESSION["loginerror"] = false;
		header("Location: AllDataInOne.php");
	}
	else{
		$_SESSION["loginerror"] = true;
		header("Location: index.php");
	}
}

if(isset($_GET['action']) && $_GET['action']=="logout"){
	session_destroy();
	header("Location: index.php");
}
else{

if($_GET['getdata']=="starcast"){
	$data = array();
	if($_GET['director']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select"){
		$result = mysqli_query($conn,'select * from actor where id in (SELECT actor_id from actors_in_movie where movie_id in (SELECT id from movie where id in (SELECT movie_id from directors_of_movie where director_id='.$_GET['director'].') && production_id='.$_GET['productionhouse'].' && genre="'.$_GET['genre'].'"))');
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

		$director_movie_count = [];
		$genre_movie_count = [];
		$production_house_movie_count = [];
		$i=1;
		while ($row = $result->fetch_assoc()) {
			$data["table"][$i]=$row["name"];

			if($_GET['director']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select") {

				$cntr = mysqli_query($conn,'SELECT COUNT(id) as cnt FROM movie WHERE id IN( SELECT a.movie_id FROM ( SELECT movie_id FROM directors_of_movie WHERE director_id = '.$_GET['director'].' ) AS a INNER JOIN( SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) AS b ON a.movie_id = b.movie_id )');

				array_push($director_movie_count,$cntr->fetch_assoc()["cnt"]);

				$cntr2 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) and genre = "'.$_GET['genre'].'"');

				array_push($genre_movie_count,$cntr2->fetch_assoc()["cnt"]);
				$cntr3 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) and production_id = '.$_GET['productionhouse']);
				array_push($production_house_movie_count,$cntr3->fetch_assoc()["cnt"]);
				$data["graph"]["director"] = $director_movie_count;
				$data["graph"]["genre"] = $genre_movie_count;
				$data["graph"]["productionhouse"] = $production_house_movie_count;
		}

		elseif ($_GET['director']=="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select") {
			$cntr2 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) and genre = "'.$_GET['genre'].'"');
			array_push($genre_movie_count,$cntr2->fetch_assoc()["cnt"]);

			$cntr3 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) and production_id = '.$_GET['productionhouse']);
			array_push($production_house_movie_count,$cntr3->fetch_assoc()["cnt"]);

			$data["graph"]["director"] = null;
			$data["graph"]["genre"] = $genre_movie_count;
			$data["graph"]["productionhouse"] = $production_house_movie_count;
		}

		elseif ($_GET['director']!="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']!="Select") {

			$cntr = mysqli_query($conn,'SELECT COUNT(id) as cnt FROM movie WHERE id IN( SELECT a.movie_id FROM ( SELECT movie_id FROM directors_of_movie WHERE director_id = '.$_GET['director'].' ) AS a INNER JOIN( SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) AS b ON a.movie_id = b.movie_id )');
			array_push($director_movie_count,$cntr->fetch_assoc()["cnt"]);

			$cntr3 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) and production_id = '.$_GET['productionhouse']);
			array_push($production_house_movie_count,$cntr3->fetch_assoc()["cnt"]);

			$data["graph"]["director"] = $director_movie_count;
			$data["graph"]["genre"] = null;
			$data["graph"]["productionhouse"] = $production_house_movie_count;
		}

		elseif ($_GET['director']!="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']=="Select") {
			$cntr = mysqli_query($conn,'SELECT COUNT(id) as cnt FROM movie WHERE id IN( SELECT a.movie_id FROM ( SELECT movie_id FROM directors_of_movie WHERE director_id = '.$_GET['director'].' ) AS a INNER JOIN( SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) AS b ON a.movie_id = b.movie_id )');
			array_push($director_movie_count,$cntr->fetch_assoc()["cnt"]);
			$data["graph"]["director"] = $director_movie_count;
			$data["graph"]["genre"] = null;
			$data["graph"]["productionhouse"] = null;
		}

		elseif ($_GET['director']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select") {

			$cntr = mysqli_query($conn,'SELECT COUNT(id) as cnt FROM movie WHERE id IN( SELECT a.movie_id FROM ( SELECT movie_id FROM directors_of_movie WHERE director_id = '.$_GET['director'].' ) AS a INNER JOIN( SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) AS b ON a.movie_id = b.movie_id )');
			array_push($director_movie_count,$cntr->fetch_assoc()["cnt"]);

			$cntr2 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) and genre = "'.$_GET['genre'].'"');
			array_push($genre_movie_count,$cntr2->fetch_assoc()["cnt"]);

			$data["graph"]["director"] = $director_movie_count;
			$data["graph"]["genre"] = $genre_movie_count;
			$data["graph"]["productionhouse"] = null;
		}

		elseif ($_GET['director']=="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select") {
			$cntr2 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) and genre = "'.$_GET['genre'].'"');
			array_push($genre_movie_count,$cntr2->fetch_assoc()["cnt"]);

			$data["graph"]["director"] = null;
			$data["graph"]["genre"] = $genre_movie_count;
			$data["graph"]["productionhouse"] = null;
		}

		elseif ($_GET['director']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select") {

			$cntr = mysqli_query($conn,'SELECT COUNT(id) as cnt FROM movie WHERE id IN( SELECT a.movie_id FROM ( SELECT movie_id FROM directors_of_movie WHERE director_id = '.$_GET['director'].' ) AS a INNER JOIN( SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) AS b ON a.movie_id = b.movie_id )');
			array_push($director_movie_count,$cntr->fetch_assoc()["cnt"]);

			$cntr2 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) and genre = "'.$_GET['genre'].'"');
			array_push($genre_movie_count,$cntr2->fetch_assoc()["cnt"]);
			$data["graph"]["director"] = $director_movie_count;
			$data["graph"]["genre"] = $genre_movie_count;
			$data["graph"]["productionhouse"] = null;
		}

		elseif ($_GET['director']=="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']!="Select") {

			$cntr3 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$row['id'].' ) and production_id = '.$_GET['productionhouse']);

			array_push($production_house_movie_count,$cntr3->fetch_assoc()["cnt"]);

			$data["graph"]["director"] = null;
			$data["graph"]["genre"] = null;
			$data["graph"]["productionhouse"] = $production_house_movie_count;
		}

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
	$starcast_movie_count = [];
	$genre_movie_count = [];
	$production_house_movie_count = [];
	$i=1;
	while ($row = $result->fetch_assoc()) {
		$data["table"][$i]=$row["name"];
		if($_GET['starcast']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select"){
			$cntr = mysqli_query($conn,'SELECT COUNT(id) as cnt FROM movie WHERE id IN( SELECT a.movie_id FROM ( SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$_GET['actor'].' ) AS a INNER JOIN( SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) AS b ON a.movie_id = b.movie_id )');
			array_push($starcast_movie_count,$cntr->fetch_assoc()["cnt"]);
			$cntr2 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) and genre = "'.$_GET['genre'].'"');
			array_push($genre_movie_count,$cntr2->fetch_assoc()["cnt"]);
			$cntr3 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) and production_id = '.$_GET['productionhouse']);
			array_push($production_house_movie_count,$cntr3->fetch_assoc()["cnt"]);
			$data["graph"]["starcast"] = $starcast_movie_count;
			$data["graph"]["genre"] = $genre_movie_count;
			$data["graph"]["productionhouse"] = $production_house_movie_count;
		}
		elseif($_GET['starcast']=="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']!="Select"){
			$cntr2 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) and genre = "'.$_GET['genre'].'"');
			array_push($genre_movie_count,$cntr2->fetch_assoc()["cnt"]);
			$cntr3 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) and production_id = '.$_GET['productionhouse']);
			array_push($production_house_movie_count,$cntr3->fetch_assoc()["cnt"]);
			$data["graph"]["startcast"] = null;
			$data["graph"]["genre"] = $genre_movie_count;
			$data["graph"]["productionhouse"] = $production_house_movie_count;
		}
		elseif ($_GET['starcast']!="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']!="Select") {
			$cntr = mysqli_query($conn,'SELECT COUNT(id) as cnt FROM movie WHERE id IN( SELECT a.movie_id FROM ( SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$_GET['actor'].' ) AS a INNER JOIN( SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) AS b ON a.movie_id = b.movie_id )');
			array_push($starcast_movie_count,$cntr->fetch_assoc()["cnt"]);
			$cntr3 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) and production_id = '.$_GET['productionhouse']);
			array_push($production_house_movie_count,$cntr3->fetch_assoc()["cnt"]);
			$data["graph"]["starcast"] = $starcast_movie_count;
			$data["graph"]["genre"] = null;
			$data["graph"]["productionhouse"] = $production_house_movie_count;
		}
		elseif ($_GET['starcast']!="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']=="Select") {
			$cntr = mysqli_query($conn,'SELECT COUNT(id) as cnt FROM movie WHERE id IN( SELECT a.movie_id FROM ( SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$_GET['starcast'].' ) AS a INNER JOIN( SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) AS b ON a.movie_id = b.movie_id )');
			array_push($starcast_movie_count,$cntr->fetch_assoc()["cnt"]);
			$data["graph"]["starcast"] = $starcast_movie_count;
			$data["graph"]["genre"] = null;
			$data["graph"]["productionhouse"] = null;
		}
		elseif ($_GET['starcast']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select") {
			$cntr = mysqli_query($conn,'SELECT COUNT(id) as cnt FROM movie WHERE id IN( SELECT a.movie_id FROM ( SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$_GET['starcast'].' ) AS a INNER JOIN( SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) AS b ON a.movie_id = b.movie_id )');
			array_push($starcast_movie_count,$cntr->fetch_assoc()["cnt"]);
			$cntr2 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) and genre = "'.$_GET['genre'].'"');
			array_push($genre_movie_count,$cntr2->fetch_assoc()["cnt"]);
			$data["graph"]["starcast"] = $starcast_movie_count;
			$data["graph"]["genre"] = $genre_movie_count;
			$data["graph"]["productionhouse"] = null;
		}
		elseif ($_GET['starcast']=="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select") {
			$cntr2 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) and genre = "'.$_GET['genre'].'"');
			array_push($genre_movie_count,$cntr2->fetch_assoc()["cnt"]);
			$data["graph"]["starcast"] = null;
			$data["graph"]["genre"] = $genre_movie_count;
			$data["graph"]["productionhouse"] = null;
		}
		elseif ($_GET['starcast']!="Select" && $_GET['genre']!="Select" && $_GET['productionhouse']=="Select") {
			$cntr = mysqli_query($conn,'SELECT COUNT(id) as cnt FROM movie WHERE id IN( SELECT a.movie_id FROM ( SELECT movie_id FROM actors_in_movie WHERE actor_id = '.$_GET['starcast'].' ) AS a INNER JOIN( SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) AS b ON a.movie_id = b.movie_id )');
			array_push($starcast_movie_count,$cntr->fetch_assoc()["cnt"]);
			$cntr2 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) and genre = "'.$_GET['genre'].'"');
			array_push($genre_movie_count,$cntr2->fetch_assoc()["cnt"]);
			$data["graph"]["starcast"] = $starcast_movie_count;
			$data["graph"]["genre"] = $genre_movie_count;
			$data["graph"]["productionhouse"] = null;
		}
		elseif ($_GET['starcast']=="Select" && $_GET['genre']=="Select" && $_GET['productionhouse']!="Select") {
			$cntr3 = mysqli_query($conn,'SELECT count(id) as cnt FROM movie where id in (SELECT movie_id FROM directors_of_movie WHERE director_id = '.$row['id'].' ) and production_id = '.$_GET['productionhouse']);
			array_push($production_house_movie_count,$cntr3->fetch_assoc()["cnt"]);
			$data["graph"]["starcast"] = null;
			$data["graph"]["genre"] = null;
			$data["graph"]["productionhouse"] = $production_house_movie_count;
		}
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


	if($_GET['getdata']=="allData"){
		$result = mysqli_query($conn,'select m.*,ph.name from movie m inner join production_house ph ON m.production_id	= ph.id');
		$data = array();
		$i = 1;
		while ($row = $result->fetch_assoc()) {

			//movie and production house name
			$data[$i]["movie_and_prod"] = array();
			array_push($data[$i]["movie_and_prod"], $row);


			//director data
			$dir_query = mysqli_query($conn,'select name from director where id in (select director_id from directors_of_movie where movie_id='.$row['id'].')');
			$data[$i]["director"] = array();
			while ($dir_row = $dir_query->fetch_assoc()) {
				array_push($data[$i]["director"], $dir_row);
			}


			//actor data
			$act_query = mysqli_query($conn,'select name from actor where id in (select actor_id from actors_in_movie where movie_id='.$row['id'].')');
			$data[$i]["actor"] = array();
			while ($act_row = $act_query->fetch_assoc()) {
				array_push($data[$i]["actor"], $act_row);
			}


			//writer data
			$writer_query = mysqli_query($conn,'select name from writer where id in (select writer_id from writers_of_movie where movie_id='.$row['id'].')');
			$data[$i]["writer"] = array();
			while ($writer_row = $writer_query->fetch_assoc()) {
				array_push($data[$i]["writer"], $writer_row);
			}

			//boxoffice and country data
			$loc_and_boxoffice_query = mysqli_query($conn,'select * from movie_location_and_box_office where movie_id='.$row['id']);
			$data[$i]["loc_and_boxoffice"] = array();
			while ($loc_and_boxoffice_row = $loc_and_boxoffice_query->fetch_assoc()) {
				array_push($data[$i]["loc_and_boxoffice"], $loc_and_boxoffice_row);
			}
			$i++;
		}
		echo json_encode($data);
	}
}
