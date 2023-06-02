<?php
require_once 'connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $title = $_POST['title'];
        $artist = $_POST['artist'];
        $lyrics = $_POST['lyrics'];
        addSong($title, $artist, $lyrics);
    } elseif ($_POST['action'] === 'delete') {
        $id = $_POST['id'];
        deleteSong($id);
    } elseif ($_POST['action'] === 'edit') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $artist = $_POST['artist'];
        $lyrics = $_POST['lyrics'];
        updateSong($id, $title, $artist, $lyrics);
    }
}

function listSongs(){
    global $conn;
    $sql = "SELECT * FROM songs WHERE active = 1";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error retrieving songs: " . $conn->error);
    }

    return $result;
}


function addSong($title, $artist, $lyrics)
{
    global $conn;
    $sql = "INSERT INTO songs (title, artist, lyrics, active) VALUES ('$title', '$artist', '$lyrics', 1)";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "New entry created successfully";
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    
    header("Location: ../index.php");
    exit();
}

function updateSong($id, $title = null, $artist = null, $lyrics = null) {
    global $conn;
    $sql = "UPDATE songs SET ";
    $updates = array();

    if ($title !== null) {
        $updates[] = "title='$title'";
    }
    if ($artist !== null) {
        $updates[] = "artist='$artist'";
    }
    if ($lyrics !== null) {
        $updates[] = "lyrics='$lyrics'";
    }

    $sql .= implode(', ', $updates) . " WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Entry updated successfully";
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    
    header("Location: ../index.php");
    exit();
}

function deleteSong($id) {
    global $conn;
    $sql = "UPDATE songs SET active = 0 WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Entry deleted successfully";
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    
    header("Location: ../index.php");
    exit();
}
?>
