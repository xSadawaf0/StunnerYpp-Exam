<?php
session_start();
require_once 'database/controller.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Song CRUD</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">
        <h1>Song CRUD</h1>
        <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#addSongModal">Add Song</button>

            <br>
        <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="alert alert-info mt-3">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
            }
        ?>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Artist</th>
                    <th>Lyrics</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once 'database/controller.php';

                $songs = listSongs();
                while ($row = $songs->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['title'] . '</td>';
                    echo '<td>' . $row['artist'] . '</td>';
                    echo '<td>' . $row['lyrics'] . '</td>';
                    ?>
                    <td>
                        <form action="database/controller.php" method="POST">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm edit-btn" data-toggle="modal" data-target="#addSongModal" data-id="<?php echo $row['id']; ?>" data-title="<?php echo $row['title']; ?>" data-artist="<?php echo $row['artist']; ?>" data-lyrics="<?php echo $row['lyrics']; ?>">Edit</button>
                    </td>
                    <?php
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addSongModal" tabindex="-1" role="dialog" aria-labelledby="addSongModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSongModalLabel">Add Song</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addSongForm" action="database/controller.php" method="POST">
                        <div class="form-group">
                            <label for="addTitle">Title</label>
                            <input type="text" class="form-control" id="addTitle" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="addArtist">Artist</label>
                            <input type="text" class="form-control" id="addArtist" name="artist" required>
                        </div>
                        <div class="form-group">
                            <label for="addLyrics">Lyrics</label>
                            <textarea class="form-control" id="addLyrics" name="lyrics" rows="5" required></textarea>
                        </div>
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="id" id="songId" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="addSongForm" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                var artist = $(this).data('artist');
                var lyrics = $(this).data('lyrics');

                $('#addSongModalLabel').text('Edit Song');
                $('#addTitle').val(title);
                $('#addArtist').val(artist);
                $('#addLyrics').val(lyrics);
                $('#formAction').val('edit');
                $('#songId').val(id);
            });

            $('#addSongModal').on('hidden.bs.modal', function() {
                $('#addSongModalLabel').text('Add Song');
                $('#addTitle').val('');
                $('#addArtist').val('');
                $('#addLyrics').val('');
                $('#formAction').val('add');
                $('#songId').val('');
            });
        });
    </script>

</body>
</html>
